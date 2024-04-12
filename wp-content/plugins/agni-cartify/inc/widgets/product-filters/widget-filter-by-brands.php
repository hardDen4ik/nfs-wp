<?php 
/**
 * Agni Ajax filter by category.
 */

if( !class_exists( 'CartifyAjaxProductBrandFilter' ) ){
    class CartifyAjaxProductBrandFilter extends WP_Widget{

        public $chosen_brands = array();

        public function __construct(){

            parent::__construct(
                'cartify_ajax_product_brand_filter',
                esc_html__( 'Cartify: Filter Products by Brands', 'agni-cartify' ),
                array(
                    'classname'   => 'widget_cartify_ajax_product_brand_filter cartify_ajax_product_widget',
                    'description' => esc_html__( 'Ajax filter woocommerce products by brands.', 'agni-cartify' )
                )
            );	

            add_action( 'widgets_init', function() {
                register_widget( 'CartifyAjaxProductBrandFilter' );
            });
     
            $this->get_filter_params();
        }

        public function widget( $args, $instance ){

			if (!is_post_type_archive('product') && !is_tax(get_object_taxonomies('product'))) {
				return;
            }

            wp_enqueue_script( 'agni-product-filters' );
            wp_enqueue_script( 'wc-add-to-cart-variation' );

            extract( $args );


            $show_search = isset( $instance[ 'show_search' ] ) ? $instance['show_search'] : 1;
            $collapsed = isset( $instance[ 'collapsed' ] ) ? $instance['collapsed'] : '';


            $expanded = !$collapsed ? ' expanded' : '';

            $pattern = '/class="([\w\s]+)"/i';
            $replacement = 'class="${1}' .$expanded. '"';

            $before_widget = preg_replace( $pattern, $replacement, $before_widget );
            
            echo wp_kses_post( $before_widget );
		
            $title = apply_filters('widget_title', $instance['title'] );
                
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


            $taxonomy     = 'product_brand';
            $orderby      = 'name';  //term_order, count, term_id
            $show_count   = 0;      // 1 for yes, 0 for no
            $pad_counts   = 0;      // 1 for yes, 0 for no
            $hierarchical = 0;      // 1 for yes, 0 for no  
            $title        = '';  
            $empty        = 0;

            $args = array(
                'taxonomy'     => $taxonomy,
                'orderby'      => $orderby,
                'show_count'   => $show_count,
                'pad_counts'   => $pad_counts,
                'hierarchical' => $hierarchical,
                'title_li'     => $title,
                'hide_empty'   => $empty
            );


            $all_categories = get_categories( $args ); ?>
            <div class="agni-product-brands" data-tax="<?php echo esc_attr( json_encode( $data_tax ) ); ?>">
                <?php if( $show_search == true ){ ?>
                    <input type="text" placeholder="<?php echo esc_attr( 'Find a Brand', 'agni-cartify' ); ?>" value="" />
                <?php } ?>
                <ul>
                
                <?php

                foreach ($all_categories as $cat) {
                    $category_id = $cat->term_id;   

                    $cat_item_classes = array(
                        "agni-product-categories__cat-item",
                        "cat-item-" . $category_id,
                        in_array( $category_id, $this->chosen_brands ) ? 'active' : '',
                    )

                ?>
                    <li class="<?php echo esc_attr( Agni_Cartify_Helper::prepare_classes( $cat_item_classes ) ); ?>">
                        <a href="<?php echo esc_url( get_term_link($cat->slug, 'product_brand') ); ?>" data-cat-id="<?php echo esc_attr( $category_id ); ?>"><?php echo esc_html( $cat->name ); ?></a>
                    </li>

                <?php } 
                ?>
                </ul>
            </div>
            <?php echo wp_kses_post( $after_widget );

        }


        public function get_filter_params(){
            if(isset( $_REQUEST[ 'filter_product_brand' ] )){
                $this->chosen_brands = explode( ',',$_REQUEST[ 'filter_product_brand' ] );
            }
            
        }


        public function form( $instance ){

            if ( isset( $instance[ 'title' ] ) ) {
                $title = $instance[ 'title' ];
            }
            else {
                $title = esc_html__( 'Filter by Brands', 'agni-cartify' );
            }


            $show_search = isset( $instance[ 'show_search' ] ) ? $instance['show_search'] : 1;
            $collapsed = isset( $instance[ 'collapsed' ] ) ? $instance['collapsed'] : '';

            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title', 'agni-cartify' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>
            <p>
				<input id="<?php echo esc_attr( $this->get_field_id('show_search') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_search') ); ?>" type="checkbox" value="1" <?php checked( $show_search, 1 ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id('show_search') ); ?>"><?php echo esc_html__( 'Show search input field', 'agni-cartify' ); ?></label>
			</p>
            <p>
				<input id="<?php echo esc_attr( $this->get_field_id('collapsed') ); ?>" name="<?php echo esc_attr( $this->get_field_name('collapsed') ); ?>" type="checkbox" value="1" <?php checked( $collapsed, 1 ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id('collapsed') ); ?>"><?php echo esc_html__( 'Collapsed by Default', 'agni-cartify' ); ?></label>
			</p>
            <?php
        }

        public function update( $new_instance, $old_instance ){
            $instance = $old_instance;

            $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
            $instance['show_search'] = ( !empty( $new_instance['show_search'] ) ) ? strip_tags( $new_instance['show_search'] ) : '';
            $instance['collapsed'] = ( !empty( $new_instance['collapsed'] ) ) ? strip_tags( $new_instance['collapsed'] ) : '';

            // print_r( $instance );

            return $instance;
        }


    }
}

$cartify_ajax_product_brands_filter = new CartifyAjaxProductBrandFilter();

// if( !function_exists( 'cartify_register_ajax_product_filter' ) ){
//     function cartify_register_ajax_product_filter(){
//         register_widget( 'CartifyAjaxProductBrandFilter' );
//     }
// }

// add_action( 'widget_init', 'cartify_register_ajax_product_filter' );