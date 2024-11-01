<?php

function DrawCalendar($month_name, $month_days, $year, $month, $today_timestamp, $availabilities, $weekday, $last_available=false){
	$positions = $weekday;
	$week_days_array = [__('Sun', 'turisbook-booking-system' ), __('Mon', 'turisbook-booking-system' ), __('Tue', 'turisbook-booking-system' ), __('Wed', 'turisbook-booking-system' ), __('Thu', 'turisbook-booking-system' ), __('Fri', 'turisbook-booking-system' ), __('Sat', 'turisbook-booking-system' )];

	?>
	<div class="col col-12 col-md-6 month pt-0">
		<div class="row monthName"><div class="col-12"><?php echo esc_attr($month_name)?></div></div>
		<div class="row seven-cols weekdays">
			<?php foreach($week_days_array as $wda){ ?>
				<div class="col-1 col-md-1" style="text-align: center;"><?php echo $wda?></div>
			<?php }?>
		</div>
		<div class="row seven-cols week">
			<?php
			echo wp_kses_post(str_repeat('<div class="col-1 col-md-1">&nbsp;</div>', $weekday));
			for($i = 1; $i<=$month_days;$i++){
				$date =  $year. "-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-" . str_pad($i, 2, '0', STR_PAD_LEFT);
				$date_f =  str_pad($i, 2, '0', STR_PAD_LEFT). "-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-" . $year;
				$i_date = strtotime($date);
				$past = $i_date < $today_timestamp ? 'past' : '' ;
				$active = $i_date == $today_timestamp ? 'active' : '';

				$available ="";
				if($past==''){
					if(array_key_exists($date,$availabilities)){
						$available = $availabilities[$date]->allotment > 0 ? "available" : "unavailable";

					}else{
						$available = "unavailable";
					}
				}
				?>
				<div class="col-1 col-md-1 text-center day-wrap <?php echo esc_attr($past); ?> <?php echo esc_attr($active); ?> <?php echo esc_attr($available); ?> <?php echo  $available=="unavailable" && $last_available ? "unavailable-checkin" : "" ?>" last_available="<?php echo $last_available ? "true":"false";?>" date="<?php echo esc_attr($date_f); ?>" time="<?php echo esc_attr($i_date); ?>">
					<div class="day"><?php echo esc_attr($i); ?></div>
				</div>
				<?php

				$last_available = false;
				if($past==''){
					if(array_key_exists($date,$availabilities)){
						$last_available = $availabilities[$date]->allotment > 0;
					}
				}
				$positions++;
				if($positions == 7){
					$positions = 0;
					?>
				</div>
				<div class="row seven-cols week">
					<?php
				}
			}
			echo wp_kses_post(str_repeat('<div class="col-1 col-md-1">&nbsp;</div>', 7 - $positions));
			?>
		</div>
	</div>
	<?php
	return ['last_available' => $last_available];
}
?>
<div class="month-navigation">      
	<ul>
		<?php
		$prev_active = ($today_year >= $prev_year && $today_month <= $prev_month) || $today_year < $prev_year;
		?>
		<li class="prev" month="<?php echo esc_attr($prev_month); ?>" year="<?php echo esc_attr($prev_year); ?>"><?php if($prev_active){ ?> &#10094; <?php echo esc_attr($prev_month_name); ?>, <?php echo esc_attr($prev_year); ?><?php }else{echo "&nbsp;";}?></li>
		<li class="next" month="<?php echo esc_attr($next_month); ?>" year="<?php echo esc_attr($next_year); ?>"><?php echo esc_attr($next_month_name); ?>, <?php echo esc_attr($next_year); ?> &#10095;</li>
	</ul>
</div>

<div class="bootstrap-iso">
	<div class="container-fluid">
		<div class="row">
			<?php $lastAvailable = DrawCalendar($actual_month_name, $actual_month_days, $actual_year, $actual_month, $today_timestamp, $availabilities, $weekday)['last_available']; ?>
			<?php DrawCalendar($actual_month_2_name, $actual_month_2_days, $actual_year_2, $actual_month_2, $today_timestamp, $availabilities, $weekday_2, $lastAvailable); ?>

		</div>
	</div>
</div>



<div style="clear:both;"></div>



<div class="bootstrap-iso">
	<div class="container-fluid tbs-room-rt">

	</div>
</div>