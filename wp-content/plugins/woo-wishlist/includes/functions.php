<?php

class wooWl {

	public function __construct() {

		//Check if woocommerce plugin is installed.
		add_action( 'admin_notices', array( $this, 'check_required_plugins' ) );

		//Add setting link for the admin settings
		add_filter( "plugin_action_links_".WOOWL_BASE, array( $this, 'woowl_settings_link' ) );

		//Add backend settings
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'woowl_settings_class' ) );

		//Add required js and css files to the site
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_woowl_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'woowl_scripts' ) );

		//Add to wishlist ajax
		add_action( 'wp_ajax_woowl_add_to_wishlist', array( $this, 'woowl_add_to_wishlist' ) );

		//Remove From wishlist Listing ajax
		add_action( 'wp_ajax_woowl_remove_wish_listing', array( $this, 'woowl_remove_wish_listing' ) );

		//Add to cart From wishlist Listing ajax
		add_action( 'wp_ajax_add_to_cart_list', array( $this, 'add_to_cart_list' ) );

		//Remove all  From wishlist Listing ajax
		add_action( 'wp_ajax_delete_all_wishlist', array( $this, 'delete_all_wishlist' ) );

		//Add wishlist setting
		add_action( 'woocommerce_before_shop_loop_item', array( $this, 'woo_wishlist_add_icon' ),9);

		//Add to wishlist text
		add_action('woocommerce_after_shop_loop_item', array($this, 'woo_add_to_wishlist_text'));

		//Add wishlist Short code
		add_shortcode('wishlist_shortcode', array($this, 'add_wishlist_shortcode'));

		//Add wishlist icon in single product
		add_action( 'woocommerce_product_thumbnails', array($this, 'woocommerce_show_product_thumbnails'), 20 );

		//Add wishlist icon in after add to cart option
		add_action( 'woocommerce_after_shop_loop_item', array($this, 'wishlist_text_after_add_to_cart'), 20 );

		//Add a wishlist flowting div
		add_action( 'template_redirect', array($this, 'wishlist_fixed_box') );

		//Add a page wishlist
		add_action( 'init', array($this, 'add_wishlist_page') );
	}


	/**
	 * Add new link for the settings under plugin links
	 *
	 * @param array   $links an array of existing links.
	 * @return array of links  along with age restricted shopping settings link.
	 *
	 */
	public function woowl_settings_link($links) {
		$settings_link = '<a href="'.admin_url('admin.php?page=wc-settings&tab=woowl').'">Settings</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Add new admin setting page for age restricted shopping settings.
	 *
	 * @param array   $settings an array of existing setting pages.
	 * @return array of setting pages along with age restricted shopping settings page.
	 *
	 */
	public function woowl_settings_class( $settings ) {
		$settings[] = include 'class-wc-settings-wishlist.php';
		return $settings;
	}


	/**
	 * Check if woocomerce is active or not.
	 */
	public function check_required_plugins() {
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) { ?>
			<div id="message" class="error">
				<?php echo __('Woo Add offer expects WooCommerce to be active. This plugin has been deactivated.'); ?>
			</div>

			<?php
			deactivate_plugins( '/woo-wishlist/woo-wishlist.php' );
		} // if woocommerce

	} // check_required_plugins

	
	/**
	 * Add admin script.
	 */
	public function admin_woowl_scripts() {

		wp_enqueue_script( 'custom',  plugins_url( 'assets/js/custom.js', WOOWL_FILE ), array( 'jquery' ), '1.0.0', true );
		wp_enqueue_style( 'woowl_admin_style', plugins_url( 'assets/css/woowl-admin.css', WOOWL_FILE ), '1.0' );
		wp_enqueue_style( 'woowl_iconpicker', plugins_url( 'assets/css/jquery.fonticonpicker.min.css', WOOWL_FILE ));
		wp_enqueue_script( 'woowl-icon-picker', plugins_url( 'assets/js/jquery.fonticonpicker.min.js', WOOWL_FILE ), array( 'jquery' ), '1.0' );
		wp_enqueue_script( 'woowl_admin', plugins_url( 'assets/js/admin-settings.js', WOOWL_FILE ), array( 'woowl-icon-picker' ) );
		$icons_path = array( 'icons_path' => plugins_url( 'assets/icons/selection.json', WOOWL_FILE ) );
		wp_localize_script( 'woowl_admin', 'woowl' , $icons_path );
		wp_enqueue_style( 'woowl_icons', plugins_url( 'assets/icons/style.css', WOOWL_FILE ), '1.0' );
	}

	
	/**
	 * Add script to the site.
	 */
	public function woowl_scripts() {


			wp_enqueue_script( 'woowl_admin', plugins_url( 'assets/js/admin-settings.js', WOOWL_FILE ), array( 'woowl-icon-picker' ) );
			$icons_path = array( 'icons_path' => plugins_url( 'assets/icons/selection.json', WOOWL_FILE ) );
			wp_localize_script( 'woowl_admin', 'woowl' , $icons_path );
			wp_enqueue_style( 'woowl_icons', plugins_url( 'assets/icons/style.css', WOOWL_FILE ), '1.0' );

			wp_enqueue_script( 'woowl-script',  plugins_url( 'assets/js/woowl.js', WOOWL_FILE ), array( 'jquery' ), '1.0.0', true );
			wp_localize_script( 'woowl-script', 'woowl', array(
				'ajaxurl'	=> admin_url( 'admin-ajax.php' ),
				'cart_url'  => wc_get_cart_url(),
				'delete_warning_text'  => get_option('woowl_wishlist_delete_warning'),
				'delete_all_warning_text'  => get_option('woowl_wishlist_delete_all_warning'),
				'wishlist_icon_color_active' => get_option('woowl_wishlist_icon_color_active'),
				'wishlist_icon_color_inactive' => get_option('woowl_wishlist_icon_color_inactive'),
				'no_item_found_text' => get_option('woowl_fixed_box_on_item_text'),
				'product_added_text' => get_option('woowl_wishlist_product_added'),
				'product_add_text' => get_option('woowl_wishlist_text')
			));
			
			//owlcarousel js
			wp_enqueue_script( 'owlcarousel-js', plugins_url( 'assets/js/owl.carousel.min.js', WOOWL_FILE ) , array( 'jquery' ), '1.0.0', true);

			//owlcarousel css
			wp_enqueue_style( 'owlcarousel-css', plugins_url( 'assets/css/owl.carousel.min.css', WOOWL_FILE ));
			wp_enqueue_style( 'owlcarousel-theme-js', plugins_url( 'assets/css/owl.theme.default.min.css', WOOWL_FILE ) );
			
			wp_enqueue_style( 'woowl-style', plugins_url( 'assets/css/woowl.css', WOOWL_FILE ) );

			if (is_user_logged_in()) {
				$user_id = get_current_user_id();
				$wishlist_id = (get_user_meta( $user_id, 'woowl_wishlist',true ));
				// $wishlist_id = array_filter($wishlist_id);
				$wishlist_id = is_array($wishlist_id) ? array_filter($wishlist_id) : array_filter([$wishlist_id]);
				$show_delete_all_btn = get_option('woowl_wishlist_add_remove_all_button');
				$css = '';
				if ($show_delete_all_btn == 'no' || empty($wishlist_id)) {
					$css .= ' .wishlist_heading { width:100%; } ';
				}

				$wishlist_icon_color_active = get_option('woowl_wishlist_icon_color_active');
				$css .=  ' .wish_icon a:hover, .wish_icon_single a:hover { color: '.$wishlist_icon_color_active.'; }';

			
				wp_add_inline_style( 'woowl-style', $css );
			}
	}

	/**
	 * Add products to wishlist.
	 */
	public function woowl_add_to_wishlist(){
		if ( isset($_POST['product_id']) && is_user_logged_in() ) {
			$product_id = $_POST['product_id'];
			$user_id = get_current_user_id();
			$wishlist_id = get_user_meta( $user_id, 'woowl_wishlist',true );
			$show_add_to_cart_button = get_option('woowl_wishlist_show_add_to_button');
			$add_to_cart_fixed_icon_text = get_option('woowl_add_to_cart_fixed_icon_text');
			$wish = array($product_id);
			$product = wc_get_product( $product_id );
			$product_type = $product->get_type();
			$image_url = get_the_post_thumbnail_url( $product_id, 'full' );
			$append_text = '';
			if( is_array($wishlist_id)  AND !empty($wishlist_id)){
				if (in_array($product_id, $wishlist_id)) {
					unset( $wishlist_id[array_search( $product_id, $wishlist_id )] );
					$response = array('result' => 'yes');
				}else{
					array_push($wishlist_id, $product_id);
					$append_text .= '<div class="items" id = "item_'.$product_id.'" ><a href="'.get_permalink( $product->get_id() ).'"><p style="margin:0">'.$product->get_name().'</p><div class="wishlist_img_container"><img class="wishlist_img" src="'.$image_url.'"> ';
					if ($show_add_to_cart_button == 'yes') {
						if ($product_type == 'grouped' || $product_type == 'variable') {
							$append_text .= '<div class="add_to_cart_fixed_icon"><a class="show_product" href="'.get_permalink( $product->get_id() ).'">'.$add_to_cart_fixed_icon_text.'</a></div>';
						}else{
							$append_text .= '<div class="add_to_cart_fixed_icon"><a class="add_to_cart_button ajax_add_to_cart add_to_cart_" data-product_id="'.$product_id.'" data-id = "'.$product_id.'" href="'.$product->add_to_cart_url().'">'.$add_to_cart_fixed_icon_text.' </a></div>';
						}
					}
					$append_text .=  '</div></a></div>';
					$response = array('result' => 'no', 'append_text' => $append_text);
				}
					$wish = $wishlist_id;
					update_user_meta( $user_id, 'woowl_wishlist', $wish );
			}else{
				$append_text .= '<div class="items" id = "item_'.$product_id.'" ><a href="'.get_permalink( $product->get_id() ).'"><p style="margin:0">'.$product->get_name().'</p><div class="wishlist_img_container"><img class="wishlist_img" src="'.$image_url.'"> ';
				if ($show_add_to_cart_button == 'yes') {
					if ($product_type == 'grouped' || $product_type == 'variable') {
						$append_text .= '<div class="add_to_cart_fixed_icon"><a class="show_product" href="'.get_permalink( $product->get_id() ).'">'.$add_to_cart_fixed_icon_text.'</a></div>';
					}else{
						$append_text .= '<div class="add_to_cart_fixed_icon"><a class="add_to_cart_button ajax_add_to_cart add_to_cart_" data-product_id="'.$product_id.'" data-id = "'.$product_id.'" href="'.$product->add_to_cart_url().'">'.$add_to_cart_fixed_icon_text.' </a></div>';
					}
				}
				$append_text .=  '</div></a></div>';
				$response = array('result' => 'no', 'append_text' => $append_text);
				update_user_meta( $user_id, 'woowl_wishlist', $wish );
			}
			echo json_encode( $response );
		}
		exit();
	}


	/**
	 * Remove products from wishlist.
	 */
	public function woowl_remove_wish_listing(){
		if ( isset($_POST['product_id']) && is_user_logged_in() ) {
			$product_id = $_POST['product_id'];
			$user_id = get_current_user_id();
			$wishlist_id = get_user_meta( $user_id, 'woowl_wishlist', true);
			$wish = $wishlist_id;
			if( is_array($wishlist_id)  AND !empty($wishlist_id)){
				foreach ($wishlist_id as $value) {
					if($value == $product_id ){
						unset( $wish[array_search( $value, $wish )] );
						update_user_meta( $user_id, 'woowl_wishlist', $wish );
						echo "1";
						exit;
					}
				}
			}
		}
		exit();
	}

	/**
	 * Remove all products from wishlist.
	 */
	public function delete_all_wishlist()
	{
		if (is_user_logged_in()) {
			$wish = array();
			$user_id = get_current_user_id();
			$wishlist_id = get_user_meta( $user_id, 'woowl_wishlist',true );
			if( is_array($wishlist_id)  AND !empty($wishlist_id)){
				update_user_meta( $user_id, 'woowl_wishlist', $wish );
				echo "1";
				exit;
			}
		}
	}

	/**
	 * Add to cart from wishlist.
	 */
	public function add_to_cart_list(){
		if ( isset($_POST['product_id']) && is_user_logged_in() ) {
			$product_id = $_POST['product_id'];
			$user_id = get_current_user_id();
			$wishlist_id = get_user_meta( $user_id, 'woowl_wishlist',true );
			$wish = $wishlist_id;
			if( is_array($wishlist_id)  AND !empty($wishlist_id)){
				foreach ($wishlist_id as $value) {
					if($value == $product_id ){
						global $woocommerce;
						$woocommerce->cart->add_to_cart($product_id);
						$remove_from_wishlist_on_add_to_cart = get_option('woowl_wishlist_remove_cart');
						$redirect_to_cart = get_option('woowl_wishlist_redirect_cart');
						if ($redirect_to_cart == 'yes' && $remove_from_wishlist_on_add_to_cart == 'yes') {
							unset( $wish[array_search( $value, $wish )] );
							update_user_meta( $user_id, 'woowl_wishlist', $wish );
							echo "remove_and_redirect";
						}else if ($redirect_to_cart == 'yes' && $remove_from_wishlist_on_add_to_cart != 'yes') {
							echo "only_redirect";
						}else if ($remove_from_wishlist_on_add_to_cart == 'yes' && $redirect_to_cart != 'yes') {
							unset( $wish[array_search( $value, $wish )] );
							update_user_meta( $user_id, 'woowl_wishlist', $wish );
							echo "only_remove";
						}else{
							echo "no_remove_and_redirect";
						}
						exit;
					}
				}
			}
		}
		exit();		
	}

	/**
	 * Add wishlist icon to your site.
	 */		
	public function woo_wishlist_add_icon() {
		global $product;
		$wishlist_icon_position = get_option('woowl_wishlist_position');
		$wishlist_icon_size = get_option('woowl_wishlist_icon_size');
		$wishlist_icon_color_active = get_option('woowl_wishlist_icon_color_active');
		$wishlist_icon_color_inactive = get_option('woowl_wishlist_icon_color_inactive');
		$login_url = esc_url( wp_login_url() );
		$options = get_option('woowl');
					
		if (get_option('woowl_wishlist_enabled')=='yes') {
			if ($wishlist_icon_position == 'after_thumbnail') {
				?>
					<div class="loader load<?php echo $product->id; ?>" style="display:none"></div>
				<?php
				if (is_user_logged_in()) {
					$user_id = get_current_user_id();
					$wishlist_id = get_user_meta( $user_id, 'woowl_wishlist',true );
					if (!empty($wishlist_id) && in_array($product->id, $wishlist_id)) {
						$color = 'color:'.$wishlist_icon_color_active.'; ';
					}else{
						$color = 'color:'.$wishlist_icon_color_inactive.'; ';
					}
					echo '<span class="wish_icon '.get_option('woowl_wishlist_position_thumbnail').'">';
					echo '<a class = "add_to_wishlist '.get_option('woowl_wishlist_position_thumbnail').'" id = "'.$product->id.'" style="'.$color.'; font-size:'.$wishlist_icon_size.'px" ><i data-fip-value="'.$options['acc_icon'].'" class="'.$options['acc_icon'].'"></i></a>';
					echo '</span>';
				}else{
					$color = 'color:'.$wishlist_icon_color_inactive.'; ';
					echo '<span class="wish_icon '.get_option('woowl_wishlist_position_thumbnail').'">';
					echo '<a class = "login_user '.get_option('woowl_wishlist_position_thumbnail').'" href="'.$login_url.'" id = "'.$product->id.'" style="'.$color.'; font-size:'.$wishlist_icon_size.'px" ><i data-fip-value="'.$options['acc_icon'].'" class="'.$options['acc_icon'].'"></i></a>';
					echo '</span>';
				}
			}
		}
	}

	public function wishlist_text_after_add_to_cart(){
		global $product;
		$wishlist_icon_position = get_option('woowl_wishlist_position');//after_thumbnail	after_summery
		$add_to_wishlist_text_after_cart = get_option('woowl_wishlist_text');
		$wishlist_icon_color_active = get_option('woowl_wishlist_icon_color_active');
		$wishlist_icon_color_inactive = get_option('woowl_wishlist_icon_color_inactive');
		$login_url = esc_url( wp_login_url() );
		$options = get_option('woowl');
		if (get_option('woowl_wishlist_enabled')=='yes') {
			if ($wishlist_icon_position == 'after_add_to_cart') {
				?>
					<div class="loader load<?php echo $product->id; ?>" style="display:none"></div>
				<?php
				if (is_user_logged_in()) {
						$user_id = get_current_user_id();
						$wishlist_id = get_user_meta( $user_id, 'woowl_wishlist',true );
						if (!empty($wishlist_id) && in_array($product->id, $wishlist_id)) {
							$color = 'color:'.$wishlist_icon_color_active.'; ';
							$wishlist_add_text = get_option('woowl_wishlist_product_added');
							$add_to_wishlist_text_after_cart = $wishlist_add_text;
						}else{
							$color = 'color:'.$wishlist_icon_color_inactive.'; ';
						}
					echo '<div class="wish_icon_after_cart">';
					echo '<a class = "add_to_wishlist_text_after_cart" id = "'.$product->id.'" style="'.$color.';" >'.$add_to_wishlist_text_after_cart.'</a>';
					echo '</div>';
				}else{
					$color = 'color:'.$wishlist_icon_color_inactive.'; ';
					echo '<div class="wish_icon_after_cart">';
					echo '<a class = "login_user" href="'.$login_url.'" id = "'.$product->id.'" style="'.$color.';" >'.$add_to_wishlist_text_after_cart.'</a>';
					echo '</div>';
				}
			}
		}
	}

	public function woo_add_to_wishlist_text (){
		global $product;
		$wishlist_add_text = get_option('woowl_wishlist_product_added');
		$wishlist_remove_text = get_option('woowl_wishlist_product_removed');
		?>
			<div id="wishlist_add_text<?php echo $product->id; ?>" class="wishlist_add_text"  role="dialog" style="display:none">
				<h4><?php echo $wishlist_add_text; ?></h4>
			</div>
			<div id="wishlist_remove_text<?php echo $product->id; ?>" class="wishlist_remove_text"  role="dialog" style="display:none">
				<h4><?php echo $wishlist_remove_text; ?></h4>
			</div>
		<?php
	}

	public function woocommerce_show_product_thumbnails(){
		global $product;
		$user_id = get_current_user_id();
		$wishlist_id = get_user_meta( $user_id, 'woowl_wishlist',true );
		$options = get_option('woowl');
		$wishlist_icon_size = get_option('woowl_wishlist_icon_size');
		$wishlist_icon_color_active = get_option('woowl_wishlist_icon_color_active');
		$wishlist_icon_color_inactive = get_option('woowl_wishlist_icon_color_inactive');

		if (!empty($wishlist_id) && in_array($product->id, $wishlist_id)) {
			$color = $wishlist_icon_color_active;
		}

		$login_url = esc_url( wp_login_url() );
		
		if (get_option('woowl_wishlist_enabled')=='yes') {
			?>
				<div class="loader load<?php echo $product->id; ?>" style="display:none"></div>
			<?php
			echo '<span class="wish_icon_single '.get_option('woowl_wishlist_position_thumbnail').'">';
			if (is_user_logged_in()) {
				echo '<a class = "add_to_wishlist" id = "'.$product->id.'" style="color:'.$color.'; font-size:'.$wishlist_icon_size.'px" ><i data-fip-value="'.$options['acc_icon'].'" class="'.$options['acc_icon'].'"></i></a>';
			}else{
				echo '<a class = "login_user" href="'.$login_url.'" id = "'.$product->id.'" style="color:'.$color.'; font-size:'.$wishlist_icon_size.'px" ><i data-fip-value="'.$options['acc_icon'].'" class="'.$options['acc_icon'].'"></i></a>';
			}
			echo '</span>';
		}
	}
	

	// Function to add wishlist list posts and pages
	public function add_wishlist_shortcode() {
		if (is_user_logged_in()) {
			$user_id = get_current_user_id();
			$wishlist_id = (get_user_meta( $user_id, 'woowl_wishlist',true ));
			$wishlist_id = array_filter($wishlist_id);
			$show_delete_all_btn = get_option('woowl_wishlist_add_remove_all_button');
			$wishlist_title = get_option('woowl_wishlist_title');
			$show_price = get_option('woowl_wishlist_show_checkbox');
			$show_add_to_cart_button = get_option('woowl_wishlist_show_add_to_button');
			$wishlist_add_to_cart_text = get_option('woowl_wishlist_add_to_cart');
			
			$grouped_product_text = get_option('woowl_grouped_product_text');

			$wishlist_add_to_cart_icon_color = get_option('woowl_wishlist_add_to_cart_icon_color');
			$wishlist_delete_icon_color = get_option('woowl_wishlist_delete_icon_color');
			$wishlist_delete_all_icon_color = get_option('woowl_wishlist_delete_all_icon_color');
			$wishlist_icon_size = get_option('woowl_wishlist_icon_size');

			$no_item_found_text = get_option('woowl_fixed_box_on_item_text');

			$options = get_option( 'woowl' );

		    ?>
		    	<div id="loader"></div>
		    	<h2 id= "wishlist_add_to_cart_text" style="display:none"><?php echo $wishlist_add_to_cart_text; ?></h2>
		    	<div class="wishlist">
		    		<div class="wishlist_contain">
		    			<div class="wishlist_heading">
		    			<?php echo $wishlist_title." - "; ?>
		    			<span class="wishlist_count"> <?php echo "( ".count($wishlist_id)." )"; ?> </span>
		    			</div>
		    			<?php
		    				if ($show_delete_all_btn == 'yes') {
			    				if (!empty($wishlist_id)) {
					    			?>
					    			<span class="wishlist_delete_all" title="<?php echo get_option('woowl_wishlist_delete_all_text'); ?>">
					    				<span><?php echo get_option('woowl_wishlist_delete_all_text'); ?> <?php echo "<i data-fip-value='".$options['delete_all_icon']."' class='".$options['delete_all_icon']."' style = 'color:".$wishlist_delete_all_icon_color."; font-size:".$wishlist_icon_size."px'></i>"; ?> </span>
					    			</span>
					    			<?php
			    				}
			    			}
		    			?>
		    			<?php 
		    				if (!empty($wishlist_id)) {	    				
			    				foreach ($wishlist_id as $key => $value) {
									$product = wc_get_product( $value );
									$stock_status = $product->get_stock_status();
									$product_type = $product->get_type();
					    			?>
					    			<div class="loader" style="display:none"></div>
					    			<div class="wishlist_items" data-id = "<?php echo $value; ?>">
					    				<div class="product_details" href="<?php echo get_permalink( $product->get_id() ); ?>">
					    					<div class="product_img">
					    						<div class="product_img_block">
					    							<div class="product_img_container">
					    								<img class="wishlist_img" src="<?php echo get_the_post_thumbnail_url( $value, 'full' ) ?>">
					    							</div>
					    						</div>
					    					</div>
					    					<div class="product_description">
					    						<div class="col col-10-12 text_w">
					    							<div class="product_title">
					    								<a href="<?php echo get_permalink( $product->get_id() ); ?>">
					    									<?php echo $product->get_name(); ?>
						    								<?php
						    									if (!empty($stock_status)) {
						    										echo "<span class='stock'> (";
						    										 echo $retVal = ($stock_status == 'instock') ? 'In Stock' : 'Out Of Stock' ;
						    										echo ") </span>";
						    									}
						    								?>
					    								</a>
					    							</div>
					    							<?php 
					    								if ($show_price == 'yes') {
					    									if ($product_type == 'grouped' || $product_type == 'variable') {
					    										echo "<span>".$grouped_product_text."</span>";
					    									}else{
								    							?>
									    							<div class="product_price">
									    								<?php 
									    									if ($product->get_regular_price() == $product->get_price()) {
									    									echo '<span>  $'.$product->get_price().'</span>';
									    									}else{ 
									    								?>
									    									<del> <?php echo "$".$product->get_regular_price(); ?> </del>  <span>  <?php echo '$'.$product->get_price(); ?></span>
									    								<?php
									    									}
									    								?>
									    							</div>
									    						<?php 
									    					}
						    							}	
						    						?>
					    						</div>
					    						<div class="col col-2-12 remove_w">
					    							<div class="delete_icon" title="<?php echo get_option('woowl_wishlist_delete_text'); ?>">
					    								<span class="remove_wishlist" id = "<?php echo $value; ?>"><?php echo "<i data-fip-value='".$options['delete_icon']."' class='".$options['delete_icon']."' style='color:".$wishlist_delete_icon_color."; font-size:".$wishlist_icon_size."px'></i>"; ?></span>
					    							</div>
					    							<?php
					    								if ($show_add_to_cart_button == 'yes') {
					    									if ($product_type == 'grouped' || $product_type == 'variable') {
					    										?>
					    										<div class="add_to_cart_icon" title="<?php echo get_option('woowl_add_to_cart_fixed_icon_text'); ?>">
					    											<a class="show_product" href="<?php echo get_permalink( $product->get_id() ); ?>">
					    												<?php echo "<i data-fip-value='".$options['add_to_cart_icon']."' class='".$options['add_to_cart_icon']."' style='color:".$wishlist_add_to_cart_icon_color."; font-size:".$wishlist_icon_size."'></i>"; ?>
					    											</a>
					    										</div>
					    										<?php
					    									}else{
								    							?>
								    							<div class="add_to_cart_icon" title="<?php echo get_option('woowl_add_to_cart_fixed_icon_text'); ?>">
								    								<a class="add_to_cart_button ajax_add_to_cart add_to_cart_list" data-product_id="<?php echo $value; ?>" data-id = "<?php echo $value; ?>" href="<?php echo $product->add_to_cart_url(); ?>">
								    									<?php echo "<i data-fip-value='".$options['add_to_cart_icon']."' class='".$options['add_to_cart_icon']."' style='color:".$wishlist_add_to_cart_icon_color."; font-size:".$wishlist_icon_size."px'></i>"; ?>
								    								</a>
								    							</div>
								    							<?php
								    						}
					    								}
					    							?>
					    						</div>
					    					</div>
					    				</div>
					    			</div>
					    			<?php  
			    				}
			    					?>
					    			<div>
					    				<div class="wishlist_items_not_found" style = "display:none">
					    					<div class="product_details">
					    					<br>
					    						<h4><?php echo $no_item_found_text; ?></h4>
					    					</div>
					    				</div>
					    			</div>
					    			<?php
		    				}else{
		    			?>
		    			<div>
		    				<div class="wishlist_items_not_found">
		    					<div class="product_details">
		    					<br>
		    						<h4><?php echo $no_item_found_text; ?></h4>
		    					</div>
		    				</div>
		    			</div>
		    			<?php
		    				}
		    			?>
		    		</div>	    		
		    	</div>
		    <?php
		}
	}

	// Display wislist fixed box
	public function wishlist_fixed_box()
	{
		if (is_user_logged_in()) {
			$fixed = get_option('woowl_wishlist_enable_fixed_box');
			$wishlist_title = get_option('woowl_wishlist_title');
			$user_id = get_current_user_id();
			$wishlist_id = (get_user_meta( $user_id, 'woowl_wishlist', true ));
			// $wishlist_id = array_filter($wishlist_id);
			$wishlist_id = is_array($wishlist_id) ? array_filter($wishlist_id) : array_filter([$wishlist_id]);
			$show_add_to_cart_button = get_option('woowl_wishlist_show_add_to_button');
			$add_to_cart_fixed_icon_text = get_option('woowl_add_to_cart_fixed_icon_text');
			$no_item_found_text = get_option('woowl_fixed_box_on_item_text');

			if ($fixed == 'yes') {
				if ( !is_admin() ) {
					?>
						<div class="wishlist_fixed_box <?php echo get_option('woowl_wishlist_fixed_box_position'); ?>">
							<h3> <?php echo $wishlist_title; ?> </h3>
							<span class="toggle_wishlist"><i data-fip-value="icon-arrow-right3" class="icon-arrow-right3"></i></span>
						</div>
						<div class="wishlist_box_body <?php echo get_option('woowl_wishlist_fixed_box_position'); ?>" id="wishlist_box_body">
							 <!-- Slideshow container -->
							<div class="slideshow-container <?php echo get_option('woowl_wishlist_fixed_box_position'); ?>">
								<div class = "wislist_head">
									<h4> <a href="<?php echo get_permalink( get_page_by_path( 'wishlist' ) ) ?>"><?php echo get_option('woowl_wishlist_display_all_text'); ?></a> </h4>
								</div>
								<div class="fixed_wishlist_container">
									<div id="owl-contain" class="owl-carousel owl-theme">
									<?php
										if (!empty($wishlist_id)) {
						    				foreach ($wishlist_id as $key => $value) {
												$product = wc_get_product( $value );
												$product_type = $product->get_type();

												?>
													<!-- Full-width slides/quotes -->
													<div class="items" id = "item_<?php echo $product->get_id(); ?>">
														<a href="<?php echo get_permalink( $product->get_id() ); ?>">
															<?php 
																if ($product_type == 'grouped' || $product_type == 'variable') {
																	?>
																		<p style="margin:0"><?php echo $product->get_name(); ?></p>
																	<?php
																}else{
																	?>
																		<p style="margin:0"><?php echo $product->get_name()." 	  ₹".$product->get_price(); ?></p>
																	<?php
																}
															?>
															<div class="wishlist_img_container">
																<img class="wishlist_img" src="<?php echo get_the_post_thumbnail_url( $value, 'full' ) ?>">
								    							<?php
								    								if ($show_add_to_cart_button == 'yes') {
								    									if ($product_type == 'grouped' || $product_type == 'variable') {
								    										?>
								    										<div class="add_to_cart_fixed_icon">
								    											<a class="show_product" href="<?php echo get_permalink( $product->get_id() ); ?>">
								    												<?php echo $add_to_cart_fixed_icon_text; ?>
								    											</a>
								    										</div>
								    										<?php
								    									}else{
											    							?>
											    							<div class="add_to_cart_fixed_icon">
											    								<a class="add_to_cart_button ajax_add_to_cart add_to_cart_" data-product_id="<?php echo $value; ?>" data-id = "<?php echo $value; ?>" href="<?php echo $product->add_to_cart_url(); ?>">
											    									<?php echo $add_to_cart_fixed_icon_text; ?>
											    								</a>
											    							</div>
											    							<?php
											    						}
								    								}
								    							?>
															</div>
														</a>
													</div>
												<?php	
											}
										}else{
											?>
												<!-- Full-width slides/quotes -->
												<div class="items" id = "no_item">
													<h4 style="margin:0"> - </h4>
													<div class="wishlist_img_container">
													<br>
													<br>
													<br>
														<h3 style="margin:0"><?php echo $no_item_found_text ?></h3>
													</div>
												</div>
											<?php
										}
									?>
									</div> <!-- end of owl-carasoul -->
								</div>  <!-- end fixed_wishlist_container div -->
							</div>  <!-- end slideshow-container div -->
						</div>  <!-- end wishlist_box_body div -->
					<?php
				}
			}
		}
	}

    /**
     * Add a page "Wishlist".
     *
     * @return void
     * @since 1.0.0
     */
    public function add_wishlist_page() {
    	if (is_user_logged_in()) {
	        $option_value = get_option( 'woowl-page-id' );

	        if ( $option_value > 0 && get_post( $option_value ) ) {
	            return;
	        }

	        $page_data = array(
	            'post_status' 		=> 'publish',
	            'post_type' 		=> 'page',
	            'post_author' 		=> 1,
	            'post_name' 		=> esc_sql( _x( 'wishlist', 'page_slug', 'mg-woocommerce-wishlist' ) ),
	            'post_title' 		=> __( 'Wishlist', 'mg-woocommerce-wishlist' ),
	            'post_content' 		=> '[wishlist_shortcode]',
	            'post_parent' 		=> 0,
	            'comment_status' 	=> 'closed'
	        );
	        $page_id = wp_insert_post( $page_data );

	        update_option( 'woowl-page-id', $page_id );
	    }
    }

}