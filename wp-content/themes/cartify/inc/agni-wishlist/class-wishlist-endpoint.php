<?php

class Agni_Wishlist_Endpoint {

    public $endpoint = 'wishlist';

    public function __construct(){

        add_action( 'init', array( $this, 'add_endpoint' ) );
        add_action( "woocommerce_account_{$this->endpoint}_endpoint", array( $this, 'contents' ) );

        add_filter( 'woocommerce_get_query_vars', array( $this, 'add_query_vars' ), 0 );
        add_filter( 'woocommerce_account_menu_items', array( $this, 'add_menu_item' ) );

    }

    public function add_endpoint() {
        add_rewrite_endpoint( $this->endpoint, EP_ROOT | EP_PAGES );
    }

          public function add_query_vars( $vars ) {
        $vars[$this->endpoint] = $this->endpoint;
        return $vars;
    }

    public function add_menu_item( $items ) {
        $customer_logout = $items['customer-logout'];
		unset( $items['customer-logout'] );

        $items[$this->endpoint] = apply_filters( 'agni_wishlist_account_menu_item_title', esc_html__( 'Wishlists', 'cartify' ) );

        $items['customer-logout'] = $customer_logout;

        return $items;
    }

      public function contents() {    
        $agni_wishlist_page = new Agni_Wishlist_Page(); 
        $agni_wishlist_page->contents();
    }
}

$agni_wishlist_endpoint = new Agni_Wishlist_Endpoint();


?>