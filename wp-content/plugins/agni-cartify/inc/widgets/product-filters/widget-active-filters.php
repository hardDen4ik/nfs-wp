<?php 
/**
 * Agni Ajax filter by category.
 */

if( !class_exists( 'CartifyAjaxProductActiveFilters' ) ){
    class CartifyAjaxProductActiveFilters extends WP_Widget{
        public function __construct(){

            parent::__construct(
                'cartify_ajax_product_active_filters',
                esc_html__( 'Cartify: Active Product Filters', 'agni-cartify' ),
                array(
                    'classname'   => 'widget_cartify_ajax_product_active_filters cartify_ajax_product_widget expanded',
                    'description' => esc_html__( 'Ajax filter woocommerce products by categories.', 'agni-cartify' )
                )
            );	

            add_action( 'widgets_init', function() {
                register_widget( 'CartifyAjaxProductActiveFilters' );
            });
     
        }

        public function widget( $args, $instance ){

			if (!is_post_type_archive('product') && !is_tax(get_object_taxonomies('product'))) {
				return;
            }
            extract( $args );
		
            $title = apply_filters('widget_title', $instance['title'] );
            
            echo wp_kses_post( $before_widget );
                
            if ( $title ){
                echo wp_kses_post( $before_title . $title . $after_title );
            }

            $cat_term_id = $cat_term_taxonomy = ''; 
            $data_tax = array();
            if( get_query_var( 'term' ) ){
                $cat_term = get_queried_object();

                if($cat_term->taxonomy == 'product_cat' || $cat_term->taxonomy == 'product_tag'){
                    $cat_term_id = $cat_term->term_id;
                    $cat_term_taxonomy = $cat_term->taxonomy;
                    $data_tax[] = array(
                        'param' => $cat_term_taxonomy,
                        'value' => $cat_term_id
                    );
                }
            }

            ?>

            <ul class="agni-product-active-filters" data-tax="<?php echo esc_attr( json_encode( $data_tax ) ); ?>">
            
            <?php


            ?>
            </ul>

            <?php echo wp_kses_post( $after_widget );

        }



        public function form( $instance ){
            if ( isset( $instance[ 'title' ] ) ) {
                $title = $instance[ 'title' ];
            }
            else {
                $title = esc_html__( 'Active Filters', 'agni-cartify' );
            }

            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title', 'agni-cartify' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>

            <?php
            
        }

        public function update( $new_instance, $old_instance ){

            $instance = $old_instance;

            $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

            // print_r( $instance );

            return $instance;
        }


    }
}

$cartify_ajax_product_active_filters = new CartifyAjaxProductActiveFilters();

// if( !function_exists( 'cartify_register_ajax_product_filter' ) ){
//     function cartify_register_ajax_product_filter(){
//         register_widget( 'CartifyAjaxProductActiveFilters' );
//     }
// }

// add_action( 'widget_init', 'cartify_register_ajax_product_filter' );