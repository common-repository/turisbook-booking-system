<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$title_color = get_post_meta( $hotel->ID, 'turisbook_color_title', true ); 

?>
<div id="unit_<?php echo $unit_id; ?>" class="row tbs-unit <?php echo isset($withrates) && $withrates ? " with-rates ":"" ?>">
    <div class="col-12 col-md-<?php echo isset($withrates) && $withrates ? "5":"5" ?>">
        <div class="myslider">
            <?php
            foreach($slider_ids as $id){
                $slide = wp_get_attachment_url( $id );
                ?>
                <div data-src="<?php echo esc_attr($slide); ?>"></div>
                <?php
            }
            ?>
        </div>
    </div>
    <div class="col-12 col-md-<?php echo isset($withrates) && $withrates ? "7":"7" ?> tbs-unit-content">
        <a href="<?php echo esc_attr($post_link);?>" style="color:<?php echo $title_color; ?>;"><h3 style="margin-bottom:0px;"><?php echo esc_html( get_the_title($unit_id) ); ?></h3>
        </a>
        <div style="margin-bottom:10px;"><small><i style=""><?php echo $turisbook_room_location_name; ?></i></small></div>

        <small><?php echo __( 'Max lotation', 'turisbook-booking-system' )?>: <i class="fas fa-user"></i> <?php echo $sleeps; ?></small>
        <br/>
        <?php 
        $my_content = get_the_excerpt($unit_id);
        echo wp_trim_words( $my_content, 30, '...' );
    ?><br/>
</div>
<a class="tbs-more-details" href="<?php echo esc_attr($post_link);?>"><small><i class="fas fa-plus-circle"></i> <?php echo esc_attr__( 'More details', 'turisbook-booking-system' )?></small></a>
</div>

<script type="text/javascript">
    jQuery(function($){
        $('.myslider').tbsSlider({height:'<?php echo isset($withrates) && $withrates ? "200":"300" ?>px'});
    });
</script>