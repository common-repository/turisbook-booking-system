<form autocomplete="off">
	<div class="tbs-rt-container ratecategory_container" unit_id="<?php echo esc_attr($unit_id); ?>" unit_name="<?php echo wp_filter_post_kses($post_title); ?>" availability="<?php echo esc_attr($room->availability); ?>" extrabed_adults="<?php echo esc_attr($room->extrabed_adults); ?>" extrabed_children="<?php echo esc_attr($room->extrabed_children); ?>" extrabed_max="<?php echo esc_attr($room->extrabed_max); ?>" lotation_base="<?php echo esc_attr($room->lotation_base); ?>">
		<?php

		foreach ($ratecategories as $rate) { 
			$price_without_discount = number_format($rate->price_without_discounts,2,',','.');
			$price_with_discount = number_format($rate->price_with_discounts,2,',','.');
			$price_pernight = number_format($rate->price_pernight,2,',','.');
			$night_string = $rate->total_days == 1 ? esc_html__( 'night', 'turisbook-booking-system' ) : esc_html__( 'nights', 'turisbook-booking-system' );

			$hasdiscount = $rate->hasdiscount ? " tbs-rt-price-with-discount " : ""; 

			?>
			<div class="row tbs-rt ratecategory" rate_id="<?php echo esc_attr($rate->id); ?>" base="<?php echo $room->lotation_base;?>" extrabed_max="<?php echo $room->extrabed_max;?>" >
				<div class="col-6 col-md-2 text-center">
					<small>
						<?php if($rate->hasdiscount){ ?>
							<span class="tbs-line-through tbs-line-through-red"><?php echo esc_attr($price_without_discount); ?> € </span><br/>
						<?php } ?>
						<span class="<?php echo esc_attr($hasdiscount);?>"><?php echo esc_attr($price_with_discount); ?> € </span><br/>
						<span><?php echo esc_attr($rate->total_days); ?> <?php echo esc_attr($night_string); ?></span>
					</small>
				</div>
				<div class="col-6 col-md-4 tb-rt-options">
					<small>
						<?php if($rate->advance_highlight_show) { ?>
							<span class="tbs-label tbs-label-success animated icon-animated-hand-pointer"><?php echo esc_attr($rate->advance_highlight_text); ?></span>
							<br/>

						<?php }
						if($rate->promotion){
							?>
							<span class="tbs-label tbs-label-success animated icon-animated-hand-pointer"><?php echo esc_attr($rate->promotion->name); ?></span>
							<br/>
							<?php
						}
						?>
						<span><i class="fa fa-coffee"></i> <?php echo esc_attr($rate->mealplan->translation->name); ?></span><br/>
						<span><i class="fa fa-bookmark"></i> <?php echo esc_attr($rate->policy->translation->name); ?> <i id="room-policy-<?php echo esc_attr($unit_id); ?>-<?php echo esc_attr($rate->id); ?>" class="far fa-question-circle pop-me-hover" description="<?php echo esc_attr($rate->policy->translation->description);?>" name="<?php echo esc_attr($rate->policy->translation->name);?>"></i></span>
					</small>
				</div>
				<?php if($room->mindayscheck){
					$qts = [
						[
							'select_start'=>1,
							'container_class' => 'adult_select',
							'title' => esc_attr__( 'Adults', 'turisbook-booking-system' ),
							'select_class' => 'adult-select',
							'lotation' => $room->lotation,
							'extrabed' => $room->extrabed_adults,
							'extrabed_price' => $rate->extrabedprices->price_adult->single,
							'info' => '',
							'info_title' => '',
							'info_id' => 'adults'.'-'.esc_attr($unit_id) . '-' . esc_attr($rate->id) 
						],
						[
							'select_start'=>0,
							'container_class' => 'children_select',
							'title' => esc_attr__( 'Children', 'turisbook-booking-system' ),
							'select_class' => 'children-select',
							'lotation' => $room->lotation_kids,
							'extrabed' => $room->extrabed_children,
							'extrabed_price' => $rate->extrabedprices->price_children->single,
							'info' => sprintf(esc_attr__( 'Up to %d years old', 'turisbook-booking-system' ), $children_max_age),
							'info_title' => esc_attr__( 'Information', 'turisbook-booking-system' ),
							'info_id' => 'children'.'-'.esc_attr($unit_id) . '-' . esc_attr($rate->id) 
						]
					];
					?>
					<div class="col-8 col-md-4">
						<?php
						$active = '';
						for($r=1; $r <= $room->availability; $r++ ){

							?>
							<div class="row mt-1 tbs_accomodation_row <?php echo $active; ?>" accomodation_count="<?php echo $r; ?>">
								<?php
								foreach($qts as $qt){
									$obj = (object)$qt;
									$j=0;
									?>
									<div class="ratecategory_element text-right <?php echo $obj->container_class; ?> col-6 col-md-6">
										<?php if($r==1){?><small><?php echo $obj->title; ?><?php if($obj->info!=''){ ?>  <small><i id="<?php echo $obj->info_id;?>" class="far fa-question-circle pop-me-hover" max_width="175" description="<?php echo $obj->info;?>" name="<?php echo $obj->info_title; ?> "></i></small><?php }?></small><br/><?php } ?>
										<select class="<?php echo $obj->select_class; ?>" lotation="<?php echo $obj->lotation;?>" extrabed="<?php echo $obj->extrabed;?>" extrabed_price="<?php echo $obj->extrabed_price;?>">
										</select>
									</div>
									<?php
								}
								?>				
							</div>
							<?php
							$active = 'accomodation_inactive';
						}
						?>				
					</div>
					<div class="ratecategory_element text-right ratecategory_select col-4 col-md-2">
						<small><?php echo esc_attr__( 'Accomodations', 'turisbook-booking-system' );?></small><br/>
						<select class="rate-select">
							<?php
							$rselected = 'selected';
							for($i=0; $i <= $room->availability; $i++){
								$price = number_format($rate->price_with_discounts * $i, 2,',','');
								?>
								<option value="<?php echo esc_attr($i); ?>" price="<?php echo esc_attr($price);?>" <?php echo esc_attr($rselected); ?>>
									<?php echo esc_attr($i); ?>
								</option>
								<?php
								$rselected='';
							}
							?>
						</select>
					</div>
					<?php
				}else{
					?>
					<div class="ratecategory_element text-right ratecategory_select col-12 col-md-6 text-center">
						<span class="text-red" style="font-size:14px; font-weight:700;"><?php echo esc_attr__( 'Minimum nights', 'turisbook-booking-system' );?>: <?php echo esc_attr($room->mindays);?></span>
					</div>
					<?php
				}
				?>
			</div>
			<?php
		}
		?>

	</div>
</form>