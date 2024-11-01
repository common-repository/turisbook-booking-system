<?php
function Turisbook_bs_booking_Slider( $atts ) {

	$a = shortcode_atts( array(
		'height' => '90vh',
		'background_position_x' => 'center',
		'background_position_y' => 'center',
	), $atts );

	$tbs = new Turisbook_Booking_System();

	global $post;

	$toreturn = "";
	$images_ids = get_post_meta($post->ID, "turisbook_room_gallery",true);

	$slider = [];

	$images = explode(',',$images_ids);

	foreach($images as $id){
		$slider[] = wp_get_attachment_url($id);
	}

	ob_start();
	include TURISBOOK_PUBLIC_PATH . '/partials/turisbook-booking-system-slider.php';
	$toreturn .= ob_get_clean();


	return $toreturn;
}

add_shortcode( 'turisbook_slider', 'Turisbook_bs_booking_Slider' );
