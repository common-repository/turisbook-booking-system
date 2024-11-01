<div class="bootstrap-iso tbs-booking-resume">
	<div class="container-fluid">
		<form id="tbs-booking-form" action="<?php echo esc_attr($page_confirmation); ?>">
			<div class="row">
				<div class="col-12 col-md-6">
					<?php include TURISBOOK_PUBLIC_PATH . '/partials/turisbook-booking-system-resume.php'; ?>
				</div>
				<div class="col-12 col-md-6">
					<?php include TURISBOOK_PUBLIC_PATH . '/partials/turisbook-booking-system-form.php'; ?>
				</div>
			</div>
			<div class="row" style="border-top: 1px solid #efefef;">
				<div class="col-12 col-md-12">
					<a href="https://turisbook.com" target="_blank">
						<small style="font-family: verdana; font-size: 9px; color: #666666">Powered by</small>
						<br/>
						<img width="137" height="10" src="<?php echo TURISBOOK_PUBLIC_URL . '/img/poweredby_logo.png'; ?>">
					</a>
				</div>
			</div>
		</form>
	</div>
</div>