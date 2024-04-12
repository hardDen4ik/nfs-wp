<?php

add_action( 'wp_nav_menu_item_custom_fields', 'agni_nav_menu_custom_fields', 99, 2 );
add_action( 'wp_update_nav_menu_item', 'agni_save_nav_menu_custom_fields', 10, 2 );

add_action( 'wp_enqueue_scripts', 'agni_megamenu_enqueue_scripts' );

add_filter( 'nav_menu_item_title', 'agni_custom_menu_item_title', 10, 4 );
add_filter( 'nav_menu_css_class', 'agni_custom_menu_css_class', 10, 4 );
add_filter( 'wp_nav_menu_objects', 'agni_custom_menu_objects', 10, 2 );
add_filter( 'walker_nav_menu_start_el', 'agni_custom_nav_menu_start_el', 10, 4 );

function agni_nav_menu_custom_fields( $item_id, $item ) {

	wp_nonce_field( 'agni_menu_options_nonce_action', 'agni_menu_options_nonce_field' );

	$label = get_post_meta( $item_id, 'agni_menu_item_label', true );
	$show_menu_on = get_post_meta( $item_id, 'agni_menu_item_show_menu_on', true );
	$hide_menu_text = get_post_meta( $item_id, 'agni_menu_item_hide_menu_text', true );
	$block_choice = get_post_meta( $item_id, 'agni_menu_item_block_choice', true );
	$width = get_post_meta( $item_id, 'agni_menu_item_width', true );
	$height = get_post_meta( $item_id, 'agni_menu_item_height', true );
	$fullwidth = get_post_meta( $item_id, 'agni_menu_item_fullwidth', true );
	$icon = get_post_meta( $item_id, 'agni_menu_item_icon', true );

	?>
	<div class="field-agni_menu_options agni_menu_options description-wide">
        <br />
	    <strong><?php echo esc_html__( "Agni Menu Options", 'cartify' ); ?></strong>
        <hr />
        <p>
            <label for="agni-menu-item-label-<?php echo esc_attr( $item_id ); ?>">
                <?php echo esc_html__( 'Label Text', 'cartify' ); ?>
                <br />
                <input type="text" id="agni-menu-item-label-<?php echo esc_attr( $item_id ); ?>" class="agni-menu-item-label" name="agni_menu_item_label[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $label ); ?>" />
            </label>
        </p>
        <p>
            <label for="agni-menu-item-show-menu-on<?php echo esc_attr( $item_id ); ?>">
                <?php echo esc_html__( 'Show Dropdown Menu on', 'cartify' ); ?>
                <br />
                <select id="agni-menu-item-show-menu-on<?php echo esc_attr( $item_id ); ?>" name="agni_menu_item_show_menu_on[<?php echo esc_attr( $item_id ); ?>]">
                    <option value="hover" <?php echo selected( $show_menu_on, 'hover', true ); ?>><?php echo esc_html__( 'Hover', 'cartify' ); ?></option>
                    <option value="click" <?php echo selected( $show_menu_on, 'click', true ); ?>><?php echo esc_html__( 'Click', 'cartify' ); ?></option>
                </select>
            </label>
        </p>
        <p>
            <label for="agni-menu-item-block-choice-<?php echo esc_attr( $item_id ); ?>">
                <?php echo esc_html__( 'Dropdown Menu Block Choice', 'cartify' ); ?>
                <br />
                <select id="agni-menu-item-block-choice-<?php echo esc_attr( $item_id ); ?>" name="agni_menu_item_block_choice[<?php echo esc_attr( $item_id ); ?>]">
                    <?php 
                    $blocks_args = array( 'post_type' => 'agni_block' );
                    $blocks = apply_filters('agni_get_posttype_options', $blocks_args, true );
                    foreach ( $blocks as $key => $value) { ?>
                        <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $block_choice, $key, true ); ?>><?php echo esc_html( $value ); ?></option>
                    <?php }
                    ?>
                </select>
            </label>
        </p>
		<div class="agni_menu_options-group">
			<p>
				<label for="agni-menu-item-width-<?php echo esc_attr( $item_id ); ?>">
					<?php echo esc_html__( 'Width', 'cartify' ); ?>
					<br />
					<input type="text" id="agni-menu-item-width-<?php echo esc_attr( $item_id ); ?>" class="agni-menu-item-width" name="agni_menu_item_width[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $width ); ?>" />
				</label>
			</p>
			<p>
				<label for="agni-menu-item-height-<?php echo esc_attr( $item_id ); ?>">
					<?php echo esc_html__( 'Height', 'cartify' ); ?>
					<br />
					<input type="text" id="agni-menu-item-height-<?php echo esc_attr( $item_id ); ?>" class="agni-menu-item-height" name="agni_menu_item_height[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $height ); ?>" />
				</label>
			</p>
		</div>
        <p>
            <label for="agni-menu-item-fullwidth-<?php echo esc_attr( $item_id ); ?>">
                <input type="checkbox" id="agni-menu-item-fullwidth-<?php echo esc_attr( $item_id ); ?>" name="agni_menu_item_fullwidth[<?php echo esc_attr( $item_id ); ?>]" <?php checked($fullwidth, 1); ?> value="1" />
                <?php echo esc_html__( 'Fullwidth', 'cartify' ); ?>
            </label>
        </p>
        <p>
            <label for="agni-menu-item-hide-menu-text-<?php echo esc_attr( $item_id ); ?>">
                <input type="checkbox" id="agni-menu-item-hide-menu-text-<?php echo esc_attr( $item_id ); ?>" name="agni_menu_item_hide_menu_text[<?php echo esc_attr( $item_id ); ?>]" <?php checked($hide_menu_text, 1); ?> value="1" />
                <?php echo esc_html__( 'Hide Menu Text', 'cartify' ); ?>
            </label>
        </p>
        <p>
            <label for="agni-menu-item-icon-<?php echo esc_attr( $item_id ); ?>">
                <?php echo esc_html__( 'Icon Class', 'cartify' ); ?>
                <br />
                <input type="text" id="agni-menu-item-icon-<?php echo esc_attr( $item_id ); ?>" class="agni-menu-item-icon description-wide" name="agni_menu_item_icon[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $icon ); ?>" />
                <span><a href="<?php echo esc_url( 'demo.agnidesigns.com/doc/cartify/additional/see-all-available-icons/' ); ?>"><?php echo esc_html__( 'Click here', 'cartify') ?></a><?php echo esc_html__( ' to see all available icons.', 'cartify' ); ?></span>
            </label>
        </p>

	</div>

    <?php
}


function agni_save_nav_menu_custom_fields( $menu_id, $menu_item_db_id ) {

	/*
	label background color, border color
	submenu width, height
	submenu bg color, color (optional)


		*/

	if ( ! isset( $_POST['agni_menu_options_nonce_field'] ) || ! wp_verify_nonce( $_POST['agni_menu_options_nonce_field'], 'agni_menu_options_nonce_action' ) ) {
		return 'Invalid Nonce';
	}

	if ( isset( $_POST['agni_menu_item_label'][$menu_item_db_id]  ) ) {
		$sanitized_data = sanitize_text_field( $_POST['agni_menu_item_label'][$menu_item_db_id] );
		update_post_meta( $menu_item_db_id, 'agni_menu_item_label', $sanitized_data );
	} else {
		delete_post_meta( $menu_item_db_id, 'agni_menu_item_label' );
	}

	if ( isset( $_POST['agni_menu_item_show_menu_on'][$menu_item_db_id]  ) ) {
		$sanitized_data = sanitize_text_field( $_POST['agni_menu_item_show_menu_on'][$menu_item_db_id] );
		update_post_meta( $menu_item_db_id, 'agni_menu_item_show_menu_on', $sanitized_data );
	} else {
		delete_post_meta( $menu_item_db_id, 'agni_menu_item_show_menu_on' );
	}

	if ( isset( $_POST['agni_menu_item_hide_menu_text'][$menu_item_db_id]  ) ) {
		$sanitized_data = sanitize_text_field( $_POST['agni_menu_item_hide_menu_text'][$menu_item_db_id] );
		update_post_meta( $menu_item_db_id, 'agni_menu_item_hide_menu_text', $sanitized_data );
	} else {
		delete_post_meta( $menu_item_db_id, 'agni_menu_item_hide_menu_text' );
	}

	if ( isset( $_POST['agni_menu_item_block_choice'][$menu_item_db_id]  ) ) {
		$sanitized_data = sanitize_text_field( $_POST['agni_menu_item_block_choice'][$menu_item_db_id] );
		update_post_meta( $menu_item_db_id, 'agni_menu_item_block_choice', $sanitized_data );
	} else {
		delete_post_meta( $menu_item_db_id, 'agni_menu_item_block_choice' );
	}



	if ( isset( $_POST['agni_menu_item_width'][$menu_item_db_id]  ) ) {
		$sanitized_data = sanitize_text_field( $_POST['agni_menu_item_width'][$menu_item_db_id] );
		update_post_meta( $menu_item_db_id, 'agni_menu_item_width', $sanitized_data );
	} else {
		delete_post_meta( $menu_item_db_id, 'agni_menu_item_width' );
	}

	if ( isset( $_POST['agni_menu_item_height'][$menu_item_db_id]  ) ) {
		$sanitized_data = sanitize_text_field( $_POST['agni_menu_item_height'][$menu_item_db_id] );
		update_post_meta( $menu_item_db_id, 'agni_menu_item_height', $sanitized_data );
	} else {
		delete_post_meta( $menu_item_db_id, 'agni_menu_item_height' );
	}


	if ( isset( $_POST['agni_menu_item_fullwidth'][$menu_item_db_id]  ) ) {
		$sanitized_data = sanitize_text_field( $_POST['agni_menu_item_fullwidth'][$menu_item_db_id] );
		update_post_meta( $menu_item_db_id, 'agni_menu_item_fullwidth', $sanitized_data );
	} else {
		delete_post_meta( $menu_item_db_id, 'agni_menu_item_fullwidth' );
	}

	if ( isset( $_POST['agni_menu_item_icon'][$menu_item_db_id]  ) ) {
		$sanitized_data = sanitize_text_field( $_POST['agni_menu_item_icon'][$menu_item_db_id] );
		update_post_meta( $menu_item_db_id, 'agni_menu_item_icon', $sanitized_data );
	} else {
		delete_post_meta( $menu_item_db_id, 'agni_menu_item_icon' );
	}
}


function agni_custom_menu_item_title( $title, $item, $args, $depth ){

	$label = get_post_meta( $item->ID, 'agni_menu_item_label', true );
	// $show_menu_on = get_post_meta( $item->ID, 'agni_menu_item_show_menu_on', true );
	$block_choice = get_post_meta( $item->ID, 'agni_menu_item_block_choice', true );
	$hide_menu_text = get_post_meta( $item->ID, 'agni_menu_item_hide_menu_text', true );
	$icon = get_post_meta( $item->ID, 'agni_menu_item_icon', true );

    $output = '';

	$output .= '<span class="agni-menu-item-container">';
    if( !empty( $icon ) ){
		$get_icon = cartify_prepare_icon( $icon );
    	$output .= '<span class="agni-menu-item-icon">' . wp_kses( $get_icon, array( 'img' => array(
			'src' => array(),
			'width' => array(),
			'height' => array(),
			'class' => array(),
			'alt' => array()
		), 
		'i' => array(
			'class' => array()
		) ) ) . '</span>';
    }
    $output .= '<span class="agni-menu-item-text">'; 
		if( !$hide_menu_text ){
		$output .= wp_kses( $title, array( 
			'span' => array(
				"class" => array()
			),
			"img" => array(
				'src' => array(),
				'width' => array(),
				'height' => array(),
				'class' => array(),
				'alt' => array()
			)
		) );
		}
		if( !empty( $item->description ) ){
			$output .= '<span>'.esc_html( $item->description ).'</span>';
		}
	$output .= '</span>';
	if( !empty( $label ) ){
		$output .= '<span class="agni-menu-item-label">' . esc_html( $label ) . '</span>';
	}
	$output .= '</span>';


	if( in_array( 'menu-item-has-children', $item->classes ) || !empty( $block_choice ) ){
		$output .= '<span class="agni-menu-item-more"><i class="lni lni-chevron-down"></i></span>';
	}

    return $output;
}

function agni_custom_menu_css_class( $classes, $item, $args, $depth ){

	$block_choice = get_post_meta( $item->ID, 'agni_menu_item_block_choice', true );
	if( !empty( $block_choice ) ){
		$classes[] = 'has-agni-block';
	}

	return $classes;

}

function agni_custom_menu_objects( $sorted_menu_items, $args ){

	// print_r( $sorted_menu_items );

		foreach ($sorted_menu_items as $key => $menu_item) {
		if( 0 !== $menu_item->menu_item_parent ){
			$block_choice = get_post_meta( $menu_item->menu_item_parent, 'agni_menu_item_block_choice', true );

			if( !empty( $block_choice ) ){
				unset( $sorted_menu_items[$key] );
			}
		}
	}

	// print_r( $args );

	// echo $args->sub_menu;

	return $sorted_menu_items;
}

function agni_custom_nav_menu_start_el( $item_output, $item, $depth, $args ){

	// echo "depth: " . $depth;
	// print_r( $args['depth'] );

	if( $args->depth == 1 ){
		return $item_output;
	}

	$block_choice = get_post_meta( $item->ID, 'agni_menu_item_block_choice', true );
	$width = get_post_meta( $item->ID, 'agni_menu_item_width', true );
	$height = get_post_meta( $item->ID, 'agni_menu_item_height', true );
	$fullwidth = get_post_meta( $item->ID, 'agni_menu_item_fullwidth', true );


	$styles = "";

		if( !wp_style_is( 'agni-builder-frontend-megamenu-' . $item->ID . '-' . $block_choice ) ) {
		if( isset( $width ) && !empty( $width ) ){
			$styles .= "@media (min-width: {$width}px) {";
				$styles .= ".menu-item-{$item->ID} .agni-megamenu-block-{$block_choice}{";
					$styles .= "--cartify_header_submenu_width:{$width}px;";
				$styles .= "}";
			$styles .= "}";
		}
		$styles .= ".menu-item-{$item->ID} .agni-megamenu-block-{$block_choice} >div{";
			if( isset( $height ) && !empty( $height ) ){
				$styles .= "max-height: {$height}px;";
			}
		$styles .= "}";

		wp_add_inline_style( 'agni-builder-frontend-megamenu-' . $item->ID . '-' . $block_choice, $styles );
	}

	// wp_enqueue_style( 'agni-builder-frontend-megamenu-' . $item->ID . '-' . $block_choice );

	if( !empty( $block_choice ) ){

		$item_output_classes = array( 
			'sub-menu',
			'agni-megamenu-block',
			'agni-megamenu-block-' . $block_choice,
			(isset( $fullwidth ) && !empty( $fullwidth )) ? 'has-fullwidth' : '',
		);


		$item_output .= '<div class="' . esc_attr( cartify_prepare_classes( $item_output_classes ) ) . '">'; 
		$item_output .= '<div class="agni-megamenu-block-container">'; 
		$item_output .= apply_filters( 'agni_content_block', $block_choice );// apply_filters('the_content', get_post_field('post_content', $block_choice));
		$item_output .= '</div>';
		$item_output .= '</div>';
	}

	return $item_output;

}


function agni_megamenu_enqueue_scripts(){



		$get_all_nav_menus = wp_get_nav_menus();

		foreach ($get_all_nav_menus as $key => $term) {
			$megamenu_styles = '';

			$term_id = $term->term_id;

			$megamenu_items = wp_get_nav_menu_items( $term_id );

			foreach ($megamenu_items as $megamenu_item) {

				$megamenu_block_id = get_post_meta( $megamenu_item->ID, 'agni_menu_item_block_choice', true );

				if( !empty( $megamenu_block_id ) ){

					$block_choice = get_post_meta( $megamenu_item->ID, 'agni_menu_item_block_choice', true );
					$width = get_post_meta( $megamenu_item->ID, 'agni_menu_item_width', true );
					$height = get_post_meta( $megamenu_item->ID, 'agni_menu_item_height', true );
					$fullwidth = get_post_meta( $megamenu_item->ID, 'agni_menu_item_fullwidth', true );

					$styles = "";

										if( isset( $width ) && !empty( $width ) ){
						$styles .= "@media (min-width: {$width}px) {";
							$styles .= ".menu-item-{$megamenu_item->ID} .agni-megamenu-block-{$block_choice}{";
								$styles .= "--cartify_header_submenu_width:{$width}px;";
							$styles .= "}";
						$styles .= "}";
					}
					$styles .= ".menu-item-{$megamenu_item->ID} .agni-megamenu-block-{$block_choice} >div{";
						if( isset( $height ) && !empty( $height ) ){
							$styles .= "max-height: {$height}px;";
						}
					$styles .= "}";

					// register styles
					wp_register_style( 'cartify-megamenu-custom-' . $megamenu_item->ID . '-' . $block_choice, AGNI_FRAMEWORK_CSS_URL . '/custom.css' );
					wp_add_inline_style( 'cartify-megamenu-custom-' . $megamenu_item->ID . '-' . $block_choice, $styles );
					wp_enqueue_style( 'cartify-megamenu-custom-' . $megamenu_item->ID . '-' . $block_choice );

				}
			}

		}
}