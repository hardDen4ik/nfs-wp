<?php

add_action( 'agni_page', 'cartify_page' );
add_action( 'agni_page', 'cartify_page_slider', 8 );
add_action( 'agni_page_after_contents', 'cartify_page_sidebar', 99 );
add_action( 'agni_page_after_contents', 'cartify_page_portfolio', 10 );
add_action( 'agni_page_after_contents', 'cartify_page_compare', 15 );


add_filter( 'body_class', 'agni_woocommerce_compare_body_class' );
add_filter( 'post_class', 'cartify_page_post_classes' );

if (!function_exists('cartify_page')) {
    /**
     * function for displaying page
     */
    function cartify_page(){

        ?>
        <div id="primary" class="content-area">
            <main id="main" class="site-main">
                <?php

                $page_id = get_the_ID();

                $page_title_hide = esc_attr( get_post_meta($page_id, 'agni_page_title_hide', true) );
                $page_title_align = esc_attr( get_post_meta($page_id, 'agni_page_title_align', true) );
                $page_bg_color = esc_attr( get_post_meta($page_id, 'agni_page_bg_color', true) );
                $page_bg_gradient = esc_attr( get_post_meta($page_id, 'agni_page_bg_gradient', true) );
                $page_margin_remove = esc_attr( get_post_meta($page_id, 'agni_page_margin_remove', true) );

                $page_sidebar_choice = esc_attr( get_post_meta($page_id, 'agni_page_sidebar_choice', true) );


                if( empty( $page_title_hide ) ){
                    $page_title_hide = cartify_get_theme_option( 'page_settings_general_title', '' );
                }
                if( empty( $page_margin_remove ) ){
                    $page_margin_remove = cartify_get_theme_option( 'page_settings_general_remove_margin', '' );
                }

                $styles = "";

                if( !empty( $page_bg_color ) ){
                    $styles .= "
                    .page-id-{$page_id}{
                        background-color: {$page_bg_color};
                    }
                    ";
                }

                if( !empty( $page_bg_gradient ) ){
                    $styles .= "
                    .page-id-{$page_id}{
                        background-image: {$page_bg_gradient};
                    }
                    ";
                }

                wp_add_inline_style( 'cartify-custom-style', $styles );
                wp_enqueue_style( 'cartify-custom-style' );

                ?>
                <?php while (have_posts()) {
                    the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <?php if( $page_title_hide != 'on' ){ 
                        $entry_header_classes = array(
                            'entry-header',
                            ( !empty( $page_title_align ) && $page_title_align != 'left' ) ? 'has-align-' . $page_title_align : ''
                        )
                        ?>
                        <header class="<?php echo esc_attr( cartify_prepare_classes( $entry_header_classes ) ); ?>">
                            <div class="entry-header-bg"><?php 
                                if( has_post_thumbnail() ){
                                    echo wp_kses( the_post_thumbnail(), 'img' );
                                } ?>
                            </div>
                            <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                        </header>
                        <?php } ?>
                        <?php  
                        $entry_content_classes = array(
                            'entry-content',
                            ( $page_margin_remove == 'on' ) ? 'has-no-margin' : ''
                        )
                        ?>
                        <div class="<?php echo esc_attr( cartify_prepare_classes( $entry_content_classes ) ); ?>">
                            <?php do_action( 'agni_page_before_contents' ); ?>
                            <?php the_content(); ?>
                            <?php wp_link_pages(array(
                                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'cartify'),
                                    'after'  => '</div>',
                                ));
                            ?>
                            <?php do_action( 'agni_page_after_contents' ); ?>
                        </div>
                        <?php do_action( 'agni_page_sidebar' ); ?>
                    </article>

                    <?php
                        // If comments are open or we have at least one comment, load up the comment template.
                        if (comments_open() || get_comments_number()) :
                            comments_template();
                        endif;
                    ?>
                <?php } ?>


            </main>
        </div>
<?php
    }
}

if (!function_exists('cartify_page_sidebar')) {
    function cartify_page_sidebar(){
        // $blog_single_sidebar = $blog_sidebar = !$disableSidebar;

        $page_id = get_the_id();

        $sidebar = get_post_meta( $page_id, 'agni_page_sidebar_choice', true );

                if( is_page() ){
            // if( $sidebar != 'no-sidebar' && $sidebar != '' ){
            if( !empty($sidebar) && $sidebar !== 'no-sidebar' ){
                get_sidebar();
            }  
        }

            }
}

function cartify_page_slider(){

    // print_r( get_option('agni_slider_builder_sliders') );

    // do action for slider remove all contents


    $page_id = get_the_ID();

        $slider_id = esc_attr( get_post_meta($page_id, 'agni_slider_id', true) );

    // $slider_id = '0';
    if( $slider_id !== '' ){
        do_action( 'agni_slider', $slider_id );
    }

    ?>

        <?php
}


function cartify_page_portfolio( $block_args = array() ){


        $portfolio_archive_choice_default = '';
    $portfolio_archive_choice = cartify_get_theme_option('portfolio_settings_archive_page_choice', $portfolio_archive_choice_default);

    if( $portfolio_archive_choice != get_the_id() ){
        return;
    }

    do_action( 'agni_page_portfolio', $block_args );

}

function cartify_page_compare(){


        $compare_page_choice_default = '';
    $compare_page_choice = cartify_get_theme_option('shop_settings_compare_page_choice', $compare_page_choice_default);

    if( $compare_page_choice != get_the_id() ){
        return;
    }
    ?>
    <?php //if( function_exists('cartify_compare_display_products') ){
        do_action( 'agni_woocommerce_compare_page' );
    // } ?>
    <?php 
}

function cartify_page_post_classes($classes){

    $page_id = get_the_ID();

    $sidebar = get_post_meta( $page_id, 'agni_page_sidebar_choice', true );

    if( !empty($sidebar) && $sidebar !== 'no-sidebar' ){
        $classes[] = 'has-' . $sidebar . '-sidebar';
    }


        return array_unique( $classes );
}

function agni_woocommerce_compare_body_class($classes){

    $compare_page_choice_default = '';
    $compare_page_choice = cartify_get_theme_option('shop_settings_compare_page_choice', $compare_page_choice_default);

    if( $compare_page_choice != get_the_id() ){
        return array_unique( $classes );
    }

    $classes[] = 'woocommerce';
    $classes[] = 'woocommerce-page';

        return array_unique( $classes );
}


