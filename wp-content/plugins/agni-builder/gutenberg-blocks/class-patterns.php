<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if( !class_exists( 'AgniBuilderPatterns' ) ){
    class AgniBuilderPatterns{

        public function __construct(){

            $this->agni_register_block_patterns();
            $this->agni_register_block_pattern_categories();

        }


        public function agni_register_block_patterns() {
        
            if ( !class_exists( 'WP_Block_Patterns_Registry' ) ) {
                return;
            }

            global $wp_filesystem;
 
            require_once ( ABSPATH . '/wp-admin/includes/file.php' );
            WP_Filesystem();
            $patterns = array();

            $patterns_json = AGNI_BUILDER_PLUGIN_PATH . '/gutenberg-blocks/helper/patterns-list.json';

            if ( $wp_filesystem->exists( $patterns_json ) ) {
                $patterns = json_decode( $wp_filesystem->get_contents( $patterns_json ), true );

            }
            
            foreach ($patterns as $key => $pattern) {
                $this->agni_register_block_pattern( $pattern );
            }
        
        }

        public static function agni_register_block_pattern( $pattern ){

            if( !empty( $pattern ) ){
                register_block_pattern(
                    'agni/' . $pattern['name'],
                    array(
                        'title'       => $pattern['title'],
                        'description' => $pattern['description'],
                        'content'     => $pattern['content'],
                        'categories'  => $pattern['categories'],
                        'keywords'    => $pattern['keywords'],
                    )
                );
            }
        }

        public function agni_register_block_pattern_categories(){
            if ( !class_exists( 'WP_Block_Patterns_Registry' ) ) {
                return;
            }

            register_block_pattern_category(
                'products',
                array( 'label' => esc_html_x( 'Agni Products', 'Products pattern category', 'agni-builder' ) )
            );
        
        }

    }
}


$agni_builder_patterns = new AgniBuilderPatterns();