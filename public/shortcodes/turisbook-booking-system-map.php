<?php
function Turisbook_bs_room_map ( $atts ) {

	$a = shortcode_atts( array(
		'width' => '90vw',
		'height' => '90vh',
	), $atts );

	$tbs = new Turisbook_Booking_System();


	global $post;

	$toreturn = "";
	$latitude = get_post_meta($post->ID, "turisbook_room_latitude",true);
	$longitude = get_post_meta($post->ID, "turisbook_room_longitude",true);
	$map_key = get_option('elementor_google_maps_api_key',true);

	ob_start();
	include TURISBOOK_PUBLIC_PATH . '/partials/turisbook-booking-system-room-map.php';
	$toreturn .= ob_get_clean();


	return $toreturn;
}

add_shortcode( 'turisbook_room_map', 'Turisbook_bs_room_map' );
