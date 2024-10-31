<?php
namespace PausAR_Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if(!class_exists('\\PausAR_Elementor\\pausar_for_elementor_htaccessValidator')) {

    class pausar_for_elementor_htaccessValidator {

        protected static $optionName = 'pausar_for_elementor_display_mimetype_notice';
        protected static $instance = null;
        protected $wp_filesystem = null;
        protected static $apacheRuleSet = [
            "AddType model/vnd.usdz+zip .usdz",
            "AddType model/vnd.reality .reality",
        ];
        protected static $nginxRuleSet = [
            "model/vnd.usdz+zip usdz",
            "model/vnd.reality reality",
        ];
        protected static $miscRuleSet = [
            "model/vnd.usdz+zip .usdz",
            "model/vnd.reality .reality",
        ];
        protected $noticeVisible = false;
        protected $missingRules = array();
        protected $rulesFound = false;
        protected $evaluatedRules = array();
        protected $rulesEvaluated = false;
        protected static $ruleListStyle = "
            border-radius: 4px;
            width: calc(100% - 38px);
            list-style: disc;
            background: #f4f4f4;
            border: 1px solid #ddd;
            color: #666;
            font-family: monospace;
            font-size: 15px;
            line-height: 1.6;
            overflow: auto;
            padding: 8px 12px 8px 24px;
            display: block;
            position: relative;
            word-wrap: break-word;
            page-break-inside: avoid;
        ";

        //------------------------
        //Instance and Constructor
        //------------------------
        public static function get_instance() {
            if (!is_a(self::$instance, '\\PausAR_Elementor\\pausar_for_elementor_htaccessValidator')) {
                self::$instance = new pausar_for_elementor_htaccessValidator();
            }
            return self::$instance;
        }

        private function __construct() {
            global $wp_filesystem;
            
            //init WP_Filesystem
            if(empty($wp_filesystem)) {
                if (!function_exists('WP_Filesystem')) {
                    require_once ABSPATH . '/wp-admin/includes/file.php';
                }
                if (function_exists('WP_Filesystem')) {
                    WP_Filesystem();
                }                    
            }

            if(!empty($wp_filesystem)) {
                $this->wp_filesystem = $wp_filesystem;
            }
            
            //Init Hooks
            register_activation_hook(
                PAUSAR_FOR_ELEMENTOR_BOOT__FILE__,
                [ $this, 'activateOptions' ]
            );
            pausar_elementor_pe_fs()->add_action('after_uninstall', [ $this, 'cleanupOptions' ] );
            /*
            register_deactivation_hook(
                PAUSAR_FOR_ELEMENTOR_BOOT__FILE__,
                [ $this, 'cleanupOptions' ]
            );
            register_uninstall_hook(
                PAUSAR_FOR_ELEMENTOR_BOOT__FILE__,
                [ $this, 'cleanupOptions' ]
            );
            */            
            
            //$this->initNotice();
        }

        public function initNotice() {
            add_action( 'admin_notices', [ $this, 'showNotice' ] );
            add_action( 'admin_enqueue_scripts', [$this, 'pausar_noticeHandlerScripts']);
            add_action( 'admin_print_scripts', [$this, 'pausar_noticeHandlerScript']);
            add_action( 'wp_ajax_pausar_dismiss_notice', [$this, 'pausar_dismiss_notice']);
            //add_action( 'wp_ajax_nopriv_pausar_dismiss_notice', [$this, 'pausar_dismiss_notice'] );
        }
        
        //Getters/Setters

        /**
         * Returns the filepath for the Apache .htaccess file or the root-folder
         */
        protected function getFilePath($htaccessFile = false) {
            if($htaccessFile) {
                return get_home_path() . '.htaccess';
            }
            return get_home_path();
        }

        public function isInitiated() {
            if(!function_exists('WP_Filesystem') || empty($this->wp_filesystem)) {
                return false;
            }
            return true;
        }
        /**
         * Verifies the use of an Apache server and the presence of an .htaccess file.
         */
        public function isApache() {
            //Example: Apache/2.4.58 (Win64) OpenSSL/3.1.3 PHP/8.2.12
            if(isset($_SERVER["SERVER_SOFTWARE"])) {
                if(is_string($_SERVER["SERVER_SOFTWARE"])) {
                    if(strpos($_SERVER["SERVER_SOFTWARE"], 'Apache') !== false) {
                        return true;
                    }
                }
            }
            return false;
        }
        public function isHtaccessAccesseble() {
            if(!$this->isApache()) {
                return false;
            }
            if(!$this->isInitiated()) {
                return false;
            }
            return $this->wp_filesystem->exists($this->getFilePath(true));
        }
        public function isNginx() {
            //Example: nginx/1.21.3
            if(isset($_SERVER["SERVER_SOFTWARE"])) {
                if(is_string($_SERVER["SERVER_SOFTWARE"])) {
                    if(strpos($_SERVER["SERVER_SOFTWARE"], 'nginx') !== false) {
                        return true;
                    }
                }
            }
            return false;
        }
        public function isWritable($htaccessFile = false) {
            return $this->wp_filesystem->is_writable($this->getFilePath($htaccessFile));
        }
        public function isReadable($htaccessFile = false) {
            return $this->wp_filesystem->is_readable($this->getFilePath($htaccessFile));
        }

        public function getMissingHtaccessRules() {
            if($this->rulesFound) {
                return $this->missingRules;
            }

            $result = array();
            if(!$this->isInitiated()) {
                return $result;
            }
            if($this->isHtaccessAccesseble()) {
                //Apache and .htaccess found
                if($this->isReadable(true)) {
                    $htaccessContent = $this->wp_filesystem->get_contents_array($this->getFilePath(true));
                    $containedRules = array();
                    
                    //Detect all occuring rules inside the .htaccess
                    for($i = 0; $i < sizeof($htaccessContent); $i++) {
                        for($j = 0; $j < sizeof(self::$apacheRuleSet); $j++) {
                            if($this->compareRules($htaccessContent[$i], self::$apacheRuleSet[$j])) {
                                array_push($containedRules, self::$apacheRuleSet[$j]);
                            }
                        }                            
                    }
                    //Evaluate all missing rules (comparing ruleSet and occuringRules)
                    for($x = 0; $x < sizeof(self::$apacheRuleSet); $x++) {
                        if(!in_array(self::$apacheRuleSet[$x], $containedRules)) {
                            array_push($result, self::$apacheRuleSet[$x]);
                        }
                    }
                    $this->missingRules = $result;
                    $this->rulesFound = true;
                    return $result;
                }
            }
            return $result;
        }

        public function evaluateHtaccessRules() {
            if($this->rulesEvaluated) {
                return $this->evaluatedRules;
            }

            $result = [
                'matchingRules' => 0,
                'data' => array()
            ];
            if(!$this->isInitiated()) {
                return $result;
            }
            if($this->isHtaccessAccesseble()) {
                //Apache and .htaccess found
                if($this->isReadable(true)) {
                    $htaccessContent = $this->wp_filesystem->get_contents_array($this->getFilePath(true));
                    $containedRules = array();
                    
                    //Detect all occuring rules inside the .htaccess
                    for($i = 0; $i < sizeof(self::$apacheRuleSet); $i++) {
                        $currentRuleFound = false;
                        for($j = 0; $j < sizeof($htaccessContent); $j++) {
                            if($this->compareRules($htaccessContent[$j], self::$apacheRuleSet[$i])) {
                                $currentRuleFound = true;
                            }
                        }
                        //Prepare Entry
                        if($currentRuleFound) {
                            $result['matchingRules'] .= 1;
                        }
                        $entry = new \stdClass();
                        $entry->contained = $currentRuleFound;
                        $entry->rule = self::$apacheRuleSet[$i];
                        array_push($result['data'], $entry);
                    }
                    $this->evaluatedRules = $result;
                    $this->rulesEvaluated = true;
                    return $result;
                }
            }
            return $result;
        }

        //Helper

        protected function compareRules($fromFile, $fromSet) {
            if(empty($fromFile) || empty($fromSet)) {
                return false;
            }
            if(!is_string($fromFile) || !is_string($fromSet)) {
                return false;
            }
            //Align variables
            $fromFile = preg_replace("/\s\s+/i", " ", $fromFile);//Removes any duplicate spaces or tabs
            $fromFile = preg_replace("/^[\s]+|[\s]+$/i", "", $fromFile);//Removes all spaces and tabs at the beginning and end of the string

            $fromSet = preg_replace("/\s\s+/i", " ", $fromSet);//Removes any duplicate spaces or tabs
            $fromSet = preg_replace("/^[\s]+|[\s]+$/i", "", $fromSet);//Removes all spaces and tabs at the beginning and end of the string

            return $fromFile == $fromSet;
            
        }

        //Misc
        /**
         * Evaluates, if a notice must be displayed and options must be added
         */
                
        public function activateOptions() {
            
            if(!$this->isInitiated()) {
                return ;
            }
            
            $option = json_decode(get_option( $this::$optionName, "{}"), true);
            $insertData = [
                'server' => null,
                'dismissed' => array()
            ];        

            if($this->isNginx()) {
                $insertData['server'] = 'nginx';
                //empty($option) ? add_option(self::$optionName, wp_json_encode($insertData), '', true) : update_option(self::$optionName, wp_json_encode($insertData), '', true);
                if(empty($option)) {
                    add_option(self::$optionName, wp_json_encode($insertData), '', true);
                }
            } else if($this->isApache()) {
                //Validator
                if(sizeof($this->getMissingHtaccessRules()) > 0) {
                    $insertData['server'] = 'apache';
                    //empty($option) ? add_option(self::$optionName, wp_json_encode($insertData), '', true) : update_option(self::$optionName, wp_json_encode($insertData), '', true);
                    if(empty($option)) {
                        add_option(self::$optionName, wp_json_encode($insertData), '', true);
                    }
                }
                
            } else {
                $insertData['server'] = 'misc';
                //empty($option) ? add_option(self::$optionName, wp_json_encode($insertData), '', true) : update_option(self::$optionName, wp_json_encode($insertData), '', true);
                if(empty($option)) {
                    add_option(self::$optionName, wp_json_encode($insertData), '', true);
                }
            }
        }
        /**
         * Removes all added options from the Database, if plugin gets disabled or removed
         */
        public function cleanupOptions() {
            if ( null !== get_option( self::$optionName, null ) ) {
                delete_option(self::$optionName);
            }
        }

        public function dismissUser() {
            if(!is_user_logged_in()) {
                return ;
            }
            if(!$this->isAdminUser()) {
                return ;
            }
            $option = json_decode(get_option( $this::$optionName, "{}"), true);
            if(!$this->validateOption($option)) {
                return ;
            }
            
            array_push($option['dismissed'], get_current_user_id());
            update_option(self::$optionName, wp_json_encode($option), null, true);
            $this->noticeVisible = false;
        }

        protected function isAdminUser() {
            if(!is_user_logged_in()) {
                return false;
            }

            $user = wp_get_current_user();
            $roles = array_values((array) $user->roles);//Converts potential key-map to plain array
            if(in_array('administrator', $roles) || in_array('Administrator', $roles) || in_array('admin', $roles) || in_array('Admin', $roles)) {
                return true;
            }
            return false;

        }

        protected function isCorrectPage() {
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

        /**
         * Displays a notice, if the option is set. Reevaluates missing rules from the .htaccess file.
         */
        public function showNotice() {
            if(!is_admin() || $this->noticeVisible || !is_user_logged_in()) {
                return ;
            }
            if(!$this->isCorrectPage()) {
                return ;
            }
            $option = json_decode(get_option( $this::$optionName, "{}"), true);
            
            if(!$this->validateOption($option)) {
                return ;
            }

            if($this->isApache()) {
                $this->apacheNotice();
            } else if($this->isNginx()) {
                $this->nginxNotice();
            } else {
                $this->miscNotice();
            }
            $this->noticeVisible = true;
        }
        /**
         * Checks, if the WP_Option exists and has a valid value (+ Validation of the current User)
         */
        private function validateOption($option = null) {
            if($option == null) {
                return false;
            }
            if(empty($option)) {
                return false;
            }
            if(!array_key_exists('dismissed', $option)) {
                return false;
            }
            /*
            if(!is_user_logged_in()) {
                return false;
            }
            */
            if(!$this->isAdminUser()) {
                return false;
            }
            /*
            if(in_array(get_current_user_id(), $option['dismissed'])) {
                return false;
            }
            */
            $currentUser = get_current_user_id();
            for($i = 0; $i < sizeof($option['dismissed']); $i++) {
                if((int) $option['dismissed'][$i] == $currentUser) {
                    return false;
                }
            }
            
            return true;
        }

        //CustomNotices

        public function nginxNotice() {
            ?>
            <div class="pausar-for-elementor-notice notice notice-info">
                    <h3>
                        <?php printf(esc_html__("Setting up PausAR - Add MimeTypes to the web server", 'pausar-3d-ar-for-elementor')); ?>
                    </h3>
                    <p>
                        <?php printf(esc_html__("PausAR offers the use of specific file formats for Apple devices, which enable the display of 3D content and AR scenes on iOS devices. However, these new formats are not supported by most web servers as standard, which means that some AR scenes cannot be displayed by Apple users. For unrestricted use of PausAR, the new MimeTypes should be added to the server configuration.", 'pausar-3d-ar-for-elementor')); ?>
                    </p>
                    <p>
                        <?php print(esc_html__("The following lines can be added to the server configuration file:", 'pausar-3d-ar-for-elementor')); ?>
                    </p>
                    <ul style="<?php echo esc_html(self::$ruleListStyle); ?>">
                    <?php
                        foreach (self::$nginxRuleSet as $rule) {
                            ?>
                                <li><?php echo esc_html($rule); ?></li>
                            <?php
                        }
                    ?>
                    </ul>
                    <p>
                        <?php
                                        
                            echo sprintf(
                                /* translators: 1: Filepath */
                                esc_html__('On an Nginx server, all optional MimeTypes are added to the "%1$s" file. This file can be found in the root folder or must be created by the administrator.', 'pausar-3d-ar-for-elementor'),
                                'mime.type'
                            );
                                    
                        ?>
                    </p>
                    
                    <p class="pausar-for-elementor-buttons" style="margin-top: 16px;">
                        <a class="pausar-for-elementor-close button button-primary" style="margin-right: 10px;">
                            <?php print(esc_html__("Don't show again", 'pausar-3d-ar-for-elementor')); ?>
                        </a>
                        <a class="button button-secondary" href="https://www.pausarstudio.de/wordpress-elementor/documentation/#installation" target="_blank">
                            <?php print(esc_html__('Learn more', 'pausar-3d-ar-for-elementor')); ?>
                        </a>
                    </p>
                </div>
            <?php
        }
        public function miscNotice() {
            ?>
            <div class="pausar-for-elementor-notice notice notice-info">
                    <h3>
                        <?php printf(esc_html__("Setting up PausAR - Add MimeTypes to the web server", 'pausar-3d-ar-for-elementor')); ?>
                    </h3>
                    <p>
                        <?php printf(esc_html__("PausAR offers the use of specific file formats for Apple devices, which enable the display of 3D content and AR scenes on iOS devices. However, these new formats are not supported by most web servers as standard, which means that some AR scenes cannot be displayed by Apple users. For unrestricted use of PausAR, the new MimeTypes should be added to the server configuration.", 'pausar-3d-ar-for-elementor')); ?>
                    </p>
                    <p>
                        <?php print(esc_html__("It cannot be determined exactly which server is currently being used. Adding the required MimeTypes may vary depending on your server. It's best to get information from your server provider.", 'pausar-3d-ar-for-elementor')); ?>
                    </p>
                    <p>
                        <?php print(esc_html__("The following MimeTypes may need to be added:", 'pausar-3d-ar-for-elementor')); ?>
                    </p>
                    <ul style="<?php echo esc_html(self::$ruleListStyle); ?>">
                    <?php
                        foreach (self::$miscRuleSet as $rule) {
                            ?>
                                <li><?php echo esc_html($rule) ?></li>
                            <?php
                        }
                    ?>
                    </ul>
                                        
                    <p class="pausar-for-elementor-buttons" style="margin-top: 16px;">
                        <a class="pausar-for-elementor-close button button-primary" style="margin-right: 10px;">
                            <?php print(esc_html__("Don't show again", 'pausar-3d-ar-for-elementor')); ?>
                        </a>
                        <a class="button button-secondary" href="https://www.pausarstudio.de/wordpress-elementor/documentation/#installation" target="_blank">
                            <?php print(esc_html__('Learn more', 'pausar-3d-ar-for-elementor')); ?>
                        </a>
                    </p>
                </div>
            <?php
        }
        public function apacheNotice() {
            if($this->isHtaccessAccesseble()) {
                //Specific Notice (Analyse .htaccess file)
                ?>
                <div class="pausar-for-elementor-notice notice notice-info">
                    <h3>
                        <?php printf(esc_html__("Setting up PausAR - Add MimeTypes to the web server", 'pausar-3d-ar-for-elementor')); ?>
                    </h3>
                    <p>
                        <?php printf(esc_html__("PausAR offers the use of specific file formats for Apple devices, which enable the display of 3D content and AR scenes on iOS devices. However, these new formats are not supported by most web servers as standard, which means that some AR scenes cannot be displayed by Apple users. For unrestricted use of PausAR, the new MimeTypes should be added to the server configuration.", 'pausar-3d-ar-for-elementor')); ?>
                    </p>
                    <?php
                        if((int) ($this->evaluateHtaccessRules()['matchingRules']) >= sizeof(self::$apacheRuleSet)) {
                            ?>
                                <p>✔️ <?php print(esc_html__("This server is already sufficiently configured. No lines need to be added.", 'pausar-3d-ar-for-elementor')); ?></p>
                            <?php
                        } else {
                            ?>
                                <p><?php print(esc_html__("The following lines can be added to the server configuration file:", 'pausar-3d-ar-for-elementor')); ?></p>
                            <?php
                        }
                    ?>
                    <ul style="<?php echo esc_html(self::$ruleListStyle); ?>">
                    <?php
                        $evRules = $this->evaluateHtaccessRules();
                        foreach ($evRules['data'] as $rule) {
                            if($rule->contained) {
                                ?>
                                <li style="opacity: 0.3333;" class="pausar-for-elementor-rule-contained"><?php echo esc_html($rule->rule); ?></li>
                                <?php
                            } else {
                                ?>
                                <li><?php echo esc_html($rule->rule); ?></li>
                                <?php
                            }
                        }
                    ?>
                    </ul>
                    <?php
                        if((int) ($this->evaluateHtaccessRules()['matchingRules']) < sizeof(self::$apacheRuleSet)) {
                            ?>
                                <p>
                                    <?php
                                        
                                        echo sprintf(
                                            /* translators: 2: Htaccess-Editors */
                                            esc_html__('On an Apache server, all optional MimeTypes are added to the "%1$s" file via specific commands. The lines shown can be added manually or using "%2$s".', 'pausar-3d-ar-for-elementor'),
                                            '/.htaccess',
                                            '<a href="'.filter_var( 'plugin-install.php?s=htaccess%2520Editor&tab=search&type=tag',  FILTER_SANITIZE_URL ).'">'.esc_html__("Htaccess-Editors", 'pausar-3d-ar-for-elementor').'</a>'
                                        );
                                    
                                    ?>
                                    
                                </p>
                                
                            <?php
                        }
                    ?>
                    <p class="pausar-for-elementor-buttons" style="margin-top: 16px;">
                        <a class="pausar-for-elementor-close button button-primary" style="margin-right: 10px;">
                            <?php print(esc_html__("Don't show again", 'pausar-3d-ar-for-elementor')); ?>
                        </a>
                        <a class="button button-secondary" href="https://www.pausarstudio.de/wordpress-elementor/documentation/#installation" target="_blank">
                            <?php print(esc_html__('Learn more', 'pausar-3d-ar-for-elementor')); ?>
                        </a>
                    </p>
                </div>
                
                <?php
            } else {
                //Generic Notice (no access to .htaccess or file missing)
                ?>
                <div class="pausar-for-elementor-notice notice notice-info">
                    <h3>
                        <?php printf(esc_html__("Setting up PausAR - Add MimeTypes to the web server", 'pausar-3d-ar-for-elementor')); ?>
                    </h3>
                    <p>
                        <?php printf(esc_html__("PausAR offers the use of specific file formats for Apple devices, which enable the display of 3D content and AR scenes on iOS devices. However, these new formats are not supported by most web servers as standard, which means that some AR scenes cannot be displayed by Apple users. For unrestricted use of PausAR, the new MimeTypes should be added to the server configuration.", 'pausar-3d-ar-for-elementor')); ?>
                    </p>
                    <p>
                        <?php print(esc_html__("The following lines can be added to the server configuration file:", 'pausar-3d-ar-for-elementor')); ?>
                    </p>
                    <ul style="<?php echo esc_html(self::$ruleListStyle); ?>">
                    <?php
                        foreach (self::$apacheRuleSet as $rule) {
                            ?>
                                <li><?php echo esc_html($rule); ?></li>
                            <?php
                        }
                    ?>
                    </ul>
                    <p>
                        <?php
                                        
                            echo sprintf(
                                /* translators: 2: Htaccess-Editors */
                                esc_html__('On an Apache server, all optional MimeTypes are added to the "%1$s" file via specific commands. The lines shown can be added manually or using "%2$s".', 'pausar-3d-ar-for-elementor'),
                                '/.htaccess',
                                '<a href="'.filter_var( 'plugin-install.php?s=htaccess%2520Editor&tab=search&type=tag',  FILTER_SANITIZE_URL ).'">'.esc_html__("Htaccess-Editors", 'pausar-3d-ar-for-elementor').'</a>'
                            );
                                    
                        ?>
                    </p>
                    
                    <p class="pausar-for-elementor-buttons" style="margin-top: 16px;">
                        <a class="pausar-for-elementor-close button button-primary" style="margin-right: 10px;">
                            <?php print(esc_html__("Don't show again", 'pausar-3d-ar-for-elementor')); ?>
                        </a>
                        <a class="button button-secondary" href="https://www.pausarstudio.de/wordpress-elementor/documentation/#installation" target="_blank">
                            <?php print(esc_html__('Learn more', 'pausar-3d-ar-for-elementor')); ?>
                        </a>
                    </p>
                </div>
                <?php
            }
            
        }

        public function pausar_noticeHandlerScripts() {
            //Style
            if(!wp_style_is('pausar_for_elementor_notice_style', 'registered')) {
                //wp_register_style( 'pausar_for_elementor_notice_style', plugins_url( "/admin/adminNotice.css", PAUSAR_FOR_ELEMENTOR_BOOT__FILE__), null, null, 'all');
                wp_register_style( 'pausar_for_elementor_notice_style', plugins_url( "/admin/adminNotice.css", PAUSAR_FOR_ELEMENTOR_BOOT__FILE__), null, get_plugin_data( PAUSAR_FOR_ELEMENTOR_BOOT__FILE__, false, false )['Version'], 'all' );
            }
            if(!wp_style_is('pausar_for_elementor_notice_style', 'enqueued')) {
                wp_enqueue_style('pausar_for_elementor_notice_style');
            }
        }

        public function pausar_noticeHandlerScript() {
            ?>
            <script type="text/javascript">
                (function() {
                    if(document.readyState === "complete" || document.readyState === "loaded") {
                        initPausAR_NoticeHandler();
                    } else {
                        document.addEventListener("DOMContentLoaded", initPausAR_NoticeHandler);
                    }
                    function initPausAR_NoticeHandler() {
                        var notice, closeButton;
                        notice = document.querySelector(".pausar-for-elementor-notice");
                        if(notice) {
                            closeButton = notice.querySelector(".pausar-for-elementor-close");
                            if(closeButton && typeof jQuery !== 'undefined' && jQuery != null) {
                                closeButton.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    var data = {
                                        action: 'pausar_dismiss_notice'
                                    };
                                    jQuery.post( '<?php echo esc_url(admin_url( 'admin-ajax.php' )); ?>', data, function( response ) {
                                    });
                                    notice.remove();
                                    
                                });
                            }
                        }                        
                    }
                })();
                
            </script>
            <?php
        }

        public function pausar_dismiss_notice() {
            
            $this->dismissUser();

            $responseData = array("noticed closed");
            echo wp_json_encode($responseData);
            wp_die();
        }
        
    }
    \PausAR_Elementor\pausar_for_elementor_htaccessValidator::get_instance();
          
}