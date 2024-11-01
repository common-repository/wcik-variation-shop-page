<?php
/*
Plugin Name: Wcik Variation Shop Page
Plugin URI: http://imran1.com/woocommerce-shop-page-variation-dropdown-image-display/
Description: WooCommerce shop page Variation Dropdown & Image updation for variable products
Version: 1.0
Author: Imran Khan
Author URI: http://imran1.com
License: GPL2
*/

/**
 * Copyright (c) 2018 Imran1 (email: info@imran1.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

 // don't call the file directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {



add_action( 'woocommerce_before_shop_loop', 'wcik_variation_shop_page' );

function wcik_variation_shop_page() {
    
	 
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 ); 
	add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_single_add_to_cart', 30 );
}

 add_filter( 'woocommerce_before_shop_loop_item_title', 'wcik_woo_display_variation_dropdown_on_shop_page' );
 
 function wcik_woo_display_variation_dropdown_on_shop_page() {

global $product;
	if( $product->is_type( 'variable' )) {
  
  // get the product variations
        $product_variations = $product->get_available_variations();
        if ( !empty( $product_variations ) ) {
            ?>
        <div class="product-variation-images">
        <?php
       
        foreach($product_variations as $product_variation) {
            // get the slug of the variation
           $product_attribute_name = strtolower($product_variation['attributes']['attribute_pa_color']);
           $product_attribute_logo = strtolower($product_variation['attributes']['attribute_logo']);
           if($product_attribute_logo != ""){ $product_attribute_logo = "-". $product_attribute_logo;}
            ?>
            <div class="product-variation-image product-variation-<?php echo $product_attribute_name.$product_attribute_logo; ?>" id="product-variation-<?php echo $product_variation['variation_id']; ?>" data-attribute="<?php echo $product_attribute_name ?>">
            <img src="<?php echo $product_variation['image']['thumb_src'] ; ?>" alt="">
            </div><!-- #product-variation-image -->
        <?php } ?>
        </div>
        <?php }
}

}


function wcik_variation_css() {
    ?>
    <style>
    .product-variation-image {
    display: none;
}
</style>
<?php
}

add_action('wp_head', 'wcik_variation_css'); 

function wcik_variation_jquery() {
    ?>
    
         <script>
        jQuery(document).ready(function(jQuery) {
             
            jQuery('input.variation_id').change( function(){
                if( '' != jQuery('input.variation_id').val() ) {
                     
                    var var_id = jQuery('input.variation_id').val();
                      var img_id = "product-variation-"+var_id;
                      
                     //  alert(img_id);
                      
                      
                      jQuery(this).parents("li").find('.product-variation-image').css( 'display', 'none' );
                      jQuery(this).parents("li").find('.attachment-woocommerce_thumbnail').css( 'display', 'none' );
                
                      jQuery(this).parents("li").find('#'+img_id).css( 'display', 'block' );
                            
                     }
                       
         jQuery(document).on('click', '.reset_variations', function() {  
               
                jQuery(this).parents("li").find('.product-variation-image').css( 'display', 'none' );
                jQuery(this).parents("li").find('.attachment-woocommerce_thumbnail').css( 'display', 'none' );
                jQuery(this).parents("li").find('.attachment-woocommerce_thumbnail').css( 'display', 'block' );
                
            })
        
                
            });
             
        });
        </script>  
    
  
    <?php
}
add_action('wp_footer', 'wcik_variation_jquery', 0 ); 

}