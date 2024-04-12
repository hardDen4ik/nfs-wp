<?php

add_action( 'agni_footer', 'cartify_footer', 10, 2 );
add_action( 'agni_footer_default', 'cartify_footer_default' );
add_action( 'agni_footer_content_block', 'cartify_footer_content_block' );

if(!function_exists('cartify_footer')){
    /**
     * function for footer
     */
    function cartify_footer( $footer_choice = '2', $footer_block_choice = '' ){

        do_action( 'agni_before_footer' );

        ?>
        <footer id="colophon" class="site-footer">
            <?php if( $footer_block_choice != 'none' ){
                if( !empty( $footer_block_choice ) || $footer_choice == '2' ){
                    do_action( 'agni_footer_content_block', $footer_block_choice );
                } else {
                    do_action( 'agni_footer_default' );
                } 
            } ?>
        </footer>
        <?php

        do_action( 'agni_after_footer' );
    }
}


function cartify_footer_default(){
    ?>
    <div class="site-footer-container">
        <?php if ( is_active_sidebar( 'cartify-footerbar-1' ) ){ ?>
            <div class="site-footer-widgets">
                <div class="site-footer-widgets__container">
                    <div class="site-footer-widgets__content"><?php 
                        dynamic_sidebar( 'cartify-footerbar-1' ); 
                    ?></div>
                </div>
            </div>
        <?php } ?>
        <div class="site-footer-main">
            <div class="site-footer-main__container">
                <div class="site-footer-copyright-text">
                    <?php // get footer text theme option

                    $footer_copyright_text = esc_html__( 'copyright @ 2022 AgniHD. All rights reserved.', 'cartify' );

                    echo apply_filters('agni_footer_copyright_text', $footer_copyright_text);

                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}


function cartify_footer_content_block( $block_id ){
    ?>
    <div class="site-footer-content-block">
        <?php echo apply_filters( 'agni_content_block', $block_id ); ?>
    </div>
    <?php 
}
