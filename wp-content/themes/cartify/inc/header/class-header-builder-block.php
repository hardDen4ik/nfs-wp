<?php  

class Agni_Header_Block{

    public function __construct(){

        if( is_admin() ){
            add_action( 'after_setup_theme', array( $this, 'set_header_default') );
            // add_action( 'after_setup_theme', array( $this, 'set_header_presets') );
        }

        add_action( 'agni_header_builder_block', array( $this, 'header_builder_block') );

        add_action( 'agni_header_main_left', array( $this, 'header_processing_blocks' ), 10 );
        add_action( 'agni_header_main_center', array( $this, 'header_processing_blocks' ), 10 );
        add_action( 'agni_header_main_right', array( $this, 'header_processing_blocks' ), 10 );

        add_action( 'agni_header_top_left', array( $this, 'header_processing_blocks' ), 10 );
        add_action( 'agni_header_top_center', array( $this, 'header_processing_blocks' ), 10 );
        add_action( 'agni_header_top_right', array( $this, 'header_processing_blocks' ), 10 );

        add_action( 'agni_header_additional_left', array( $this, 'header_processing_blocks' ), 10 );
        add_action( 'agni_header_additional_center', array( $this, 'header_processing_blocks' ), 10 );
        add_action( 'agni_header_additional_right', array( $this, 'header_processing_blocks' ), 10 );

        add_action( 'agni_header_sticky_left', array( $this, 'header_processing_blocks' ), 10 );
        add_action( 'agni_header_sticky_center', array( $this, 'header_processing_blocks' ), 10 );
        add_action( 'agni_header_sticky_right', array( $this, 'header_processing_blocks' ), 10 );

        // add_filter( 'agni_header_default', array( $this, 'get_header_default' ) );

    }

    public function header_builder_block( $header_choice ){


        $header_list = get_option('agni_header_builder_headers_list');

        if( empty( $header_list ) ){
            return;
        }


		$header_array = array();


		        if( !empty($header_choice) ){

            foreach ($header_list as $key => $header) {
                if( $header_choice == $header['id']){
                    $header_array = $header;
                }
            }
        }
        else{
            foreach ($header_list as $key => $header) {
                if( isset( $header['default'] ) && $header['default'] ){
                    $header_array = $header;
                }
                else if( $header['id'] == '0' ){
                    $header_array = $header;
                }
            }
        }

                $header_id = $header_array['id'];
        $header_content = $header_array['content'];
        $header_settings = $header_array['settings'];       

        // wp_enqueue_style( 'cartify-header-custom-' . $header_id );


        $header_overlap = isset( $header_settings['overlap'] ) ? $header_settings['overlap'] : 'off';
        $header_fullwidth = isset( $header_settings['fullwidth'] ) ? $header_settings['fullwidth'] : 'off';

        $header_container_classnames = array(
            "site-header-container",
            $header_settings['className']
        )
        ?>
        <div class="<?php echo esc_attr( cartify_prepare_classes( $header_container_classnames ) ); ?>">
        <?php

        if( !empty( $header_content ) ){

                        foreach ($header_content as $device => $content) {


                $header_classes = array(
                    'site-header-' . $device,
                    $header_overlap == 'on' ? 'overlap' : '',
                    $header_fullwidth == 'on' ? 'fullwidth' : ''
                );

                if( $device == 'mobile' ){
                    $header_classes[] = 'hide-sm';
                }
                elseif($device == 'tab'){
                    $header_classes[] = 'hide-sm-max hide-md'; 
                }
                elseif($device == 'laptop'){
                    $header_classes[] = 'hide-md-max hide-xl'; 
                }
                elseif($device == 'desktop'){
                    $header_classes[] = 'hide-xl-max'; 
                }

                $spacer_classes = array(
                    'spacer',
                    'spacer-' . $device
                );


                ?>
                <div class="<?php echo esc_attr( cartify_prepare_classes($header_classes) ); ?>">
                    <div class="<?php echo esc_attr( 'site-header-' . $device . '-container' ); ?>">
                    <?php

                    usort($content, array($this, 'sortByRowId'));

                    foreach ($content as $row) {

                        foreach ($row['content'] as $column) {
                            $row_has_content[$row['rowName']] = false;
                            foreach ($row['content'] as $column) {
                                if( !empty( $column['content'] ) ){
                                    $row_has_content[$row['rowName']] = true;
                                }
                            }
                        }


                        if( $row_has_content[$row['rowName']] ){

                                                        $header_row_pos = $row['rowName'];

                            $row_settings = $row['settings'];

                            $row_flex_type = isset($row_settings['flex-type']) ? $row_settings['flex-type'] : '';
                            $sticky_style = isset($row_settings['sticky-style']) ? $row_settings['sticky-style'] : '';

                                                        $classname = isset($row_settings['className']) ? $row_settings['className'] : '';


                            $row_classes = array(
                                'site-header-' . $header_row_pos,
                                !empty($row_flex_type) ? 'flex-' . $row_flex_type : '',
                                !empty($sticky_style) ? 'style-' . $sticky_style : '',
                                $classname
                            );

                            ?>
                            <div class="<?php echo esc_attr( cartify_prepare_classes($row_classes) ); ?>">
                                <div class="site-header-<?php echo esc_attr($header_row_pos); ?>__container">
                                <?php
                                foreach($row['content'] as $col){
                                    $header_col_pos = $col['colName'];

                                    $col_settings = isset($col['settings']) ? $col['settings'] : '';

                                                                        $col_classname = isset($col_settings['className']) ? $col_settings['className'] : '';

                                    $col_classes = array(
                                        'site-header-' . $header_row_pos . '__contents--' . $header_col_pos,
                                        $col_classname
                                    );

                                    ?>
                                    <div class="<?php echo esc_attr( cartify_prepare_classes($col_classes) ); ?>"><?php if( !empty($col['content']) ){
                                        do_action("agni_header_{$header_row_pos}_{$header_col_pos}", $col['content']); 
                                        } 
                                    ?></div>
                                    <?php
                                }
                                ?>
                                </div>
                            </div>
                        <?php
                        }
                    }
                    ?>
                    </div>
                    <div class="<?php echo esc_attr( cartify_prepare_classes( $spacer_classes ) ); ?>"></div>
                </div>
                <?php
            } ?>
            <?php
        } ?>
        </div>
        <?php
    }

    public function header_processing_blocks($blocks){
        if( !empty($blocks) ){
            foreach ($blocks as $block) {
                $block_id = $block['id'];
                $block_options = $block['settings'];

                ?>
                <?php switch ($block_id) {
                    case 'logo':
                        apply_filters( 'agni_header_logo', $block_options );
                        break;
                    case 'menu-1':
                        apply_filters( 'agni_header_menu', $block_options );
                        break;
                    case 'menu-2':
                        apply_filters( 'agni_header_additional_menu', $block_options );
                        break;
                    case 'menu-3':
                        apply_filters( 'agni_header_categories_dropdown', $block_options );
                        //cartify_header_menu_categories_dropdown($block_options); // done
                        break;
                    case 'search':
                        apply_filters( 'agni_header_search', $block_options );
                        //cartify_header_search($block_options);
                        break;
                    case 'info':
                        apply_filters( 'agni_header_additional_info', $block_options );
                        break;
                    case 'language':
                        apply_filters( 'agni_header_language', $block_options );
                        break;
                    case 'currency':
                        apply_filters( 'agni_header_currency', $block_options );
                        break;
                    case 'my-account':
                        apply_filters( 'agni_header_myaccount', $block_options );
                        //cartify_header_icons_myaccount($block_options); // done
                        break;
                    case 'cart':
                        apply_filters( 'agni_header_cart', $block_options );
                        //cartify_header_icons_cart($block_options); // done
                        break;
                    case 'wishlist':
                        apply_filters( 'agni_header_wishlist', $block_options );
                        //cartify_header_icons_wishlist($block_options); // done
                        break;
                    case 'compare':
                        apply_filters( 'agni_header_compare', $block_options );
                        break;
                    case 'social':
                        apply_filters( 'agni_header_social_icons', $block_options );
                        //cartify_header_social_icons($block_options); // done
                        break;
                    case 'button':
                        apply_filters( 'agni_header_button', $block_options );
                        //cartify_header_social_icons($block_options); // done
                        break;
                    case 'content-block':
                        apply_filters( 'agni_header_content_block', $block_options );
                        break;

                                        default:
                        # code...
                        break;
                } 
                // echo $block['id']; ?>
                <?php
            }
        }

    }

    public function sortByRowId($x, $y) {
        return $x['rowId'] - $y['rowId'];
    }

    public function set_header_default(){

        if( !current_user_can( 'edit_posts' ) ){
            return;
        }

        // delete_option('agni_header_builder_headers_list');
        if( !empty( get_option('agni_header_builder_headers_list') ) ){
            return;
        }

        $header = $header_default = array();

        $header_default = $this->get_header_json_file( 'header-default' );

        if( empty( $header_default ) ){
            return;
        }

        $header = array(
            array(
                'id' => $header_default['id'],
                'title' => $header_default['title'],
                'locked' => true,
                'content' => $header_default['content'],
                'settings' => $header_default['settings'],
            )
        );

                update_option( 'agni_header_builder_headers_list', $header );

    }

    // public function set_header_presets(){

    //     if( !current_user_can( 'edit_posts' ) ){
    //         return;
    //     }

    //     $header = $header_presets = array();

    //     $header_presets = $this->get_header_json_file( 'header-presets' );

    //     if( empty( get_option('agni_header_builder_headers_preset') ) ){
    //         update_option( 'agni_header_builder_headers_preset', $header_presets );
    //     }

    // }

    public static function get_header_json_file( $filename = '' ){

        if( empty( $filename ) ){
            return;
        }

        global $wp_filesystem;

         require_once ( ABSPATH . '/wp-admin/includes/file.php' );
        WP_Filesystem();

                $header_default_json = AGNI_TEMPLATE_DIR . '/header/helper/' . $filename . '.json';
        $header = '';
        if ( $wp_filesystem->exists( $header_default_json ) ) {
            $header = json_decode( $wp_filesystem->get_contents( $header_default_json ), true );
        }

            return $header;

    }

}

$agni_header_block = new Agni_Header_Block();

?>