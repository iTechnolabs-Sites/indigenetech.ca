#!/usr/bin/env bash
# Post-deploy checks for the audit-remediation theme changes.
# Usage: SITE_URL=https://staging.indigenetech.ca SITE_ROOT=/path/to/site bash checks.sh
# Prints PASS / WARN / FAIL lines and a final "RESULT: PASS" or "RESULT: FAIL".
# Exit code 0 = pass, 1 = fail. Read-only: it never changes the site or submits forms.
set -uo pipefail

: "${SITE_URL:?Set SITE_URL, e.g. https://staging.indigenetech.ca}"
SITE_URL="${SITE_URL%/}"
SITE_ROOT="${SITE_ROOT:-}"
LIVE_HOST_RE='https?://(www\.)?indigenetech\.ca'
UA='Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 indigenetech-deploy-check'
CURL=(curl -sS --max-time 30 -A "$UA")
[ -n "${CURL_EXTRA:-}" ] && CURL+=($CURL_EXTRA)   # e.g. CURL_EXTRA="--resolve staging.indigenetech.ca:443:127.0.0.1"
TMP=$(mktemp -d)
trap 'rm -rf "$TMP"' EXIT

pass=0; fail=0; warn=0
PASS() { echo "PASS  $*"; pass=$((pass+1)); }
FAIL() { echo "FAIL  $*"; fail=$((fail+1)); }
WARN() { echo "WARN  $*"; warn=$((warn+1)); }
count() { grep -oE "$1" "$2" 2>/dev/null | wc -l | tr -d ' '; }   # occurrences of regex in file
bust() { case "$1" in *\?*) echo "$1&_chk=$RANDOM";; *) echo "$1?_chk=$RANDOM";; esac; }

echo "== Checks against $SITE_URL ($(date -u +%FT%TZ))"

# ---------- page list: the sitemap pages (Rank Math), falling back to the audited 25 ----------
PATHS=()
# PAGES="/ /about-us/" overrides the sitemap (used for testing)
if [ -n "${PAGES:-}" ]; then read -r -a PATHS <<< "$PAGES"; else
for sm in page-sitemap.xml post-sitemap.xml; do
  while IFS= read -r loc; do
    p=$(echo "$loc" | sed -E "s#^$LIVE_HOST_RE##; s#^https?://[^/]+##")
    [ -n "$p" ] && PATHS+=("$p")
  done < <("${CURL[@]}" "$SITE_URL/$sm" 2>/dev/null | grep -oE '<loc>[^<]+' | sed 's/<loc>//' | grep -v '/wp-content/')
done
if [ "${#PATHS[@]}" -lt 5 ]; then
  WARN "sitemap not readable; using the audited page list"
  PATHS=(/ /about-us/ /contact-us/ /blog/ /ai-ml-development/ /app-development/ /blockchain-solutions/ /cloud-solutions/ /custom-vr-development/ /enterprise-custom-software-development/ /erp-solutions/ /it-consulting/ /it-staff-augmentation/ /web-development/ /blog/digital-tools-empowering-indigenous-communities-entrepreneurs/ /blog/guide-to-indigenous-owned-and-operated-businesses/ /blog/guide-to-mobile-app-development/ /blog/how-certified-indigenous-business-teams-build-enterprise-software/ /blog/how-indigenous-owned-businesses-can-build-scalable-mobile-apps/ /blog/how-technology-is-helping-indigenous-businesses-grow/ /blog/how-to-showcase-your-indigenous-business-to-a-global-market/ /blog/indigenetech-joins-ccib/ /blog/indigenous-owned-and-operated-tech-businesses/ /blog/indigenous-owned-business/ /blog/indigenous-tech-solutions-shaping-the-future-of-innovation/)
fi
fi
mapfile -t PATHS < <(printf '%s\n' "${PATHS[@]}" | awk '!seen[$0]++')
if [ -n "${EXTRA_PAGES+x}" ]; then read -r -a EXTRA <<< "$EXTRA_PAGES"; else EXTRA=(/blog/page/2/ /blog/category/uncategorized/ "/?s=app"); fi
echo "   ${#PATHS[@]} sitemap pages + ${#EXTRA[@]} extra templates"

# ---------- per-page checks ----------
page_fail=0; links_file="$TMP/links.txt"; : > "$links_file"
check_page() {  # $1 path, $2 expected status
  local p="$1" want="$2" f="$TMP/page.html" code issues=()
  code=$("${CURL[@]}" -o "$f" -w '%{http_code}' "$(bust "$SITE_URL$p")" || echo 000)
  [ "$code" = "$want" ] || issues+=("HTTP $code (expected $want)")
  if [ "$code" = "200" ] || [ "$code" = "404" ]; then
    grep -qiE 'Attention Required! \| Cloudflare|cf-chl-|Just a moment\.\.\.' "$f" && issues+=("Cloudflare challenge page: rerun with CURL_EXTRA to hit the origin")
    grep -qE '<b>(Fatal error|Parse error|Warning|Notice|Deprecated)</b>:|Fatal error: |Configuration error:' "$f" && issues+=("PHP error text in page")
    [ "$(count '<!doctype html|<!DOCTYPE html' "$f")" = 1 ] || issues+=("doctype count $(count '<!doctype html|<!DOCTYPE html' "$f")")
    [ "$(count "href=[\"']#[\"']" "$f")" = 0 ] || issues+=("$(count "href=[\"']#[\"']" "$f") href=\"#\" links")
    [ "$(count "href=[\"']javascript:" "$f")" = 0 ] || issues+=("javascript: links")
    grep -q 'indigenetech-child/assets/js/itech-site.js' "$f" || issues+=("itech-site.js not loaded")
    [ "$(count 'id="itechPopupOverlay"' "$f")" = 1 ] || issues+=("popup markup count $(count 'id="itechPopupOverlay"' "$f") (expected 1)")
    for bad in 'owl\.carousel' "id=['\"]fontawesome-4-5-css" 'font-awesome/7\.3\.1' 'cdn\.jsdelivr\.net' 'wp-emoji' 'magnific-popup' 'ekit-header-search' 'ekit-header-offcanvas' 'elementor-gf-local-roboto' 'fonts\.googleapis\.com/css\?family=Montserrat' "id=['\"]hfe-social-share-icons" "id=['\"]hfe-nav-menu-icons" 'indigenetech-child/style\.css' 'forms/embed/v2\.js' 'hbspt\.forms\.create' 'class="fa-solid'; do
      [ "$(count "$bad" "$f")" = 0 ] || issues+=("still present: $bad")
    done
    if [ "$p" = "/" ]; then
      grep -q 'assets/vendor/slick/slick.min.js' "$f" || issues+=("homepage: self-hosted Slick not loaded")
      grep -q 'itech-newhome-css' "$f" || issues+=("homepage: newhome.css missing")
      [ "$(count '<button type="button" class="custom-faq-question"' "$f")" -ge 1 ] || issues+=("homepage: FAQ buttons missing")
      for bad in "id=['\"]ekit-tab-js" "id=['\"]wp-block-library-css"; do [ "$(count "$bad" "$f")" = 0 ] || issues+=("homepage still loads $bad"); done
    else
      [ "$(count 'itech-newhome-css' "$f")" = 0 ] || issues+=("newhome.css loaded outside the homepage")
    fi
    # same-site links for the redirect check
    grep -oE "href=[\"'][^\"'#]+[\"']" "$f" | sed -E "s/^href=[\"']//; s/[\"']$//" \
      | grep -E "^/[^/]|^${SITE_URL//./\\.}" | grep -vE '/wp-(content|includes|json|admin)|/feed/|\.(xml|php|png|jpe?g|webp|svg|pdf|css|js)(\?|$)|^/cdn-cgi/' >> "$links_file" || true
  fi
  if [ "${#issues[@]}" -eq 0 ]; then echo "   ok    $p"; else echo "   FAIL  $p: ${issues[*]}"; page_fail=$((page_fail+1)); fi
}
for p in "${PATHS[@]}" "${EXTRA[@]}"; do check_page "$p" 200; done
check_page "/this-page-does-not-exist-$RANDOM/" 404
total=$(( ${#PATHS[@]} + ${#EXTRA[@]} + 1 ))
if [ "$page_fail" -eq 0 ]; then PASS "all $total pages: status, no PHP errors, no placeholder links, old assets gone, new assets present"; else FAIL "$page_fail of $total pages have problems (see lines above)"; fi

# ---------- internal links must not redirect ----------
sort -u "$links_file" -o "$links_file"
redir=0; n=0
while IFS= read -r l; do
  [ -z "$l" ] && continue; n=$((n+1))
  case "$l" in /*) u="$SITE_URL$l";; *) u="$l";; esac
  c=$("${CURL[@]}" -o /dev/null -w '%{http_code}' "$u" || echo 000)
  if [ "$c" != 200 ]; then echo "   $c  $l"; redir=$((redir+1)); fi
done < "$links_file"
if [ "$redir" -eq 0 ]; then PASS "$n internal links all return 200 (no redirects)"; else FAIL "$redir of $n internal links do not return 200 (listed above)"; fi

# ---------- theme files on the server ----------
status_of() { "${CURL[@]}" -o /dev/null -w '%{http_code}' "$1" || echo 000; }
T="$SITE_URL/wp-content/themes/indigenetech-child"
[ "$(status_of "$T/assets/vendor/slick/slick.min.js")" = 200 ] && PASS "new file served: assets/vendor/slick/slick.min.js" || FAIL "assets/vendor/slick/slick.min.js not served"
if "${CURL[@]}" "$(bust "$T/assets/js/itech-site.js")" | grep -q 'IntersectionObserver'; then PASS "itech-site.js is the new version"; else FAIL "itech-site.js is missing or old"; fi
gone=0; for old in assets/js/slider.js assets/js/appzend-custom.js assets/library/owlcarousel/js/owl.carousel.min.js assets/js/functions.php template-parts/content-download.php; do
  c=$(status_of "$T/$old"); [ "$c" = 404 ] || [ "$c" = 403 ] || { echo "   still served ($c): $old"; gone=$((gone+1)); }
done
[ "$gone" -eq 0 ] && PASS "removed files are gone" || FAIL "$gone removed files are still served (deploy without --delete?)"

# ---------- caching headers ----------
cc=$("${CURL[@]}" -I "$T/assets/css/common.css?ver=check" | tr -d '\r' | grep -i '^cache-control:' | head -1)
if echo "$cc" | grep -q 'max-age=31536000'; then PASS "versioned CSS cached for a year ($cc)"
else WARN "versioned CSS cache header is '${cc:-none}'. If Cloudflare shows max-age=14400, set Browser Cache TTL to 'Respect existing headers'; if Apache, check mod_headers/AllowOverride"; fi

# ---------- server-side checks (only when run on the server) ----------
if [ -n "$SITE_ROOT" ] && [ -d "$SITE_ROOT/wp-content/themes/indigenetech-child" ]; then
  if command -v php >/dev/null; then
    lint=0; while IFS= read -r -d '' f; do php -l "$f" >/dev/null 2>&1 || { echo "   lint error: $f"; lint=$((lint+1)); }; done < <(find "$SITE_ROOT/wp-content/themes/indigenetech-child" -name '*.php' -print0)
    [ "$lint" -eq 0 ] && PASS "PHP syntax OK in the child theme" || FAIL "$lint PHP files with syntax errors"
  else WARN "php CLI not found; skipped PHP lint"; fi
  log="$SITE_ROOT/wp-content/debug.log"
  if [ -n "${LOG_MARK:-}" ] && [ -f "$log" ]; then
    new=$(tail -c +"$((LOG_MARK+1))" "$log" 2>/dev/null | grep -E 'PHP (Fatal|Warning|Parse)' | grep -E 'themes/indigenetech' || true)
    if [ -z "$new" ]; then PASS "no new PHP warnings/errors from the theme in debug.log"; else FAIL "new PHP errors in debug.log:"; echo "$new" | tail -20 | sed 's/^/   /'; fi
  fi
fi

echo
echo "Summary: $pass passed, $warn warnings, $fail failed"
if [ "$fail" -eq 0 ]; then echo "RESULT: PASS"; exit 0; else echo "RESULT: FAIL"; exit 1; fi
