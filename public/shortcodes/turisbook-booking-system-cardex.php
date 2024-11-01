<?php
function Turisbook_bs_cardex_Shortcode( $atts ) {
	$tbs = new Turisbook_Booking_System();

	$uid = isset($_GET['uid']) ? trim($_GET['uid']) : '';

	$thankyou = isset($_GET['thankyou']);

	$toreturn = "";
	ob_start();

	if($thankyou){
		include TURISBOOK_PUBLIC_PATH . '/partials/turisbook-booking-system-cardex-thankyou.php';
	}else{

		$result = $tbs->getCardex($uid);

		if($result['httpcode']==200 && $result['cardex']->editable ==1){

			$cardex = $result['cardex'];
			$countries = Turisbook_Booking_System::getCountries();

			include TURISBOOK_PUBLIC_PATH . '/partials/turisbook-booking-system-cardex-info.php';

		}else{
			include TURISBOOK_PUBLIC_PATH . '/partials/turisbook-booking-system-cardex-invalid.php';
		}

	}
	$toreturn .= ob_get_clean();


	return $toreturn;
}

add_shortcode( 'turisbook_cardex', 'Turisbook_bs_cardex_Shortcode' );
