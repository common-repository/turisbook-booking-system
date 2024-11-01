<div class="tbs-payment <?php echo $selected?>" tbs-payment="3">
	<div class="tbs-payment-head">
		<div class="tbs-payment-name"><?php echo __( 'Wire Bank', 'turisbook-booking-system' )?></div>
		<div class="tbs-payment-logos"></div>
	</div>
	<div class="tbs-payment-body">
		<div class="row mb-0">
			<div class="col-12 col-md-12">
				<?php
				foreach($payments as $p){
					if($p->id==3){
						?>
						<?php echo __( 'Bank', 'turisbook-booking-system' )?>: <strong><?php echo esc_attr($p->extra->bank); ?></strong><br/>
						<?php echo __( 'IBAN', 'turisbook-booking-system' )?>: <strong><?php echo esc_attr($p->extra->iban); ?></strong><br/>
						<?php echo __( 'SWIFT', 'turisbook-booking-system' )?>: <strong><?php echo esc_attr($p->extra->swift); ?></strong>
						<?php
					}
				}
				?>
			</div>
		</div>
	</div>
</div>