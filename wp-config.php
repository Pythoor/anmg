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
define('DB_NAME', 'anmg');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'u|8@_%K_CpPL$=/Q3[>;Ai}3n;B?FJk;l1TbpN~te.KeX4|7>k!TW)V1j_I>AX23');
define('SECURE_AUTH_KEY',  '-9$g4SE2nqlYU%YOzB(a-T}5hke6,x_irbhqH8SjvNfyo%.F?Z76])ub]@p b5F<');
define('LOGGED_IN_KEY',    '95ZE[8.z8f_5S@$ILcese2o~G^YH8xu]%Qtc Uw2o=EFk3K]h&=mzz3kSE)gm}B[');
define('NONCE_KEY',        'W_`+1_7U&gKqe.:I&zLPMjYB5*}U9D(D_tg/G1w>*1r@CO=0mdMD!,(3Rm8xwAz=');
define('AUTH_SALT',        'B2s{uoD05cfMf+h8Wq4&:I-)6D1.ySnN.xly{hTW{Z+SM+ek# <pq##1 #=vb )@');
define('SECURE_AUTH_SALT', 'Hlm8sN|SCYSY%2Pk)oES4<tIHF(=E-r}Avnu!1A$MS>D*FWg[[}-|Yy3w4!oAr!5');
define('LOGGED_IN_SALT',   'QHYz&p~RzyY6(bTyp@0h0hw:xIC&!0Gxyna/gnElSrv*+un)p+tr8?C:5,36H*HM');
define('NONCE_SALT',       'GG!ZvGiTJ^m2i{dbPI{iU$9]#~D*;MRa/mY1{VD!qo NBT:E]h65DZI([}V4w!f-');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'am_';

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
