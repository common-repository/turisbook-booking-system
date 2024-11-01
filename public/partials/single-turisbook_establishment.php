<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
get_header();

$tbs = new Turisbook_Booking_System();
$ta = new Turisbook_Admin();

global $post;



?>
<main class="site-main" role="main">

    <div class="bootstrap-iso">
        <div class="container-fluid">
            <div class="row mt-3">
                <?php
                $hotel = $post;
                $tbs = new Turisbook_Booking_System();

             $hotel_id = get_option('turisbook-hotel-id');
                $hotel_id = get_post_meta($post->ID,'turisbook_establishment_id',true);
                $children_max_age = get_post_meta($post->ID,'turisbook_children_max_age', 12);



                $get_checkin = isset($_GET['checkin']) ? sanitize_text_field($_GET['checkin']) : false;
                $get_checkout = isset($_GET['checkout']) ? sanitize_text_field($_GET['checkout']) : false;

                $checkin_checkout = $tbs->validateCheckinCheckout($get_checkin, $get_checkout,'d-m-Y');

                $get_availabilities = $get_checkin && $get_checkout;

                $checkin = $checkin_checkout['checkin'];
                $checkout = $checkin_checkout['checkout'];

                $check_rooms_count = false;

                $post_type = get_post_type($post);
                $post_type_link = get_post_permalink( $post );

                $total_rooms = isset($_GET['rooms']) && is_numeric($_GET['rooms']) ? (int)_GET['rooms'] : 1;
                $total_adults = isset($_GET['adults']) && is_numeric($_GET['adults']) ? (int)$_GET['adults'] : 1;
                $total_children = isset($_GET['children']) && is_numeric($_GET['children']) ? (int)$_GET['children'] : 0;
                $page_prebook_id = get_option('turisbook-page-prebook',0);
                $page_prebook = get_permalink($page_prebook_id);


                $result = $tbs->getRoomsAvailabilities($hotel_id,$checkin,$checkout,$total_adults, $total_rooms);

                $rooms = isset($result['rooms']) ? $result['rooms'] : [];
                $ndays = isset($result['ndays']) ? $result['ndays'] : 0;
                $count_rooms = count($rooms);


                ?>
                <div class="col-12 col-md-<?php echo $count_rooms > 0 ? "8" : "12"; ?>" style="min-height:400px;">
                    <?php
                    if($count_rooms>0){
                        $check_rooms_count = true;
                        foreach($rooms as $room){
                            if(sizeof($room->active_ratecategories)>0){


                                $proom = $tbs->getPostByMeta(['meta_key' => 'turisbook_room_id', 'meta_value' => $room->id,'post_type'=>'turisbook_room']);
                                $unit_id = $proom->ID;

                                if($tbs->PolylangIsActive() && Turisbook_Booking_System::getLanguage() != Turisbook_Booking_System::calcLanguage(pll_get_post_language($unit_id))) continue;

                                $post_title = get_the_title($unit_id);
                                $post_link = get_permalink($unit_id).'?checkin='.$checkin.'&checkout='.$checkout;
                                $slider_string = get_post_meta($unit_id,'turisbook_room_gallery',true);
                                $slider_ids = explode(',', $slider_string);
                                $withrates = true;

                                $sleeps = (int)$room->lotation_base + (int)$room->extrabed_max;

                                include(TURISBOOK_PUBLIC_PATH . '/partials/archive-turisbook_room_single.php');
                                $ratecategories = $room->active_ratecategories;
                                include(TURISBOOK_PUBLIC_PATH . '/partials/turisbook-booking-system-ratecategories.php');
                            }
                        }
                    } else {
                        ?>
                        <div class="text-center" style="padding:20xp; background-color:#a7a7a7; border-radius:5px; border:1px solid gray; margin-bottom:20px;">
                            <?php echo __('There are no results for the chosen dates.','turisbook-booking-system'); ?>
                            <br/>
                            <?php echo __('Please choose new dates.','turisbook-booking-system'); ?>
                        </div>
                        <div id="calendar_container" class="search-calendar" checkin="<?php echo  $checkin; ?>" checkout="<?php echo  $checkout; ?>" search-link="<?php echo $post_type_link; ?>"></div>
                        <div id="booking-content" class="hidden bootstrap-iso"></div>
                        <input type="hidden" class="unit_id" value="0"/>
                        <input type="hidden" class="hid" value=<?php echo $hotel_id; ?>>
                        <?php
                    }
                    ?>
                </div>
                <?php if($check_rooms_count){
                    $cart_position = get_option('turisbook-cart-position',-60);
                    $cart_background = get_option('turisbook-cart-position',"#a7a7a7");
                    ?>
                    <div class="col-12 col-md-4">
                        <?php echo do_shortcode('[turisbook_resume_block  offset="'.$cart_position.'" backgroundcolor="'.$cart_background.'" ]'); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>