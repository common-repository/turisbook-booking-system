<div id="calendar_container" init="<?php echo $checkin_checkout['hasdates'] ? 1 : 0; ?>"<?php if($checkin_checkout['hasdates']) {?>checkin="<?php echo $checkin_checkout['checkin'];?>" checkout="<?php echo $checkin_checkout['checkout'];?>" <?php } ?>></div>
<div id="booking-content" class="hidden bootstrap-iso"></div>
<input type="hidden" class="unit_id" value="<?php echo get_the_ID()?>"/>
<input type="hidden" class="hid" value="<?php echo $hotel_id; ?>"/>