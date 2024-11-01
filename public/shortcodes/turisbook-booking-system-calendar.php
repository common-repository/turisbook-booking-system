<?php
function get_turisbook_calendar( $atts ) {
	$tbs = new Turisbook_Booking_System();
	$get_checkin = isset($_GET['checkin']) ? sanitize_text_field($_GET['checkin']) : false;
	$get_checkout = isset($_GET['checkout']) ? sanitize_text_field($_GET['checkout']) : false;

	$checkin_checkout = ['hasdates'=> false];

	if($get_checkin && $get_checkout)
		$checkin_checkout = $tbs->validateCheckinCheckout($get_checkin, $get_checkout,'d-m-Y');

	$hotel_id = get_post_meta(get_the_ID(),'turisbook_room_establishment', true);
	$toreturn = "";
	ob_start();
	include TURISBOOK_PUBLIC_PATH . '/partials/turisbook-book-modal.php';
	$toreturn .= ob_get_clean();


	return $toreturn;
}

add_shortcode( 'turisbook_calendar', 'get_turisbook_calendar' );
