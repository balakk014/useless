<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress_test');

/** MySQL database username */
define('DB_USER', 'svnuser');

/** MySQL database password */
define('DB_PASSWORD', 'pXc9nbmrnCnpXhza');

/** MySQL hostname */
define('DB_HOST', '192.168.1.243');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/** FTP **/
define('FS_METHOD','direct');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '&9Y<%KL=UM8WtM3zu5p8w7N#q$s[wQYf^Hr!N/78_lzkJd]wbX`2xf*h[*mzr75]');
define('SECURE_AUTH_KEY',  '?7<fWO@T`!|aJC]<>OuOJ*o[A+EjXQ2gyvog$w,pOSat2D0RtC5j~k}vZ&1Z=,bj');
define('LOGGED_IN_KEY',    '%kx*w8m6*f5WD,zAm2 5WMFYG)`DOODPB`EDoz0,+@7#Z98&)A9Q/:}s/P6z:.mF');
define('NONCE_KEY',        'w>MIGjM-o3rLfXK4o-.ukA,,4I;pQrP/l`x7]EfWpyMx{1RISGn~1|? w~1X(B7a');
define('AUTH_SALT',        'E@oF%l}G0+P]TQgzMlw]3g/vz{]}76-lQ?Ux8~mkO]!0l_@o5tM,6vWhs[7l^k__');
define('SECURE_AUTH_SALT', 'mQ9XHNhjm#WUGu!<-ahh{^3,-*@PsS/K[kplHQ^c/]?qowIMQ8;]q8$U(BAtQnMS');
define('LOGGED_IN_SALT',   'D#W-8M;Q/SrRarC>/yu;i[QblW(@* z/YxeA/F>uXd#_^h=UxEG V$Ha3[f%UPBT');
define('NONCE_SALT',       ':f;+SD7QYhl.SWHKUR5y>-p)n>6`EaHwDE=y&AsP)xo)z!9OZxMa|hX~lqv<l=H^');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
