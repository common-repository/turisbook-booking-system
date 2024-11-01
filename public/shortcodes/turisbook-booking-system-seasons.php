<?php
function Turisbook_bs_seasons( $atts ) {

	$toreturn = "";

	$id = get_the_ID();

	if($id){
		$seasons = json_decode(get_post_meta($id,"turisbook_seasons",'[]'));

		$years = [];

		if(isset($seasons)){	
			foreach($seasons as $season){
				if(!isset($year[$season->year])) $year[$season->year] = [];
				$years[$season->year][] = $season; 
			}
		}

		ob_start();
		include TURISBOOK_PATH . 'public/partials/turisbook-booking-system-seasons.php';
		$toreturn .= ob_get_clean();
	}	


	return $toreturn;
}
add_shortcode( 'turisbook_seasons', 'Turisbook_bs_seasons' );
