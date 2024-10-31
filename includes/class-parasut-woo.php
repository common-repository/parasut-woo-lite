<?php
use Parasut\Client;
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       www.tema.ninja
 * @since      1.0.0
 *
 * @package    Parasut_Woo
 * @subpackage Parasut_Woo/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Parasut_Woo
 * @subpackage Parasut_Woo/includes
 * @author     TemaNinja <destek@tema.ninja>
 */
class Parasut_Woo {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Parasut_Woo_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		
		
		$this->parasut_woo_plugin_name = 'Parasut Woo Lite';
		$this->parasut_woo_version = '1.0.1';
		$this->parasut_woo_dir_name = plugin_basename( __FILE__ );

		$this->parasut_woo_load_dependencies();
		$this->parasut_woo_set_locale();
		$this->parasut_woo_define_admin_hooks();
		$this->parasut_woo_define_public_hooks();
	

		

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Parasut_Woo_Loader. Orchestrates the hooks of the plugin.
	 * - Parasut_Woo_i18n. Defines internationalization functionality.
	 * - Parasut_Woo_Admin. Defines all hooks for the admin area.
	 * - Parasut_Woo_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function parasut_woo_load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-parasut-woo-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-parasut-woo-i18n.php';
	
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-parasut-woo-admin.php';
		
		/**
		 * The class responsible for defining product functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-parasut-woo-products.php';
		
		/**
		*  Paraşüt Mayoz Project
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'parasut/vendor/autoload.php';
		
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-parasut-woo-public.php';
		
		/**
		 * The class responsible for authenticating with Parasut and the backend
		 * side of the site.
		 */
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-parasut-authorize.php';

		$this->loader = new Parasut_Woo_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Parasut_Woo_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function parasut_woo_set_locale() {

		$plugin_i18n = new Parasut_Woo_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'parasut_woo_load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function parasut_woo_define_admin_hooks() {

		$plugin_admin = new Parasut_Woo_Admin( $this->parasut_woo_get_plugin_name(), $this->parasut_woo_get_version() );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'parasut_woo_display_admin_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'parasut_api_settings_init' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'parasut_api_dogrula' );
		$this->loader->add_action( 'admin_footer', $plugin_admin, 'parasut_woo_varyasyon_js' );
		$this->loader->add_action( 'save_post_product', $plugin_admin, 'parasut_urun_save_meta_box',10,2 );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'parasut_woo_enqueue_styles' ,9999);
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'parasut_woo_enqueue_scripts' );
		$this->loader->add_action('product_cat_add_form_fields', $plugin_admin, 'parasut_woo_support_categories',10, 2);
		$this->loader->add_action('product_cat_edit_form_fields', $plugin_admin, 'parasut_woo_categories_support_field',10, 2);
		$this->loader->add_action( 'edited_product_cat', $plugin_admin,'parasut_woo_save_taxonomy_custom_meta', 10, 2 );  
		$this->loader->add_action( 'create_product_cat', $plugin_admin,'parasut_woo_save_taxonomy_custom_meta', 10, 2 );
		

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function parasut_woo_define_public_hooks() {

		$plugin_public = new Parasut_Woo_Public( $this->parasut_woo_get_plugin_name(), $this->parasut_woo_get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'parasut_woo_fe_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'parasut_woo_fe_enqueue_scripts' );



	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function parasut_woo_run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function parasut_woo_get_plugin_name() {
		return $this->parasut_woo_plugin_name;
	}


	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Parasut_Woo_Loader    Orchestrates the hooks of the plugin.
	 */
	public function parasut_woo_get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function parasut_woo_get_version() {
		return $this->version;
	}

}
