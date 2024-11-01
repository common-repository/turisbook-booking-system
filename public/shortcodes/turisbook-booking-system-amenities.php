<?php
function Turisbook_bs_unit_amenities_Shortcode( $atts ) {

	$tbs = new Turisbook_Booking_System();

	$id = get_the_ID();
	$amenities = wp_get_post_terms($id, 'turisbook_amenity');

	$toreturn = "";
	ob_start();
	include TURISBOOK_PUBLIC_PATH . '/partials/turisbook-booking-system-amenities.php';
	$toreturn .= ob_get_clean();


	return $toreturn;
}

add_shortcode( 'turisbook_unit_amenities', 'Turisbook_bs_unit_amenities_Shortcode' );
