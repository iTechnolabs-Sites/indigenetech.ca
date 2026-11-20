# Staging deploy scripts

| Script | What it does |
|---|---|
| `deploy.sh` | Backup, deploy the child theme + parent `style.css` + `.htaccess` caching block, flush the WordPress object cache, run `checks.sh`. Never touches `wp-config.php`. Refuses the live URL. |
| `checks.sh` | Read-only checks; prints PASS/WARN/FAIL and `RESULT: PASS|FAIL`. |
| `rollback.sh <backup folder>` | Restores a backup (saves the current state first). Without an argument it lists the backups. |

Elementor's generated CSS is not flushed unless `FLUSH_ELEMENTOR=1` is set: the theme changes don't affect it, and on a server where PHP cannot rewrite `wp-content/uploads/elementor/css/`, a flush leaves every page without its Elementor styles.

## "stylesheets do not load" for `uploads/elementor/css/post-*.css`

Elementor's CSS cache was cleared and Elementor could not write the files again. Pages lose their Elementor styling (headings, buttons, backgrounds).

Diagnose (read-only):

```bash
cd "$SITE_ROOT"
ls -ld wp-content/uploads wp-content/uploads/elementor wp-content/uploads/elementor/css
ls -la wp-content/uploads/elementor/css | head
ps -eo user,comm | grep -E 'php-fpm|php-cgi|lsphp|apache2|httpd' | sort -u
wp eval 'print_r(wp_upload_dir());'
wp option get elementor_css_print_method
```

Look for: the `css` folder not writable by the PHP user from `ps`, or `basedir` pointing somewhere other than `$SITE_ROOT/wp-content/uploads`.

Fix the cause, then load any page once so Elementor writes the files again:

- PHP user cannot write: make `wp-content/uploads/elementor` (and `css`) owned by, or group-writable for, the PHP user.
- Wrong `basedir`: correct the `upload_path` / `upload_url_path` options (`wp option get upload_path`).

Immediate workaround that needs no files (staging only; it makes Elementor print its CSS inline in each page):

```bash
wp option update elementor_css_print_method internal && wp elementor flush-css
```

Undo with `wp option update elementor_css_print_method external` once the folder is writable.

## SEO content changes (`seo-content.php`)

Applies the approved items in `docs/seo-proposals-2026-11-20.md` (all except S3 share image and L3 privacy policy, which need material from the owner). Run with WP-CLI as the site's file owner:

```bash
wp --path="$SITE_ROOT" eval-file deploy/staging/seo-content.php            # dry run
wp --path="$SITE_ROOT" eval-file deploy/staging/seo-content.php apply      # back up + apply
wp --path="$SITE_ROOT" eval-file deploy/staging/seo-content.php revert ~/indigenetech-backups/seo-content-<time>.json
```

- Each item is changed only if its current value is what the proposal saw (or empty); otherwise `WARN ... left unchanged`.
- `ALREADY` means the value is already as proposed (staging already has the Site Title and the Organization name).
- On a re-run, content items print `WARN ... nothing to change`: they were done the first time.
- Every changed value is written to the backup JSON before it changes (the file is updated after each item), and `revert` restores them exactly.
- Schema type changes SC1/SC3 are in the theme (`functions.php`), not in this script.
