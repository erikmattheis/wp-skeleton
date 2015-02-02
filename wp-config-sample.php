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

/*REMOVE1
define( 'WP_ALLOW_MULTISITE', true );
REMOVE1*/
/*REMOVE2
define( 'SUNRISE', 'on' );
define( 'MULTISITE', true );
define( 'SUBDOMAIN_INSTALL', true );
define( 'PATH_CURRENT_SITE', '/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );
REMOVE2*/

/**
* Language (leave blank for American English)
*/
define( 'WPLANG', '' );

/**
 * Setup the dev, staging, and production environments
 */
$urlParts = explode( '.', $_SERVER['SERVER_NAME'] );
if ( $urlParts[0] == 'dev') {
  /**
   * Environment
   */
  define( 'GSN_ENV', $urlParts[0] );

  // Show errors
  define( 'WP_DEBUG', true );

  // define root site
  define( 'DOMAIN_CURRENT_SITE', 'localhost' );
} elseif ( $urlParts[0] == 'test' || $urlParts[0] == 'beta' || $urlParts[0] == 'stage' || $urlParts[0] == 'staging' ) {
  /**
  * Environment
  */
  define( 'GSN_ENV', $urlParts[0] );

  // Hide errors
  define( 'WP_DEBUG', true );

  // dynamic root site
  define( 'DOMAIN_CURRENT_SITE',  ''.$_SERVER['HTTP_HOST'].'' );
} else {
  /**
  * Production
  */
  define( 'GSN_ENV', 'prod' );

  // Hide errors
  define( 'WP_DEBUG', false );

/*REMOVE2
  // define root site
  define('DOMAIN_CURRENT_SITE', 'wpa.gsngrocers.com');
REMOVE2*/
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
* custom config if any
*/
if ( file_exists( dirname( __FILE__ ) . '/../my-config.php' ) ) {
  include dirname( __FILE__ ) . '/../my-config.php';
} else {

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'database_name_here');

/** MySQL database username */
define('DB_USER', 'username_here');

/** MySQL database password */
define('DB_PASSWORD', 'password_here');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');

/**#@-*/

}

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * Cache Key Salt
 * https://wordpress.org/support/topic/many-wp-installations-on-the-same-server
 */

define('WP_CACHE_KEY_SALT', md5(DB_NAME . __FILE__));

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
  define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
