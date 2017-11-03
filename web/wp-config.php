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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'dev');

/** MySQL database password */
define('DB_PASSWORD', 'dev');

/** MySQL hostname */
define('DB_HOST', 'wordpress_mysql');

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
define('AUTH_KEY',         '797GsmMF`}RfCMU~I{A-q8.7yIm8g|*$i0&H61|=YYKBg{+08JrH!jg?]WDk)@:i');
define('SECURE_AUTH_KEY',  'y,`|boBeC?Ag.,Bj9FNh6(zXn2v</~y];x[J}%{ ;(TN?SW5_XzAnDchFW,Pv4-o');
define('LOGGED_IN_KEY',    '~Q_SiOv9Ezw9aBqK]voh?}H`tK;@W$)Qi!#y!S*n%AuBI*P@Z2gxKH9~N{ =)+oz');
define('NONCE_KEY',        'BK?8%NT%)n}g=WdUK}F.P&p[2fWb$e<SOP1lz#$<1,0sRJ$&99^&i,|]R**b(+mx');
define('AUTH_SALT',        'zOAA&@yK5)sq{Nl5PXznTPj@%IVl6:0XG69[!)e>WoflPi&ve=~`]DLOr;k?6Cmk');
define('SECURE_AUTH_SALT', 'e7%c5s!9-$.S0XYG/d1VJoMWpY)n<sJB=t`N8@I,EKC_84A$b:E7]Gl^YJ.b(ePr');
define('LOGGED_IN_SALT',   'kPUAp85):]gAC8<5)f8dZj<^S]wh)!#=1Zsn=^n7iS|gGJ@<,pvI&F{rz.q.Cr `');
define('NONCE_SALT',       '(G`k7^z:(rKF0^7pC2[m8s@QwzdJBDstbt;ws.Ox}T</XEs{j=ms#PuTM^F,q+&M');

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
