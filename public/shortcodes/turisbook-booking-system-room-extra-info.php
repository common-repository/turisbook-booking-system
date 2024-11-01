<?php
function Turisbook_bs_unit_room_extra_info_Shortcode( $atts ) {

	$a = shortcode_atts( array(

	), $atts );
	$id = get_the_ID();

	$bedrooms = get_turisbook_room_metabox('turisbook_room_rooms',$id);
	$adults = (int)get_turisbook_room_metabox('turisbook_room_adult_capacity',$id);
	$children = (int)get_turisbook_room_metabox('turisbook_room_children_capacity',$id);
	$extrabeds = (int)get_turisbook_room_metabox('turisbook_room_extrabed_max',$id);
	$toreturn = "";
	$sleeps = $adults + $children + $extrabeds;
	ob_start();
	?>
	Sleeps: <?php echo $sleeps; ?> | Adults: <?php echo $adults; ?> | Children: <?php echo $children; ?> | Extrabeds: <?php echo $extrabeds; ?>
	<?php	
	$toreturn .= ob_get_clean();


	return $toreturn;
}

add_shortcode( 'turisbook_room_extra_info', 'Turisbook_bs_unit_room_extra_info_Shortcode' );
