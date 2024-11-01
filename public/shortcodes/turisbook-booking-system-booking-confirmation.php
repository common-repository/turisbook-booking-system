<?php
function Turisbook_bs_booking_confirmation_Shortcode( $atts ) {
	$tbs = new Turisbook_Booking_System();
	$toreturn = "";
	$book_id = isset( $_COOKIE['tbs-book_id-cookie'] ) && is_numeric($_COOKIE['tbs-book_id-cookie']) ? (int)$_COOKIE['tbs-book_id-cookie'] : 0;
	$hotel_id = isset( $_COOKIE['tbs-book_hotel_id-cookie'] ) && is_numeric($_COOKIE['tbs-book_hotel_id-cookie']) ? (int)$_COOKIE['tbs-book_hotel_id-cookie'] : 0;

	// $book_id = 4442;
	//if($book_id == 0) return "";

	ob_start();

	$result = $tbs->getBook($hotel_id,$book_id);

	$actual_lang = Turisbook_Booking_System::getLanguage();

	$book = $result['book'];
	include TURISBOOK_PUBLIC_PATH . '/partials/turisbook-booking-system-confirmation.php';
	$toreturn .= ob_get_clean();
	return $toreturn;
}

add_shortcode( 'turisbook_booking_confirmation', 'Turisbook_bs_booking_confirmation_Shortcode' );
