<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_CACHE', true); //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/var/www/edu.anabim.org/public_html/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'edu');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'dataanava');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Pp<I42exu_~U}{z^x2jf$VY/rI6B-C@fx|-7-]Z:*={j)-[k{-@JKTyNv rd#IRv');
define('SECURE_AUTH_KEY',  '2g%w.a_)364C!/kPw3ktsb?,{&Q/@fZ@+ss;|QOL=g]x-+}|bRi+WbN#~hGa0?k{');
define('LOGGED_IN_KEY',    'h4!}wB4-EfPwHza*PdW3.N=0>[uc<%HCmAIPs35hM<JC}j)?F}6[|-{;F@^586os');
define('NONCE_KEY',        'F#KuI1TU}#syM0*WBX-7!knvxg-WMZmX[p.Y@j.iw0RKcFAqB`+O(+vY2pIa-Dw.');
define('AUTH_SALT',        '|6pngk#|^ld`:H)@kH$c7}; 8h}|WsQvoQCS}_~Xv>-`?K7+atX>/ <`nqXK +xm');
define('SECURE_AUTH_SALT', 'e7#{ s8{H*n~dUYhOmro/-hsm@z9-C&`C)o)?$k89}S~%OY-_ySSf&|-$|a8C*[}');
define('LOGGED_IN_SALT',   'FxSbw|qX<*G9{!;GXT_FEUksUi3Cg1v25;ny8#Xr+Fa&S3@Vw(Me$otG@1F5s_im');
define('NONCE_SALT',       '!y||Ga.J$Uy-!aky1fFAk^f~M(LK{s.Wt,V:7TQU#zTVyOpCI20Z6LU%&5Enfncc');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

// log php errors
@ini_set('log_errors','On'); // enable or disable php error logging (use 'On' or 'Off')
@ini_set('display_errors','Off'); // enable or disable public display of errors (use 'On' or 'Off')
@ini_set('error_log','/var/www/edu.anabim.org/public_html/php-errors.log'); // path to server-writable log file

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
