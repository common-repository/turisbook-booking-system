<div style="padding:20px; border-radius:3px; border:1px solid #a7a7a7;">
	<div class="row">
		<div class="col-12">
			<h2><?php echo esc_attr__( 'Personal Data', 'turisbook-booking-system' )?></h2>
		</div>
	</div>
	<div class="row">
		<div class="col-6 col-md-6">
			<div class="tbs-form-field-group">
				<label><?php echo esc_attr__( 'Name', 'turisbook-booking-system' )?><span class="text-red"> *</span></label>
				<input type="text" name="first_name" value="" class="tbs-form-input-firstname" tbs-required="true"/>	
			</div>
		</div>
		<div class="col-6 col-md-6">
			<div class="tbs-form-field-group">
				<label><?php echo esc_attr__( 'Surname', 'turisbook-booking-system' )?><span class="text-red"> *</span></label>
				<input type="text" name="last_name" value="" class="tbs-form-input-lastname" tbs-required="true"/>	
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-6 col-md-6">
			<div class="tbs-form-field-group">
				<label><?php echo esc_attr__( 'Email', 'turisbook-booking-system' )?><span class="text-red"> *</span></label>
				<input type="text" name="email" value="" class="tbs-form-input-email" tbs-required="true"/>	
			</div>
		</div>
		<div class="col-6 col-md-6">
			<div class="tbs-form-field-group">
				<label><?php echo esc_attr__( 'Phone', 'turisbook-booking-system' )?><span class="text-red"> *</span></label>
				<input type="text" name="phone" value="" class="tbs-form-input-phone" tbs-required="true"/>	
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-md-12">
			<div class="tbs-form-field-group">
				<label><?php echo esc_attr__( 'Address', 'turisbook-booking-system' )?><span class="text-red"> *</span></label>
				<input type="text" name="address" value="" class="tbs-form-input-address" tbs-required="true"/>	
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-4 col-md-4">
			<div class="tbs-form-field-group field-group-inline">
				<label><?php echo esc_attr__( 'Postal Code', 'turisbook-booking-system' )?></label>
				<input type="text" name="pc1" class="tbs-input-size-4 tbs-input-left tbs-form-input-pc1 onMaxFocusTo" nextfocus=".tbs-form-input-pc2" placeholder="____" maxlength="4" value=""/>
				<span class="input-sep">-</span>
				<input type="text" name="pc2" class="tbs-input-size-3 tbs-input-right tbs-form-input-pc2 checkPreFocus" prevfocus=".tbs-form-input-pc1" placeholder="___" maxlength="3" value=""/>
			</div>
		</div>
		<div class="col-4 col-md-4">
			<div class="tbs-form-field-group">
				<label><?php echo esc_attr__( 'City', 'turisbook-booking-system' )?><span class="text-red"> *</span></label>
				<input type="text" name="city" value="" class="tbs-form-input-city" tbs-required="true"/>	
			</div>
		</div>

		<div class="col-4 col-md-4">
			<div class="tbs-form-field-group filled">
				<label><?php echo esc_attr__( 'Country', 'turisbook-booking-system' )?><span class="text-red"> *</span></label>
				<select name="country" class="tbs-form-input-country" tbs-required="true">
					<?php 
					foreach($countries as $key => $country){
						?>
						<option value="<?php echo esc_attr($key); ?>" <?php echo $key == 174 ? " selected " : "" ?>><?php echo esc_html($country); ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	</div>
	<h2><?php echo esc_attr__( 'Payment', 'turisbook-booking-system' )?></h2>
	<div class="row">
		<div class="col-12 col-md-12">
			<div class="tbs-form-payments">
				<?php
				$payments_ids = [];
				foreach($payments as $p){
					$payments_ids[] = $p->id;
				}

				$selected = ' selected ';
				if(in_array(1,$payments_ids) || in_array(2,$payments_ids)) include TURISBOOK_PUBLIC_PATH . '/partials/payments/turisbook-booking-system-cc.php';
				if(in_array(3,$payments_ids)) include TURISBOOK_PUBLIC_PATH . '/partials/payments/turisbook-booking-system-wb.php';
			?></div>
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-md-12">
			<div class="tbs-form-field-group">
				<label><?php echo esc_attr__( 'Requests / Remarks', 'turisbook-booking-system' )?></label>
				<textarea  name="remarks" class=" tbs-form-input-remarks"></textarea>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-md-12">
			<div class="tbs-form-field-group checkbox-group">
				<input type="checkbox" id="tbs-form-accept" class="tbs-form-input-accept" name="accept" value="1" tbs-required="true">
				<a href="#" tbs-modal data-tbs-modal-target=".turisbook-modal"><?php echo esc_attr__( 'I have read and accept the Terms and Conditions of sale', 'turisbook-booking-system' )?></a>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-6 col-md-6">
			<a href="<?php echo $return_link; ?>"><i class="fa fa-caret-left" aria-hidden="true"></i> <?php echo esc_attr__( 'Cancel and Return', 'turisbook-booking-system' )?></a>
		</div>
		<div class="col-6 col-md-6" style="text-align:right;">
			<button class="btn btn-select-ratecategory" style="width:100%;text-align:left;"><?php echo esc_attr__( 'Finish', 'turisbook-booking-system' )?></button>
		</div>
	</div>
</div>



<div class="tbs-modal turisbook-modal">
	<div class="modal-content">
		<span class="close">&times;</span>
		<div class="modal-content-head">
			<?php echo __('Sale Condition ', 'turisbook-booking-system' )?>
		</div>
		<div class="modal-content-body">
			<?php
			foreach($prebook->ulines as $line){
				$post = $tbs->getPostByMeta(['meta_key' => 'turisbook_room_id', 'meta_value' => $line->room_id,'post_type'=>'turisbook_room']);
				$unit_id = $post->ID;
				if($tbs->PolylangIsActive()){
					$unit_id = pll_get_post($post->ID, $actual_lang);
					$post = get_post($unit_id);
				}

				?>
				<h4 class="title-bordered"><?php echo esc_attr($line->rooms_qtt); ?> x "<?php echo esc_attr($post->post_title); ?>"</h4>
				<span><?php echo __('Adults: ', 'turisbook-booking-system' ) . esc_attr($line->adults_qtt); ?> </span> <?php if($line->children_qtt) { ?>	<br/><span><?php echo __('Children: ', 'turisbook-booking-system' ) . esc_attr($line->children_qtt); ?> </span> <?php }?>
				<br/>
				<span><i class="fa fa-coffee"></i> <?php echo esc_attr($line->mp->name); ?></span><br/>
				<span><i class="fa fa-bookmark"></i> <?php echo esc_attr($line->poli->translation->name); ?><br/>
					<small><strong><?php echo wp_kses_post($line->poli->translation->description);?></strong></small>
				</span>
				<br/>
				<br/>
				<hr/>
				<?php
			}
			?>
		</div>
	</div>
</div>