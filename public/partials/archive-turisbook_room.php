<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
get_header();

?>
<main class="site-main" role="main">

    <div class="bootstrap-iso">
        <div class="container-fluid">
            <div class="row mt-3">
                <div class="col-12 col-md-12 turisbook_rooms_container">
                    <?php
                    $tbs = new Turisbook_Booking_System();
                    $hotel_id = get_option('turisbook-hotel-id',0);
                    $hotel = $tbs->getEstablishmentById($hotel_id);
                    while ( have_posts() ) {
                        the_post();
                        $unit_id = get_the_ID();
                        if($tbs->PolylangIsActive() && Turisbook_Booking_System::getLanguage() != Turisbook_Booking_System::calcLanguage(pll_get_post_language($unit_id))) continue;
                        $post_link = get_permalink();
                        $slider_string = get_post_meta($unit_id,'turisbook_room_gallery',true);
                        $slider_ids = explode(',', $slider_string);
                        $lotation_base = (int)get_turisbook_room_metabox('turisbook_room_lotation_base',$unit_id);
                        $extrabed_max = (int)get_turisbook_room_metabox('turisbook_room_extrabed_max',$unit_id);
                        $extrabed_max = (int)get_turisbook_room_metabox('turisbook_room_extrabed_max',$unit_id);
                        $turisbook_room_location_name = get_turisbook_room_metabox('turisbook_room_location_name',$unit_id);

                        $sleeps = (int)get_turisbook_room_metabox('turisbook_room_sleeps',$unit_id);

                        include(TURISBOOK_PUBLIC_PATH . '/partials/archive-turisbook_room_single.php');
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>