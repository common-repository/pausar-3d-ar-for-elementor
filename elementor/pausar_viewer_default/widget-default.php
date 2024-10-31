<?php

namespace PausAR_Elementor;

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly.
}
class PausAR_Widget_Default extends \Elementor\Widget_Base {
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
    }

    //------------------------------
    protected function getPausAR_Logo_Inline() {
        ?>
			<img src="data:image/png;base64,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" alt="" />
		<?php 
    }

    private $maxSkyboxSize = [
        'width'  => 2048,
        'height' => 1024,
    ];

    //------------------------------
    public function get_name() {
        return 'PausAR_Viewer_Default';
        //Unique ID | must be added to 'pausAR-elementor-Handler.js'
    }

    public function get_title() {
        return esc_html__( 'PausAR Viewer', 'pausar-3d-ar-for-elementor' );
    }

    public function get_icon() {
        //https://elementor.github.io/elementor-icons/
        return 'pausar-icon-viewer';
    }

    public function get_custom_help_url() {
        return 'https://www.pausarstudio.de/wordpress-elementor/documentation/';
    }

    public function get_categories() {
        return ['pausar-3d-ar-for-elementor'];
    }

    public function get_keywords() {
        return [
            'Paus',
            'AR',
            'Augmented',
            'Reality',
            'Model',
            'Viewer',
            'Model-Viewer',
            '3D',
            'PausAR'
        ];
    }

    public function get_script_depends() {
        return [
            'pausar-frontendScript',
            //individual
            'pausar-modelviewer-script',
            //once
            'pausar-elementor-widgetHandler',
            //once
            'pausar-loadingHandler-script',
        ];
    }

    public function get_style_depends() {
        return ['pausar-elementor-widget-style', 'pausar-elementor-base-style'];
    }

    protected function register_controls() {
        // Content Tab Start
        $this->start_controls_section( 'content_section', [
            'label' => esc_html__( 'Basic Settings', 'pausar-3d-ar-for-elementor' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );
        $this->add_control( 'button_title', [
            'label'       => esc_html__( 'Button Text', 'pausar-3d-ar-for-elementor' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => esc_html__( 'Start AR', 'pausar-3d-ar-for-elementor' ),
            'label_block' => false,
            'show_label'  => true,
        ] );
        $this->add_control( 'hrIconOpen', [
            'type'      => \Elementor\Controls_Manager::DIVIDER,
            'condition' => [
                'icon[value]!' => '',
            ],
        ] );
        $this->add_control( 'icon', [
            'label'       => __( 'Icon', 'pausar-3d-ar-for-elementor' ),
            'type'        => \Elementor\Controls_Manager::ICONS,
            'default'     => [
                'value'   => '',
                'library' => '',
            ],
            'skin'        => 'inline',
            'label_block' => false,
            'show_label'  => true,
            'classes'     => 'pausAR-UI-elementorDisabled',
        ] );
        $this->add_control( 'icon_align', [
            'label'                => esc_html__( 'Icon Position', 'pausar-3d-ar-for-elementor' ),
            'type'                 => \Elementor\Controls_Manager::CHOOSE,
            'options'              => [
                'row'         => [
                    'title' => esc_html__( 'Start', 'pausar-3d-ar-for-elementor' ),
                    'icon'  => 'eicon-h-align-' . (( is_rtl() ? 'right' : 'left' )),
                ],
                'row-reverse' => [
                    'title' => esc_html__( 'End', 'pausar-3d-ar-for-elementor' ),
                    'icon'  => 'eicon-h-align-' . (( is_rtl() ? 'left' : 'right' )),
                ],
            ],
            'default'              => ( is_rtl() ? 'row-reverse' : 'row' ),
            'selectors_dictionary' => [
                'left'  => ( is_rtl() ? 'row-reverse' : 'row' ),
                'right' => ( is_rtl() ? 'row' : 'row-reverse' ),
            ],
            'selectors'            => [
                '{{WRAPPER}} .pausAR-UI-ArButton .pausAR-UI-ArButtonWrapper' => 'flex-direction: {{VALUE}};',
            ],
            'toggle'               => false,
            'label_block'          => false,
        ] );
        $this->add_responsive_control( 'icon_margin', [
            'type'           => \Elementor\Controls_Manager::SLIDER,
            'label'          => esc_html__( 'Margin', 'pausar-3d-ar-for-elementor' ),
            'range'          => [
                'px'  => [
                    'min' => 0,
                    'max' => 50,
                ],
                'em'  => [
                    'min' => 0,
                    'max' => 5,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 5,
                ],
            ],
            'default'        => [
                'size' => 5,
                'unit' => 'px',
            ],
            'tablet_default' => [
                'size' => 5,
                'unit' => 'px',
            ],
            'mobile_default' => [
                'size' => 5,
                'unit' => 'px',
            ],
            'size_units'     => [
                'px',
                'em',
                'rem',
                'custom'
            ],
            'selectors'      => [
                '{{WRAPPER}} .pausAR-UI-ArButton .pausAR-UI-ArButtonWrapper' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ] );
        $this->add_control( 'hrIconClose', [
            'type'      => \Elementor\Controls_Manager::DIVIDER,
            'condition' => [
                'icon[value]!' => '',
            ],
        ] );
        $this->add_responsive_control( 'button_align', [
            'label'        => esc_html__( 'Alignment', 'pausar-3d-ar-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::CHOOSE,
            'options'      => [
                'left'    => [
                    'title' => esc_html__( 'Left', 'pausar-3d-ar-for-elementor' ),
                    'icon'  => 'eicon-text-align-left',
                ],
                'center'  => [
                    'title' => esc_html__( 'Center', 'pausar-3d-ar-for-elementor' ),
                    'icon'  => 'eicon-text-align-center',
                ],
                'right'   => [
                    'title' => esc_html__( 'Right', 'pausar-3d-ar-for-elementor' ),
                    'icon'  => 'eicon-text-align-right',
                ],
                'justify' => [
                    'title' => esc_html__( 'Justified', 'pausar-3d-ar-for-elementor' ),
                    'icon'  => 'eicon-text-align-justify',
                ],
            ],
            'prefix_class' => 'pausAR-UI-ArButton-%s',
            'default'      => 'center',
        ] );
        //---
        $this->end_controls_section();
        $this->start_controls_section( 'ar_section', [
            'label' => esc_html__( 'AR Scene', 'pausar-3d-ar-for-elementor' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );
        //---
        //Widget Status Container (RAW_HTML)
        $this->add_control( 'widget_status_container_all', [
            'label'           => '',
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => '<div class="pausAR-UI-elementorStatusContainerHeader">
      						<div class="pausAR-UI-elementorStatusContainerHeaderIcon"></div>
      							<div class="pausAR-UI-elementorStatusContainerHeaderText">
         							' . esc_html__( 'Configuration of the AR scene not yet complete', 'pausar-3d-ar-for-elementor' ) . '
      							</div>
    						</div>
    
    						<p>' . esc_html__( 'The following 3D content is missing:', 'pausar-3d-ar-for-elementor' ) . '</p>
    						<ul>
      							<li>' . esc_html__( '3D Model (Android and Preview)', 'pausar-3d-ar-for-elementor' ) . '</li>
      							<li>' . esc_html__( '3D Model (iOS)', 'pausar-3d-ar-for-elementor' ) . '</li>
    						</ul>
    						<p>' . esc_html__( 'Android or iOS users cannot view the AR scene if the corresponding files are missing.', 'pausar-3d-ar-for-elementor' ) . '</p>
    						<p>' . sprintf( 
                // translators: 1: Link open tag, 2: Link close tag.
                esc_html__( 'Use our free %1$s online converter %2$s to easily convert your GLTF models (*.glb) for iOS.', 'pausar-3d-ar-for-elementor' ),
                '<a href="https://converter.pausarstudio.de/" target="_blank" rel="noopener">',
                '</a>'
             ) . '</p>',
            'show_label'      => false,
            'label_block'     => false,
            'content_classes' => 'pausAR-UI-elementorStatusContainer',
            'conditions'      => [
                'relation' => 'and',
                'terms'    => [[
                    'name'     => 'android_glb_file[url]',
                    'operator' => '==',
                    'value'    => '',
                ], [
                    'name'     => 'apple_ios_file[url]',
                    'operator' => '==',
                    'value'    => '',
                ]],
            ],
        ] );
        $this->add_control( 'widget_status_container_usdz', [
            'label'           => '',
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => '<div class="pausAR-UI-elementorStatusContainerHeader">
      						<div class="pausAR-UI-elementorStatusContainerHeaderIcon"></div>
      							<div class="pausAR-UI-elementorStatusContainerHeaderText">
         							' . esc_html__( 'Configuration of the AR scene not yet complete', 'pausar-3d-ar-for-elementor' ) . '
      							</div>
    						</div>
    
    						<p>' . esc_html__( 'The following 3D content is missing:', 'pausar-3d-ar-for-elementor' ) . '</p>
    						<ul>
      							<li><s>' . esc_html__( '3D Model (Android and Preview)', 'pausar-3d-ar-for-elementor' ) . '</s></li>
      							<li>' . esc_html__( '3D Model (iOS)', 'pausar-3d-ar-for-elementor' ) . '</li>
    						</ul>
    						<p>' . esc_html__( 'Android or iOS users cannot view the AR scene if the corresponding files are missing.', 'pausar-3d-ar-for-elementor' ) . '</p>
    						<p>' . sprintf( 
                // translators: 1: Link open tag, 2: Link close tag.
                esc_html__( 'Use our free %1$s online converter %2$s to easily convert your GLTF models (*.glb) for iOS.', 'pausar-3d-ar-for-elementor' ),
                '<a href="https://converter.pausarstudio.de/" target="_blank" rel="noopener">',
                '</a>'
             ) . '</p>',
            'show_label'      => false,
            'label_block'     => false,
            'content_classes' => 'pausAR-UI-elementorStatusContainer',
            'conditions'      => [
                'relation' => 'and',
                'terms'    => [[
                    'name'     => 'android_glb_file[url]',
                    'operator' => '!=',
                    'value'    => '',
                ], [
                    'name'     => 'apple_ios_file[url]',
                    'operator' => '==',
                    'value'    => '',
                ]],
            ],
        ] );
        $this->add_control( 'widget_status_container_android', [
            'label'           => '',
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => '<div class="pausAR-UI-elementorStatusContainerHeader">
      						<div class="pausAR-UI-elementorStatusContainerHeaderIcon"></div>
      							<div class="pausAR-UI-elementorStatusContainerHeaderText">
         							' . esc_html__( 'Configuration of the AR scene not yet complete', 'pausar-3d-ar-for-elementor' ) . '
      							</div>
    						</div>
    
    						<p>' . esc_html__( 'The following 3D content is missing:', 'pausar-3d-ar-for-elementor' ) . '</p>
    						<ul>
      							<li>' . esc_html__( '3D Model (Android and Preview)', 'pausar-3d-ar-for-elementor' ) . '</li>
      							<li><s>' . esc_html__( '3D Model (iOS)', 'pausar-3d-ar-for-elementor' ) . '</s></li>
    						</ul>
    						<p>' . esc_html__( 'Android or iOS users cannot view the AR scene if the corresponding files are missing.', 'pausar-3d-ar-for-elementor' ) . '</p>',
            'show_label'      => false,
            'label_block'     => false,
            'content_classes' => 'pausAR-UI-elementorStatusContainer',
            'conditions'      => [
                'relation' => 'and',
                'terms'    => [[
                    'name'     => 'android_glb_file[url]',
                    'operator' => '==',
                    'value'    => '',
                ], [
                    'name'     => 'apple_ios_file[url]',
                    'operator' => '!=',
                    'value'    => '',
                ]],
            ],
        ] );
        $this->add_control( 'widget_status_container_complete', [
            'label'           => '',
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => '<div class="pausAR-UI-elementorStatusContainerHeader pausAR-UI-elementorStatusContainerHeaderComplete">
      						<div class="pausAR-UI-elementorStatusContainerHeaderIcon"></div>
      							<div class="pausAR-UI-elementorStatusContainerHeaderText">
         							' . esc_html__( 'The required AR scene content has been specified', 'pausar-3d-ar-for-elementor' ) . '
      							</div>
    						</div>
    
    						<p>' . esc_html__( 'All required 3D content has been added. Android and iOS users can now both launch the AR scene.', 'pausar-3d-ar-for-elementor' ) . '</p>',
            'show_label'      => false,
            'label_block'     => false,
            'content_classes' => 'pausAR-UI-elementorStatusContainer',
            'conditions'      => [
                'relation' => 'and',
                'terms'    => [[
                    'name'     => 'android_glb_file[url]',
                    'operator' => '!=',
                    'value'    => '',
                ], [
                    'name'     => 'apple_ios_file[url]',
                    'operator' => '!=',
                    'value'    => '',
                ]],
            ],
        ] );
        //---
        $this->add_control( 'android_glb_file', [
            'label'       => esc_html__( '3D Model (Android and Preview)', 'pausar-3d-ar-for-elementor' ),
            'type'        => \Elementor\Controls_Manager::MEDIA,
            'label_block' => true,
            'media_type'  => ['model/gltf-binary', 'application/pausarxr'],
            'description' => esc_html__( 'Filetype: .glb', 'pausar-3d-ar-for-elementor' ),
        ] );
        $this->add_control( 'apple_ios_file', [
            'label'       => esc_html__( '3D Model (iOS)', 'pausar-3d-ar-for-elementor' ),
            'type'        => \Elementor\Controls_Manager::MEDIA,
            'label_block' => true,
            'media_type'  => ['model/vnd.usdz+zip', 'application/pausarql'],
            'description' => sprintf( 
                /* translators: 1: Link open tag, 3: Link close tag. */
                esc_html__( 'Filetype(s): .usdz, .reality | Try out our free %1$s USDZ Converter %2$s.', 'pausar-3d-ar-for-elementor' ),
                '<a href="https://converter.pausarstudio.de/" target="_blank" rel="noopener">',
                '</a>'
             ),
        ] );
        $this->add_control( 'arSceneDivider', [
            'type' => \Elementor\Controls_Manager::DIVIDER,
        ] );
        $this->add_control( 'scene_sound_file', [
            'label'       => esc_html__( 'Sound', 'pausar-3d-ar-for-elementor' ),
            'type'        => \Elementor\Controls_Manager::MEDIA,
            'label_block' => true,
            'media_type'  => [
                'audio/wav',
                'audio/x-wav',
                'audio/mpeg3',
                'audio/x-mpeg-3',
                'audio/mpeg',
                'audio/mp3'
            ],
            'description' => sprintf(
                /* translators: 1: quotation mark, 2: Link open tag, 3: Link close tag. */
                esc_html__( 'Filetype(s): .wav, .mp3 | Only applied to scenes with .glb files. For Apple devices, sound files are embedded directly into the model via %1$s reality composer %1$s. %2$s Learn more %3$s', 'pausar-3d-ar-for-elementor' ),
                '"',
                '<a href="https://www.pausarstudio.de/wordpress-elementor/documentation/" target="_blank" rel="noopener">',
                '</a>'
            ),
            'classes'     => 'pausAR-UI-elementorDisabled',
        ] );
        $this->add_control( 'soundDivider', [
            'type' => \Elementor\Controls_Manager::DIVIDER,
        ] );
        $this->add_control( 'scene_ui_activate', [
            'label'        => esc_html__( 'Enable Scene UI', 'pausar-3d-ar-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'On', 'pausar-3d-ar-for-elementor' ),
            'label_off'    => esc_html__( 'Off', 'pausar-3d-ar-for-elementor' ),
            'return_value' => 'true',
            'default'      => 'false',
            'classes'      => 'pausAR-UI-elementorDisabled',
        ] );
        $this->add_control( 'scene_disable_scaling', [
            'label'        => esc_html__( 'Disable Scaling', 'pausar-3d-ar-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'On', 'pausar-3d-ar-for-elementor' ),
            'label_off'    => esc_html__( 'Off', 'pausar-3d-ar-for-elementor' ),
            'return_value' => 'true',
            'default'      => 'false',
            'classes'      => 'pausAR-UI-elementorDisabled',
        ] );
        $this->add_control( 'scene_occlusion', [
            'label'        => esc_html__( 'Object Blending', 'pausar-3d-ar-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'On', 'pausar-3d-ar-for-elementor' ),
            'label_off'    => esc_html__( 'Off', 'pausar-3d-ar-for-elementor' ),
            'return_value' => 'true',
            'default'      => 'false',
            'description'  => esc_html__( 'Only applied to scenes with .glb files', 'pausar-3d-ar-for-elementor' ),
        ] );
        $this->add_control( 'scene_placement', [
            'label'        => esc_html__( 'Vertical Surface Placement', 'pausar-3d-ar-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'On', 'pausar-3d-ar-for-elementor' ),
            'label_off'    => esc_html__( 'Off', 'pausar-3d-ar-for-elementor' ),
            'return_value' => 'vertical',
            'default'      => 'horizontal',
            'description'  => sprintf( 
                /* translators: 1: Link open tag, 2: Link close tag. */
                esc_html__( 'Only applied to scenes with .glb files. Apple devices automatically switch placement methods when a model is dragged onto a wall or floor. %1$s Learn more %2$s', 'pausar-3d-ar-for-elementor' ),
                '<a href="https://www.pausarstudio.de/wordpress-elementor/documentation/" target="_blank" rel="noopener">',
                '</a>'
             ),
        ] );
        $this->end_controls_section();
        //-- Scene Preview Section
        $this->start_controls_section( 'preview_section', [
            'label' => esc_html__( 'Scene Preview', 'pausar-3d-ar-for-elementor' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );
        $this->add_control( 'preview_toggle', [
            'label'        => esc_html__( 'Disable Preview', 'pausar-3d-ar-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'On', 'pausar-3d-ar-for-elementor' ),
            'label_off'    => esc_html__( 'Off', 'pausar-3d-ar-for-elementor' ),
            'return_value' => 'true',
            'default'      => 'false',
            'description'  => esc_html__( 'Disable the rendering of the 2D/3D preview of the AR scene on the webpage.', 'pausar-3d-ar-for-elementor' ),
            'classes'      => 'pausAR-UI-elementorDisabled',
        ] );
        $this->add_control( 'hrPreviewToggle', [
            'type' => \Elementor\Controls_Manager::DIVIDER,
        ] );
        $this->add_control( 'preview_align', [
            'label'       => esc_html__( 'Button Position', 'pausar-3d-ar-for-elementor' ),
            'type'        => \Elementor\Controls_Manager::CHOOSE,
            'options'     => [
                'bottom' => [
                    'title' => esc_html__( 'Top', 'pausar-3d-ar-for-elementor' ),
                    'icon'  => 'eicon-v-align-top',
                ],
                'top'    => [
                    'title' => esc_html__( 'Bottom', 'pausar-3d-ar-for-elementor' ),
                    'icon'  => 'eicon-v-align-bottom',
                ],
            ],
            'default'     => 'bottom',
            'toggle'      => false,
            'label_block' => false,
        ] );
        $this->add_control( 'preview_mode', [
            'label'       => esc_html__( 'Preview Mode', 'pausar-3d-ar-for-elementor' ),
            'type'        => \Elementor\Controls_Manager::CHOOSE,
            'options'     => [
                '3D' => [
                    'title' => esc_html__( '3D', 'pausar-3d-ar-for-elementor' ),
                    'icon'  => 'eicon-video-camera',
                ],
                '2D' => [
                    'title' => esc_html__( '2D', 'pausar-3d-ar-for-elementor' ),
                    'icon'  => 'eicon-image-bold',
                ],
            ],
            'default'     => '3D',
            'toggle'      => false,
            'label_block' => true,
            'classes'     => 'pausAR-UI-elementorDisabled',
        ] );
        $this->add_control( 'button_toggle', [
            'label'        => esc_html__( 'Hide Button', 'pausar-3d-ar-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'On', 'pausar-3d-ar-for-elementor' ),
            'label_off'    => esc_html__( 'Off', 'pausar-3d-ar-for-elementor' ),
            'return_value' => 'true',
            'default'      => 'false',
            'classes'      => 'pausAR-UI-elementorDisabled',
        ] );
        $this->add_control( 'preview_animation_toggle', [
            'label'        => esc_html__( 'Play Animation', 'pausar-3d-ar-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Loop', 'pausar-3d-ar-for-elementor' ),
            'label_off'    => esc_html__( 'Off', 'pausar-3d-ar-for-elementor' ),
            'return_value' => 'true',
            'default'      => 'false',
            'classes'      => 'pausAR-UI-elementorDisabled',
        ] );
        $this->add_control( 'allow_fullscreen_toggle', [
            'label'        => esc_html__( 'Allow fullscreen', 'pausar-3d-ar-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'On', 'pausar-3d-ar-for-elementor' ),
            'label_off'    => esc_html__( 'Off', 'pausar-3d-ar-for-elementor' ),
            'return_value' => 'true',
            'default'      => 'false',
            'description'  => esc_html__( 'Not available when using iframe mode', 'pausar-3d-ar-for-elementor' ),
        ] );
        $this->add_control( 'preview_loading_type', [
            'label'   => esc_html__( 'Loading Type', 'pausar-3d-ar-for-elementor' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'lazy',
            'options' => [
                'lazy'  => esc_html__( 'Lazy', 'pausar-3d-ar-for-elementor' ),
                'eager' => esc_html__( 'Eager', 'pausar-3d-ar-for-elementor' ),
            ],
        ] );
        $this->add_control( 'preview_media_poster', [
            'label'       => esc_html__( 'Poster', 'pausar-3d-ar-for-elementor' ),
            'type'        => \Elementor\Controls_Manager::MEDIA,
            'label_block' => false,
            'separator'   => 'before',
            'default'     => [
                'url' => '',
            ],
        ] );
        $this->add_control( 'preview_media_poster_generate', [
            'label'       => esc_html__( 'Generate Poster', 'pausar-3d-ar-for-elementor' ),
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'label_block' => false,
            'separator'   => 'after',
            'button_type' => 'default',
            'text'        => esc_html__( 'Generate', 'pausar-3d-ar-for-elementor' ),
            'event'       => 'pausAR:editor:generatePreviewPosterStarter',
            'description' => esc_html__( 'Generates an image of the current view, to use as the poster', 'pausar-3d-ar-for-elementor' ),
        ] );
        $this->add_control( 'camera_orbit_popover', [
            'label'        => esc_html__( 'Camera Orientation', 'pausar-3d-ar-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'label_off'    => esc_html__( 'Default', 'pausar-3d-ar-for-elementor' ),
            'label_on'     => esc_html__( 'Custom', 'pausar-3d-ar-for-elementor' ),
            'return_value' => 'active',
            'default'      => 'inactive',
        ] );
        $this->start_popover();
        $this->add_control( 'camera_orbit_theta', [
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'label'      => esc_html__( 'Theta (degree)', 'pausar-3d-ar-for-elementor' ),
            'range'      => [
                'deg' => [
                    'min' => 0,
                    'max' => 360,
                ],
            ],
            'default'    => [
                'size' => 0,
                'unit' => 'deg',
            ],
            'size_units' => ['deg'],
            'conditions' => [
                'relation' => 'and',
                'terms'    => [[
                    'name'     => 'camera_orbit_popover',
                    'operator' => '==',
                    'value'    => 'active',
                ]],
            ],
        ] );
        $this->add_control( 'camera_orbit_phi', [
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'label'      => esc_html__( 'Phi (degree)', 'pausar-3d-ar-for-elementor' ),
            'range'      => [
                'deg' => [
                    'min'  => 22.5,
                    'max'  => 157.5,
                    'step' => 1,
                ],
            ],
            'default'    => [
                'size' => 75,
                'unit' => 'deg',
            ],
            'size_units' => ['deg'],
            'conditions' => [
                'relation' => 'and',
                'terms'    => [[
                    'name'     => 'camera_orbit_popover',
                    'operator' => '==',
                    'value'    => 'active',
                ]],
            ],
        ] );
        $this->add_control( 'camera_orbit_radius', [
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'label'      => esc_html__( 'Radius', 'pausar-3d-ar-for-elementor' ),
            'separator'  => 'after',
            'range'      => [
                '%' => [
                    'min' => 55,
                    'max' => 105,
                ],
            ],
            'default'    => [
                'size' => 105,
                'unit' => '%',
            ],
            'size_units' => ['%'],
            'conditions' => [
                'relation' => 'and',
                'terms'    => [[
                    'name'     => 'camera_orbit_popover',
                    'operator' => '==',
                    'value'    => 'active',
                ]],
            ],
        ] );
        $this->end_popover();
        $this->add_control( 'camera_mode', [
            'label'       => esc_html__( 'Camera Control', 'pausar-3d-ar-for-elementor' ),
            'type'        => \Elementor\Controls_Manager::CHOOSE,
            'options'     => [
                'none'   => [
                    'title' => esc_html__( 'No Control', 'pausar-3d-ar-for-elementor' ),
                    'icon'  => 'eicon-ban',
                ],
                'touch'  => [
                    'title' => esc_html__( 'Touch', 'pausar-3d-ar-for-elementor' ),
                    'icon'  => 'eicon-cursor-move',
                ],
                'scroll' => [
                    'title' => esc_html__( 'Scroll', 'pausar-3d-ar-for-elementor' ),
                    'icon'  => 'eicon-scroll',
                ],
            ],
            'default'     => 'touch',
            'toggle'      => false,
            'label_block' => true,
        ] );
        $this->add_control( 'toggle_cursor_swivel', [
            'label'        => esc_html__( 'Cursor Swivel', 'pausar-3d-ar-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'On', 'pausar-3d-ar-for-elementor' ),
            'label_off'    => esc_html__( 'Off', 'pausar-3d-ar-for-elementor' ),
            'return_value' => 'true',
            'default'      => 'false',
            'conditions'   => [
                'relation' => 'and',
                'terms'    => [[
                    'relation' => 'or',
                    'terms'    => [[
                        'name'     => 'camera_mode',
                        'operator' => '==',
                        'value'    => 'none',
                    ], [
                        'name'     => 'camera_mode',
                        'operator' => '==',
                        'value'    => 'touch',
                    ]],
                ]],
            ],
            'description'  => esc_html__( 'No preview in edit-mode', 'pausar-3d-ar-for-elementor' ),
            'classes'      => 'pausAR-UI-elementorDisabled',
        ] );
        $this->add_control( 'toggle_preview_zoom', [
            'label'        => esc_html__( 'Zoom', 'pausar-3d-ar-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'On', 'pausar-3d-ar-for-elementor' ),
            'label_off'    => esc_html__( 'Off', 'pausar-3d-ar-for-elementor' ),
            'return_value' => 'true',
            'default'      => 'true',
            'conditions'   => [
                'relation' => 'and',
                'terms'    => [[
                    'name'     => 'camera_mode',
                    'operator' => '==',
                    'value'    => 'touch',
                ]],
            ],
        ] );
        $this->add_control( 'toggle_pan_scroll', [
            'label'        => esc_html__( 'Disable panning while scrolling', 'pausar-3d-ar-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'On', 'pausar-3d-ar-for-elementor' ),
            'label_off'    => esc_html__( 'Off', 'pausar-3d-ar-for-elementor' ),
            'return_value' => 'true',
            'default'      => 'false',
            'description'  => esc_html__( 'No preview in edit-mode', 'pausar-3d-ar-for-elementor' ),
            'conditions'   => [
                'relation' => 'and',
                'terms'    => [[
                    'name'     => 'preview_mode',
                    'operator' => '==',
                    'value'    => '3D',
                ], [
                    'relation' => 'or',
                    'terms'    => [[
                        'name'     => 'camera_mode',
                        'operator' => '==',
                        'value'    => 'touch',
                    ]],
                ], [
                    'name'     => 'preview_toggle',
                    'operator' => '!=',
                    'value'    => 'true',
                ]],
            ],
        ] );
        $this->add_control( 'toggle_pan', [
            'label'        => esc_html__( 'Disable side panning', 'pausar-3d-ar-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'On', 'pausar-3d-ar-for-elementor' ),
            'label_off'    => esc_html__( 'Off', 'pausar-3d-ar-for-elementor' ),
            'return_value' => 'true',
            'default'      => 'false',
            'conditions'   => [
                'relation' => 'and',
                'terms'    => [[
                    'name'     => 'preview_mode',
                    'operator' => '==',
                    'value'    => '3D',
                ], [
                    'relation' => 'or',
                    'terms'    => [[
                        'name'     => 'camera_mode',
                        'operator' => '==',
                        'value'    => 'touch',
                    ]],
                ], [
                    'name'     => 'preview_toggle',
                    'operator' => '!=',
                    'value'    => 'true',
                ]],
            ],
        ] );
        $this->add_control( 'toggle_tap', [
            'label'        => esc_html__( 'Disable camera reset', 'pausar-3d-ar-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'On', 'pausar-3d-ar-for-elementor' ),
            'label_off'    => esc_html__( 'Off', 'pausar-3d-ar-for-elementor' ),
            'return_value' => 'true',
            'default'      => 'false',
            'conditions'   => [
                'relation' => 'and',
                'terms'    => [[
                    'name'     => 'preview_mode',
                    'operator' => '==',
                    'value'    => '3D',
                ], [
                    'relation' => 'or',
                    'terms'    => [[
                        'name'     => 'camera_mode',
                        'operator' => '==',
                        'value'    => 'touch',
                    ]],
                ], [
                    'name'     => 'preview_toggle',
                    'operator' => '!=',
                    'value'    => 'true',
                ]],
            ],
        ] );
        $this->add_control( 'toggle_preview_rotation', [
            'label'        => esc_html__( 'Auto Rotation', 'pausar-3d-ar-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'On', 'pausar-3d-ar-for-elementor' ),
            'label_off'    => esc_html__( 'Off', 'pausar-3d-ar-for-elementor' ),
            'return_value' => 'true',
            'default'      => 'false',
            'conditions'   => [
                'relation' => 'and',
                'terms'    => [[
                    'name'     => 'camera_mode',
                    'operator' => '!=',
                    'value'    => 'scroll',
                ]],
            ],
        ] );
        $this->add_control( 'preview_rotation_delay', [
            'label'      => esc_html__( 'Rotation Delay', 'pausar-3d-ar-for-elementor' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'range'      => [
                's' => [
                    'min'  => 0,
                    'max'  => 10,
                    'step' => 0.01,
                ],
            ],
            'default'    => [
                'unit' => 's',
                'size' => 3,
            ],
            'size_units' => ['s'],
            'conditions' => [
                'relation' => 'and',
                'terms'    => [[
                    'name'     => 'camera_mode',
                    'operator' => '!=',
                    'value'    => 'scroll',
                ], [
                    'name'     => 'toggle_preview_rotation',
                    'operator' => '==',
                    'value'    => 'true',
                ]],
            ],
        ] );
        $this->add_control( 'toggle_show_prompt', [
            'label'        => esc_html__( 'Show Prompt', 'pausar-3d-ar-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'On', 'pausar-3d-ar-for-elementor' ),
            'label_off'    => esc_html__( 'Off', 'pausar-3d-ar-for-elementor' ),
            'return_value' => 'true',
            'default'      => 'true',
            'conditions'   => [
                'relation' => 'and',
                'terms'    => [[
                    'name'     => 'camera_mode',
                    'operator' => '==',
                    'value'    => 'touch',
                ]],
            ],
        ] );
        $this->add_control( 'toggle_prompt_style', [
            'label'      => esc_html__( 'Prompt Type', 'pausar-3d-ar-for-elementor' ),
            'type'       => \Elementor\Controls_Manager::SELECT,
            'default'    => 'wiggle',
            'options'    => [
                'basic'  => esc_html__( 'Basic', 'pausar-3d-ar-for-elementor' ),
                'wiggle' => esc_html__( 'Wiggle', 'pausar-3d-ar-for-elementor' ),
            ],
            'conditions' => [
                'relation' => 'and',
                'terms'    => [[
                    'name'     => 'toggle_show_prompt',
                    'operator' => '==',
                    'value'    => 'true',
                ], [
                    'name'     => 'camera_mode',
                    'operator' => '==',
                    'value'    => 'touch',
                ]],
            ],
        ] );
        $this->add_control( 'interaction-prompt-threshold', [
            'label'      => esc_html__( 'Prompt Threshold', 'pausar-3d-ar-for-elementor' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'range'      => [
                's' => [
                    'min'  => 0,
                    'max'  => 10,
                    'step' => 0.01,
                ],
            ],
            'default'    => [
                'unit' => 's',
                'size' => 3,
            ],
            'size_units' => ['s'],
            'conditions' => [
                'relation' => 'and',
                'terms'    => [[
                    'name'     => 'toggle_show_prompt',
                    'operator' => '==',
                    'value'    => 'true',
                ], [
                    'name'     => 'camera_mode',
                    'operator' => '==',
                    'value'    => 'touch',
                ]],
            ],
        ] );
        $this->add_control( 'dimensions_toggle', [
            'label'        => esc_html__( 'Show dimensions', 'pausar-3d-ar-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'On', 'pausar-3d-ar-for-elementor' ),
            'label_off'    => esc_html__( 'Off', 'pausar-3d-ar-for-elementor' ),
            'return_value' => 'true',
            'default'      => 'false',
            'conditions'   => [
                'relation' => 'and',
                'terms'    => [[
                    'name'     => 'camera_mode',
                    'operator' => '==',
                    'value'    => 'touch',
                ]],
            ],
            'classes'      => 'pausAR-UI-elementorDisabled',
        ] );
        $this->add_control( 'preview_scroll_sensitivity', [
            'label'      => esc_html__( 'Rotation Sensitivity', 'pausar-3d-ar-for-elementor' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'range'      => [
                'deg' => [
                    'min'  => -100,
                    'max'  => 100,
                    'step' => 1,
                ],
            ],
            'default'    => [
                'unit' => 'deg',
                'size' => 50,
            ],
            'size_units' => ['deg'],
            'conditions' => [
                'relation' => 'and',
                'terms'    => [[
                    'name'     => 'camera_mode',
                    'operator' => '==',
                    'value'    => 'scroll',
                ]],
            ],
        ] );
        $this->end_controls_section();
        // Content Tab End
        // Style Tab Start
        $this->start_controls_section( 'style_section_button', [
            'label' => esc_html__( 'AR Button', 'pausar-3d-ar-for-elementor' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'typography',
            'selector' => '{{WRAPPER}} .pausAR-UI-ArButton',
            'label'    => esc_html__( 'Typography', 'pausar-3d-ar-for-elementor' ),
        ] );
        $this->add_group_control( \Elementor\Group_Control_Text_Shadow::get_type(), [
            'name'     => 'text_shadow',
            'selector' => '{{WRAPPER}} .pausAR-UI-ArButton',
            'label'    => esc_html__( 'Text Shadow', 'pausar-3d-ar-for-elementor' ),
        ] );
        $this->start_controls_tabs( 'button_style_tabs' );
        $this->start_controls_tab( 'button_style_normal', [
            'label' => esc_html__( 'Normal', 'pausar-3d-ar-for-elementor' ),
        ] );
        $this->add_control( 'button_text_color_normal', [
            'label'     => esc_html__( 'Text Color', 'pausar-3d-ar-for-elementor' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .pausAR-UI-ArButton' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
            'default'   => '',
            'global'    => [
                'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT,
            ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Background::get_type(), [
            'name'           => 'button_background_normal',
            'types'          => ['classic', 'gradient'],
            'exclude'        => ['image'],
            'selector'       => '{{WRAPPER}} .pausAR-UI-ArButton',
            'fields_options' => [
                'background' => [
                    'default' => 'classic',
                ],
                'color'      => [
                    'global' => [
                        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
                    ],
                ],
            ],
        ] );
        $this->end_controls_tab();
        $this->start_controls_tab( 'button_style_hover', [
            'label' => esc_html__( 'Hover', 'pausar-3d-ar-for-elementor' ),
        ] );
        $this->add_control( 'button_text_color_hover', [
            'label'     => esc_html__( 'Text Color', 'pausar-3d-ar-for-elementor' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                'body {{WRAPPER}} .pausAR-UI-ArButton:hover, body {{WRAPPER}} .pausAR-UI-ArButton:focus' => 'color: {{VALUE}}; fill: {{VALUE}};',
            ],
            'default'   => '',
        ] );
        $this->add_group_control( \Elementor\Group_Control_Background::get_type(), [
            'name'           => 'button_background_hover',
            'types'          => ['classic', 'gradient'],
            'exclude'        => ['image'],
            'selector'       => '{{WRAPPER}} .pausAR-UI-ArButton:hover, {{WRAPPER}} .pausAR-UI-ArButton:focus',
            'fields_options' => [
                'background' => [
                    'default' => 'classic',
                ],
                'color'      => [
                    'global' => [],
                ],
            ],
        ] );
        $this->add_control( 'button_border_color_hover', [
            'label'     => esc_html__( 'Border Color', 'pausar-3d-ar-for-elementor' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                'body {{WRAPPER}} .pausAR-UI-ArButton:hover, body {{WRAPPER}} .pausAR-UI-ArButton:focus' => 'border-color: {{VALUE}};',
            ],
            'default'   => '',
        ] );
        $this->add_control( 'hover_animation', [
            'label' => esc_html__( 'Hover Animation', 'pausar-3d-ar-for-elementor' ),
            'type'  => \Elementor\Controls_Manager::HOVER_ANIMATION,
        ] );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_group_control( \Elementor\Group_Control_Border::get_type(), [
            'name'      => 'border',
            'selector'  => '{{WRAPPER}} .pausAR-UI-ArButton',
            'separator' => 'before',
        ] );
        $this->add_responsive_control( 'border_radius', [
            'label'      => esc_html__( 'Border Radius', 'pausar-3d-ar-for-elementor' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .pausAR-UI-ArButton' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'ar_button_shadow',
            'selector' => '{{WRAPPER}} .pausAR-UI-ArButton',
            'label'    => esc_html__( 'Box Shadow', 'pausar-3d-ar-for-elementor' ),
        ] );
        $this->add_responsive_control( 'text_padding', [
            'label'      => esc_html__( 'Padding', 'pausar-3d-ar-for-elementor' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors'  => [
                '{{WRAPPER}} .pausAR-UI-ArButton' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'separator'  => 'before',
        ] );
        $this->end_controls_section();
        $this->start_controls_section( 'style_section_preview', [
            'label' => esc_html__( 'Scene Preview', 'pausar-3d-ar-for-elementor' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_responsive_control( 'preview_margin', [
            'type'           => \Elementor\Controls_Manager::SLIDER,
            'label'          => esc_html__( 'Margin', 'pausar-3d-ar-for-elementor' ),
            'range'          => [
                'px'  => [
                    'min' => 0,
                    'max' => 100,
                ],
                '%'   => [
                    'min' => 0,
                    'max' => 100,
                ],
                'em'  => [
                    'min' => 0.1,
                    'max' => 10,
                ],
                'rem' => [
                    'min' => 0.1,
                    'max' => 10,
                ],
            ],
            'devices'        => ['desktop', 'tablet', 'mobile'],
            'default'        => [
                'size' => 15,
                'unit' => 'px',
            ],
            'tablet_default' => [
                'size' => 15,
                'unit' => 'px',
            ],
            'mobile_default' => [
                'size' => 15,
                'unit' => 'px',
            ],
            'size_units'     => [
                'px',
                '%',
                'em',
                'rem'
            ],
            'classes'        => 'pausAR-UI-elementorDisabled',
        ] );
        $this->add_responsive_control( 'preview_height', [
            'type'           => \Elementor\Controls_Manager::SLIDER,
            'label'          => esc_html__( 'Height', 'pausar-3d-ar-for-elementor' ),
            'range'          => [
                'px' => [
                    'min' => 200,
                    'max' => 1200,
                ],
                'cm' => [
                    'min' => 5.5,
                    'max' => 30,
                ],
                'in' => [
                    'min' => 2.16,
                    'max' => 11.81,
                ],
            ],
            'devices'        => ['desktop', 'tablet', 'mobile'],
            'default'        => [
                'unit' => 'px',
                'size' => 500,
            ],
            'tablet_default' => [
                'unit' => 'px',
                'size' => 500,
            ],
            'mobile_default' => [
                'unit' => 'px',
                'size' => 275,
            ],
            'size_units'     => ['px', 'cm', 'in'],
            'selectors'      => [
                '{{WRAPPER}} .pausAR-UI-modelPreviewContainer' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ] );
        $this->add_control( 'hrAfterPreviewMargin', [
            'type' => \Elementor\Controls_Manager::DIVIDER,
        ] );
        $this->add_control( 'toggle_preview_shadow', [
            'label'        => esc_html__( 'Show Shadow', 'pausar-3d-ar-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'On', 'pausar-3d-ar-for-elementor' ),
            'label_off'    => esc_html__( 'Off', 'pausar-3d-ar-for-elementor' ),
            'return_value' => 'true',
            'default'      => 'true',
            'classes'      => 'pausAR-UI-elementorDisabled',
        ] );
        $this->add_control( 'shadowStyleDivider', [
            'type' => \Elementor\Controls_Manager::DIVIDER,
        ] );
        $this->add_control( 'toggle_preview_background', [
            'label'        => esc_html__( 'Background', 'pausar-3d-ar-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'On', 'pausar-3d-ar-for-elementor' ),
            'label_off'    => esc_html__( 'Off', 'pausar-3d-ar-for-elementor' ),
            'return_value' => 'true',
            'default'      => 'false',
            'classes'      => 'pausAR-UI-elementorDisabled',
        ] );
        $this->add_control( 'backgroundStyleDivider', [
            'type' => \Elementor\Controls_Manager::DIVIDER,
        ] );
        $this->add_responsive_control( 'preview_border_radius', [
            'label'      => esc_html__( 'Border Radius', 'pausar-3d-ar-for-elementor' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'classes'    => 'pausAR-UI-elementorDisabled',
        ] );
        $this->end_controls_section();
        // Style Tab End
        $this->start_controls_section( 'misc_section', [
            'label' => esc_html__( 'Misc Settings', 'pausar-3d-ar-for-elementor' ),
            'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
        ] );
        $this->add_control( 'paus_anchor_link', [
            'label'       => esc_html__( 'Custom Anchor', 'pausar-3d-ar-for-elementor' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'placeholder' => '',
            'default'     => '',
            'label_block' => false,
            'show_label'  => true,
            'description' => esc_html__( 'A custom anchor link added to the popup\'s QR code. Only visible on desktop devices.', 'pausar-3d-ar-for-elementor' ),
            'classes'     => 'pausAR-UI-elementorDisabled',
        ] );
        $this->add_control( 'autostart_toggle', [
            'label'        => esc_html__( 'Autostart AR with QR Code', 'pausar-3d-ar-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'On', 'pausar-3d-ar-for-elementor' ),
            'label_off'    => esc_html__( 'Off', 'pausar-3d-ar-for-elementor' ),
            'return_value' => 'true',
            'default'      => 'false',
            'classes'      => 'pausAR-UI-elementorDisabled',
        ] );
        $this->add_control( 'hrQRCode', [
            'type' => \Elementor\Controls_Manager::DIVIDER,
        ] );
        $this->add_control( 'watermark_toggle', [
            'label'        => esc_html__( 'Hide Watermark', 'pausar-3d-ar-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'On', 'pausar-3d-ar-for-elementor' ),
            'label_off'    => esc_html__( 'Off', 'pausar-3d-ar-for-elementor' ),
            'return_value' => 'true',
            'default'      => 'false',
            'classes'      => 'pausAR-UI-elementorDisabled',
        ] );
        $this->add_control( 'hrWatermark', [
            'type' => \Elementor\Controls_Manager::DIVIDER,
        ] );
        $this->add_control( 'iframe_toggle', [
            'label'        => esc_html__( 'Iframe mode', 'pausar-3d-ar-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'On', 'pausar-3d-ar-for-elementor' ),
            'label_off'    => esc_html__( 'Off', 'pausar-3d-ar-for-elementor' ),
            'return_value' => 'true',
            'default'      => 'false',
            'classes'      => 'pausAR-UI-elementorDisabled',
            'description'  => sprintf( 
                /* translators: 1: Preview Link with open tag, 2: Link close tag. */
                esc_html__( 'Changes the function of the popup and forces the widget into full screen mode. (visible on live page or %1$s preview mode %2$s).', 'pausar-3d-ar-for-elementor' ),
                '<a href="javascript: $e.run( \'panel/close\' )">',
                '</a>'
             ),
        ] );
        $this->end_controls_section();
    }

    //==================================================================
    // * + * + * + * + * + * + * + * + * + * + * + * + * + * + * + * + *
    // End of Control-Section  * + * + * + * + * + * + * + * + * + * + *
    // * + * + * + * + * + * + * + * + * + * + * + * + * + * + * + * + *
    //==================================================================
    //-------------------------------
    //Render-Conditions
    //-------------------------------
    /**
     * Calculates, if the preview-content (Image or Model-Viewer) is displayed underneath or atop of the button. Top-align is the default.
     *
     * @param [type] $settings
     * @return void
     */
    protected function getArButtonPosition( $settings ) {
        if ( !isset( $settings ) ) {
            return false;
        }
        if ( isset( $settings['preview_align'] ) ) {
            if ( $settings['preview_align'] == 'bottom' ) {
                return true;
            } else {
                if ( $settings['preview_align'] == 'top' ) {
                    return false;
                }
            }
        }
        return true;
    }

    protected function hideButton( $settings ) {
        if ( !isset( $settings ) ) {
            return false;
        }
        return false;
    }

    protected function render_widgetContainer( $settings, &$containerOptions = null ) {
        if ( !isset( $settings ) ) {
            return false;
        }
        if ( !is_array( $containerOptions ) ) {
            //$modelViewerOptions
            return false;
        }
        //---
        $containerOptions['class'] = ['pausAR-UI-widgetContainer', 'pausAR-UI-elementorViewerContainer'];
        $containerOptions['pausar'] = 'pausARID-widget-' . $this->get_id();
        $containerOptions['pausar_version'] = 'elementor|starter';
        $containerOptions['editmode'] = $this->isEditMode();
    }

    protected function render_CustomIcon( $settings ) {
        if ( !isset( $settings ) ) {
            return -1;
        }
        return 0;
    }

    protected function render_Watermark( $settings ) {
        if ( !isset( $settings ) ) {
            return true;
        }
        return true;
    }

    protected function render_SceneUI( $settings, $control = null ) {
        if ( !isset( $settings ) ) {
            return '';
        }
        if ( gettype( $control ) !== 'string' ) {
            return '';
        }
        switch ( $control ) {
            case 'scene_title':
                return rawurlencode( 'WebAR by PausAR Studio' );
                break;
            case 'scene_link':
                return rawurlencode( 'https://www.pausarstudio.de/wordpress-elementor/' );
                break;
            case 'scene_description':
                return rawurlencode( 'Discover PausAR Viewer Pro' );
                break;
            case 'scene_cta':
                return rawurlencode( 'Visit' );
                break;
            default:
                return '';
                break;
        }
    }

    protected function render_Shadow( $settings, &$modelViewerOptions = null ) {
        if ( !isset( $settings ) ) {
            return false;
        }
        if ( $modelViewerOptions == null ) {
            //$modelViewerOptions
            return false;
        }
        //---
        $modelViewerOptions['shadow-intensity'] = 0;
    }

    protected function render_Background( $settings, &$modelViewerOptions = null ) {
        if ( !isset( $settings ) ) {
            return false;
        }
        if ( $modelViewerOptions == null ) {
            //$modelViewerOptions
            return false;
        }
    }

    protected function render_PreviewInlineStyle( $settings, &$modelViewerOptions = null ) {
        if ( !isset( $settings ) ) {
            return false;
        }
        if ( $modelViewerOptions == null ) {
            return false;
        }
        if ( $this->displayPreview( $settings ) != 0 ) {
            return;
        }
        //---
        $inlineStyle = "";
        if ( gettype( $inlineStyle ) === 'string' && $inlineStyle != "" ) {
            $modelViewerOptions['style'] = $inlineStyle;
        }
    }

    protected function render_Swivel( $settings, &$modelViewerOptions = null ) {
        if ( !isset( $settings ) ) {
            return false;
        }
        if ( $modelViewerOptions == null ) {
            //$modelViewerOptions
            return false;
        }
    }

    protected function render_Dimensions( $settings, &$modelViewerOptions = null ) {
        if ( !isset( $settings ) ) {
            return false;
        }
        return false;
    }

    protected function render_Animation( $settings, &$modelViewerOptions = null ) {
        if ( !isset( $settings ) ) {
            return false;
        }
        if ( $modelViewerOptions == null ) {
            //$modelViewerOptions
            return false;
        }
    }

    protected function render_Zoom( $settings, &$modelViewerOptions = null ) {
        if ( !isset( $settings ) ) {
            return false;
        }
        if ( $modelViewerOptions == null ) {
            //$modelViewerOptions
            return false;
        }
        //---
        if ( isset( $settings['toggle_preview_zoom'] ) ) {
            if ( $settings['toggle_preview_zoom'] !== 'true' && $settings['toggle_preview_zoom'] !== true ) {
                $modelViewerOptions['disable-zoom'] = null;
            }
        }
    }

    protected function render_Scaling( $settings, &$arButtonOptions = null ) {
        if ( !isset( $settings ) ) {
            return false;
        }
        if ( $arButtonOptions == null ) {
            //$arButtonOptions
            return false;
        }
        //---
        $arButtonOptions['paus_scene_resizable'] = 'true';
    }

    protected function render_Iframe( $settings, &$arButtonOptions = null ) {
        if ( !isset( $settings ) ) {
            return false;
        }
        if ( $arButtonOptions == null ) {
            //$arButtonOptions
            return false;
        }
    }

    protected function render_QRAutostart( $settings, &$arButtonOptions = null ) {
        if ( !isset( $settings ) ) {
            return false;
        }
        if ( $arButtonOptions == null ) {
            //$arButtonOptions
            return false;
        }
    }

    protected function render_CustomAnchor( $settings, &$arButtonOptions = null ) {
        if ( !isset( $settings ) ) {
            return false;
        }
        if ( $arButtonOptions == null ) {
            //$arButtonOptions
            return false;
        }
    }

    protected function render_SceneSound( $settings, &$arButtonOptions = null ) {
        if ( !isset( $settings ) ) {
            return false;
        }
        if ( $arButtonOptions == null ) {
            //$arButtonOptions
            return false;
        }
    }

    protected function render_Fullscreen( $settings ) {
        if ( !isset( $settings ) ) {
            return false;
        }
        //---
        if ( $this->displayPreview( $settings ) == 0 ) {
            if ( isset( $settings['allow_fullscreen_toggle'] ) ) {
                if ( $settings['allow_fullscreen_toggle'] === 'true' || $settings['allow_fullscreen_toggle'] === true ) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Returns the calculated display-type for the content-preview. 
     *
     * @param [type] $settings
     * @return [number] -1 = No preview; 0 = Model-Viewer; 1 = Image;
     */
    protected function displayPreview( $settings ) {
        if ( !isset( $settings ) ) {
            return -1;
        }
        //GLB exists?---
        if ( isset( $settings['android_glb_file'] ) ) {
            if ( isset( $settings['android_glb_file']['url'] ) ) {
                if ( is_string( $settings['android_glb_file']['url'] ) ) {
                    if ( !empty( $settings['android_glb_file']['url'] ) ) {
                        return 0;
                    }
                }
            }
        }
        return -1;
    }

    protected function isEditMode() {
        return ( \Elementor\Plugin::$instance->editor->is_edit_mode() ? 'true' : 'false' );
    }

    protected function validateCameraOrientation( $settings ) {
        if ( !isset( $settings ) ) {
            return -1;
        }
        $cameraOrientation = [
            'camera_orbit_theta'  => 0,
            'camera_orbit_phi'    => 75,
            'camera_orbit_radius' => 105,
        ];
        if ( isset( $settings['camera_orbit_popover'] ) ) {
            if ( $settings['camera_orbit_popover'] == 'active' ) {
                if ( isset( $settings['camera_orbit_theta'] ) ) {
                    if ( isset( $settings['camera_orbit_theta']['size'] ) ) {
                        if ( is_numeric( $settings['camera_orbit_theta']['size'] ) ) {
                            $cameraOrientation['camera_orbit_theta'] = (float) $settings['camera_orbit_theta']['size'];
                        }
                    }
                }
                if ( isset( $settings['camera_orbit_phi'] ) ) {
                    if ( isset( $settings['camera_orbit_phi']['size'] ) ) {
                        if ( is_numeric( $settings['camera_orbit_phi']['size'] ) ) {
                            $cameraOrientation['camera_orbit_phi'] = (float) $settings['camera_orbit_phi']['size'];
                        }
                    }
                }
                if ( isset( $settings['camera_orbit_radius'] ) ) {
                    if ( isset( $settings['camera_orbit_radius']['size'] ) ) {
                        if ( is_numeric( $settings['camera_orbit_radius']['size'] ) ) {
                            $cameraOrientation['camera_orbit_radius'] = (float) $settings['camera_orbit_radius']['size'];
                        }
                    }
                }
            }
        }
        return $cameraOrientation;
    }

    protected function isImageSizeLimitReached( $url = null, $maxX = null, $maxY = null ) {
        if ( gettype( $url ) != 'string' ) {
            return false;
        }
        if ( $url == "" ) {
            return false;
        }
        //
        $maxXLimit = $this->maxSkyboxSize['width'];
        //Default
        $maxYLimit = $this->maxSkyboxSize['height'];
        //Default
        if ( gettype( $maxX ) === 'integer' ) {
            if ( $maxX > 0 ) {
                $maxXLimit = $maxX;
            }
        }
        if ( gettype( $maxY ) === 'integer' ) {
            if ( $maxY > 0 ) {
                $maxYLimit = $maxY;
            }
        }
        //
        $calculatedSize = false;
        try {
            $calculatedSize = wp_getimagesize( $url );
        } catch ( \Throwable $th ) {
            return false;
        }
        if ( $calculatedSize != false ) {
            if ( $calculatedSize[0] * $calculatedSize[1] > $maxXLimit * $maxYLimit ) {
                return true;
            }
        }
        return false;
    }

    //-----------------------------------------
    protected function render() {
        $settings = $this->get_settings_for_display();
        $containerOptions = [];
        $this->render_widgetContainer( $settings, $containerOptions );
        $this->add_render_attribute( 'widget-container', $containerOptions );
        //---ModelViewer
        $modelViewerOptions = [
            'pausar'         => 'pausARID-widget-' . $this->get_id(),
            'pausar_version' => 'elementor|starter',
            'class'          => ['pausAR-UI-modelViewer', 'pausAR-UI-elementorModelViewer'],
            'src'            => ( isset( $settings['android_glb_file']['url'] ) ? $settings['android_glb_file']['url'] : '' ),
            'poster'         => ( !empty( $settings['preview_media_poster'] ) ? ( !empty( $settings['preview_media_poster']['url'] ) ? $settings['preview_media_poster']['url'] : '' ) : '' ),
        ];
        if ( $this->displayPreview( $settings ) == 0 ) {
            $this->render_Animation( $settings, $modelViewerOptions );
            if ( isset( $settings['camera_mode'] ) ) {
                switch ( $settings['camera_mode'] ) {
                    case 'none':
                        //Rotation
                        if ( isset( $settings['toggle_preview_rotation'] ) ) {
                            if ( $settings['toggle_preview_rotation'] === 'true' || $settings['toggle_preview_rotation'] === true ) {
                                $modelViewerOptions['auto-rotate'] = null;
                                if ( isset( $settings['preview_rotation_delay'] ) ) {
                                    if ( isset( $settings['preview_rotation_delay']['size'] ) ) {
                                        if ( is_numeric( $settings['preview_rotation_delay']['size'] ) ) {
                                            $modelViewerOptions['auto-rotate-delay'] = $settings['preview_rotation_delay']['size'] * 1000;
                                        }
                                    }
                                }
                            }
                        }
                        //Swivel
                        $this->render_Swivel( $settings, $modelViewerOptions );
                        //StartPos.
                        if ( isset( $settings['camera_orbit_popover'] ) ) {
                            if ( $settings['camera_orbit_popover'] == 'active' ) {
                                if ( isset( $settings['camera_orbit_theta'] ) && isset( $settings['camera_orbit_phi'] ) && isset( $settings['camera_orbit_radius'] ) ) {
                                    $validatedCamera = $this->validateCameraOrientation( $settings );
                                    $modelViewerOptions['camera-orbit'] = $validatedCamera['camera_orbit_theta'] . $settings['camera_orbit_theta']['unit'] . ' ' . $validatedCamera['camera_orbit_phi'] . $settings['camera_orbit_phi']['unit'] . ' ' . $validatedCamera['camera_orbit_radius'] . $settings['camera_orbit_radius']['unit'];
                                    //$modelViewerOptions['camera-orbit'] = $settings['camera_orbit_theta']['size'] . $settings['camera_orbit_theta']['unit'] . ' ' . $settings['camera_orbit_phi']['size'] . $settings['camera_orbit_phi']['unit'] . ' ' . $settings['camera_orbit_radius']['size'] . $settings['camera_orbit_radius']['unit'];
                                }
                            }
                        }
                        break;
                    case 'touch':
                        //Camera
                        $modelViewerOptions['camera-controls'] = null;
                        //Prompt
                        if ( isset( $settings['toggle_show_prompt'] ) ) {
                            if ( ($settings['toggle_show_prompt'] === 'true' || $settings['toggle_show_prompt'] === true) && isset( $settings['toggle_prompt_style'] ) ) {
                                if ( isset( $settings['interaction-prompt-threshold'] ) ) {
                                    if ( isset( $settings['interaction-prompt-threshold']['size'] ) ) {
                                        if ( is_numeric( $settings['interaction-prompt-threshold']['size'] ) ) {
                                            $modelViewerOptions['interaction-prompt-threshold'] = $settings['interaction-prompt-threshold']['size'] * 1000;
                                        }
                                    }
                                }
                                $modelViewerOptions['interaction-prompt'] = 'auto';
                                switch ( $settings['toggle_prompt_style'] ) {
                                    case 'wiggle':
                                        $modelViewerOptions['interaction-prompt-style'] = $settings['toggle_prompt_style'];
                                        break;
                                    case 'basic':
                                        $modelViewerOptions['interaction-prompt-style'] = $settings['toggle_prompt_style'];
                                        break;
                                    default:
                                        $modelViewerOptions['interaction-prompt-style'] = 'wiggle';
                                }
                            } else {
                                $modelViewerOptions['interaction-prompt'] = 'none';
                            }
                        }
                        //Zoom
                        $this->render_Zoom( $settings, $modelViewerOptions );
                        //Rotation
                        if ( isset( $settings['toggle_preview_rotation'] ) ) {
                            if ( $settings['toggle_preview_rotation'] === 'true' || $settings['toggle_preview_rotation'] === true ) {
                                $modelViewerOptions['auto-rotate'] = null;
                                if ( isset( $settings['preview_rotation_delay'] ) ) {
                                    if ( isset( $settings['preview_rotation_delay']['size'] ) ) {
                                        if ( is_numeric( $settings['preview_rotation_delay']['size'] ) ) {
                                            $modelViewerOptions['auto-rotate-delay'] = $settings['preview_rotation_delay']['size'] * 1000;
                                        }
                                    }
                                }
                            }
                        }
                        //Swivel
                        $this->render_Swivel( $settings, $modelViewerOptions );
                        //Dimensions
                        $this->render_Dimensions( $settings, $modelViewerOptions );
                        //StartPos.
                        if ( isset( $settings['camera_orbit_popover'] ) ) {
                            if ( $settings['camera_orbit_popover'] == 'active' ) {
                                if ( isset( $settings['camera_orbit_theta'] ) && isset( $settings['camera_orbit_phi'] ) && isset( $settings['camera_orbit_radius'] ) ) {
                                    $validatedCamera = $this->validateCameraOrientation( $settings );
                                    $modelViewerOptions['camera-orbit'] = $validatedCamera['camera_orbit_theta'] . $settings['camera_orbit_theta']['unit'] . ' ' . $validatedCamera['camera_orbit_phi'] . $settings['camera_orbit_phi']['unit'] . ' ' . $validatedCamera['camera_orbit_radius'] . $settings['camera_orbit_radius']['unit'];
                                    //$modelViewerOptions['camera-orbit'] = $settings['camera_orbit_theta']['size'] . $settings['camera_orbit_theta']['unit'] . ' ' . $settings['camera_orbit_phi']['size'] . $settings['camera_orbit_phi']['unit'] . ' ' . $settings['camera_orbit_radius']['size'] . $settings['camera_orbit_radius']['unit'];
                                }
                            }
                        }
                        //Pan
                        if ( isset( $settings['toggle_pan'] ) ) {
                            if ( $settings['toggle_pan'] === 'true' || $settings['toggle_pan'] === true ) {
                                $modelViewerOptions['disable-pan'] = null;
                            }
                        }
                        //Tap
                        if ( isset( $settings['toggle_tap'] ) ) {
                            if ( $settings['toggle_tap'] === 'true' || $settings['toggle_tap'] === true ) {
                                $modelViewerOptions['disable-tap'] = null;
                            }
                        }
                        if ( isset( $settings['toggle_pan_scroll'] ) && ($this->isEditMode() == 'false' || $this->isEditMode() == false) ) {
                            if ( $settings['toggle_pan_scroll'] === 'true' || $settings['toggle_pan_scroll'] === true ) {
                                $modelViewerOptions['touch-action'] = 'pan-y';
                            }
                        }
                        break;
                    case 'scroll':
                        //StartPos.
                        if ( isset( $settings['preview_scroll_sensitivity'] ) ) {
                            if ( isset( $settings['camera_orbit_popover'] ) ) {
                                if ( $settings['camera_orbit_popover'] == 'active' ) {
                                    if ( isset( $settings['camera_orbit_theta'] ) && isset( $settings['camera_orbit_phi'] ) && isset( $settings['camera_orbit_radius'] ) ) {
                                        $validatedCamera = $this->validateCameraOrientation( $settings );
                                        $modelViewerOptions['camera-orbit'] = 'calc(' . $validatedCamera['camera_orbit_theta'] . $settings['camera_orbit_theta']['unit'] . ' - env(window-scroll-y) * ' . (int) $settings['preview_scroll_sensitivity']['size'] * 5 . $settings['preview_scroll_sensitivity']['unit'] . ') ' . $validatedCamera['camera_orbit_phi'] . $settings['camera_orbit_phi']['unit'] . ' ' . $validatedCamera['camera_orbit_radius'] . $settings['camera_orbit_radius']['unit'];
                                        //$modelViewerOptions['camera-orbit'] = 'calc(' . $settings['camera_orbit_theta']['size'] . $settings['camera_orbit_theta']['unit'] . ' - env(window-scroll-y) * ' . ((int)($settings['preview_scroll_sensitivity']['size']) * 5) . $settings['preview_scroll_sensitivity']['unit'] . ') ' . $settings['camera_orbit_phi']['size'] . $settings['camera_orbit_phi']['unit'] . ' ' . $settings['camera_orbit_radius']['size'] . $settings['camera_orbit_radius']['unit'];
                                    }
                                } else {
                                    $modelViewerOptions['camera-orbit'] = 'calc(0deg - env(window-scroll-y) * ' . (int) $settings['preview_scroll_sensitivity']['size'] * 5 . $settings['preview_scroll_sensitivity']['unit'] . ') 75deg 105%';
                                }
                            } else {
                                $modelViewerOptions['camera-orbit'] = 'calc(0deg - env(window-scroll-y) * ' . (int) $settings['preview_scroll_sensitivity']['size'] * 5 . $settings['preview_scroll_sensitivity']['unit'] . ') 75deg 105%';
                            }
                        }
                        break;
                }
            }
            $this->render_Shadow( $settings, $modelViewerOptions );
            $this->render_Background( $settings, $modelViewerOptions );
            $this->render_PreviewInlineStyle( $settings, $modelViewerOptions );
            if ( isset( $settings['preview_loading_type'] ) ) {
                $modelViewerOptions['loading'] = $settings['preview_loading_type'];
            }
        }
        $this->add_render_attribute( 'model-viewer', $modelViewerOptions );
        //---AR Button (Settings)
        $arButtonOptions = [
            'class'                  => ['pausAR-UI-ArButton'],
            'id'                     => 'pausAR-' . $this->get_id(),
            'pausar'                 => 'pausARID-widget-' . $this->get_id(),
            'pausar_version'         => 'elementor|starter',
            'cm_system'              => 'wordpress|elementor',
            'editMode'               => $this->isEditMode(),
            'paus_file'              => null,
            'paus_glb'               => ( !empty( $settings['android_glb_file']['url'] ) ? $settings['android_glb_file']['url'] : null ),
            'paus_ios'               => ( !empty( $settings['apple_ios_file']['url'] ) ? $settings['apple_ios_file']['url'] : null ),
            'paus_scene_occlusion'   => ( !empty( $settings['scene_occlusion'] ) ? ( $settings['scene_occlusion'] === true || $settings['scene_occlusion'] === 'true' ? 'true' : 'false' ) : 'false' ),
            'paus_scene_placement'   => ( !empty( $settings['scene_placement'] ) ? ( $settings['scene_placement'] === 'vertical' ? 'vertical' : 'horizontal' ) : 'horizontal' ),
            'paus_scene_title'       => $this->render_SceneUI( $settings, 'scene_title' ),
            'paus_scene_link'        => $this->render_SceneUI( $settings, 'scene_link' ),
            'paus_scene_description' => $this->render_SceneUI( $settings, 'scene_description' ),
            'paus_scene_cta'         => $this->render_SceneUI( $settings, 'scene_cta' ),
        ];
        $this->render_Scaling( $settings, $arButtonOptions );
        $this->render_Iframe( $settings, $arButtonOptions );
        $this->render_QRAutostart( $settings, $arButtonOptions );
        $this->render_CustomAnchor( $settings, $arButtonOptions );
        $this->render_SceneSound( $settings, $arButtonOptions );
        if ( !empty( $settings['hover_animation'] ) ) {
            array_push( $arButtonOptions['class'], 'elementor-animation-' . $settings['hover_animation'] );
        }
        $this->add_render_attribute( 'ar_button', $arButtonOptions );
        $fullscreenButtonOptions = [
            'class'  => ['pausAR-UI-fullscreenButton', 'pausAR-UI-elementHide'],
            'pausar' => 'pausARID-widget-' . $this->get_id(),
        ];
        $this->add_render_attribute( 'fullscreen_button', $fullscreenButtonOptions );
        //--- Button Title (inline-editing)
        $this->add_render_attribute( 'button_title', [
            'class' => ['pausAR-UI-ArButtonLabel'],
        ] );
        $this->add_inline_editing_attributes( 'button_title', 'basic' );
        //--- Preview Image Src
        $this->add_render_attribute( 'preview_media_image_attribute', [
            'class' => ['pausAR-UI-previewImage'],
            'src'   => ( isset( $settings['preview_media_image'] ) ? ( !empty( $settings['preview_media_image']['url'] ) ? $settings['preview_media_image']['url'] : '' ) : '' ),
        ] );
        //--- AR Button Margin (for Preview)
        $arButtonMarginArgs = [
            'class' => ['pausAR-UI-buttonContainer'],
        ];
        if ( $this->displayPreview( $settings ) >= 0 && isset( $settings['preview_align'] ) ) {
            if ( $settings['preview_align'] == 'top' ) {
                array_push( $arButtonMarginArgs['class'], 'pausAR-UI-buttonContainerBottom' );
            } else {
                if ( $settings['preview_align'] == 'bottom' ) {
                    array_push( $arButtonMarginArgs['class'], 'pausAR-UI-buttonContainerTop' );
                }
            }
        }
        $this->add_render_attribute( 'button_margin', $arButtonMarginArgs );
        //--- Icon (Margin)
        $arButtonIconMarginArgs = [
            'class' => ['pausAR-UI-buttonIcon'],
        ];
        $this->add_render_attribute( 'button_icon', $arButtonIconMarginArgs );
        ?>
		<div <?php 
        echo $this->get_render_attribute_string( 'widget-container' );
        ?>>
		<?php 
        if ( $this->getArButtonPosition( $settings ) == true ) {
            ?>
			<?php 
            if ( $this->hideButton( $settings ) == false ) {
                ?>
			<div <?php 
                echo $this->get_render_attribute_string( 'button_margin' );
                ?>>
				<a <?php 
                echo $this->get_render_attribute_string( 'ar_button' );
                ?>>
					<span class="pausAR-UI-ArButtonWrapper">
						<?php 
                if ( $this->render_CustomIcon( $settings ) == 1 ) {
                    ?>
							<span <?php 
                    echo $this->get_render_attribute_string( 'button_icon' );
                    ?>><?php 
                    \Elementor\Icons_Manager::render_icon( $settings['icon'], [
                        'aria-hidden' => 'true',
                    ] );
                    ?></span>
						<?php 
                } elseif ( $this->render_CustomIcon( $settings ) == 0 ) {
                    ?>
							<span <?php 
                    echo $this->get_render_attribute_string( 'button_icon' );
                    ?>><?php 
                    echo $this->getPausAR_Logo_Inline();
                    ?></span>
						<?php 
                }
                //render_CustomIcon()
                ?>
						<span <?php 
                echo $this->get_render_attribute_string( 'button_title' );
                ?>><?php 
                echo $this->get_settings( 'button_title' );
                ?></span>
					</span>
				</a>
			</div>
			<?php 
            }
            //hideButton()
            ?>
		<?php 
        } elseif ( $this->displayPreview( $settings ) == 0 ) {
            ?>
			<?php 
            if ( $this->render_Watermark( $settings ) == true ) {
                ?>
			<div class="pausAR-UI-watermarkContainer">
				<?php 
                if ( $this->isEditMode() == 'false' || $this->isEditMode() == false ) {
                    ?>
					<a href="https://www.pausarstudio.de/wordpress-elementor/" target="_blank" rel="noopener" class="pausAR-UI-watermarkLink">
				<?php 
                } else {
                    ?>
					<a class="pausAR-UI-watermarkLink">
				<?php 
                }
                ?>
						<img class="pausAR-UI-watermark" src="<?php 
                echo esc_html( plugins_url( "../assets/watermark.svg", __DIR__ ) );
                ?>" alt="3D / AR Experience - made with PausAR" pausar_src_active="<?php 
                echo esc_html( plugins_url( "../assets/watermark.png", __DIR__ ) );
                ?>" pausar_src_static="<?php 
                echo esc_html( plugins_url( "../assets/watermark.svg", __DIR__ ) );
                ?>">
					</a>
			</div>
			<?php 
            }
            //render_Watermark()
            ?>
		<?php 
        }
        ?>
		<?php 
        if ( $this->displayPreview( $settings ) == 0 ) {
            ?>
			<div class="pausAR-UI-modelPreviewContainer">
				<model-viewer <?php 
            echo $this->get_render_attribute_string( 'model-viewer' );
            ?>>
					<?php 
            if ( $this->render_Dimensions( $settings ) == true ) {
                ?>
						<div slot="hotspot-dot+X-Y+Z" class="pausAR-UI-dimensionDot pausAR-UI-elementHide" data-position="1 -1 1" data-normal="1 0 0"></div>
						<div slot="hotspot-dim+X-Y" class="pausAR-UI-dimensionDim pausAR-UI-elementHide" data-position="1 -1 0" data-normal="1 0 0"></div>
						<div slot="hotspot-dot+X-Y-Z" class="pausAR-UI-dimensionDot pausAR-UI-elementHide" data-position="1 -1 -1" data-normal="1 0 0"></div>
						<div slot="hotspot-dim+X-Z" class="pausAR-UI-dimensionDim pausAR-UI-elementHide" data-position="1 0 -1" data-normal="1 0 0"></div>
						<div slot="hotspot-dot+X+Y-Z" class="pausAR-UI-dimensionDot pausAR-UI-elementHide" data-position="1 1 -1" data-normal="0 1 0"></div>
						<div slot="hotspot-dim+Y-Z" class="pausAR-UI-dimensionDim pausAR-UI-elementHide" data-position="0 -1 -1" data-normal="0 1 0"></div>
						<div slot="hotspot-dot-X+Y-Z" class="pausAR-UI-dimensionDot pausAR-UI-elementHide" data-position="-1 1 -1" data-normal="0 1 0"></div>
						<div slot="hotspot-dim-X-Z" class="pausAR-UI-dimensionDim pausAR-UI-elementHide" data-position="-1 0 -1" data-normal="-1 0 0"></div>
						<div slot="hotspot-dot-X-Y-Z" class="pausAR-UI-dimensionDot pausAR-UI-elementHide" data-position="-1 -1 -1" data-normal="-1 0 0"></div>
						<div slot="hotspot-dim-X-Y" class="pausAR-UI-dimensionDim pausAR-UI-elementHide" data-position="-1 -1 0" data-normal="-1 0 0"></div>
						<div slot="hotspot-dot-X-Y+Z" class="pausAR-UI-dimensionDot pausAR-UI-elementHide" data-position="-1 -1 1" data-normal="-1 0 0"></div>

						<svg id="pausAR-UI-dimLinesGraphic" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" class="pausAR-UI-dimensionLineContainer pausAR-UI-elementHide">
							<line class="pausAR-UI-dimensionLine"></line>
							<line class="pausAR-UI-dimensionLine"></line>
							<line class="pausAR-UI-dimensionLine"></line>
							<line class="pausAR-UI-dimensionLine"></line>
							<line class="pausAR-UI-dimensionLine"></line>
						</svg>
					<?php 
            }
            ?>
				</model-viewer>
				<?php 
            if ( $this->render_Fullscreen( $settings ) == true ) {
                ?>
					<div class="pausAR-UI-modelPreviewInterface">
						<button disabled <?php 
                echo $this->get_render_attribute_string( 'fullscreen_button' );
                ?>></button>
					</div>						
				<?php 
            }
            ?>
				
			</div>
		<?php 
        } elseif ( $this->displayPreview( $settings ) == 1 ) {
            ?>
			<div class="pausAR-UI-previewContainer">
				<img <?php 
            echo $this->get_render_attribute_string( 'preview_media_image_attribute' );
            ?>>
			</div>
		<?php 
        }
        ?>
		<?php 
        if ( $this->getArButtonPosition( $settings ) == false ) {
            ?>
			<?php 
            if ( $this->hideButton( $settings ) == false ) {
                ?>
			<div <?php 
                echo $this->get_render_attribute_string( 'button_margin' );
                ?>>
				<a <?php 
                echo $this->get_render_attribute_string( 'ar_button' );
                ?>>
					<span class="pausAR-UI-ArButtonWrapper">
					<?php 
                if ( $this->render_CustomIcon( $settings ) == 1 ) {
                    ?>
						<span <?php 
                    echo $this->get_render_attribute_string( 'button_icon' );
                    ?>><?php 
                    \Elementor\Icons_Manager::render_icon( $settings['icon'], [
                        'aria-hidden' => 'true',
                    ] );
                    ?></span>
					<?php 
                } elseif ( $this->render_CustomIcon( $settings ) == 0 ) {
                    ?>
						<span <?php 
                    echo $this->get_render_attribute_string( 'button_icon' );
                    ?>><?php 
                    echo $this->getPausAR_Logo_Inline();
                    ?></span>
					<?php 
                }
                //render_CustomIcon()
                ?>
						<span <?php 
                echo $this->get_render_attribute_string( 'button_title' );
                ?>><?php 
                echo $this->get_settings( 'button_title' );
                ?></span>
					</span>
				</a>
			</div>
			<?php 
            }
            //hideButton()
            ?>
		<?php 
        } elseif ( $this->displayPreview( $settings ) == 0 ) {
            ?>
			<?php 
            if ( $this->render_Watermark( $settings ) == true ) {
                ?>
			<div class="pausAR-UI-watermarkContainer">
				<?php 
                if ( $this->isEditMode() == 'false' || $this->isEditMode() == false ) {
                    ?>
					<a href="https://www.pausarstudio.de/wordpress-elementor/" target="_blank" rel="noopener" class="pausAR-UI-watermarkLink">
				<?php 
                } else {
                    ?>
					<a class="pausAR-UI-watermarkLink">
				<?php 
                }
                ?>
						<img class="pausAR-UI-watermark" src="<?php 
                echo esc_html( plugins_url( "../assets/watermark.svg", __DIR__ ) );
                ?>" alt="3D / AR Experience - made with PausAR" pausar_src_active="<?php 
                echo esc_html( plugins_url( "../assets/watermark.png", __DIR__ ) );
                ?>" pausar_src_static="<?php 
                echo esc_html( plugins_url( "../assets/watermark.svg", __DIR__ ) );
                ?>">
					</a>
			</div>
			<?php 
            }
            //render_Watermark()
            ?>
		<?php 
        }
        ?>
		</div>
		
	<?php 
    }

    protected function content_template() {
        ?>
		<# /*console.log("ID (content_template): pausARID-widget-" + view.model.id);*/
		
		//console.log("JS Settings:");
		//console.log(settings);
		
		//-------------------------------
		//Render-Conditions (+[Freemius])
		//-------------------------------

		function getArButtonPosition() {

			if(!settings) {
				return false;
			}

			if(settings.preview_align) {
				if(settings.preview_align == 'bottom') {
					return true;
				} else if(settings.preview_align == 'top') {
					return false;
				}
			}
			return true;
		}

		function hideButton() {
			if(!settings) {
				return false;
			}

			<?php 
        ?>
				return false;
			<?php 
        ?>
		}

		
		function render_widgetContainer(containerOptions) {
			if(!settings) {
				return false;
			}
			if(containerOptions == null) {
				return false;
			}
			//---
			containerOptions['class'] = [ 'pausAR-UI-widgetContainer',  'pausAR-UI-elementorViewerContainer'];
			containerOptions['pausar'] = "pausARID-widget-" + view.model.id;
			containerOptions['pausar_version'] = "elementor|starter";
			containerOptions['editmode'] = elementorFrontend.isEditMode();
			<?php 
        ?>
		}

		function render_CustomIcon() {
			if(!settings) {
				return -1;
			}
			//---
			<?php 
        ?>
				return 0;
			<?php 
        ?>
		}

		function render_Watermark() {
			if(!settings) {
				return true;
			}
			//---
			<?php 
        ?>
				return true;
			<?php 
        ?>
		}

		function render_CustomAnchor() {
			if(!settings) {
				return false;
			}
			//---
			<?php 
        ?>
				return false;
			<?php 
        ?>
		}

		function render_SceneUI(control) {
			if(!settings) {
				return '';
			}
			if(typeof control !== 'string') {
				return '';
			}
			//---
			<?php 
        ?>
				switch (control) {
					case 'scene_title':
						return encodeURIComponent('WebAR by PausAR Studio');
						break;
					case 'scene_link':
						return encodeURIComponent('https://www.pausarstudio.de/wordpress-elementor/');
						break;
					case 'scene_description':
						return encodeURIComponent('Discover PausAR Viewer Pro');
						break;
					case 'scene_cta':
						return encodeURIComponent('Visit');
						break;					
					default:
						return '';
						break;
				}
			<?php 
        ?>
		}

		function render_Shadow(modelViewerOptions) {
			if(!settings) {
				return false;
			}
			if(!isset(modelViewerOptions)) {
				return false;
			}
			//---
			modelViewerOptions['shadow-intensity'] = 0;

			<?php 
        ?>
		}

		function render_Background(modelViewerOptions) {
			if(!settings) {
				return false;
			}
			if(!isset(modelViewerOptions)) {
				return false;
			}
			//---
			<?php 
        ?>
		}

		function render_PreviewInlineStyle(modelViewerOptions) {
			if(!settings) {
				return false;
			}
			if(!isset(modelViewerOptions)) {
				return false;
			}
			if(displayPreview() != 0) {
				return ;
			}
			//---
			let inlineStyle = "";
			<?php 
        ?>
			if(typeof inlineStyle === 'string' && inlineStyle != "") {
				modelViewerOptions['style'] = inlineStyle;
			}
			
		}


		function render_Swivel(modelViewerOptions) {
			if(!settings) {
				return false;
			}
			if(!isset(modelViewerOptions)) {
				return false;
			}
			//---
			<?php 
        ?>		
		}

		function render_Dimensions(modelViewerOptions) {
			if(!settings) {
				return false;
			}
			//---
			<?php 
        ?>
			return false;
		}

		function render_Animation(modelViewerOptions) {
			if(!settings) {
				return false;
			}
			if(!isset(modelViewerOptions)) {
				return false;
			}
			//---
			<?php 
        ?>		
		}

		function render_Zoom(modelViewerOptions) {
			if(!settings) {
				return false;
			}
			if(!isset(modelViewerOptions)) {
				return false;
			}
			//---
			if(isset(settings['toggle_preview_zoom'])) {
				if(settings['toggle_preview_zoom'] != 'true') {
					modelViewerOptions['disable-zoom'] = null;
				}
			}
		}

		function render_Scaling(arButtonOptions) {
			if(!settings) {
				return false;
			}
			if(!isset(arButtonOptions)) {
				return false;
			}
			//---

			arButtonOptions['paus_scene_resizable'] = 'true';

			<?php 
        ?>
		}

		function render_Iframe(arButtonOptions) {

			if(!settings) {
				return false;
			}
			if(!isset(arButtonOptions)) {
				return false;
			}
			//---

			<?php 
        ?>
		}

		function render_QRAutostart(arButtonOptions) {

			if(!settings) {
				return false;
			}
			if(!isset(arButtonOptions)) {
				return false;
			}
			//---
			delete arButtonOptions['paus_autostart'];
			<?php 
        ?>
		}

		function render_CustomAnchor(arButtonOptions) {

			if(!settings) {
				return false;
			}
			if(!isset(arButtonOptions)) {
				return false;
			}
			//---
			delete arButtonOptions['paus_anchor'];
			<?php 
        ?>
		}

		function render_SceneSound(arButtonOptions) {

			if(!settings) {
				return false;
			}
			if(!isset(arButtonOptions)) {
				return false;
			}
			//---
			delete arButtonOptions['paus_scene_sound'];
			<?php 
        ?>
		}

		function render_Fullscreen() {
			if(!settings) {
				return false;
			}
			//---
		if(displayPreview() == 0) {
			if(isset(settings['allow_fullscreen_toggle'])) {
				if(settings['allow_fullscreen_toggle'] == 'true') {
					<?php 
        ?>
							return true;
						<?php 
        ?>
					}
				}
			}
			return false;
		}

		function displayPreview() {

			if(!settings) {
				return -1;
			}
			//---

			<?php 
        ?>
				//GLB exists?---
				if(isset(settings['android_glb_file'])) {
					if(isset(settings['android_glb_file']['url'])) {
						if(typeof settings['android_glb_file']['url'] === 'string') {
							if(!empty(settings['android_glb_file']['url'])) {
								return 0;
							}
						}
					}	
				}
				return -1;
			<?php 
        ?>	

		}

		function validateCameraOrientation() {

			let cameraOrientation = {
				'camera_orbit_theta': 0,
				'camera_orbit_phi': 75,
				'camera_orbit_radius': 105
			};

			if (isset(settings['camera_orbit_popover'])) {
				if (settings['camera_orbit_popover'] == 'active') {

					if (isset(settings['camera_orbit_theta'])) {
						if(isset(settings['camera_orbit_theta']['size'])) {
							if(is_numeric(settings['camera_orbit_theta']['size'])) {
								cameraOrientation['camera_orbit_theta'] = parseFloat(settings['camera_orbit_theta']['size']);
							}
						}
					}
					if (isset(settings['camera_orbit_phi'])) {
						if(isset(settings['camera_orbit_phi']['size'])) {
							if(is_numeric(settings['camera_orbit_phi']['size'])) {
								cameraOrientation['camera_orbit_phi'] = parseFloat(settings['camera_orbit_phi']['size']);
							}
						}
					}
					if (isset(settings['camera_orbit_radius'])) {
						if(isset(settings['camera_orbit_radius']['size'])) {
							if(is_numeric(settings['camera_orbit_radius']['size'])) {
								cameraOrientation['camera_orbit_radius'] = parseFloat(settings['camera_orbit_radius']['size']);
							}
						}
					}

				}
			}
			return cameraOrientation;
		}

		function isset(obj) {

			if(typeof obj === 'undefined') {
				return false;
			}
			if(obj == null) {
				return false;
			}
			return true;
		}
		/*
		function empty(obj) {
			if(typeof obj === 'string') {
				if(obj != "") {
					return false;
				}
			}
			if(!isset(obj)) {
				return false;
			}
			return true;
		}
		*/
		function empty(mixedVar) {
  
			let undef;
			let key;
			let i;
			let len;
			const emptyValues = [undef, null, false, 0, '', '0'];
			for (i = 0, len = emptyValues.length; i < len; i++) {
				if (mixedVar === emptyValues[i]) {
				return true
				}
			}
			if (typeof mixedVar === 'object') {
				for (key in mixedVar) {
				if (mixedVar.hasOwnProperty(key)) {
					return false;
				}
				}
				return true;
			}
			return false;
		}

		function is_numeric(mixedVar) {
  			if(typeof mixedVar === 'number') {
     			return true;
  			} else if(typeof mixedVar === 'string') {
    			if(mixedVar == "") {
      				return false;
    			} else if(mixedVar.match(/^\d+$/i)) {
      				return true;
    			}
    			return false;
  			}
  			return false;  
		}

		function pausar_esc_html(html) {
			if(typeof html !== 'string') {
				return '';
			}
			//return html.replace(/<\/?[^>]+(>|$)/g, "");
			/*
			return html.replace(/&/g, "&amp;")
         		.replace(/</g, "&lt;")
         		.replace(/>/g, "&gt;")
         		.replace(/"/g, "&quot;")
         		.replace(/'/g, "&#39;");			
			*/
			return html;
		}
			

		//-----------------------------------------

			let containerOptions = {};
			render_widgetContainer(containerOptions);
			view.addRenderAttribute(
				'widget-container',
				containerOptions
			);
				
			let modelViewerOptions = {
					'pausar' : ('pausARID-widget-'+ view.model.id),
					'pausar_version' : 'elementor|starter',
					'class' : [ 'pausAR-UI-modelViewer', 'pausAR-UI-elementorModelViewer' ],
					'src' : !empty(settings['android_glb_file']['url']) ? settings['android_glb_file']['url']: '',
					//'poster': !empty(settings['preview_media_poster']) ? (!empty(settings['preview_media_poster']['url']) ? settings['preview_media_poster']['url'] : '') : '',
			};
			<?php 
        ?>	

			if(displayPreview() == 0) {

				render_Animation(modelViewerOptions);

				if(isset(settings['camera_mode'])) {
				switch(settings['camera_mode']) {
					case 'none':
						//Rotation
						if(isset(settings['toggle_preview_rotation'])) {
							if(settings['toggle_preview_rotation'] == 'true') {
								modelViewerOptions['auto-rotate'] = null;
								if(isset(settings['preview_rotation_delay'])) {
									if(isset(settings['preview_rotation_delay']['size'])) {
										if(is_numeric(settings['preview_rotation_delay']['size'])) {
											modelViewerOptions['auto-rotate-delay'] = settings['preview_rotation_delay']['size'] * 1000;
										}
									}
								}
							}
						}
						//Swivel
						render_Swivel(modelViewerOptions);
						//StartPos.
						if(isset(settings['camera_orbit_popover'])) {
							if(settings['camera_orbit_popover'] == 'active') {
								if(isset(settings['camera_orbit_theta']) && isset(settings['camera_orbit_phi']) && isset(settings['camera_orbit_radius'])) {
									let validatedCamera = validateCameraOrientation();
									modelViewerOptions['camera-orbit'] = validatedCamera['camera_orbit_theta'] + settings['camera_orbit_theta']['unit'] + ' ' + validatedCamera['camera_orbit_phi'] + settings['camera_orbit_phi']['unit'] + ' ' + validatedCamera['camera_orbit_radius'] + settings['camera_orbit_radius']['unit'];
									//modelViewerOptions['camera-orbit'] = settings['camera_orbit_theta']['size'] + settings['camera_orbit_theta']['unit'] + ' ' + settings['camera_orbit_phi']['size'] + settings['camera_orbit_phi']['unit'] + ' ' + settings['camera_orbit_radius']['size'] + settings['camera_orbit_radius']['unit'];
								}
							}
						}
						break;
					case 'touch':
						//Camera
						modelViewerOptions['camera-controls'] = null;
						//Prompt
						if(isset(settings['toggle_show_prompt'])) {
							if(settings['toggle_show_prompt'] == 'true' && isset(settings['toggle_prompt_style'])) {
								if(isset(settings['interaction-prompt-threshold'])) {
									if(isset(settings['interaction-prompt-threshold']['size'])) {
										if(is_numeric(settings['interaction-prompt-threshold']['size'])) {
											modelViewerOptions['interaction-prompt-threshold'] = settings['interaction-prompt-threshold']['size'] * 1000;
										}
									}
								}
								modelViewerOptions['interaction-prompt'] = 'auto';
								switch (settings['toggle_prompt_style']) {
									case 'wiggle':
										modelViewerOptions['interaction-prompt-style'] = settings['toggle_prompt_style'];
									break;
									case 'basic':
										modelViewerOptions['interaction-prompt-style'] = settings['toggle_prompt_style'];
									break;
									default:
									modelViewerOptions['interaction-prompt-style'] = 'wiggle';
								}
								
							} else {
								modelViewerOptions['interaction-prompt'] = 'none';
							}
						}
						//Zoom
						render_Zoom(modelViewerOptions);
						//Rotation
						if(isset(settings['toggle_preview_rotation'])) {
							if(settings['toggle_preview_rotation'] == 'true') {
								modelViewerOptions['auto-rotate'] = null;
								if(isset(settings['preview_rotation_delay'])) {
									if(isset(settings['preview_rotation_delay']['size'])) {
										if(is_numeric(settings['preview_rotation_delay']['size'])) {
											modelViewerOptions['auto-rotate-delay'] = settings['preview_rotation_delay']['size'] * 1000;
										}
									}
								}
							}
						}
						//Swivel
						render_Swivel(modelViewerOptions);
						//Dimensions
						render_Dimensions(modelViewerOptions);
						//StartPos.
						if(isset(settings['camera_orbit_popover'])) {
							if(settings['camera_orbit_popover'] == 'active') {
								if(isset(settings['camera_orbit_theta']) && isset(settings['camera_orbit_phi']) && isset(settings['camera_orbit_radius'])) {
									let validatedCamera = validateCameraOrientation();
									modelViewerOptions['camera-orbit'] = validatedCamera['camera_orbit_theta'] + settings['camera_orbit_theta']['unit'] + ' ' + validatedCamera['camera_orbit_phi'] + settings['camera_orbit_phi']['unit'] + ' ' + validatedCamera['camera_orbit_radius'] + settings['camera_orbit_radius']['unit'];
									//modelViewerOptions['camera-orbit'] = settings['camera_orbit_theta']['size'] + settings['camera_orbit_theta']['unit'] + ' ' + settings['camera_orbit_phi']['size'] + settings['camera_orbit_phi']['unit'] + ' ' + settings['camera_orbit_radius']['size'] + settings['camera_orbit_radius']['unit'];
								}
							}
						}
						//Pan
						if(isset(settings['toggle_pan'])) {
							if(settings['toggle_pan'] == 'true') {
								modelViewerOptions['disable-pan'] = null;
							}
						}
						//Tap
						if (isset(settings['toggle_tap'])) {
							if (settings['toggle_tap'] == 'true') {
								modelViewerOptions['disable-tap'] = null;
							}
						}
						if(isset(settings['toggle_pan_scroll']) && elementorFrontend.isEditMode() == false) {
							if(settings['toggle_pan_scroll'] == 'true') {
								modelViewerOptions['touch-action'] = 'pan-y';
							}
						}
						break;
					case 'scroll':
						//StartPos.
						if (isset(settings['preview_scroll_sensitivity'])) {
							if (isset(settings['camera_orbit_popover'])) {
								if (settings['camera_orbit_popover'] == 'active') {
									if (isset(settings['camera_orbit_theta']) && isset(settings['camera_orbit_phi']) && isset(settings['camera_orbit_radius'])) {
										let validatedCamera = validateCameraOrientation();
										modelViewerOptions['camera-orbit'] = 'calc(' + validatedCamera['camera_orbit_theta'] + settings['camera_orbit_theta']['unit'] + ' - env(window-scroll-y) * ' + (parseInt(settings['preview_scroll_sensitivity']['size']) * 5) + settings['preview_scroll_sensitivity']['unit'] + ') ' + validatedCamera['camera_orbit_phi'] + settings['camera_orbit_phi']['unit'] + ' ' + validatedCamera['camera_orbit_radius'] + settings['camera_orbit_radius']['unit'];
										//modelViewerOptions['camera-orbit'] = 'calc(' + settings['camera_orbit_theta']['size'] + settings['camera_orbit_theta']['unit'] + ' - env(window-scroll-y) * ' + (parseInt(settings['preview_scroll_sensitivity']['size']) * 5) + settings['preview_scroll_sensitivity']['unit'] + ') ' + settings['camera_orbit_phi']['size'] + settings['camera_orbit_phi']['unit'] + ' ' + settings['camera_orbit_radius']['size'] + settings['camera_orbit_radius']['unit'];
									}
								} else {
									modelViewerOptions['camera-orbit'] = 'calc(0deg - env(window-scroll-y) * ' + (parseInt(settings['preview_scroll_sensitivity']['size']) * 5) + settings['preview_scroll_sensitivity']['unit'] + ') 75deg 105%';
								}
							} else {
								modelViewerOptions['camera-orbit'] = 'calc(0deg - env(window-scroll-y) * ' + (parseInt(settings['preview_scroll_sensitivity']['size']) * 5) + settings['preview_scroll_sensitivity']['unit'] + ') 75deg 105%';
							}
						}
						break;
					}
				}

				render_Shadow(modelViewerOptions);
				render_Background(modelViewerOptions);
				render_PreviewInlineStyle(modelViewerOptions);
				if(isset(settings['preview_loading_type'])) {
					modelViewerOptions['loading'] = settings['preview_loading_type'];
				}

			}			

			
			view.addRenderAttribute(
				'model-viewer',
				modelViewerOptions
			);
			

			view.addRenderAttribute(
				'button_title',
				{
					'class': [ 'pausAR-UI-ArButtonLabel' ],
					
				}
			);
			view.addInlineEditingAttributes( 'button_title', 'none' );

			view.addRenderAttribute(
				'preview_media_image_attribute',
				{
					'class' : ['pausAR-UI-previewImage'],
					'src' : (isset(settings.preview_media_image) ? (isset(settings.preview_media_image.url) ? settings.preview_media_image.url : '') : ''),
				}
			);

			//--- AR Button Margin (for Preview)
			let arButtonMarginArgs = {
				'class' : ['pausAR-UI-buttonContainer']
			};
			
			if(displayPreview() >= 0 && isset(settings['preview_align'])) {
				if(settings['preview_align'] == 'top') {
					arButtonMarginArgs['class'].push('pausAR-UI-buttonContainerBottom');
				} else if(settings['preview_align'] == 'bottom') {
					arButtonMarginArgs['class'].push('pausAR-UI-buttonContainerTop');
				}
			}
					

			view.addRenderAttribute(
					'button_margin',
					arButtonMarginArgs
			);

			//--- Icon (Fa-Fa-Icon & Margin)
			let buttonIcon = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' );
			let arButtonIconMarginArgs = {
			'class' : ['pausAR-UI-buttonIcon'],
			};

			view.addRenderAttribute(
				'button_icon',
				arButtonIconMarginArgs
			);

			//---AR Button (Settings)
			let arButtonOptions = {
				'class' : ['pausAR-UI-ArButton'],
				//'href': '#',
				'id' : "pausAR-" + view.model.id,
				'pausar': "pausARID-widget-" + view.model.id,
				'pausar_version': 'elementor|starter',
				'editMode': elementorFrontend.isEditMode(),
				'paus_file': null,
				'paus_glb' : !empty(settings['android_glb_file']['url']) ? settings['android_glb_file']['url'] : null,
				'paus_ios' : !empty(settings['apple_ios_file']['url']) ? settings['apple_ios_file']['url'] : null,
				'paus_scene_sound' : !empty(settings['scene_sound_file']['url']) ? settings['scene_sound_file']['url'] : null,
				'paus_scene_resizable' : !empty(settings['scene_disable_scaling']) ? ((settings['scene_disable_scaling'] == true || settings['scene_disable_scaling'] == 'true') ? 'true' : 'false' ) : 'false',
				'paus_scene_occlusion' : !empty(settings['scene_occlusion']) ? ((settings['scene_occlusion'] == true || settings['scene_occlusion'] == 'true') ? 'true' : 'false' ) : 'false',
				'paus_scene_placement' : !empty(settings['scene_placement']) ? (settings['scene_placement'] == 'vertical' ? 'vertical' : 'horizontal' ) : 'horizontal',
    			'paus_scene_title' : render_SceneUI('scene_title'),
				'paus_scene_link' : render_SceneUI('scene_link'),
				'paus_scene_description' : render_SceneUI('scene_description'),
				'paus_scene_cta' : render_SceneUI('scene_cta'),
			};
			render_Scaling(arButtonOptions);
			render_Iframe(arButtonOptions);
			render_QRAutostart(arButtonOptions);
			render_CustomAnchor(arButtonOptions);
			render_SceneSound(arButtonOptions);
			<?php 
        ?>	
			
			if(isset(settings.hover_animation)) {
				arButtonOptions.class.push('elementor-animation-' + settings.hover_animation);
			}
			view.addRenderAttribute(
				'ar_button',
				arButtonOptions
			);


			let fullscreenButtonOptions = {
				'class' : ['pausAR-UI-fullscreenButton', 'pausAR-UI-elementHide'],
				'pausar': "pausARID-widget-" + view.model.id,
			};
			view.addRenderAttribute(
				'fullscreen_button',
				fullscreenButtonOptions
			);

		#>

				
		<div {{{ view.getRenderAttributeString( 'widget-container' ) }}}>
			<# if(getArButtonPosition() == true) { #>
			<# if(hideButton() == false) { #>
			<div {{{ view.getRenderAttributeString( 'button_margin' ) }}}>
				<a {{{ view.getRenderAttributeString( 'ar_button' ) }}}>
					<span class="pausAR-UI-ArButtonWrapper">
						<# if(render_CustomIcon() == 1) { #>
							<span {{{ view.getRenderAttributeString( 'button_icon' ) }}}> {{{ buttonIcon.value }}} </span>
						<# } else if(render_CustomIcon() == 0) { #>
							<span {{{ view.getRenderAttributeString( 'button_icon' ) }}}><?php 
        echo $this->getPausAR_Logo_Inline();
        ?></span>
						<# } #>
						<span {{{ view.getRenderAttributeString( 'button_title' ) }}}>{{{ pausar_esc_html(settings.button_title) }}}</span>
					</span>
				</a>
			</div>
			<# } #>
			<# } else if(displayPreview() == 0) { #>
				<# if(render_Watermark() == true) { #>
				<div class="pausAR-UI-watermarkContainer">
					<a class="pausAR-UI-watermarkLink">
						<img class="pausAR-UI-watermark" src="<?php 
        echo esc_html( plugins_url( "../assets/watermark.svg", __DIR__ ) );
        ?>" alt="3D / AR Experience - made with PausAR" pausar_src_active="<?php 
        echo esc_html( plugins_url( "../assets/watermark.png", __DIR__ ) );
        ?>" pausar_src_static="<?php 
        echo esc_html( plugins_url( "../assets/watermark.svg", __DIR__ ) );
        ?>">
					</a>
				</div>
				<# } //render_Watermark() #>
			<# } #>
			<# if(displayPreview() == 0) { #>
				<div class="pausAR-UI-modelPreviewContainer">
					<model-viewer {{{ view.getRenderAttributeString( 'model-viewer' ) }}}>
						<# if(render_Dimensions() == true) { #>
							<div slot="hotspot-dot+X-Y+Z" class="pausAR-UI-dimensionDot pausAR-UI-elementHide" data-position="1 -1 1" data-normal="1 0 0"></div>
							<div slot="hotspot-dim+X-Y" class="pausAR-UI-dimensionDim pausAR-UI-elementHide" data-position="1 -1 0" data-normal="1 0 0"></div>
							<div slot="hotspot-dot+X-Y-Z" class="pausAR-UI-dimensionDot pausAR-UI-elementHide" data-position="1 -1 -1" data-normal="1 0 0"></div>
							<div slot="hotspot-dim+X-Z" class="pausAR-UI-dimensionDim pausAR-UI-elementHide" data-position="1 0 -1" data-normal="1 0 0"></div>
							<div slot="hotspot-dot+X+Y-Z" class="pausAR-UI-dimensionDot pausAR-UI-elementHide" data-position="1 1 -1" data-normal="0 1 0"></div>
							<div slot="hotspot-dim+Y-Z" class="pausAR-UI-dimensionDim pausAR-UI-elementHide" data-position="0 -1 -1" data-normal="0 1 0"></div>
							<div slot="hotspot-dot-X+Y-Z" class="pausAR-UI-dimensionDot pausAR-UI-elementHide" data-position="-1 1 -1" data-normal="0 1 0"></div>
							<div slot="hotspot-dim-X-Z" class="pausAR-UI-dimensionDim pausAR-UI-elementHide" data-position="-1 0 -1" data-normal="-1 0 0"></div>
							<div slot="hotspot-dot-X-Y-Z" class="pausAR-UI-dimensionDot pausAR-UI-elementHide" data-position="-1 -1 -1" data-normal="-1 0 0"></div>
							<div slot="hotspot-dim-X-Y" class="pausAR-UI-dimensionDim pausAR-UI-elementHide" data-position="-1 -1 0" data-normal="-1 0 0"></div>
							<div slot="hotspot-dot-X-Y+Z" class="pausAR-UI-dimensionDot pausAR-UI-elementHide" data-position="-1 -1 1" data-normal="-1 0 0"></div>

							<svg id="pausAR-UI-dimLinesGraphic" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" class="pausAR-UI-dimensionLineContainer pausAR-UI-elementHide">
								<line class="pausAR-UI-dimensionLine"></line>
								<line class="pausAR-UI-dimensionLine"></line>
								<line class="pausAR-UI-dimensionLine"></line>
								<line class="pausAR-UI-dimensionLine"></line>
								<line class="pausAR-UI-dimensionLine"></line>
							</svg>
						<# } #>
					</model-viewer>
					<# if(render_Fullscreen() == true) { #>
					<div class="pausAR-UI-modelPreviewInterface">
					<button disabled {{{ view.getRenderAttributeString( 'fullscreen_button' ) }}}></button>
					</div>
					<# } #>
				</div>
				<# //if(!elementorFrontend.isEditMode()) { #>
				<# //} #>
			<# } else if(displayPreview() == 1) { #>
				<div class="pausAR-UI-previewContainer">
					<img {{{ view.getRenderAttributeString( 'preview_media_image_attribute' ) }}}>
				</div>
			<# } #>
			
			<# if(getArButtonPosition() == false) { #>
			<# if(hideButton() == false) { #>
			<div {{{ view.getRenderAttributeString( 'button_margin' ) }}}>
				<a {{{ view.getRenderAttributeString( 'ar_button' ) }}}>
					<span class="pausAR-UI-ArButtonWrapper">
					<# if(render_CustomIcon() == 1) { #>
						<span {{{ view.getRenderAttributeString( 'button_icon' ) }}}> {{{ buttonIcon.value }}} </span>
					<# } else if(render_CustomIcon() == 0) { #>
						<span {{{ view.getRenderAttributeString( 'button_icon' ) }}}><?php 
        echo $this->getPausAR_Logo_Inline();
        ?></span>
					<# } #>
						<span {{{ view.getRenderAttributeString( 'button_title' ) }}}>{{{ pausar_esc_html(settings.button_title) }}}</span>
					</span>
				</a>
			</div>
			<# } #>
			<# } else if(displayPreview() == 0) { #>
				<# if(render_Watermark() == true) { #>
				<div class="pausAR-UI-watermarkContainer">
					<a class="pausAR-UI-watermarkLink">
						<img class="pausAR-UI-watermark" src="<?php 
        echo esc_html( plugins_url( "../assets/watermark.svg", __DIR__ ) );
        ?>" alt="3D / AR Experience - made with PausAR" pausar_src_active="<?php 
        echo esc_html( plugins_url( "../assets/watermark.png", __DIR__ ) );
        ?>" pausar_src_static="<?php 
        echo esc_html( plugins_url( "../assets/watermark.svg", __DIR__ ) );
        ?>">
					</a>
				</div>
				<# } //render_Watermark() #>
			<# } #>
		</div>
		
<?php 
    }

}
