<?php 
/**
 * Agni Ajax filter by rating.
 */

if( !class_exists( 'CartifyAjaxProductRatingFilter' ) ){
    class CartifyAjaxProductRatingFilter extends WP_Widget{

        public $chosen_ratings = array();

        public function __construct(){

            parent::__construct(
                'cartify_ajax_product_rating_filter',
                esc_html__( 'Cartify: Filter Products by Ratings', 'agni-cartify' ),
                array(
                    'classname'   => 'widget_cartify_ajax_product_rating_filter cartify_ajax_product_widget',
                    'description' => esc_html__( 'Ajax filter woocommerce products by ratings.', 'agni-cartify' )
                )
            );	

            add_action( 'widgets_init', function() {
                register_widget( 'CartifyAjaxProductRatingFilter' );
            });
     
            $this->get_filter_params();
        }

        public function widget( $args, $instance ){

			if (!is_post_type_archive('product') && !is_tax(get_object_taxonomies('product'))) {
				return;
            }

            extract( $args );   

            wp_enqueue_script( 'agni-product-filters' );
            wp_enqueue_script( 'wc-add-to-cart-variation' );


            $ratings_count = array();
            $show_five = isset( $instance[ 'show_five' ] ) ? $instance['show_five'] : 1;
            $count = isset( $instance[ 'count' ] ) ? $instance['count'] : 1;
            $hide_empty = isset( $instance[ 'hide_empty' ] ) ? $instance['hide_empty'] : '';
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



            global $wp;
           
            // print_r( $this->get_products_count() );

            // print_r( wc_get_product_visibility_term_ids() ); ?>
            <div class="agni-product-ratings" data-tax="<?php echo esc_attr( json_encode( $data_tax ) ); ?>">
                <ul>
                <?php

                for ($rating = ($show_five == true) ? 5 : 4; $rating > 0; $rating--) {
                    
                    $rating_classes = array(
                        'agni-product-rating',
                        'star-' . $rating
                    );

                    $rating_query = ($rating != '5') ? $rating . '+' : $rating;

                    if( !($hide_empty == true && $this->get_products_count( $rating ) == 0) ){ ?>
                    <li class="<?php echo esc_attr( Agni_Cartify_Helper::prepare_classes( $rating_classes ) ); ?>">
                        <a href="<?php echo esc_url( add_query_arg( 'rating', $rating_query, home_url( $wp->request )) ) ?>"><span class="star-rating"><?php echo wp_kses_post( wc_get_star_rating_html( $rating ) ); ?></span><span><?php echo $rating != '5' ? esc_html__( '& Up', 'agni-cartify' ) : ''; ?></span>
                        <?php if( $count == true ){ ?>
                            (<?php echo esc_html( $this->get_products_count( $rating ) ); ?>)
                        <?php } ?>
                        </a>
                    </li>

                    <?php }
                } 
                ?>
                </ul>
            </div>
            <?php echo wp_kses_post( $after_widget );

        }

        public function get_products_count( $rating ){

            $ratings_count = array(
                '1' => 0,
                '2' => 0,
                '3' => 0,
                '4' => 0,
                '5' => 0,
            );

            $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy'  => 'product_visibility',
                        'terms'     => array( 'exclude-from-catalog' ),
                        'field'     => 'name',
                        'operator'  => 'NOT IN',
                    ),
                ),
                'nopaging' => true,
                'fields' => 'ids',

            );


            $rating_query = new WP_Query( $args );

            // print_r( $rating_query );
            if( $rating_query->have_posts() ){
                while ($rating_query->have_posts()) { $rating_query->the_post();

                    $product = wc_get_product( get_the_id() );
                    
                    // echo '<div>'.get_the_id().'</div>';

                    $rating_count = $product->get_rating_count();
                    // echo $rating_count;
                    if ( $rating_count > 0 ){
                        $average_rating = $product->get_average_rating();
                        if( $average_rating == 5 ){
                            $ratings_count['5'] = $ratings_count['5'] + 1;
                        }
                        elseif ( $average_rating >= 4 && $average_rating < 5  ) {
                            $ratings_count['4'] = $ratings_count['4'] + 1;
                        }
                        elseif ( $average_rating >= 3 && $average_rating < 4  ) {
                            $ratings_count['3'] = $ratings_count['3'] + 1;
                        }
                        elseif ( $average_rating >= 2 && $average_rating < 3  ) {
                            $ratings_count['2'] = $ratings_count['2'] + 1;
                        }
                        elseif ( $average_rating >= 1 && $average_rating < 2  ) {
                            $ratings_count['1'] = $ratings_count['1'] + 1;
                        }
                    }
    
                }
            }

            return $ratings_count[$rating];
        }

        public function get_filter_params(){
            if(isset( $_REQUEST[ 'rating_filter' ] )){
                $this->chosen_ratings = explode( ',',$_REQUEST[ 'rating_filter' ] );
            }
            
        }


        public function form( $instance ){

            if ( isset( $instance[ 'title' ] ) ) {
                $title = $instance[ 'title' ];
            }
            else {
                $title = esc_html__( 'Filter by Ratings', 'agni-cartify' );
            }


            $show_five = isset( $instance[ 'show_five' ] ) ? $instance['show_five'] : 1;
            $count = isset( $instance[ 'count' ] ) ? $instance['count'] : 1;
            $hide_empty = isset( $instance[ 'hide_empty' ] ) ? $instance['hide_empty'] : '';
            $collapsed = isset( $instance[ 'collapsed' ] ) ? $instance['collapsed'] : '';

            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title', 'agni-cartify' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>
            <p>
				<input id="<?php echo esc_attr( $this->get_field_id('count') ); ?>" name="<?php echo esc_attr( $this->get_field_name('count') ); ?>" type="checkbox" value="1" <?php checked( $count, 1 ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id('count') ); ?>"><?php echo esc_html__( 'Show product counts', 'agni-cartify' ); ?></label>
			</p>
            <p>
				<input id="<?php echo esc_attr( $this->get_field_id('show_five') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_five') ); ?>" type="checkbox" value="1" <?php checked( $show_five, 1 ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id('show_five') ); ?>"><?php echo esc_html__( 'Show 5 Stars', 'agni-cartify' ); ?></label>
			</p>
            <p>
				<input id="<?php echo esc_attr( $this->get_field_id('hide_empty') ); ?>" name="<?php echo esc_attr( $this->get_field_name('hide_empty') ); ?>" type="checkbox" value="1" <?php checked( $hide_empty, 1 ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id('hide_empty') ); ?>"><?php echo esc_html__( 'Hide the empty star(s) ', 'agni-cartify' ); ?></label>
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
            $instance['count'] = ( !empty( $new_instance['count'] ) ) ? strip_tags( $new_instance['count'] ) : '';
            $instance['show_five'] = ( !empty( $new_instance['show_five'] ) ) ? strip_tags( $new_instance['show_five'] ) : '';
            $instance['hide_empty'] = ( !empty( $new_instance['hide_empty'] ) ) ? strip_tags( $new_instance['hide_empty'] ) : '';
            $instance['collapsed'] = ( !empty( $new_instance['collapsed'] ) ) ? strip_tags( $new_instance['collapsed'] ) : '';

            // print_r( $instance );

            return $instance;
        }


    }
}

$cartify_ajax_product_ratings_filter = new CartifyAjaxProductRatingFilter();

// if( !function_exists( 'cartify_register_ajax_product_filter' ) ){
//     function cartify_register_ajax_product_filter(){
//         register_widget( 'CartifyAjaxProductRatingFilter' );
//     }
// }

// add_action( 'widget_init', 'cartify_register_ajax_product_filter' );