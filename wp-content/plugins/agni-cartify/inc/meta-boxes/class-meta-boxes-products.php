<?php 

class AgniProductsMetaBoxes{

    public $headers_list;

    public $sliders_list;

    public $blocks_list;

    public function __construct(){

        $this->headers_list = AgniCustomMetaboxes::prepare_headers_list();
        $this->sliders_list = AgniCustomMetaboxes::prepare_sliders_list();
        $this->blocks_list = Agni_Cartify_Helper::get_posttype_posts_list( array( 'post_type' => 'agni_block' ), true );

        add_action( 'add_meta_boxes', array($this, 'register_meta_box') );
        add_action( 'save_post', array($this, 'save') );
    }

    public function register_meta_box(){

        add_meta_box(
            'agni-page-meta-options-panel',
            esc_html__( 'Cartify Page Options', 'agni-cartify' ),
            array( $this, 'render_meta_box_content' ),
            array( 'product' ),
            'advanced',
            'high',
            array(
                '__back_compat_meta_box' => true
            )
        );
    }

    
    public function render_meta_box_content( $post ){
        // header
        // slider
        // footer
        // (control on layout builder) - sidebar
        // (control on layout builder)  - background color or gradient

        // Add nonce for security and authentication.
        wp_nonce_field( 'agni_meta_box_nonce_action', 'security' );


        // retrieve the existing value(s) for this meta field.
        $agni_product_header_id = get_post_meta($post->ID, 'agni_product_header_id', true);
        $agni_product_slider_id = get_post_meta($post->ID, 'agni_product_slider_id', true);
        $agni_product_footer_block_id = get_post_meta($post->ID, 'agni_product_footer_block_id', true);

        ?>
        <div class="form-field">
            <label for="agni_product_header_id_field"><?php echo esc_html__( 'Header Choice', 'agni-cartify' ); ?></label>
            <select name="agni_product_header_id" id="agni_product_header_id_field">
                <?php foreach ($this->headers_list as $key => $value) { ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php echo selected( $agni_product_header_id, $key, true ); ?>><?php echo esc_html( $value ); ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-field">
            <label for="agni_product_slider_id_field"><?php echo esc_html__( 'Slider Choice', 'agni-cartify' ); ?></label>
            <select name="agni_product_slider_id" id="agni_product_slider_id_field">
                <?php foreach ($this->sliders_list as $key => $value) { ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php echo selected( $agni_product_slider_id, $key, true ); ?>><?php echo esc_html( $value ); ?></option>
                <?php } ?>
            </select>
        </div><div class="form-field">
            <label for="agni_product_footer_block_id_field"><?php echo esc_html__( 'Footer Block Choice', 'agni-cartify' ); ?></label>
            <select name="agni_product_footer_block_id" id="agni_product_footer_block_id_field">
                <?php foreach ($this->blocks_list as $key => $value) { ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php echo selected( $agni_product_footer_block_id, $key, true ); ?>><?php echo esc_html( $value ); ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
    }

    public function save( $post_id ) {
 
        // Add nonce for security and authentication.
        $nonce_name   = isset( $_POST['security'] ) ? $_POST['security'] : '';
        $nonce_action = 'agni_meta_box_nonce_action';

        // Check if nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }
  
        // Check the user's permissions.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }
 
        /* OK, it's safe for us to save the data now. */
 
        $agni_product_header_id = sanitize_text_field( $_POST['agni_product_header_id'] );
        $agni_product_slider_id = sanitize_text_field( $_POST['agni_product_slider_id'] );
        $agni_product_footer_block_id = sanitize_text_field( $_POST['agni_product_footer_block_id'] );

        update_post_meta($post_id, 'agni_product_header_id', $agni_product_header_id);
        update_post_meta($post_id, 'agni_product_slider_id', $agni_product_slider_id);
        update_post_meta($post_id, 'agni_product_footer_block_id', $agni_product_footer_block_id);
    }
}

$agni_products_meta_boxes = new AgniProductsMetaBoxes();
