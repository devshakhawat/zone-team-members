<?php
/**
 * Plugin Name: Zone7 Team Members
 * Plugin URI:  https://example.com/
 * Description: A custom plugin to display team members.
 * Version:     1.0.0
 * Author:      Shakhawat
 * Author URI:  https://example.com/
 * License:     GPLv3
 * Text Domain: teamzone
 *
 * @package TeamMembers
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 * Defining constants
 */
define( 'ZTEAM_VERSION', '1.0.0' );
define( 'ZTEAM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ZTEAM_PLUGIN_URI', plugins_url( '', __FILE__ ) );

// Load Composer autoloader if it exists.
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

// Initialize the plugin.
if ( class_exists( 'Shakhawat\\Team\\Init' ) ) {
	\Shakhawat\Team\Init::get_instance();
}

/**
 * Register activation hook.
 */
register_activation_hook( __FILE__, 'zteam_activate_plugin' );

/**
 * Activation logic.
 */
function zteam_activate_plugin() {
	if ( class_exists( 'Shakhawat\\Team\\DummyData' ) ) {
		$dummy_data = new \Shakhawat\Team\DummyData();
		if ( ! $dummy_data->is_dummy_data_imported() ) {
			$dummy_data->import_dummy_data();
		}
	}
}
