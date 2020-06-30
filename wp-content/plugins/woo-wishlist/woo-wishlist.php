<?php
/*
Plugin Name: WooCommerce WishList
Plugin URI: http://wooWishlist.com
Description: A plugin to add wishlist on woocommerce products.
Version: 1.1
Author: Magnigenie
Author URI: http://magnigenie.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// No direct file access
! defined( 'ABSPATH' ) AND exit;
define('WOOWL_FILE', __FILE__);
define('WOOWL_PATH', plugin_dir_path(__FILE__));
define('WOOWL_BASE', plugin_basename(__FILE__));

require WOOWL_PATH . '/includes/functions.php';

new wooWl();