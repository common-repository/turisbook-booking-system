<div class="bootstrap-iso tbs-booking-resume">
	<div class="container-fluid">
		<form id="tbs-cardex-form" action="?thankyou">
			<input type="hidden" name="uid" value="<?php echo esc_attr($uid); ?>">
			<div class="row">
				<div class="col-12 col-md-12">
					<h4><b><?php echo esc_attr__( 'Booking nr.', 'turisbook-booking-system' )?> <?php echo $cardex->reference; ?></b></h4>
					<h4><b><?php echo $cardex->ota; ?></b></h4>
					<h4><?php echo esc_attr__( 'As regarding your reservation, please, as mandatory by the Portuguese law, please fill this guest registration form:', 'turisbook-booking-system' )?></h4>
					<br/>
				</div>
			</div>
			<div class="row">
				<div class="col-12 col-md-6">
					<div style="padding:20px; border-radius:3px; border:1px solid #a7a7a7;">
						<div class="row">
							<div class="col-12">
								<h2><?php echo esc_attr__( 'Personal Data', 'turisbook-booking-system' )?></h2>
							</div>
						</div>
						<div class="row">
							<div class="col-12 col-md-12">
								<div class="tbs-form-field-group">
									<label><?php echo esc_attr__( 'Full Name', 'turisbook-booking-system' )?><span class="text-red"> *</span></label>
									<input type="text" name="full_name" value="<?php echo $cardex->full_name; ?>" class="tbs-form-input-fullname" tbs-required="true"/>	
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12 col-md-12">
								<h5><?php echo esc_attr__( 'Birthday Info', 'turisbook-booking-system' )?></h5>
							</div>
							<div class="col-4 col-md-4">
								<div class="tbs-form-field-group field-group-inline">
									<label><?php echo esc_attr__( 'Birthday', 'turisbook-booking-system' )?><span class="text-red"> *</span></label>
									<input type="text" name="birthday_day" class="tbs-input-size-3 tbs-input-left tbs-form-input-birthday_day onMaxFocusTo" nextfocus=".tbs-form-input-birthday_month" placeholder="dd" maxlength="2" value="" tbs-required="true"/>
									<span class="input-sep">/</span>
									<input type="text" name="birthday_month" class="tbs-input-size-3 tbs-input-left tbs-form-input-birthday_month checkPreFocus onMaxFocusTo" prevfocus=".tbs-form-input-birthday_day" nextfocus=".tbs-form-input-birthday_year" placeholder="mm" maxlength="2" value="" tbs-required="true"/>
									<span class="input-sep">/</span>
									<input type="text" name="birthday_year" class="tbs-input-size-4 tbs-input-right tbs-form-input-birthday_year checkPreFocus" prevfocus=".tbs-form-input-birthday_month" placeholder="yyyy" maxlength="4" value="" tbs-required="true"/>
								</div>
							</div>
							<div class="col-4 col-md-4">
								<div class="tbs-form-field-group">
									<label><?php echo esc_attr__( 'Place of Birth', 'turisbook-booking-system' )?><span class="text-red"> *</span></label>
									<input type="text" name="place_birthday" value="" class="tbs-form-input-place-birth" tbs-required="true"/>	
								</div>
							</div>

							<div class="col-4 col-md-4">
								<div class="tbs-form-field-group filled">
									<label><?php echo esc_attr__( 'Nationality', 'turisbook-booking-system' )?><span class="text-red"> *</span></label>
									<select name="nationality" class="tbs-form-input-nationality" tbs-required="true">
										<option value="0"><?php echo esc_attr__( '== Select ==', 'turisbook-booking-system' )?></option>
										<?php 
										foreach($countries as $key => $country){
											?>
											<option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($country); ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12 col-md-12">
								<h5><?php echo esc_attr__( 'Residence Info', 'turisbook-booking-system' )?></h5>
							</div>
							<div class="col-4 col-md-4">
								<div class="tbs-form-field-group">
									<label><?php echo esc_attr__( 'City', 'turisbook-booking-system' )?><span class="text-red"> *</span></label>
									<input type="text" name="place_residence" value="" class="tbs-form-input-place-residence" tbs-required="true"/>	
								</div>
							</div>

							<div class="col-4 col-md-4">
								<div class="tbs-form-field-group filled">
									<label><?php echo esc_attr__( 'Country', 'turisbook-booking-system' )?><span class="text-red"> *</span></label>
									<select name="country_residence" class="tbs-form-input-country-residence" tbs-required="true">
										<option value="0"><?php echo esc_attr__( '== Select ==', 'turisbook-booking-system' )?></option>
										<?php 
										foreach($countries as $key => $country){
											?>
											<option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($country); ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-4 col-md-4">
								<div class="tbs-form-field-group">
									<label><?php echo esc_attr__( 'NIF', 'turisbook-booking-system' )?></label>
									<input type="text" name="nif" value="" class="tbs-form-input-nif"/>	
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12 col-md-12">
								<h5><?php echo esc_attr__( 'Identification Document', 'turisbook-booking-system' )?></h5>
							</div>
							<div class="col-4 col-md-4">
								<div class="tbs-form-field-group">
									<label><?php echo esc_attr__( 'Type', 'turisbook-booking-system' )?><span class="text-red"> *</span></label>
									<select type="text" name="document_type" value="" class="tbs-form-input-place-residence" tbs-required="true">
										<option value="2"><?php echo esc_attr__( 'Citizen Card', 'turisbook-booking-system' )?></option>
										<option value="1"><?php echo esc_attr__( 'Passport', 'turisbook-booking-system' )?></option>
										<option value="3"><?php echo esc_attr__( 'Driving license', 'turisbook-booking-system' )?></option>
									</select>
								</div>
							</div>
							<div class="col-4 col-md-4">
								<div class="tbs-form-field-group">
									<label><?php echo esc_attr__( 'Number', 'turisbook-booking-system' )?><span class="text-red"> *</span></label>
									<input type="text" name="document_number" value="" class="tbs-form-input-document-number" tbs-required="true"/>	
								</div>
							</div>
							<div class="col-4 col-md-4">
								<div class="tbs-form-field-group filled">
									<label><?php echo esc_attr__( 'Country issuer', 'turisbook-booking-system' )?><span class="text-red"> *</span></label>
									<select name="document_country" class="tbs-form-input-country-document-country" tbs-required="true">
										<option value="0"><?php echo esc_attr__( '== Select ==', 'turisbook-booking-system' )?></option>
										<?php 
										foreach($countries as $key => $country){
											?>
											<option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($country); ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-6">
					<div style="padding:20px; border-radius:3px; border:1px solid #a7a7a7;">
						<h2><?php echo esc_attr__( 'Refundable Damage Deposit', 'turisbook-booking-system' )?></h2>
						<p><i><?php echo esc_attr__( 'The amount will only be charged if any major damages are noticed.', 'turisbook-booking-system' )?></i></p>
						<div class="row">
							<div class="col-12 col-md-12">
								<div class="tbs-form-payments">
									<?php
									$selected = " selected ";
									include TURISBOOK_PUBLIC_PATH . '/partials/payments/turisbook-booking-system-cc.php';
									?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-3 col-md-3 pull-right" style="text-align:right;">
								<button class="btn btn-select-ratecategory" style="text-align:left;"><?php echo esc_attr__( 'Finish', 'turisbook-booking-system' )?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>

	<div class="row" style="margin-top:20px;border-top: 1px solid #efefef;">
		<div class="col-12 col-md-12">
			<a href="https://turisbook.com" target="_blank">
				<small style="font-family: verdana; font-size: 9px; color: #666666">Powered by</small>
				<br/>
				<img width="137" src="<?php echo TURISBOOK_PUBLIC_URL . '/img/poweredby_logo.png' ?>">
			</a>
		</div>
	</div>
</div>


<script type="text/javascript">

	jQuery(function($){
		$('.singledate').flatpickr({
			dateFormat: "d-m-Y",
			disableMobile: "true",
			locale:'<?php echo Turisbook_Booking_System::getLanguage(); ?>',
			monthSelectorType:'static',

		});

	});
</script>