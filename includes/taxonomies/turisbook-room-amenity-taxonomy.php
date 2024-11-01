<?php
add_action( 'init', 'create_turisbook_room_amenities_taxonomy', 0 );

function create_turisbook_room_amenities_taxonomy() {



	$labels = array(
		'name' => _x( 'Amenities', 'taxonomy general name', 'turisbook-booking-system' ),
		'singular_name' => _x( 'Amenity', 'taxonomy singular name', 'turisbook-booking-system' ),
		'search_items' =>  __( 'Search Amenities', 'turisbook-booking-system' ),
		'popular_items' => __( 'Popular Amenities', 'turisbook-booking-system' ),
		'all_items' => __( 'All Amenities', 'turisbook-booking-system' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit Amenities', 'turisbook-booking-system' ), 
		'update_item' => __( 'Update Amenities', 'turisbook-booking-system' ),
		'add_new_item' => __( 'Add New Amenity', 'turisbook-booking-system' ),
		'new_item_name' => __( 'New Topic Name', 'turisbook-booking-system' ),
		'separate_items_with_commas' => __( 'Separate Amenities with commas', 'turisbook-booking-system' ),
		'add_or_remove_items' => __( 'Add or remove Amenities', 'turisbook-booking-system' ),
		'choose_from_most_used' => __( 'Choose from the most used Amenities', 'turisbook-booking-system' ),
		'menu_name' => __( 'Amenities', 'turisbook-booking-system' ),
	); 

	register_taxonomy('turisbook_amenity','turisbook_room',array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => false,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
		'rewrite' => array( 'slug' => 'turisbook-amenity' ),
	));
}


add_filter('template_include', 'Tbs_turisbook_amenities_template');

function Tbs_turisbook_amenities_template( $template ) {
	if( is_tax('turisbook_amenity')) $template = TURISBOOK_PUBLIC_PATH.'/partials/taxonomy-turisbook_amenity.php';

	return $template;

}