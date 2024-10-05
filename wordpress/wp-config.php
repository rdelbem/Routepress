<?php

// Database configuration
define('DB_NAME', getenv('TEST_DB_NAME') ?: 'wp_test');
define('DB_USER', getenv('TEST_DB_USER') ?: 'root');
define('DB_PASSWORD', getenv('TEST_DB_PASSWORD') ?: 'root');
define('DB_HOST', getenv('TEST_DB_HOST') ?: 'db:3306');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

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
define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );

$table_prefix = 'wp_';

// Debugging mode
define('WP_DEBUG', true);

// Set the WordPress directory path
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/vendor/johnpbloch/wordpress/');
}

// Include WordPress settings
require_once 'wp-settings.php';
