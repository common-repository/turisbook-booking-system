<?php if ( ! defined( 'WPINC' ) ) exit;?>


<h3><?php esc_attr_e( 'Please login', 'turisbook-booking-system' ); ?></h3>
<form id="form-turisbook-login">
	<input type="text" name="tb-api-token" value="" class="regular-text tb-api-token" placeholder="Api Access Token"/>
	<br/><br/>	
	<div class="btn-wrapper is-active">
		<button class="button-primary login-form-submit"><?php esc_attr_e( 'Login', 'turisbook-booking-system' ); ?></button>
	</div>
</form>
