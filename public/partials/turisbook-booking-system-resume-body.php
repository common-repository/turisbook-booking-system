<?php
$i=0;
foreach($prebook->ulines as $line){
	$i++;
	$post = $tbs->getPostByMeta(['meta_key' => 'turisbook_room_id', 'meta_value' => $line->room_id,'post_type'=>'turisbook_room']);
	$unit_id = $post->ID;
	if($tbs->PolylangIsActive()){
		$unit_id = pll_get_post($post->ID, $actual_lang);
		$post = get_post($unit_id);
	}

	foreach($line->options as $option){
		?>
		<div class="row prebook_line" max_extrabed="<?php echo $line->extrabed_max;?>" line_id="<?php echo $line->line_id;?>" room_id="<?php echo $line->room_id;?>" room_count="<?php echo $i;?>" >
			<div class="col-12" style="padding:10px 20px 10px;border-radius:5px; border:1px solid #a7a7a7;">
				<h4><?php echo esc_attr($post->post_title); ?></h4>
				<span><i class="fas fa-coffee"></i> <?php echo esc_attr($line->mp->name); ?> | <i class="fas fa-bookmark"></i> <?php echo esc_attr($line->poli->translation->name); ?> <i id="room-policy-<?php echo esc_attr($unit_id); ?>-<?php echo esc_attr($line->rate_category_id); ?>-<?php echo esc_attr($i); ?>" class="far fa-question-circle room-policy" description="<?php echo esc_attr($line->poli->translation->description);?>" name="<?php echo esc_attr($line->poli->translation->name);?>"></i></span>
				<br/>
				<span><?php echo __('Adults', 'turisbook-booking-system' ) .': ' . esc_attr($option->adults); ?> </span> <?php if($option->children > 0) { ?> | <span><?php echo __('Children', 'turisbook-booking-system' ) .': ' . esc_attr($option->children); ?> </span> <?php }?> <?php if($option->extrabed_adult > 0) { ?> | <span><?php echo __('Extrabed Adult', 'turisbook-booking-system' ) .': '. esc_attr($option->extrabed_adult); ?> </span> <?php }?> <?php if($option->extrabed_children > 0) { ?> | <span><?php echo __('Extrabed Children', 'turisbook-booking-system' ) .': ' . esc_attr($option->extrabed_children); ?> </span> <?php }?>
				<?php
				if($prebook->choose_bed_type == 1){
					$radio_class = 'room_choose_beds_'.esc_attr($line->rate_category_id).'_'. $line->line_id.'_'.$i;
					?>
					<br/>
					<br/>
					<b><?php echo __('Choose your bed ', 'turisbook-booking-system' )?></b> (<?php echo __('if available', 'turisbook-booking-system' )?>):
					<ul class="choose_beds_list" style="list-style-type: none;">
						<li><input type="radio" class="line_choose_beds" name="<?php echo $radio_class;?>" value="1" checked/> <img height="25" width="25" style="width:25px!important;" src="<?php echo TURISBOOK_PUBLIC_URL . '/img/beds-twin.jpg' ?>"/> 2 <?php echo __('individual beds', 'turisbook-booking-system' ) ?></li>
						<li><input type="radio" class="line_choose_beds" name="<?php echo $radio_class;?>" value="2"/> <img height="25" width="25" style="width:25px;" src="<?php echo TURISBOOK_PUBLIC_URL . '/img/beds-king.jpg' ?>"/> 1 <?php echo __('extra large double bed', 'turisbook-booking-system' ) ?></li>
					</ul>
				<?php } ?>
			</div>
		</div>
		<?php
	}
	?>
	<?php
}
?>
<br/>
<br/>
<div class="tbs-price-resume">
	<?php

	include TURISBOOK_PUBLIC_PATH . '/partials/turisbook-booking-system-resume-body-price.php';
	?>
</div>