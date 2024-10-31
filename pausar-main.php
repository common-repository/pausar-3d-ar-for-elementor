<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly.
}
if ( !function_exists( 'pausar_3d_ar_for_elementor_add_mime_types' ) ) {
    function pausar_3d_ar_for_elementor_add_mime_types(  $mime_types  ) {
        $additionalMimeTypes = [
            'glb'  => 'model/gltf-binary',
            'usdz' => 'model/vnd.usdz+zip',
        ];
        return array_merge( $mime_types, $additionalMimeTypes );
    }

    add_filter(
        'upload_mimes',
        'pausar_3d_ar_for_elementor_add_mime_types',
        10,
        1
    );
}
if ( !function_exists( 'pausar_3d_ar_for_elementor_filter_mime_types' ) ) {
    function pausar_3d_ar_for_elementor_filter_mime_types(
        $data,
        $file,
        $filename,
        $mimes
    ) {
        //$data = ['ext' => false,'type' => false, 'proper_filename' => false} //default
        $fileInfo = pathinfo( $filename );
        $ext = $fileInfo['extension'];
        switch ( $ext ) {
            case 'glb':
                $data['ext'] = $ext;
                $data['type'] = 'model/gltf-binary';
                break;
            case 'usdz':
                $data['ext'] = $ext;
                $data['type'] = 'model/vnd.usdz+zip';
                break;
        }
        return $data;
    }

    add_filter(
        'wp_check_filetype_and_ext',
        'pausar_3d_ar_for_elementor_filter_mime_types',
        99,
        4
    );
}
//Admin menu
if ( !class_exists( '\\PausAR_Elementor\\pausar_for_elementor_menu' ) ) {
    require_once plugin_dir_path( PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ) . '/admin/menu/adminMenu.php';
}
if ( !class_exists( '\\PausAR_Elementor\\pausar_for_elementor_htaccessValidator' ) ) {
    require_once plugin_dir_path( PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ) . '/admin/adminNoticeValidator.php';
}
if ( !function_exists( 'pausar_for_elementor_init_elementor_widget' ) ) {
    function pausar_for_elementor_init_elementor_widget() {
        //init and libary-imports
        require_once plugin_dir_path( PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ) . '/elementor/pausar-elementor-plugin.php';
        \PausAR_Elementor\PausAR_Plugin::instance();
    }

    add_action( 'plugins_loaded', 'pausar_for_elementor_init_elementor_widget' );
}
add_filter( 'plugin_action_links_' . plugin_basename( PAUSAR_FOR_ELEMENTOR_BOOT__FILE__ ), 'pausar_for_elementor_add_action_link_filter' );
if ( !function_exists( 'pausar_for_elementor_add_action_link_filter' ) ) {
    function pausar_for_elementor_add_action_link_filter(  $actions  ) {
        $links = array('<a style="color: #c82128;" href="https://www.pausarstudio.de/wordpress-elementor/#pricing" target="_blank"><strong style="display: inline;">' . esc_html__( 'Augmented features with Pro!', 'pausar-3d-ar-for-elementor' ) . '</strong></a>');
        $actions = array_merge( $actions, $links );
        return $actions;
    }

}