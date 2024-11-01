<?php
function Turisbook_bs_unit_grid_Shortcode( $atts ) {

	$a = shortcode_atts( array(
		'per_row' => 3,
		'total_units' => -1
	), $atts );


	$per_row =  is_numeric($a['per_row']) && (int)$a['per_row'] >= 2 && (int)$a['per_row'] <= 4 ? (int)$a['per_row'] : 3;

	$size_per_row = 12 / $per_row;

	$tbs = new Turisbook_Booking_System();

	$args = array(
		'post_type'  => 'turisbook_room',
		'numberposts'      => $a['total_units'],
		'order' => 'ASC',
		'orderby' => 'menu_order'

	);

	$establishment_type = 	get_option('turisbook-hotel-type');

	if($tbs->PolylangIsActive()){ $args['lang'] = pll_current_language('slug'); }


	$result = get_posts( $args );
	$units = [];
	if($tbs->PolylangIsActive()){
		foreach($result as $unit){
			if(Turisbook_Booking_System::getLanguage() == Turisbook_Booking_System::calcLanguage(pll_get_post_language($unit->ID))){
				$units[] = $unit; 
			}
		}
	}
	else{
		$units = $result;
	}


	$toreturn = "";
	ob_start();

	include TURISBOOK_PUBLIC_PATH . '/partials/turisbook-booking-system-grid.php';
	$toreturn .= ob_get_clean();


	return $toreturn;
}

add_shortcode( 'turisbook_unit_grid', 'Turisbook_bs_unit_grid_Shortcode' );
