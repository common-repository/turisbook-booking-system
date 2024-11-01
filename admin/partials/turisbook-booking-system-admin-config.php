<form id="turisbook-booking-system-config-form">
    <table class="form-table">
        <tbody>
            <tr>
                <th><?php esc_attr_e( 'Pre-Book page', 'turisbook-booking-system' ); ?></th>
                <td>
                    <select name="turisbook-page-prebook">
                        <option value="0" <?php echo 0 == $page_prebook ? 'selected="selected"':'' ?>>== Seleccionar ==</option>
                        <?php
                        foreach( $posts as $post ) : setup_postdata($post); ?>
                            <option value="<?php echo $post->ID; ?>" <?php echo $post->ID == $page_prebook ? 'selected="selected"':'' ?> ><?php the_title(); ?></option>
                            <?php
                        endforeach;
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?php esc_attr_e( 'Confirmation page', 'turisbook-booking-system' ); ?></th>
                <td>
                    <select name="turisbook-page-confirmation">
                        <option value="0" <?php echo 0 == $page_confirmation ? 'selected="selected"':'' ?>>== Seleccionar ==</option>
                        <?php
                        foreach( $posts as $post ) : setup_postdata($post); ?>
                            <option value="<?php echo $post->ID; ?>" <?php echo $post->ID == $page_confirmation ? 'selected="selected"':'' ?>><?php the_title(); ?></option>
                            <?php
                        endforeach;
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?php esc_attr_e( 'Availability Search page', 'turisbook-booking-system' ); ?></th>
                <td>
                    <select name="turisbook-page-availability-search">
                        <option value="0" <?php echo 0 == $page_availability_search ? 'selected="selected"':'' ?>>== Seleccionar ==</option>
                        <?php
                        foreach( $posts as $post ) : setup_postdata($post); ?>
                            <option value="<?php echo $post->ID; ?>" <?php echo $post->ID == $page_availability_search ? 'selected="selected"':'' ?>><?php the_title(); ?></option>
                            <?php
                        endforeach;
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?php esc_attr_e( 'Menu Apt', 'turisbook-booking-system' ); ?></th>
                <td>
                    <select name="turisbook-show-menu-apt">
                        <option value="0" <?php echo $show_menu_apt == 0 ? 'selected="selected"' : ""; ?>><?php esc_attr_e( 'Hide', 'turisbook-booking-system' ); ?></option>
                        <option value="1" <?php echo $show_menu_apt == 1 ? 'selected="selected"' : ""; ?>><?php esc_attr_e( 'Show', 'turisbook-booking-system' ); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?php esc_attr_e( 'Cart Position', 'turisbook-booking-system' ); ?> (px)</th>
                <td>
                    <input type="number" name="turisbook-cart-position" value="<?php echo $cart_position;?>" />
                </td>
            </tr>
            <tr>
                <th><?php esc_attr_e( 'Cart background color', 'turisbook-booking-system' ); ?></th>
                <td>
                    <input class="tbs-color-picker" type="color" value="<?php echo $cart_background;?>" />
                    <input class="tbs-color-text" type="text" name="turisbook-cart-background" last_value="<?php echo $cart_background;?>" value="<?php echo $cart_background;?>" />
                </td>
            </tr>
            <tr>
                <th><?php esc_attr_e( 'Use Analytics Goal for booking?', 'turisbook-booking-system' ); ?></th>
                <td>
                    <select name="turisbook-analytics-booking-goal-enabled">
                        <option value="0" <?php echo $analytics_booking_goal_enabled == 0 ? ' selected="selected" ' : '';?>><?php esc_attr_e( 'No', 'turisbook-booking-system' ); ?></option>
                        <option value="1" <?php echo $analytics_booking_goal_enabled == 1 ? ' selected="selected" ' : '';?>><?php esc_attr_e( 'Yes', 'turisbook-booking-system' ); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?php esc_attr_e( 'Sync data from Turisbook.com', 'turisbook-booking-system' ); ?></th>
                <td><button class="button-primary turisbook-sync-data"><?php esc_attr_e( 'Sync', 'turisbook-booking-system' ); ?></button></td>
            </tr>
        </tbody>
    </table>
    <button class="button-primary turisbook-booking-system-save-data"><?php esc_attr_e( 'Save', 'turisbook-booking-system' ); ?></button>
</form>