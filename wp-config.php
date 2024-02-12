<?php
define( 'WP_CACHE', true );
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', '' );

/** Database username */
define( 'DB_USER', '' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'xojpziiupyc5swdy8gxrndzagvvy389cdssxm0wynzo8affk3tajh5l8xjurvt8p' );
define( 'SECURE_AUTH_KEY',  'lbeaooxlnbazmwlhdgkbblemaronl175zqptarso69gjhpdcxwzxlo7k8wlypi3l' );
define( 'LOGGED_IN_KEY',    'ni0v7bg4kxacsljcizhk4yyqq2rywigukcm7gwcyazguoimodvkxa0ptz6jvgacz' );
define( 'NONCE_KEY',        '4omwsiq4mmm7ftp8o9hsugv8gk7bbfsmnh1l3lfcyrmbphgbj9uodxhpxqroxsfh' );
define( 'AUTH_SALT',        'jz5qcjuckmnvre8exe3luxdyrl7agtryqoxylaqtszpzwtvn22xzyexkwpgfcnjt' );
define( 'SECURE_AUTH_SALT', 'fjhjanigjpvxzk1ggabvjrckpmhzornuwyqw2y06hahchb3qq4sp4d7zljbsqapx' );
define( 'LOGGED_IN_SALT',   'dwhqx8tbcj65jepatfpuwgtoizviseh8qjtvqndtznqorde0rpwi5ak5d1tgqbqr' );
define( 'NONCE_SALT',       'yvnsa9huownlk2pn949prqulbvuo6diu93h9nalz63nmzbtcr84i7wokzdpjxpos' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpgw_';

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
