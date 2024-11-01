<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.neteuro.pt/
 * @since             1.0.0
 * @package           Turisbook_Booking_System
 *
 * @wordpress-plugin
 * Plugin Name:       Turisbook Booking System
 * Plugin URI:        https://www.neteuro.pt/turisbook
 * Description:       Connect your webiste with the Turisbook.com platform API
 * Version:           1.3.7
 * Author:            Turisbook
 * Author URI:        https://www.neteuro.pt/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       turisbook-booking-system
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
define( 'TURISBOOK_BOOKING_SYSTEM_VERSION', '1.3.7' );
define( 'TURISBOOK_API_URL', 'https://turisbook.com/api/v1' );
define( 'TURISBOOK_ADMIN_PATH', plugin_dir_path( __FILE__ ) . 'admin' );
define( 'TURISBOOK_PUBLIC_PATH', plugin_dir_path( __FILE__ ) . 'public' );
define( 'TURISBOOK_PUBLIC_URL', plugin_dir_url( __FILE__ ) . 'public' );
define( 'TURISBOOK_PATH', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-turisbook-booking-system-activator.php
 */
function activate_turisbook_booking_system() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-turisbook-booking-system-activator.php';
	Turisbook_Booking_System_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-turisbook-booking-system-deactivator.php
 */
function deactivate_turisbook_booking_system() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-turisbook-booking-system-deactivator.php';
	Turisbook_Booking_System_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_turisbook_booking_system' );
register_deactivation_hook( __FILE__, 'deactivate_turisbook_booking_system' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-turisbook-booking-system.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_turisbook_booking_system() {

	$plugin = new Turisbook_Booking_System();
	$plugin->run();

}
run_turisbook_booking_system();
