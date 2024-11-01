<?php

add_action( 'load-post.php', 'turisbook_room_metaboxes_setup' );
add_action( 'load-post-new.php', 'turisbook_room_metaboxes_setup' );


/* Meta box setup function. */
function turisbook_room_metaboxes_setup() {

	/* Add meta boxes on the 'add_meta_boxes' hook. */
	add_action( 'add_meta_boxes', 'add_post_turisbook_room_metaboxes' );

	add_action( 'save_post', 'save_post_turisbook_room_metaboxes', 10, 2 );
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
function add_post_turisbook_room_metaboxes() {

	add_meta_box(
    'turisbook_room_metaboxes',      // Unique ID
    esc_html__( 'Turisbook values', 'turisbook-booking-system' ),
    'turisbook_room_metaboxes',   // Callback function
    'turisbook_room',         // Admin page (or post type)
    'normal',         // Context
    'high'         // Priority
);
}


function getTurisbookRoomMetakeys(){
	return [
		'turisbook_room_rnal' => esc_html__( 'Rnal', 'turisbook-booking-system' ),
		'turisbook_room_adult_capacity' => esc_html__( 'Adults', 'turisbook-booking-system' ),
		'turisbook_room_children_capacity' => esc_html__( 'Capacity', 'turisbook-booking-system' ),
		'turisbook_room_extrabed_children' => esc_html__( 'Extrabed Children', 'turisbook-booking-system' ),
		'turisbook_room_extrabed_adults' => esc_html__( 'Extrabed Adults', 'turisbook-booking-system' ),
		'turisbook_room_extrabed_max' => esc_html__( 'Max Extrabeds', 'turisbook-booking-system' ),
		'turisbook_room_lotation_base' => esc_html__( 'Lotation Base', 'turisbook-booking-system' ),
		'turisbook_room_rooms' => esc_html__( 'Rooms', 'turisbook-booking-system' ),
		'turisbook_room_bathrooms' => esc_html__( 'Bathrooms', 'turisbook-booking-system' ),
		'turisbook_room_sleeps' => esc_html__( 'Sleeps', 'turisbook-booking-system' ),
		'turisbook_room_latitude' => esc_html__( 'Latitude', 'turisbook-booking-system' ),
		'turisbook_room_longitude' => esc_html__( 'Longitude', 'turisbook-booking-system' ),
		'turisbook_room_location_name' => esc_html__( 'Location', 'turisbook-booking-system' ),
		'turisbook_room_location_id' => esc_html__( 'Location ID', 'turisbook-booking-system' ),
	];
}

/* Save the meta box’s post metadata. */
function save_post_turisbook_room_metaboxes( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['turisbook_room_metaboxes_nonce'] ) || !wp_verify_nonce( $_POST['turisbook_room_metaboxes_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	$post_type = get_post_type_object( $post->post_type );

	foreach(getTurisbookRoomMetakeys() as $meta_key => $value){

		/* Get the post type object. */

		/* Check if the current user has permission to edit the post. */
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
			return $post_id;

		/* Get the posted data and sanitize it for use as an HTML class. */
		$new_meta_value = ( isset( $_POST[$meta_key] ) ?  $_POST[$meta_key] : '' );

		/* Get the meta value of the custom field key. */
		$meta_value = get_post_meta( $post_id, $meta_key, true );

		/* If a new meta value was added and there was no previous value, add it. */
		if ( $new_meta_value && '' == $meta_value ) add_post_meta( $post_id, $meta_key, $new_meta_value, true );

		/* If the new meta value does not match the old value, update it. */
		elseif ( $new_meta_value && $new_meta_value != $meta_value ) update_post_meta( $post_id, $meta_key, $new_meta_value );

		/* If there is no new meta value but an old value exists, delete it. */
		elseif ( '' == $new_meta_value && $meta_value ) delete_post_meta( $post_id, $meta_key, $meta_value );
	}
}

function get_turisbook_room_metabox($metabox,$id=-1){
	/* Get the current post ID. */
	$post_id = $id==-1 ? get_the_ID() : $id;

	$metabox_value = '';
	/* If we have a post ID, proceed. */

	if ( !empty( $post_id ) ) {
		/* Get the custom post class. */
		$post_class = get_post_meta( $post_id, $metabox, true );

		/* If a post class was input, sanitize it and add it to the post class array. */
		if ( !empty( $post_class ) ) $metabox_value = $post_class;
	}
	return $metabox_value;
}


/* Display the post meta box. */
function turisbook_room_metaboxes( $post ) {
	?>

	<?php wp_nonce_field( basename( __FILE__ ), 'turisbook_room_metaboxes_nonce' ); ?>
	<table class="form-table">
		<tbody>
			<?php
			foreach(getTurisbookRoomMetakeys() as $key => $value){
				?>
				<tr>
					<th><?php echo esc_html($value) ?></th>
					<td><input type="text" name="<?php echo esc_attr($key); ?>" value="<?php echo get_turisbook_room_metabox($key); ?>" /></td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
	<?php
}
