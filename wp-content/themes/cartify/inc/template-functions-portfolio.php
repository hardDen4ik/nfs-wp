<?php 

add_action( 'agni_page_portfolio', 'cartify_page_portfolio_archive', 10, 1 );

add_action( 'agni_portfolio_single', 'cartify_portfolio_single' );
add_action( 'agni_portfolio_single', 'cartify_portfolio_single_slider', 9 );

add_filter( 'agni_portfolio_single_navigation', 'cartify_portfolio_single_navigation' );

function cartify_page_portfolio_archive( $block_args = array() ){

    $blog_args = array(
        'post_type' => 'portfolio',
        'posts_per_page' => get_option( 'posts_per_page' ),
        'display_style' => cartify_get_theme_option( 'portfolio_settings_general_display_style', '1' ),
        'showButton' => cartify_get_theme_option( 'portfolio_settings_general_item_button_show', '1' ),
    );

    $args = wp_parse_args( $block_args, $blog_args );

    extract( $args );

    if( get_query_var('paged') != '' ){
        $paged = get_query_var('paged');
    }
    elseif( get_query_var('page') != '' ){
        $paged = get_query_var('page');
    }
    else{
        $paged = 1;
    }

    $args['paged'] = $paged;


        $blog_query = new WP_Query($args);

    $portfolio_items_classes = array(
        'portfolio-items',
        'has-display-style-' . $display_style,
    );

    ?>

    <div class="portfolio-items-container">
        <div class="<?php echo esc_attr( cartify_prepare_classes( $portfolio_items_classes ) ); ?>">
            <?php
            if ($blog_query->have_posts()) {
                while ($blog_query->have_posts()) { $blog_query->the_post();
                ?>
                <div id="post-<?php the_ID(); ?>" <?php post_class( 'portfolio-item' ); ?>>
                    <?php  ?>
                    <?php if( !isset($showThumbnail) || $showThumbnail ){ ?> 
                        <div class="portfolio-item-thumbnail"><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php 
                            apply_filters( 'agni_portfolio_item_thumbnail', the_post_thumbnail() ); 
                        ?></a></div>
                    <?php } ?>
                    <div class="portfolio-item-details">
                        <?php if( !isset($showCategory) || $showCategory ){ ?> 
                            <div class="portfolio-item-category">
                                <?php echo apply_filters( 'agni_portfolio_item_category', cartify_portfolio_cat( get_the_ID() ) ); ?>
                            </div>
                        <?php } ?>
                        <?php if( !isset($showTitle) || $showTitle ){ ?> 
                            <h2 class="portfolio-item-title"><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo wp_kses( get_the_title(), 'title' ); ?></a></h2>
                        <?php } ?>
                        <?php if( !isset($showButton) || $showButton ){ ?>
                            <a class="portfolio-item-button" href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo esc_html__( 'Explore this item', 'cartify' ); ?></a>
                        <?php } ?>
                    </div>
                    <?php ?>
                </div>
                <?php }
            } 

            ?>
        </div>
        <?php 
        wp_reset_postdata();

        if( !isset($pagination) || !empty($pagination) ){
            cartify_portfolio_navigation( $blog_query );
        }


                ?>

                        </div>
    <?php 
}
function cartify_portfolio_navigation( $blog_query ){
    $paged = get_query_var( 'paged' );

        ?>
    <div class="navigation posts-pagination">
        <div class="nav-links">
            <?php echo wp_kses( paginate_links( array(

                                'base'         => esc_url_raw( str_replace( 999999999, '%#%', get_pagenum_link( 999999999, false ) ) ), 
                'format'       => '',
                'add_args'     => '',
                'current'      => max( 1, $paged ),
                'total'        => $blog_query->max_num_pages,
                'prev_next'    => false,
                'mid_size'     => 1,
            )), array(
                'i' => array( 'class' => array() ),
                'span' => array( 'class' => array() ),
                'a' => array( 'href' => array(), 'class' => array() )
            ) );
            ?>
        </div>
    </div>
    <?php
}

if( !function_exists( 'cartify_portfolio_single_navigation' ) ){
    function cartify_portfolio_single_navigation( $post_id ){

        $previous_post = get_previous_post();
        $next_post = get_next_post();

        ?>
        <div class="post-navigation">
            <div class="post-navigation__prev">
            <?php 
                if( $previous_post ){
                    ?>
                    <a href="<?php echo esc_url( get_permalink( $previous_post->ID) ); ?>" class="post-navigation__link">
                        <span><?php echo esc_html__( 'Previous Post', 'cartify' ); ?></span>
                        <span class="post-navigation__title"><?php echo wp_kses( $previous_post->post_title, 'title' ); ?></span>
                    </a>
                    <?php 
                }
            ?>
            </div>
            <div class="post-navigation__next">
            <?php 
                if( $next_post ){
                    ?>
                    <a href="<?php echo esc_url( get_permalink( $next_post->ID) ); ?>" class="post-navigation__link">
                        <span><?php echo esc_html__( 'Next Post', 'cartify' ); ?></span>
                        <span class="post-navigation__title"><?php echo wp_kses( $next_post->post_title, 'title' ); ?></span>
                    </a>
                    <?php 
                }
            ?>
            </div>
        </div>
        <?php
    }
}


if( !function_exists('cartify_portfolio_single') ){
    /**
     * function for displaying single post.
     */
    function cartify_portfolio_single(){

        $show_button = false;

        $portfolio_single_classes = cartify_prepare_classes(array(
            'portfolio-single-page-container'
        ));

        $portfolio_single_btn_url = '#portfolio-single-content'; // get_the_permalink();

        ?>
        <div class="<?php echo esc_attr( $portfolio_single_classes ); ?>">
            <div id="primary" class="content-area">
                <main id="main" class="site-main">
                    <?php while ( have_posts() ) { the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                                                        <header class="entry-header portfolio-single-header">
                                <div class="portfolio-single-thumbnail"><?php 
                                    apply_filters( 'agni_portfolio_post_thumbnail', the_post_thumbnail() ); 
                                ?></div>
                                <?php // the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                                <div class="portfolio-single-header-content">
                                    <div class="portfolio-single-category">
                                        <?php echo apply_filters( 'agni_portfolio_item_category', cartify_portfolio_cat( get_the_ID() ) ); ?>
                                    </div>
                                    <h1 class="portfolio-single-title"><?php echo wp_kses( get_the_title(), 'title' ); ?></h1>
                                    <?php if($show_button){ ?>
                                        <a href="<?php echo esc_url( $portfolio_single_btn_url ) ?>"><?php echo esc_html__( 'Explore More', 'cartify' );?></a>
                                    <?php } ?>
                                </div>
                            </header>
                            <div id="portfolio-single-content" class="entry-content portfolio-single-content">
                                <?php the_content(); ?>
                            </div>
                        </article>
                    <?php } ?>
                    <div class="portfolio-single-navigation"><?php echo apply_filters( 'agni_portfolio_single_navigation', get_the_ID() ); ?></div>

                </main>
            </div>
        </div>
        <?php
    }
}


function cartify_portfolio_single_slider(){


    $page_id = get_the_ID();

        $slider_id = esc_attr( get_post_meta($page_id, 'agni_slider_id', true) );

    if( $slider_id !== '' ){
        do_action( 'agni_slider', $slider_id );
    }

    ?>

        <?php
}


function cartify_portfolio_cat( $portfolio_id ) {
	// Hide category and tag text for pages.
	if ( 'portfolio' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$portfolio_category_names = wp_get_object_terms( $portfolio_id, 'portfolio_category', array( 'fields' => 'names' ) );
        // print_r( $categories_list );
		if ( !empty($portfolio_category_names) ) { ?>
            <span class="cat-links">
                <?php  foreach ($portfolio_category_names as $key => $name) { ?>
                    <span><?php echo esc_html( $name ); ?></span>
                <?php } ?>
            </span>
            <?php
		}
	}
}