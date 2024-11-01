<?php
function get_turisbook_hotel_logo( $atts ) {

	global $post;


	$type = get_post_type($post);


	$toreturn = "";
	ob_start();
	$id = 0;
	$tbs = new Turisbook_Booking_System();
	$c_prebook=null;
	
	if ($type == 'turisbook_estab' ) {
		$id = $post->ID;
	} else if ($type == 'turisbook_room'){
		$estab_turisbook_id = get_post_meta($post->ID,'turisbook_room_establishment',true);
		$estab = $tbs->getPostByMeta(['meta_key' => 'turisbook_establishment_id', 'meta_value' => $estab_turisbook_id, "post_type"=>"turisbook_estab"]); 
		$id = $estab->ID;
	}else{
		$c_prebook = $tbs->getPrebookCookie();
		$estab = $tbs->getPostByMeta(['meta_key' => 'turisbook_establishment_id', 'meta_value' => $c_prebook->hotel, "post_type"=>"turisbook_estab"]); 
		$id = $estab->ID;
	}

	$thumb = get_the_post_thumbnail_url($id);
	// include TURISBOOK_PUBLIC_PATH . '/partials/turisbook-book-modal.php';
	$toreturn .= ob_get_clean();
	// echo '<pre>';
	// var_dump($c_prebook);
	// echo '</pre>';
	echo '<img src="'.$thumb.'"/>';
	return $toreturn;
}

add_shortcode( 'turisbook_hotel_logo', 'get_turisbook_hotel_logo' );
