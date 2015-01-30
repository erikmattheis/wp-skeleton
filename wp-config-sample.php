<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */


/**
 * Setup the dev, staging, and production environments
 */
$urlParts = explode( '.', $_SERVER['SERVER_NAME'] );
if ( $urlParts[0] == 'dev' || $urlParts[0] == 'test' || $urlParts[0] == 'beta') {
	/**
	 * DEV
	 */
	define( 'WP_STAGE', $urlParts[0] );
	//define( 'WP_HOME', 'http://' . WP_STAGE . '.' . $project['domain'] );

	// Show errors
	define( 'WP_DEBUG', true );
} elseif ( $urlParts[0] == 'stage' || $urlParts[0] == 'staging' ) {

	// Hide errors
	define( 'WP_DEBUG', false );
} else {
	/**
	 * PRODUCTION
	 */
	define( 'WP_STAGE', 'production' );

	// Hide errors
	define( 'WP_DEBUG', false );
}

/**
 * Misc. Settings
 ** Limit Post Revision to 8
 */
define( 'WP_POST_REVISIONS', 8 );

define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/../content' );

/**
 * Debug settings
 */
if ( WP_DEBUG == true ) {
	ini_set( 'display_errors', '1' );
	define( 'WP_DEBUG_DISPLAY', true );
} else {
	ini_set( 'display_errors', '0' );
	define( 'WP_DEBUG_DISPLAY', false );
}

/**
 * Salts, for security
 */
if ( file_exists( dirname( __FILE__ ) . '/./../config/wp-salts.php' ) ) {
	include dirname( __FILE__ ) . '/./../config/wp-salts.php';
} else {
	trigger_error( 'There is no config/wp-salts.php file for the ' . strip_tags( $_SERVER['SERVER_NAME'] ) . ' site.' , E_USER_WARNING );
}

/**
 * Language (leave blank for American English)
 */
define( 'WPLANG', '' );

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'database_name_here');

/** MySQL database username */
define('DB_USER', 'username_here');

/** MySQL database password */
define('DB_PASSWORD', 'password_here');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/* That's all, stop editing! Happy blogging. */

/**
 * You almost certainly do not want to change these
 */
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
