<?php

class Agni_Cartify_Helper{

    public static function prepare_classes( $classes ) {
        return trim( preg_replace('!\s+!', ' ', join(' ', $classes) ) );
    }

    public static function get_icon_svg($group, $icon, $size = 24) {
        if( !class_exists( 'Cartify_SVG_Icons' ) ){
            return;
        }

        return Cartify_SVG_Icons::get_svg($group, $icon, $size);
    }

    public static function get_fonts_list(){
        $fonts_list = get_option('agni_font_manager_list');
        // print_r($fonts_list);
        $new_fonts_list = array();
        if( !empty( $fonts_list ) ){
            foreach ($fonts_list[0] as $fonts_src => $fonts) {
                if( !empty( $fonts ) ){
                    foreach ($fonts['families'] as $family) {
                        if( $fonts_src == 'custom_fonts' ){
                            $custom_family = array(
                                'name' => $family['name'],
                                'variants' => array()
                            );
                            foreach ($family['content'] as $key => $value) {
                                array_push( $custom_family['variants'], $value['weight']);
                            }
                            array_push($new_fonts_list, $custom_family);
                        }
                        else{
                            array_push($new_fonts_list, $family);
                        }
                    }
                }
            }
        }
        // print_r($new_fonts_list);

        return $new_fonts_list;
    }

    public static function get_posttype_posts_list( $query_args, $empty = false ) {
        $post_options = array();
        $args = wp_parse_args( $query_args, array(
            'post_type'   => 'post',
            'numberposts' => -1,
        ) );

        $posts = get_posts( $args );
        if( $empty == true ){
            $post_options = array("" => "");
        }
        if ( $posts ) {
            foreach ( $posts as $post ) {
                $post_options[ $post->ID ] = $post->post_title;
            }

        }
        return $post_options;
    }

}

$agni_cartify_helper = new Agni_Cartify_Helper();