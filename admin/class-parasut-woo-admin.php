<?php
use Parasut\Client;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.tema.ninja
 * @since      1.0.0
 *
 * @package    Parasut_Woo
 * @subpackage Parasut_Woo/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Parasut_Woo
 * @subpackage Parasut_Woo/admin
 * @author     TemaNinja <destek@tema.ninja>
 */
class Parasut_Woo_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	 
	private $plugin_name = 'Parasut Woo Lite';

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version = '1.0.1';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	

	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function parasut_woo_enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Parasut_Woo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Parasut_Woo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/parasut-woo-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function parasut_woo_enqueue_scripts() {
		global $pagenow;
		global $post;
		
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Parasut_Woo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Parasut_Woo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/parasut-woo-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'parasut', array() );
		if (!empty($post->ID)) {
			$urun = get_post($post->ID);
			$api_ayar = get_option( 'parasut_api_settings' );
			
			if (( $pagenow == 'post.php' ) && $urun->post_type == 'product') {
				$product = wc_get_product( $post->ID );
				
				$urun = wc_get_product( $post->ID );
	            
				if ($urun->is_type('variable')) { 
					
					$product = new WC_Product_Variable($post->ID);
	            	
			            	$urunler = $product->get_children();
			            			   
						    foreach ($urunler as $urun) {
						    
						    	$parasut_urun_id = get_post_meta( $urun, 'parasut_urun_id', true );
						    	
						    	if (!empty($parasut_urun_id)) {
						    			$veriler = array(
						    					'varyant'	=> $urun,
						    					'urun_class' => '',
									    		'urun_status' => '<strong style="padding:0 5px;">'._e('Varyasyonlu Ürün','parasut-woo').'</strong>',
									    		'urun_islem'  => '' 
								    			);
						    	}
						    	
						    }
					
					
				} else {
					
					$parasut_urun_id = get_post_meta( $post->ID, 'parasut_urun_id', true );
					
				    if (empty($parasut_urun_id)) { 
				    	$veriler = array(
				    		'urun_class' => ' pasif',
				    		'urun_status' => '<strong style="color:red; padding:0 5px;">'._e('Eşleşme Yok','parasut-woo').'</strong>',
				    		'urun_islem'  => '<a href="" onclick="document.getElementById("post").submit();" style="padding-left:5px;">Eşleştir</a>' 
				    		);
				     } else { 
				     	
				     	$veriler = array(
				    		'urun_class' => '',
				    		'urun_status' => '<strong style="color:green; padding:0 5px;">'._e('Eşleşme Var','parasut-woo').'</strong>',
				    		'urun_islem'  => '<a href="https://uygulama.parasut.com/'.$api_ayar['parasut_api_company_id'].'/hizmet-ve-urunler/'.$parasut_urun_id.'" style="padding-left:5px;" target="_blank">Ürüne Git</a>' 
				    		);
				     }
					
				}
				wp_localize_script( $this->plugin_name, 'parasut', $veriler );
				
			}
		}
		

	}
	public function parasut_api_dogrula($parasut) {
		global $parasut;
		$options = get_option( 'parasut_api_settings' );
		$parasut = new Client([
		  'client_id'     => $options['parasut_api_client_id'],
		  'client_secret' => $options['parasut_api_client_secret'],
		  'username'      => $options['parasut_api_client_username'],
		  'password'      => $options['parasut_api_client_password'],
		  'company_id'    => $options['parasut_api_company_id'],
		  'grant_type'    => 'password',
		  'redirect_uri'  => 'urn:ietf:wg:oauth:2.0:oob',
		]);
		
	}

	public function parasut_woo_display_admin_page() {
	         add_menu_page(
		        __( 'Paraşüt Woocommerce Eklentisi', 'textdomain' ),
		        __( 'Paraşüt Woo','textdomain' ),
		        'manage_options',
		        'parasut-woo-admin',
		        array($this, 'parasut_woo_showPage'),
		        plugins_url('parasut-woo-lite/admin/img/parasut-icon.png')
		    );
	}
	
	public function parasut_woo_showPage() {
		include plugin_dir_path( dirname( __FILE__ ) ).'includes/class-parasut-authorize.php';
		include plugin_dir_path( dirname( __FILE__ ) ).'admin/partials/parasut-woo-admin-display.php';
	}
	

	public function parasut_api_settings_init(  ) { 
	
		register_setting( 'api_page', 'parasut_api_settings' );
		register_setting( 'araclar_page', 'parasut_araclar_ayarlar' );
		register_setting( 'parasut_license_page', 'parasut_license_key' );
		
		add_settings_section(
			'parasut_api_plugin_section', 
			__( 'API Ayarlarınız bu alandan yapılmalıdır.', 'parasut-woo' ), 
			'Parasut_Woo_Admin::parasut_api_settings_section_callback', 
			'api_page'
		);
	
		add_settings_section(
			'parasut_araclar_ayarlar_section', 
			__( 'Paraşüt Entegrasyonu için ana ayarları bu alandan yapılmalıdır.', 'parasut-woo' ), 
			'Parasut_Woo_Admin::parasut_araclar_ayarlar_section_callback', 
			'araclar_page'
		);
		
		add_settings_section(
			'parasut_license_section', 
			__( 'Paraşüt Entegrasyonu için ana ayarları bu alandan yapılmalıdır.', 'parasut-woo' ), 
			'Parasut_Woo_Admin::parasut_license_page', 
			'parasut_license_page'
		);
	
		add_settings_field( 
			'parasut_api_client_id', 
			__( 'Paraşüt Client ID', 'parasut-woo' ), 
			'Parasut_Woo_Admin::parasut_api_render', 
			'api_page', 
			'parasut_api_plugin_section',
			array ( 'context' => 'client_id' ) // custom arguments
		);
	
		add_settings_field( 
			'parasut_api_client_secret', 
			__( 'Paraşüt Client Secret', 'parasut-woo' ), 
			'Parasut_Woo_Admin::parasut_api_render', 
			'api_page', 
			'parasut_api_plugin_section',
			array ( 'context' => 'client_secret' )
		);
		add_settings_field( 
			'parasut_api_company_id', 
			__( 'Paraşüt Firma No', 'parasut-woo' ), 
			'Parasut_Woo_Admin::parasut_api_render', 
			'api_page', 
			'parasut_api_plugin_section',
			array ( 'context' => 'company_id' )
		);
	
		add_settings_field( 
			'parasut_api_client_username', 
			__( 'Paraşüt Kullanıcı Adı', 'parasut-woo' ), 
			'Parasut_Woo_Admin::parasut_api_render', 
			'api_page', 
			'parasut_api_plugin_section',
			array ( 'context' => 'client_username' )
		);
	
		add_settings_field( 
			'parasut_api_client_password', 
			__( 'Paraşüt Kullanıcı Şifreniz', 'parasut-woo' ), 
			'Parasut_Woo_Admin::parasut_api_render', 
			'api_page', 
			'parasut_api_plugin_section',
			array ( 'context' => 'client_password' )
		);
		add_settings_field( 
			'parasut_uye_kategorisi', 
			__( 'Paraşüt Üye Kategorinizi Eşleştirin', 'parasut-woo' ), 
			'Parasut_Woo_Admin::parasut_ana_ayarlar_render', 
			'ana_ayarlar_page', 
			'parasut_ana_ayarlar_section',
			array ( 'context' => 'uye_kategorisi' )
		);
		
		
		
		add_settings_field( 
			'parasut_urun_eslestirmeleri', 
			__( 'Mağaza ve Paraşüt Ürünlerini Eşleştir', 'parasut-woo' ), 
			'Parasut_Woo_Admin::parasut_araclar_render', 
			'araclar_page', 
			'parasut_araclar_ayarlar_section',
			array ( 'context' => 'urun_eslestir' )
		);
		add_settings_field( 
			'parasut_eslestirmeleri_sil', 
			__( 'Mağazadaki Eşleştirmeleri Sil', 'parasut-woo' ), 
			'Parasut_Woo_Admin::parasut_araclar_render', 
			'araclar_page', 
			'parasut_araclar_ayarlar_section',
			array ( 'context' => 'eslestirmeleri_sil' )
		);
		
			
	    
	
	
	}
	public static function parasut_api_render($args) { 

		$options = get_option( 'parasut_api_settings' );
		if ( 'client_id' === $args[ 'context' ] ) {
	        ?>
	       
			<input type='text' name='parasut_api_settings[parasut_api_client_id]' value='<?php echo esc_html($options['parasut_api_client_id']); ?>'>
			<?php
	    } elseif  ( 'client_secret' === $args[ 'context' ] ) {
	        ?>
			<input type='text' name='parasut_api_settings[parasut_api_client_secret]' value='<?php echo esc_html($options['parasut_api_client_secret']); ?>'>
			<?php
	    } elseif  ( 'company_id' === $args[ 'context' ] ) {
	        ?>
			<input type='text' name='parasut_api_settings[parasut_api_company_id]' value='<?php echo esc_html($options['parasut_api_company_id']); ?>'>
			<?php
	    } elseif  ( 'client_username' === $args[ 'context' ] ) {
	        ?>
			<input type='text' name='parasut_api_settings[parasut_api_client_username]' value='<?php echo esc_html($options['parasut_api_client_username']); ?>'>
			<?php
	    } elseif  ( 'client_password' === $args[ 'context' ] ) {
	        ?>
			<input type='text' name='parasut_api_settings[parasut_api_client_password]' value='<?php echo esc_html($options['parasut_api_client_password']); ?>'>
			<?php
	    } else {
	        print 'Unknown context!';
	    }

	}
	public function parasut_ana_ayarlar_render($args) { 
		global $parasut;
		$options = get_option( 'parasut_ana_ayarlar' );
		

	}
	public static function parasut_araclar_render($args) { 

		$options = get_option( 'parasut_araclar_ayarlar' );
		if ( 'urun_stoklari' === $args[ 'context' ] ) {
	        ?>
		       
			<?php
	    } elseif  ( 'urun_eslestir' === $args[ 'context' ] ) {
	        ?>
			 <a class="button button-primary button-hero load-customize hide-if-no-customize" href="admin.php?page=parasut-woo-admin&tab=araclar&islem=urunleri_eslestir"><?php _e('Tüm Ürünleri Eşleştir','parasut-woo');?></a>
			 <p> <?php _e('Bu butona tıkladığınızda mağazanızda henüz Paraşüt hesabınıza eklenmemiş ürünler varsa bunlar artık eşleştirilmiş hale gelecektir.','parasut-woo');?></p>
			 <p> <?php _e('ÖNEMLİ: Eğer Paraşüt Üzerinden stok takibi yapacaksanız öncelikle mutlaka stok takibini tüm ürünler için etkinleştirmelisiniz.','parasut-woo');?></p>
			 
			<?php
	    } elseif  ( 'eslestirmeleri_sil' === $args[ 'context' ] ) {
	        ?>
			 <a class="button button-primary button-hero load-customize hide-if-no-customize" href="admin.php?page=parasut-woo-admin&tab=araclar&islem=eslestirmeleri_sil"><?php _e('EŞLEŞTİRMELERİ SİL','parasut-woo');?></a>
			 <p><?php _e('Bu butona tıkladığınızda mağazanızda Paraşüt hesabınız ile eşleştirilmiş tüm ürünlerin eşleştirmeleri kaldırılacaktır.','parasut-woo');?></p>
			 <p><?php _e('ÖNEMLİ: Eşleştirmeleri kaldırdıktan sonra eğer tekrar ürünleri eşleştirirseniz aynı ürünler PARAŞÜT\'e tekrar eklenecektir ve Paraşüt üzerinden de ürünlerinizi kaldırmazsanız aynı ürünlerden 2 şer adet görür hale geleceksiniz.','parasut-woo');?></p>
			<?php
	    } else {
	        print 'Unknown context!';
	    }
	}
	
	
	public static function parasut_api_settings_section_callback() {
		echo __( 'Api ayarlarınızı bilgi bankamızda yer alan <a href="http://bilgibankasi.tema.ninja/article/46-parasut-eklentisi-api-ayarlari" target="_blank">makalemizdeki</a> gibi yapabilirsiniz.', 'parasut-woo' );
	}
	public static function parasut_ana_ayarlar_section_callback() {
		echo __( 'Genel ayarlarınızı bilgi bankamızda yer alan <a href="http://bilgibankasi.tema.ninja/article/46-parasut-eklentisi-api-ayarlari" target="_blank">makalemizdeki</a> gibi yapabilirsiniz.', 'parasut-woo' );
	}
	public static function parasut_araclar_ayarlar_section_callback() {
		echo __( 'Genel ayarlarınızı bilgi bankamızda yer alan <a href="http://bilgibankasi.tema.ninja/article/46-parasut-eklentisi-api-ayarlari" target="_blank">makalemizdeki</a> gibi yapabilirsiniz.', 'parasut-woo' );
	}
	public static function parasut_license_page_callback() {
		echo __( 'Genel ayarlarınızı bilgi bankamızda yer alan <a href="/" target="_blank">makalemizdeki</a> gibi yapabilirsiniz.', 'parasut-woo' );
	}
	public function parasut_woo_support_categories($term) {
		global $parasut;
		if (!empty($term->term_id)) {
			$t_id = $term->term_id;
		
		$term_meta = get_option( "taxonomy_$t_id" );
		$parasut->authorize();

	    $kategori_listesi = $parasut->make('category')->get([]); ?>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="<?php echo $term_meta['parasut_term_meta'];?>"><?php _e('Paraşüt Kategorinizi Seçin', 'parasut-woo' ); ?></label></th>
				<td>
					<select name="term_meta[parasut_term_meta]">
				       <?php foreach($kategori_listesi['items'] as $kategori => $deger) { 
				        if ($deger['category_type'] == 'Product') {?>
				        	<option value="<?php echo $deger['id']; ?>"><?php echo $deger['name'];?></option>
				        <?php } } ?>
				    </select>
				</td>
			</tr>	    
		<?php
		}
		
	}
	function parasut_woo_categories_support_field($term) {
		global $parasut;
		$t_id = $term->term_id;
		//print_r($term);
		$term_meta = get_option( "taxonomy_$t_id" );
		
		$parasut->authorize();
	    	
	    $kategori_listesi = $parasut->make('category')->get([]); ?>
	   
		<tr class="form-field">
			<th scope="row" valign="top"><label for="term_meta[parasut_term_meta]"><?php _e( 'Paraşüt Kategorinizi Seçin', 'parasut-woo' ); ?></label></th>
				<td>
					<select name="term_meta[parasut_term_meta]">
				       <?php foreach($kategori_listesi['items'] as $kategori => $deger) { 
				        if ($deger['category_type'] == 'Product') {?>
				        	<option <?php selected( $term_meta['parasut_term_meta'], $deger['id'] ); ?> value="<?php echo $deger['id']; ?>"><?php echo $deger['name'];?></option>
				        <?php } } ?>
				    </select>
				</td>
			</tr>	    
		<?php
	}
	function parasut_woo_save_taxonomy_custom_meta( $term_id ) {
		if ( isset( $_POST['term_meta'] ) ) {
			$t_id = $term_id;
			$term_meta = get_option( "taxonomy_$t_id" );
			$cat_keys = array_keys( $_POST['term_meta'] );
			foreach ( $cat_keys as $key ) {
				if ( isset ( $_POST['term_meta'][$key] ) ) {
					$term_meta[$key] = sanitize_key($_POST['term_meta'][$key]);
				}
			}
			// Save the option array.
			update_option( "taxonomy_$t_id", $term_meta );
		
		}
	}
	
	
	public function parasut_urun_save_meta_box() {
		global $post;
		global $parasut;
		global $woocoommerce;
		
		if(!empty($post->ID)){ 
			self::parasut_woo_urunu_eslestir($post->ID);
		}
		
		
	}
	
	public function parasut_woo_urun_tipi($id) {
		$urun = wc_get_product( $id );
	}	
	
	public function urunleri_listele() {
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'meta_query' => array(
                    array(
                         'key' => 'parasut_urun_id',
                         'compare' => 'NOT EXISTS' // this should work...
                        ),
                    )
		);
		$sorgu = new WP_Query( $args );
		if ( $sorgu->have_posts() ) {
        			while ( $sorgu->have_posts() ) : $loop->the_post();
        				$this->urunleri_eslestir($sorgu->ID);
        				print_r($sorgu);
        			endwhile;
        		}
	}	
	
	
	public function parasut_woo_urunleri_eslestir() {
				global $parasut;
				
				$parasut->authorize();
				$args = array(
        			'post_type' => 'product',
        			'posts_per_page' => -1,
        			'meta_query' => array(
                            array(
                                 'key' => 'parasut_urun_id',
                                 'compare' => 'NOT EXISTS' // this should work...
                                ),
                            )
        			);
        		$loop = new WP_Query( $args );
        		global $product;
        		if ( $loop->have_posts() ) {
        			while ( $loop->have_posts() ) : $loop->the_post();
            			$parasut_urun_id = get_post_meta( get_the_ID(), 'parasut_urun_id', true );
            					
            						self::parasut_woo_urunu_eslestir(get_the_ID());
            					
                    			endwhile;
                    			printf( '<div class="%1$s"><p>%2$s</p></div>', 'updated notice notice-success is-dismissible', _e('Tüm ürünler başarıyla eklendi.','parasut-woo') ); 
                    		} else {
                    		    printf( '<div class="%1$s"><p>%2$s</p></div>', 'updated notice notice-success is-dismissible', _e('Tebrikler! Tüm Ürünler Eşleştirilmiş Durumda.','parasut-woo') );
                    		}
                    		wp_reset_postdata();
                    		
	}
	public function parasut_woo_urunu_eslestir($id) {
			global $parasut;
			$parasut->authorize();
			$urun = wc_get_product( $id );
			if ($urun->is_type('variable')) {
			    $product = new WC_Product_Variable($id);
			    $v_urunler = $product->get_children();
			   // print_r($urun->get_id());
    			    foreach ($v_urunler as $v_urun) {
    			    	$parasut_urun_id = get_post_meta( $v_urun, 'parasut_urun_id', true );
    			        $product = new WC_Product_Variation($v_urun);
    			        
    			    	$urun_adi = $product->get_name();
    			    
    			        $vergi_bul = WC_Tax::get_rates( $product->get_tax_class() );
    			        $rates = array_shift(WC_Tax::get_rates( $product->get_tax_class() ));
                        $vergi_orani = number_format(array_shift($rates),1,'.','.');
                        $price = $product->get_price_excluding_tax();
                        $inventory = $product->managing_stock() ? true : false;
                        $qty = empty($product->get_stock_quantity()) ? 0 : $product->get_stock_quantity();
                        $urun_kategorisi = wp_get_post_terms($urun->get_id(), 'product_cat', array("fields" => "ids"));
                        $term_id = reset($urun_kategorisi);
    			        $term_meta = get_option( "taxonomy_$term_id" );
    			        
    			        self::parasut_islem($v_urun,$product->get_sku(),$urun_adi,$vergi_orani,'TRL',$price,'false','adet',$inventory,$qty,$term_meta);
    			    }
			    
    			} else {
    			    $product = new WC_Product($id);
    			    $vergi_bul = WC_Tax::get_rates( $product->get_tax_class() );
			        $rates = array_shift(WC_Tax::get_rates( $product->get_tax_class() ));
                    $vergi_orani = number_format(array_shift($rates),1,'.','.');
                    $price = $product->get_price_excluding_tax();
                    $inventory = $product->managing_stock() ? true : false;
                    $qty = empty($product->get_stock_quantity()) ? 0 : $product->get_stock_quantity();
                    $urun_kategorisi = wp_get_post_terms($product->get_id(), 'product_cat', array("fields" => "ids"));
                    $term_id = reset($urun_kategorisi);
			        $term_meta = get_option( "taxonomy_$term_id" );
			        $parasut_urun_id = get_post_meta( $v_urun, 'parasut_urun_id', true );
			        
			        self::parasut_islem($id,$product->get_sku(),$product->get_name(),$vergi_orani,'TRL',$price,'false','adet',$inventory,$qty,$term_meta);
			        
    			}
	}
	
	public function parasut_islem($id,$sku,$name,$tax,$cur = 'TRL',$price,$archive,$unit='adet',$inventory,$qty,$category) {
		global $parasut;
		$parasut->authorize();
		
			$parasut_meta_id = get_post_meta($id,'parasut_urun_id',true);
		
			
			if (empty($parasut_meta_id) || is_null($parasut_meta_id)) {
				$parasut_urun_esle = $parasut->make('product')->create([
					'code' => $sku,
					'name' => $name,
					'vat_rate' => $tax,
					'currency' => $cur,
					'list_price' => $price,
					'archived' => $archive,
					'unit' => $unit,
					'inventory_tracking' => $inventory,
					'initial_stock_count' => $qty,
					'category_id' => $category,
			    ]);

			    add_post_meta($id, 'parasut_urun_id', $parasut_urun_esle['product']['id']);
			} else {
				$parasut_urun_esle = $parasut->make('product')->update($parasut_meta_id,[
					'code' => $sku,
					'name' => $name,
					'vat_rate' => $tax,
					'currency' => $cur,
					'list_price' => $price,
					'archived' => $archive,
					'unit' => $unit,
					'inventory_tracking' => $inventory,
					'initial_stock_count' => $qty,
					'category_id' => $category,
			    ]);
			}
	}
	
	public function parasut_woo_varyasyon_js(){
		global $pagenow;
		global $post;
		if(!empty($post->ID)) {
			$post_id = $post->ID;
			$urun = get_post($post->ID);
	
			$api_ayar = get_option( 'parasut_api_settings' );
			
			if (( $pagenow == 'post.php' ) && $urun->post_type == 'product') { 
				$product = wc_get_product( $post_id );
				
				
				$urun = wc_get_product( $post_id );
	            			
				if ($urun->is_type('variable')) { 
					$product = new WC_Product_Variable($post_id);
					$urunler = $product->get_children();
					$js = '<script>';
						//$js .= 'jQuery( document ).ready(function() {';
							
							$js .= "jQuery('#woocommerce-product-data').on('woocommerce_variations_loaded', function(event) {"; 
							foreach ($urunler as $urun) { 
								$parasut_urun_id = get_post_meta($urun,'parasut_urun_id',true);
								
								if (!empty($parasut_urun_id)) $urun_url = "https://uygulama.parasut.com/".$api_ayar['parasut_api_company_id'].'/hizmet-ve-urunler/'.$parasut_urun_id;
								if (empty($parasut_urun_id)) $class = ' pasif';
								 //$js .= "$('<div class='test'></div>').insertBefore('a[rel=".$deger."]');";
								 $js .= "jQuery('";
								 $js .= '<a href="'.$urun_url.'" target="_blank">';
								 $js .= '<div class="misc-parasut-visibility-variable'.$class.'" id="parasut-status">';
								 
								 $js .='</div>';
								 $js .= '</a>';
								 $js .= "').insertAfter";
								 $js .= "('a.remove_variation[rel=".$urun."]');";
								    }
							$js .= "});";
						//$js .= "});";
					$js .= '</script>';
					echo $js;
				}
				
			}
		}

	}
	
	public function eslestirmeleri_sil() {
		$args = array(
    			'post_type' => 'product',
    			'posts_per_page' => -1
    			);
    		$the_query = new WP_Query( $args );	
                if ( $the_query->have_posts() ) {
                	while ( $the_query->have_posts() ) {
                		$the_query->the_post();
                		$urun = wc_get_product( get_the_ID() );
            			delete_post_meta_by_key('parasut_urun_id');
						if ($urun->is_type('variable')) { 
							$product = new WC_Product_Variable(get_the_ID());
							$urunler = $product->get_children();
							foreach ($urunler as $urun) { 
								delete_post_meta($urun,'parasut_urun_id');
							}
                		} else {
                			delete_post_meta_by_key('parasut_urun_id');
                		}
                	wp_reset_postdata();
                }
                printf( '<div class="%1$s"><p>%2$s</p></div>', 'updated notice notice-success is-dismissible', _e('Tüm ürün eşleştirmeleri başarıyla kaldırıldı.','parasut-woo') );
            }
	}
	
	
		

}
