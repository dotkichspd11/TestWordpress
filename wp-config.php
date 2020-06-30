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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'thanh1' );

/** MySQL database username */
define( 'DB_USER', 'thanh1' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Yasuo2014' );

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
define( 'AUTH_KEY',         '#@Xt@~C*&#FOK3!U5~Sci>B!B5YGU}F?x!.tG5T%*@l&()OGJ}wjI/,t`a#7JkTn' );
define( 'SECURE_AUTH_KEY',  'Ne6=>.bHZR MOUkk1ik.bmYw.Kd&@Wm.CzvO6[X?8wuU$o%x0,m|lWddlf(@b<+<' );
define( 'LOGGED_IN_KEY',    'PF0y;.?/aW}`Z{lW*Q?D>kETVyIceeBxDG$a)_;xH!szQ5 GBi~,z$k]h>XiwuU>' );
define( 'NONCE_KEY',        '3?rr IHgHt.5j.]y]Xr5JHY0DH)tX36{S{dqrO}X:lQsnYG:R Xf6)U1A+=}PL<W' );
define( 'AUTH_SALT',        'jX;.k_P]O+.gb).`c{P|nuBd*JL)*$N-DD3Zk1)sEw;#F?m&_oX,*=jp[Lf</.0(' );
define( 'SECURE_AUTH_SALT', 'sZz!F`N&yR!TS4WSjGM;Fx+`l3~.v*0VFNa5v!%J1?hOS3<7@vI:jMD)OXK~4qYS' );
define( 'LOGGED_IN_SALT',   '~@5dE``=.5ojo4C%`C;2lx~2g+^6F0oYwYWZaohL=7RI^T:cqDSCcDF:)_+o7)1[' );
define( 'NONCE_SALT',       'C;?QfMve*NtOBk*e27sg&O/<u<%~nI,2c^-qr<N)r>wB2z4TUNvXKS&I$,FW4uU+' );

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
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
