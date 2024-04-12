<?php

/**
 * Register metabox for Product Tags
 */

class AgniProductTagsMetaBox{

    public $blocks_list = array();

    public function __construct(){

        add_action('product_tag_add_form_fields', array( $this, 'add_meta_field' ), 99, 1);
        add_action('product_tag_edit_form_fields', array( $this, 'edit_meta_field' ), 99, 1);

        add_action('edited_product_tag', array( $this, 'save_meta_field' ), 99, 1);
        add_action('create_product_tag', array( $this, 'save_meta_field' ), 99, 1);

        $this->prepare_content_blocks_list();

        register_term_meta('product_tag', 'agni_product_tag_banner_image_id', array( 'single' => true, 'show_in_rest' => true ));
        register_term_meta('product_tag', 'agni_product_tag_banner_content_bg', array( 'single' => true, 'show_in_rest' => true ));
        register_term_meta('product_tag', 'agni_product_tag_content_block', array( 'single' => true, 'show_in_rest' => true ));


    }

    public function prepare_content_blocks_list(){

        $this->blocks_list = Agni_Cartify_Helper::get_posttype_posts_list( array( 'post_type' => 'agni_block'), true );
    }

    public function meta_field_iconpicker(){

    }

    public function add_meta_field() {

        // Add nonce for security and authentication.
        wp_nonce_field( 'agni_meta_box_nonce_action', 'security' );

        ?>   
        <div class="form-field">
            <label for="agni_product_tag_banner_image_id"><?php echo esc_html__( 'Banner Image', 'agni-cartify' ); ?></label>
            <div id="agni_product_tag_banner_image">
            </div>
            <div style="display: flex; gap: 10px;">
                <input type="hidden" id="agni_product_tag_banner_image_id" name="agni_product_tag_banner_image_id" value="">
                <button type="button" class="agni_product_tag_banner_image_button button"><?php echo esc_html__( 'Upload/Add image', 'agni-cartify' ); ?></button>
                <button type="button" class="agni_product_tag_banner_image_remove_button button-link"><?php echo esc_html__( 'Remove image', 'agni-cartify' ); ?></button>
            </div>
            <p class="description"><?php echo esc_html__( 'The banner background image is how it display on your site.', 'agni-cartify' ); ?></p>
        </div>
        <tr class="form-field">
            <th scope="row" valign="top"><label for="agni_product_tag_banner_content_bg"><?php echo esc_html__( 'Banner Content BG', 'agni-cartify' ); ?></label></th>
            <td>
                <input type="text" name="agni_product_tag_banner_content_bg" id="banner-content-bg-color" class="agni-colorpicker" value="">
                <p class="description"><?php echo esc_html__( 'The banner background image is how it display on your site.', 'agni-cartify' ); ?></p>
            </td>
        </tr>
        <div class="form-field">
            <label for="agni_product_tag_content_block"><?php echo esc_html__( 'Content Block', 'agni-cartify' ); ?></label>
            <select name="agni_product_tag_content_block" id="agni_product_tag_content_block">
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
        $agni_product_tag_banner_image_id = (int)get_term_meta($term_id, 'agni_product_tag_banner_image_id', true);
        $agni_product_tag_banner_content_bg = get_term_meta($term_id, 'agni_product_tag_banner_content_bg', true);
        $agni_product_tag_content_block = get_term_meta($term_id, 'agni_product_tag_content_block', true);

        ?>
        <tr class="form-field">
            <th scope="row" valign="top"><label for="agni_product_tag_banner_image_id"><?php echo esc_html__( 'Banner Image', 'agni-cartify' ); ?></label></th>
            <td>
                <div id="agni_product_tag_banner_image">
                    <?php if( $agni_product_tag_banner_image_id ){ ?>
                        <img src="<?php echo esc_url( wp_get_attachment_image_src($agni_product_tag_banner_image_id)[0] ); ?>" />
                    <?php } ?>
                </div>
                <div style="display: flex; gap: 10px;">
                    <input type="hidden" id="agni_product_tag_banner_image_id" name="agni_product_tag_banner_image_id" value="<?php echo esc_attr($agni_product_tag_banner_image_id) ? esc_attr($agni_product_tag_banner_image_id) : ''; ?>">
                    <button type="button" class="agni_product_tag_banner_image_button button"><?php echo esc_html__( 'Upload/Add Banner Image', 'agni-cartify' ); ?></button>
                    <button type="button" class="agni_product_tag_banner_image_remove_button button-link"><?php echo esc_html__( 'Remove', 'agni-cartify' ); ?></button>
                </div>
                <p class="description"><?php echo esc_html__( 'The banner background image is how it display on your site.', 'agni-cartify' ); ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><label for="agni_product_tag_banner_content_bg"><?php echo esc_html__( 'Banner Content BG', 'agni-cartify' ); ?></label></th>
            <td>
                <input type="text" name="agni_product_tag_banner_content_bg" id="banner-content-bg-color" class="agni-colorpicker" value="<?php echo $agni_product_tag_banner_content_bg ? esc_attr( $agni_product_tag_banner_content_bg ) : ''; ?>">
                <p class="description"><?php echo esc_html__( 'The banner background image is how it display on your site.', 'agni-cartify' ); ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><label for="agni_product_tag_content_block"><?php echo esc_html__( 'Content Block', 'agni-cartify' ); ?></label></th>
            <td>
                <select name="agni_product_tag_content_block" id="agni_product_tag_content_block">
                    <?php foreach ($this->blocks_list as $key => $value) { ?>
                        <option value="<?php echo esc_attr( $key ); ?>" <?php echo selected( $agni_product_tag_content_block, $key, true ); ?>><?php echo esc_html( $value ); ?></option>
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
        $agni_product_tag_banner_image_id = sanitize_text_field( $_POST['agni_product_tag_banner_image_id'] );
        $agni_product_tag_banner_content_bg = sanitize_text_field( $_POST['agni_product_tag_banner_content_bg'] );
        $agni_product_tag_content_block = sanitize_text_field( $_POST['agni_product_tag_content_block'] );

        update_term_meta($term_id, 'agni_product_tag_banner_image_id', $agni_product_tag_banner_image_id);
        update_term_meta($term_id, 'agni_product_tag_banner_content_bg', $agni_product_tag_banner_content_bg);
        update_term_meta($term_id, 'agni_product_tag_content_block', $agni_product_tag_content_block);
    }

}

$agni_product_tagegories_meta_box = new AgniProductTagsMetaBox();
 

