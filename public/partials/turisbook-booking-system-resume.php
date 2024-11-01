<div class="tbs-booking-resume-head">
	<div class="tbs-resume-toggle"><i class="fa fa-chevron-down"></i></div>

	<div class="row">
		<div class="col-6 col-md-6">
			<span class="tbs-date-type"><?php echo esc_attr__( 'Checkin', 'turisbook-booking-system' )?></span>
			<br/>
			<span class="tbs-date"><?php echo esc_attr($prebook->checkin); ?></span>
			<br/>
		</div>
		<div class="col-6 col-md-6">
			<span class="tbs-date-type"><?php echo esc_attr__( 'Checkout', 'turisbook-booking-system' )?></span>
			<br/>
			<span class="tbs-date"><?php echo esc_attr($prebook->checkout); ?></span>
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-md-12">
			<small style="color:#a7a7a7;"><i class="fas fa-info-circle"></i> <?php echo sprintf(__('%s night stay.', 'turisbook-booking-system' ), $prebook->nights); ?></small>
		</div>
	</div>
	<br/>
	<div class="row">
		<div class="col-6 col-md-6">
			<span><?php echo esc_attr__( 'Booking total', 'turisbook-booking-system' )?></span><br/>
			<span class="tbs-booking-total"><b><span class="price"><?php echo esc_attr($price); ?></span> &euro;</b> <small>(<?php echo esc_attr__( 'taxes included', 'turisbook-booking-system' )?>)</small></span>
		</div>
		<div class="col-6 col-md-6">
			<span><?php echo __('Promotional Code', 'turisbook-booking-system' );?>:</span>
			<input type="text" class="tbs-cupon" value="" /><button class="btn tbs-cupon-btn"><i class="fas fa-check"></i></button><button class="btn tbs-cupon-btn-cancel hidden"><i class="fa fa-times"></i></button>
			<input type="hidden" name="cupon"/>
			<small class="tbs-invalid-cupon hidden"></small>
		</div>
	</div>
</div>
<br/>
<div class="tbs-booking-resume-body">
	<div class="row">
		<div class="col-12 col-md-12">
			<hr/>
		</div>
	</div>
	<?php include TURISBOOK_PUBLIC_PATH . '/partials/turisbook-booking-system-resume-body.php'; ?>
</div>
