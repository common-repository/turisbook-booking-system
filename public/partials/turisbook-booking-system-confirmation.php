<div class="toprint">
	<div class="bootstrap-iso">
		<div class="container-fluid">
			<div class="tbs-booking-resume-head">
				<div class="row">
					<div class="col-12 text-center">
						<h2><?php echo __('Thank you for your preference!', 'turisbook-booking-system' );?></h2>
						<h3><?php echo sprintf(__('Your reservation has been registered <br/> with the code <br/> <strong>%s</strong>', 'turisbook-booking-system' ),$book->ref);?></h3>
						<br/>
					</div>
				</div>
				<div class="row">
					<div class="col-6 col-md-6">
						<span class="tbs-date-type"><?php echo esc_attr__( 'Checkin', 'turisbook-booking-system' )?></span>
						<br/>
						<span class="tbs-date"><?php echo esc_attr($book->checkin); ?></span>
						<br/>
					</div>
					<div class="col-6 col-md-6">
						<span class="tbs-date-type"><?php echo esc_attr__( 'Checkout', 'turisbook-booking-system' )?></span>
						<br/>
						<span class="tbs-date"><?php echo esc_attr($book->checkout); ?></span>
					</div>
				</div>
				<div class="row">
					<div class="col-12 col-md-12">
						<small style="color:#a7a7a7;"><i class="fa fa-info-circle"></i> <?php echo sprintf(__('%s night stay.', 'turisbook-booking-system' ), $book->nights); ?></small>
					</div>
				</div>
			</div>
			<br/>
			<br/>
			<?php
			$haspromotion = false;
			$total_with_taxes = $book->total_with_discount;
			foreach($book->lines as $line){
				$post = $tbs->getPostByMeta(['meta_key' => 'turisbook_room_id', 'meta_value' => $line->room_id,'post_type'=>'turisbook_room']);
				$unit_id = $post->ID;
				if($tbs->PolylangIsActive()){
					$unit_id = pll_get_post($post->ID, $actual_lang);
					$post = get_post($unit_id);
				}

				if($line->promotion_id >0){
					$haspromotion=true;
				}

				for($i=0; $i<$line->rooms_qtt;$i++){
					?>
					<div class="row">
						<div class="col-12" class="" style="padding:10px 20px 0;border-radius:5px; border:1px solid #a7a7a7;">
							<h4><?php echo esc_attr($post->post_title); ?></h4>
							<span><i class="fa fa-coffee"></i> <?php echo esc_attr($line->mealplan->translation->name); ?></span>
							<br/>
							<span>
								<i class="fa fa-bookmark"></i> <?php echo esc_attr($line->policy->translation->name); ?>
								<br>
								<div style="text-indent:20px;font-style: italic;">
									<small><?php echo wp_kses_post($line->policy->translation->description);?></small>
								</div>
							</span>
							<span><?php echo __('Adults: ', 'turisbook-booking-system' ) . esc_attr($line->adults_qtt); ?> </span> <?php if($line->children_qtt) { ?>	| <span><?php echo __('Children: ', 'turisbook-booking-system' ) . esc_attr($line->children_qtt); ?> </span> <?php }?>
							<br/>
							<br/>
							<?php if($book->choose_bed_type == 1){?>
								<b><?php echo __('Choose your bed ', 'turisbook-booking-system' )?></b> (<?php echo __('if available', 'turisbook-booking-system' )?>):
								<ul style="list-style-type: none;">
									<li><input type="radio" class="" name="room_choose_beds_<?php echo esc_attr($line->rate_category_id); ?>" value="1" checked/> <img style="width:25px;" src="<?php echo TURISBOOK_PUBLIC_URL . '/img/beds-twin.jpg' ?>"/> 2 <?php echo __('individual beds', 'turisbook-booking-system' ) ?></li>
									<li><input type="radio" class="" name="room_choose_beds_<?php echo esc_attr($line->rate_category_id); ?>" value="1" /> <img style="width:25px;" src="<?php echo TURISBOOK_PUBLIC_URL . '/img/beds-king.jpg' ?>"/> 1 <?php echo __('extra large double bed', 'turisbook-booking-system' ) ?></li>
								</ul>
							<?php } ?>
						</div>
					</div>
					<?php
				}
				?>
			<?php } ?>
			<br/>
			<br/>
			<div class="tbs-price-resume">
				<div class="row border-bottom">
					<div class="col-6 col-md-6"><?php echo __('Price/night', 'turisbook-booking-system' ) ?></div>
					<div class="col-6 col-md-6 text-right"><?php echo number_format($book->price_pernight,2,'.',' ') ?> &euro;</div>
				</div>
				<?php if($book->extrabed_total_price > 0){?>
					<div class="row border-bottom">
						<div class="col-6 col-md-6"><?php echo __('Extrabeds', 'turisbook-booking-system' ) ?></div>
						<div class="col-6 col-md-6 text-right"> <?php echo number_format($book->extrabed_total_price,2,'.',' ');?> &euro;</div>
					</div>
				<?php } ?>
				<?php if($haspromotion){?>
					<div class="row border-bottom">
						<div class="col-6 col-md-6"><?php echo __('Total without discount', 'turisbook-booking-system' ) ?></div>
						<div class="col-6 col-md-6 text-right"> <?php echo number_format($book->total_without_discount,2,'.',' ');?> &euro;</div>
					</div>
					<div class="row border-bottom">
						<div class="col-6 col-md-6"><?php echo __('Promotion', 'turisbook-booking-system' ) ?></div>
						<div class="col-6 col-md-6 text-right text-red">-<?php echo number_format($book->total_without_discount - $book->total_with_discount,2,'.',' ');?> &euro;</div>
					</div>
				<?php } ?>
				<div class="row border-bottom">
					<div class="col-6 col-md-2"><?php echo __('Taxes', 'turisbook-booking-system' ) ?></div>
					<div class="col-6 col-md-10">
						<div class="row tbs-resume-small tbs-sub-row">
							<div class="col-6"><?php echo __('VAT', 'turisbook-booking-system' ) ?></div>
							<div class="col-6 text-right"><?php echo number_format($book->vat,2,'.',' ') ?> &euro;</div>
						</div>

						<?php foreach($book->taxes as $tax){
							$total_with_taxes += $tax->price_total;
							?>
							<div class="row no-padding tbs-resume-small tbs-sub-row">
								<div class="col-6"><?php echo esc_attr($tax->translation); ?></div>
								<div class="col-6 text-right"><span class="resume-vat-value"><?php echo number_format($tax->price_total,2,',',''); ?></span> &euro;</div>
							</div>
						<?php } ?>

					</div>
				</div>
				<?php if(floatval($book->cupon_discount)>0){?>
					<div class="row border-bottom">
						<div class="col-6 col-md-6"><?php echo __('Cupon', 'turisbook-booking-system' ) ?></div>
						<div class="col-6 col-md-6 text-right text-red"> -<?php echo number_format($book->cupon_discount,2,'.',' ');?> &euro;</div>
					</div>
				<?php } ?>
				<div class="row border-bottom tbs-booking-total-price" price-without-cupon="<?php echo number_format($book->price_without_cupon,2,'.','');?>">
					<div class="col-6 col-md-6 text-bold"><?php echo __('Total', 'turisbook-booking-system' ) ?></div>
					<div class="col-6 col-md-6 text-right text-bold"><span class="price"><?php echo number_format($total_with_taxes,2,'.',' ')?></span> &euro;</div>
				</div>
				<div class="row border-bottom tbs-booking-prepay-price">
					<div class="col-6 col-md-6 text-bold"><?php echo __('Pay now', 'turisbook-booking-system' ) ?></div>
					<div class="col-6 col-md-6 text-right text-bold"><span class="price"><?php echo number_format($book->prepay,2,'.',' ')?></span> &euro;</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12 text-center">
				<br/>
				<br/>
				<button style="width:150px; border-radius:15px;" class="print"  target=".toprint"><i class="fas fa-print"></i></button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	jQuery(function($){

		$(document).on('click', '.print', function(){
			var obj = $(this);
			var target = obj.attr('target');
			$(target).printElement({
				overrideElementCSS:[
				"<?php echo TURISBOOK_PUBLIC_URL;?>/css/bootstrap-iso.css",
				"<?php echo TURISBOOK_PUBLIC_URL;?>/css/turisbook-booking-system-public.css",
				]
			}); 
			return false;
		});
	});
</script>