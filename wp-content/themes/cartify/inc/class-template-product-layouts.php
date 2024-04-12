<?php 

class Agni_Product_Layouts{

    public $layout_id;

    public $layout_classes;

    public function __construct(){

        $this->includes();

        if( is_admin() ){
            add_action( 'after_setup_theme', array( $this, 'set_product_presets' ) );
            add_action( 'after_setup_theme', array( $this, 'set_product_default' ) );
        }

        add_action( 'woocommerce_init', array( $this, 'remove_existing_actions' ) );

        // add_action( 'woocommerce_before_main_content', array( $this, 'layout_setup' ), 1 );

        add_action( 'agni_single_product_layout', array( $this, 'layout_init' ) );

        add_filter( 'agni_single_product_layout_container_classes', array( $this, 'layout_container_class' ) );

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

    }

    public function includes(){

        require_once AGNI_TEMPLATE_DIR . '/product-layout/class-product-layout-builder-block.php';
        require_once AGNI_TEMPLATE_DIR . '/product-layout/product-layout-builder-block-processor.php';
        require_once AGNI_TEMPLATE_DIR . '/product-layout/product-layout-builder-css-processor.php';

    }

    public static function remove_existing_actions(){

        remove_action( 'woocommerce_before_single_product_summary', 'cartify_woocommerce_single_product_video', 25 );
        remove_action( 'woocommerce_before_single_product_summary', 'cartify_woocommerce_single_product_360_image', 30 );
        remove_action( 'woocommerce_before_single_product_summary', 'cartify_woocommerce_label_hot', 9 );
        remove_action( 'woocommerce_before_single_product_summary', 'cartify_woocommerce_label_new', 9 );
        remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
        remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
        remove_action( 'woocommerce_single_product_summary', 'cartify_woocommerce_after_single_product_title', 9 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );

        remove_action( 'woocommerce_single_product_summary', array( 'Agni_Wishlist', 'add_to_wishlist' ), 35 );


                remove_action( 'woocommerce_single_product_summary', 'cartify_woocommerce_template_single_features', 15 );

        remove_action( 'woocommerce_single_product_summary', 'cartify_woocommerce_single_product_sale_countdown', 12 );
        remove_action( 'woocommerce_single_product_summary', 'cartify_woocommerce_single_product_featured', 4 );
        remove_action( 'woocommerce_single_product_summary', 'cartify_woocommerce_single_additional_info', 45 );

        remove_action( 'woocommerce_single_product_summary', 'cartify_compare_add_to_compare_button', 38 );
        remove_action( 'woocommerce_single_product_summary', 'cartify_woocommerce_single_compare_button', 55 );


        remove_action( 'woocommerce_after_single_product_summary', 'cartify_addon_products_display_single_product', 9 );
        remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
        remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 ); 
        remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

        remove_action( 'woocommerce_after_single_product_summary', 'cartify_recently_viewed_products', 10 );
        remove_action( 'woocommerce_after_single_product_summary', 'cartify_compare_display_single_product', 20 );


                remove_action( 'agni_woocommerce_after_single_product_title', 'cartify_woocommerce_template_single_brand' );
        remove_action( 'agni_woocommerce_after_single_product_title', 'woocommerce_template_single_rating' );

        remove_action( 'agni_woocommerce_single_product_additional_info', 'cartify_woocommerce_template_single_offers' );
        remove_action( 'agni_woocommerce_single_product_additional_info', 'cartify_woocommerce_template_single_shipping_info', 15 );

        remove_action( 'woocommerce_sidebar', 'cartify_woocommerce_single_get_sidebar', 10 );


    }


    public function layout_init( $layout_id ){

        $layout_list = get_option('agni_product_builder_layouts_list');


        if( empty( $layout_list ) ){
            return;
        }

        foreach ($layout_list as $key => $layout) {
            if( !empty($layout_id) && $layout_id == $layout['id']){
                $chosen_layout = $layout;
            }
            else if( isset( $layout['default'] ) && $layout['default'] ){
                $chosen_layout = $layout;
            }
            else if( $layout['id'] == '0' ){
                $chosen_layout = $layout;
            }
        }

                $chosen_layout_id = $chosen_layout['id'];
        $chosen_layout_content = $chosen_layout['content'];
        $chosen_layout_template = $chosen_layout['template'];
        $chosen_layout_settings = $chosen_layout['settings'];

        // wp_enqueue_style( 'cartify-product-layout-custom-' . $chosen_layout_id );


        $this->layout_classes[] = 'layout-template-' . $chosen_layout_template;
        $this->layout_classes[] = !empty( $chosen_layout_settings['stretch'] ) ? 'has-columns-' . $chosen_layout_settings['stretch'] : '';
        $this->layout_classes[] = isset( $chosen_layout_settings['className'] ) ? $chosen_layout_settings['className'] : '';

        $this->prepare_template_sidebar($chosen_layout_template);

        foreach ($chosen_layout_content as $placement_key => $placement) {
            $hook = $placement['slug'];
            $placement_settings = $placement['settings'];

            $placement_settings['className'] = isset($placement_settings['className']) ? $placement_settings['className'] : $placement['slug'];

            add_action( "agni_product_hook_{$hook}_classes", function($classes) use($placement_settings){
                $classes[] = $placement_settings['className'];
                $classes[] = (isset($placement_settings['sticky']) && $placement_settings['sticky']) ? 'sticky' : '';
                $classes[] = !empty($placement_settings['stretch']) ? 'has-columns-' . $placement_settings['stretch'] : '';
                $classes[] = (!empty($placement_settings['bg-color']) && $placement_settings['bg-fullwidth']) ? 'has-background-full' : '';

                return $classes;
            } );

                        if( !empty($placement_settings['bg-color']) ){
                add_action( "agni_woocommerce_before_{$hook}_container", function() use($hook){
                    ?>
                    <div class="agni-product-hook-<?php echo esc_attr($hook); ?>-background <?php echo esc_attr($hook); ?>-background"></div>
                    <?php
                } );
            }

            // foreach ($row['content'] as $col_key => $col) { 
            $i = 10; 
            if( isset($placement['content']) && !empty($placement['content']) ){
                $this->layout_block_processor_loop($placement, $hook, $i);
            }

        }

    }

    public function layout_block_processor_loop($blocks_array, $hook, $i){
        foreach ($blocks_array['content'] as $block_key => $block) {
            $block['hook'] = $hook;
            $block['priority'] = $i + 1;

            if( $block['slug'] == 'columns' ){
                // add column hook with prioriy
                $j = $block['priority'];
                // add_action( 'woocommerce_' . $hook, 'cartify_woocommerce_single_row_open_tag', $j );
                add_action('woocommerce_' . $hook, function() use ($block) { 
                    $row_settings = isset( $block['settings'] ) ? $block['settings'] : '';

                    $row_classes = array(
                        "agni-product-layout-row",
                        "agni-product-layout-row-" . $block['id'],
                        isset($row_settings['className']) ? $row_settings['className'] : ''
                    );
                    ?><div class="<?php echo esc_attr( cartify_prepare_classes( $row_classes ) ); ?>"><?php
                }, $j);
                foreach ($block['content'] as $inner_col_key => $inner_col) {
                    $k = $j + 1;
                    add_action( 'woocommerce_' . $hook, function() use ($inner_col) { 
                        $inner_col_settings = isset( $inner_col['settings'] ) ? $inner_col['settings'] : '';

                                                $col_classes = array(
                            "agni-product-layout-column",
                            "agni-product-layout-column-" . $inner_col['id'],
                            isset( $inner_col_settings['direction'] ) ? $inner_col_settings['direction'] : '',
                            isset($inner_col_settings['className']) ? $inner_col_settings['className'] : ''
                        );
                        ?><div class="<?php echo esc_attr( cartify_prepare_classes( $col_classes ) ); ?>"><?php
                    }, $k );
                    // foreach ($inner_col['content'] as $inner_block_key => $inner_block) {
                    //     $inner_block['hook'] = $hook;
                    //     $inner_block['priority'] = $k + 1;
                    //     // echo apply_filters( 'agni_product_layout_block_processor', $inner_block );

                    //     $k = $inner_block['priority'] + 1; 
                    // }

                    $k = $this->layout_block_processor_loop($inner_col, $hook, $k );

                    add_action( 'woocommerce_' . $hook, function() use($k){
                        ?>
                        </div>
                        <?php
                    }, $k );
                    $j = $k + 1; 
                }
                add_action( 'woocommerce_' . $hook, function() use($j){
                    ?>
                    </div>
                    <?php
                }, $j );
                $i = $j + 1;
            }
            else{
                echo apply_filters( 'agni_product_layout_block_processor', $block );
            }
            $i = $i + 4;

                    } 
        return $i;

    }


    public function layout_container_class($classes){

        if( !empty( $this->layout_classes ) ){
            foreach ($this->layout_classes as $class) {
                $classes[] = $class;
            }
        }

        return $classes;
    }

    public function prepare_template_sidebar( $layout_template ){
        if( $layout_template == 1 || $layout_template == 4 ){
            add_action( 'agni_woocommerce_after_single_product_summary', 'cartify_woocommerce_single_get_sidebar', 10 );
        }
        if( $layout_template == 2 ){
            add_action( 'woocommerce_after_single_product', 'cartify_woocommerce_single_get_sidebar', 10 );
        }
    }

    public function set_product_default(){

        if( !current_user_can( 'edit_posts' ) ){
            return;
        }

        if( !empty( get_option('agni_product_builder_layouts_list') ) ){
            return;
        }


        $layout = $product_layout_default = array();

        $product_layout_default = $this->get_product_default();

        if( empty( $product_layout_default ) ){
            return;
        }

        $product_layout_default['locked'] = true;

        array_push( $layout, $product_layout_default );

                update_option( 'agni_product_builder_layouts_list', $layout );

    }
    public function set_product_presets(){

        if( !current_user_can( 'edit_posts' ) ){
            return;
        }

        $product = $product_layout_presets = array();

        $product_layout_presets = $this->get_product_presets();

        // $layout = array(
        //     array(
        //         'id' => $product_layout_presets['id'],
        //         'title' => $product_layout_presets['title'],
        //         'locked' => true,
        //         'content' => $product_layout_presets['content'],
        //         'settings' => $product_layout_presets['settings'],
        //     )
        // );

                if( empty( get_option('agni_product_builder_layouts_preset') ) ){
            update_option( 'agni_product_builder_layouts_preset', $product_layout_presets );
        }

    }


    public function get_product_default(){

        global $wp_filesystem;

         require_once ( ABSPATH . '/wp-admin/includes/file.php' );
        WP_Filesystem();

                $product_default_json = AGNI_TEMPLATE_DIR . '/product-layout/helper/product-layout-default.json';

        $product = '';

                if ( $wp_filesystem->exists( $product_default_json ) ) {
            $product = json_decode( $wp_filesystem->get_contents( $product_default_json ), true );
        }

            return $product;

    }

    public function get_product_presets(){

        global $wp_filesystem;

         require_once ( ABSPATH . '/wp-admin/includes/file.php' );
        WP_Filesystem();

                $product_default_json = AGNI_TEMPLATE_DIR . '/product-layout/helper/product-layout-presets.json';

        $product = '';

                if ( $wp_filesystem->exists( $product_default_json ) ) {
            $product = json_decode( $wp_filesystem->get_contents( $product_default_json ), true );
        }

            return $product;

    }

    public function get_existing_layouts(){
        return get_option('agni_product_builder_layouts_list');
    }

    public function enqueue_scripts(){

                $layouts = $this->get_existing_layouts();

                if( empty( $layouts ) ){
            return;
        }

        foreach ($layouts as $key => $layout) {
            $layout_id = $layout['id'];

            $styles = apply_filters( 'agni_product_layout_css', $layout );

            // register styles
            wp_register_style( 'cartify-product-layout-custom-' . $layout_id, AGNI_FRAMEWORK_CSS_URL . '/custom.css' );
            wp_add_inline_style( 'cartify-product-layout-custom-' . $layout_id, $styles );

            // enqueue styles
            wp_enqueue_style( 'cartify-product-layout-styles', AGNI_FRAMEWORK_CSS_URL . '/product-layout/product-layout.css', array(), wp_get_theme()->get('Version'));
            wp_style_add_data( 'cartify-product-layout-styles', 'rtl', 'replace' );

        }

        $product_id = get_the_id();

        $layout_id = get_post_meta( $product_id, 'agni_product_layout_choice', true );


        if( empty( $layout_id ) ){
            $layout_list = get_option('agni_product_builder_layouts_list');

            if( !empty( $layout_list ) ){
                foreach ($layout_list as $key => $layout) {
                    if( isset( $layout['default'] ) && $layout['default'] ){
                        $layout_id = $layout['id'];
                    }
                    else if( $layout['id'] == '0' ){
                        $layout_id = $layout['id'];
                    }
                }
            }
        }

                wp_enqueue_style( 'cartify-product-layout-custom-' . $layout_id );

    }

    public function admin_enqueue_scripts(){
        // enqueue styles

        wp_enqueue_style( 'cartify-product-layout-editor-styles', AGNI_FRAMEWORK_CSS_URL . '/product-layout/product-layout-editor.css', array(), wp_get_theme()->get('Version'));

            }
}

$agni_product_layouts = new Agni_Product_Layouts();

?>