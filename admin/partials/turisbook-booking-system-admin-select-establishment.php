<?php
if ( ! defined( 'WPINC' ) ) exit;
?>
<h3><?php esc_attr_e( 'Please select establishment', 'turisbook-booking-system' ); ?></h3>
<form id="turisbook-choose-establishment" action="" method="POST">

	<select class="establishment form-control">
		<?php
		foreach($establishments as $establishment){
			?>
			<option <?php echo get_site_url() == $establishment->website ? " checked ":""?> unique_id="<?php echo esc_attr($establishment->unique_id); ?>" establishment_type="<?php echo esc_attr($establishment->type); ?>" value="<?php echo esc_attr($establishment->id); ?>"><?php echo esc_attr( $establishment->name )?></option>
			<?php
		}
		?>
	</select>
		<br/><br/>
	<div class="btn-wrapper is-active">
		<button class="button-primary lock-form-submit"><?php esc_attr_e( 'Lock', 'turisbook-booking-system'  ); ?></button>
	</div>
</form>