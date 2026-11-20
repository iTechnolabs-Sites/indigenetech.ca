#!/usr/bin/env bash
# Deploy the audit-remediation theme changes to STAGING from a checkout of this repo.
#
#   SITE_ROOT=/var/www/staging SITE_URL=https://staging.indigenetech.ca bash deploy/staging/deploy.sh
#
# What it changes on the server (nothing else, wp-config.php is never touched):
#   wp-content/themes/indigenetech-child/   replaced (files removed in the repo are deleted)
#   wp-content/themes/indigenetech/style.css
#   .htaccess                                caching block added/updated at the top
# Steps: preflight -> backup -> deploy -> Elementor cache flush -> checks -> PASS/FAIL.
# Options: DRY_RUN=1 (show what would change, change nothing), SKIP_CHECKS=1,
#          CURL_EXTRA="--resolve staging.indigenetech.ca:443:127.0.0.1" (bypass Cloudflare for checks).
set -euo pipefail

say()  { echo "== $*"; }
die()  { echo "FAIL  $*"; echo "RESULT: FAIL (nothing was changed)"; exit 1; }

: "${SITE_ROOT:?Set SITE_ROOT to the staging WordPress folder (the one with wp-config.php)}"
: "${SITE_URL:?Set SITE_URL, e.g. https://staging.indigenetech.ca}"
SITE_ROOT="${SITE_ROOT%/}"; SITE_URL="${SITE_URL%/}"
REPO="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
SRC_CHILD="$REPO/wp-content/themes/indigenetech-child"
SRC_PARENT_CSS="$REPO/wp-content/themes/indigenetech/style.css"
DST_CHILD="$SITE_ROOT/wp-content/themes/indigenetech-child"
DST_PARENT_CSS="$SITE_ROOT/wp-content/themes/indigenetech/style.css"
STAMP=$(date +%Y%m%d-%H%M%S)
BACKUP_DIR="${BACKUP_BASE:-$HOME/indigenetech-backups}/$STAMP"

# ---------------- preflight ----------------
say "Preflight"
case "$SITE_URL" in
  *://indigenetech.ca*|*://www.indigenetech.ca*) [ "${ALLOW_LIVE:-}" = "yes-i-am-sure" ] || die "SITE_URL is the LIVE site. This script is for staging.";;
esac
for t in rsync curl tar awk php; do command -v "$t" >/dev/null || die "missing tool: $t"; done
[ -f "$SITE_ROOT/wp-config.php" ] || [ -f "$(dirname "$SITE_ROOT")/wp-config.php" ] || die "$SITE_ROOT does not look like WordPress (no wp-config.php)"
[ -d "$DST_CHILD" ] || die "$DST_CHILD not found"
[ -f "$DST_PARENT_CSS" ] || die "$DST_PARENT_CSS not found"
HT_BLOCK="$REPO/deploy/staging/htaccess-caching.conf"
[ -d "$SRC_CHILD" ] && [ -f "$SRC_PARENT_CSS" ] && grep -q 'END IndigeneTech static asset caching' "$HT_BLOCK" 2>/dev/null || die "repo checkout incomplete: $REPO"
COMMIT=$(git -C "$REPO" rev-parse HEAD 2>/dev/null || echo unknown)
echo "   source commit: $COMMIT ($(git -C "$REPO" rev-parse --abbrev-ref HEAD 2>/dev/null || echo '?'))"
echo "   site root:     $SITE_ROOT"
echo "   site url:      $SITE_URL"
lint=0; while IFS= read -r -d '' f; do php -l "$f" >/dev/null 2>&1 || { echo "   lint error: $f"; lint=1; }; done < <(find "$SRC_CHILD" -name '*.php' -print0)
[ "$lint" -eq 0 ] || die "PHP syntax errors in the source theme"
echo "   PHP syntax of source theme: OK"
pre=$(curl -sS --max-time 30 -A 'Mozilla/5.0 indigenetech-deploy-check' ${CURL_EXTRA:-} -o /dev/null -w '%{http_code}' "$SITE_URL/" || echo 000)
echo "   staging homepage before deploy: HTTP $pre"
[ "$pre" = 200 ] || echo "WARN  homepage is not 200 before deploying; checks may fail for reasons unrelated to this change"
OWNER=$(stat -c '%U:%G' "$DST_CHILD")
echo "   theme owner: $OWNER"

CHANGES=$(rsync -rlc --delete --dry-run --itemize-changes "$SRC_CHILD/" "$DST_CHILD/")
if [ -z "$CHANGES" ]; then
  NOTE="re-run: theme already matched $COMMIT before this deploy (not the original theme)"
  echo "   child theme already matches this commit (re-run)"
else
  NOTE="pre-deploy theme (as it was before $COMMIT)"
  echo "   files that will change in the child theme:"; echo "$CHANGES" | sed 's/^/     /' | head -80
fi
if [ "${DRY_RUN:-}" = 1 ]; then echo "DRY_RUN=1: stopping before any change."; exit 0; fi

# ---------------- backup ----------------
say "Backup -> $BACKUP_DIR"
mkdir -p "$BACKUP_DIR"
tar -czf "$BACKUP_DIR/files.tgz" -C "$SITE_ROOT" wp-content/themes/indigenetech-child wp-content/themes/indigenetech/style.css $( [ -f "$SITE_ROOT/.htaccess" ] && echo .htaccess )
tar -tzf "$BACKUP_DIR/files.tgz" >/dev/null || die "backup archive is unreadable"
cp "$REPO/deploy/staging/rollback.sh" "$BACKUP_DIR/rollback.sh"   # rollback works even after the clone is deleted
{ echo "site_root=$SITE_ROOT"; echo "site_url=$SITE_URL"; echo "owner=$OWNER"; echo "deployed_commit=$COMMIT"; echo "had_htaccess=$([ -f "$SITE_ROOT/.htaccess" ] && echo yes || echo no)"; echo "note=$NOTE"; } > "$BACKUP_DIR/meta"
echo "   $(tar -tzf "$BACKUP_DIR/files.tgz" | wc -l) entries, $(du -h "$BACKUP_DIR/files.tgz" | cut -f1)"
LOG="$SITE_ROOT/wp-content/debug.log"; LOG_MARK=$( [ -f "$LOG" ] && stat -c %s "$LOG" || echo 0 ); export LOG_MARK

# ---------------- deploy ----------------
say "Deploy"
# functions.php first: the new templates call functions defined in it.
cp "$SRC_CHILD/functions.php" "$DST_CHILD/functions.php"
rsync -rlc --delete "$SRC_CHILD/" "$DST_CHILD/"
cp "$SRC_PARENT_CSS" "$DST_PARENT_CSS"
BLOCK=$(cat "$HT_BLOCK")
HT="$SITE_ROOT/.htaccess"; touch "$HT"
awk '/# BEGIN IndigeneTech static asset caching/{skip=1} !skip{print} /# END IndigeneTech static asset caching/{skip=0}' "$HT" > "$HT.new"
{ printf '%s\n\n' "$BLOCK"; cat "$HT.new"; } > "$HT.tmp" && rm -f "$HT.new" && cat "$HT.tmp" > "$HT" && rm -f "$HT.tmp"
if [ "$(id -u)" = 0 ]; then chown -R "$OWNER" "$DST_CHILD" "$DST_PARENT_CSS"; chown "$OWNER" "$HT" 2>/dev/null || true; fi
echo "   child theme synced, parent style.css copied, .htaccess caching block in place"
# PHP OPcache can mix old and new files for a few seconds; retry before judging.
home() { curl -sS --max-time 30 -A 'Mozilla/5.0 indigenetech-deploy-check' ${CURL_EXTRA:-} -o /dev/null -w '%{http_code}' "$SITE_URL/?_chk=$RANDOM" || echo 000; }
for i in 1 2 3 4 5 6; do code=$(home); [ "$code" = 200 ] && break; sleep 3; done
echo "   homepage after deploy: HTTP $code"
if [ "$code" != 200 ]; then
  echo "WARN  homepage not 200 after 18 s; restoring the previous .htaccess to rule it out"
  tar -xzf "$BACKUP_DIR/files.tgz" -C "$SITE_ROOT" .htaccess 2>/dev/null || true
  sleep 3; code=$(home); echo "   homepage with previous .htaccess: HTTP $code"
  [ "$code" = 200 ] && echo "FAIL  the caching block breaks this server's .htaccess (check mod_headers / AllowOverride); old .htaccess kept"
fi

# ---------------- caches ----------------
say "Caches"
# These theme changes do not affect Elementor's generated CSS, so it is NOT
# flushed by default: on a server where PHP cannot rewrite
# uploads/elementor/css, a flush leaves every page without its Elementor styles.
if command -v wp >/dev/null; then
  WP=(wp --path="$SITE_ROOT"); [ "$(id -u)" = 0 ] && WP+=(--allow-root)
  "${WP[@]}" cache flush 2>&1 | sed 's/^/   /' || true
  if [ "${FLUSH_ELEMENTOR:-}" = 1 ]; then "${WP[@]}" elementor flush-css 2>&1 | sed 's/^/   /' || true; fi
fi
echo "   If staging is behind Cloudflare with caching, purge it now."

# ---------------- checks ----------------
if [ "${SKIP_CHECKS:-}" != 1 ]; then
  say "Checks"
  echo "   waiting 5 s for PHP OPcache to pick up the changed files"
  echo "   (if pages still show the old theme and opcache.validate_timestamps=0, reload PHP-FPM and run checks.sh again)"
  sleep 5
  set +e
  SITE_URL="$SITE_URL" SITE_ROOT="$SITE_ROOT" bash "$REPO/deploy/staging/checks.sh"
  rc=$?
  set -e
else rc=0; echo "SKIP_CHECKS=1: checks skipped"; fi

echo
echo "Deployed commit $COMMIT to $SITE_ROOT"
echo "Backup: $BACKUP_DIR"
echo "Rollback: SITE_ROOT=$SITE_ROOT SITE_URL=$SITE_URL bash $BACKUP_DIR/rollback.sh $BACKUP_DIR"
if [ "$rc" -eq 0 ]; then echo "DEPLOY RESULT: PASS"; else echo "DEPLOY RESULT: FAIL (see FAIL lines above; roll back with the command above if the site is affected)"; fi
exit "$rc"
