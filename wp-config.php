<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'staging_indigenetech_ca' );

/** Database username */
define( 'DB_USER', 'staging_indigenetech_ca' );

/** Database password */
define( 'DB_PASSWORD', 'uFc0K3SMoEh5S9r2' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '-7kVYyR^c[@1oUX:d>y88gAGAv8DNKVY10f~uWHB7l*:$/;3U>lH?LJDHZ)=uBu*' );
define( 'SECURE_AUTH_KEY',  'f6fe~MNgR_K ctnjA|G7)kAAF=lnmf]Y48fp<0OWT4GD,of5D::1|v!qIGi-S$Hl' );
define( 'LOGGED_IN_KEY',    '}+xwa9{es;oCHK{@%}Eroixo@uh(Q+?=zlH sK1.~7fT!,)zKIA*!_Ghf&+M9O5G' );
define( 'NONCE_KEY',        't!O;uWE2;4PZP$8}_6&[ ?bfU}xww~(tC9EJzFt7X^,`&q~&FEU ,Z7l``h{HptZ' );
define( 'AUTH_SALT',        'FPJMr]potE,R+IqnFZPlbkw3p2Pi+V1/(+B&lMYuyd*1H6jRK68|+}sd&CU|EhMq' );
define( 'SECURE_AUTH_SALT', '||sS{N@m=@{S3bV]^l&*-GPZ)A@.ZNjwZlSXEuXK1dIegMCeiw)d.EFQXWXvuv?c' );
define( 'LOGGED_IN_SALT',   '{Aicv{Q2+m&(},_!OO;,D@un5DLoNw@vm7~DiZ8B&:O,FEFg%mm 2{[}a>{Gc7#L' );
define( 'NONCE_SALT',       'IcjHzW`AZn@m^0Apzj6a>{1h~0:_W$Y|[ptBW`$]n]F-P5^[eK0`,XQUHbv%Ac=x' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );
define('WP_MEMORY_LIMIT', '256M');
define('FS_METHOD', 'direct');
define('WP_POST_REVISIONS', 10);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress didrectory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
