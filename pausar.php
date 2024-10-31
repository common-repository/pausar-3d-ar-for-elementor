<?php

/*
Plugin Name: PausAR - 3D and AR for Elementor
Description: PausAR is a user-friendly and web-based 3D & augmented reality viewer that can be easily integrated into any Elementor page.
Version: 1.3.1
Author: PausAR Studio
Author URI: https://www.pausarstudio.de/
Plugin URI: https://www.pausarstudio.de/wordpress-elementor/
License: GPLv2
Text Domain: pausar-3d-ar-for-elementor

Elementor tested up to: 3.23.4
Elementor Pro tested up to: 3.23.3
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly.
}
if ( function_exists( 'pausar_elementor_pe_fs' ) ) {
    pausar_elementor_pe_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    if ( !function_exists( 'pausar_elementor_pe_fs' ) ) {
        // Create a helper function for easy SDK access.
        function pausar_elementor_pe_fs() {
            global $pausar_elementor_pe_fs;
            if ( !isset( $pausar_elementor_pe_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $pausar_elementor_pe_fs = fs_dynamic_init( array(
                    'id'             => '14304',
                    'slug'           => 'pausar-3d-ar-for-elementor',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_40b61a6893e7e90a5e91ac8bf7845',
                    'is_premium'     => false,
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                        'days'               => 30,
                        'is_require_payment' => true,
                    ),
                    'menu'           => array(
                        'slug'       => 'pausar-3d-ar',
                        'first-path' => 'admin.php?page=pausar-3d-ar-getting-started',
                        'contact'    => false,
                    ),
                    'is_live'        => true,
                ) );
            }
            return $pausar_elementor_pe_fs;
        }

        // Init Freemius.
        pausar_elementor_pe_fs();
        // Signal that SDK was initiated.
        do_action( 'pausar_elementor_pe_fs_loaded' );
        //Custom Opt-In's
        if ( !function_exists( 'pausar_elementor_pe_fs_custom_connect_message_on_update' ) ) {
            //Returning users
            //%1$s User
            //%2$s Plugin Name
            //%5$s Freemius Link
            function pausar_elementor_pe_fs_custom_connect_message_on_update(
                $message,
                $user_first_name,
                $plugin_title,
                $user_login,
                $site_link,
                $freemius_link
            ) {
                return sprintf(
                    /* translators: %1$s is replaced with the Admin-Name (Wordpress) */
                    __( 'Hey %1$s', 'pausar-3d-ar-for-elementor' ) . ',<br>' . __( 'Please help us improve %2$s! If you opt-in, some data about your usage of %2$s will be sent to %5$s. If you skip this, that\'s okay! %2$s will still work just fine.', 'pausar-3d-ar-for-elementor' ),
                    $user_first_name,
                    '<b>' . $plugin_title . '</b>',
                    '<b>' . $user_login . '</b>',
                    $site_link,
                    $freemius_link
                );
            }

        }
        if ( !function_exists( 'pausar_elementor_pe_fs_custom_connect_message' ) ) {
            //New users
            function pausar_elementor_pe_fs_custom_connect_message(
                $message,
                $user_first_name,
                $plugin_title,
                $user_login,
                $site_link,
                $freemius_link
            ) {
                return sprintf(
                    /* translators: %1$s is replaced with the Admin-Name (Wordpress) */
                    __( 'Hey %1$s', 'pausar-3d-ar-for-elementor' ) . ',<br>' . __( 'Please help us improve %2$s! If you opt-in, some data about your usage of %2$s will be sent to %5$s. If you skip this, that\'s okay! %2$s will still work just fine.', 'pausar-3d-ar-for-elementor' ),
                    $user_first_name,
                    '<b>' . $plugin_title . '</b>',
                    '<b>' . $user_login . '</b>',
                    $site_link,
                    $freemius_link
                );
            }

        }
        pausar_elementor_pe_fs()->add_filter(
            'connect_message_on_update',
            'pausar_elementor_pe_fs_custom_connect_message_on_update',
            10,
            6
        );
        pausar_elementor_pe_fs()->add_filter(
            'connect_message',
            'pausar_elementor_pe_fs_custom_connect_message',
            10,
            6
        );
    }
    if ( !defined( 'PAUSAR_FOR_ELEMENTOR_BOOT__FILE__' ) ) {
        define( 'PAUSAR_FOR_ELEMENTOR_BOOT__FILE__', __FILE__ );
    }
    //Textdomain
    if ( !function_exists( 'pausar_for_elementor_load_textdomain' ) ) {
        function pausar_for_elementor_load_textdomain() {
            load_plugin_textdomain( 'pausar-3d-ar-for-elementor', false, dirname( plugin_basename( PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ) ) . '/languages/' );
        }

        add_action( 'plugins_loaded', 'pausar_for_elementor_load_textdomain' );
    }
    if ( !class_exists( '\\PausAR_Elementor\\PausAR_Plugin_VersionChecker' ) ) {
        require_once plugin_dir_path( PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ) . "/pausar-version.php";
        if ( \PausAR_Elementor\PausAR_Plugin_VersionChecker::checkSystemVersions() === true ) {
            require_once plugin_dir_path( PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ) . "/pausar-main.php";
        }
    }
}