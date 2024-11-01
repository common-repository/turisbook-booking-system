<div class="tbs-booking-cart tbs-sticky" offset="<?php echo esc_attr($a['offset']);?>">
    <div class="tbs-cart-toggle"><i class="fa fa-chevron-up"></i></div>
    <form action="<?php echo esc_attr($page_prebook); ?>" availabilities="<?php echo esc_attr($a['availabilities']);?>" class="tbs-rate-select" autocomplete="OFF">
        <div class="tbs-cart-body">
            <h3 class="text-center"><?php echo esc_attr__( 'Make your reservation!', 'turisbook-booking-system' )?></h3>

            <p style="text-align: center; <?php echo $hasdates ? "": "display:none;" ?>" class="cart-info">
                <small>
                    <i class="fas fa-calendar-check"></i> <span class="cart-checkin"><?php echo $checkin ;?></span> - <span class="cart-checkout"><?php echo $checkout ;?></span><br/>
                    <span style="text-transform: capitalize;"><?php echo esc_html__( 'nights', 'turisbook-booking-system' ) ?></span>: <span class="cart-nights"><?php echo $days ?></span>
                </small>
            </p>
            <hr>
            <table class="tbs-booking-cart-table"><tbody></tbody></table>
            <hr>
        </div>
        <div class="tbs-cart-total"><?php echo esc_attr__( 'Total', 'turisbook-booking-system' )?>: <span class="tbs-booking-total">0,00</span> <b>€</b></div>
        <div class="tbs-cart-btn"><button class="tbs-search-button disabled"><?php echo esc_attr__( 'Book Now', 'turisbook-booking-system' )?></button></div>
        <input type="hidden" name="unit_rates" value="[]">
        <input type="hidden" class="" name="checkin" value="<?php echo $checkin ;?>" />
        <input type="hidden" class="" name="checkout" value="<?php echo $checkout ;?>"/>
    </form>
    <div class="turisbook-cart-error">
        <span class="turisbook-cart-error-dates"><?php echo __('Please select checkin and checkout dates','turisbook-booking-system');?></span>
        <span class="turisbook-cart-error-rooms"><?php echo __('No accomodation selected.<br/> Please select an accomodation from the dropdown menu','turisbook-booking-system');?></span>
    </div>
</div>