<?php 

class Agni_Wishlist_Page {

    public $wishlist_default = null;

    public function __construct(){

        if( isset($_REQUEST['wishlist_id']) ){
            $this->wishlist_default = $_REQUEST['wishlist_id'];
        }

        // add_action( 'agni_woocommerce_wishlist_dropdown', array( $this, 'wishlist_dropdown' ) );
        // add_action( 'agni_woocommerce_wishlist_contents', array( $this, 'wishlist_contents' ) );
        add_action( 'wc_ajax_agni_wishlist_page', array( $this, 'ajax_wishlist_page' ) );

    }

    public function contents(){
        /*?>
        <div>
            <?php $this->wishlist_header(); ?>
        </div>
        <?php*/

        wp_enqueue_script( 'cartify-wishlist' );

                ?>
        <div class="agni-wishlist-page">
            <div class="agni-wishlist-page__header">
                <div class="agni-wishlist-page__dropdown">
                    <?php $this->wishlist_dropdown(); ?>
                    <?php // do_action( 'agni_woocommerce_wishlist_dropdown' ); ?>
                </div>
                <div class="agni-wishlist-page__new">
                    <span class="agni-wishlist-page__new-link"><?php echo esc_html__( 'Create new wishlist', 'cartify' ); ?></span>
                    <div class="agni-wishlist-page__new-panel">
                        <div class="agni-wishlist-page__new-panel-close"><i class="lni lni-close"></i></div>
                        <div class="agni-wishlist-page__new-panel-contents">
                            <form class="agni-wishlist-page-new-form" method="post">
                                <label><?php echo esc_html__( 'Wishlist name', 'cartify' ); ?></label>
                                <input type="text" name="wishlist_name" class="agni-wishlist-page-new-form__name" value="<?php echo esc_attr( Agni_Wishlist::get_wishlist_name() ); ?>" />
                                <input type="hidden" name="user_id" value="<?php echo esc_attr( Agni_Wishlist::get_user_id() ); ?>" />
                                <button type="submit" class="agni-wishlist-page-new-form__submit"><?php echo esc_html__( 'Submit', 'cartify' ); ?></button>
                            </form>
                        </div>
                        <div class="agni-wishlist__loader"><?php echo esc_html__( 'Creating wishlist.', 'cartify' ); ?></div>
                    </div>
                </div>
            </div>
            <div class="agni-wishlist-page-wishlist">
                <?php 
                $current_user = wp_get_current_user();
                $current_username = $current_user->user_login;

                $wishlist = get_post( $this->wishlist_default );
                if( $current_username == get_the_author_meta( 'user_login', $wishlist->post_author ) ){
                    $this->wishlist_page($this->wishlist_default);
                }
                ?>
                <?php // do_action( 'agni_woocommerce_wishlist_contents' ); ?>
            </div>
        </div>

        <?php

            }

    public function wishlist_header(){
        ?>
        <table>
            <tr>
                <th>Product</th>
                <th>Product</th>
                <th>Product</th>
            </tr>
        </table>
        <?php
    }

    public function wishlist_dropdown(){
        $current_user = wp_get_current_user();
        $current_username = $current_user->user_login;

        $args = array(
            'post_type' => 'agni_wc_wishlist',
            'numberposts' => -1
        );

        $wishlists = get_posts( $args );

        // print_r($wishlists);

        if( !empty($wishlists) ){

            $set_wishlist_default = 0;
            ?> 
            <select>
            <?php foreach ($wishlists as $key => $wishlist) {

                if( $current_username == get_the_author_meta( 'user_login', $wishlist->post_author ) ){
                    if( $set_wishlist_default == 0 && $this->wishlist_default == null ){
                        $this->wishlist_default = $wishlist->ID;
                    }

                    ?>
                    <option value="<?php echo esc_attr( $wishlist->ID ); ?>" <?php selected( $wishlist->ID, $this->wishlist_default ); ?>><?php echo esc_html( $wishlist->post_title ); ?></option>
                    <?php
                    $set_wishlist_default = 1;
                }

            } ?>
            </select>
            <?php
        }
        else{
            ?>
            <div class="wishlist-empty"><?php echo esc_html__( 'No items in Wishlist', 'cartify' ); ?></div>
            <?php
        }
    }


    public function wishlist_page($wishlist_id){


        $remove_wishlist_url = add_query_arg( array(
            'remove_wishlist' => $wishlist_id, 
        ), '');

        $products_count = sizeof( $this->get_product_ids($wishlist_id) );

        ?>
        <div class="agni-wishlist-page-wishlist__header">
            <h1 class="agni-wishlist-page-wishlist__title"><?php echo esc_html( get_the_title( $wishlist_id ) ); ?></h1>
            <div class="agni-wishlist-page-wishlist__tools">
                <span class="agni-wishlist-page-wishlist__count"><?php echo sprintf( _n( '%s Product', '%s Products', esc_html( $products_count ), 'cartify' ), esc_html( $products_count ) ) ?></span>
                <div class="agni-wishlist-page-wishlist__settings">
                    <span class="agni-wishlist-page-wishlist__settings-link"><?php echo esc_html__( 'Settings', 'cartify' ); ?></span>
                    <div class="agni-wishlist-page-wishlist__settings-panel" tabindex="0">
                        <div class="agni-wishlist-page-wishlist__settings-panel-close"><i class="lni lni-close"></i></div>
                        <div class="agni-wishlist-page-wishlist__settings-panel-contents">
                            <form class="agni-wishlist-page-wishlist-settings-form" method="post">
                                <label><?php echo esc_html__( 'Edit name', 'cartify' ); ?></label>
                                <input type="text" name="wishlist_name" class="agni-wishlist-page-wishlist-settings-form__name" value="<?php echo esc_attr( get_the_title( $wishlist_id ) ) ?>" />
                                <input type="hidden" name="wishlist_id" value="<?php echo esc_attr( $wishlist_id ); ?>" />
                                <button type="submit" class="agni-wishlist-page-wishlist-settings-form__submit"><?php echo esc_html__( 'Submit', 'cartify' ); ?></button>
                            </form>
                        </div>
                        <div class="agni-wishlist__loader"><?php echo esc_html__( 'Saving new settings.', 'cartify' ); ?></div>
                    </div>
                </div>
                <a href="<?php echo esc_url( $remove_wishlist_url ); ?>" class="agni-wishlist-page-wishlist__delete"><?php echo esc_html__( 'Delete', 'cartify' ); ?></a>
            </div>
        </div>
        <div class="agni-wishlist-page-wishlist__contents">
            <?php $this->wishlist_contents($wishlist_id); ?>
        </div>
        <div class="agni-wishlist-page-wishlist__footer">
            <a href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>" class="agni_wishlist-page-wishlist__add-more btn btn-bold btn-alt"><?php echo esc_html__( 'Add more products', 'cartify' ); ?></a>
        </div>
        <?php
    }

    public function wishlist_contents($wishlist_id){
        ?>
        <?php 

                wp_enqueue_script( 'cartify-ajax-sidecart' );

        $product_ids = $this->get_product_ids($wishlist_id);

        // print_r($existing_product_ids_array); 

        if( !empty($product_ids) ){ ?>
            <?php 
            foreach ($product_ids as $key => $value) {
                $product_id = $variation_id = "";
                // $value = '32:98';
                $value_array = explode(':', $value);
                $product_id = $value_array[0];

                $product = wc_get_product( $product_id );

                // $price = wc_get_price_excluding_tax( $product ); // price without VAT
                // $price = wc_get_price_including_tax( $product );  // price with VAT
                $price = wc_price( wc_get_price_to_display( $product ) );

                if( $product->is_type('variable') ){
                    $variation_id = !empty($value_array[1]) ? $value_array[1] : '';

                    $available_variations = $product->get_available_variations();
                    foreach ($available_variations as $key => $variation) {
                        if($variation['variation_id'] == $variation_id){
                            $price = wc_price( $variation['display_price'] );
                        }
                    }

                }

                                                            if( $product->is_type('variable') ){
                    $remove_from_wishlist_url = add_query_arg(array( 
                        'product_id' => $product_id, 
                        'variation_id' => $variation_id, 
                        'wishlist_id' => $wishlist_id
                    ), '');
                }
                else{
                    $remove_from_wishlist_url = add_query_arg(array( 
                        'product_id' => $product_id, 
                        'wishlist_id' => $wishlist_id
                    ), '');
                }

                $args = array(
                    'max_value' => $product->backorders_allowed() ? '' : $product->get_stock_quantity(),  
                    'min_value' => 0,
                    'product_name' => $product->get_name(),
                    'classes' => 'input-text qty text agni-wishlist-product-cart-form__input'
                )

                                ?>
                <div class="agni-wishlist-product">
                    <div class="agni-wishlist-product__thumbnail">
                        <?php echo wp_kses( $product->get_image(), 'img' ); ?>
                    </div>
                    <div class="agni-wishlist-product__contents">
                        <div class="agni-wishlist-product__details">
                            <div class="agni-wishlist-product__title">
                                <h2><?php echo esc_html( $product->get_title() ) ?></h2>
                            </div>
                            <div class="agni-wishlist-product__price">
                                <?php echo wp_kses( $price, array(
                                    'del' => array(
                                        'aria-hidden' => array(),
                                        'class' => array(),
                                    ),
                                    'ins' => array(
                                        'class' => array(),
                                    ),
                                    'span' => array(
                                        'class' => array(),
                                    ), 
                                    'bdi' => array()
                                ) ); ?>
                            </div>
                            <a href="<?php echo esc_url( $remove_from_wishlist_url ); ?>" class="agni-wishlist-product__remove"><?php echo esc_html__( 'Remove from wishlist', 'cartify' ); ?></a>
                        </div>
                        <div class="agni-wishlist-product__cart">
                            <?php if( $product->is_type('variable') && empty( $variation_id ) ){  ?>
                                <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="agni-wishlist-product__cart-variable"><?php echo esc_html( $product->add_to_cart_text() ); ?></a>
                            <?php } 
                            else { ?>
                                <form class="cart agni-wishlist-product-cart-form" method="post" enctype="multipart/form-data">
                                    <?php woocommerce_quantity_input($args, $product, true); ?>
                                    <button type="submit" class="agni-wishlist-product-cart-form__button button alt btn-lite btn-sm"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
                                    <input type="hidden" name="add-to-cart" value="<?php echo absint( $product_id ); ?>" />
                                    <?php if( !empty( $variation_id ) ){ ?>
                                        <input type="hidden" name="product_id" value="<?php echo absint( $product_id ); ?>" />
                                        <input type="hidden" name="variation_id" class="variation_id" value="<?php echo absint( $variation_id ) ?>" />
                                    <?php } ?>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            ?>
        <?php } ?>
        <?php
    }


    public function ajax_wishlist_page(){


        if (!check_ajax_referer('agni_ajax_wishlist_nonce', 'security')) {
            return 'Invalid Nonce';
        }

        if( !isset($_REQUEST['wishlist_id']) ){
            return;
        }

                $wishlist_id = $_REQUEST['wishlist_id'];

        ?>
        <?php
        $this->wishlist_page($wishlist_id);

        ?>
        <?php

        wp_die();
    }

    public function get_product_ids($wishlist_id){
        $existing_product_ids = esc_attr( get_post_meta( $wishlist_id, 'agni_wishlist_product_ids', true ) );
        $existing_product_ids_array = explode( '|', $existing_product_ids );
        $existing_product_ids_array = array_filter($existing_product_ids_array);

        return $existing_product_ids_array;
    }


}

$agni_wishlist_page = new Agni_Wishlist_Page();

?>