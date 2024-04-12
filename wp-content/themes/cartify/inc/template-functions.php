<?php

// Actions
add_action( 'after_setup_theme', 'cartify_add_theme_support' );
add_action( 'after_setup_theme', 'cartify_register_nav_menus' );

add_action( 'widgets_init', 'cartify_widgets_init' );
add_action( 'wp_head', 'cartify_pingback_header' );

add_action( 'agni_body_open_tag', 'cartify_body_open_tag' );
add_action( 'agni_body_close_tag', 'cartify_body_close_tag' );
add_action( 'agni_page_open_tag', 'cartify_page_open_tag' );
add_action( 'agni_page_close_tag', 'cartify_page_close_tag' );
add_action( 'agni_content_open_tag', 'cartify_content_open_tag' );
add_action( 'agni_content_close_tag', 'cartify_content_close_tag' );

add_action( 'wp_enqueue_scripts', 'cartify_scripts' );
add_action( 'admin_enqueue_scripts', 'cartify_admin_scripts' );
add_action( 'enqueue_block_assets', 'cartify_block_assets' );
add_action( 'enqueue_block_editor_assets', 'cartify_block_editor_assets' );
// add_action( 'wp_enqueue_scripts', 'cartify_remove_default_block_styles' );

// Filters
add_filter( 'agni_content_block', 'cartify_agni_content_block', 10, 1 );
add_filter( 'agni_get_posttype_options', 'cartify_posttype_options', 10, 2 );
add_filter( 'wp_kses_allowed_html', 'cartify_allowed_html_tags', 10, 2 );

/**
 * Setup theme related features & functions.
 */
function cartify_add_theme_support()
{
    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');
    // add_image_size('cartify-standard-thumbnail', 960, 0, true);
    // add_image_size('cartify-post-thumbnail', 960, 520, true);

    /*
	 * Enable support for WooCommerce.
	 * See http://docs.woothemes.com/documentation/plugins/woocommerce/
	 */
    add_theme_support('woocommerce');

    // add_theme_support( 'wc-product-gallery-zoom' );
    // add_theme_support( 'wc-product-gallery-lightbox' );
    // add_theme_support( 'wc-product-gallery-slider' );

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for Block Styles.
    add_theme_support('wp-block-styles');

    // Add support for full and wide align images.
    add_theme_support('align-wide');

    // Add support for editor styles.
    add_theme_support('editor-styles');

    // Add support for editor styles.
    add_theme_support('editor-styles');

    // Enqueue editor styles.
    add_editor_style('assets/css/blocks/prefixed-editor-style.css');

    // Add support Block Templates
    add_theme_support( 'block-templates' );

    // Add support for responsive embedded content.
    add_theme_support('responsive-embeds');

}

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function cartify_register_nav_menus(){

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'cartify'),
        'secondary' => esc_html__('Additional Menu', 'cartify'),
        // 'ternary' => esc_html__('Ternary Menu', 'cartify'),
        'category' => esc_html__('Category Menu', 'cartify'),
        // 'footer' => esc_html__('Footer Menu', 'cartify'),
    ));
}


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function cartify_widgets_init()
{
    // Sidebar widget
    register_sidebar(array(
        'name' => esc_html__('Sidebar', 'cartify'),
        'id' => 'cartify-sidebar-1',
        'description' => esc_html__('Add widgets here.', 'cartify'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));

    // Footer widget
    register_sidebar(array(
        'name' => esc_html__('Footerbar', 'cartify'),
        'id' => 'cartify-footerbar-1',
        'description' => esc_html__('Add widgets here.', 'cartify'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}

if (!function_exists('cartify_scripts')) {
    /**
     * Enqueue Frontend Scripts & Styles
     */
    function cartify_scripts()
    {

        // Registering CSS
        wp_register_style( 'lineicons', AGNI_FRAMEWORK_CSS_URL . '/LineIcons.min.css', array(), '1.0.1' );
        wp_register_style( 'font-awesome', AGNI_FRAMEWORK_CSS_URL . '/fontawesome.min.css', array(), '5.12' );
        wp_register_style( 'slick', AGNI_FRAMEWORK_CSS_URL . '/slick.min.css', array(), wp_get_theme()->get('Version') );
        wp_register_style( 'cartify-photoswipe-style', AGNI_FRAMEWORK_CSS_URL . '/photoswipe.min.css', array(), wp_get_theme()->get('Version') );
        wp_register_style( 'cartify-custom-style', AGNI_FRAMEWORK_CSS_URL . '/custom.css', array(), wp_get_theme()->get('Version') );
        // wp_enqueue_style('lineicons');

        wp_enqueue_style( 'lineicons' );
        wp_enqueue_style( 'font-awesome' );

                wp_register_style( 'cartify-animista', AGNI_FRAMEWORK_CSS_URL . '/animista.min.css', array(), wp_get_theme()->get('Version') ); 


                // Enqueue Styles
        wp_enqueue_style( 'cartify-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version') );
        wp_style_add_data( 'cartify-style', 'rtl', 'replace' );


        // Registering JS
        //wp_register_script('cartify-ajax-search', AGNI_FRAMEWORK_JS_URL . 'agni-ajax-search/agni-ajax-search.js', array('jquery'), '1.0');
        wp_register_script( 'slick', AGNI_FRAMEWORK_JS_URL . '/slick.min.js', array( 'jquery' ), wp_get_theme()->get('Version'), true );
        wp_register_script( 'easyzoom', AGNI_FRAMEWORK_JS_URL . '/easyzoom.min.js', array(), wp_get_theme()->get('Version'), true );
        wp_register_script( 'threesixty', AGNI_FRAMEWORK_JS_URL . '/threesixty.min.js', array( 'jquery' ), wp_get_theme()->get('Version'), true );
        wp_register_script( 'js-cookie', AGNI_FRAMEWORK_JS_URL . '/js.cookie.min.js', array(), wp_get_theme()->get('Version'), true );

        // wp_register_script( 'resize-sensor', AGNI_FRAMEWORK_JS_URL . '/resize-sensor.min.js', array( 'jquery' ), wp_get_theme()->get('Version'), true );
        // wp_register_script( 'sticky-sidebar', AGNI_FRAMEWORK_JS_URL . '/sticky-sidebar.min.js', array( 'jquery', 'resize-sensor' ), wp_get_theme()->get('Version'), true );
        wp_register_script( 'cartify-photoswipe-script', AGNI_FRAMEWORK_JS_URL . '/photoswipe.min.js', array( 'jquery' ), wp_get_theme()->get('Version'), true );
        wp_register_script( 'cartify-mb.ytplayer', AGNI_FRAMEWORK_JS_URL . '/jquery.mb.YTPlayer.min.js', array( 'jquery' ), '', true );
        wp_register_script( 'cartify-mb.vimeo_player', AGNI_FRAMEWORK_JS_URL . '/jquery.mb.vimeo_player.min.js', array( 'jquery' ), '', true );
        wp_register_script( 'cartify-rellax', AGNI_FRAMEWORK_JS_URL . '/rellax.min.js', '', '', true );
        wp_register_script( 'cartify-waypoint', AGNI_FRAMEWORK_JS_URL . '/jquery.waypoints.min.js', '', '', true );
        // Enqueueing JS
        wp_register_script( 'cartify-youtube-iframe_api', 'https://www.youtube.com/iframe_api', array( 'jquery' ), wp_get_theme()->get('Version'), true );


        // Threaded comment reply styles.
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }

                // Enqueueing JS
        wp_enqueue_script( 'cartify-scripts', AGNI_FRAMEWORK_JS_URL . '/scripts.js', array( 'jquery' ), wp_get_theme()->get('Version'), true );

    }
}


if (!function_exists('cartify_admin_scripts')) {
    /**
     * Enqueue Backend Scripts & Styles
     */
    function cartify_admin_scripts(){

        wp_register_style('lineicons', AGNI_FRAMEWORK_CSS_URL . '/LineIcons.min.css', array(), '1.0.1');
        wp_register_style('font-awesome', AGNI_FRAMEWORK_CSS_URL . '/fontawesome.min.css', array(), '5.12');

                // Enqueueing CSS for admin 
        wp_enqueue_style('cartify-admin-style', AGNI_FRAMEWORK_CSS_URL . '/admin/admin-style.css', array(), wp_get_theme()->get('Version'));

    }
}

if( !function_exists('cartify_block_assets') ){
    function cartify_block_assets(){

        // // Registering CSS
        // wp_register_style('lineicons', AGNI_FRAMEWORK_CSS_URL . '/LineIcons.min.css', array(), '1.0.1');
        // wp_register_style('font-awesome', AGNI_FRAMEWORK_CSS_URL . '/fontawesome.min.css', array(), '5.12');

        wp_enqueue_style('cartify-block-styles', AGNI_FRAMEWORK_CSS_URL . '/blocks/blocks-style.css', array(), wp_get_theme()->get('Version'));
        wp_style_add_data( 'cartify-block-styles', 'rtl', 'replace' );
    }
}

if( !function_exists('cartify_block_editor_assets') ){

        function cartify_block_editor_assets(){

        wp_register_style('lineicons', AGNI_FRAMEWORK_CSS_URL . '/LineIcons.min.css', array(), '1.0.1');
        wp_register_style('font-awesome', AGNI_FRAMEWORK_CSS_URL . '/fontawesome.min.css', array(), '5.12');


        wp_enqueue_style('lineicons');
        wp_enqueue_style('font-awesome');
        // wp_enqueue_style('cartify-animista');


             // Enqueue Styles
        wp_enqueue_style('cartify-block-editor-styles', AGNI_FRAMEWORK_CSS_URL . '/blocks/editor-style.css', array(), wp_get_theme()->get('Version'));

    }
}

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function cartify_pingback_header() {
	if ( is_singular() && pings_open() ) {
        ?>
		<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>">
        <?php
	}
}


/**
 * Getting Posts from the posttype
 */
if( !function_exists('cartify_posttype_options') ){
    function cartify_posttype_options( $query_args, $empty = false  ) {
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


if (!function_exists('cartify_body_open_tag')) {
    /**
     * Adding Body Open Tag
     */
    function cartify_body_open_tag(){ ?>
        <body <?php body_class(); ?>>
            <?php wp_body_open(); ?>
    <?php }
}

if (!function_exists('cartify_body_close_tag')) {
    /**
     * Adding Body Close Tag
     */
    function cartify_body_close_tag(){ ?>               
            <?php wp_footer(); ?>
        </body>
    <?php }
}


if (!function_exists('cartify_page_open_tag')) {
    /**
     * Adding Page Open Tag
     */
    function cartify_page_open_tag(){ ?>
        <div id="page" class="site">
    <?php }
}

if (!function_exists('cartify_page_close_tag')) {
    /**
     * Adding Page Close Tag
     */
    function cartify_page_close_tag(){ ?>
        </div>
    <?php }
}

if (!function_exists('cartify_content_open_tag')) {
    /**
     * Adding Content Open Tag
     */
    function cartify_content_open_tag(){ ?>
        <div id="content" class="site-content">
    <?php }
}

if (!function_exists('cartify_content_close_tag')) {
    /**
     * Adding Content Close Tag
     */
    function cartify_content_close_tag(){ ?>
        </div>
    <?php }
}


// Get Agni Block Contents
function cartify_agni_content_block( $block_id = '' ){
    if( empty( $block_id ) ){
        return;
    }

        return apply_filters( 'the_content', get_post_field('post_content', $block_id) );

}

// function cartify_remove_default_block_styles(){
//     wp_dequeue_style( 'wp-block-library' );
//     wp_dequeue_style( 'wp-block-library-theme' );
//     wp_dequeue_style( 'wc-blocks-style' ); // Remove WooCommerce block CSS
// }


function cartify_allowed_html_tags( $tags, $context ) {

    switch( $context ){
        case 'title' : 
            $tags = array(
                'a'          => array(
                    'href'     => true,
                    'rel'      => true,
                    'rev'      => true,
                    'name'     => true,
                    'target'   => true,
                    'download' => array(
                        'valueless' => 'y',
                    ),
                ),
                'h1'         => array(
                    'align' => true,
                    'class' => true,
                    'id' => true
                ),
                'h2'         => array(
                    'align' => true,
                    'class' => true,
                    'id' => true
                ),
                'h3'         => array(
                    'align' => true,
                    'class' => true,
                    'id' => true
                ),
                'h4'         => array(
                    'align' => true,
                    'class' => true,
                    'id' => true
                ),
                'h5'         => array(
                    'align' => true,
                    'class' => true,
                    'id' => true
                ),
                'h6'         => array(
                    'align' => true,
                    'class' => true,
                    'id' => true
                ),
                'i'          => array(),
                'img'        => array(
                    'alt'      => true,
                    'align'    => true,
                    'border'   => true,
                    'height'   => true,
                    'hspace'   => true,
                    'loading'  => true,
                    'longdesc' => true,
                    'vspace'   => true,
                    'src'      => true,
                    'usemap'   => true,
                    'width'    => true,
                ),
                'span'       => array(
                    'align' => true,
                ),
                'small'      => array(),
                'strike'     => array(),
                'strong'     => array(),
                'sub'        => array(),
                'sup'        => array(),
                'u'          => array(),
            );
            break;
        case 'img': 
            $tags = array( 
                'img' => array(
                    'width' => array(),
                    'height' => array(),
                    'src' => array(),
                    'srcset' => array(),
                    'class' => array(),
                    'alt' => array(),
                    'loading' => array(),
                    'sizes' => array()
                )
            );
            break;
        case 'svg': 
            $tags = array(
                'svg' => array(
                    'xmlns' => array(),
                    'fill' => array(),
                    'viewbox' => array(),
                    'width' => array(),
                    'height' => array(),
                    'role' => array(),
                    'aria-hidden' => array(),
                    'focusable' => array(),
                ),
                'path' => array(
                    'd' => array(),
                    'fill' => array(),
                    'transform' => array(),
                ),
                'g' => array(
                    'transform' => array()
                ),
                'circle' => array(
                    'cx' => array(),
                    'cy' => array(),
                    'r' => array(),
                    'style' => array()
                )
            );
            break;
        case 'form' : 
            $tags = array(
                'form' => array(
                    'class' => array(),
                    'data-product_id' => array(),
                    'data-product_variations' => array(),
                ),
                'select' => array(
                    'class' => array(),
                    'id' => array(),
                    'name' => array(),
                    'data-attribute_name' => array(),
                    'show_option_none' => array()
                ),
                'option' => array(
                    'class' => array(),
                    'value' => array(),
                ),
                'input' => array(
                    'type' => array(),
                    'name' => array(),
                    'value' => array(),
                )
            );
            break;
        case 'price': 
            $tags = array(
                'del' => array(
                    'aria-hidden' => array(),
                    'class' => array(),
                ),
                'ins' => array(
                    'class' => array(),
                ),
                'span' => array(
                    'class' => array(),
                )
            );
            break;

    }

    return $tags;

}


/**
 * Gets the SVG code for a given icon.
 */
function cartify_get_icon_svg( $group, $icon, $size = 24 ){
    return Cartify_SVG_Icons::get_svg($group, $icon, $size);
}

function cartify_prepare_classes( $classes ){
    return trim( preg_replace('!\s+!', ' ', join(' ', $classes) ) );
}

function cartify_prepare_slick_options( $options ){
    $options = preg_replace('/[\s+\']/', '', $options);
    $options = preg_replace( '/(\w+):/', '"$1":', $options );

    return '{' . $options . '}';
}

function cartify_prepare_icon( $icon = '' ){
	$icon_html = '';

		if( preg_match('/feather:/', $icon, $matches) ) {
		$icon_name = str_replace( $matches[0], '', $icon );
        $icon_html = '<img width="20" height="20" src="' . esc_url( AGNI_FRAMEWORK_ICONS_URL . '/feather/' . esc_attr( $icon_name ) . '.svg' ) . '" alt="" />';

			}
	else if( preg_match('/ionicons:/', $icon, $matches) ){
		$icon_name = str_replace( $matches[0], '', $icon );
        $icon_html = '<img width="20" height="20" src="' . esc_url( AGNI_FRAMEWORK_ICONS_URL . '/ionicons-outlined/' . esc_attr( $icon_name ) . '.svg' ) . '" alt="" />';
	}
	else if( preg_match('/miscellaneous:/', $icon, $matches) ){
		$icon_name = str_replace( $matches[0], '', $icon );
        $icon_html = '<img width="20" height="20" src="' . esc_url( AGNI_FRAMEWORK_ICONS_URL . '/miscellaneous/' . esc_attr( $icon_name ) . '.svg' ) . '" alt="" />';
	}
	else if( preg_match('/ fa-/', $icon, $matches) ){
		$icon_name = str_replace( 'fontawesome:', '', $icon );
        $icon_html = '<i class="' . esc_attr( $icon_name ) . '"></i>';
	}
	else if( preg_match('/lni /', $icon, $matches) ){
		$icon_name = str_replace( 'iconicons:', '', $icon );
        $icon_html = '<i class="' . esc_attr( $icon_name ) . '"></i>';
	}

	return $icon_html;
	// return $icon_src
}


function cartify_prepare_css_styles($css_array){
    $css_values_array = array();
    foreach ($css_array as $key => $value) {
        if(!empty($value)){
            array_push( $css_values_array, "{$key}:{$value};" );
        }
    }
    return implode(' ', array_filter($css_values_array));
}

function cartify_prepare_responsive_values($value_array = array(), $default = false){
    $new_value_array = [];
    $default_array = [];

    $default_array = $default;

    if( !is_array( $default ) ){
        $default_array = array(
            'desktop' => $default,
            'laptop' => $default,
            'tab' => $default,
            'mobile' => $default,
        );
    }

    $new_value_array = array(
        'desktop' => isset( $value_array['desktop'] ) ? $value_array['desktop'] : $default_array['desktop'],
        'laptop' => isset( $value_array['laptop'] ) ? $value_array['laptop'] : $default_array['laptop'],
        'tab' => isset( $value_array['tab'] ) ? $value_array['tab'] : $default_array['tab'],
        'mobile' => isset( $value_array['mobile'] ) ? $value_array['mobile'] : $default_array['mobile'],
    );

    // echo "value array \n\n";
    // print_r( $new_value_array );
    // echo "value default \n\n";
    // print_r( $default_array );
    // echo "value ends \n\n";

    return $new_value_array;
}

function cartify_get_header_list_dropdown( $empty = false ){
    $heaer_options = array();

    $header_list = get_option('agni_header_builder_headers_list');
    if( $empty ){
        $heaer_options[''] = 'Default/Inherit';
    }
    if( !empty( $header_list ) ){
        foreach ($header_list as  $header) {
            $heaer_options[$header['id']] = $header['title'];
        }
    }

    return $heaer_options;
}


function cartify_get_theme_option( $option_name, $option_value = '' ){
    global $cartify_theme_options;

    $cartify_theme_mods = get_theme_mods();

    if( isset( $cartify_theme_options[$option_name] ) ){
        return $cartify_theme_options[$option_name];
    }
    else{
        return isset($cartify_theme_mods[$option_name]) ? $cartify_theme_mods[$option_name] : $option_value;
    }
}




/* Admin Menu Panel */
require_once AGNI_TEMPLATE_DIR . '/admin/admin.php';

/* Agni Plugins Installer */
require_once AGNI_TEMPLATE_DIR . '/product-registration/class-product-registration.php';

/* Agni Plugins Installer */
require_once AGNI_TEMPLATE_DIR . '/plugins-installer/class-plugins-installer.php';

// configuration for Kirki theme options
require AGNI_TEMPLATE_DIR . '/custom-kirki-options.php';

// class for storing svg icons
require AGNI_TEMPLATE_DIR . '/class-template-svg-icons.php';


// functions for displaying basic theme stuffs
require AGNI_TEMPLATE_DIR . '/class-template-header.php';
require AGNI_TEMPLATE_DIR . '/template-functions-footer.php';
require AGNI_TEMPLATE_DIR . '/template-functions-terms.php';
require AGNI_TEMPLATE_DIR . '/template-functions-page.php';
require AGNI_TEMPLATE_DIR . '/template-functions-blog.php';
require AGNI_TEMPLATE_DIR . '/template-functions-portfolio.php';
require AGNI_TEMPLATE_DIR . '/template-functions-404.php';
require AGNI_TEMPLATE_DIR . '/template-functions-body.php';

// Including Agni Megamenu
require AGNI_TEMPLATE_DIR . '/agni-megamenu.php';

// Including Agni Slider
require AGNI_TEMPLATE_DIR . '/class-template-slider.php';

// Including Agni Product Templates
require AGNI_TEMPLATE_DIR . '/class-template-product-layouts.php';

// functions for displaying woocommerce stuffs
if (class_exists('WooCommerce')) {

    // Theme WooCommerce function
    require AGNI_TEMPLATE_DIR . '/template-functions-woocommerce.php';

    // Custom tabs for woocommerce
    require AGNI_TEMPLATE_DIR . '/agni-custom-tabs/agni-custom-tabs.php';

    // Recently viewed products
    require AGNI_TEMPLATE_DIR . '/agni-recently-viewed-products.php';

    // agni login/register form
    require AGNI_TEMPLATE_DIR . '/agni-ajax-login.php';

    // Search form options
    require AGNI_TEMPLATE_DIR . '/agni-ajax-search.php';

    // agni sidecart options
    require AGNI_TEMPLATE_DIR . '/agni-ajax-sidecart.php';

    // agni swatches options
    require AGNI_TEMPLATE_DIR . '/agni-ajax-variation-swatches/agni-ajax-variation-swatches.php';

    // agni quickview
    require AGNI_TEMPLATE_DIR . '/agni-quickview.php';

    // agni wishlist 
    require AGNI_TEMPLATE_DIR . '/agni-wishlist/agni-wishlist.php';

    // agni compare
    require AGNI_TEMPLATE_DIR . '/agni-compare/agni-compare.php';

    // agni addon products
    require AGNI_TEMPLATE_DIR . '/agni-addon-products/agni-addon-products.php';

    // agni additional variation images options
    require AGNI_TEMPLATE_DIR . '/agni-additional-variation-images/agni-additional-variation-images.php';

    // agni multistep checkout tabs
    include AGNI_TEMPLATE_DIR . '/agni-multistep-navigation.php';

}