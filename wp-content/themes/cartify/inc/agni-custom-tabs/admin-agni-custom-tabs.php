<?php

/**
 * Add a custom product tab.
 */
function cartify_product_data_tabs_additional( $tabs) {

    $tabs['agni_additional_tabs'] = array(
		'label'		=> esc_html__( 'Cartify Additional Tabs', 'cartify' ),
		'target'	=> 'agni-additional-tabs-options',
		'class'		=> array( 'agni_woocommerce_additional_tab' ),
    );

    $tabs['agni_additional_info'] = array(
		'label'		=> esc_html__( 'Cartify Additional Info', 'cartify' ),
		'target'	=> 'agni-additional-info-options',
		'class'		=> array( 'agni_woocommerce_additional_tab' ),
    );


    	return $tabs;

}

function cartify_product_data_tab_specification_table_fields_prepare( $field = array() ){
    ?>
    <div>
        <p class="form-field agni_product_data_tab_specification_table_data_key_field">
            <label for="agni_product_data_tab_specification_table_data_key"><?php echo esc_html__( 'Data Key', 'cartify' ); ?></label>
            <input id="agni_product_data_tab_specification_table_data_key" type="text" placeholder="<?php echo esc_attr__( 'Model', 'cartify' ); ?>" name="agni_product_data_tab_specification_table_data_key[]" value="<?php if(isset( $field['key'] )){ echo esc_attr( $field['key'] ); } ?>" /> 
        </p>
        <p class="form-field agni_product_data_tab_specification_table_data_value_field">
            <label for="agni_product_data_tab_specification_table_data_value"><?php echo esc_html__( 'Data Value', 'cartify' ); ?></label>
            <input id="agni_product_data_tab_specification_table_data_value" type="text" placeholder="<?php echo esc_attr__( '#ML20673', 'cartify' ); ?>" name="agni_product_data_tab_specification_table_data_value[]" value="<?php if(isset( $field['value'] )){ echo esc_attr( $field['value'] ); } ?>" />
        </p>
        <p class="form-field"><a href="#" class="button remove"><?php echo esc_html_x( 'Remove', 'Product Options Specification remove', 'cartify' );?></a></p>

            </div>
    <?php
}


function cartify_product_data_features_fields_prepare( $field = array() ){
    ?>
    <div>
        <p class="form-field agni_product_data_feature_icon_field">
            <label for="agni_product_data_feature_icon"><?php echo esc_html__( 'Icon', 'cartify' ); ?></label>
            <input id="agni_product_data_feature_icon" type="text" placeholder="<?php echo esc_attr__( 'Icon', 'cartify' ); ?>" name="agni_product_data_feature_icon[]" value="<?php if(isset( $field['icon'] )){ echo esc_attr( $field['icon'] ); } ?>" /> 
        </p>
        <p class="form-field agni_product_data_feature_text_field">
            <label for="agni_product_data_feature_text"><?php echo esc_html__( 'Text', 'cartify' ); ?></label>
            <input id="agni_product_data_feature_text" type="text" placeholder="<?php echo esc_attr__( 'Text', 'cartify' ); ?>" name="agni_product_data_feature_text[]" value="<?php if(isset( $field['text'] )){ echo esc_attr( $field['text'] ); } ?>" />
        </p>
        <p class="form-field"><a href="#" class="button remove"><?php echo esc_html_x( 'Remove', 'Product Options Feature remove', 'cartify' );?></a></p>

            </div>
    <?php
}

function cartify_product_data_offer_fields_prepare( $field = '' ){
    ?>
    <div>
        <p class="form-field agni_product_data_offer_text_field">
            <label for="agni_product_data_offer_text"><?php echo esc_html__( 'Text', 'cartify' ); ?></label>
            <input id="agni_product_data_offer_text" type="text" placeholder="<?php echo esc_attr__( 'Offer Text', 'cartify' ); ?>" name="agni_product_data_offer_text[]" value="<?php if(isset( $field )){ echo esc_attr( $field ); } ?>" />
            <a href="#" class="button remove"><?php echo esc_html_x( 'Remove', 'Product Options Offers remove', 'cartify' );?></a>
        </p>
    </div>
    <?php
}

function cartify_product_data_additional_tabs_custom_field(){

    global $post;

    $agni_product_data_tab_specification_title = get_post_meta( $post->ID, 'agni_product_data_tab_specification_title', true );
    $agni_product_data_tab_specification_table_data = get_post_meta( $post->ID, 'agni_product_data_tab_specification_table_data', true );

    $agni_product_data_tab_shipping_info_title = get_post_meta( $post->ID, 'agni_product_data_tab_shipping_info_title', true );
    $agni_product_data_tab_shipping_info_desc = get_post_meta( $post->ID, 'agni_product_data_tab_shipping_info_desc', true );

    ?><div id='agni-additional-tabs-options' class='panel woocommerce_options_panel'><?php

		?><div class='options_group'>

            <p><strong><?php echo esc_html__( 'Specification Tab', 'cartify' ); ?></strong></p>
            <div class="agni_product_data_custom_tab_specification_fields">
                <?php
                $content = '';
                $editor_id = 'agni_product_data_tab_specification_desc';
                $setting = array(
                    'editor_class' => 'agni_product_data_tab_specification_desc'
                )
                ?>

                <?php

                woocommerce_wp_text_input( array(
                    'id'      => 'agni_product_data_tab_specification_title',
                    'name'    => 'agni_product_data_tab_specification_title',
                    'label'   => esc_html__( 'Title', 'cartify' ),
                    'value'   => esc_html( $agni_product_data_tab_specification_title )
                    //'desc_tip'=> esc_html__( 'Enter title for specification tab', 'cartify' ),
                ) );
                ?>
                <div id="agni_product_data_tab_specification_table_fields_repeatable" class="agni_product_data_tab_specification_table_fields agni_product_data_fields_repeatable">
                    <?php
                    if ( $agni_product_data_tab_specification_table_data ) {
                        foreach ( $agni_product_data_tab_specification_table_data as $field ) {
                            cartify_product_data_tab_specification_table_fields_prepare($field);
                        }
                    }
                    else {
                        cartify_product_data_tab_specification_table_fields_prepare();
                    } 
                    ?>

                    <p class="form-field"><a href="#" class="add_field button"><?php echo esc_html__( 'Duplicate', 'cartify' );?></a></p>
                </div>
            </div>
            <p><strong><?php echo esc_html__( 'Shipping Info Tab', 'cartify' ); ?></strong></p>
            <div class="agni_product_data_custom_tab_shipping_info_fields">
                <?php
                $content = wp_kses_post( $agni_product_data_tab_shipping_info_desc );
                $editor_id = 'agni_product_data_tab_shipping_info_desc';
                $setting = array(
                    'editor_class' => 'agni_product_data_tab_shipping_info_desc'
                )
                ?>

                <?php

                woocommerce_wp_text_input( array(
                    'id'      => 'agni_product_data_tab_shipping_info_title',
                    'name'      => 'agni_product_data_tab_shipping_info_title',
                    'label'   => esc_html__( 'Title', 'cartify' ),
                ) );

                ?>
                <div class="form-field agni_product_data_tab_shipping_info_desc_field">
                    <label for="<?php echo esc_attr( $setting['editor_class'] ); ?>"><?php echo esc_html__( 'Content', 'cartify' ); ?></label>
                    <?php
                    wp_editor( $content, $editor_id, $setting );
                    ?>
                </div>
            </div>


                    </div>
    </div>
    <?php
}

function cartify_product_data_additional_info_custom_field(){

	global $post;


        $agni_product_data_shipping_info_title = get_post_meta( $post->ID, 'agni_product_data_shipping_info_title', true );
    $agni_product_data_shipping_info_desc = get_post_meta( $post->ID, 'agni_product_data_shipping_info_desc', true );
    $agni_product_data_shipping_info_link_text = get_post_meta( $post->ID, 'agni_product_data_shipping_info_link_text', true );
    $agni_product_data_shipping_info_popup = get_post_meta( $post->ID, 'agni_product_data_shipping_info_popup', true );

    $agni_product_data_features = get_post_meta( $post->ID, 'agni_product_data_features', true );

    $agni_product_data_offer_title = get_post_meta( $post->ID, 'agni_product_data_offer_title', true );
    $agni_product_data_offer_text = get_post_meta( $post->ID, 'agni_product_data_offer_text', true );

    $agni_product_data_threesixty_images = get_post_meta( $post->ID, 'agni_product_data_threesixty_images', true );
    $agni_product_data_video_embed_url = get_post_meta( $post->ID, 'agni_product_data_video_embed_url', true );

	// Note the 'id' attribute needs to match the 'target' parameter set above
	?><div id='agni-additional-info-options' class='panel woocommerce_options_panel'><?php

		?><div class='options_group'>
            <p><strong><?php echo esc_html__( 'Shipping Info', 'cartify' ); ?></strong></p>
            <?php

            $editor_id = 'agni_product_data_shipping_info_popup';
            $setting = array(
                'editor_class' => 'agni_product_data_shipping_info_popup'
            );

            woocommerce_wp_text_input( array(
                'id'        => 'agni_product_data_shipping_info_title',
                'name'      => 'agni_product_data_shipping_info_title',
                'label'     => esc_html__( 'Title', 'cartify' ),
                'value'     => esc_html($agni_product_data_shipping_info_title),
            ) );
            woocommerce_wp_textarea_input( array(
                'id'        => 'agni_product_data_shipping_info_desc',
                'name'      => 'agni_product_data_shipping_info_desc',
                'label'     => esc_html__( 'Short Description', 'cartify' ),
                'value'     => esc_html($agni_product_data_shipping_info_desc),
            ) );
            woocommerce_wp_text_input( array(
                'id'        => 'agni_product_data_shipping_info_link_text',
                'name'      => 'agni_product_data_shipping_info_link_text',
                'label'     => esc_html__( 'Link Text', 'cartify' ),
                'value'     => esc_html($agni_product_data_shipping_info_link_text),
            ) );
            ?>
            <div class="form-field agni_product_data_shipping_info_popup_field">
                <label for="<?php echo esc_attr( $setting['editor_class'] ); ?>"><?php echo esc_html__( 'Popup Content', 'cartify' ); ?></label>
                <?php
                wp_editor( $agni_product_data_shipping_info_popup, $editor_id, $setting );
                ?>
            </div>
            <?php
            ?>
            <p><strong><?php echo esc_html__( 'Features', 'cartify' ); ?></strong></p>
            <div id="agni_product_data_features_fields_repeatable" class="agni_product_data_features_fields agni_product_data_fields_repeatable">
                <?php
                if ( $agni_product_data_features ) {
                    foreach ( $agni_product_data_features as $field ) {
                        cartify_product_data_features_fields_prepare($field);
                    }
                }
                else {
                    cartify_product_data_features_fields_prepare();
                } 
                ?>

                <p class="form-field"><a href="#" class="add_field button"><?php echo esc_html__( 'Duplicate', 'cartify' );?></a></p>
            </div>

            <p><strong><?php echo esc_html__( 'Offers', 'cartify' ); ?></strong></p>
            <div>
                <?php 
                woocommerce_wp_text_input( array(
                    'id'      => 'agni_product_data_offer_title',
                    'name'    => 'agni_product_data_offer_title',
                    'label'   => esc_html__( 'Title', 'cartify' ),
                    'value'   => esc_attr( $agni_product_data_offer_title )
                ) );
                ?>

                <div id="agni_product_data_offer_fields_repeatable" class="agni_product_data_offer_fields agni_product_data_fields_repeatable">
                    <?php
                    if ( $agni_product_data_offer_text ) {
                        foreach ( $agni_product_data_offer_text as $field ) {
                            cartify_product_data_offer_fields_prepare($field);
                        }
                    }
                    else {
                        cartify_product_data_offer_fields_prepare();
                    } 
                    ?>

                    <p class="form-field"><a href="#" class="add_field button"><?php echo esc_html__( 'Duplicate', 'cartify' );?></a></p>
                </div>
            </div>


                                    <p><strong><?php echo esc_html__( '360 Degree Image', 'cartify' ); ?></strong></p>
            <div class="agni_product_data_tab_threesixty_images_fields">
                <div class="form-field agni_product_data_tab_threesixty_images_field">
                    <label for="<?php echo esc_attr( $setting['editor_class'] ); ?>"><?php echo esc_html__( 'Choose Images', 'cartify' ); ?></label>
                    <div class="agni_product_data_tab_threesixty_images__holder"> 
                        <?php 

                            //$agni_product_data_threesixty_images = json_decode( $agni_product_data_threesixty_images_json[0], true );

                                                        if( $agni_product_data_threesixty_images ){
                                foreach( $agni_product_data_threesixty_images as $agni_product_data_threesixty_image ){
                                    ?><div id="agni_product_data_tab_threesixty_image-<?php echo esc_attr( $agni_product_data_threesixty_image['id'] ); ?>" class="agni_product_data_tab_threesixty_images__image">
                                    <?php echo wc_get_gallery_image_html( $agni_product_data_threesixty_image['id'], false ); ?>
                                    </div><?php
                                }
                            }
                        ?>
                    </div>
                    <?php 
                    if( $agni_product_data_threesixty_images ){
                        foreach( $agni_product_data_threesixty_images as $agni_product_data_threesixty_image ){ ?>
                            <input type="hidden" id="agni_product_data_tab_threesixty_images" name="agni_product_data_tab_threesixty_images[]" value="<?php echo esc_attr( implode(',', $agni_product_data_threesixty_image) ); ?>" />
                        <?php }
                    }
                    ?>
                    <a href="#" class="form-field agni_product_data_tab_threesixty_images__button button" data-product-id="<?php echo esc_attr($post->ID); ?>"><?php echo esc_html__( 'Add Images', 'cartify' ); ?></a>
                    <p><?php echo esc_html__( 'Add atleast 30 images to see flawless view.', 'cartify' ); ?></p>
                </div>
            </div>

            <p><strong><?php echo esc_html__( 'Produce Video', 'cartify' ); ?></strong></p>
            <div class="agni_product_data_tab_video_fields">
                <?php  
                    woocommerce_wp_textarea_input( array(
                    'id'        => 'agni_product_data_tab_embed_url',
                    'name'      => 'agni_product_data_tab_embed_url',
                    'label'     => esc_html__( 'Embed URL', 'cartify' ),
                    'value'     => $agni_product_data_video_embed_url,
                ) );
                ?>
            </div>

                    </div>
    </div>
    <?php

}

function cartify_product_data_custom_tabs_save_custom_field( $post_id ) {

	    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if (!current_user_can('edit_post', $post_id))
        return;


    $existing_shipping_info_title = get_post_meta( $post_id, 'agni_product_data_tab_shipping_info_title', true );
    $existing_shipping_info_desc = get_post_meta( $post_id, 'agni_product_data_tab_shipping_info_desc', true );

    $shipping_info_title = $_POST['agni_product_data_tab_shipping_info_title'];
    $shipping_info_desc = $_POST['agni_product_data_tab_shipping_info_desc'];

    if( !empty($shipping_info_title) ){
        update_post_meta( $post_id, 'agni_product_data_tab_shipping_info_title', $shipping_info_title );
    }
    else{
        delete_post_meta( $post_id, 'agni_product_data_tab_shipping_info_title', $existing_shipping_info_title );
    }

    if( !empty($shipping_info_desc) ){
        update_post_meta( $post_id, 'agni_product_data_tab_shipping_info_desc', $shipping_info_desc );
    }
    else{
        delete_post_meta( $post_id, 'agni_product_data_tab_shipping_info_desc', $existing_shipping_info_desc );
    }

            $existing_specification_title = get_post_meta( $post_id, 'agni_product_data_tab_specification_title', true );
    $existing_specification_table_data_fields = get_post_meta( $post_id, 'agni_product_data_tab_specification_table_data', true );
    $new_specification_table_data_fields = array();

    $specification_title = $_POST['agni_product_data_tab_specification_title'];
    $specification_table_data_key = $_POST['agni_product_data_tab_specification_table_data_key'];
    $specification_table_data_value = $_POST['agni_product_data_tab_specification_table_data_value'];


    if( !empty($specification_title) ){
        update_post_meta( $post_id, 'agni_product_data_tab_specification_title', $specification_title );
    }
    else{
        delete_post_meta( $post_id, 'agni_product_data_tab_specification_title', $existing_specification_title );
    }

    for ( $i = 0; $i < count( $specification_table_data_key ); $i++ ) {
        if ( $specification_table_data_key[$i] != '' ){
            $new_specification_table_data_fields[$i]['key'] = $specification_table_data_key[$i];
        }
        if ( $specification_table_data_value[$i] != '' ){
            $new_specification_table_data_fields[$i]['value'] = $specification_table_data_value[$i];
        }
    }
    if ( !empty( $new_specification_table_data_fields ) && $new_specification_table_data_fields != $existing_specification_table_data_fields )
        update_post_meta( $post_id, 'agni_product_data_tab_specification_table_data', $new_specification_table_data_fields );
    elseif ( empty($new_specification_table_data_fields) && $existing_specification_table_data_fields )
        delete_post_meta( $post_id, 'agni_product_data_tab_specification_table_data', $existing_specification_table_data_fields );


    $existing_features_fields = get_post_meta($post_id, 'agni_product_data_features', true);
    $new_features_fields = array();
    $features_icons = $_POST['agni_product_data_feature_icon'];
    $features_text = $_POST['agni_product_data_feature_text'];

    for ( $i = 0; $i < count( $features_icons ); $i++ ) {
        if ( $features_icons[$i] != '' ){
            $new_features_fields[$i]['icon'] = $features_icons[$i];
        }
        if ( $features_text[$i] != '' ){
            $new_features_fields[$i]['text'] = $features_text[$i];
        }
    }
    if ( !empty( $new_features_fields ) && $new_features_fields != $existing_features_fields )
        update_post_meta( $post_id, 'agni_product_data_features', $new_features_fields );
    elseif ( empty($new_features_fields) && $existing_features_fields )
        delete_post_meta( $post_id, 'agni_product_data_features', $existing_features_fields );


    $existing_offer_title_field = get_post_meta( $post_id, 'agni_product_data_offer_title', true );
    $existing_offer_text_field = get_post_meta( $post_id, 'agni_product_data_offer_text', true );
    $new_offer_text_field = array();

    $offer_title = $_POST['agni_product_data_offer_title'];
    $offer_texts = $_POST['agni_product_data_offer_text'];
    foreach( $offer_texts as $offer_text ){
        $new_offer_text_field[] = $offer_text;
    }

    if( !empty($new_offer_text_field) ){
        update_post_meta( $post_id, 'agni_product_data_offer_text', $new_offer_text_field );
    }
    else{
        delete_post_meta( $post_id, 'agni_product_data_offer_text', $existing_offer_text_field );
    }

    if( !empty($offer_title) ){
        update_post_meta( $post_id, 'agni_product_data_offer_title', $offer_title );
    }
    else{
        delete_post_meta( $post_id, 'agni_product_data_offer_title', $existing_offer_title_field );
    }


    $existing_shipping_info_title_field = get_post_meta( $post_id, 'agni_product_data_shipping_info_title', true );
    $existing_shipping_info_desc_field = get_post_meta( $post_id, 'agni_product_data_shipping_info_desc', true );
    $existing_shipping_info_link_text_field = get_post_meta( $post_id, 'agni_product_data_shipping_info_link_text', true );
    $existing_shipping_info_popup_field = get_post_meta( $post_id, 'agni_product_data_shipping_info_popup', true );

        $shipping_info_title = $_POST['agni_product_data_shipping_info_title'];
    $shipping_info_desc = $_POST['agni_product_data_shipping_info_desc'];
    $shipping_info_link_text = $_POST['agni_product_data_shipping_info_link_text'];
    $shipping_info_popup = $_POST['agni_product_data_shipping_info_popup'];

    if( !empty($shipping_info_title) ){
        update_post_meta( $post_id, 'agni_product_data_shipping_info_title', $shipping_info_title );
    }
    else{
        delete_post_meta( $post_id, 'agni_product_data_shipping_info_title', $existing_shipping_info_title_field );
    }

    if( !empty($shipping_info_desc) ){
        update_post_meta( $post_id, 'agni_product_data_shipping_info_desc', $shipping_info_desc );
    }
    else{
        delete_post_meta( $post_id, 'agni_product_data_shipping_info_desc', $existing_shipping_info_desc_field );
    }

    if( !empty($shipping_info_link_text) ){
        update_post_meta( $post_id, 'agni_product_data_shipping_info_link_text', $shipping_info_link_text );
    }
    else{
        delete_post_meta( $post_id, 'agni_product_data_shipping_info_link_text', $existing_shipping_info_link_text_field );
    }
    if( !empty($shipping_info_popup) ){
        update_post_meta( $post_id, 'agni_product_data_shipping_info_popup', $shipping_info_popup );
    }
    else{
        delete_post_meta( $post_id, 'agni_product_data_shipping_info_popup', $existing_shipping_info_popup_field );
    }

    $existing_threesixty_images_list_field = get_post_meta( $post_id, 'agni_product_data_threesixty_images', true );
    $new_threesixty_images_list = array();
    if( isset( $_POST['agni_product_data_tab_threesixty_images'] ) ){
        $threesixty_images_list = $_POST['agni_product_data_tab_threesixty_images'];

        for ( $i = 0; $i < count( $threesixty_images_list ); $i++ ) {
            if ( $threesixty_images_list[$i] != '' ){
                $threesixty_images_list_item = explode( ',', $threesixty_images_list[$i] );
                $new_threesixty_images_list[$i]['id'] = $threesixty_images_list_item[0];
                $new_threesixty_images_list[$i]['url'] = $threesixty_images_list_item[1];
            }
        }

        if( !empty($new_threesixty_images_list) && $new_threesixty_images_list != $existing_threesixty_images_list_field ){
            update_post_meta( $post_id, 'agni_product_data_threesixty_images', $new_threesixty_images_list );
        }
        elseif ( empty($new_threesixty_images_list) && $existing_threesixty_images_list_field ){
            delete_post_meta( $post_id, 'agni_product_data_threesixty_images', $existing_threesixty_images_list_field );
        }
    }

    $existing_video_embed_url = get_post_meta( $post_id, 'agni_product_data_video_embed_url', true );
    $video_embed_url = $_POST['agni_product_data_tab_embed_url'];

    if( !empty( $video_embed_url ) ){
        update_post_meta( $post_id, 'agni_product_data_video_embed_url', $video_embed_url );
    }
    else{
        delete_post_meta( $post_id, 'agni_product_data_video_embed_url', $existing_video_embed_url );
    }


        return $post_id;

	}


function cartify_admin_custom_tabs_scripts(){

    // Enqueue JS
	if( !did_action( 'wp_enqueue_media' ) ) {
        wp_enqueue_media();
	}

    // Registering JS for custom tabs
    wp_enqueue_script('cartify-admin-custom-tabs', AGNI_FRAMEWORK_JS_URL . '/agni-custom-tabs/admin-agni-custom-tabs.js', array('jquery'), wp_get_theme()->get('Version'), true);
}



add_filter( 'woocommerce_product_data_tabs', 'cartify_product_data_tabs_additional' );
add_filter( 'woocommerce_product_data_panels', 'cartify_product_data_additional_info_custom_field', 11 );
add_filter( 'woocommerce_product_data_panels', 'cartify_product_data_additional_tabs_custom_field' );
add_action( 'woocommerce_process_product_meta', 'cartify_product_data_custom_tabs_save_custom_field', 10, 1 );

add_action( 'admin_enqueue_scripts', 'cartify_admin_custom_tabs_scripts' );