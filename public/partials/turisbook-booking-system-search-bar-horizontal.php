<div class="bootstrap-iso">
	<div class="container-fluid">
		<?php if(trim($a['text1']) !== ''){ ?>
			<h3 style="color:<?php echo $a['text1_color'];?>;"><?php echo $a['text1'];?> <smal style="font-size:22px;color:<?php echo $a['text2_color'];?>;"><?php echo $a['text2'];?></smal></h3>
		<?php } ?>
		<form autocomplete="off" class="search-form" method="GET" action="<?php echo $post_type_link;?>">
			<div class="row">
				<div class="col-10 col-md-8 p-1 form-group my-input-container my-input-calendar">
					<input class="form-control flatpickr white-readonly" type="text" readonly placeholder="Checkin - Checkout"/>
					<input type="hidden" name="checkin" value="<?php echo esc_attr($checkin); ?>" />
					<input type="hidden" name="checkout" value="<?php echo esc_attr($checkout); ?>" />
				</div>
				<div class="col-2 col-md-4 p-1 form-group">
					<button class="tbs-search-button" type="submit" ><i class="fa fa-search"></i></button>
				</div>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">

	jQuery(function($){
		$('.flatpickr').flatpickr({
			dateFormat: "d-m-Y",
			disableMobile: "true",
			minDate: "today",
			mode:'range',
			locale:'<?php echo Turisbook_Booking_System::getLanguage(); ?>',
			<?php if($checkin!=''){?>
				defaultDate: ['<?php echo $checkin; ?>', '<?php echo $checkout; ?>'],
			<?php } ?>
			monthSelectorType:'static',
			onChange: function(selectedDates, dateStr, instance) {
				if(selectedDates.length==2){
					var dates = dateStr.split(instance.l10n.rangeSeparator);
					var obj = $(instance.input);
					var form = obj.closest('form');
					form.find('input[name="checkin"]').val(dates[0]);
					form.find('input[name="checkout"]').val(dates[1]);
					form.submit();
				}
			},

		});
	});
</script>