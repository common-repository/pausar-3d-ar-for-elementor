<?php

namespace PausAR_Elementor;

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly.
}
final class PausAR_Plugin {
    const MINIMUM_ELEMENTOR_VERSION = '3.12.0';

    private static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        if ( $this->is_compatible() ) {
            add_action( 'elementor/init', [$this, 'init'] );
        }
    }

    public function is_compatible() {
        if ( class_exists( '\\PausAR_Elementor\\PausAR_Plugin_VersionChecker' ) ) {
            if ( \PausAR_Elementor\PausAR_Plugin_VersionChecker::checkElementorVersion( true ) !== true ) {
                return false;
            }
        } else {
            return false;
        }
        return true;
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have Elementor installed or activated.
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_missing_main_plugin() {
        if ( isset( $_GET['activate'] ) ) {
            unset($_GET['activate']);
        }
        ?>
			<div class="notice notice-warning">
				<p>
                    <?php 
        echo sprintf( 
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '%1$s requires %2$s to be installed and activated.', 'pausar-3d-ar-for-elementor' ),
            '<strong>' . esc_html__( 'PausAR', 'pausar-3d-ar-for-elementor' ) . '</strong>',
            '<strong><a href="' . filter_var( 'https://wordpress.org/plugins/elementor/', FILTER_SANITIZE_URL ) . '" target="_blank">' . esc_html__( 'Elementor', 'pausar-3d-ar-for-elementor' ) . '</a></strong>'
         );
        ?>
                </p>
			</div>

		<?php 
    }

    public function init() {
        add_action( 'elementor/editor/before_enqueue_scripts', [$this, 'initEditorStyles'] );
        add_action( 'elementor/elements/categories_registered', [$this, 'addWidgetCategories'] );
        add_action( 'elementor/frontend/after_enqueue_styles', [$this, 'initStyles'] );
        add_action( 'elementor/frontend/after_enqueue_scripts', [$this, 'initLibraries'] );
        add_action( 'elementor/widgets/register', [$this, 'register_widgets'] );
    }

    public function initLibraries() {
        if ( !wp_script_is( 'pausar-frontendScript', 'registered' ) ) {
            wp_register_script(
                "pausar-frontendScript",
                plugins_url( "assets/js/pausar.js", PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ),
                null,
                get_plugin_data( PAUSAR_FOR_ELEMENTOR_BOOT__FILE__, false, false )['Version'],
                false
            );
        }
        if ( !wp_script_is( 'pausar-modelviewer-script', 'registered' ) ) {
            //https://github.com/google/model-viewer/tree/master/packages/model-viewer
            wp_register_script(
                "pausar-modelviewer-script",
                plugins_url( "lib/model-viewer.min.js", PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ),
                null,
                get_plugin_data( PAUSAR_FOR_ELEMENTOR_BOOT__FILE__, false, false )['Version'],
                false
            );
        }
        //Helpers
        if ( !wp_script_is( 'pausar-loadingHandler-script', 'registered' ) ) {
            wp_register_script(
                'pausar-loadingHandler-script',
                plugins_url( "assets/js/helper/loaderHelper.js", PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ),
                null,
                get_plugin_data( PAUSAR_FOR_ELEMENTOR_BOOT__FILE__, false, false )['Version'],
                false
            );
        }
        //Widget Handler
        if ( !wp_script_is( 'pausar-elementor-widgetHandler', 'registered' ) ) {
            wp_register_script(
                "pausar-elementor-widgetHandler",
                plugins_url( "elementor/pausar-elementor-Handler.js", PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ),
                ['elementor-frontend', 'jquery'],
                '1.0.0',
                true
            );
        }
        add_filter(
            'wp_script_attributes',
            [$this, 'addTypeModule'],
            10,
            1
        );
    }

    public function addTypeModule( $attributes ) {
        $whitelist = ['pausar-frontendScript', 'pausar-modelviewer-script', 'pausar-loadingHandler-script'];
        if ( isset( $attributes['id'] ) ) {
            //The filter may add the script-type to the id ('-js')
            foreach ( $whitelist as $scriptHandle ) {
                //error_log(print_r($scriptHandle, true));
                if ( gettype( $scriptHandle ) === 'string' ) {
                    if ( $attributes['id'] === $scriptHandle || $attributes['id'] === $scriptHandle . "-js" ) {
                        $attributes['type'] = 'module';
                        break;
                    }
                }
            }
        }
        return $attributes;
    }

    public function initStyles() {
        if ( !wp_style_is( 'pausar-elementor-widget-style', 'registered' ) ) {
            wp_register_style(
                'pausar-elementor-widget-style',
                plugins_url( "elementor/pausar-elementor-widget.css", PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ),
                null,
                get_plugin_data( PAUSAR_FOR_ELEMENTOR_BOOT__FILE__, false, false )['Version'],
                'all'
            );
        }
        if ( !wp_style_is( 'pausar-elementor-base-style', 'registered' ) ) {
            wp_register_style(
                'pausar-elementor-base-style',
                plugins_url( "assets/css/pausar-base.css", PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ),
                null,
                get_plugin_data( PAUSAR_FOR_ELEMENTOR_BOOT__FILE__, false, false )['Version'],
                'all'
            );
        }
    }

    public function register_widgets( $widgets_manager ) {
        require_once plugin_dir_path( PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ) . "/elementor/pausar_viewer_default/widget-default.php";
        $widgets_manager->register( new PausAR_Widget_Default() );
    }

    //-----
    public function addWidgetCategories( $elements_manager ) {
        $elements_manager->add_category( 'pausar-3d-ar-for-elementor', [
            'title'  => esc_html__( 'PausAR', 'pausar-3d-ar-for-elementor' ),
            'active' => true,
        ] );
    }

    public function initEditorStyles() {
        //Custom Font
        if ( !wp_style_is( 'pausar-icon-font-style', 'registered' ) ) {
            wp_register_style(
                'pausar-icon-font-style',
                plugins_url( "assets/css/pausar-icon-font.css", PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ),
                null,
                get_plugin_data( PAUSAR_FOR_ELEMENTOR_BOOT__FILE__, false, false )['Version'],
                'all'
            );
        }
        if ( !wp_style_is( 'pausar-icon-font-style', 'enqueued' ) ) {
            wp_enqueue_style( 'pausar-icon-font-style' );
        }
        //Elementor Menu
        if ( !wp_style_is( 'pausar-elementor-editor-icons-style', 'registered' ) ) {
            wp_register_style(
                'pausar-elementor-editor-icons-style',
                plugins_url( "elementor/pausar-elementor-editor.css", PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ),
                null,
                get_plugin_data( PAUSAR_FOR_ELEMENTOR_BOOT__FILE__, false, false )['Version'],
                'all'
            );
        }
        if ( !wp_style_is( 'pausar-elementor-editor-icons-style', 'enqueued' ) ) {
            wp_enqueue_style( 'pausar-elementor-editor-icons-style' );
        }
    }

}

//\PausAR_Elementor\PausAR_Plugin::instance();