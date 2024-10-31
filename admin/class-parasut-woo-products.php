<?php

/**
 * The product-specific functionality of the plugin.
 *
 * @link       www.tema.ninja
 * @since      1.0.0
 *
 * @package    Parasut_Woo
 * @subpackage Parasut_Woo/product
 */
 
 class Parasut_Woo_Product {
     
     public function parasut_woo_urun_tipi($post_id) {
         $urun = wc_get_product( $post_id ); 
         if ($urun->is_type('variable')) { 
             $tip = 'varyant';
         } else {
             $product = new WC_Product($post_id);
         }
         
         return $product;
     }
     public function parasut_woo_urun_varyantlari($varyantlar = array()) {
            foreach ($varyantlar as $varyant) {
		        $product = new WC_Product_Variation($varyant);
		        $ozellikler = $product->get_attributes( );
		    	foreach ($ozellikler as $ozellik => $ozellikkey) {
		    		$urun_adi = $product->get_title().' '.$ozellikkey;
		    	    echo $urun_adi;
		    	}
            }
     }
     
     public function parasut_woo_urunu_eslestir($post_id,$code, $name, $tax, $curr, $price, $stock, $qty, $term ) {
            
            $parasut_urun_esle = $parasut->make('product')->create([
          				'code' => $product->get_sku(),
          				'name' => $urun_adi,
          				'vat_rate' => $vergi_orani,
          				'currency' => 'TRL',
          				'list_price' => $product->get_price_excluding_tax(),
          				'archived' => false,
          				'unit' => 'adet',
          				'inventory_tracking' => $product->managing_stock() ? true : false,
          				'initial_stock_count' => empty($product->get_stock_quantity()) ? 0 : $product->get_stock_quantity(),
          				'category_id' => $term_meta['parasut_term_meta'],
		         ]);
	        
	        self::parasut_woo_urun_meta_ekle($post_id,$parasut_urun_esle['product']['id']);
	        
     }
     
     public function parasut_woo_urun_meta_ekle($post_id,$parasut_id) {

            add_post_meta($post_id, 'parasut_urun_id', $parasut_id);
            
     }
     
     
 }