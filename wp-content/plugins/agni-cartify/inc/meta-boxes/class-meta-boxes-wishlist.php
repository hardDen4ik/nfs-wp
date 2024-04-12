<?php
/**
 * Register a meta box using a class.
 */
class Agni_Wishlist_MetaBox {
 
    /**
     * Constructor.
     */
    public function __construct() {
        if ( is_admin() ) {
            add_action( 'load-post.php', array( $this, 'init_metabox' ) );
            add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
        }

        // register_post_meta('agni_wc_wishlist', 'agni_wishlist_product_ids', [
        //     'type'        => 'string',
        //     'single'    => true,
        //     'default'      => '',
        //     'show_in_rest' => true
        // ]);
 
    }
 
    /**
     * Meta box initialization.
     */
    public function init_metabox() {
        add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
        add_action( 'save_post', array( $this, 'save_metabox' ), 10, 2 );
    }
 
    /**
     * Adds the meta box.
     */
    public function add_metabox() {
        add_meta_box(
            'agni-wishlist-options',
            esc_html__( 'Wishlist Options', 'agni-cartify' ),
            array( $this, 'render_metabox' ),
            'agni_wc_wishlist',
            'advanced',
            'default'
        );

        
 
    }
 
    /**
     * Renders the meta box.
     */
    public function render_metabox( $post ) {
        // Add nonce for security and authentication.
        wp_nonce_field( 'agni_meta_box_nonce_action', 'security' );

        // Use get_post_meta to retrieve an existing value from the database.
        $value = get_post_meta( $post->ID, 'agni_wishlist_product_ids', true );
 
        // Display the form, using the current value.
        ?>
        <label for="agni_wishlist_product_ids_field">
            <?php esc_html_e( 'Product IDs', 'agni-cartify' ); ?>
        </label>
        <input type="text" id="agni_wishlist_product_ids_field" name="agni_wishlist_product_ids_field" value="<?php echo esc_attr( $value ); ?>" size="25" />

        <?php
    }
 
    /**
     * Handles saving the meta box.
     *
     * @param int     $post_id Post ID.
     * @param WP_Post $post    Post object.
     * @return null
     */
    public function save_metabox( $post_id, $post ) {
        // Add nonce for security and authentication.
        $nonce_name   = isset( $_POST['security'] ) ? $_POST['security'] : '';
        $nonce_action = 'agni_meta_box_nonce_action';
 
        // Check if nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }
 
        // Check if user has permissions to save data.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
 
        // Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }
 
        // Check if not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }


        /* OK, it's safe for us to save the data now. */
 
        // Sanitize the user input.
        $mydata = sanitize_text_field( $_POST['agni_wishlist_product_ids_field'] );
 
        // Update the meta field.
        update_post_meta( $post_id, 'agni_wishlist_product_ids', $mydata );
    }
}
 
$agni_wishlist_metabox = new Agni_Wishlist_MetaBox();

