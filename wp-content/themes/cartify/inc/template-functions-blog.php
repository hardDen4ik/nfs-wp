<?php 

add_action( 'agni_sidebar', 'cartify_sidebar', 10, 1 );
add_action( 'agni_posts', 'cartify_posts', 10, 1 );

add_action( 'agni_post_single', 'cartify_post_single' );
add_action( 'agni_post_single', 'cartify_post_single_slider', 9 );
add_action( 'agni_post_single_sidebar', 'cartify_post_sidebar', 10, 1 );

function cartify_archive_header($blog_query){
    $archive_title    = '';
    $archive_subtitle = '';

    if ( is_search() ) {

        $archive_title = sprintf(
            '%1$s %2$s',
            '<span class="color-accent">' . esc_html__( 'Search:', 'cartify' ) . '</span>',
            '&ldquo;' . get_search_query() . '&rdquo;'
        );

        if ( $blog_query->found_posts ) {
            $archive_subtitle = sprintf(
                /* translators: %s: Number of search results. */
                _n(
                    'We found %s result for your search.',
                    'We found %s results for your search.',
                    $blog_query->found_posts,
                    'cartify'
                ),
                number_format_i18n( $blog_query->found_posts )
            );
        } else {
            $archive_subtitle = esc_html__( 'We could not find any results for your search. You can give it another try through the search form below.', 'cartify' );
        }
    } elseif ( is_archive() ) {
        $archive_title    = get_the_archive_title();
        $archive_subtitle = get_the_archive_description();
    }

    if ( $archive_title || $archive_subtitle ) {
        ?>

        <header class="archive-header">
            <div class="archive-header-inner">

                <?php if ( $archive_title ) { ?>
                    <h1 class="archive-title"><?php echo wp_kses( $archive_title, 'title' ); ?></h1>
                <?php } ?>

                <?php if ( $archive_subtitle ) { ?>
                    <div class="archive-subtitle"><?php echo wp_kses( wpautop( $archive_subtitle ), 'title' ); ?></div>
                <?php } ?>

            </div>
        </header>

        <?php
    }
}

function cartify_post_no_results(){

    if( is_search() ){ ?>
        <div class="no-search-results-form">
            <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ) ?>">
                <label>
                    <span class="screen-reader-text"><?php echo esc_html_x( 'Search for:', 'label', 'cartify' ); ?></span>
                    <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'cartify' ); ?>" value="<?php echo esc_attr( get_search_query() ) ?>" name="s" />
                </label>
                <button class="search-submit"></button>
            </form>
        </div>
    <?php } 
    else { ?>
        <p><?php echo esc_html__( 'There is no posts!', 'cartify' ); ?></p>
    <?php }
}

function cartify_post_date() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}


	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( get_option('time_format') ) ),
		esc_html( get_the_time( get_option( 'date_format' ) ) ),
		esc_attr( get_the_modified_date( get_option('time_format') ) ),
		esc_html( get_the_modified_date( get_option( 'date_format' ) ) )
	);

	$posted_on = sprintf(
		esc_html_x( '%s', 'post date', 'cartify' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

    return '<div class="posted-on">' . $posted_on . '</div>'; // WPCS: XSS OK.

        ?>
    <?php

}

function cartify_post_author() {

        ?>
    <div class="byline"><?php echo sprintf( 
        wp_kses( '<span>%s</span><span class="post-author-avatar">%s</span><span class="post-author-name">%s</span>', array( 'span' => array( 'class' => array() ) ) ), 
        esc_html__( 'By', 'cartify' ), 
        get_avatar( get_the_author_meta('ID'), 30 ),
        '<a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>' 
    ); ?></div>
    <?php 

}


function cartify_post_author_bio(){
    ?>
    <div class="author-bio">
        <div class="author-bio__avatar"><?php echo get_avatar( get_the_author_meta('email'), 60 ); ?></div>
        <div class="author-bio__details">
            <h5 class="author-bio__name"><?php echo get_the_author_meta( 'display_name', get_the_author_meta( 'ID' )); ?></h5>                
            <p class="author-bio__description"><?php the_author_meta('description'); ?></p>
            <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo esc_html__( 'View all posts', 'cartify' ); ?></a>
        </div>
    </div>
    <?php
}

function cartify_post_cat() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list();
		if ( $categories_list ) {
			return '<div class="cat-links">'.$categories_list.'</div>';
		}
	}
}

function cartify_post_tag() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ' ', 'cartify' ) );// comma deleted 
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( '%1$s', 'cartify' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}
}

function cartify_posts_navigation( $blog_query ){
    $paged = get_query_var( 'paged' );

    if( $blog_query->found_posts == 0 ){
        return;
    }
    ?>
    <div class="navigation posts-pagination">
        <div class="nav-links">
            <?php echo wp_kses( paginate_links( array(

                                'base'         => esc_url_raw( str_replace( 999999999, '%#%', get_pagenum_link( 999999999, false ) ) ), 
                'format'       => '',
                'add_args'     => '',
                'current'      => max( 1, $paged ),
                'total'        => $blog_query->max_num_pages,
                // 'prev_next'    => true,
                'prev_text' => sprintf(
                    '<i class="lni lni-chevron-left"></i><span class="nav-prev-text">%s</span>',
                    esc_html__( 'Newer Posts', 'cartify' )
                ), 
                'next_text' => sprintf(
                    '<span class="nav-next-text">%s</span><i class="lni lni-chevron-right"></i>',
                    esc_html__( 'Older Posts', 'cartify' )
                ),
                // 'type'         => 'list',
                // 'end_size'     => 1,
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



if (!function_exists('cartify_sidebar')) {
    function cartify_sidebar( $sidebar = 'no-sidebar' ) {

        if( $sidebar != 'no-sidebar' ){
            get_sidebar();
        }  

            }
}


if (!function_exists('cartify_post_sidebar')) {
    function cartify_post_sidebar( $sidebar = 'no-sidebar' ) {

        // if( $sidebar != 'no-sidebar' ){
            get_sidebar();
        // }  

            }
}


function cartify_post_navigation(){

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


/**
 * Post Excerpt
 */
if( !function_exists('cartify_excerpt') ){
    function cartify_excerpt( $charlength = null, $readmore = false ) {
        $excerpt = get_the_excerpt();
        $charlength++;

        if ( mb_strlen( $excerpt ) > $charlength ) {
            $subex = mb_substr( $excerpt, 0, $charlength - 5 );
            $exwords = explode( ' ', $subex );
            $excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
            if ( $excut < 0 ) {
                $excerpt = mb_substr( $subex, 0, $excut );
            } else {
                $excerpt = $subex;
            }
            if( $readmore == true ){
                $readmore = '<p><a class="more-link" href="'. get_permalink( get_the_ID() ) . '">' . esc_html__( 'Continue reading', 'cartify' ) . '</a></p>';
                $excerpt =  $excerpt.$readmore;
            }
        } 
        return $excerpt;
    }
}


if (!function_exists('cartify_posts')) {
    /**
     * function main posts
     */
    function cartify_posts( $block_args = array() ) {

        $blog_sidebar = '';

        $post_id = get_the_id();

        if( is_front_page() && is_home() ){
            // $blog_sidebar = cartify_get_theme_option( 'blog_settings_general_sidebar', 'no-sidebar' );
            $blog_sidebar = cartify_get_theme_option( 'blog_settings_general_sidebar', 'right' );
        }
        else if( is_home() ) {
            $post_id = get_option( 'page_for_posts' );
        }
        if( empty( $blog_sidebar ) ){
            $blog_sidebar = get_post_meta( $post_id, 'agni_page_sidebar_choice', true );
        }

                $blog_args = array(
            'display_style' => cartify_get_theme_option( 'blog_settings_general_display_style', '1' ),
            'sidebar' => !empty( $blog_sidebar ) ? $blog_sidebar : 'no-sidebar',
            'posts_per_page' => get_option( 'posts_per_page' )
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


        if( is_search() || is_archive() ){
            $blog_query = $GLOBALS['wp_query'];
        }

        cartify_archive_header( $blog_query );

        $blog_classes = array(
            (!isset($isBlock) || !$isBlock) ? 'blog-page-container' : '',
            'blog-post-style-' . $display_style
        );

        if(!isset($disableSidebar) || !empty($disableSidebar)){
            if( $sidebar != 'no-sidebar' ){
                $blog_classes[] = 'has-' . $sidebar . '-sidebar';
            }
        }

                ?>
        <div class="<?php echo esc_attr( cartify_prepare_classes( $blog_classes ) ); ?>">
            <div id="primary" class="content-area">
                <main id="main" class="site-main">
                    <?php
                    if ($blog_query->have_posts()) {
                        while ($blog_query->have_posts()) { $blog_query->the_post();
                        ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <?php if( $display_style == '1' ){  ?>
                                <?php if( !isset($showCategory) || $showCategory ){ ?> 
                                    <div class="blog-post-cat">
                                        <?php echo apply_filters( 'agni_post_date', cartify_post_cat() ); ?>
                                    </div>
                                <?php } ?>
                                <?php if( !isset($showTitle) || $showTitle ){ ?> 
                                    <h2 class="blog-post-title"><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo wp_kses( get_the_title(), 'title' ); ?></a></h2>
                                <?php } ?>
                                <div class="blog-post-meta">
                                    <?php if( !isset($showAuthor) || $showAuthor ){ ?> 
                                        <?php echo apply_filters( 'agni_post_author', cartify_post_author() ); ?>
                                    <?php } ?>
                                    <?php if( !isset($showDate) || $showDate ){ ?> 
                                        <?php echo apply_filters( 'agni_post_date', cartify_post_date() ); ?>
                                    <?php } ?>
                                </div>
                                <?php if( !isset($showThumbnail) || $showThumbnail ){ ?> 
                                    <div class="blog-post-thumbnail"><?php 
                                        apply_filters( 'agni_blog_post_thumbnail', the_post_thumbnail() ); 
                                    ?></div>
                                <?php } ?>
                                <?php if( !isset($showExcerpt) || $showExcerpt ){ ?> 
                                    <div class="blog-post-excerpt"><?php echo wp_kses_post( cartify_excerpt(240, true) ); ?></div>
                                <?php } ?>
                            <?php } 
                            else if( $display_style == '2' ){ ?>
                                <?php if( !isset($showThumbnail) || $showThumbnail ){ ?> 
                                    <div class="blog-post-thumbnail"><?php 
                                        apply_filters( 'agni_blog_post_thumbnail', the_post_thumbnail() ); 
                                    ?></div>
                                <?php } ?>
                                <?php if( !isset($showCategory) || $showCategory ){ ?> 
                                    <div class="blog-post-cat">
                                        <?php echo apply_filters( 'agni_post_date', cartify_post_cat() ); ?>
                                    </div>
                                <?php } ?>
                                <?php if( !isset($showTitle) || $showTitle ){ ?> 
                                    <h2 class="blog-post-title"><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo wp_kses( get_the_title(), 'title' ); ?></a></h2>
                                <?php } ?>
                                <?php if( !isset($showExcerpt) || $showExcerpt ){ ?> 
                                    <div class="blog-post-excerpt"><?php echo wp_kses_post( cartify_excerpt(100) ); ?></div>
                                <?php } ?>
                                <div class="blog-post-meta">
                                    <?php if( !isset($showAuthor) || $showAuthor ){ ?> 
                                        <?php echo apply_filters( 'agni_post_author', cartify_post_author() ); ?>
                                    <?php } ?>
                                    <?php if( !isset($showDate) || $showDate ){ ?> 
                                        <?php echo apply_filters( 'agni_post_date', cartify_post_date() ); ?>
                                    <?php } ?>
                                </div>
                            <?php } 
                            else if( $display_style == '3' ){ ?>
                                <?php if( !isset($showThumbnail) || $showThumbnail ){ ?> 
                                    <div class="blog-post-thumbnail"><?php 
                                        apply_filters( 'agni_blog_post_thumbnail', the_post_thumbnail() ); 
                                    ?></div>
                                <?php } ?>
                                <div class="blog-post-details">
                                    <?php if( !isset($showCategory) || $showCategory ){ ?> 
                                        <div class="blog-post-cat">
                                            <?php echo apply_filters( 'agni_post_date', cartify_post_cat() ); ?>
                                        </div>
                                    <?php } ?>
                                    <?php if( !isset($showTitle) || $showTitle ){ ?> 
                                        <h2 class="blog-post-title"><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo wp_kses( get_the_title(), 'title' ); ?></a></h2>
                                    <?php } ?>
                                    <?php if( !isset($showExcerpt) || $showExcerpt ){ ?> 
                                        <div class="blog-post-excerpt"><?php echo wp_kses_post( cartify_excerpt(100) ); ?></div>
                                    <?php } ?>
                                    <div class="blog-post-meta">
                                        <?php if( !isset($showAuthor) || $showAuthor ){ ?> 
                                            <?php echo apply_filters( 'agni_post_author', cartify_post_author() ); ?>
                                        <?php } ?>
                                        <?php if( !isset($showDate) || $showDate ){ ?> 
                                            <?php echo apply_filters( 'agni_post_date', cartify_post_date() ); ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } 
                            else if( $display_style == '4' ){ ?>
                                <?php if( !isset($showThumbnail) || $showThumbnail ){ ?> 
                                    <div class="blog-post-thumbnail"><?php 
                                        apply_filters( 'agni_blog_post_thumbnail', the_post_thumbnail() ); 
                                    ?></div>
                                <?php } ?>
                                <div class="blog-post-details">
                                    <?php if( !isset($showTitle) || $showTitle ){ ?> 
                                        <h2 class="blog-post-title"><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo wp_kses( get_the_title(), 'title' ); ?></a></h2>
                                    <?php } ?>
                                    <div class="blog-post-meta">
                                        <?php if( !isset($showAuthor) || $showAuthor ){ ?> 
                                            <?php echo apply_filters( 'agni_post_author', cartify_post_author() ); ?>
                                        <?php } ?>
                                        <?php if( !isset($showDate) || $showDate ){ ?> 
                                            <?php echo apply_filters( 'agni_post_date', cartify_post_date() ); ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </article>
                        <?php }
                    } else { 
                        cartify_post_no_results(); ?>
                    <?php }

                    ?>
                </main>
                <?php

                                wp_reset_postdata();

                if( !isset($pagination) || !empty($pagination) ){
                    cartify_posts_navigation( $blog_query );
                }

                                ?>
            </div>

                                <?php if( !isset($disableSidebar) || !$disableSidebar ){ ?>
                <?php do_action( 'agni_sidebar', $sidebar ); ?>
            <?php } ?>
        </div>
    <?php }
}

if( !function_exists('cartify_post_single') ){
    /**
     * function for displaying single post.
     */
    function cartify_post_single(){

        $blog_single_sidebar = cartify_get_theme_option( 'blog_settings_single_sidebar', 'no-sidebar' );

        $blog_single_classes = cartify_prepare_classes(array(
            'blog-single-page-container',
            $blog_single_sidebar,
        ));

        ?>
        <div class="<?php echo esc_attr( $blog_single_classes ); ?>">
            <div id="primary" class="content-area">
                <main id="main" class="site-main">
                    <?php 
                    while ( have_posts() ) { the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                                                        <header class="entry-header">
                                <div class="blog-post-thumbnail"><?php 
                                    apply_filters( 'agni_blog_post_thumbnail', the_post_thumbnail() ); 
                                ?></div>
                                <?php // the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                                <div class="blog-post-cat">
                                    <?php echo apply_filters( 'agni_post_date', cartify_post_cat() ); ?>
                                </div>
                                <h1 class="blog-post-title"><?php echo wp_kses( get_the_title(), 'title' ); ?></h1>
                                <div class="blog-post-meta">
                                    <?php echo apply_filters( 'agni_post_author', cartify_post_author() ); ?>
                                    <?php echo apply_filters( 'agni_post_date', cartify_post_date() ); ?>
                                </div>
                            </header>
                            <div class="entry-content">
                                <?php the_content(); ?>
                                <?php
                                    wp_link_pages( array(
                                        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'cartify' ),
                                        'after'  => '</div>',
                                    ) );
                                ?>
                            </div>
                        </article>
                    <?php } ?>
                    <div class="agni-post-author-bio"><?php echo apply_filters( 'agni_post_author_bio', cartify_post_author_bio() ) ?></div>
                    <div class="agni-post-navigation"><?php echo apply_filters( 'agni_post_navigation', cartify_post_navigation() ); ?></div>
                    <?php
                        // If comments are open or we have at least one comment, load up the comment template.
                        if ( comments_open() || get_comments_number() ) :
                            comments_template();
                        endif;
                    ?>
                </main>
            </div>

            <?php // do_action( 'agni_post_single_sidebar', $blog_single_sidebar ); ?>
        </div>
        <?php
    }
}



function cartify_post_single_slider(){


    $page_id = get_the_ID();

        $slider_id = esc_attr( get_post_meta($page_id, 'agni_slider_id', true) );

    if( $slider_id !== '' ){
        do_action( 'agni_slider', $slider_id );
    }

    ?>

        <?php
}