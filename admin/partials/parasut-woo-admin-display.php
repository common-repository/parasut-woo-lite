
    <div class="wrap">
     
        <div id="icon-themes" class="icon32"></div>
        <h2><?php _x('Paraşüt WooCommerce Eklentisi Ayar Ekranı','parasut-woo'); ?></h2>
        <?php settings_errors(); ?>
         
        <?php
            if( isset( $_GET[ 'tab' ] ) ) {
                $active_tab = $_GET[ 'tab' ];
            } else {
                $active_tab = 'api_ayarlari';
            }
        ?>
         
        <h2 class="nav-tab-wrapper">
            
            <a href="?page=parasut-woo-admin&tab=api_ayarlari" class="nav-tab <?php echo $active_tab == 'api_ayarlari' ? 'nav-tab-active' : ''; ?>">API Ayarları</a>
            <a href="?page=parasut-woo-admin&tab=araclar" class="nav-tab <?php echo $active_tab == 'araclar' ? 'nav-tab-active' : ''; ?>">Araçlar</a>
            <a href="?page=parasut-woo-admin&tab=lisans_ayarlari" class="nav-tab <?php echo $active_tab == 'lisans_ayarlari' ? 'nav-tab-active' : ''; ?>">Lisans Ayarları</a>
        </h2>
         
        <form method="post" action="options.php">
            <?php
                 
                if( $active_tab == 'ana_ayarlar') {
                    try { $parasut->authorize();
                        global $wp_roles;

    
            	        $parasut->authorize();
            	        $kategori_listesi = $parasut->make('category')->get([]);
            	        //print_r($wp_roles->roles);
            	        $editable_roles = get_editable_roles();
            	        foreach ($editable_roles as $value => $key) {
            	            //echo translate_user_role($key['name']);
            	        }
            	        foreach ($kategori_listesi['items'] as $kategori => $deger) {
            	            //print_r($deger);
            	            if ($deger['category_type'] == 'Contact') {
            	                //echo $deger['id'];
            	            }
            	        }
            	        
                        settings_fields( 'ana_ayarlar_page' );
                        do_settings_sections( 'ana_ayarlar_page' );
                        
                    } catch (Exception $e) { ?>
                        <p>
            			    <strong>UYARI: </strong> Öncelikle API ayarlarınız doğru ayarlanmalıdır ardından bu ayarları yapılandırabilirsiniz.
            			</p>
            			<p>
            			    <a href="<?php echo admin_url( 'admin.php?page=parasut-woo-admin&tab=api_ayarlari' );?>">Api Ayarlarınızı Yapılandırmak İçin Tıklayın</a>
            			</p>
                    <?php }
                    
                } elseif ($active_tab == 'api_ayarlari') {
                        
                        try {
                            $parasut->authorize();	
                        } catch(Exception $e) { ?>
                          <div id="setting-error-settings_updated" class="error">
            				<p>
            					<strong>HATA: </strong> Kullanıcı Bilgileriniz Doğrulanamıyor. Paraşüt yanıtı: <strong><?php echo $e->getMessage();?></strong>
            				</p>
            			</div>
                        <?php }
                    settings_fields( 'api_page' );
                    do_settings_sections( 'api_page' );
                } elseif ($active_tab == 'lisans_ayarlari') {
                   echo '<br/><br/><br/>Eklentinin Normal Sürü İçin ayarları bu alandan yapabilirsiniz.';
                } elseif ($active_tab == 'araclar'){ 
                   settings_fields( 'araclar_page' );
                    try {
                        $parasut->authorize();
                         
                        do_settings_sections( 'araclar_page' );
                    } catch(Exception $e) { ?>
                          <p>
            			    <strong>UYARI: </strong> Öncelikle API ayarlarınız doğru ayarlanmalıdır ardından bu ayarları yapılandırabilirsiniz.
            			</p>
            			<p>
            			    <a href="<?php echo admin_url( 'admin.php?page=parasut-woo-admin&tab=api_ayarlari' );?>">Api Ayarlarınızı Yapılandırmak İçin Tıklayın</a>
            			</p>
              <?php }
                    if (isset($_GET['islem']) && $_GET['islem'] == 'urunleri_eslestir') {
                        
                         Parasut_Woo_Admin::parasut_woo_urunleri_eslestir();
                    		
                    } 
                    if(isset($_GET['islem']) && $_GET['islem'] == 'eslestirmeleri_sil') {
                        Parasut_Woo_Admin::parasut_woo_eslestirmeleri_sil();
                    }
                    
                   
                }
                if (isset($_GET['tab']) && $_GET['tab'] != 'araclar') {
                    submit_button();
                }
                 
            ?>
        </form>
         
    </div><!-- /.wrap -->