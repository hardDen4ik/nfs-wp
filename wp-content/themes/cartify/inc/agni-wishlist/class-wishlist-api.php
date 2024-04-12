<?php

class Agni_Wishlist_REST_API{

    public function __construct(){

        add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );

        add_filter( 'agni_wishlist_update_wishlist_response', array( $this, 'update_wishlist_response' ) );

        add_filter( 'rest_authentication_errors', function( $result ) {
            global $wp;

            // No authentication has been performed yet.
            // Return an error if user is not logged in and not trying to login.
            if ( ! is_user_logged_in() && $wp->request === 'wp-json/wp/v2/agni_wc_wishlist' ) {
                return new WP_Error(
                    'rest_forbidden',
                    esc_html__( 'Sorry, you are not allowed to do that.', 'cartify' ),
                    array( 'status' => 401 )
                );
            }

            return $result;
        });
    }

    public function register_rest_routes(){

        if( !class_exists('WooCommerce') ){
            return;
        }

        $current_user_can = current_user_can( 'read' );

        register_rest_route( 'agni-wishlist/v1', 'wishlist', array(
            'methods' => WP_REST_Server::EDITABLE,
            'callback' => array( $this, 'create_wishlist' ),
            // 'permission_callback' => '__return_true'
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
        ));

        register_rest_route( 'agni-wishlist/v1', 'wishlist/(?P<id>\d+)', array(
            'methods' => WP_REST_Server::EDITABLE,
            'callback' => array( $this, 'update_wishlist' ),
            // 'permission_callback' => '__return_true'
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
        ));

        register_rest_route( 'agni-wishlist/v1', 'wishlist/(?P<id>\d+)', array(
            'methods' => WP_REST_Server::DELETABLE,
            'callback' => array( $this, 'delete_wishlist' ),
            // 'permission_callback' => '__return_true'
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
        ));

    }


    public function create_wishlist( WP_REST_Request $request ){

        $args = array(
            'post_type'     => 'agni_wc_wishlist',
            'post_status'   => 'publish',
            'post_author'   => $request['user_id'],
            'post_title'    => $request['wishlist_name'],
        );

        if( isset($request['product_id']) && !empty($request['product_id']) ){

            $product_id = $request['product_id'];
            $product_id .= isset($request['variation_id']) ? ':' . $request['variation_id'] : '';

            $args['meta_input'] = array(
                'agni_wishlist_product_ids'    => $product_id
            );

        }

            $notice = esc_html__( 'Wishlist created.', 'cartify' );

        $wishlist_id = wp_insert_post( $args );

        $redirect_url = add_query_arg(array(
            'wishlist_id' => $wishlist_id
        ), esc_url( wc_get_account_endpoint_url('wishlist') ));

        $data = array(
            'notice' => $notice,
            'redirect_url' => $redirect_url,
            'redirect_text' => esc_html__( 'Go to wishlist.', 'cartify' )
        );

        ob_start();

        echo apply_filters( 'agni_wishlist_update_wishlist_response', $data );

        $response = ob_get_clean();
        return wp_send_json( $response );
    }

    public function update_wishlist( WP_REST_Request $request ){

        $args = array(
            'ID'           => $request['id'],
        );

        if( isset( $request['wishlist_name'] ) && !empty( $request['wishlist_name'] ) ){
            $args['post_title'] = $request['wishlist_name'];

            $notice = esc_html__( 'Changes saved.', 'cartify' );
        }

        if( isset( $request['product_id'] ) && !empty( $request['product_id'] ) ){
            $product_id = $request['product_id'];
            $product_id .= isset($request['variation_id']) ? ':' . $request['variation_id'] : ''; 

            $existing_product_ids = esc_attr( get_post_meta( $request['id'], 'agni_wishlist_product_ids', true ) );
            $existing_product_ids_array = explode( '|', $existing_product_ids );

            if( $request['remove_from_wishlist'] ){
                $product_key = array_search($product_id, $existing_product_ids_array);
                array_splice( $existing_product_ids_array, $product_key, 1 );

                $notice = esc_html__( 'Product removed from list.', 'cartify' );
            }
            else if( in_array($product_id, $existing_product_ids_array) ){
                $notice = esc_html__( 'Already in list.', 'cartify' );
            }
            else{
                array_push( $existing_product_ids_array, $product_id );

                $notice = esc_html__( 'Product added to list.', 'cartify' );
            }

            $args['meta_input'] = array(
                'agni_wishlist_product_ids'    => implode( '|', $existing_product_ids_array )
            );

        }

        // Update the post into the database
        wp_update_post( $args );

        $redirect_url = add_query_arg(array(
            'wishlist_id' => $request['id']
        ), esc_url( wc_get_account_endpoint_url('wishlist') ));

        $data = array(
            'notice' => $notice
        );

        if( !$request['remove_from_wishlist'] ){
            $data['redirect_url'] = $redirect_url;
            $data['redirect_text'] = esc_html__( 'Go to wishlist.', 'cartify' );
        }

        ob_start();

        echo apply_filters( 'agni_wishlist_update_wishlist_response', $data );

        $response = ob_get_clean();
        return wp_send_json( $response );
        // return $response;
    }

    public function delete_wishlist( WP_REST_Request $request ){

        $notice = esc_html__( 'Wishlist deleted.', 'cartify' );
        wp_delete_post( $request['id'] );

        $data = array(
            'notice' => $notice,
            'redirect_url' => esc_url( wc_get_account_endpoint_url('wishlist') )
        );

        // $url = wc_get_account_endpoint_url('wishlist');

        return wp_send_json( $data );
    }


    public function update_wishlist_response($response){

        ?>
        <div class="agni-add-to-wishlist-response">
            <span class="agni-add-to-wishlist-response__text"><?php echo esc_html( $response['notice'] ); ?></span>
            <?php if( $response['redirect_text'] ){ ?>
                <a href="<?php echo esc_url( $response['redirect_url'] ); ?>" class="agni-add-to-wishlist-response__redirect-link"><?php echo esc_html( $response['redirect_text'] ); ?></a>
            <?php } ?>
        </div>
        <?php

            }

}

$wishlist_rest_api = new Agni_Wishlist_REST_API();

?>