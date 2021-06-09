<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       ricardoshaffer.com
 * @since      1.0.0
 *
 * @package    Resource_Scheduling
 * @subpackage Resource_Scheduling/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Resource_Scheduling
 * @subpackage Resource_Scheduling/includes
 * @author     Ricardo Shaffer <hello@ricardoshaffer.com>
 */
class Resource_Scheduling_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'resource-scheduling',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
