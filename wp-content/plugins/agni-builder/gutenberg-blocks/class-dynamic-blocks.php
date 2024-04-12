<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
if( !class_exists('AgniBuilderDynamicBlocks') ){
    class AgniBuilderDynamicBlocks{

        private $category_button = '';

        private $category_image_size = '';

        public function __construct(){

            $this->register_blocks();

            $this->includes();


            // add_action( 'init', array( $this, 'includes' ), 99 );

            // add_action( 'init', array( $this, 'register_blocks' ), 98 );

        }

        public function includes(){
            require_once AGNI_BUILDER_PLUGIN_PATH . '/gutenberg-blocks/blocks/countdown.php';
            require_once AGNI_BUILDER_PLUGIN_PATH . '/gutenberg-blocks/blocks/slider.php';
            require_once AGNI_BUILDER_PLUGIN_PATH . '/gutenberg-blocks/blocks/menu.php';
            require_once AGNI_BUILDER_PLUGIN_PATH . '/gutenberg-blocks/blocks/products.php';
            require_once AGNI_BUILDER_PLUGIN_PATH . '/gutenberg-blocks/blocks/products-tab.php';
            require_once AGNI_BUILDER_PLUGIN_PATH . '/gutenberg-blocks/blocks/products-categories.php';
            require_once AGNI_BUILDER_PLUGIN_PATH . '/gutenberg-blocks/blocks/products-categories-tab.php';
            require_once AGNI_BUILDER_PLUGIN_PATH . '/gutenberg-blocks/blocks/brands.php';
            require_once AGNI_BUILDER_PLUGIN_PATH . '/gutenberg-blocks/blocks/content-block.php';
            require_once AGNI_BUILDER_PLUGIN_PATH . '/gutenberg-blocks/blocks/posts.php';
            require_once AGNI_BUILDER_PLUGIN_PATH . '/gutenberg-blocks/blocks/portfolio.php';
            require_once AGNI_BUILDER_PLUGIN_PATH . '/gutenberg-blocks/blocks/recently-viewed-products.php';
        }

        public function register_blocks(){

            register_block_type('agni/columns', array(
                // 'style' => array( 'cartify-animista' )
                'render_callback' => function($attr, $content){
                    if( !is_admin() ){
                        if( isset($attr['animation']) && $attr['animation'] ){
                            wp_enqueue_style( 'cartify-animista' );
                        }
                    }

                    return $content;
                }
            ));

            register_block_type('agni/countdown', array(
                'render_callback' => 'dynamic_block_agni_countdown',
                'attributes' => array(
                    'displayStyle' => array(
                        'type' => 'string',
                        'default' => '1'
                    ),
                    'dateFrom' => array(
                        'type' => 'string'
                    ),
                    'dateTo' => array(
                        'type' => 'string'
                    ),
                    'timerIcon' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'showLabel' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'beforeText' => array(
                        'type' => 'string'
                    ),
                    'afterText' => array(
                        'type' => 'string'
                    ),
                    'prefixText' => array(
                        'type' => 'string'
                    ),
                    'suffixText' => array(
                        'type' => 'string'
                    ),
                    'customClassName' => array(
                        'type' => 'string'
                    )
                )
            ));

            register_block_type('agni/button', array(
                'render_callback' => function($attr, $content){

                    wp_enqueue_style( 'lineicons' );
                    wp_enqueue_style( 'font-awesome' );

                    return $content;
                }
            ));

            register_block_type('agni/icon', array(
                'render_callback' => function($attr, $content){

                    wp_enqueue_style( 'lineicons' );
                    wp_enqueue_style( 'font-awesome' );

                    return $content;
                }
            ));

            register_block_type('agni/icon-card', array(
                'render_callback' => function($attr, $content){

                    wp_enqueue_style( 'lineicons' );
                    wp_enqueue_style( 'font-awesome' );

                    return $content;
                }
            ));

            register_block_type('agni/list', array(
                'render_callback' => function($attr, $content){

                    wp_enqueue_style( 'lineicons' );
                    wp_enqueue_style( 'font-awesome' );

                    return $content;
                }
            ));

            register_block_type('agni/banners', array(
                'render_callback' => function($attr, $content){
                    if( !is_admin() ){

                        if( isset($attr['carousel']) && $attr['carousel'] ){
                            wp_enqueue_style( 'slick' );
                            wp_enqueue_script( 'slick' );
                        }
                    }

                    return $content;
                }
            ));

            register_block_type('agni/images', array(
                // 'style' => 'cartify-photoswipe-style',
                // 'script' => 'cartify-photoswipe-script',
                'editor_style' => 'slick',
                'render_callback' => function($attr, $content){
                    if( !is_admin() ){
                        if( isset($attr['lightbox']) && $attr['lightbox'] ){
                            wp_enqueue_style( 'cartify-photoswipe-style' );
                            wp_enqueue_script( 'cartify-photoswipe-script' );
                        }

                        if( isset($attr['carousel']) && $attr['carousel'] ){
                            wp_enqueue_style( 'slick' );
                            wp_enqueue_script( 'slick' );
                        }
                    }

                    return $content;
                }
            ));

            register_block_type('agni/video', array(
                'attributes' => array(
                    'videoProvider' => array(
                        'type' => 'string',
                        'default' => 'youtube'
                    ),
                ),
                'render_callback' => function( $attr, $content ){
                    if( !is_admin() ){

                        extract( $attr );

                        $scripts = "";

                        if($videoProvider == 'vimeo'){

                        }
                        else if($videoProvider == 'youtube'){
                            // echo "controls" . $controls;

                            if( !isset( $controls ) || empty( $controls ) ){
                                $controls = 0;
                            }
                            if( !isset( $autoplay ) || empty( $autoplay ) ){
                                $autoplay = 0;
                            }
                            if( !isset( $loop ) || empty( $loop ) ){
                                $loop = 0;
                            }
                            if( !isset( $loop ) || empty( $loop ) ){
                                $loop = 0;
                            }
                            

                            wp_enqueue_script( 'cartify-youtube-iframe_api' );
                        
                            $scripts = "
                                var player;
                                function onYouTubeIframeAPIReady() {
                                    player = new YT.Player('" . $videoSelector . "', {
                                        events: {
                                            'onReady': onPlayerReady
                                        }
                                    });
                                }
    
                                // 4. The API will call this function when the video player is ready.
                                function onPlayerReady(event) {
                                    console.log('its working');
                                    jQuery('.agni-block-video-controls--play').on('agniYoutubePlay', function(){
                                        event.target.playVideo();
                                    });
                                    jQuery('.agni-block-video-controls--pause').on('agniYoutubePause', function(){
                                        event.target.pauseVideo();
                                    })
                                }
                            ";
    
                        }

                       
                        wp_add_inline_script( 'cartify-youtube-iframe_api', $scripts );
                    }

                    return $content;
                }
            ));

            register_block_type('agni/map', array(
                'editor_script' => 'googleapi',
                'script' => 'googleapi',
            ));    

            register_block_type('agni/testimonials', array(
                'render_callback' => function( $attributes, $content = null ){
                    if( !is_admin() ){
                        if(isset($attributes['carousel']) && $attributes['carousel']){
                            wp_enqueue_style('slick');
                            wp_enqueue_script('slick');
                        }
                    }

                    return $content;
                }
            ));
            
            register_block_type('agni/products', array(
                // 'render_callback' => array( $this, 'register_dynamic_blocks_agni_products' ),
                'render_callback' => 'dynamic_blocks_agni_products',
                'attributes' => array(
                    'headingText' => array(
                        'type' => 'string',
                        'default' => 'Add Heading'
                    ),
                    'headingLevel' => array(
                        'type' => 'string',
                        'default' => '3'
                    ),
                    'headingCssFontSize' => array(
                        'type' => 'string',
                        'default' => '22px'
                    ),
                    'headingCssColor' => array(
                        'type' => 'string',
                    ),
                    'countdown' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'countdownPlacement' => array(
                        'type' => 'string',
                        'default' => '1'
                    ),
                    'buttonUrl' => array(
                        'type' => 'string',
                    ),
                    'buttonText' => array(
                        'type' => 'string',
                    ),
                    'buttonTarget' => array(
                        'type' => 'string',
                        'default' => '_self'
                    ),
                    'rel' => array(
                        'type' => 'string',
                        'default' => 'noreferrer noopener'
                    ),
                    'buttonCssColor' => array(
                        'type' => 'string'
                    ),
                    'header_style' => array(
                        'type' => 'string',
                        'default' => '1'
                    ),
                    'header_align' => array(
                        'type' => 'string',
                        'default' => 'left'
                    ),
                    'headerFilled' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'headerOutlined' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'headerCssBackgroundColor' => array(
                        'type' => 'string',
                    ),
                    'headerCssBackgroundImage' => array(
                        'type' => 'string',
                    ),
                    'headerCssBorderTopWidth' => array(
                        'type' => 'string',
                        'default' => '0px'
                    ),
                    'headerCssBorderBottomWidth' => array(
                        'type' => 'string',
                        'default' => '1px'
                    ),
                    'headerCssBorderColor' => array(
                        'type' => 'string',
                    ),
                    'divideLineDisplayStyle' => array(
                        'type' => 'string',
                        'default' => ''
                    ),
                    'divideLineWidth' => array(
                        'type' => 'string',
                        'default' => '52px'
                    ),
                    'divideLineHeight' => array(
                        'type' => 'string',
                        'default' => '12px'
                    ),
                    'divideLineSize' => array(
                        'type' => 'number',
                        'default' => 100
                    ),
                    'divideLineColor' => array(
                        'type' => 'string',
                        'default' => ''
                    ),
                    'divideLineAlign' => array(
                        'type' => 'string',
                        'default' => 'left'
                    ),
                    'columns' => array(
                        'type' => 'object',
                        'default' => array( 'desktop' => 4, 'laptop' => 4, 'tab' => 4, 'mobile' => 2 ),
                    ),
                    'rows' => array(
                        'type' => 'object',
                        'default' => array( 'desktop' => 1, 'laptop' => 1, 'tab' => 1, 'mobile' => 2 ),
                    ),
                    'productsSynced' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),

                    'widthRatio' => array(
                        'type' => 'array',
                    ),
                    'countOnGrid' => array(
                        'type' => 'string',
                        'default' => '4',
                    ),
                    'inlineProductsMobile' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'count' => array(
                        'type' => 'string',
                        'default' => '4',
                    ),
                    'paginationStyle' => array(
                        'type' => 'string',
                        'default' => '1'
                    ),
                    'products_type' => array(
                        'type' => 'string',
                        'default' => 'all'
                    ),
                    'product_ids' => array(
                        'type' => 'array',
                    ),
                    'category_ids' => array(
                        'type' => 'array',
                    ),
                    'order_by' => array(
                        'type' => 'string',
                        'default' => '5'
                    ),
                    'display_style' => array(
                        'type' => 'string',
                        'default' => '1'
                    ),
                    'stackOnMobile' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'imgMaxWidth' => array(
                        'type' => 'number',
                        'default' => ''
                    ),
                    'imgDisplayStyle' => array(
                        'type' => 'string',
                        'default' => ''
                    ),
                    'imgCssBorderRadius' => array(
                        'type' => 'string',
                        'default' => '0px'
                    ),
                    'product_qty' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'product_qty_choice' => array(
                        'type' => 'string',
                        'default' => '1'
                    ),
                    'product_title' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'product_desc' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'product_category' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'product_price' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'product_rating' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'product_buttons' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'product_stock' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'product_countdown' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'gap' => array(
                        'type' => 'string',
                        'default' => '14',
                    ),
                    'carousel' => array(
                        'type' => 'boolean',
                        'default' => false,
                    ),
                    'carouselAutoplay' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'carouselInfinite' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'carouselArrows' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'carouselDots' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),

                    'fullWidthBG' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
            
                    'backgroundColor' => array(
                        'type' => 'string',
                    ),
                    'backgroundGradient' => array(
                        'type' => 'string'
                    ),
                    'backgroundOverlayBackward' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'backgroundUrl' => array(
                        'type' => 'string',
                    ),
                    'backgroundId' => array(
                        'type' => 'number',
                    ),
                    'backgroundImagePosition' => array(
                        'type' => 'string',
                        'default' => '50% 50%'
                    ),
                    'backgroundImageRepeat' => array(
                        'type' => 'string',
                        'default' => 'repeat'
                    ),
                    'backgroundImageSize' => array(
                        'type' => 'string',
                        'default' => 'cover'
                    ),
                    'backgroundOpacity' => array(
                        'type' => 'number',
                    ),
                    'backgroundType' => array(
                        'type' => 'string',
                    ),
                    'backgroundFallbackUrl' => array(
                        'type' => 'string'
                    ),
                    'videoAutoplay' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'videoMuted' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'videoLoop' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'borderWidth' => array(
                        'type' => 'object',
                        'default' => array( 'top' => '0px', 'left' => '0px', 'right' => '0px', 'bottom' => '0px' )
                    ),
                    'cssBorderColor' => array(
                        'type' => 'string'
                    ),
                    'cssBorderRadius' => array(
                        'type' => 'string'
                    ),

                    'showPlaceholder' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                            
                    'customClassName' => array(
                        'type' => 'string',
                    ),
                ),
                
            ));

            register_block_type('agni/products-tab', array(
                'render_callback' => 'dynamic_blocks_agni_products_tab',
                'attributes' => array(
                    'headingText' => array(
                        'type' => 'string',
                        'default' => 'Add Heading'
                    ),
                    'headingLevel' => array(
                        'type' => 'string',
                        'default' => '3'
                    ),
                    'headingCssFontSize' => array(
                        'type' => 'string',
                        'default' => '22px'
                    ),
                    'headingCssColor' => array(
                        'type' => 'string',
                    ),
                    'buttonUrl' => array(
                        'type' => 'string',
                    ),
                    'buttonText' => array(
                        'type' => 'string',
                    ),
                    'buttonTarget' => array(
                        'type' => 'string',
                        'default' => '_self'
                    ),
                    'rel' => array(
                        'type' => 'string',
                        'default' => 'noreferrer noopener'
                    ),
                    'buttonCssColor' => array(
                        'type' => 'string'
                    ),
                    'header_style' => array(
                        'type' => 'string',
                        'default' => '1'
                    ),
                    'header_align' => array(
                        'type' => 'string',
                        'default' => 'left'
                    ),
                    'headerFilled' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'headerOutlined' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'headerCssBackgroundColor' => array(
                        'type' => 'string',
                    ),
                    'headerCssBackgroundImage' => array(
                        'type' => 'string',
                    ),
                    'headerCssBorderTopWidth' => array(
                        'type' => 'string',
                        'default' => '0px'
                    ),
                    'headerCssBorderBottomWidth' => array(
                        'type' => 'string',
                        'default' => '1px'
                    ),
                    'headerCssBorderColor' => array(
                        'type' => 'string',
                    ),
                    'columns' => array(
                        'type' => 'object',
                        'default' => array( 'desktop' => 4, 'laptop' => 4, 'tab' => 4, 'mobile' => 2 ),
                    ),
                    'rows' => array(
                        'type' => 'object',
                        'default' => array( 'desktop' => 1, 'laptop' => 1, 'tab' => 1, 'mobile' => 2 ),
                    ),
                    'productsSynced' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),

                    'widthRatio' => array(
                        'type' => 'array',
                    ),
                    'countOnGrid' => array(
                        'type' => 'string',
                        'default' => '4',
                    ),

                    'activeTab' => array(
                        'type' => 'string',
                        'default' => '0',
                    ),

                    'showIcon' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),

                    'iconSize' => array(
                        'type' => 'string',
                        'default' => '32px',
                    ),

                    'tabs' => array(
                        'type' => 'array',
                    ),
                    'inlineProductsMobile' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'count' => array(
                        'type' => 'string',
                        'default' => '4',
                    ),
                    'paginationStyle' => array(
                        'type' => 'string',
                        'default' => '1'
                    ),
                    'products_type' => array(
                        'type' => 'string',
                        'default' => 'all'
                    ),
                    'product_ids' => array(
                        'type' => 'array',
                    ),
                    'category_ids' => array(
                        'type' => 'array',
                    ),
                    'order_by' => array(
                        'type' => 'string',
                        'default' => '5'
                    ),
                    'display_style' => array(
                        'type' => 'string',
                        'default' => '1'
                    ),
                    'imgMaxWidth' => array(
                        'type' => 'number',
                        'default' => ''
                    ),
                    'imgDisplayStyle' => array(
                        'type' => 'string',
                        'default' => ''
                    ),
                    'imgCssBorderRadius' => array(
                        'type' => 'string',
                        'default' => '0px'
                    ),
                    'product_qty' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'product_qty_choice' => array(
                        'type' => 'string',
                        'default' => '1'
                    ),
                    'product_title' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'product_desc' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'product_category' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'product_price' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'product_rating' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'product_buttons' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'product_stock' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'product_countdown' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'gap' => array(
                        'type' => 'string',
                        'default' => '14',
                    ),
                    'carousel' => array(
                        'type' => 'boolean',
                        'default' => false,
                    ),
                    'carouselAutoplay' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'carouselInfinite' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'carouselArrows' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'carouselDots' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),

                    'fullWidthBG' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
            
                    'backgroundColor' => array(
                        'type' => 'string',
                    ),
                    'backgroundGradient' => array(
                        'type' => 'string'
                    ),
                    'backgroundOverlayBackward' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'backgroundUrl' => array(
                        'type' => 'string',
                    ),
                    'backgroundId' => array(
                        'type' => 'number',
                    ),
                    'backgroundImagePosition' => array(
                        'type' => 'string',
                        'default' => '50% 50%'
                    ),
                    'backgroundImageRepeat' => array(
                        'type' => 'string',
                        'default' => 'repeat'
                    ),
                    'backgroundImageSize' => array(
                        'type' => 'string',
                        'default' => 'cover'
                    ),
                    'backgroundOpacity' => array(
                        'type' => 'number',
                    ),
                    'backgroundType' => array(
                        'type' => 'string',
                    ),
                    'backgroundFallbackUrl' => array(
                        'type' => 'string'
                    ),
                    'videoAutoplay' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'videoMuted' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'videoLoop' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'borderWidth' => array(
                        'type' => 'object',
                        'default' => array( 'top' => '0px', 'left' => '0px', 'right' => '0px', 'bottom' => '0px' )
                    ),
                    'cssBorderColor' => array(
                        'type' => 'string'
                    ),
                    'cssBorderRadius' => array(
                        'type' => 'string'
                    ),

                    'showPlaceholder' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                            
                    'customClassName' => array(
                        'type' => 'string',
                    ),
                ),
                // 'render_callback' => function($attributes, $content = null){
                //     print_r( $attributes );
                //     if( !is_admin() ){

                //         wp_enqueue_style( 'lineicons' );
                //         wp_enqueue_style( 'font-awesome' );
                        
                //         $product_thumbnail_style = '';

                //         if( function_exists( 'cartify_get_theme_option' ) ){
                //             $product_thumbnail_style = cartify_get_theme_option( 'shop_settings_general_thumbnail_choice', '1' );
                //         }

                //         if( $product_thumbnail_style == '3' ){
                //             wp_enqueue_style('slick');
                //             wp_enqueue_script('slick');
                //         }

                //         wp_enqueue_script( 'wc-add-to-cart-variation' );
                //     }

                //     return $content;
                // },
                
            ));

            register_block_type('agni/products-categories', array(
                // 'render_callback' => array( $this, 'register_dynamic_blocks_agni_products_categories' ),
                'render_callback' => 'dynamic_blocks_agni_products_categories',
                'attributes' => array(
                    'headingText' => array(
                        'type' => 'string',
                        'default' => 'Add Heading'
                    ),
                    'headingLevel' => array(
                        'type' => 'string',
                        'default' => '3'
                    ),
                    'headingCssFontSize' => array(
                        'type' => 'string',
                        'default' => '22px'
                    ),
                    'headingCssColor' => array(
                        'type' => 'string',
                    ),
                    'countdown' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'buttonUrl' => array(
                        'type' => 'string',
                    ),
                    'buttonText' => array(
                        'type' => 'string',
                    ),
                    'buttonTarget' => array(
                        'type' => 'string',
                        'default' => '_self'
                    ),
                    'rel' => array(
                        'type' => 'string',
                        'default' => 'noreferrer noopener'
                    ),
                    'buttonCssColor' => array(
                        'type' => 'string'
                    ),
                    'header_style' => array(
                        'type' => 'string',
                        'default' => '1'
                    ),
                    'header_align' => array(
                        'type' => 'string',
                        'default' => 'left'
                    ),
                    'headerFilled' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'headerOutlined' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'headerCssBackgroundColor' => array(
                        'type' => 'string',
                    ),
                    'headerCssBackgroundImage' => array(
                        'type' => 'string',
                    ),
                    'headerCssBorderTopWidth' => array(
                        'type' => 'string',
                        'default' => '0px'
                    ),
                    'headerCssBorderBottomWidth' => array(
                        'type' => 'string',
                        'default' => '1px'
                    ),
                    'headerCssBorderColor' => array(
                        'type' => 'string',
                    ),
                    'columns' => array(
                        'type' => 'object',
                        'default' => array( 'desktop' => 4, 'laptop' => 4, 'tab' => 4, 'mobile' => 2 ),
                    ),
                    'rows' => array(
                        'type' => 'object',
                        'default' => array( 'desktop' => 1, 'laptop' => 1, 'tab' => 1, 'mobile' => 2 ),
                    ),
                    'count' => array(
                        'type' => 'string',
                        'default' => '4',
                    ),
                    'paginationStyle' => array(
                        'type' => 'string',
                        'default' => '1'
                    ),
                    'categoriesSynced' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'countOnGrid' => array(
                        'type' => 'string',
                        'default' => '4',
                    ),
                    'widthRatio' => array(
                        'type' => 'array',
                    ),

                    'inlineCategoriesMobile' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'hide_empty' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'category_ids' => array(
                        'type' => 'array',
                    ),
                    'order_by' => array(
                        'type' => 'string',
                        'default' => '1'
                    ),
                    'display_style' => array(
                        'type' => 'string',
                        'default' => '1'
                    ),
                    'stackOnMobile' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    
                    'imgMaxWidth' => array(
                        'type' => 'string',
                        'default' => ''
                    ),
                    'imgSize' => array(
                        'type' => 'string',
                        'default' => ''
                    ),
                    'contentAlignment' => array(
                        'type' => 'string',
                        'default' => 'left'
                    ),
                    'imgBorderRadius' => array(
                        'type' => 'string',
                    ),
                    'categoryBackgroundColor' => array(
                        'type' => 'string',
                    ),
                    'categoryBorderColor' => array(
                        'type' => 'string',
                    ),
                    
                    'category_thumbnail' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'category_icon' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'category_title' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    
                    'category_desc' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'category_count' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'category_children' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'category_button' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'category_button_text' => array(
                        'type' => 'string',
                        'default' => 'Explore Now'
                    ),
                    
                    'gap' => array(
                        'type' => 'string',
                        'default' => '14',
                    ),
                    'carousel' => array(
                        'type' => 'boolean',
                        'default' => false,
                    ),
                    'carouselAutoplay' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'carouselInfinite' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'carouselArrows' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'carouselDots' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    
                    'fullWidthBG' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
            
                    'backgroundColor' => array(
                        'type' => 'string',
                    ),
                    'backgroundGradient' => array(
                        'type' => 'string'
                    ),
                    'backgroundOverlayBackward' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'backgroundUrl' => array(
                        'type' => 'string',
                    ),
                    'backgroundId' => array(
                        'type' => 'number',
                    ),
                    'backgroundImagePosition' => array(
                        'type' => 'string',
                        'default' => '50% 50%'
                    ),
                    'backgroundImageRepeat' => array(
                        'type' => 'string',
                        'default' => 'repeat'
                    ),
                    'backgroundImageSize' => array(
                        'type' => 'string',
                        'default' => 'cover'
                    ),
                    'backgroundOpacity' => array(
                        'type' => 'number',
                    ),
                    'backgroundType' => array(
                        'type' => 'string',
                    ),
                    'backgroundFallbackUrl' => array(
                        'type' => 'string'
                    ),
                    'videoAutoplay' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'videoMuted' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'videoLoop' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'borderWidth' => array(
                        'type' => 'object',
                        'default' => array( 'top' => '0px', 'left' => '0px', 'right' => '0px', 'bottom' => '0px' )
                    ),
                    'cssBorderColor' => array(
                        'type' => 'string'
                    ),
                    'cssBorderRadius' => array(
                        'type' => 'string'
                    ),

                    'showPlaceholder' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                            
                    'customClassName' => array(
                        'type' => 'string',
                    ),
                )
                
            ));

            register_block_type('agni/products-categories-tab', array(
                'attributes' => array(
                    'headingText' => array(
                        'type' => 'string',
                        'default' => 'Add Heading'
                    ),
                    'headingLevel' => array(
                        'type' => 'string',
                        'default' => '3'
                    ),
                    'headingCssFontSize' => array(
                        'type' => 'string',
                        'default' => '22px'
                    ),
                    'headingCssColor' => array(
                        'type' => 'string',
                    ),
                    'countdown' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'buttonUrl' => array(
                        'type' => 'string',
                    ),
                    'buttonText' => array(
                        'type' => 'string',
                    ),
                    'buttonTarget' => array(
                        'type' => 'string',
                        'default' => '_self'
                    ),
                    'rel' => array(
                        'type' => 'string',
                        'default' => 'noreferrer noopener'
                    ),
                    'buttonCssColor' => array(
                        'type' => 'string'
                    ),
                    'header_style' => array(
                        'type' => 'string',
                        'default' => '1'
                    ),
                    'header_align' => array(
                        'type' => 'string',
                        'default' => 'left'
                    ),
                    'headerFilled' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'headerOutlined' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'headerCssBackgroundColor' => array(
                        'type' => 'string',
                    ),
                    'headerCssBackgroundImage' => array(
                        'type' => 'string',
                    ),
                    'headerCssBorderTopWidth' => array(
                        'type' => 'string',
                        'default' => '0px'
                    ),
                    'headerCssBorderBottomWidth' => array(
                        'type' => 'string',
                        'default' => '1px'
                    ),
                    'headerCssBorderColor' => array(
                        'type' => 'string',
                    ),
                    'columns' => array(
                        'type' => 'object',
                        'default' => array( 'desktop' => 4, 'laptop' => 4, 'tab' => 4, 'mobile' => 2 ),
                    ),
                    'rows' => array(
                        'type' => 'object',
                        'default' => array( 'desktop' => 1, 'laptop' => 1, 'tab' => 1, 'mobile' => 2 ),
                    ),
                    'count' => array(
                        'type' => 'string',
                        'default' => '4',
                    ),
                    'paginationStyle' => array(
                        'type' => 'string',
                        'default' => '1'
                    ),
                    'categoriesSynced' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'countOnGrid' => array(
                        'type' => 'string',
                        'default' => '4',
                    ),
                    'widthRatio' => array(
                        'type' => 'array',
                    ),

                    'inlineCategoriesMobile' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),

                    'showIcon' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),

                    'activeTab' => array(
                        'type' => 'string',
                        'default' => '0'
                    ),

                    'tabs' => array(
                        'type' => 'array',
                    ),
                    'iconSize' => array(
                        'type' => 'string',
                        'default' => '32px'
                    ),
                    'hide_empty' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'category_ids' => array(
                        'type' => 'array',
                    ),
                    'order_by' => array(
                        'type' => 'string',
                        'default' => '1'
                    ),
                    'display_style' => array(
                        'type' => 'string',
                        'default' => '1'
                    ),
                    'imgMaxWidth' => array(
                        'type' => 'string',
                        'default' => ''
                    ),
                    'imgSize' => array(
                        'type' => 'string',
                        'default' => ''
                    ),
                    'contentAlignment' => array(
                        'type' => 'string',
                        'default' => 'left'
                    ),
                    'imgBorderRadius' => array(
                        'type' => 'string',
                    ),
                    'categoryBackgroundColor' => array(
                        'type' => 'string',
                    ),
                    'categoryBorderColor' => array(
                        'type' => 'string',
                    ),
                    
                    'category_thumbnail' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'category_icon' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'category_title' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    
                    'category_desc' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'category_count' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'category_children' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'category_button' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'category_button_text' => array(
                        'type' => 'string',
                        'default' => 'Explore Now'
                    ),
                    
                    'gap' => array(
                        'type' => 'string',
                        'default' => '14',
                    ),
                    'carousel' => array(
                        'type' => 'boolean',
                        'default' => false,
                    ),
                    'carouselAutoplay' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'carouselInfinite' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'carouselArrows' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'carouselDots' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    
                    'fullWidthBG' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
            
                    'backgroundColor' => array(
                        'type' => 'string',
                    ),
                    'backgroundGradient' => array(
                        'type' => 'string'
                    ),
                    'backgroundOverlayBackward' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'backgroundUrl' => array(
                        'type' => 'string',
                    ),
                    'backgroundId' => array(
                        'type' => 'number',
                    ),
                    'backgroundImagePosition' => array(
                        'type' => 'string',
                        'default' => '50% 50%'
                    ),
                    'backgroundImageRepeat' => array(
                        'type' => 'string',
                        'default' => 'repeat'
                    ),
                    'backgroundImageSize' => array(
                        'type' => 'string',
                        'default' => 'cover'
                    ),
                    'backgroundOpacity' => array(
                        'type' => 'number',
                    ),
                    'backgroundType' => array(
                        'type' => 'string',
                    ),
                    'backgroundFallbackUrl' => array(
                        'type' => 'string'
                    ),
                    'videoAutoplay' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'videoMuted' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'videoLoop' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'borderWidth' => array(
                        'type' => 'object',
                        'default' => array( 'top' => '0px', 'left' => '0px', 'right' => '0px', 'bottom' => '0px' )
                    ),
                    'cssBorderColor' => array(
                        'type' => 'string'
                    ),
                    'cssBorderRadius' => array(
                        'type' => 'string'
                    ),

                    'showPlaceholder' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                            
                    'customClassName' => array(
                        'type' => 'string',
                    ),
                ),
                'render_callback' => 'dynamic_blocks_agni_products_categories_tab'
            ));

            register_block_type('agni/products-brands', array(
                'render_callback' => 'dynamic_blocks_agni_products_brands',
                'attributes' => array(
                    'align' => array(
                        'type' => 'string',
                        'default' => 'center'
                    ),
                    'columns' => array(
                        'type' => 'object',
                        'default' => array( 'desktop' => 6, 'laptop' => 6, 'tab' => 6, 'mobile' => 3 ),
                    ),
                    'count' => array(
                        'type' => 'number',
                        'default' => '6',
                    ),
                    'hide_empty' => array(
                        'type' => 'boolean',
                        'default' => false,
                    ),
                    'brand_ids' => array(
                        'type' => 'array',
                    ),
                    'order_by' => array(
                        'type' => 'string',
                        'default' => '3'
                    ),
                    'alphabet' => array(
                        'type' => 'string',
                        'default' => ''
                    ),
                    'imgMaxWidth' => array(
                        'type' => 'number',
                        'default' => ''
                    ),
                    'imgMaxHeight' => array(
                        'type' => 'number',
                        'default' => ''
                    ),
                    'brandFilled' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'brandOutlined' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'brandBackgroundColor' => array(
                        'type' => 'string',
                    ),
                    'brandBorderColor' => array(
                        'type' => 'string',
                        'default' => '#cccccc',
                    ),
                    'brand_thumbnail'  => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'brand_name' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    
                    'brand_description' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                     
                    'gap' => array(
                        'type' => 'number',
                        'default' => 0,
                    ),
                    'padding' => array(
                        'type' => 'object',
                    ),
                    'margin' => array(
                        'type' => 'object',
                    ),
                    
                    'carousel' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'carouselOptionsChoice' => array(
                        'type' => 'string',
                        'default' => 'basic'
                    ),
                    'carouselAutoplay' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'carouselInfinite' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'carouselArrows' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'carouselDots' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'carouselOptions' => array(
                        'type' => 'string',
                    
                    ),

                    'showPlaceholder' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    
                    'customClassName' => array(
                        'type' => 'string',
                    ),
                )
                
            ));

            register_block_type('agni/slider', array(
                // 'editor_style' => $this->getAllSliderStyles(), 
                'render_callback' => 'dynamic_block_agni_slider',
                'attributes' => array(
                    'id' => array(
                        'type' => 'string',
                        // 'default' => '1'
                    ),
                    'width' => array(
                        'type' => 'string',
                        'default' => 'container'
                    ),
                    'customClassName' => array(
                        'type' => 'string'
                    ),
                )
            ));

            register_block_type('agni/content-block', array(
                'render_callback' => 'dynamic_block_agni_content_block',
                'attributes' => array(
                    'id' => array(
                        'type' => 'string',
                        // 'default' => '1'
                    ),
                    'customClassName' => array(
                        'type' => 'string'
                    )
                )
            ));

            register_block_type('agni/menu', array(
                'render_callback' => 'dynamic_block_agni_menu',
                'attributes' => array(
                    'menu_choice' => array(
                        'type' => 'string',
                        // 'default' => '1'
                    ),
                    'gap' => array(
                        'type' => 'string',
                        'default' => '7px'
                    ),
                    'hasSeparator' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'hasMenuArrow' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'direction' => array(
                        'type' => 'string',
                        'default' => 'horizontal'
                    ),

                    'depth' => array(
                        'type' => 'string',
                        'default' => '1'
                    ),
                    'fontSize' => array(
                        'type' => 'string',
                    ),
                    'textAlign' => array(
                        'type' => 'string',
                        'default' => 'left'
                    ),
                    'customClassName' => array(
                        'type' => 'string'
                    )
                )
            ));

            register_block_type('agni/posts', array(
                // 'render_callback' => array( $this, 'register_dynamic_blocks_agni_products' ),
                'render_callback' => 'dynamic_blocks_agni_posts',
                'attributes' => array(
                    'headingText' => array(
                        'type' => 'string',
                        'default' => 'Add Heading'
                    ),
                    'headingLevel' => array(
                        'type' => 'string',
                        'default' => '3'
                    ),
                    'headingCssFontSize' => array(
                        'type' => 'string',
                        'default' => '22px'
                    ),
                    'headingCssColor' => array(
                        'type' => 'string',
                    ),
                    'buttonUrl' => array(
                        'type' => 'string',
                    ),
                    'buttonText' => array(
                        'type' => 'string',
                    ),
                    'buttonTarget' => array(
                        'type' => 'string',
                        'default' => '_self'
                    ),
                    'rel' => array(
                        'type' => 'string',
                        'default' => 'noreferrer noopener'
                    ),
                    'buttonCssColor' => array(
                        'type' => 'string'
                    ),
                    'header_style' => array(
                        'type' => 'string',
                        'default' => '1'
                    ),
                    'header_align' => array(
                        'type' => 'string',
                        'default' => 'left'
                    ),
                    'headerFilled' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'headerOutlined' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'headerCssBackgroundColor' => array(
                        'type' => 'string',
                    ),
                    'headerCssBackgroundImage' => array(
                        'type' => 'string',
                    ),
                    'headerCssBorderTopWidth' => array(
                        'type' => 'string',
                        'default' => '0px'
                    ),
                    'headerCssBorderBottomWidth' => array(
                        'type' => 'string',
                        'default' => '1px'
                    ),
                    'headerCssBorderColor' => array(
                        'type' => 'string',
                    ),
                    'divideLineDisplayStyle' => array(
                        'type' => 'string',
                        'default' => ''
                    ),
                    'divideLineWidth' => array(
                        'type' => 'string',
                        'default' => '52px'
                    ),
                    'divideLineHeight' => array(
                        'type' => 'string',
                        'default' => '12px'
                    ),
                    'divideLineSize' => array(
                        'type' => 'number',
                        'default' => 100
                    ),
                    'divideLineColor' => array(
                        'type' => 'string',
                        'default' => ''
                    ),
                    'divideLineAlign' => array(
                        'type' => 'string',
                        'default' => 'left'
                    ),
                    'columns' => array(
                        'type' => 'object',
                        'default' => array( 'desktop' => 3, 'laptop' => 3, 'tab' => 3, 'mobile' => 1 ),
                    ),
                    'count' => array(
                        'type' => 'string',
                        'default' => '3',
                    ),
                    'pagination' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'product_ids' => array(
                        'type' => 'array',
                    ),
                    'category_ids' => array(
                        'type' => 'array',
                    ),
                    'order_by' => array(
                        'type' => 'string',
                        'default' => '3'
                    ),
                    'imgSize' => array(
                        'type' => 'string',
                        'default' => ''
                    ),
                    'display_style' => array(
                        'type' => 'string',
                        'default' => '2'
                    ),
                    'post_thumbnail' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'post_title' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'post_desc' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'post_category' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'post_author' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'post_date' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                   
                    'gap' => array(
                        'type' => 'string',
                        'default' => '14',
                    ),
                    'carousel' => array(
                        'type' => 'boolean',
                        'default' => false,
                    ),
                    'carouselAutoplay' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'carouselInfinite' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'carouselArrows' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'carouselDots' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),

                    'fullWidthBG' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
            
                    'backgroundColor' => array(
                        'type' => 'string',
                    ),
                    'backgroundGradient' => array(
                        'type' => 'string'
                    ),
                    'backgroundOverlayBackward' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'backgroundUrl' => array(
                        'type' => 'string',
                    ),
                    'backgroundId' => array(
                        'type' => 'number',
                    ),
                    'backgroundImagePosition' => array(
                        'type' => 'string',
                        'default' => '50% 50%'
                    ),
                    'backgroundImageRepeat' => array(
                        'type' => 'string',
                        'default' => 'repeat'
                    ),
                    'backgroundImageSize' => array(
                        'type' => 'string',
                        'default' => 'cover'
                    ),
                    'backgroundOpacity' => array(
                        'type' => 'number',
                    ),
                    'backgroundType' => array(
                        'type' => 'string',
                    ),
                    'backgroundFallbackUrl' => array(
                        'type' => 'string'
                    ),
                    'videoAutoplay' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'videoMuted' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'videoLoop' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'borderWidth' => array(
                        'type' => 'object',
                        'default' => array( 'top' => '0px', 'left' => '0px', 'right' => '0px', 'bottom' => '0px' )
                    ),
                    'cssBorderColor' => array(
                        'type' => 'string'
                    ),
                    'cssBorderRadius' => array(
                        'type' => 'string'
                    ),

                    'showPlaceholder' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                            
                    'customClassName' => array(
                        'type' => 'string',
                    ),
                ),
                
            ));

            register_block_type('agni/portfolio', array(
                // 'render_callback' => array( $this, 'register_dynamic_blocks_agni_products' ),
                'render_callback' => 'dynamic_blocks_agni_portfolio',
                'attributes' => array(
                    'headingText' => array(
                        'type' => 'string',
                        'default' => 'Add Heading'
                    ),
                    'headingLevel' => array(
                        'type' => 'string',
                        'default' => '3'
                    ),
                    'headingCssFontSize' => array(
                        'type' => 'string',
                        'default' => '22px'
                    ),
                    'headingCssColor' => array(
                        'type' => 'string',
                    ),
                    'buttonUrl' => array(
                        'type' => 'string',
                    ),
                    'buttonText' => array(
                        'type' => 'string',
                    ),
                    'buttonTarget' => array(
                        'type' => 'string',
                        'default' => '_self'
                    ),
                    'rel' => array(
                        'type' => 'string',
                        'default' => 'noreferrer noopener'
                    ),
                    'buttonCssColor' => array(
                        'type' => 'string'
                    ),
                    'header_style' => array(
                        'type' => 'string',
                        'default' => '1'
                    ),
                    'header_align' => array(
                        'type' => 'string',
                        'default' => 'left'
                    ),
                    'headerFilled' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'headerOutlined' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'headerCssBackgroundColor' => array(
                        'type' => 'string',
                    ),
                    'headerCssBackgroundImage' => array(
                        'type' => 'string',
                    ),
                    'headerCssBorderTopWidth' => array(
                        'type' => 'string',
                        'default' => '0px'
                    ),
                    'headerCssBorderBottomWidth' => array(
                        'type' => 'string',
                        'default' => '1px'
                    ),
                    'headerCssBorderColor' => array(
                        'type' => 'string',
                    ),
                    'divideLineDisplayStyle' => array(
                        'type' => 'string',
                        'default' => ''
                    ),
                    'divideLineWidth' => array(
                        'type' => 'string',
                        'default' => '52px'
                    ),
                    'divideLineHeight' => array(
                        'type' => 'string',
                        'default' => '12px'
                    ),
                    'divideLineSize' => array(
                        'type' => 'number',
                        'default' => 100
                    ),
                    'divideLineColor' => array(
                        'type' => 'string',
                        'default' => ''
                    ),
                    'divideLineAlign' => array(
                        'type' => 'string',
                        'default' => 'left'
                    ),
                    'columns' => array(
                        'type' => 'object',
                        'default' => array( 'desktop' => 3, 'laptop' => 3, 'tab' => 2, 'mobile' => 1 ),
                    ),
                    'count' => array(
                        'type' => 'string',
                        'default' => '3',
                    ),
                    'pagination' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'product_ids' => array(
                        'type' => 'array',
                    ),
                    'category_ids' => array(
                        'type' => 'array',
                    ),
                    'order_by' => array(
                        'type' => 'string',
                        'default' => '3'
                    ),
                    'display_style' => array(
                        'type' => 'string',
                        'default' => '2'
                    ),
                    'post_thumbnail' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'post_title' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'post_desc' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'post_category' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'post_author' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'post_date' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                   
                    'gap' => array(
                        'type' => 'string',
                        'default' => '14',
                    ),
                    'carousel' => array(
                        'type' => 'boolean',
                        'default' => false,
                    ),
                    'carouselAutoplay' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'carouselInfinite' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'carouselArrows' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'carouselDots' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),

                    'fullWidthBG' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
            
                    'backgroundColor' => array(
                        'type' => 'string',
                    ),
                    'backgroundGradient' => array(
                        'type' => 'string'
                    ),
                    'backgroundOverlayBackward' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                    'backgroundUrl' => array(
                        'type' => 'string',
                    ),
                    'backgroundId' => array(
                        'type' => 'number',
                    ),
                    'backgroundImagePosition' => array(
                        'type' => 'string',
                        'default' => '50% 50%'
                    ),
                    'backgroundImageRepeat' => array(
                        'type' => 'string',
                        'default' => 'repeat'
                    ),
                    'backgroundImageSize' => array(
                        'type' => 'string',
                        'default' => 'cover'
                    ),
                    'backgroundOpacity' => array(
                        'type' => 'number',
                    ),
                    'backgroundType' => array(
                        'type' => 'string',
                    ),
                    'backgroundFallbackUrl' => array(
                        'type' => 'string'
                    ),
                    'videoAutoplay' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'videoMuted' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'videoLoop' => array(
                        'type' => 'boolean',
                        'default' => true
                    ),
                    'borderWidth' => array(
                        'type' => 'object',
                        'default' => array( 'top' => '0px', 'left' => '0px', 'right' => '0px', 'bottom' => '0px' )
                    ),
                    'cssBorderColor' => array(
                        'type' => 'string'
                    ),
                    'cssBorderRadius' => array(
                        'type' => 'string'
                    ),

                    'showPlaceholder' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                            
                    'customClassName' => array(
                        'type' => 'string',
                    ),
                ),
                
            ));

            register_block_type('agni/recently-viewed-products', array(
                'render_callback' => 'dynamic_blocks_agni_recently_viewed_products',
                'attributes' => array(
                    'headingText' => array(
                        'type' => 'string',
                        'default' => 'Add Heading'
                    ),
                    'headingLevel' => array(
                        'type' => 'string',
                        'default' => '3'
                    ),
                    'headingCssFontSize' => array(
                        'type' => 'string',
                        'default' => '22px'
                    ),
                    'headingCssColor' => array(
                        'type' => 'string',
                    ),
                    'align' => array(
                        'type' => 'string',
                        'default' => 'left'
                    ),
                    'columns' => array(
                        'type' => 'object',
                        'default' => array( 'desktop' => 10, 'laptop' => 10, 'tab' => 6, 'mobile' => 3 ),
                    ),

                    'gap' => array(
                        'type' => 'string',
                        'default' => '14',
                    ),
                    'count' => array(
                        'type' => 'string',
                        'default' => '10'
                    ),

                    'showPlaceholder' => array(
                        'type' => 'boolean',
                        'default' => false
                    ),
                            
                    'customClassName' => array(
                        'type' => 'string',
                    )
                )
            ));
        }


        public static function agni_product_display_options( $attributes, $reverse = false ){

            $role = 'remove';

            if( $reverse === true ){
                $role = 'add';
            }


            if( isset( $attributes['product_category'] ) && $attributes['product_category'] == false ){
                ($role . '_action')( 'woocommerce_shop_loop_item_title', 'cartify_woocommerce_products_loop_category_title', 9 );
            }
            if( isset( $attributes['product_title'] ) && $attributes['product_title'] == false ){
                ($role . '_action')( 'woocommerce_shop_loop_item_title', 'cartify_woocommerce_template_loop_product_title', 10 );
            }
            if( isset( $attributes['product_rating'] ) && $attributes['product_rating'] == false ){
                ($role . '_action')( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
            }
            if( isset( $attributes['product_price'] ) && $attributes['product_price'] == false ){
                ($role . '_action')( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
            }
            if( isset( $attributes['product_stock'] ) && $attributes['product_stock'] == false ){
                ($role . '_action')( 'woocommerce_after_shop_loop_item', 'cartify_woocommerce_instock_indicator', 20 );
            }
            if( isset( $attributes['product_countdown'] ) && $attributes['product_countdown'] == false ){
                ($role . '_action')( 'woocommerce_after_shop_loop_item', 'cartify_woocommerce_sale_countdown', 25 );
            }
            if( isset( $attributes['product_qty'] ) && $attributes['product_qty'] == false ){
                ($role . '_action')( 'agni_woocommerce_after_shop_loop_item', 'cartify_template_loop_qty_update', 10 );
            }
            // if( isset( $attributes['product_buttons'] ) && $attributes['product_buttons'] == false ){
            //     remove_all_actions( 'agni_woocommerce_after_shop_loop_item' );
            // }

            if( isset( $attributes['product_add_to_cart'] ) && $attributes['product_add_to_cart'] == false ){
                ($role . '_action')( 'woocommerce_before_shop_loop_item_title', 'cartify_template_loop_cart_open_tag', 15 );
                ($role . '_action')( 'woocommerce_before_shop_loop_item_title', 'cartify_template_loop_cart_close_tag', 24 );
                ($role . '_action')( 'woocommerce_before_shop_loop_item_title', 'cartify_template_loop_qty_update', 18 );
                ($role . '_action')( 'woocommerce_before_shop_loop_item_title', 'cartify_template_loop_add_to_cart', 21 );

                ($role . '_action')( 'agni_woocommerce_after_shop_loop_item', 'cartify_template_loop_cart_open_tag', 9 );
                ($role . '_action')( 'agni_woocommerce_after_shop_loop_item', 'cartify_template_loop_cart_close_tag', 11 );
                ($role . '_action')( 'agni_woocommerce_after_shop_loop_item', 'cartify_template_loop_qty_update', 10 );
                ($role . '_action')( 'agni_woocommerce_after_shop_loop_item', 'cartify_template_loop_add_to_cart', 10 );
            }

            if( isset( $attributes['product_add_to_compare'] ) && $attributes['product_add_to_compare'] == false ){
                ($role . '_action')( 'agni_woocommerce_after_shop_loop_item', 'cartify_compare_add_to_compare_button', 20 );
            }
            if( isset( $attributes['product_quickview'] ) && $attributes['product_quickview'] == false ){
                ($role . '_action')( 'agni_woocommerce_after_shop_loop_item', 'cartify_quickview_button', 15 );
            }


            if( !isset( $attributes['product_desc'] ) || $attributes['product_desc'] == true ){
                if( $reverse === true ){
                    remove_action( 'woocommerce_after_shop_loop_item_title', 'cartify_woocommerce_block_short_description', 9 );
                }
                else{
                    add_action( 'woocommerce_after_shop_loop_item_title', 'cartify_woocommerce_block_short_description', 9 );
                }
            }



        }


        public static function agni_products_pagination( $attributes, $total_products_to_display){

            extract($attributes);

            $current_page = '1';
            
            $allowed = array('columns', 'rows', 'count', 'productsSynced', 'countOnGrid', 'products_type', 'product_ids', 'category_ids', 'order_by', 'imgSize', 'imgDisplayStyle', 'product_qty', 'product_title', 'product_desc', 'product_category', 'product_price', 'product_rating', 'product_add_to_cart', 'product_add_to_compare', 'product_quickview', 'product_stock', 'product_countdown');


            $has_ajax_pagination = $total_pages = array();

            foreach ($columns as $colDevice => $column) {
                foreach ($rows as $rowDevice => $row) {
                    if( $colDevice == $rowDevice ){
                        if( !$productsSynced ){
                            $total_products_on_grid = $countOnGrid;
                        }
                        else{
                            $total_products_on_grid = $column * $row;
                        }
                        
                        $has_ajax_pagination[$rowDevice] = ( ($total_products_on_grid < $total_products_to_display) && !filter_var($carousel, FILTER_VALIDATE_BOOLEAN));
                        $total_pages[$rowDevice] = ceil($total_products_to_display/$total_products_on_grid);
                    }
                }
            }

            $pagination_classes = array();
        
            if(!(count(array_unique($has_ajax_pagination)) === 1 && !current($has_ajax_pagination))) {
                $pagination_classes[] = 'agni-block-products-pagination';

                foreach ($has_ajax_pagination as $device => $value) {
                    if( $value ){
                        $pagination_classes[] = 'has-pagination-' . $device;
                    }
                }       
            }

            if( empty( $pagination_classes ) ){
                return;
            }

            ?>
            <?php 
            wp_enqueue_script( 'wc-add-to-cart-variation' );

            ?>
            <div class="<?php echo esc_attr( AgniBuilderHelper::prepare_classes( $pagination_classes ) ); ?>" data-args="<?php echo esc_attr( wp_json_encode( array_intersect_key($attributes, array_flip($allowed)) ) ) ?>" data-current-page-num="<?php echo esc_attr($current_page); ?>" data-total-page-num="<?php echo esc_attr(wp_json_encode($total_pages)); ?>" data-total-products="<?php echo esc_attr($total_products_to_display); ?>">
                <span class="agni-block-products-pagination__nav <?php echo esc_attr(($paginationStyle == '2') ? 'load-more':'nav-prev'); ?>">
                    <?php if($paginationStyle == '2'){ echo esc_html__( 'Load More', 'agni-builder' ); } else{ ?>
                        <i class="lni lni-chevron-left"></i>
                        <span><?php echo esc_html__( 'Previous', 'agni-builder' ); ?></span>
                    <?php } ?>
                </span>
                <?php if( $paginationStyle != '2' ){ ?>
                    <span class="agni-block-products-pagination__nav nav-next">
                        <span><?php echo esc_html__( 'Next', 'agni-builder' ); ?></span>
                        <i class="lni lni-chevron-right"></i>
                    </span>
                <?php } ?>
            </div>

            <?php
        }

        public static function agni_products_categories_pagination( $attributes, $total_products_to_display ){
            
            extract($attributes);

            $current_page = '1';
            
            $allowed = array( 'columns', 'rows', 'count', 'categoriesSynced', 'countOnGrid', 'hide_empty', 'category_ids', 'order_by', 'imgSize', 'category_title', 'category_desc', 'category_button', 'category_children', 'category_count', 'widthRatio' );


            $has_ajax_pagination = $total_pages = array();

            foreach ($columns as $colDevice => $column) {
                foreach ($rows as $rowDevice => $row) {
                    if( $colDevice == $rowDevice ){
                        if( !$categoriesSynced ){
                            $total_products_on_grid = $countOnGrid;
                        }
                        else{
                            $total_products_on_grid = $column * $row;
                        }
                        
                        $has_ajax_pagination[$rowDevice] = ( ($total_products_on_grid < $total_products_to_display) && !filter_var($carousel, FILTER_VALIDATE_BOOLEAN));
                        $total_pages[$rowDevice] = ceil($total_products_to_display/$total_products_on_grid);
                    }
                }
            }

            $pagination_classes = array();
        
            if(!(count(array_unique($has_ajax_pagination)) === 1 && !current($has_ajax_pagination))) {
                $pagination_classes[] = 'agni-block-products-categories-pagination';

                foreach ($has_ajax_pagination as $device => $value) {
                    if( $value ){
                        $pagination_classes[] = 'has-pagination-' . $device;
                    }
                }       
            }

            if( empty( $pagination_classes ) ){
                return;
            }


            ?>
            <div class="<?php echo esc_attr( AgniBuilderHelper::prepare_classes( $pagination_classes ) ); ?>" data-args="<?php echo esc_attr( wp_json_encode( array_intersect_key($attributes, array_flip($allowed)) ) ) ?>" data-current-page-num="<?php echo esc_attr($current_page); ?>" data-total-page-num="<?php echo esc_attr(wp_json_encode($total_pages)); ?>" data-total-products="<?php echo esc_attr($total_products_to_display); ?>">
                <span class="agni-block-products-categories-pagination__nav <?php echo esc_attr(($paginationStyle == '2') ? 'load-more':'nav-prev'); ?>">
                    <?php if($paginationStyle == '2'){ echo esc_html__( 'Load More', 'agni-builder' ); } else{ ?>
                        <i class="lni lni-chevron-left"></i>
                        <span><?php echo esc_html__( 'Previous', 'agni-builder' ); ?></span>
                    <?php } ?>
                </span>
                <?php if( $paginationStyle != '2' ){ ?>
                    <span class="agni-block-products-categories-pagination__nav nav-next">
                        <span><?php echo esc_html__( 'Next', 'agni-builder' ); ?></span>
                        <i class="lni lni-chevron-right"></i>
                    </span>
                <?php } ?>
            </div>

            <?php
        }



        public function getAllSliderStyles(){
            $sliders = get_option( 'agni_slider_builder_sliders' );

            if( !empty( $sliders ) ){
                foreach ($sliders as $key => $slider) {
                    $sliders_style_hooks[] = 'cartify-slider-custom-' . $slider['id'];
                }
                $sliders_style_hooks[] = 'agni-builder-animista';

                return implode( ',', $sliders_style_hooks );
            }
            else{
                return;
            }
        }

        public function getAllSliderScripts(){

        }
        
    }
}

$agni_builder_dynamic_blocks = new AgniBuilderDynamicBlocks();