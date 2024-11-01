<?php
if ( ! function_exists('turisbook_rooms_post_type') ) {

	function turisbook_rooms_post_type() {

		$establishment_types = [];
		$establishment_types[1] = ['slug' => 'room', 'singular' => __( 'Room', 'turisbook-booking-system' ), 'plural' => __( 'Rooms', 'turisbook-booking-system' )];
		$establishment_types[2] = ['slug' => 'apartment', 'singular' => __( 'Apartment', 'turisbook-booking-system' ), 'plural' => __( 'Apartments', 'turisbook-booking-system' )];
		$establishment_types[3] = ['slug' => 'hostel', 'singular' => __( 'Hostel', 'turisbook-booking-system' ), 'plural' => __( 'Hostels', 'turisbook-booking-system' )];
		$establishment_types[4] = ['slug' => 'property', 'singular' => __( 'Property', 'turisbook-booking-system' ), 'plural' => __( 'Properties', 'turisbook-booking-system' )];



		$establishment_type_id= get_option('turisbook-hotel-type',1);
		$establishment_type= $establishment_types[$establishment_type_id];
		$type_name = $establishment_type['singular'];
		$type_name_plural = $establishment_type['plural'];

		$show_menu_apt = get_option('turisbook-show-menu-apt', 0);


		$labels = array(
			'name'                  => $type_name_plural,
			'singular_name'         => $type_name,
			'menu_name'             => 'Turisbook '. $type_name_plural,
			'name_admin_bar'        => 'Turisbook '. $type_name_plural,
			'archives'              => $type_name_plural,
			'attributes'            => __( 'Item Attributes', 'turisbook-booking-system' ),
			'parent_item_colon'     => __( 'Parent Item:', 'turisbook-booking-system' ),
			'all_items'             => __( 'All Items', 'turisbook-booking-system' ),
			'add_new_item'          => __( 'Add New Item', 'turisbook-booking-system' ),
			'add_new'               => __( 'Add New', 'turisbook-booking-system' ),
			'new_item'              => __( 'New Item', 'turisbook-booking-system' ),
			'edit_item'             => __( 'Edit Item', 'turisbook-booking-system' ),
			'update_item'           => __( 'Update Item', 'turisbook-booking-system' ),
			'view_item'             => __( 'View Item', 'turisbook-booking-system' ),
			'view_items'            => __( 'View Items', 'turisbook-booking-system' ),
			'search_items'          => __( 'Search Item', 'turisbook-booking-system' ),
			'not_found'             => __( 'Not found', 'turisbook-booking-system' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'turisbook-booking-system' ),
			'featured_image'        => __( 'Featured Image', 'turisbook-booking-system' ),
			'set_featured_image'    => __( 'Set featured image', 'turisbook-booking-system' ),
			'remove_featured_image' => __( 'Remove featured image', 'turisbook-booking-system' ),
			'use_featured_image'    => __( 'Use as featured image', 'turisbook-booking-system' ),
			'insert_into_item'      => __( 'Insert into item', 'turisbook-booking-system' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'turisbook-booking-system' ),
			'items_list'            => __( 'Items list', 'turisbook-booking-system' ),
			'items_list_navigation' => __( 'Items list navigation', 'turisbook-booking-system' ),
			'filter_items_list'     => __( 'Filter items list', 'turisbook-booking-system' ),
		);
		$rewrite = array(
			'slug'                  => strtolower($establishment_type['slug']),
			'with_front'            => true,
			'pages'                 => true,
			'feeds'                 => true,
		);
		$args = array(
			'label'                 => 'Turisbook '. $type_name,
			'description'           => __( 'Post Type Description', 'turisbook-booking-system' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail','excerpt','page-attributes' ),
			'taxonomies'            => array( 'category', 'post_tag' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => $show_menu_apt == 1,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-welcome-learn-more',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'rewrite'               => $rewrite,
			'capability_type'       => 'page',
		);
		register_post_type( 'turisbook_room', $args );

	}
	add_action( 'init', 'turisbook_rooms_post_type', 0 );


	add_filter('template_include', 'Tbs_turisbook_room_template');

	function Tbs_turisbook_room_template( $template ) {
		if ( is_post_type_archive('turisbook_room') ) {
			$theme_files = array('archive-turisbook_room.php');
			$exists_in_theme = locate_template($theme_files, false);
			if ( $exists_in_theme != '' ) {
				return $exists_in_theme;
			} else {
				return TURISBOOK_PUBLIC_PATH . '/partials/archive-turisbook_room.php';
			}
		}
		return $template;
	}
	
	add_action('parse_query', 'turisbook_rooms_sort_posts');

	function turisbook_rooms_sort_posts($q)
	{
		if(!$q->is_main_query() || is_admin())
			return;

		if(!is_post_type_archive('turisbook_room')) return;

		$q->set('orderby', 'menu_order');
		$q->set('order', 'ASC');

	}

	add_action( 'pre_get_posts', 'turisbook_rooms_nr_posts' );

	function turisbook_rooms_nr_posts( $query ) {
		if ( !is_admin() && $query->is_main_query() && is_post_type_archive('turisbook_room') ) {
			$query->set( 'posts_per_page', '6' );
		}
	}

}