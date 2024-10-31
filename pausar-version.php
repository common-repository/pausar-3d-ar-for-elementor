<?php

namespace PausAR_Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if (!class_exists('\\PausAR_Elementor\\PausAR_Plugin_VersionChecker')) {
    class PausAR_Plugin_VersionChecker {

        const PAUSAR_MIN_WP_VERSION = '5.9';
        const PAUSAR_MIN_PHP_VERSION = '7.0';
        const MINIMUM_ELEMENTOR_VERSION = '3.12';

        public static function checkSystemVersions() {

            if(version_compare(get_bloginfo( 'version' ), self::PAUSAR_MIN_WP_VERSION, '<')) {
                if(self::isCorrectPage()) {
                    add_action('admin_notices', array( __CLASS__, 'wordpressNotice' ) );
                }                
                return false;
            }
            if(version_compare(PHP_VERSION, self::PAUSAR_MIN_PHP_VERSION, '<')) {
                if(self::isCorrectPage()) {
                    add_action('admin_notices', array( __CLASS__, 'phpNotice' ) );
                }
                return false;
            }
            return true;
        }

        public static function checkElementorVersion($showNotice = false) {
            // Check if Elementor is installed and activated
            if ( ! self::checkElementorActivation() ) {
                if(self::isCorrectPage() && $showNotice) {
                    add_action('admin_notices', array( __CLASS__, 'elementorPluginNotice' ) );
                }
                return false;
            }
            if(!defined("ELEMENTOR_VERSION")) {
                if(self::isCorrectPage() && $showNotice) {
                    add_action('admin_notices', array( __CLASS__, 'elementorPluginNotice' ) );
                }
                return false;
            }
            // Check for required Elementor version
            if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
                if(self::isCorrectPage() && $showNotice) {
                    add_action('admin_notices', array( __CLASS__, 'elementorVersionNotice' ) );
                }
                return false;
            }
            return true;
        }

        public static function checkElementorActivation() {
            // Check if Elementor is installed and activated
            if ( ! did_action( 'elementor/loaded' ) ) {
                return false;
            }
            return true;
        }

        public static function getPausARVersion() {
            if(defined('PAUSAR_FOR_ELEMENTOR_BOOT__FILE__')) {
                return get_plugin_data( PAUSAR_FOR_ELEMENTOR_BOOT__FILE__, false, false )['Version'];
            }
            return false;
        }
        public static function getElementorVersion() {
            if(defined('ELEMENTOR__FILE__')) {
                return get_plugin_data( ELEMENTOR__FILE__, false, false )['Version'];
            }
            return false;
        }
        public static function checkPluginUpdateAvailable() {
            
            $updateCheckTransient = "pausar-3d-ar-update-check-transient";
            if(false === ( $transientValue = get_transient( $updateCheckTransient ) )) {
                set_transient( $updateCheckTransient, true, 60 * MINUTE_IN_SECONDS );
                wp_update_plugins();//fetches update list from WP API
            }

            $update_plugins = get_site_transient( 'update_plugins' );//get the local list of available updates
            if ( ! empty( $update_plugins->response ) ) {

                if( isset($update_plugins->response[plugin_basename(PAUSAR_FOR_ELEMENTOR_BOOT__FILE__)])) {
                    if (! version_compare( self::getPausARVersion(), $update_plugins->response[plugin_basename(PAUSAR_FOR_ELEMENTOR_BOOT__FILE__)]->new_version, '>=' ) ) {
                        //return $update_plugins->response[plugin_basename(PAUSAR_FOR_ELEMENTOR_BOOT__FILE__)]->new_version;
                        return true;
                    }
                }
            }
            return false;
        }

        //------------------
        // Notice callbacks
        //------------------

        public static function wordpressNotice() {
            ?>
            <div class="error notice">
                <h3>
                    <?php printf(esc_html__('PausAR is not running because your Wordpress version is out of date', 'pausar-3d-ar-for-elementor')); ?>
                </h3>
                <p>
                    <?php
                    /* translators: %s is replaced with the required version of Wordpress for the plugin (Min-Version) */
                    printf(esc_html__('Update to Wordpress %s or higher', 'pausar-3d-ar-for-elementor'), self::PAUSAR_MIN_WP_VERSION);
                    ?>
                </p>
            </div>
            <?php
        }
    
        public static function phpNotice() {
            ?>
            <div class="error notice">
                <h3>
                    <?php printf(esc_html__('PausAR is not running because PHP is out of date', 'pausar-3d-ar-for-elementor')); ?>
                </h3>
                <p>
                    <?php
                    /* translators: %s is replaced with the required version of PHP for the plugin (Min-Version) */
                    printf(esc_html__('Update to PHP %s or higher', 'pausar-3d-ar-for-elementor'), self::PAUSAR_MIN_PHP_VERSION);
                    ?>
                </p>
            </div>
            <?php
        }

        public static function elementorPluginNotice() {
            if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

            ?>
                <div class="notice notice-warning">
                    <p>
                        <?php
                        echo sprintf(
                            /* translators: 1: Plugin name 2: Elementor */
                            esc_html__( '%1$s requires %2$s to be installed and activated.', 'pausar-3d-ar-for-elementor' ),
                            '<strong>' . esc_html__( 'PausAR', 'pausar-3d-ar-for-elementor' ) . '</strong>',
                            '<strong><a href="'.filter_var( 'https://wordpress.org/plugins/elementor/',  FILTER_SANITIZE_URL ).'" target="_blank">' . esc_html__( 'Elementor', 'pausar-3d-ar-for-elementor' ) . '</a></strong>'
                        );              
                        ?>
                    </p>
                </div>

            <?php
        }

        public static function elementorVersionNotice() {
            if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

            ?>
            
            <div class="notice notice-warning">
                <p>
                    <?php

                    echo sprintf(
                        /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
                        esc_html__( '%1$s requires %2$s version %3$s or greater.', 'pausar-3d-ar-for-elementor' ),
                        '<strong>' . esc_html__( 'PausAR', 'pausar-3d-ar-for-elementor' ) . '</strong>',
                        esc_html__( 'Elementor', 'pausar-3d-ar-for-elementor' ),
                        esc_html(self::MINIMUM_ELEMENTOR_VERSION)
                    );

                    ?>
                </p>
            </div>

            <?php
        }

        protected static function isCorrectPage() {
            global $pagenow;
            if(!is_admin()) {
                return false;
            }
            if(!isset($pagenow)) {
                return false;
            }
            
            if ($pagenow == 'admin.php') {
                if(strpos($_GET['page'], 'pausar-3d-ar') == 0) {
                    return true;
                }
            } else if (
                //$pagenow == 'plugin-install.php' ||
                $pagenow == 'index.php' ||
                $pagenow == 'edit.php' ||
                $pagenow == 'plugins.php' ||
                $pagenow == 'users.php' ||
                $pagenow == 'tools.php' ||
                $pagenow == 'themes.php' ||
                $pagenow == 'edit-tags.php' ||
                $pagenow == 'edit-comments.php' ||
                $pagenow == 'upload.php') {
                return true;
            }
            return false;
        }
        
    }
}