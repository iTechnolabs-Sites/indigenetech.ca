#!/usr/bin/env bash
# Restore the theme files and .htaccess saved by deploy.sh.
#
#   SITE_ROOT=/var/www/staging SITE_URL=https://staging.indigenetech.ca bash deploy/staging/rollback.sh <backup dir>
#
# With no argument it lists the backups instead of guessing.
# Before restoring it saves the current state too, so a rollback can itself be undone.
set -euo pipefail
: "${SITE_ROOT:?Set SITE_ROOT}"; : "${SITE_URL:?Set SITE_URL}"
SITE_ROOT="${SITE_ROOT%/}"; SITE_URL="${SITE_URL%/}"
BASE="${BACKUP_BASE:-$HOME/indigenetech-backups}"
if [ -z "${1:-}" ]; then
  echo "Usage: rollback.sh <backup folder>. Backups on this server (oldest first):"
  for d in $(ls -1d "$BASE"/*/ 2>/dev/null | sort); do echo "  ${d%/}   $(grep -h '^note=' "$d/meta" 2>/dev/null | cut -d= -f2-)"; done
  echo "Pass the one taken before the FIRST deploy to get back to the original theme."
  exit 1
fi
B="${1%/}"
[ -f "$B/files.tgz" ] || { echo "FAIL  no backup at '$B'"; echo "ROLLBACK RESULT: FAIL"; exit 1; }
. <(grep -E '^(owner|had_htaccess|site_root)=' "$B/meta")
[ "$site_root" = "$SITE_ROOT" ] || { echo "FAIL  backup was taken from $site_root, not $SITE_ROOT"; echo "ROLLBACK RESULT: FAIL"; exit 1; }

echo "== Rolling back $SITE_ROOT from $B"
SAVE="$BASE/pre-rollback-$(date +%Y%m%d-%H%M%S)"; mkdir -p "$SAVE"
tar -czf "$SAVE/files.tgz" -C "$SITE_ROOT" wp-content/themes/indigenetech-child wp-content/themes/indigenetech/style.css $( [ -f "$SITE_ROOT/.htaccess" ] && echo .htaccess )
cp "$B/meta" "$SAVE/meta"
echo "   current state saved to $SAVE"

rm -rf "$SITE_ROOT/wp-content/themes/indigenetech-child"
tar -xzf "$B/files.tgz" -C "$SITE_ROOT"
[ "$had_htaccess" = no ] && rm -f "$SITE_ROOT/.htaccess"
[ "$(id -u)" = 0 ] && chown -R "$owner" "$SITE_ROOT/wp-content/themes/indigenetech-child" "$SITE_ROOT/wp-content/themes/indigenetech/style.css" && { [ -f "$SITE_ROOT/.htaccess" ] && chown "$owner" "$SITE_ROOT/.htaccess" || true; }
echo "   files restored"
if command -v wp >/dev/null; then
  WP=(wp --path="$SITE_ROOT"); [ "$(id -u)" = 0 ] && WP+=(--allow-root)
  "${WP[@]}" cache flush >/dev/null 2>&1 && echo "   WordPress object cache flushed" || true
fi

echo "== Quick checks"
fail=0
for p in / /about-us/ /contact-us/ /ai-ml-development/ /blog/; do
  f=$(mktemp); c=$(curl -sS --max-time 30 -A 'Mozilla/5.0 indigenetech-deploy-check' ${CURL_EXTRA:-} -o "$f" -w '%{http_code}' "$SITE_URL$p?_chk=$RANDOM" || echo 000)
  if [ "$c" = 200 ] && ! grep -qE '<b>(Fatal error|Parse error)</b>|Fatal error: ' "$f"; then echo "PASS  $p 200"; else echo "FAIL  $p HTTP $c"; fail=1; fi
  rm -f "$f"
done
if [ "$fail" = 0 ]; then echo "ROLLBACK RESULT: PASS"; else echo "ROLLBACK RESULT: FAIL"; exit 1; fi
