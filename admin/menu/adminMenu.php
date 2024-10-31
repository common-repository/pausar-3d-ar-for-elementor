<?php

namespace PausAR_Elementor;

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly.
}
if ( !class_exists( '\\PausAR_Elementor\\pausar_for_elementor_menu' ) ) {
    class pausar_for_elementor_menu {
        protected static $instance = null;

        protected static $menuSlug = 'pausar-3d-ar';

        protected static $blockNoticesOnSlugs = ["pausar-3d-ar", "pausar-3d-ar-getting-started"];

        protected static $hideSubmenuSlugs = [];

        // e.g.: ["pausar-3d-ar-getting-started"];
        //------------------------
        //Instance and Constructor
        //------------------------
        public static function get_instance() {
            if ( !is_a( self::$instance, '\\PausAR_Elementor\\pausar_for_elementor_menu' ) ) {
                self::$instance = new pausar_for_elementor_menu();
            }
            return self::$instance;
        }

        private function __construct() {
            \add_action( 'admin_print_scripts', [$this, 'init_admin_inline_script'] );
            //alternatives: 'admin_print_footer_scripts' or 'admin_head'
            \add_action( 'admin_enqueue_scripts', [$this, 'initScripts'] );
            \add_action( 'admin_menu', [$this, 'register_pausar_admin_menu'] );
            \add_filter( 'admin_footer_text', [$this, 'change_admin_footer_text'] );
            \add_action( 'in_admin_header', [$this, 'block_notices'], \PHP_INT_MAX );
            \add_action( 'admin_head', [$this, 'hide_submenus'] );
        }

        public function initScripts() {
            //Custom Font
            if ( !wp_style_is( 'pausar-icon-font-style', 'registered' ) ) {
                wp_register_style(
                    'pausar-icon-font-style',
                    plugins_url( "/assets/css/pausar-icon-font.css", PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ),
                    null,
                    get_plugin_data( PAUSAR_FOR_ELEMENTOR_BOOT__FILE__, false, false )['Version'],
                    'all'
                );
            }
            if ( !wp_style_is( 'pausar-icon-font-style', 'enqueued' ) ) {
                wp_enqueue_style( 'pausar-icon-font-style' );
            }
            //Style
            if ( !wp_style_is( 'pausar-admin-menu-style', 'registered' ) ) {
                wp_register_style(
                    'pausar-admin-menu-style',
                    plugins_url( "/admin/menu/adminMenu.css", PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ),
                    null,
                    get_plugin_data( PAUSAR_FOR_ELEMENTOR_BOOT__FILE__, false, false )['Version'],
                    'all'
                );
            }
            if ( !wp_style_is( 'pausar-admin-menu-style', 'enqueued' ) ) {
                wp_enqueue_style( 'pausar-admin-menu-style' );
                //wp_style_add_data( 'pausar-admin-menu-style', 'rtl', 'replace' );//adminMenu-rtl.css
            }
        }

        public function change_admin_footer_text( $footer_text ) {
            $current_screen = get_current_screen();
            $is_pausar_screen = $current_screen && false !== strpos( $current_screen->id, self::$menuSlug );
            if ( $is_pausar_screen ) {
                $footer_text = sprintf( 
                    /* translators: 1: PausAR tag, 2: link to review */
                    esc_html__( 'If you like %1$s, feel free to give us a %2$s rating.', 'pausar-3d-ar-for-elementor' ),
                    '<strong>' . esc_html__( 'PausAR', 'pausar-3d-ar-for-elementor' ) . '</strong>',
                    '<a href="https://wordpress.org/support/plugin/pausar-3d-ar-for-elementor/reviews/" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
                 );
            }
            return $footer_text;
        }

        public function block_notices() {
            if ( sizeof( self::$blockNoticesOnSlugs ) <= 0 ) {
                //If no slug is specified, nothing will be bocked
                return;
            }
            $current_screen = get_current_screen();
            if ( $current_screen ) {
                for ($i = 0; $i < sizeof( self::$blockNoticesOnSlugs ); $i++) {
                    if ( strpos( $current_screen->id, self::$blockNoticesOnSlugs[$i] ) == strlen( $current_screen->id ) - strlen( self::$blockNoticesOnSlugs[$i] ) ) {
                        \remove_all_actions( 'admin_notices' );
                        \remove_all_actions( 'user_admin_notices' );
                        \remove_all_actions( 'network_admin_notices' );
                        \remove_all_actions( 'all_admin_notices' );
                    }
                }
            }
        }

        public function hide_submenus() {
            if ( sizeof( self::$hideSubmenuSlugs ) <= 0 ) {
                return;
            }
            for ($i = 0; $i < sizeof( self::$hideSubmenuSlugs ); $i++) {
                remove_submenu_page( self::$menuSlug, self::$hideSubmenuSlugs[$i] );
            }
        }

        public function register_pausar_admin_menu() {
            if ( !current_user_can( 'manage_options' ) ) {
                return;
            }
            global $admin_page_hooks;
            global $submenu;
            //-------------
            add_menu_page(
                esc_html__( 'General', 'pausar-3d-ar-for-elementor' ),
                //page_title
                esc_html__( 'PausAR', 'pausar-3d-ar-for-elementor' ),
                //menu_title
                'manage_options',
                //capability
                self::$menuSlug,
                //menu_slug //changes the css class (#toplevel_page_ + "menu_slug")
                [$this, 'render_home_page'],
                //callback > render html
                '',
                //icon_url
                60
            );
            // Remove notification counter from hook and CSS class (only for submenus)
            $admin_page_hooks[self::$menuSlug] = "pausar";
            //overwrites the $current_screen->id attribute with a static string. Useful for fixed CSS class names in submenus
            //Getting started
            add_submenu_page(
                self::$menuSlug,
                //parent_slug,
                esc_html__( 'Getting Started', 'pausar-3d-ar-for-elementor' ),
                //page_title,
                esc_html__( 'Getting Started', 'pausar-3d-ar-for-elementor' ),
                //menu_title,
                'manage_options',
                //capability,
                self::$menuSlug . '-getting-started',
                //menu_slug,
                [$this, 'render_gettingStarted_page']
            );
            //-------------
            if ( isset( $submenu[self::$menuSlug] ) ) {
                //Add External links
                $submenu[self::$menuSlug][] = array(esc_html__( 'Documentation', 'pausar-3d-ar-for-elementor' ), 'manage_options', 'https://www.pausarstudio.de/wordpress-elementor/documentation/');
                $submenu[self::$menuSlug][] = array(esc_html__( 'Change Log', 'pausar-3d-ar-for-elementor' ), 'manage_options', 'https://www.pausarstudio.de/wordpress-elementor/changelog/');
                $submenu[self::$menuSlug][] = array(esc_html__( 'Contact us', 'pausar-3d-ar-for-elementor' ), 'manage_options', 'https://www.pausarstudio.de/support/');
                //Change/Overwrite name of the top level menu
                $submenu[self::$menuSlug][0][0] = esc_html__( 'General', 'pausar-3d-ar-for-elementor' );
            }
        }

        //===========================================
        // Render Functions
        //===========================================
        public function render_home_page() {
            //include_once plugins_url( "/admin/menu/home.php", PAUSAR_FOR_ELEMENTOR_BOOT__FILE__);
            if ( !current_user_can( 'manage_options' ) ) {
                wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
            }
            //---
            $currentTab = ( !empty( $_GET['tab'] ) ? esc_attr( $_GET['tab'] ) : 'dashboard' );
            ?>

            <div class="wrap pausar-WPAdminMenu-homescreen-body">
                <div class="pausar-WPAdminMenu-title-row">
                    <!--notifications-->
                    <div class="wp-header-end"></div>
                    <!---->
                    <div class="pausar-WPAdminMenu-title-warpper">
                        <div class="pausar-WPAdminMenu-title-logo"></div>
                        <h1><?php 
            echo esc_html__( 'PausAR Dashboard', 'pausar-3d-ar-for-elementor' );
            ?>
                    </div>
                </div>

                <?php 
            $this->render_home_page_tabs( $currentTab );
            if ( $currentTab == 'dashboard' ) {
                ?>               
                
                <div class="pausar-WPAdminMenu-dashboard-mainSection"><!--Main Section-->
                    <div class="pausar-WPAdminMenu-dashboard-mainSection-content">
                        
                        <ul class="pausar-WPAdminMenu-dashboard-row">
                            <li id="pausar-WPID-welcomeItem">
                                <strong class="pausar-WPAdminMenu-dashboard-header"><?php 
                echo esc_html__( 'Get to know PausAR', 'pausar-3d-ar-for-elementor' );
                ?></strong>
                                <div class="pausar-flexbox">
                                    <div>
                                        <div class="pausar-textbox">
                                            <h2><?php 
                echo esc_html__( 'Not sure where to start?', 'pausar-3d-ar-for-elementor' );
                ?></h2>
                                            <p><?php 
                echo esc_html__( 'If you are not yet familiar with PausAR and the creation of web-based AR content, you can take a look at our guide for beginners.', 'pausar-3d-ar-for-elementor' );
                ?></p>
                                            <p><?php 
                echo esc_html__( 'You can also view and read important basic knowledge about PausAR, the most important basic settings and the required 3D models in our documentation.', 'pausar-3d-ar-for-elementor' );
                ?></p>
                                            <div class="pausar-buttonbar">
                                                <a class="pausar-button" href="<?php 
                echo esc_html( get_admin_url() . "admin.php?page=" . self::$menuSlug . "-getting-started" );
                ?>"><?php 
                echo esc_html__( 'Getting Started', 'pausar-3d-ar-for-elementor' );
                ?></a>
                                                <a class="pausar-button pausar-button-secondary" href="https://www.pausarstudio.de/wordpress-elementor/documentation/" target="_blank" rel="noopener"><?php 
                echo esc_html__( 'View Documentation', 'pausar-3d-ar-for-elementor' );
                ?></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pausar-contentbox">
                                            <div class="pausar-contentbox-wrapper">
                                                <video with="100%" height="auto" controls autoplay muted loop controlsList="nodownload">
                                                    <source src="<?php 
                echo esc_html( plugins_url( "/admin/menu/assets/pausar_elementor_demo.mp4", PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ) );
                ?>" type="video/mp4">
                                                </video>
                                            </div>
                                        </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <?php 
                if ( pausar_elementor_pe_fs()->is_not_paying() ) {
                    ?>
                    <div class="pausar-WPAdminMenu-dashboard-mainSection-sidebar">
                        <ul class="pausar-WPAdminMenu-dashboard-row">
                            <li id="pausar-WPID-upgradeItem">
                                <strong class="pausar-WPAdminMenu-dashboard-header pausar-WPAdminMenu-dashboard-fullwidth">
                                    <?php 
                    echo esc_html__( 'Upgrade to Premium?', 'pausar-3d-ar-for-elementor' );
                    ?>
                                </strong>
                                <p><?php 
                    echo esc_html__( 'Would you like more features and styling options for your AR scenes? Then check out our Pro features and update to one of our premium versions of PausAR.', 'pausar-3d-ar-for-elementor' );
                    ?></p>
                                <a href="https://www.pausarstudio.de/wordpress-elementor/#pricing" target="_blank" rel="noopener"><?php 
                    echo esc_html__( 'Explore PausAR Premium', 'pausar-3d-ar-for-elementor' );
                    ?></a>
                            </li>
                        </ul>
                    </div>
                    <?php 
                }
                ?>
                </div><!--End: Main Section-->
                <?php 
            }
            ?>
            </div>
            
            <?php 
        }

        private function render_home_page_tabs( $current = 'dashboard' ) {
            $tabs = array(
                'dashboard' => __( 'Dashboard', 'pausar-3d-ar-for-elementor' ),
            );
            $html = '<h2 class="nav-tab-wrapper">';
            foreach ( $tabs as $tab => $name ) {
                $class = ( $tab == $current ? 'nav-tab-active' : '' );
                $html .= '<a class="nav-tab ' . $class . '" href="?page=' . self::$menuSlug . '&tab=' . $tab . '">' . $name . '</a>';
            }
            $html .= '</h2>';
            if ( sizeof( $tabs ) > 1 ) {
                echo $html;
            }
        }

        //---
        public function render_gettingStarted_page() {
            if ( !current_user_can( 'manage_options' ) ) {
                wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
            }
            ?>
            
            <div class="pausar-WPAdminMenu-getting-started-body">
                <div class="pausar-WPAdminMenu-waveContainer"></div>
                
                <div class="pausar-WPAdminMenu-getting-started-main">
                    <div class="pausar-WPAdminMenu-getting-started-logo-header">
                        <div class="pausar-WPAdminMenu-getting-started-logo-wrapper">
                            <img src="<?php 
            echo esc_html( plugins_url( "/admin/menu/assets/pausar_logo_border.svg", PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ) );
            ?>">
                        </div>
                        <span><?php 
            echo get_plugin_data( PAUSAR_FOR_ELEMENTOR_BOOT__FILE__, false, false )['Name'];
            ?></span>
                    </div>
                    
                    <div class="pausar-WPAdminMenu-getting-started-window">
                        <h2><?php 
            echo esc_html( get_admin_page_title() );
            ?></h2>
                        <h3><?php 
            echo esc_html__( 'Thank you for installing PausAR.', 'pausar-3d-ar-for-elementor' );
            ?></h3>
                        <h3><?php 
            echo esc_html__( 'Create your own web-based AR scene in just a few steps.', 'pausar-3d-ar-for-elementor' );
            ?></h3>

                        <div class="pausar-WPAdminMenu-spinnerContainer">
                            <div class="pausar-WPAdminMenu-spinnerWrapper">
                                <div class="pausar-WPAdminMenu-spinner"></div>
                            </div>
                        </div>
                        <!--timeline entries-->
                        <ul class="pausar-WPAdminMenu-timeline"></ul>
                        <!---->
                        <div class="pausar-WPAdminMenu-getting-started-tabs">
                            <div class="pausar-WPAdminMenu-getting-started-tabs-container">
                                <!--Tabs-->
                                <div class="pausar-tab" name="<?php 
            echo esc_html__( 'Introduction', 'pausar-3d-ar-for-elementor' );
            ?>">
                                    <div class="pausar-flexbox">
                                        <div>
                                            <div class="pausar-textbox">
                                                <h3><?php 
            echo esc_html__( 'Explanation: What is PausAR?', 'pausar-3d-ar-for-elementor' );
            ?></h3>
                                                <p><?php 
            echo esc_html__( 'PausAR enables the display of AR and 3D content directly in the web browser and on your websites.', 'pausar-3d-ar-for-elementor' );
            ?></p>
                                                <p><?php 
            echo esc_html__( 'AR scenes can be created in just a few steps and hosted directly on your WordPress site, without the need for iframes and external servers.', 'pausar-3d-ar-for-elementor' );
            ?></p>
                                                <p><?php 
            echo esc_html__( 'Our goal is to simplify the creation of AR applications and content in order to make them accessible to a wide range of users.', 'pausar-3d-ar-for-elementor' );
            ?></p>
                                            </div>
                                        </div>
                                        <!--
                                        <div class="pausar-contentbox" id="pausar-WPID-gettingStartedDemoVideo">
                                            <div class="pausar-contentbox-wrapper pausar-video-wrapper">
                                                <div class="pausar-video-wrapper" id="pausar-WPID-gettingStartedDemoVideo">
                                                    <iframe src="" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen=""></iframe>
                                                </div>
                                            </div>
                                        </div>-->
                                        <div class="pausar-contentbox">
                                            <div class="pausar-contentbox-wrapper">
                                                <video with="100%" height="auto" controls autoplay muted loop controlsList="nodownload">
                                                    <source src="<?php 
            echo esc_html( plugins_url( "/admin/menu/assets/pausar_elementor_demo.mp4", PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ) );
            ?>" type="video/mp4">
                                                </video>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pausar-tab" name="<?php 
            echo esc_html__( 'Create a Scene', 'pausar-3d-ar-for-elementor' );
            ?>">
                                    <div class="pausar-flexbox">
                                        <div>
                                            <div class="pausar-textbox">
                                                <h3><?php 
            echo esc_html__( 'Creating a scene in Elementor', 'pausar-3d-ar-for-elementor' );
            ?></h3>
                                                <p><?php 
            echo esc_html__( 'Creating a new AR scene can be done directly in the Elementor editor. PausAR adds a new category and widgets to the Elementor website builder. No settings in the WordPress admin menu are necessary.', 'pausar-3d-ar-for-elementor' );
            ?></p>
                                                <p><?php 
            echo esc_html__( 'A new AR scene can be added to your own website by editing the page with Elementor and creating a new instance of the PausAR Viewer widget. The widget is simply dragged onto the website.', 'pausar-3d-ar-for-elementor' );
            ?></p>
                                                <p><?php 
            echo esc_html__( 'Each PausAR widget contains an AR scene and a 3D preview, which can be further customized in terms of content, appearance and functionality.', 'pausar-3d-ar-for-elementor' );
            ?></p>
                                            </div>
                                        </div>
                                        <div class="pausar-contentbox">
                                            <div class="pausar-contentbox-wrapper">
                                                <img src="<?php 
            echo esc_html( plugins_url( "/admin/menu/assets/pausar_widget_animated.png", PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ) );
            ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pausar-tab" name="<?php 
            echo esc_html__( 'Required Files', 'pausar-3d-ar-for-elementor' );
            ?>">
                                    <div class="pausar-flexbox">
                                        <div>
                                            <div class="pausar-textbox">
                                                <h3><?php 
            echo esc_html__( 'Basic settings of a scene', 'pausar-3d-ar-for-elementor' );
            ?></h3>
                                                <p><?php 
            echo esc_html__( 'Although PausAR offers many setting and customization options, only the 3D models are required as mandatory files to start the AR scene.', 'pausar-3d-ar-for-elementor' );
            ?></p>
                                                <p><?php 
            echo esc_html__( 'Each AR scene always requires two separate files with identical content, even though only one 3D model can be displayed. The reason for this is technical differences and the specific file formats for 3D content used by Android and iOS/iPadOS.', 'pausar-3d-ar-for-elementor' );
            ?></p>
                                                <p>
                                                    <?php 
            echo sprintf( 
                /* translators: 1: Link with open tag, 2: Link close tag. */
                esc_html__( 'All required files for each scene should always be included so that all potential users can view the scene. The file formats used and their creation are described in more detail in the %1$s documentation%2$s.', 'pausar-3d-ar-for-elementor' ),
                '<a href="https://www.pausarstudio.de/wordpress-elementor/documentation/" target="_blank">',
                '</a>'
             );
            ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="pausar-contentbox">
                                            <div class="pausar-contentbox-wrapper">
                                                <img src="<?php 
            echo esc_html( plugins_url( "/admin/menu/assets/pausar_required_media.png", PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ) );
            ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pausar-tab" name="<?php 
            echo esc_html__( 'Additional Settings', 'pausar-3d-ar-for-elementor' );
            ?>">
                                    
                                    <div class="pausar-flexbox">
                                        <div>
                                            <div class="pausar-textbox">
                                                <h3><?php 
            echo esc_html__( 'Individualization and styling', 'pausar-3d-ar-for-elementor' );
            ?></h3>
                                                <p><?php 
            echo esc_html__( 'In addition to the required 3D models, which should always be specified, there are other customization options that help to adapt each AR scene individually to your own website.', 'pausar-3d-ar-for-elementor' );
            ?></p>
                                                <p><?php 
            echo esc_html__( 'Since these settings are optional, you can simply experiment a little and customize the plugin specifically. These settings include visual adjustments to the start button and the preview, but also technical and content adjustments to the AR scene itself.', 'pausar-3d-ar-for-elementor' );
            ?></p>
                                                <?php 
            ?>
                                                    <p><?php 
            echo esc_html__( 'It is worth mentioning that the free version of PausAR does not contain the same range of optional customization functions as the paid versions.', 'pausar-3d-ar-for-elementor' );
            ?></p>
                                                <?php 
            ?>
                                            </div>
                                        </div>
                                        <div class="pausar-contentbox">
                                            <div class="pausar-contentbox-wrapper">
                                                <img src="<?php 
            echo esc_html( plugins_url( "/admin/menu/assets/pausar_gettingstarted_styling.png", PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ) );
            ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pausar-tab" name="<?php 
            echo esc_html__( 'Experience AR', 'pausar-3d-ar-for-elementor' );
            ?>">
                                    <div class="pausar-flexbox">
                                        <div>
                                            <div class="pausar-textbox">
                                                <h3><?php 
            echo esc_html__( 'Try it out', 'pausar-3d-ar-for-elementor' );
            ?></h3>
                                                <p><?php 
            echo esc_html__( 'It does not take much to integrate a simple AR scene into your own website. Even without prior knowledge, you can create your own web-based AR presence in just a few steps, as long as you have the necessary 3D models.', 'pausar-3d-ar-for-elementor' );
            ?></p>
                                                <p><?php 
            echo esc_html__( 'If everything has been configured correctly, users can now start the AR scenes directly via their web browsers using their AR-capable devices (e.g. smartphones). If a device is not supported, users will be informed of this by a pop-up window and an attempt will be made to redirect the user to a capable AR device.', 'pausar-3d-ar-for-elementor' );
            ?></p>
                                            </div>
                                        </div>
                                        <div class="pausar-contentbox">
                                            <div class="pausar-contentbox-wrapper">
                                                <img src="<?php 
            echo esc_html( plugins_url( "/admin/menu/assets/pausar_comparison.png", PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ) );
            ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pausar-tab">
                                    <div class="pausar-flexbox">
                                        <div>
                                            <div class="pausar-textbox">
                                                <h3><?php 
            echo esc_html__( 'Ready to go', 'pausar-3d-ar-for-elementor' );
            ?></h3>
                                                <p><?php 
            echo esc_html__( 'You should now have a sufficient understanding of how PausAR works and be able to create customized AR scenes.', 'pausar-3d-ar-for-elementor' );
            ?></p>
                                                <p>
                                                    <?php 
            echo sprintf(
                /* translators: 1: Documentation link with open tag,, 2: Support link with open tag, 3: Support mailto link with open tag, 4: Link close tag. */
                esc_html__( 'If you want to learn even more about PausAR and its features, check out our %1$s documentation%4$s. If you have any problems, feel free to visit the %2$s support forum%4$s or write to us directly via our %3$s support%4$s email.', 'pausar-3d-ar-for-elementor' ),
                '<a href="https://www.pausarstudio.de/wordpress-elementor/documentation/" target="_blank">',
                '<a href="https://wordpress.org/support/plugin/pausar-3d-ar-for-elementor/" target="_blank">',
                '<a href="mailto:support@pausarstudio.de">',
                '</a>'
            );
            ?>
                                                </p>
                                                <?php 
            if ( pausar_elementor_pe_fs()->is_not_paying() ) {
                ?>
                                                    <p>
                                                    <?php 
                echo sprintf( 
                    /* translators: 1: Website link with open tag,, 2: Link close tag. */
                    esc_html__( 'If you would like to learn more about the features of our paid versions, please visit our %1$s website%2$s.', 'pausar-3d-ar-for-elementor' ),
                    '<a href="https://www.pausarstudio.de/wordpress-elementor/" target="_blank">',
                    '</a>'
                 );
                ?>
                                                </p>
                                                <?php 
            }
            ?>
                                                <br/>
                                                <a class="button button-primary" href="<?php 
            echo esc_html( get_admin_url() . "admin.php?page=" . self::$menuSlug );
            ?>"><?php 
            echo esc_html__( 'Continue to Dashboard', 'pausar-3d-ar-for-elementor' );
            ?></a>
                                            </div>
                                        </div>
                                        <div class="pausar-contentbox">
                                            <div class="pausar-contentbox-wrapper">
                                                <video with="100%" height="auto" controls autoplay muted loop controlsList="nodownload">
                                                    <source src="<?php 
            echo esc_html( plugins_url( "/admin/menu/assets/pausar_gettingstarted_demo.mp4", PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ) );
            ?>" type="video/mp4">
                                                </video>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--/Tabs-->
                            </div>
                            <div class="pausar-WPAdminMenu-getting-started-tabs-nav">
                                <button class="pausar-WPAdminMenu-getting-started-tabs-button" id="pausar-prev"></button>
                                <button class="pausar-WPAdminMenu-getting-started-tabs-button" id="pausar-next"></button>
                            </div>
                        </div>



                    </div>

                </div>               

            </div>
            <?php 
        }

        public function init_admin_inline_script() {
            ?>
            <script type="text/javascript">
                (function() {
                    if(document.readyState === "complete" || document.readyState === "loaded") {
                        init();
                    } else {
                        document.addEventListener("DOMContentLoaded", init);
                    }
                    function init() {
                        //Change external link attributes (Admin Menu)
                        const externalMenuButtons = document.querySelectorAll('.menu-top.toplevel_page_pausar-3d-ar#toplevel_page_pausar-3d-ar li a[href^="http"]');
                        for(let i = 0; i < externalMenuButtons.length; i++) {
                            externalMenuButtons[i].setAttribute("target", "_blank");
                        }
                        //---
                        const AdminBody_homePage = document.querySelector("body.toplevel_page_pausar-3d-ar #wpcontent");
                        const AdminBody_gettingStarted = document.querySelector("body.pausar_page_pausar-3d-ar-getting-started #wpcontent");
                        //Home Page
                        if(typeof AdminBody_homePage !== 'undefined' && AdminBody_homePage != null) {
                            //---
                            let videoPlayers = AdminBody_homePage.querySelectorAll("video");
                            for(let v = 0; v < videoPlayers.length; v++) {
                                videoPlayers[v].addEventListener("contextmenu", function(e) {
                                    e.preventDefault();
                                });                                
                            }
                            //---
                        }
                        //Getting Started
                        if(typeof AdminBody_gettingStarted !== 'undefined' && AdminBody_gettingStarted != null) {
                            //---
                            let videoPlayers = AdminBody_gettingStarted.querySelectorAll("video");
                            for(let v = 0; v < videoPlayers.length; v++) {
                                videoPlayers[v].addEventListener("contextmenu", function(e) {
                                    e.preventDefault();
                                });                                
                            }
                            //---
                            let currentTabIndex = 0;
                            let timelineElement = AdminBody_gettingStarted.querySelector(".pausar-WPAdminMenu-timeline");
                            let tabsContainerElement = AdminBody_gettingStarted.querySelector(".pausar-WPAdminMenu-getting-started-tabs-container");
                            let tabsSectionElement = AdminBody_gettingStarted.querySelector(".pausar-WPAdminMenu-getting-started-tabs");
                            let prevButtonElement = AdminBody_gettingStarted.querySelector(".pausar-WPAdminMenu-getting-started-tabs-nav .pausar-WPAdminMenu-getting-started-tabs-button#pausar-prev");
                            let nextButtonElement = AdminBody_gettingStarted.querySelector(".pausar-WPAdminMenu-getting-started-tabs-nav .pausar-WPAdminMenu-getting-started-tabs-button#pausar-next");
                            let tabs = [];
                            let spinner = AdminBody_gettingStarted.querySelector(".pausar-WPAdminMenu-spinnerContainer");
                            
                            //Generate Tab JSON from static HTML
                            if(!tabsContainerElement || !timelineElement || !prevButtonElement || !nextButtonElement || !tabsSectionElement) {
                                return ;
                            }
                            let tabsElements = tabsContainerElement.querySelectorAll(".pausar-tab");
                            if(tabsElements.length <= 0) {
                                return ;//No tabs found
                            }
                            if(spinner) {
                                spinner.remove();
                            }
                            //---
                            let tabEntry;
                            let entryName;
                            let listElement;
                            //---
                            for(let i = 0; i < tabsElements.length; i++) {
                                tabEntry = {
                                    "name": null,
                                    "tabElement": null,
                                    "listElement": null,
                                };
                                entryName = tabsElements[i].getAttribute("name");
                                if(entryName != null && entryName != "") {
                                    tabEntry.name = entryName;
                                }                               
                                tabEntry.tabElement = tabsElements[i];
                                //Timeline entry
                                if(tabEntry.name != null) {
                                    listElement = document.createElement("li");
                                    listElement.innerHTML = tabEntry.name;
                                    tabEntry.listElement = listElement;

                                    timelineElement.appendChild(listElement);
                                    listElement.addEventListener("click", function() {
                                        changeTab(i);
                                    });
                                }                                
                                tabs.push(tabEntry);
                            }
                            
                            //Display tab section
                            prevButtonElement.addEventListener("click", prevTab);
                            nextButtonElement.addEventListener("click", nextTab);

                            timelineElement.style.setProperty("display", "inline-flex", "important");
                            tabsSectionElement.style.setProperty("display", "block", "important");

                            refreshUI();

                            function refreshUI() {
                                //listEntries (timeline) + tab container                         
                                for(let t = 0; (t < tabs.length); t++) {

                                    let tmpVideoElement = null;
                                    
                                    if(t < currentTabIndex) {
                                        //previous tab
                                        if(tabs[t].listElement != null) {
                                            tabs[t].listElement.className = "pausar-done";
                                        }
                                        tabs[t].tabElement.style.setProperty("display", "none", "important");
                                        //pause video
                                        tmpVideoElement = tabs[t].tabElement.querySelector("video");
                                        if(tmpVideoElement != null) {
                                            tmpVideoElement.pause();
                                            tmpVideoElement = null;
                                        }
                                        
                                    } else if(t == currentTabIndex) {
                                        //current tab (active)
                                        if(tabs[t].listElement != null) {
                                            tabs[t].listElement.className = "pausar-active";
                                            if(t == tabs.length - 1) {
                                                //last available tab
                                                tabs[t].listElement.className = "pausar-done pausar-active";
                                            }
                                        }
                                        tabs[t].tabElement.style.setProperty("display", "block", "important");
                                        //play video
                                        tmpVideoElement = tabs[t].tabElement.querySelector("video");
                                        if(tmpVideoElement != null) {
                                            tmpVideoElement.currentTime = 0;
                                            tmpVideoElement.load();//safari
                                            tmpVideoElement.play();
                                            tmpVideoElement = null;
                                        }
                                    } else {
                                        //next tab
                                        if(tabs[t].listElement != null) {
                                            tabs[t].listElement.className = "";
                                        }
                                        tabs[t].tabElement.style.setProperty("display", "none", "important");
                                        //pause video
                                        tmpVideoElement = tabs[t].tabElement.querySelector("video");
                                        if(tmpVideoElement != null) {
                                            tmpVideoElement.pause();
                                            tmpVideoElement = null;
                                        }
                                    }                                    
                                }

                                prevButtonElement.innerHTML = "";
                                prevButtonElement.disabled = true;
                                prevButtonElement.style.setProperty("display", "none", "important");

                                nextButtonElement.innerHTML = "";
                                nextButtonElement.disabled = true;
                                nextButtonElement.style.setProperty("display", "none", "important");

                                //Navbar
                                if(tabs.length > 1) {
                                    if(currentTabIndex > 0) {
                                        prevButtonElement.innerHTML = "<?php 
            echo esc_html__( 'Back', 'pausar-3d-ar-for-elementor' );
            ?>";
                                        prevButtonElement.disabled = false;
                                        prevButtonElement.style.setProperty("display", "inline-block", "important");
                                    }
                                    if(currentTabIndex < tabs.length - 1) {
                                        //More tab available
                                        if(currentTabIndex == tabs.length - 2 && tabs[currentTabIndex + 1].listElement == null) {
                                            //Second to last tab (+ last tab is not listet)
                                            nextButtonElement.innerHTML = "<?php 
            echo esc_html__( 'Finish', 'pausar-3d-ar-for-elementor' );
            ?>";
                                        } else {
                                            nextButtonElement.innerHTML = "<?php 
            echo esc_html__( 'Next', 'pausar-3d-ar-for-elementor' );
            ?>";
                                        }
                                        nextButtonElement.disabled = false;
                                        nextButtonElement.style.setProperty("display", "inline-block", "important");
                                    }
                                }

                                
                            }

                            function changeTab(index) {
                                if(!tabs) {
                                    return ;
                                }
                                if(tabs.length <= 0) {
                                    return ;
                                }
                                if(typeof index !== 'number') {
                                    return ;
                                }
                                if(index < 0) {
                                    index = 0;
                                } else if(index >= tabs.length) {
                                    index = tabs.length-1;
                                }
                                //---
                                currentTabIndex = index;
                                refreshUI();
                                document.body.scrollIntoView();
                            }

                            function nextTab() {
                                changeTab(currentTabIndex + 1);
                            }

                            function prevTab() {
                                changeTab(currentTabIndex - 1);
                            }
                        }
                    }

                    })();
            </script>
            <?php 
        }

    }

    \PausAR_Elementor\pausar_for_elementor_menu::get_instance();
}