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
define( 'DB_NAME', 'taxi' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '|S)4xIv8jp%e!eB>urFj(APSxw{v.9+@fyI}|B/C~jwyy~rMFwr$|j(x>1 ?X8aC' );
define( 'SECURE_AUTH_KEY',  '=vmt*YUuiSk|j^6&=VyZsA>hl)eqY^l .ehJ}rolYly=VY:Q}i:NdFtx)e{bcyp8' );
define( 'LOGGED_IN_KEY',    'f.MW|73)/yE5P6nuC0#(9f,0nmfWOydTwfgXiO+7AWgFk0~PTxlg7`7oUtzQ.Wd@' );
define( 'NONCE_KEY',        ';j7cvcg<Z4q{P%JH*Hn[?KtbZ+2;<0h9u@GJ?4dE)Up6K?6_kw8n?JrRGQIAgw?Z' );
define( 'AUTH_SALT',        '`Efdt{p!Pd8t=yVa@D?mJ7:X%8ctm|.KBId+34VW9C<A@bs$VTC7~md~v-a/[vRN' );
define( 'SECURE_AUTH_SALT', 'PvpNz<I4 $U|6,B:L9^BW:thYKD6N^3Id!;-<Ec(2x+~X-yE9<AsW][)1+GxzjZV' );
define( 'LOGGED_IN_SALT',   'l_1tkk1foLL>5V^v:%FP9g`jgV>sS(aAfzJ&<N5ffm~fj8 9c&L@re0w$)$[ScQu' );
define( 'NONCE_SALT',       'ZQ@l~EsEIgf%:WA^5[0.:IVi,Aw (3 @VLJJFr&w=AvKL[(ZUN Td)>_J#[#- -}' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
