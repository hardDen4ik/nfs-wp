<?php 
/**
 * Agni Ajax filter by category.
 */

if( !class_exists( 'CartifyAjaxProductPriceFilter' ) ){
    class CartifyAjaxProductPriceFilter extends WP_Widget{

        public $chosen_price_range = array( 'min' => '', 'max' => '' );

        public function __construct(){

            parent::__construct(
                'cartify_ajax_product_price_filter',
                esc_html__( 'Cartify: Filter Products by Price', 'agni-cartify' ),
                array(
                    'classname'   => 'widget_cartify_ajax_product_price_filter cartify_ajax_product_widget',
                    'description' => esc_html__( 'Ajax filter woocommerce products by brands.', 'agni-cartify' )
                )
            );	

            add_action( 'widgets_init', function() {
                register_widget( 'CartifyAjaxProductPriceFilter' );
            });

            $this->get_filter_params();
     
        }

        public function widget( $args, $instance ){

			if (!is_post_type_archive('product') && !is_tax(get_object_taxonomies('product'))) {
				return;
            }

            global $wp;

            wp_enqueue_script( 'agni-product-filters' );
            wp_enqueue_script( 'wc-add-to-cart-variation' );

            extract( $args );

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

            ?>
            
            <?php
            if ( '' === get_option( 'permalink_structure' ) ) {
                $form_action = remove_query_arg( array( 'page', 'paged', 'product-page' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
            } else {
                $form_action = preg_replace( '%\/page/[0-9]+%', '', home_url( trailingslashit( $wp->request ) ) );
            }

            $min_price = $max_price = 0;
            $step = 1;

            $agni_product_filters = new AgniProductFilters();

            $min_price = $agni_product_filters->get_products_min_price( false );
            $max_price = $agni_product_filters->get_products_max_price( false );
            
            $chosen_min_price = !empty($this->chosen_price_range['min'])?$this->chosen_price_range['min']:$min_price;
            $chosen_max_price = !empty($this->chosen_price_range['max'])?$this->chosen_price_range['max']:$max_price;

            // $growthRatio = ($max_price - $min_price) / 100 ;
            // echo $growthRatio;
            // $chosen_min_percentage = number_format($chosen_min_price / $growthRatio, 2);
            // $chosen_max_percentage = number_format($chosen_max_price / $growthRatio, 2);
            
            $range = $max_price - $min_price;
            $chosen_min_percentage = number_format(($chosen_min_price - $min_price) * 100 / $range, 2);
            $chosen_max_percentage = number_format(($chosen_max_price - $min_price) * 100 / $range, 2);

            ?>
            <div class="agni-product-price" data-tax="<?php echo esc_attr( json_encode( $data_tax ) ); ?>">
                <form class="agni-product-price-form" method="get" action="<?php echo esc_url( $form_action ); ?>">

                    <div class="agni-product-price-form__min">
                        <label><?php echo esc_html__( 'Min Price', 'agni-cartify' ); ?></label>
                        <div class="agni-product-price-form__min--input">
                            <span><?php echo get_woocommerce_currency_symbol(); ?></span>
                            <input 
                                type="number" 
                                class="min-price" 
                                name="min_price" 
                                placeholder="<?php echo esc_attr( 'Min Price', 'agni-cartify' ); ?>" 
                                min="<?php echo esc_attr( $min_price ); ?>" 
                                max="<?php echo esc_attr( $max_price - $step ); ?>" 
                                step="<?php echo esc_attr( $step ); ?>"
                                value="<?php echo esc_html( $chosen_min_price ); ?>" 
                                data-min="<?php echo esc_attr( $min_price ); ?>" 
                            />
                        </div>
                    </div>
                    
                    <div class="agni-product-price-form__max">
                        <label><?php echo esc_html__( 'Max Price', 'agni-cartify' ); ?></label>
                        <div class="agni-product-price-form__max--input">
                            <span><?php echo get_woocommerce_currency_symbol(); ?></span>
                            <input 
                                type="number" 
                                class="max-price" 
                                name="max_price" 
                                placeholder="<?php echo esc_attr( 'Max Price', 'agni-cartify' ); ?>" 
                                min="<?php echo esc_attr( $min_price + $step ); ?>" 
                                max="<?php echo esc_attr( $max_price ); ?>" 
                                step="<?php echo esc_attr( $step ); ?>"
                                value="<?php echo esc_html( $chosen_max_price ); ?>" 
                                data-max="<?php echo esc_attr( $max_price ); ?>" 
                            />
                        </div>
                    </div>
                    <button type="submit" ><?php echo esc_html__( 'Go', 'agni-cartify' ); ?></button>

                </form>
                <div class="agni-product-price-slider">
                    <span class="agni-product-price-slider__handle">
                        <span class="agni-product-price-slider__handle--min" style="width: <?php echo esc_attr( $chosen_min_percentage );?>%"></span>
                        <span class="agni-product-price-slider__handle--max" style="width: <?php echo esc_attr( $chosen_max_percentage );?>%"></span>
                        <span class="agni-product-price-slider__handle--base"></span>
                    </span>
                    <span class="agni-product-price-slider__range">
                        <span class="agni-product-price-slider__range--min"><?php echo get_woocommerce_currency_symbol(); ?><span><?php echo esc_html( $chosen_min_price ); ?></span></span>
                        <span class="agni-product-price-slider__range--max"><?php echo get_woocommerce_currency_symbol(); ?><span><?php echo esc_html( $chosen_max_price ); ?></span></span>
                    </span>
                </div>
            </div>

            <?php echo wp_kses_post( $after_widget );

        }

        public function get_filter_params(){
            if( isset($_REQUEST['min_price']) ){
                $this->chosen_price_range['min'] = $_REQUEST['min_price'];
            }
            if( isset($_REQUEST['max_price']) ){
                $this->chosen_price_range['max'] = $_REQUEST['max_price'];
            }

        }


        public function form( $instance ){
            if ( isset( $instance[ 'title' ] ) ) {
                $title = $instance[ 'title' ];
            }
            else {
                $title = esc_html__( 'Filter by Price', 'agni-cartify' );
            }

            $collapsed = isset( $instance[ 'collapsed' ] ) ? $instance['collapsed'] : '';

            ?>

            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title', 'agni-cartify' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
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
            $instance['collapsed'] = ( !empty( $new_instance['collapsed'] ) ) ? strip_tags( $new_instance['collapsed'] ) : '';

            // print_r( $instance );

            return $instance;
        }


    }
}

$cartify_ajax_product_price_filter = new CartifyAjaxProductPriceFilter();

// if( !function_exists( 'cartify_register_ajax_product_filter' ) ){
//     function cartify_register_ajax_product_filter(){
//         register_widget( 'CartifyAjaxProductPriceFilter' );
//     }
// }

// add_action( 'widget_init', 'cartify_register_ajax_product_filter' );