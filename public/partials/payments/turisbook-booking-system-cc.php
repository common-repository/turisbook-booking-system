<div class="tbs-payment tbs-payment-cc <?php echo $selected; ?>" tbs-payment="0">
	<?php $selected = '';?> 
	<div class="tbs-payment-head">
		<div class="tbs-payment-name"><?php echo __( 'Credit Card', 'turisbook-booking-system' )?></div>
		<div class="tbs-payment-logos">
			<img src="<?php echo TURISBOOK_PUBLIC_URL . '/img/mastercard.png' ?>" class="tbs-mastercard" />
			<img src="<?php echo TURISBOOK_PUBLIC_URL . '/img/visa.png' ?>" class="tbs-visa" />
		</div>
	</div>
	<div class="tbs-payment-body">
		<div class="row">
			<div class="col-12 col-md-6">
				<div class="tbs-form-field-group">
					<label><?php echo __( 'Card Number', 'turisbook-booking-system' )?><span class="text-red"> *</span></label>
					<input type="text" name="cc_number" class="tbs-form-input-card-card-number cc_validation" maxlength="16" value="" tbs-validate-card="true">
				</div>
				
			</div>
			<div class="col-12 col-md-6">
				<div class="tbs-form-field-group">
					<label><?php echo __( 'Cardholder Name', 'turisbook-booking-system' )?><span class="text-red"> *</span></label>
					<input type="text" name="cc_name" class="tbs-form-input-card-card-name cc_validation" value="">
				</div>
			</div>
		</div>
		<div class="row mb-0">
			<div class="col-6 col-md-6">
				<div class="tbs-form-field-group">
					<label><?php echo __( 'CVV', 'turisbook-booking-system' )?><span class="text-red"> *</span></label>
					<input type="text" name="cc_cvv" maxlength="3" class="cc_validation" value="">
				</div>
			</div>
			<div class="col-6 col-md-6">
				<div class="tbs-form-field-group field-group-inline">
					<label><?php echo __( 'Validity', 'turisbook-booking-system' )?><span class="text-red"> *</span></label>
					<input type="text" name="cc_month" name="card_validity_month" class="tbs-integer tbs-input-size-3 tbs-input-left tbs-form-input-card-validity-month onMaxFocusTo cc_validation" nextfocus=".tbs-form-input-card-validity-year" maxlength="2" placeholder="<?php echo esc_attr( 'MM', 'turisbook-booking-system' )?>" value="">
					<span class="input-sep">/</span>
					<input type="text" name="cc_year" name="card_validity_year" class="tbs-integer tbs-input-size-3 tbs-input-right tbs-form-input-card-validity-year checkPreFocus cc_validation" prevfocus=".tbs-form-input-card-validity-month" maxlength="2" placeholder="<?php echo esc_attr( 'YY', 'turisbook-booking-system' )?>" value="">
				</div>
			</div>
		</div>
	</div>
</div>