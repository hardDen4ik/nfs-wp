<?php

add_action( 'agni_content_open_tag', 'cartify_term_slider', 20 );


function cartify_term_slider(){
    if( get_query_var( 'term' ) || get_query_var('cat') || get_query_var('tag') ){
        $term = get_queried_object();

        if( isset( $term->term_id ) ){
            $term_id = $term->term_id;

            $slider_id = esc_attr( get_term_meta($term_id, 'agni_slider_id', true) );

            ?>
            <?php if( $slider_id !== '' ){ 
                do_action( 'agni_slider', $slider_id ); 
            }?>
            <?php
        }

    }
}