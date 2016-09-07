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
define('DB_NAME', 'sdm_proof');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         'KH#j*,5/6U8*x>9!k/|Y(1EgZZ1gitN:dyWz^nq0`qUlk[7k?Yf$UzR)MUJ4 gaE');
define('SECURE_AUTH_KEY',  'Q_Et?RZ$B(5%$iRr`l:mk,2#LlPx[eEXKhYcf~3In;qY9Xk<td{T~aEr+8xZ~2X2');
define('LOGGED_IN_KEY',    'Tg$1CfMHV$??iWz;Fbba8?6e@z 06` uvo?!fNi9$>s#8! HMvx2LQ~dGT8DQ2yz');
define('NONCE_KEY',        'IK=3.vXUOU2qYIQqp+mM_G*9)&33ru5~}VHWV/K,(l{xnC)VmW2aeo=&Y^`/~j|1');
define('AUTH_SALT',        'rt^UioC#Y(IpxKSLj.-f7NooeHbY,l8)XV4`]h:b]T}|.7ejl l#(=m0Jd~z^z,O');
define('SECURE_AUTH_SALT', '{5T$HkHtWY6M`yL~p+N^!^vv/8+2_#:EbOl&17P-B>_TS[|3:z!bW&N+IKmWp<xX');
define('LOGGED_IN_SALT',   'bb*k(|f/=Z$Rp<iR?}Zp2T2#yN}_F;-pw`y,Vau>;wS.b[%`Me1L/6(Ejr{R Iq3');
define('NONCE_SALT',       'X&q29W_ns-EsT`V N-$Ghs9$8bma!_Hz[8Yx)DB!B.%Mh:o.oAW{Hsb)-vkpkY!F');

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
