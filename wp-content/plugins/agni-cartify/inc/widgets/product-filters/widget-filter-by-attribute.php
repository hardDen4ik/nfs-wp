<?php 
/**
 * Agni Ajax filter by category.
 */

if( !class_exists( 'CartifyAjaxProductAttributeFilter' ) ){
    class CartifyAjaxProductAttributeFilter extends WP_Widget{

        public $chosen_attributes = array();

        public function __construct(){

            parent::__construct(
                'cartify_ajax_product_attribute_filter',
                esc_html__( 'Cartify: Filter Products by Attribute', 'agni-cartify' ),
                array(
                    'classname'   => 'widget_cartify_ajax_product_attribute_filter cartify_ajax_product_widget',
                    'description' => esc_html__( 'Ajax filter woocommerce products by categories.', 'agni-cartify' )
                )
            );	

            add_action( 'widgets_init', function() {
                register_widget( 'CartifyAjaxProductAttributeFilter' );
            });

     
        }

        public function widget( $args, $instance ){

			if (!is_post_type_archive('product') && !is_tax(get_object_taxonomies('product'))) {
				return;
            }
            extract( $args );

            global $wp;

            wp_enqueue_script( 'agni-product-filters' );
            wp_enqueue_script( 'wc-add-to-cart-variation' );


            $attribute_choice = isset( $instance[ 'attribute' ] ) ? $instance['attribute'] : '';
            $attribute_limit = isset( $instance[ 'limit' ] ) ? $instance['limit'] : '';
            $query_choice = isset( $instance[ 'query_type' ] ) ? $instance['query_type'] : 'and';
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


            if( isset( $_REQUEST['filter_' . $attribute_choice ] ) ){
                $this->chosen_attributes[$attribute_choice] = explode( ',', $_REQUEST['filter_' . $attribute_choice ] );
            }

            $chosen_attributes = isset( $this->chosen_attributes[$attribute_choice] ) ? $this->chosen_attributes[$attribute_choice] : array();
            // print_r( $this->chosen_attributes[$attribute_choice] );


            // print_r( wc_get_attribute_taxonomies() );



            // echo $attribute_choice;
            $terms = get_terms( 'pa_'.$attribute_choice );

            // print_r( $terms );
            $attribute_taxonomies = wc_get_attribute_taxonomies();

            // print_r( $attribute_taxonomies );

            $terms_list = [];
            $attribute_type = '';

            foreach($attribute_taxonomies as $tax ){
                if( $tax->attribute_name == $attribute_choice ){
                    $attribute_type = $tax->attribute_type;
                }
            }


            $attributes_classes = array(
                "agni-product-attributes",
                "attribute-" . $attribute_choice,
                "type-" . $attribute_type,
                "query-" . $query_choice
            );

            ?>

            <?php if( !empty( $attribute_limit ) && $attribute_limit != 0 ){
                ?>
                <div class="<?php echo esc_attr( Agni_Cartify_Helper::prepare_classes($attributes_classes) ); ?>" data-limit="<?php echo esc_attr( $attribute_limit ); ?>" data-query="<?php echo esc_attr( $query_choice ); ?>" data-tax="<?php echo esc_attr( json_encode( $data_tax ) ); ?>">
                <?php
            }
            else {
                ?>
                <div class="<?php echo esc_attr( Agni_Cartify_Helper::prepare_classes($attributes_classes) ); ?>" data-query="<?php echo esc_attr( $query_choice ); ?>" data-tax="<?php echo esc_attr( json_encode( $data_tax ) ); ?>">
                <?php
            } ?>
                <ul>
                
                <?php

// $args = array(
//     'post_type' => 'product',
//     'tax_query' => array(
//         'relation' => 'OR',
//         array(
//             'taxonomy' => 'product_cat',
//             'terms'    => $cat_term_id,
//         ),
//     ),
// );
// $tax_product_query = new WP_Query( $args );
// echo $tax_product_query->found_posts;



                foreach( $terms as $index => $term ){
                    $terms_list[$term->slug] = $term->name;
                                    
                    $term_value = get_term_meta( $term->term_id, 'agni_variation_swatch_field', true );
                    
                    ?>
                    <li class="<?php echo in_array( $term->slug, $chosen_attributes ) ? 'active' : ''; ?>">
                    <?php
                    $url = home_url( $wp->request );
                    // if( !empty( $cat_term_id ) ){
                    //     $url = add_query_arg( 'cat', $cat_term_id, home_url( $wp->request ));
                    // }
                    switch( $attribute_type ){
                        case 'color':
                            ?>
                            <a href="<?php echo esc_url( add_query_arg( 'filter_' . $attribute_choice, $term->slug, $url) ) ?>" style="background-color: <?php echo esc_attr( $term_value ); ?>; border-color: <?php echo esc_attr( $term_value ); ?>;" title="<?php echo esc_attr( $term->name );  ?>"><span><?php echo esc_html( $term->name ); ?></span></a>
                            <?php
                            break;
                        case 'label':
                            ?>
                            <a href="<?php echo esc_url( add_query_arg( 'filter_' . $attribute_choice, $term->slug, $url) ) ?>" title="<?php echo esc_attr( $term->name );  ?>"><span><?php echo esc_html( $term_value ); ?></span></a>
                            <?php
                            break;
                        case 'image':
                            ?>
                            <a href="<?php echo esc_url( add_query_arg( 'filter_' . $attribute_choice, $term->slug, $url) ) ?>" title="<?php echo esc_attr( $term->name );  ?>"><span><?php echo wp_kses_post( wp_get_attachment_image($term_value) ); ?></span></a>
                            <?php
                            break;
                        default:
                            ?>
                            <a href="<?php echo esc_url( add_query_arg( 'filter_' . $attribute_choice, $term->slug, $url) ) ?>"  title="<?php echo esc_attr( $term->name ); ?>"><span><?php echo esc_html( $term->name ); ?></span></a>
                            <?php
                            break;
                    }
                    // print_r( get_option( 'agni_variation_swatch_field_' . $term->term_id ) );

                    ?>
                    </li>
                    <?php
                }

                // print_r( $terms_list );

                ?>
                </ul>

                <?php if( !empty( $attribute_limit ) && $attribute_limit != 0 ){ ?>
                <div class="list-toggle">
                    <span class=""><?php echo esc_html__( 'Show More', 'agni-cartify' ); ?></span>
                    <span><?php echo esc_html__( 'Show Less', 'agni-cartify' ); ?></span>
                </div>
                <?php } ?>
            </div>
            <?php  echo wp_kses_post( $after_widget );

        }


        public function form( $instance ){
            if ( isset( $instance[ 'title' ] ) ) {
                $title = $instance[ 'title' ];
            }
            else {
                $title = esc_html__( 'Filter by Attribute', 'agni-cartify' );
            }

            $attribute_choice = isset( $instance[ 'attribute' ] ) ? $instance['attribute'] : '';
            $attribute_limit = isset( $instance['limit'] ) ? $instance['limit'] : '';
            // $display_type = isset( $instance[ 'display_type' ] ) ? $instance['display_type'] : '';
            $query_choice = isset( $instance[ 'query_type' ] ) ? $instance['query_type'] : 'and';
            $collapsed = isset( $instance[ 'collapsed' ] ) ? $instance['collapsed'] : '';

            $product_attributes = wc_get_attribute_taxonomies();

            $display_type_options = array(
                array( 
                    'label' => esc_html__( 'List', 'agni-cartify' ),
                    'value' => 'list'
                ),
                array( 
                    'label' => esc_html__( 'Select', 'agni-cartify' ),
                    'value' => 'select'
                ),
                array( 
                    'label' => esc_html__( 'Color', 'agni-cartify' ),
                    'value' => 'color'
                ),
                array( 
                    'label' => esc_html__( 'Label', 'agni-cartify' ),
                    'value' => 'label'
                ),
                array( 
                    'label' => esc_html__( 'Image', 'agni-cartify' ),
                    'value' => 'image'
                ),
            );

            // $attributes_list = [];
            // foreach( $product_attributes as $attribute ){
            //     $attributes_list[$attribute->attribute_name] = $attribute->attribute_label;
            // }


            // print_r( $attributes_list );

            $query_types = array(
                array(
                    'label' => 'AND',
                    'value' => 'and',
                ),
                array(
                    'label' => 'OR',
                    'value' => 'or',
                ),
            );




            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title', 'agni-cartify' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'attribute' ); ?>"><?php esc_html_e( 'Attribute', 'agni-cartify' ); ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'attribute' ); ?>" name="<?php echo $this->get_field_name( 'attribute' ); ?>">
                <?php foreach( $product_attributes as $attribute ){ ?>
                    <option value="<?php echo esc_attr( $attribute->attribute_name ); ?>" <?php selected( $attribute_choice, $attribute->attribute_name ); ?>><?php echo esc_html( $attribute->attribute_label ); ?></option>
                <?php } ?>
                </select>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'query_type' ); ?>"><?php esc_html_e( 'Query Type', 'agni-cartify' ); ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'query_type' ); ?>" name="<?php echo $this->get_field_name( 'query_type' ); ?>">
                <?php foreach( $query_types as $query_type ){ 
                    $query_type = (object) $query_type; ?>
                    <option value="<?php echo esc_attr( $query_type->value ); ?>" <?php selected( $query_choice, $query_type->value ); ?>><?php echo esc_html( $query_type->label ); ?></option>
                <?php } ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php esc_html_e( 'Display Limit', 'agni-cartify' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="number" value="<?php echo esc_attr( $attribute_limit ); ?>" />
            </p>
            <!-- <p>
                <label for="<?php echo $this->get_field_id( 'display_type' ); ?>"><?php esc_html_e( 'Display Type', 'agni-cartify' ); ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'display_type' ); ?>" name="<?php echo $this->get_field_name( 'display_type' ); ?>">
                <?php foreach( $display_type_options as $option ){ ?>
                    <option value="<?php echo esc_attr( $option['value'] ); ?>" <?php selected( $display_type, $option['value'] ); ?>><?php echo esc_html( $option['label'] ); ?></option>
                <?php } ?>
                </select>
            </p> -->
            <p>
				<input id="<?php echo esc_attr( $this->get_field_id('collapsed') ); ?>" name="<?php echo esc_attr( $this->get_field_name('collapsed') ); ?>" type="checkbox" value="1" <?php checked( $collapsed, 1 ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id('collapsed') ); ?>"><?php echo esc_html__( 'Collapsed by Default', 'agni-cartify' ); ?></label>
			</p>
            <?php

            
        }

        public function update( $new_instance, $old_instance ){

            $instance = $old_instance;

            $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
            $instance['attribute'] = ( !empty( $new_instance['attribute'] ) ) ? strip_tags( $new_instance['attribute'] ) : '';
            $instance['limit'] = ( !empty( $new_instance['limit'] ) ) ? strip_tags( $new_instance['limit'] ) : '';
            // $instance['display_type'] = ( !empty( $new_instance['display_type'] ) ) ? strip_tags( $new_instance['display_type'] ) : '';
            $instance[ 'query_type' ] = ( !empty( $new_instance['query_type'] ) ) ? strip_tags( $new_instance['query_type'] ) : '';
            $instance['collapsed'] = ( !empty( $new_instance['collapsed'] ) ) ? strip_tags( $new_instance['collapsed'] ) : '';

            // print_r( $instance );

            return $instance;
        }


    }
}

$cartify_ajax_product_attribute_filter = new CartifyAjaxProductAttributeFilter();

// if( !function_exists( 'cartify_register_ajax_product_filter' ) ){
//     function cartify_register_ajax_product_filter(){
//         register_widget( 'CartifyAjaxProductAttributeFilter' );
//     }
// }

// add_action( 'widget_init', 'cartify_register_ajax_product_filter' );