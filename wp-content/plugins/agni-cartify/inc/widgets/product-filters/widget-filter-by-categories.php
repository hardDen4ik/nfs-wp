<?php 
/**
 * Agni Ajax filter by category.
 */

if( !class_exists( 'CartifyAjaxProductCategoryFilter' ) ){
    class CartifyAjaxProductCategoryFilter extends WP_Widget{

        /*

        - option to display category without checkbox
        option to step by step category filter 
        option to choose or / and category
        x option to hide all sub category
        show more/less on category

        */

        public $chosen_categories = array();

        public function __construct(){

            parent::__construct(
                'cartify_ajax_product_category_filter',
                esc_html__( 'Cartify: Filter Products by Categories', 'agni-cartify' ),
                array(
                    'classname'   => 'widget_cartify_ajax_product_category_filter cartify_ajax_product_widget',
                    'description' => esc_html__( 'Ajax filter woocommerce products by categories.', 'agni-cartify' )
                )
            );	

            add_action( 'widgets_init', function() {
                register_widget( 'CartifyAjaxProductCategoryFilter' );
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



            $orderby_choice = isset( $instance[ 'orderby' ] ) ? $instance['orderby'] : 'name';
            $count = isset( $instance[ 'count' ] ) ? $instance['count'] : 1;
            $hierarchical = isset( $instance[ 'hierarchical' ] ) ? $instance['hierarchical'] : 1; // 1 or 0
            $hide_empty = isset( $instance[ 'hide_empty' ] ) ? $instance['hide_empty'] : '';
            $subcategory_dropdown = isset( $instance[ 'subcategory_dropdown' ] ) ? $instance['subcategory_dropdown'] : '';
            $show_children = isset( $instance[ 'show_children' ] ) ? $instance['show_children'] : ''; // only sub category of 
            $category_limit = isset( $instance[ 'limit' ] ) ? $instance['limit'] : '';
            $max_depth = !empty( $instance[ 'depth' ] ) ? $instance[ 'depth' ] : '5';
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


            
            $taxonomy     = 'product_cat';
            $orderby      = $orderby_choice;  //term_order, count, term_id
            $show_count   = $count;      // 1 for yes, 0 for no
            $pad_count    = 0;      // 1 for yes, 0 for no
            $hierarchical = $hierarchical;      // 1 for yes, 0 for no  
            $title        = '';  
            $empty        = $hide_empty;

            $args = array(
                'taxonomy'     => $taxonomy,
                // 'child_of'     => 0,
                // 'parent'       => 0,
                'orderby'      => $orderby,
                'show_count'   => $show_count,
                'pad_count'    => $pad_count,
                'hierarchical' => $hierarchical,
                'title_li'     => $title,
                'hide_empty'   => $empty,
                'show_children'   => $show_children
            );

            if( $hierarchical == true ){
                $args['child_of'] = 0;
                $args['parent'] = 0;
            }

            
            ?>
            <div class="agni-product-categories-container">
            <?php 
            $term_id = $term_has_children = '';

            if( get_query_var( 'term' ) ){
                $term = get_queried_object();

                if($term->taxonomy == 'product_cat'){
                    // print_r( $term );
                    
                    $term_id = $term->term_id;

                    ?>
                    <?php
                    if( !empty( get_term_children( $term_id, $term->taxonomy ) ) ){
                        $term_has_children = '1';
                        ?>
                        <span class="agni-product-categories-taxonomy-toggle">
                            <span><?php echo esc_html__( 'Show All Categories', 'agni-cartify' ); ?></span>
                        </span>
                        <?php
                    }
                    ?>
                    <div class="agni-product-categories-taxonomy" data-limit="<?php echo esc_attr( $category_limit ); ?>">
                        
                        <?php $this->agni_get_product_categories_loop( $args, 0, $max_depth ); ?>
                        <?php if( !empty( $category_limit ) && $category_limit != 0 ){ 
                            
                            // if( empty($term_id) || $term_has_children ){
                                ?>
                                <div class="list-toggle">
                                    <span class=""><?php echo esc_html__( 'Show More', 'agni-cartify' ); ?></span>
                                    <span><?php echo esc_html__( 'Show Less', 'agni-cartify' ); ?></span>
                                </div>
                            <?php // } 
                        } ?>
                        <?php
                        ?>
                    </div>
                    <?php

                    $args['parent'] = $term_id;
                }
            }

            // $args['child_of'] = $term_id;

            // print_r( $args );
            // $all_categories = get_terms( $args );
            // print_r( $all_categories );

            ?>
            
            <?php 
            

            $product_categories_classes = array(
                'agni-product-categories',
                ($subcategory_dropdown == 1) ? 'subcategory-dropdown' : '',
                ($show_children == 1) ? 'show-children-only' : ''
            );
            
            
            if( !empty( $category_limit ) && $category_limit != 0 ){
                ?>
                <div class="<?php echo esc_attr( Agni_Cartify_Helper::prepare_classes($product_categories_classes) ); ?>" data-limit="<?php echo esc_attr( $category_limit ); ?>">
                <?php
            }
            else {
                ?>
                <div class="<?php echo esc_attr( Agni_Cartify_Helper::prepare_classes($product_categories_classes) ); ?>">
                <?php
            } ?>
            <?php
            /*if( $show_children == true ){
                ?>
                <a href="#" class="agni-product-categories__back-link"><?php echo esc_html__( 'All Categories', 'agni-cartify' ); ?></a>
                <?php
            }*/

            $this->agni_get_product_categories_loop( $args, 0, $max_depth );
            ?>
            <?php if( !empty( $category_limit ) && $category_limit != 0 ){ 
                
                if( empty($term_id) || $term_has_children ){
                    ?>
                    <div class="list-toggle">
                        <span class=""><?php echo esc_html__( 'Show More', 'agni-cartify' ); ?></span>
                        <span><?php echo esc_html__( 'Show Less', 'agni-cartify' ); ?></span>
                    </div>
                <?php } 
            } ?>
            <?php
            ?>
            </div>
            </div>
            <?php echo wp_kses_post( $after_widget );

        }

        public function agni_get_product_categories_loop( $args, $depth, $max_depth){
            // print_r( $args );
            // echo $depth;
            // $all_categories = get_categories( $args );
            $all_categories = get_terms( $args );

            // print_r($all_categories);
            
            if( $depth < $max_depth && $all_categories ){

                // if( $depth == 0){
                    ?>
                    <ul>
                    <?php
                    $depth++;
                // }

                foreach ($all_categories as $cat) {
                    $category_id = $cat->term_id;   

                    $args['child_of'] = $cat->category_parent;
                    $args['parent'] = $category_id;

                    // echo '<pre>'; 
                    // print_r( $cat );
                    // echo '</pre>';

                    $cat_item_classes = array(
                        "agni-product-categories__cat-item",
                        "cat-item-" . $category_id,
                        !empty( get_term_children( $category_id, $cat->taxonomy ) ) ? 'cat-parent' : '',
                        in_array( $category_id, $this->chosen_categories ) ? 'active' : '',
                    )
                    
                    ?>
                    <li class="<?php echo esc_attr( Agni_Cartify_Helper::prepare_classes( $cat_item_classes ) ); ?>">
                        <?php if( $args['show_children'] == true ){ 
                            $term = get_term_by( 'id', $cat->category_parent, 'product_cat' ); 
                            if( $term ){ 
                                $term_name = esc_html( $term->name ); 
                            }else{ 
                                $term_name = esc_html__( 'All Categories', 'agni-cartify' ); 
                            }
                            ?>
                            <span class="agni-product-categories__cat-back-link cat-back-link" data-cat-id="<?php echo esc_attr( $cat->category_parent ); ?>"><i class="lni lni-arrow-left"></i><?php echo sprintf( __( 'Back to %s', 'agni-cartify' ), esc_html( $term_name ) ); ?></span>
                        <?php } ?>
                        <a href="<?php echo esc_url( get_term_link($cat->slug, 'product_cat') ); ?>" data-cat-id="<?php echo esc_attr( $category_id ); ?>"><?php echo esc_html( $cat->name ); ?>
                        <?php if( $args['show_count'] == true ){ ?>
                            <span>(<?php echo esc_html( $this->get_products_count($cat, $args['hierarchical']) ); ?>)</span>
                        <?php } ?></a>
                        <?php if( !empty( get_term_children( $category_id, $cat->taxonomy ) ) ) { ?>
                            <span class="cat-toggle"></span>
                        <?php } ?>
                        <?php if( $args['hierarchical'] == true ){
                            $this->agni_get_product_categories_loop( $args, $depth, $max_depth ); 
                            } ?>
                    </li>
                    <?php
                }
                // if( $depth == 0 ){
                    ?>
                    </ul>
                    <?php
                // }

            }

        }

        public function get_products_count($cat, $hierarchical){
            if( $hierarchical == true ){
                $category_id = $cat->term_id;
                $query = new WP_Query( array(
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'id',
                            'terms' => $category_id, // Replace with the parent category ID
                            'include_children' => true,
                        ),
                        array(
                            'taxonomy'  => 'product_visibility',
                            'terms'     => array( 'exclude-from-catalog' ),
                            'field'     => 'name',
                            'operator'  => 'NOT IN',
                        ),
                    ),
                    'nopaging' => true,
                    'fields' => 'ids',
                ) );

                $count = $query->post_count;
            }
            else{
                $count = $cat->category_count;
            }

            return $count;
        }

        public function get_filter_params(){
            if(isset( $_REQUEST[ 'filter_product_cat' ] )){
                $this->chosen_categories = explode( ',',$_REQUEST[ 'filter_product_cat' ] );
            }
            
        }

        public function form( $instance ){

            if ( isset( $instance[ 'title' ] ) ) {
                $title = $instance[ 'title' ];
            }
            else {
                $title = esc_html__( 'Filter by Categories', 'agni-cartify' );
            }

            $orderby_choice = isset( $instance[ 'orderby' ] ) ? $instance['orderby'] : 'name';
            $count = isset( $instance[ 'count' ] ) ? $instance['count'] : 1;
            $hierarchical = isset( $instance[ 'hierarchical' ] ) ? $instance['hierarchical'] : 1;
            $hide_empty = isset( $instance[ 'hide_empty' ] ) ? $instance['hide_empty'] : '';
            $show_children = isset( $instance[ 'show_children' ] ) ? $instance['show_children'] : '';
            $category_limit = isset( $instance[ 'limit' ] ) ? $instance['limit'] : '';
            $subcategory_dropdown = isset( $instance[ 'subcategory_dropdown' ] ) ? $instance[ 'subcategory_dropdown' ] : '';
            $depth = isset( $instance[ 'depth' ] ) ? $instance[ 'depth' ] : '';
            $collapsed = isset( $instance[ 'collapsed' ] ) ? $instance['collapsed'] : '';

            $orderby_options = array(
                array( 
                    'label' => esc_html__( 'Category Order', 'agni-cartify' ),
                    'value' => 'term_order'
                ),
                array( 
                    'label' => esc_html__( 'Name', 'agni-cartify' ),
                    'value' => 'name'
                ),
            );


            ?>

            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title', 'agni-cartify' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php esc_html_e( 'Order by', 'agni-cartify' ); ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
                <?php foreach( $orderby_options as $option ){ ?>
                    <option value="<?php echo esc_attr( $option['value'] ); ?>" <?php selected( $orderby_choice, $option['value'] ); ?>><?php echo esc_html( $option['label'] ); ?></option>
                <?php } ?>
                </select>
            </p>
            <p>
				<input id="<?php echo esc_attr( $this->get_field_id('count') ); ?>" name="<?php echo esc_attr( $this->get_field_name('count') ); ?>" type="checkbox" value="1" <?php checked( $count, 1 ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id('count') ); ?>"><?php echo esc_html__( 'Show product counts', 'agni-cartify' ); ?></label>
			</p>
            <p>
				<input id="<?php echo esc_attr( $this->get_field_id('hierarchical') ); ?>" name="<?php echo esc_attr( $this->get_field_name('hierarchical') ); ?>" type="checkbox" value="1" <?php checked( $hierarchical, 1 ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id('hierarchical') ); ?>"><?php echo esc_html__( 'Show hierarchy', 'agni-cartify' ); ?></label>
			</p>
            <p>
				<input id="<?php echo esc_attr( $this->get_field_id('hide_empty') ); ?>" name="<?php echo esc_attr( $this->get_field_name('hide_empty') ); ?>" type="checkbox" value="1" <?php checked( $hide_empty, 1 ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id('hide_empty') ); ?>"><?php echo esc_html__( 'Hide empty categories', 'agni-cartify' ); ?></label>
			</p>
            <p>
				<input id="<?php echo esc_attr( $this->get_field_id('subcategory_dropdown') ); ?>" name="<?php echo esc_attr( $this->get_field_name('subcategory_dropdown') ); ?>" type="checkbox" value="1" <?php checked( $subcategory_dropdown, 1 ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id('subcategory_dropdown') ); ?>"><?php echo esc_html__( 'Show subcategories as a dropdown', 'agni-cartify' ); ?></label>
			</p>
            <p>
				<input id="<?php echo esc_attr( $this->get_field_id('show_children') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_children') ); ?>" type="checkbox" value="1" <?php checked( $show_children, 1 ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id('show_children') ); ?>"><?php echo esc_html__( 'Only show children of the current category', 'agni-cartify' ); ?></label>
			</p>
            <p>
                <label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php esc_html_e( 'Display Limit', 'agni-cartify' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="number" value="<?php echo esc_attr( $category_limit ); ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'depth' ); ?>"><?php esc_html_e( 'Maximum depth', 'agni-cartify' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'depth' ); ?>" name="<?php echo $this->get_field_name( 'depth' ); ?>" type="number" value="<?php echo esc_attr( $depth ); ?>" />
            </p>
            <p>
				<input id="<?php echo esc_attr( $this->get_field_id('collapsed') ); ?>" name="<?php echo esc_attr( $this->get_field_name('collapsed') ); ?>" type="checkbox" value="1" <?php checked( $collapsed, 1 ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id('collapsed') ); ?>"><?php echo esc_html__( 'Collapsed by Default', 'agni-cartify' ); ?></label>
			</p>
            <?php

        }

        public function update( $new_instance, $old_instance ){
            // $instance = $old_instance;

            $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
            $instance['orderby'] = ( !empty( $new_instance['orderby'] ) ) ? strip_tags( $new_instance['orderby'] ) : '';
            $instance['count'] = ( !empty( $new_instance['count'] ) ) ? strip_tags( $new_instance['count'] ) : '';
            $instance['hierarchical'] = ( !empty( $new_instance['hierarchical'] ) ) ? strip_tags( $new_instance['hierarchical'] ) : '';
            $instance['hide_empty'] = ( !empty( $new_instance['hide_empty'] ) ) ? strip_tags( $new_instance['hide_empty'] ) : '';
            $instance['subcategory_dropdown'] = ( !empty( $new_instance['subcategory_dropdown'] ) ) ? strip_tags( $new_instance['subcategory_dropdown'] ) : '';
            $instance['show_children'] = ( !empty( $new_instance['show_children'] ) ) ? strip_tags( $new_instance['show_children'] ) : '';
            $instance['limit'] = ( !empty( $new_instance['limit'] ) ) ? strip_tags( $new_instance['limit'] ) : '';
            $instance['depth'] = ( !empty( $new_instance['depth'] ) ) ? strip_tags( $new_instance['depth'] ) : '';
            $instance['collapsed'] = ( !empty( $new_instance['collapsed'] ) ) ? strip_tags( $new_instance['collapsed'] ) : '';

            // print_r( $instance );

            return $instance;
        }


    }
}

$cartify_ajax_product_categories_filter = new CartifyAjaxProductCategoryFilter();

// if( !function_exists( 'cartify_register_ajax_product_filter' ) ){
//     function cartify_register_ajax_product_filter(){
//         register_widget( 'CartifyAjaxProductCategoryFilter' );
//     }
// }

// add_action( 'widget_init', 'cartify_register_ajax_product_filter' );