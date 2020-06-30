
<?php
/**
 * WooCommerce Wishlist Settings
 *
 * @author 		Magnigenie
 * @category 	Admin
 * @version     1.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (  class_exists( 'WC_Settings_Page' ) ) :

/**
 * WC_Settings_Accounts
 */

class WC_Settings_WOO_Wishlist extends WC_Settings_Page {
	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'woowl';
		$this->label = __( 'Wishlist settings', 'woowl' );
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
	}


	/**
	 * Get Setting
	 *
	 * @return array
	 */
	public function get_settings() {

		return apply_filters( 'woocommerce_' . $this->id . '_settings',
			array(
				array(
					'title' 					=> __( 'WooCommerce Wishlist Settings', 'woopcs' ),
					'type'  					=> 'title',
					'desc'						=> '',
					'id' 						=> 'woowl_title'
				),
		      	array(
					'title' 					=> __( 'Enable Wishlist ', 'woowl' ),
					'desc' 						=> __( 'Enable all plugin features. [wishlist_shortcode] Add this Shortcode to display the wishlist in your pages. ', 'woowl' ),
					'type' 						=> 'checkbox',
					'id'						=> 'woowl_wishlist_enabled',
					'default'	 				=> 'no'
				),
		      	array(
					'title' 					=> __( 'Default wishlist title', 'woowl' ),
					'default' 		  			=> 'My wishlist',
					'type' 						=> 'text',
					'id'						=> 'woowl_wishlist_title',
				),
		      	array(
					'title'             		=> __( 'Wishlist Icon Position', 'woowl' ),
					'type'              		=> 'select',
				    'options'					=> array(
													'after_thumbnail'        	=> __( 'After Thumbnail', 'woowl' ),
													'after_add_to_cart'        	=> __( 'After Add To Cart', 'woowl' )
								    				),
					'id'                		=> 'woowl_wishlist_position',
      			),
		      	array(
					'title'         		    => __( 'Wishlist Icon Position On Thumbnail', 'woowl' ),
					'type'          		    => 'select',
				    'options'					=> array(
			  										'top_left'        	=> __( 'Top - Left', 'woowl' ),
			  										'top_right'        	=> __( 'Top - Right', 'woowl' ),
			  										'bottom_left'        	=> __( 'Bottom - Left', 'woowl' ),
			  										'bottom_right'        	=> __( 'Bottom - Right', 'woowl' )
								 					),
					'id'            		    => 'woowl_wishlist_position_thumbnail',
		      	),
		      	array(
					'title' 				    => __( 'Enable Fixed Wishlist box', 'woowl' ),
					'desc' 				      	=> 'Add a wishlist fixed box to your site.',
					'type' 				      	=> 'checkbox',
					'id'				        => 'woowl_wishlist_enable_fixed_box',
					'default' 			    	=> 'no'
				),
		      	array(
					'title'         		    => __( 'Wishlist fixed box Position', 'woowl' ),
					'type'          		    => 'select',
				    'options'					=> array(
			  										'left_top'        		=> __( 'Top - Left', 'woowl' ),
			  										'left_center'        	=> __( 'Center - Left', 'woowl' ),
			  										'left_bottom'        	=> __( 'Bottom - Left', 'woowl' ),
			  										'right_top'        		=> __( 'Top - Right', 'woowl' ),
			  										'right_center'        	=> __( 'Center - Right', 'woowl' ),
			  										'right_bottom'        	=> __( 'Bottom - Right', 'woowl' )
								 					),
					'id'            		    => 'woowl_wishlist_fixed_box_position',
		      	),
		      	array(
					'title' 					=> __( 'Add to cart text in Fixed wishlist box', 'woowl' ),
					'default' 		  			=> 'Add to cart',
					'type' 						=> 'text',
					'id'						=> 'woowl_add_to_cart_fixed_icon_text',
				),
				array(
					'title' 					=> __( 'No item found text in Fixed wishlist box', 'woowl' ),
					'default' 		  			=> 'No Item Found',
					'type' 						=> 'text',
					'id'						=> 'woowl_fixed_box_on_item_text',
				),
		      	array(
		      		'title' 	  				=> __( 'Wishlist icon', 'woowl' ),
		      		'id' 		  				=> 'woowl[acc_icon]',
		      		'type' 		  				=> 'text',
		      		'default'	  				=> 'woowl-square-plus',
		      		'class'		  				=> 'woowl-icon-picker',
		      		'desc_tip'					=>  true
		      	),
		      	array(
		      		'title' 	  				=> __( 'Delete icon', 'woowl' ),
		      		'id' 		  				=> 'woowl[delete_icon]',
		      		'type' 		  				=> 'text',
		      		'default'	  				=> 'woowl-square-plus',
		      		'class'		  				=> 'woowl-icon-picker',
		      		'desc_tip'					=>  true
		      	),
		      	array(
		      		'title' 	  				=> __( 'Delete all icon', 'woowl' ),
		      		'id' 		  				=> 'woowl[delete_all_icon]',
		      		'type' 		  				=> 'text',
		      		'default'	  				=> 'woowl-square-plus',
		      		'class'		  				=> 'woowl-icon-picker',
		      		'desc_tip'					=>  true
		      	),
		      	array(
		      		'title' 	  				=> __( 'Add to cart icon', 'woowl' ),
		      		'id' 		  				=> 'woowl[add_to_cart_icon]',
		      		'type' 		  				=> 'text',
		      		'default'	  				=> 'woowl-square-plus',
		      		'class'		  				=> 'woowl-icon-picker',
		      		'desc_tip'					=>  true
		      	),
		      	array(
					'title' 					=> __( 'Wishlist icon color Active', 'wcmd' ),
					'id' 						=> 'woowl_wishlist_icon_color_active',
					'type' 						=> 'color',
					'default'					=> '#f33939',
					'css' 						=> 'width: 125px;',
					'desc_tip'					=>  true
				),
				array(
					'title' 					=> __( 'Wishlist icon color Inactive', 'wcmd' ),
					'id' 		  				=> 'woowl_wishlist_icon_color_inactive',
					'type' 						=> 'color',
					'default'					=> '#000',
					'css' 						=> 'width: 125px;',
					'desc_tip'					=>  true
				),
				array(
					'title' 					=> __( 'Add to cart icon color', 'wcmd' ),
					'id' 		  				=> 'woowl_wishlist_add_to_cart_icon_color',
					'type' 						=> 'color',
					'default'					=> '#82ed83',
					'css' 						=> 'width: 125px;',
					'desc_tip'					=>  true
				),
				array(
					'title' 					=> __( 'Delete individual wishlist icon color', 'wcmd' ),
					'id' 		  				=> 'woowl_wishlist_delete_icon_color',
					'type' 						=> 'color',
					'default'					=> '#c90707',
					'css' 						=> 'width: 125px;',
					'desc_tip'					=>  true
				),
				array(
					'title' 					=> __( 'Delete All wishlist icon color', 'wcmd' ),
					'id' 						=> 'woowl_wishlist_delete_all_icon_color',
					'type' 						=> 'color',
					'default'					=> '#c90707',
					'css' 						=> 'width: 125px;',
					'desc_tip'					=>  true
				),
				array(
					'title'   					=> __( 'Icon size (in px)', 'wcmd' ),
					'desc' 	  					=> __( 'Enter a value to set icon size , Maximum size is 40px.', 'wcmd' ),
					'type' 	  					=> 'number',
					'id'	  					=> 'woowl_wishlist_icon_size',
					'css' 	  					=> 'width: 125px;',
					'default' 					=> '25',
					'custom_attributes' 		=> array( 'max' => '40', 'min' => '10', 'step' => '1' )
				),
	      		array(
					'title' 					=> __( ' "Delete from wishlist warning" text', 'woowl' ),
					'default' 		  			=> 'Are you sure you want to remove this item from Wishlist?',
					'type' 						=> 'text',
					'id'						=> 'woowl_wishlist_delete_warning',
				),
	      		array(
					'title' 					=> __( ' "Delete all from wishlist warning" text', 'woowl' ),
					'default' 		  			=> 'Are you sure you want to remove all items from Wishlist?',
					'type' 						=> 'text',
					'id'						=> 'woowl_wishlist_delete_all_warning',
				),
	      		array(
					'title' 					=> __( ' "Delete all" text', 'woowl' ),
					'default'	 		  		=> 'Delete All',
					'type' 						=> 'text',
					'id'						=> 'woowl_wishlist_delete_all_text',
				),
				array(
					'title' 					=> __( ' "Delete" text', 'woowl' ),
					'default'	 		  		=> 'Delete From wishlist',
					'type' 						=> 'text',
					'id'						=> 'woowl_wishlist_delete_text',
				),
	      		array(
					'title' 					=> __( ' "Display all" text', 'woowl' ),
					'default' 			  		=> 'Display All',
					'type' 						=> 'text',
					'id'						=> 'woowl_wishlist_display_all_text',
				),
	      		array(
					'title' 				    => __( 'Redirect to cart', 'woowl' ),
					'desc' 				      	=> 'Redirect to cart page if "Add to cart" button is clicked in the wishlist page.',
					'type' 				      	=> 'checkbox',
					'id'				        => 'woowl_wishlist_redirect_cart',
					'default' 			    	=> 'no'
				),
	      		array(
					'title' 					=> __( 'Remove if added to the cart', 'woowl' ),
					'desc' 						=> 'Remove the product from the wishlist if it has been added to the cart.',
					'type' 						=> 'checkbox',
					'id'						=> 'woowl_wishlist_remove_cart',
					'default'					=> 'yes'
				),
	      		array(
					'title' 					=> __( '"Add to Wishlist" text', 'woowl' ),
					'default' 					=> 'Add to Wishlist',
					'type' 						=> 'text',
					'id'						=> 'woowl_wishlist_text',
				),
	      		array(
					'title' 					=> __( '"Product added" text', 'woowl' ),
					'default' 					=> 'Product added to wishlist',
					'type' 						=> 'text',
					'id'						=> 'woowl_wishlist_product_added',
				),
				array(
					'title' 					=> __( '"Product removed" text', 'woowl' ),
					'default' 					=> 'Product removed',
					'type' 						=> 'text',
					'id'						=> 'woowl_wishlist_product_removed',
				),
	      		array(
					'title'						=> __( '"Added to Cart" text', 'woowl' ),
					'default' 					=> 'Added to Cart',
					'type' 						=> 'text',
					'id'						=> 'woowl_wishlist_add_to_cart',
				),
	      		array(
					'title'			            => __( 'Grouped Product Text in Wishlist', 'woowl' ),
					'default' 					=> 'Grouped Product',
					'type' 						=> 'text',
					'id'						=> 'woowl_grouped_product_text',
	      		),
	      		array(
					'title' 					=> __( 'Show Unit price', 'woowl' ),
					'desc' 						=> 'Show unit price for each product in wishlist.',
					'type' 						=> 'checkbox',
					'id'						=> 'woowl_wishlist_show_checkbox',
					'default'	 				=> 'yes'
				),
				array(
					'title' 					=> __( 'Show "Add to Cart" button', 'woowl' ),
					'desc' 						=> 'Show "Add to Cart" button for each product in wishlist.',
					'type' 						=> 'checkbox',
					'id'						=> 'woowl_wishlist_show_add_to_button',
					'default' 					=> 'yes'
				),
				array(
					'title' 					=> __( 'Show Stock status', 'woowl' ),
					'desc' 						=> ' Show "In stock" or "Out of stock" label for each product in wishlist.',
					'type' 						=> 'checkbox',
					'id'						=> 'woowl_wishlist_show_stock_sttus',
					'default' 					=> 'yes'
				),
				array(
					'title' 					=> __( 'Add remove All button	', 'woowl' ),
					'desc' 						=> '  Add a remove All button at the right of the title.',
					'type' 						=> 'checkbox',
					'id'						=> 'woowl_wishlist_add_remove_all_button',
					'default' 					=> 'yes'
				),
				'section_end'         			=> array(
												    'type' 							=> 'sectionend',
												    'id' 							=> 'wooap_sectionend'
												)
				)
			); // End pages settings

	}


}

return new WC_Settings_WOO_Wishlist();
endif;

