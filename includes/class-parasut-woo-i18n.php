<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       www.tema.ninja
 * @since      1.0.0
 *
 * @package    Parasut_Woo
 * @subpackage Parasut_Woo/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Parasut_Woo
 * @subpackage Parasut_Woo/includes
 * @author     TemaNinja <destek@tema.ninja>
 */
class Parasut_Woo_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function parasut_woo_load_plugin_textdomain() {

		load_plugin_textdomain(
			'parasut-woo',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
