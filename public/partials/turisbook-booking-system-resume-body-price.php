<div class="row border-bottom">
	<div class="col-6 col-md-6"><?php echo __('Price/night', 'turisbook-booking-system' ) ?></div>
	<div class="col-6 col-md-6 text-right"><?php echo number_format($prebook->price_pernight,2,'.',' ') ?> &euro;</div>
</div>
<?php if($prebook->hasPromotion){?>
	<div class="row border-bottom">
		<div class="col-6 col-md-6"><?php echo __('Total without discount', 'turisbook-booking-system' ) ?></div>
		<div class="col-6 col-md-6 text-right"> <?php echo number_format($prebook->total_without_discount,2,'.',' ');?> &euro;</div>
	</div>
	<div class="row border-bottom">
		<div class="col-6 col-md-6"><?php echo __('Promotion', 'turisbook-booking-system' ) ?></div>
		<div class="col-6 col-md-6 text-right text-red">-<?php echo number_format($prebook->promotion_discount,2,'.',' ');?> &euro;</div>
	</div>
<?php } ?>
<?php if($prebook->extrabed_prices_total > 0){?>
<div class="row border-bottom">
	<div class="col-6 col-md-6"><?php echo __('Extrabeds', 'turisbook-booking-system' ) ?></div>
	<div class="col-6 col-md-6 text-right"> <?php echo number_format($prebook->extrabed_prices_total,2,'.',' ');?> &euro;</div>
</div>
<?php } ?>
<div class="row border-bottom">
	<div class="col-6 col-md-2"><?php echo __('Taxes', 'turisbook-booking-system' ) ?></div>
	<div class="col-6 col-md-10">
		<div class="row tbs-resume-small tbs-sub-row">
			<div class="col-6"><?php echo __('VAT', 'turisbook-booking-system' ) ?></div>
			<div class="col-6 text-right"><?php echo number_format($prebook->vat,2,'.',' ') ?> &euro;</div>
		</div>

		<?php foreach($prebook->taxes as $tax){?>
			<div class="row no-padding tbs-resume-small tbs-sub-row">
				<div class="col-6"><?php echo esc_attr($tax->translation); ?></div>
				<div class="col-6 text-right"><span class="resume-vat-value"><?php echo number_format($tax->price_total,2,',',''); ?></span> &euro;</div>
			</div>
		<?php } ?>

	</div>
</div>
<?php if($prebook->cupon_discount!=0){?>
	<div class="row border-bottom">
		<div class="col-6 col-md-6"><?php echo __('Cupon', 'turisbook-booking-system' ) ?></div>
		<div class="col-6 col-md-6 text-right text-red"> -<?php echo number_format($prebook->cupon_discount,2,'.',' ');?> &euro;</div>
	</div>
<?php } ?>
<div class="row border-bottom tbs-booking-total-price" price-without-cupon="<?php echo number_format($prebook->price_without_cupon,2,'.','');?>">
	<div class="col-6 col-md-6 text-bold"><?php echo __('Total', 'turisbook-booking-system' ) ?></div>
	<div class="col-6 col-md-6 text-right text-bold"><span class="price"><?php echo number_format($prebook->price_with_discounts_and_taxes,2,'.',' ')?></span> &euro;</div>
</div>
<div class="row border-bottom tbs-booking-prepay-price">
	<div class="col-6 col-md-6 text-bold"><?php echo __('Pay now', 'turisbook-booking-system' ) ?></div>
	<div class="col-6 col-md-6 text-right text-bold"><span class="price"><?php echo number_format($prebook->prepay,2,'.',' ')?></span> &euro;</div>
</div>
