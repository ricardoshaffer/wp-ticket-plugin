<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              ricardoshaffer.com
 * @since             1.0.0
 * @package           Resource_Scheduling
 *
 * @wordpress-plugin
 * Plugin Name:       Resource Scheduling
 * Plugin URI:        ricardoshaffer.com/projects
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Ricardo Shaffer
 * Author URI:        ricardoshaffer.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       resource-scheduling
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'RESOURCE_SCHEDULING_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-resource-scheduling-activator.php
 */
function activate_resource_scheduling() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-resource-scheduling-activator.php';
	Resource_Scheduling_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-resource-scheduling-deactivator.php
 */
function deactivate_resource_scheduling() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-resource-scheduling-deactivator.php';
	Resource_Scheduling_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_resource_scheduling' );
register_deactivation_hook( __FILE__, 'deactivate_resource_scheduling' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-resource-scheduling.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_resource_scheduling() {

	$plugin = new Resource_Scheduling();
	$plugin->run();

}
run_resource_scheduling();
