<?php 

add_action('agni_404', 'cartify_404');

if (!function_exists('cartify_404')) {
    /**
     * function of 404 page.
     */
    function cartify_404(){ 

                $image_url = cartify_get_theme_option( '404_settings_content_image_url', '' );
        $product_search = cartify_get_theme_option( '404_settings_content_products_search', '1' );

        $block_id = cartify_get_theme_option( '404_settings_content_block_choice', '' );

                ?>
        <div class="error-404 not-found page">
            <div class="error-404-header entry-header">
                <?php if( !empty( $image_url ) ){ ?>
                    <img class="error-404__image" src="<?php echo esc_url( $image_url ); ?>" />
                <?php } ?>
                <h1 class="error-404__title"><?php echo esc_html__( 'Sorry!', 'cartify' ); ?></h1>
                <p class="error-404__description"><?php echo esc_html__( 'It looks like nothing was found at this location. Try a search or check the links below.', 'cartify' ); ?></p>
                <div class="error-404__search"><?php cartify_404_search_form( $product_search ); ?></div>
                <div class="error-404__back-link"><span><?php echo esc_html__( 'Go back to ', 'cartify' ); ?></span><a href="<?php echo esc_url( home_url('/') ); ?>"><?php echo esc_html( home_url() ); ?></a></div>
            </div>
            <div class="error-404-content entry-content">
                <?php echo apply_filters( 'agni_content_block', $block_id ) ?>
            </div>
        </div>
    <?php }
}

if( !function_exists( 'cartify_404_search_form' ) ){
    function cartify_404_search_form( $product_search = '' ){
        ?>
        <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ) ?>">
            <label>
                <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search Products..', '404_placeholder', 'cartify' ) ?>" name="s" />
            </label>
            <?php if( $product_search ){ ?>
                <input type="hidden" name="post_type" value="product" />
            <?php } ?>
            <button type="submit" class="search-submit"></button>
        </form>
        <?php
    }
}