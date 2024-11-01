<div class="bootstrap-iso">
	<div class="container-fluid">
		<div class="row">
			<?php 
			$count = 0;
			$total = count($units);
			$line = 1;
			$left_in_row = $per_row;
			foreach($units as $unit){
				$left = $total - $count;

				if($left < $left_in_row && $line > 1 ){
					$size_per_row = 6;
				}

				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $unit->ID ), 'single-post-thumbnail' );
				$post_link = get_permalink($unit->ID);
				$bedrooms = get_turisbook_room_metabox('turisbook_room_rooms',$unit->ID);
				$adults = (int)get_turisbook_room_metabox('turisbook_room_adult_capacity',$unit->ID);
				$children = (int)get_turisbook_room_metabox('turisbook_room_children_capacity',$unit->ID);
				$extrabeds = (int)get_turisbook_room_metabox('turisbook_room_extrabed_max',$unit->ID);
				$bathrooms = (int)get_turisbook_room_metabox('turisbook_room_bathrooms',$unit->ID);
				$turisbook_room_location_name = get_turisbook_room_metabox('turisbook_room_location_name',$unit->ID);
				$bathrooms_text = $bathrooms == 1 ? __('Bathroom', 'turisbook-booking-system') : __('Bathrooms', 'turisbook-booking-system');
				$bedrooms_text = $bedrooms == 1 ? __('Bedroom', 'turisbook-booking-system') : __('Bedrooms', 'turisbook-booking-system');
				
				$sleeps = (int)get_turisbook_room_metabox('turisbook_room_sleeps',$unit->ID);

				$info = [];

				$info['sleeps'] = __('Sleeps', 'turisbook-booking-system') .': '.$sleeps;
				if(in_array($establishment_type, [2,4])){
					$info['bedrooms'] = $bedrooms . ' ' . $bedrooms_text;
					$info['bathrooms'] = $bathrooms . ' ' . $bathrooms_text;
				}
				?>
				<div class="col-12 col-md-<?php echo $size_per_row;?> tbs-grid-unit-container">
					<a href="<?php echo $post_link; ?>">
						<div class="tbs-grid-unit" style="background-image:url(<?php echo $image[0];?>);">
							<div class="tbs-grid-unit-cover"></div>
							<div class="tbs-grid-unit-info">
								<div class="tbs-grid-unit-title"><?php echo $unit->post_title ?></div>
								<div style="color:#fff;font-size: 11px;"><?php echo $turisbook_room_location_name ?></div>
							</div>
							<div class="tbs-grid-info-container">
								<div class="tbs-grid-info-number"><?php echo implode(' | ',$info);?></div>
							</div>
							<div class="tbs-grid-unit-more-info-container"><button><?php echo __('More Info', 'turisbook-booking-system') ;?></button></div>
						</div>
					</a>
				</div>
				<?php
				$count++;
				$left_in_row--;
				if($count % $per_row === 0){
					$line++;
					$left_in_row = $per_row;
					?>
				</div>
				<div class="row">
					<?php
				}
			}
			?>
		</div>
	</div>
</div>