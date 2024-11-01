<div class="myslider">
    <?php foreach($slider as $slide){
        ?>
        <div data-src="<?php echo esc_attr($slide); ?>" data-background-position="<?php echo esc_attr($a["background_position_x"]); ?> <?php echo esc_attr($a["background_position_y"]); ?>"></div>
        <?php
    }
    ?>
</div>

<script type="text/javascript">
    jQuery(function($){
        $('.myslider').tbsSlider(
        {
            "height" : 'calc(<?php echo esc_attr($a["height"]); ?> - 100px)'
        }
        );
    });
</script>