<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.neteuro.pt/
 * @since      1.0.0
 *
 * @package    Turisbook_Booking_System
 * @subpackage Turisbook_Booking_System/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Turisbook_Booking_System
 * @subpackage Turisbook_Booking_System/includes
 * @author     Neteuro <apoiotecnico@neteuro.net>
 */
class Turisbook_Booking_System_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'turisbook-booking-system',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
