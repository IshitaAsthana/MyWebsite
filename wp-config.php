<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'my_database' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
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
define( 'AUTH_KEY',         ';`vG=Q.4Y9%ATK|SQC]C5E+62s>06mGXCVjb237koD-(RoKO_6w&|ewW65) 5DD8' );
define( 'SECURE_AUTH_KEY',  ')=.P$_EG>5Pvshc>r&Zb4RQR<D1DWkjou+@+m~AW._CYa_8~(glzTL9F[9OPhkm)' );
define( 'LOGGED_IN_KEY',    'Rm;2.SkUT)sh1EJq|:RUXyIfbqB<B&y64peIT$UD=Fz{9eA=3_EVr/=5RYS1-;_~' );
define( 'NONCE_KEY',        'RIb(./[aEcRCj;u~/>;jQLS.X-g}CY%,#u@DvBPI&G|K-9!xv5*mC@L|a,J$B8xI' );
define( 'AUTH_SALT',        'o7tqT#T?v-g({p<}w}ina})`NJ1gBv4h*tZ)_$&D*<s(oGI6<et}zzWoM3ufKZN&' );
define( 'SECURE_AUTH_SALT', '%0?#*S WJM~/,%.U(w2]Gr2f?*)v%aZ!ha^@k5YYM;J<U2)lpJ7+uy>uE@$CnE+#' );
define( 'LOGGED_IN_SALT',   'S[th0kW>l1-u!GR??X1BT+-qF,4TG@0 7rMP6I7>sBB}WX5([ID%SrbG?;sGR7Rq' );
define( 'NONCE_SALT',       '3Jp5C`4>(kBml~`6D8<eZN#EBFH}&5?EnX*.UYkn5I1@]G:O_W;*?Q};DUvcCl>s' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
//define( 'WP_DEBUG', false );

// Turns WordPress debugging on
define('WP_DEBUG', true);

// Tells WordPress to log everything to the /wp-content/debug.log file
define('WP_DEBUG_LOG', true);

// Doesn�t force the PHP 'display_errors' variable to be on
define('WP_DEBUG_DISPLAY', true);

// Hides errors from being displayed on-screen
@ini_set('display_errors', 0);


/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
