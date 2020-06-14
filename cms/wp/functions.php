
<?php

if ( ! function_exists( 'blank_page_template_bootstrap' ) ) {
    function blank_page_template_bootstrap() {
        
        // Register the Blank Page Template template.
        blank_page_template_add_template_to_wordpress(
            'blank-page-template-template.php',
            esc_html__( 'Blank Page Template', 'blank-page-template' )
        );

        // Add to the dropdown in the admin panel.
        blank_page_template_add_template_to_admin(
            '../../../plugins/blank-page-template/cms/wp/theme.php',
            'Blank Page Template'
        );
        
        // Load the template in the FE.
        blank_page_template_load_template_to_front_end(
            __DIR__ . '/theme.php',
            'blank-page-template'
        );
    }
}

if ( ! function_exists( 'blank_page_template_add_template_to_wordpress' ) ) {
    function blank_page_template_add_template_to_wordpress( $file, $label ) {
        add_filter(
            'blank_page_template_templates',
            function ( array $templates ) use ( $file, $label ) {
                $templates[ $file ] = $label;

                return $templates;
            }
        );
    }
}


if ( ! function_exists( 'blank_page_template_add_template_to_admin' ) ) {
    function blank_page_template_add_template_to_admin($file,$label) {
        add_filter(
            'theme_page_templates',
            function ( array $templates ) use ($file,$label) {
                $blank_page_template = array($file => $label);
                return array_merge( $templates , $blank_page_template);
            }
        );
    }
}

if ( ! function_exists( 'blank_page_template_load_template_to_front_end' ) ) {
    function blank_page_template_load_template_to_front_end($file,$plugin_name) {
        add_filter(
            'template_include',
            function ( $template ) use ($file,$plugin_name) {
                
                if ( is_singular() ) {
                    $post_template = get_post_meta( get_the_ID(), '_wp_page_template', true );
                    if($post_template)
                        return (strpos($post_template, $plugin_name) !== false) ?
                            wp_normalize_path($file) :
                            get_page_template();                    
                }

                return $template;
            }
        );
    }
}

add_action( 'plugins_loaded', 'blank_page_template_bootstrap' );