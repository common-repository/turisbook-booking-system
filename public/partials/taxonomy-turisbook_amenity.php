<?php
get_header();

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>
<main class="site-main" role="main">
    <div class="bootstrap-iso">
        <div class="container-fluid">
            <div class="row mt-3">
                <?php

                $per_row = 2;

                $size_per_row = 12 / $per_row;

                $tbs = new Turisbook_Booking_System();
                $term_id = get_queried_object()->term_id;
                $args = [
                  'post_type'  => 'turisbook_room',
                  'numberposts'      => -1,
                  'tax_query' => array(
                    array(
                        'taxonomy' => 'turisbook_amenity',
                        'field' => 'term_id',
                        'terms' => $term_id,
                    ),
                ),
              ];

              $terms = get_terms( 'turisbook_amenity', array(
                'hide_empty' => true,

            ) );

              $establishment_type =   get_option('turisbook-hotel-type');

              if($tbs->PolylangIsActive()){ $args['lang'] = pll_current_language('slug'); }


              $result = get_posts( $args );
              $units = [];
              if($tbs->PolylangIsActive()){
                foreach($result as $unit){
                    if(Turisbook_Booking_System::getLanguage() == pll_get_post_language($unit->ID)){
                        $units[] = $unit; 
                    }
                }
            }
            else{
                $units = $result;
            }
            ?>
            <div class="col-md-3">
                <h4><?php echo __('Amenities','turisbook-booking-system');?></h4>
                <ul style="list-style-type: none;" class="tbs-amenities-list">
                    <?php
                    foreach($terms as $term){
                        if(pll_get_term_language($term->term_id) == Turisbook_Booking_System::getLanguage()){
                            $alink = get_term_link($term->term_id);
                            $selected = $term_id == $term->term_id ? " selected " : "";
                            ?>
                            <li class="<?php echo $selected ;?>"><a href="<?php echo $alink?>"><i class="fas fa-chevron-right"></i> <?php echo $term->name; ?></a></li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
            <div class="col-md-9">
                <?php
                include TURISBOOK_PUBLIC_PATH . '/partials/turisbook-booking-system-grid.php';
                ?>
            </div>
        </div>
    </div>
</div>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>