<?php

add_action( 'load-post.php', 'turisbook_room_gallery_meta_boxes_setup' );
add_action( 'load-post-new.php', 'turisbook_room_gallery_meta_boxes_setup' );


/* Meta box setup function. */
function turisbook_room_gallery_meta_boxes_setup() {

	/* Add meta boxes on the 'add_meta_boxes' hook. */
	add_action( 'add_meta_boxes', 'gallery_add_post_meta_boxes' );

	add_action( 'save_post', 'gallery_save_post_class_meta', 10, 2 );
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
function gallery_add_post_meta_boxes() {

	add_meta_box(
    'post-image-gallery',      // Unique ID
    esc_html__( 'Image Gallery', 'turisbook-booking-system' ),    // Title
    'turisbook_room_gallery_class_meta_box',   // Callback function
    'turisbook_room',         // Admin page (or post type)
    'normal',         // Context
    'low'         // Priority
);
}


/* Save the meta box’s post metadata. */
function gallery_save_post_class_meta( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['turisbook_room_gallery_class_nonce'] ) || !wp_verify_nonce( $_POST['turisbook_room_gallery_class_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	/* Get the posted data and sanitize it for use as an HTML class. */
	$new_meta_value = ( isset( $_POST['turisbook_room_gallery'] ) ?  $_POST['turisbook_room_gallery'] : '' );

	/* Get the meta key. */
	$meta_key = 'turisbook_room_gallery';

	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && '' == $meta_value ) add_post_meta( $post_id, $meta_key, $new_meta_value, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value && $new_meta_value != $meta_value ) update_post_meta( $post_id, $meta_key, $new_meta_value );

	/* If there is no new meta value but an old value exists, delete it. */
	//elseif ( '' == $new_meta_value && $meta_value ) delete_post_meta( $post_id, $meta_key, $meta_value );
}

function get_turisbook_room_gallery_ids(){
	/* Get the current post ID. */
	$post_id = get_the_ID();


	$ids = [];
	/* If we have a post ID, proceed. */
	if ( !empty( $post_id ) ) {
		/* Get the custom post class. */
		$post_class = get_post_meta( $post_id, 'turisbook_room_gallery', true );

		/* If a post class was input, sanitize it and add it to the post class array. */
		if ( !empty( $post_class ) )
			$ids = explode(',',$post_class);
	}
	return $ids;
}

/* Display the post meta box. */
function turisbook_room_gallery_class_meta_box( $post ) { ?>

	<?php wp_nonce_field( basename( __FILE__ ), 'turisbook_room_gallery_class_nonce' ); ?>

	<p>
		<div id="post_image_gallery_container">
			<ul class="ui-sortable post_image_gallery">
				<?php
				$ids = [];
				foreach(get_turisbook_room_gallery_ids() as $id){
					$src=wp_get_attachment_image_src ($id);
					$ids[] = $id;
					echo '<li class="image" data-attachment_id="' . $id . '"><img src="' . $src[0] . '"/><a class="post-remove"><span class="dashicons dashicons-dismiss"></span></a></li>';
					
				}
				?>
			</ul>
		</div>
		<a class="add_post_image_gallery" href="#">Adicionar imagens à galeria</a>
		<input type="hidden" id="turisbook_room_gallery" name="turisbook_room_gallery" value="<?php echo implode(',',$ids); ?>" />
	</p>

	<style type="text/css">
	#post_image_gallery_container{position:relative; width:100%;}
	.post_image_gallery{
		width: 100%;
		position: relative;
		min-height: 100%;
		overflow: hidden;
	}
	.post_image_gallery li.image,
	.metabox-sortable-placeholder
	{
		width: 75px;
		height: 75px;
		float: left;
		cursor: move;
		border: 1px solid#d5d5d5;
		margin: 9px 9px 0 0;
		background-color:#f7f7f7;
		border-radius: 2px;
		position: relative;
		box-sizing: border-box;
	}
	.post_image_gallery li.image img{
		width: 100%;
		height: auto;
		display: block;
	}

	.post-remove{
		position: absolute;
		display:none;
		top: -7px;
		right: -7px;
		text-align: center;
		cursor:pointer;
	}


	.post-remove span{
		color:#fff;
		background: #999;
		border-radius:50%;
	}

	.post_image_gallery li.image:hover .post-remove{
		display:inline;
	}
	.post_image_gallery li.image .post-remove:hover span{
		color:#fff;
		background: #C41E3A;
	}

</style>
<script type="text/javascript">
	jQuery(function($){
		var product_gallery_frame;
		var $image_gallery_ids = $( '#turisbook_room_gallery' );
		var $post_images    = $( 'ul.post_image_gallery' );


		$( document ).on( 'click', '.add_post_image_gallery', function( event ) {
			var $el = $( this );

			event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( product_gallery_frame ) {
			product_gallery_frame.open();
			return;
		}

		// Create the media frame.
		product_gallery_frame = wp.media.frames.product_gallery = wp.media({
			// Set the title of the modal.
			title: $el.data( 'choose' ),
			button: {
				text: $el.data( 'update' )
			},
			states: [
			new wp.media.controller.Library({
				title: $el.data( 'choose' ),
				filterable: 'all',
				multiple: true
			})
			]
		});

		// When an image is selected, run a callback.
		product_gallery_frame.on( 'select', function() {
			var selection = product_gallery_frame.state().get( 'selection' );
			var attachment_ids = $image_gallery_ids.val();

			selection.map( function( attachment ) {
				attachment = attachment.toJSON();

				if ( attachment.id ) {
					attachment_ids   = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;
					var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

					$post_images.append(
						'<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image + '"/><a class="post-remove"><span class="dashicons dashicons-dismiss"></span></a></li></li>'
						);
				}
			});

			$image_gallery_ids.val( attachment_ids );
		});

		// Finally, open the modal.
		product_gallery_frame.open();
	});

	// Image ordering.
	$post_images.sortable({
		items: 'li.image',
		cursor: 'move',
		scrollSensitivity: 40,
		forcePlaceholderSize: true,
		forceHelperSize: false,
		helper: 'clone',
		opacity: 0.65,
		placeholder: 'metabox-sortable-placeholder',
		start: function( event, ui ) {
			ui.item.css( 'background-color', '#f6f6f6' );
		},
		stop: function( event, ui ) {
			ui.item.removeAttr( 'style' );
		},
		update: function() {
			var attachment_ids = '';

			var sep = '';
			$( '#post_image_gallery_container' ).find( 'ul li.image' ).css( 'cursor', 'move' ).each( function() {
				var attachment_id = $( this ).attr( 'data-attachment_id' );
				attachment_ids = attachment_ids + sep +attachment_id;
				sep = ',';
			});

			$image_gallery_ids.val( attachment_ids );

			console.log($image_gallery_ids.val());
		}
	});

	// Remove images.
	$( '#post_image_gallery_container' ).on( 'click', 'a.post-remove', function() {
		$( this ).closest( 'li.image' ).remove();

		var attachment_ids = '';
		var sep = '';
		$( '#post_image_gallery_container' ).find( 'ul li.image' ).css( 'cursor', 'move' ).each( function() {
			var attachment_id = $( this ).attr( 'data-attachment_id' );
			attachment_ids = attachment_ids + sep +attachment_id;
			sep = ',';
		});

		$image_gallery_ids.val( attachment_ids );

		// Remove any lingering tooltips.
		$( '#tiptip_holder' ).removeAttr( 'style' );
		$( '#tiptip_arrow' ).removeAttr( 'style' );

		return false;
	});

});
</script>
<?php }

use ElementorPro\Modules\DynamicTags\Tags\Base\Data_Tag;
use ElementorPro\Modules\DynamicTags\Module;

add_action( 'elementor/dynamic_tags/register_tags', function( $dynamic_tags ) {
	class Turisbook_Room_Gallery_Tag extends Data_Tag {


		public function get_name() {
			return 'Turisbook_Room_Gallery_Tag';
		}

		public function get_title() {
			return __( 'Post Gallery', 'turisbook-booking-system' );
		}


		public function get_group() {
			return [ Module::POST_GROUP ];
		}

		public function get_categories() {
			return [ Module::GALLERY_CATEGORY ];
		}

		public function get_value( array $options = []) {
			$images = get_turisbook_room_gallery_ids();

			$value = [];

			foreach ( $images as $image ) {
				$value[] = [
					'id' => $image,
				];
			}

			return $value;
		}

	}
	$dynamic_tags->register_tag( 'Turisbook_Room_Gallery_Tag' );
} );

