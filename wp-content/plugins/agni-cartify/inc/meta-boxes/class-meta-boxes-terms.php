<?php 

class AgniTermMetaBoxes{

    public $headers_list;

    public $sliders_list;

    public $blocks_list;

    public $term_slugs;

    public function __construct(){

        $this->init();

        foreach ($this->term_slugs as $key => $term_slug) {
            
            add_action("{$term_slug}_add_form_fields", array( $this, 'add_meta_field' ), 999, 1);
            add_action("{$term_slug}_edit_form_fields", array( $this, 'edit_meta_field' ), 999, 1);

            add_action("edited_{$term_slug}", array( $this, 'save_meta_field' ), 99, 1);
            add_action("create_{$term_slug}", array( $this, 'save_meta_field' ), 99, 1);

            register_term_meta("{$term_slug}", 'agni_term_header_id', array( 'single' => true, 'show_in_rest' => true ));
            register_term_meta("{$term_slug}", 'agni_slider_id', array( 'single' => true, 'show_in_rest' => true ));
            register_term_meta("{$term_slug}", 'agni_term_footer_block_id', array( 'single' => true, 'show_in_rest' => true ));

            // add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );

            
        }

    }
    
    public function init(){

        $this->headers_list = AgniCustomMetaboxes::prepare_headers_list();
        $this->sliders_list = AgniCustomMetaboxes::prepare_sliders_list();
        $this->blocks_list = Agni_Cartify_Helper::get_posttype_posts_list( array( 'post_type' => 'agni_block' ), true );

        $this->term_slugs = array( 'product_cat', 'product_tag', 'product_brand', 'category', 'post_tag', 'portfolio_category' );

    }
    public function add_meta_field() {

        // Add nonce for security and authentication.
        wp_nonce_field( 'agni_meta_box_nonce_action', 'security' );

        ?>   
        <div class="form-field">
            <label for="agni_term_header_id_field"><?php echo esc_html__( 'Header Choice', 'agni-cartify' ); ?></label>
            <select name="agni_term_header_id" id="agni_term_header_id_field">
                <?php foreach ($this->headers_list as $key => $value) { ?>
                    <option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-field">
            <label for="agni_term_slider_id_field"><?php echo esc_html__( 'Slider Choice', 'agni-cartify' ); ?></label>
            <select name="agni_term_slider_id" id="agni_term_slider_id_field">
                <?php foreach ($this->sliders_list as $key => $value) { ?>
                    <option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-field">
            <label for="agni_term_footer_block_id_field"><?php echo esc_html__( 'Footer Block Choice', 'agni-cartify' ); ?></label>
            <select name="agni_term_footer_block_id" id="agni_term_footer_block_id_field">
                <?php foreach ($this->blocks_list as $key => $value) { ?>
                    <option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
    }

    public function edit_meta_field( $term ) {

        // Add nonce for security and authentication.
        wp_nonce_field( 'agni_meta_box_nonce_action', 'security' );

        //getting term ID
        $term_id = $term->term_id;

        // retrieve the existing value(s) for this meta field.
        $agni_term_header_id = get_term_meta($term_id, 'agni_term_header_id', true);
        $agni_term_slider_id = get_term_meta($term_id, 'agni_slider_id', true);
        $agni_term_footer_block_id = get_term_meta($term_id, 'agni_term_footer_block_id', true);

        ?>
        <tr class="form-field">
            <th scope="row" valign="top"><label for="agni_term_header_id_field"><?php echo esc_html__( 'Header Choice', 'agni-cartify' ); ?></label></th>
            <td>
                <select name="agni_term_header_id" id="agni_term_header_id_field">
                    <?php foreach ($this->headers_list as $key => $value) { ?>
                        <option value="<?php echo esc_attr( $key ); ?>" <?php echo selected( $agni_term_header_id, $key, true ); ?>><?php echo esc_html( $value ); ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><label for="agni_term_slider_id_field"><?php echo esc_html__( 'Slider Choice', 'agni-cartify' ); ?></label></th>
            <td>
                <select name="agni_term_slider_id" id="agni_term_slider_id_field">
                    <?php foreach ($this->sliders_list as $key => $value) { ?>
                        <option value="<?php echo esc_attr( $key ); ?>" <?php echo selected( $agni_term_slider_id, $key, true ); ?>><?php echo esc_html( $value ); ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><label for="agni_term_footer_block_id_field"><?php echo esc_html__( 'Footer Block Choice', 'agni-cartify' ); ?></label></th>
            <td>
                <select name="agni_term_footer_block_id" id="agni_term_footer_block_id_field">
                    <?php foreach ($this->blocks_list as $key => $value) { ?>
                        <option value="<?php echo esc_attr( $key ); ?>" <?php echo selected( $agni_term_footer_block_id, $key, true ); ?>><?php echo esc_html( $value ); ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <?php
    }


    public function save_meta_field($term_id) {

        // Add nonce for security and authentication.
        $nonce_name   = isset( $_POST['security'] ) ? $_POST['security'] : '';
        $nonce_action = 'agni_meta_box_nonce_action';

        // Check if nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }

        // Check if user has permissions to save data.
        if ( ! current_user_can( 'edit_posts' ) ) {
            return;
        }
        $agni_term_header_id = sanitize_text_field( $_POST['agni_term_header_id'] );
        $agni_term_slider_id = sanitize_text_field( $_POST['agni_term_slider_id'] );
        $agni_term_footer_block_id = sanitize_text_field( $_POST['agni_term_footer_block_id'] );

        update_term_meta($term_id, 'agni_term_header_id', $agni_term_header_id);
        update_term_meta($term_id, 'agni_slider_id', $agni_term_slider_id);
        update_term_meta($term_id, 'agni_term_footer_block_id', $agni_term_footer_block_id);
    }
}

$agni_term_meta_boxes = new AgniTermMetaBoxes();