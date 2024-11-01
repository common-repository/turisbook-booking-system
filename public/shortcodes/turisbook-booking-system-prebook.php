<?php
function Turisbook_bs_prebook_Shortcode( $atts ) {
	$tbs = new Turisbook_Booking_System();

	$c_prebook =  $tbs->getPrebookCookie();
	ob_start();
	if($c_prebook->invalid) return "";

	$currentLanguage = pll_current_language();

	$room_rates = $c_prebook->room_rates;
	$room_rates_encoded = json_encode($c_prebook->room_rates);
	$checkin = $c_prebook->checkin;
	$checkout = $c_prebook->checkout;
	$total_rooms = isset($c_prebook->rooms) ? int($c_prebook->rooms) :1;
	$total_adults = isset($c_prebook->adults) ? int($c_prebook->adults) : 1;
	$total_children = isset($c_prebook->children) ? int($c_prebook->children) : 0;
	$cupon = isset($c_prebook->cupon) ? $c_prebook->cupon : '';

	$hotel = $c_prebook->hotel;

	$result = $tbs->getPrebook($hotel,$room_rates, $checkin, $checkout, $total_rooms, $total_adults, $cupon);
	$prebook = $result['prebook'];

	$price = number_format($prebook->price_with_discounts_and_taxes,2,',','.');

	$actual_lang = Turisbook_Booking_System::getLanguage();

	$page_confirmation_id = get_option('turisbook-page-confirmation',0);
	
	$translatedPageID = pll_get_post($page_confirmation_id, $currentLanguage);
	$page_confirmation = get_permalink($translatedPageID);


	$h = $tbs->getEstablishmentById($hotel);
	$payments = json_decode(get_post_meta($h->ID,'turisbook_payments',true));

	$countries = Turisbook_Booking_System::getCountries();

	$return_link = '';



	if(defined('TURISBOOK_ADMIN_VERSION')){
		$return_link = get_permalink($h->ID);
	}else{
		$return_link = get_post_type_archive_link( 'turisbook_room' );
	}

	$toreturn = "";
	include TURISBOOK_PUBLIC_PATH . '/partials/turisbook-booking-system-prebook.php';
	$toreturn .= ob_get_clean();


	return $toreturn;
}

add_shortcode( 'turisbook_booking_prebook', 'Turisbook_bs_prebook_Shortcode' );
