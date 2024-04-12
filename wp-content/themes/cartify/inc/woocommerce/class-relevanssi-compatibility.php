<?php

if( !class_exists('Agni_Relevanssi') ){

    class Agni_Relevanssi{
        public function __construct(){

            add_filter( 'woocommerce_redirect_single_search_result', '__return_false' );
            add_filter( 'relevanssi_orderby', array( $this, 'orderby' ) );
            add_filter( 'relevanssi_order', array( $this, 'order' ) );

        }

        public function orderby( $orderby ) {
            if ( in_array( $orderby, array( 'price', 'price-desc' ), true ) ) {
                global $wp_query;
                $orderby = 'meta_value_num';
                $wp_query->query_vars['meta_key'] = '_regular_price';
            }
            if ( 'date ID' === $orderby ) {
                global $wp_query;
                $orderby = 'post_date';
            }
            if ( 'popularity' === $orderby ) {
                global $wp_query, $rlv_wc_order;
                $orderby = 'meta_value_num';
                $rlv_wc_order = 'desc';
                $wp_query->query_vars['meta_key'] = 'total_sales';
            }
            return $orderby;
        }

        public function order( $order ) {
            global $rlv_wc_order;
            if ( $rlv_wc_order ) {
                $order = $rlv_wc_order;
            }
            return $order;
        }

    }

    $agni_relevanssi = new Agni_Relevanssi();

}