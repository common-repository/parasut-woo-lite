<?php
/**
 *
 * Plugin Name: Parasut Woo Lite
 * Plugin URI: https://www.tema.ninja
 * Description: Paraşüt Ürünlerinizi ve Faturalarınızı Senkronize Edin
 * Author: TemaNinja
 * Author URI: https://www.tema.ninja
 * Text Domain: parasut-woo
 * Domain Path: /languages
 * Version: 1.0.5
 */
 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/class-parasut-updater.php';


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-parasut-woo-activator.php
 */
 
function activate_parasut_woo() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-parasut-woo-activator.php';
	Parasut_Woo_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-parasut-woo-deactivator.php
 */
function deactivate_parasut_woo() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-parasut-woo-deactivator.php';
	Parasut_Woo_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_parasut_woo' );
register_deactivation_hook( __FILE__, 'deactivate_parasut_woo' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-parasut-woo.php';



/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_parasut_woo() {

	$plugin = new Parasut_Woo();
	$plugin->parasut_woo_run();

}
run_parasut_woo();
