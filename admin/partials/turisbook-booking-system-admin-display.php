<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.neteuro.pt/
 * @since      1.0.0
 *
 * @package    Turisbook_Booking_System
 * @subpackage Turisbook_Booking_System/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->




<div class="wrap" style="position:relative;">
    <!-- <div class="" style="position:fixed; width:100%; height: 100%; top:0; left: 0; z-index:100000; background-color: rgba(0,0,0,.8);"></div> -->
    <div id="icon-options-general" class="icon32"></div>
    <h1><?php esc_attr_e( 'Turisbook Booking System', 'turisbook-booking-system' ); ?></h1>

    <div id="poststuff">

        <div id="post-body" class="metabox-holder columns-1">
            <div class="meta-box-sortables ui-sortable wptbtabs">
                <h2 class="nav-tab-wrapper">
                    <a href="#Turisbook_bs_config" class="nav-tab nav-tab-active"><?php esc_attr_e( 'Configurations', 'turisbook-booking-system' ); ?></a>
                    <a href="#Turisbook_bs_account" class="nav-tab"><?php esc_attr_e( 'Connection', 'turisbook-booking-system' ); ?></a>
                </h2>

                <div id="post-body-content">
                    <div id="Turisbook_bs_config" class="wptbtab active">
                        <br/>
                        <?php include_once(plugin_dir_path( __FILE__ ) . 'turisbook-booking-system-admin-config.php'); ?>
                    </div>
                    <div id="Turisbook_bs_account" class="wptbtab">
                        <?php
                        $establishments = [];
                        $file = "turisbook-booking-system-admin-login";
                        if($hotel_id > 0) $file = "turisbook-booking-system-admin-loggedin";
                        else if(trim($token) != ""){
                            $tbs = new Turisbook_Booking_System();
                            $all = defined('TURISBOOK_ADMIN_VERSION');
                            $result = $tbs->getEstablishments($all);
                            $establishments = $result['establishments'];
                            $file = "turisbook-booking-system-admin-select-establishment";

                        }
                        include_once(plugin_dir_path( __FILE__ ) . $file.'.php');
                        ?>
                    </div>


                </div>
            </div>
        </div>
        <br class="clear">
    </div>
</div>