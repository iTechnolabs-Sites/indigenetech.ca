<?php
/**
 * WordPress configuration for indigenetech.ca
 *
 * No secrets live in this file. Values are read, in order of priority, from:
 *   1. wp-config-local.php (optional, git-ignored) - may define() any constant below.
 *   2. Environment variables (Docker / hosting panel / .env via docker-compose).
 *   3. Non-secret defaults below.
 *
 * Database credentials and all 8 auth keys/salts MUST be supplied via 1 or 2.
 * Generate new keys/salts at https://api.wordpress.org/secret-key/1.1/salt/
 *
 * @package WordPress
 */

if ( file_exists( __DIR__ . '/wp-config-local.php' ) ) {
	require_once __DIR__ . '/wp-config-local.php';
}

/**
 * Read an environment variable (also supports Docker secrets via NAME_FILE).
 */
function indigenetech_env( $name, $default = null ) {
	$file = getenv( $name . '_FILE' );
	if ( $file && is_readable( $file ) ) {
		return rtrim( file_get_contents( $file ), "\r\n" );
	}
	$value = getenv( $name );
	return ( false === $value || '' === $value ) ? $default : $value;
}

/**
 * Define a constant from the environment unless already defined.
 * Required constants with no value stop the site with a clear error.
 */
function indigenetech_define( $name, $default = null, $required = false ) {
	if ( defined( $name ) ) {
		return;
	}
	$value = indigenetech_env( $name, $default );
	if ( null === $value ) {
		if ( $required ) {
			header( 'HTTP/1.1 500 Internal Server Error' );
			exit( 'Configuration error: ' . $name . ' is not set. See README.md.' );
		}
		return;
	}
	define( $name, $value );
}

// ** Database settings ** //
indigenetech_define( 'DB_NAME', 'indigenetech' );
indigenetech_define( 'DB_USER', null, true );
indigenetech_define( 'DB_PASSWORD', null, true );
indigenetech_define( 'DB_HOST', 'localhost' );
indigenetech_define( 'DB_CHARSET', 'utf8mb4' );
indigenetech_define( 'DB_COLLATE', '' );

// ** Authentication unique keys and salts ** //
foreach ( array(
	'AUTH_KEY',
	'SECURE_AUTH_KEY',
	'LOGGED_IN_KEY',
	'NONCE_KEY',
	'AUTH_SALT',
	'SECURE_AUTH_SALT',
	'LOGGED_IN_SALT',
	'NONCE_SALT',
) as $indigenetech_key ) {
	indigenetech_define( $indigenetech_key, null, true );
}
unset( $indigenetech_key );

// ** Database table prefix ** //
$table_prefix = indigenetech_env( 'WP_TABLE_PREFIX', 'wp_' );

// ** Optional URL overrides (useful for local/staging copies) ** //
indigenetech_define( 'WP_HOME' );
indigenetech_define( 'WP_SITEURL' );

// ** Behaviour ** //
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', filter_var( indigenetech_env( 'WP_DEBUG', 'false' ), FILTER_VALIDATE_BOOLEAN ) );
}
indigenetech_define( 'WP_MEMORY_LIMIT', '256M' );
indigenetech_define( 'FS_METHOD', 'direct' );
if ( ! defined( 'WP_POST_REVISIONS' ) ) {
	define( 'WP_POST_REVISIONS', (int) indigenetech_env( 'WP_POST_REVISIONS', 10 ) );
}

// Honour HTTPS when running behind a reverse proxy / load balancer.
if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && 'https' === $_SERVER['HTTP_X_FORWARDED_PROTO'] ) {
	$_SERVER['HTTPS'] = 'on';
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
