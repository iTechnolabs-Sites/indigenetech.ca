# indigenetech.ca

WordPress site for indigenetech.ca.

- WordPress core 7.0.2 (tracked in this repo)
- Themes: `indigenetech` (parent) and `indigenetech-child` (site customizations: homepage, blog, schema)
- Plugins: Elementor, ElementsKit Lite, Header Footer Elementor, Rank Math SEO, SVG Support, Call Now Button
- Runtime: `wordpress:php8.2-apache` image (see `Dockerfile`)

## Configuration

`wp-config.php` contains **no secrets**. It reads settings from, in order:

1. `wp-config-local.php` (optional, git-ignored). Any constant you `define()` there wins.
2. Environment variables. `NAME_FILE` is also supported for Docker secrets.
3. Non-secret defaults.

Required: `DB_NAME`, `DB_HOST`, `DB_USER`, `DB_PASSWORD` and the 8 keys/salts (`AUTH_KEY`, `SECURE_AUTH_KEY`,
`LOGGED_IN_KEY`, `NONCE_KEY`, `AUTH_SALT`, `SECURE_AUTH_SALT`, `LOGGED_IN_SALT`, `NONCE_SALT`).
If one is missing, the site stops with `Configuration error: <NAME> is not set`.

Optional: `WP_TABLE_PREFIX`
(default `wp_`), `WP_HOME`, `WP_SITEURL`, `WP_DEBUG`, `WP_MEMORY_LIMIT`, `WP_POST_REVISIONS`.

### Production / server

Either set the variables above in the container or hosting environment, or create
`wp-config-local.php` next to `wp-config.php` on the server:

```php
<?php
define( 'DB_NAME', '...' );
define( 'DB_HOST', '...' );
define( 'DB_USER', '...' );
define( 'DB_PASSWORD', '...' );
// Paste the block from https://api.wordpress.org/secret-key/1.1/salt/
```

## Local development

```bash
cp .env.example .env   # then fill in values
docker compose up --build
```

The site runs at http://localhost:8080. To start with real content, put a database dump
(`*.sql` or `*.sql.gz`) in `db-init/` before the first `docker compose up`. If the database
volume already exists, run `docker compose down -v` first. Dumps are git-ignored.
