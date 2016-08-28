<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'proofconsulting_com');

/** MySQL database username */
define('DB_USER', 'proofconsultingc');

/** MySQL database password */
define('DB_PASSWORD', 'CzTi8vEe');

/** MySQL hostname */
define('DB_HOST', 'mysql.proofconsulting.com');

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
define('AUTH_KEY',         '&ElimQyq;fG_3F:zQ;!zm$aXFle/#Jqw6+j0A*lLdXFb^L7;*KXK^7NGdr)w|fLM');
define('SECURE_AUTH_KEY',  '38Lhzh80k&CXNU9O1iy(PX*O*m&q^u*)!Q)B^cL+M20?T^T_NAScw%$eP*gd:IZj');
define('LOGGED_IN_KEY',    '@_zr"(G*p~LVp?N`codN84E;Q3d3IbC"UVKIlzUEw"t@Hypvwo)S$U~LMyo+FSH/');
define('NONCE_KEY',        'Nu+0P2w(RA*bFyP|?cE7t%JO!%GEC2f_4o%ZMQD5%Kf(mNtqEo5fTdKnvm%mw~!n');
define('AUTH_SALT',        '/NNn2FZIe4vsnCZAQ!M80np$d#Nv23$cAU#bPqop+bZsWMd91cat!Q)?t:7(7R8|');
define('SECURE_AUTH_SALT', 'DFCuIhB_ea|iLvd;ov&49CuQEY?CmEPL08J^)i"sJu:b(pC@oYiik%_ztEMJzoj$');
define('LOGGED_IN_SALT',   'L#vR$NcTM)0e*p5mE*E8x%*W6oN$:EL^ye"5S~Wtgc"3a`Xsp80+ITG:?%nkV1(G');
define('NONCE_SALT',       'r8$bne4XBtY0a|vSbGET/tLhZ:hUB24Tfc+4SPGVCrHe&dTNVx@J#c%fZdI;clos');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_hh9xvd_';

/**
 * Limits total Post Revisions saved per Post/Page.
 * Change or comment this line out if you would like to increase or remove the limit.
 */
define('WP_POST_REVISIONS',  10);

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

