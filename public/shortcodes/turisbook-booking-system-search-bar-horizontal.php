<?php
function Turisbook_bs_search_bar_horizontal_Shortcode( $atts ) {

	$a = shortcode_atts( array(
		'text1' => __('Book now', 'turisbook-booking-system'),
		'text1_color' => "#333",
		'text2' => __('at the best price', 'turisbook-booking-system'),
		'text2_color' => "#555",
	), $atts );

	$tbs = new Turisbook_Booking_System();

	if(defined('TURISBOOK_ADMIN_VERSION')){
		global $post;
		$type = get_post_type($post);

		if ($type == 'turisbook_estab' ) {
			$id = $post->ID;

			$color = get_post_meta($id, 'turisbook_color_text',true);

			$a['text1_color'] = $color;
			$a['text2_color'] = $color;

		} else if ($type == 'turisbook_room'){
			$estab_turisbook_id = get_post_meta($post->ID,'turisbook_room_establishment',true);
			$estab = $tbs->getPostByMeta(['meta_key' => 'turisbook_establishment_id', 'meta_value' => $estab_turisbook_id, "post_type"=>"turisbook_estab"]); 
			$id = $estab->ID;
			$color = get_post_meta($id, 'turisbook_color_text',true);
			$a['text1_color'] = $color;
			$a['text2_color'] = $color;
		}


	}

	$total_rooms = isset($_POST['rooms']) && is_numeric($_POST['rooms']) ? (int)$_POST['rooms'] : 1;
	$total_adults = isset($_POST['adults']) && is_numeric($_POST['adults']) ? (int)$_POST['adults'] : 1;
	$total_children = isset($_POST['children']) && is_numeric($_POST['children']) ? (int)$_POST['children'] : 0;


	$get_checkin = isset($_GET['checkin']) ? sanitize_text_field($_GET['checkin']) : false;
	$get_checkout = isset($_GET['checkout']) ? sanitize_text_field($_GET['checkout']) : false;


	$checkin_checkout = $tbs->validateCheckinCheckout($get_checkin,$get_checkout,'d-m-Y');
	$hasdates = $checkin_checkout['hasdates'];
	$checkin = $checkin_checkout['checkin']; 
	$checkout = $checkin_checkout['checkout'];

	$c_prebook =  $tbs->getPrebookCookie();

	if(defined('TURISBOOK_ADMIN_VERSION')){
		global $post;
		$type = get_post_type($post);

		if ($type == 'turisbook_estab' ) {
			$post_type_link = get_permalink($post->ID);
		} else if ($type == 'turisbook_room'){
			$estab_turisbook_id = get_post_meta($post->ID,'turisbook_room_establishment',true);
			$estab = $tbs->getPostByMeta(['meta_key' => 'turisbook_establishment_id', 'meta_value' => $estab_turisbook_id, "post_type"=>"turisbook_estab"]); 
			$post_type_link = get_permalink($estab->ID);
		}


	}else{
		$availability_page_id = get_option('turisbook-page-availability-search',0);
		$post_type_link = get_permalink($availability_page_id);
	}

	$toreturn = "";
	ob_start();
	include TURISBOOK_PUBLIC_PATH . '/partials/turisbook-booking-system-search-bar-horizontal.php';
	$toreturn .= ob_get_clean();

	return $toreturn;
}

add_shortcode( 'turisbook_search_bar_horizontal', 'Turisbook_bs_search_bar_horizontal_Shortcode' );
