<?php
if ( ! defined( 'WPINC' ) ) exit;
?>
<form id="turisbook-unleash-establishment" action="" method="POST">

	<table class="form-table">
		<tbody>
			<tr>
				<th>
					<h2><?php echo __('Establishment','turisbook-booking-system'); ?></h2>
				</th>
				<td>	<select disabled="true"><option><?php echo esc_attr( $establishment ); ?></option></select></td>
			</tr>
			<tr>
				<th></th>
				<td><button type="submit" class="button-primary logout-form-submit">Logout</button></td>
			</tr>
		</tbody>
	</table>
</form>