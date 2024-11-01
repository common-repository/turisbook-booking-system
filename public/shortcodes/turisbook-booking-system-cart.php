<?php
function Turisbook_bs_cart_Shortcode( $atts ) {
	$a = shortcode_atts( [
		'offset' => '0',
		'backgroundcolor' => '#fff',
		'availabilities' => 'availabilities'
	], $atts );
	ob_start();

	$tbs = new Turisbook_Booking_System();

	$currentLanguage = pll_current_language();

	$unit_id = isset($_POST['unit_id']) ? (int)$_POST['unit_id'] : 0;
	$total_rooms = isset($_POST['rooms']) ? (int)$_POST['rooms'] : 1;
	$total_adults = isset($_POST['adults']) ? (int)$_POST['adults'] : 1;
	$total_children = isset($_POST['children']) ? (int)$_POST['children'] : 0;
	$page_prebook_id = get_option('turisbook-page-prebook',0);

	$get_checkin = isset($_GET['checkin']) ? $_GET['checkin'] : false;
	$get_checkout = isset($_GET['checkout']) ? $_GET['checkout'] : false;

	$checkin_checkout = $tbs->validateCheckinCheckout($get_checkin, $get_checkout);

	$checkin = $checkin_checkout['checkin'];
	$checkout = $checkin_checkout['checkout'];
	$hasdates = $checkin_checkout['hasdates'];
	
	$translatedPageID = pll_get_post($page_prebook_id, $currentLanguage);
	$page_prebook = get_permalink($translatedPageID);

	$start = new DateTime($checkin);
	$end = new DateTime($checkout);
	$interval = $start->diff($end);
	$days = $interval->days;

	$toreturn = "";
	?>
	<div class="bootstrap-iso">
		<div class="row">
			<div class="col">
				<?php
				include TURISBOOK_PUBLIC_PATH . '/partials/turisbook-booking-system-cart.php';
				?>
			</div>
		</div>
	</div>
	<?php
	$toreturn .= ob_get_clean();


	return $toreturn;
}

add_shortcode( 'turisbook_resume_block', 'Turisbook_bs_cart_Shortcode' );
