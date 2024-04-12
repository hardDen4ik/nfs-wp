<?php

if( !function_exists('dynamic_block_agni_menu') ){
    function dynamic_block_agni_menu( $attributes, $content = '' ){
        $menu_choice = '';
        extract( $attributes );

        // $depth = '1';

        $args['hasMenuArrow'] = $hasMenuArrow;


        $menu_classnames = array(
            'agni-block-menu',
            // 'has-display-style-' . $display_style,
            $hasSeparator ? 'has-separator' : '',
            $hasMenuArrow ? 'has-arrow' : '',
            $textAlign ? 'has-align-' . $textAlign : '',
            $direction,
            $customClassName
        );

        ob_start();

        ?>
        <div class="<?php echo esc_attr( AgniBuilderHelper::prepare_classes( $menu_classnames ) ); ?>">
            <?php 
            if( empty( $menu_choice ) ){
                $available_menus = get_terms('nav_menu');
                if( !empty( $available_menus ) ){
                    $menu_choice = $available_menus[0]->term_id;
                }
            }
            $menu_items = wp_get_nav_menu_items( $menu_choice );
            $menu = array();

            if( !empty( $menu_items ) ){
                foreach ($menu_items as $m) {
                    if (empty($m->menu_item_parent)) {
                        $menu[$m->ID] = array();
                        $menu[$m->ID]['ID'] = $m->ID;
                        $menu[$m->ID]['title'] = $m->title;
                        $menu[$m->ID]['url'] = $m->url;
                        $menu[$m->ID]['children'] = agni_get_menu_children($menu_items, $m);
                    }
                }
            }

            // print_r( $menu );
            ?>
            <ul>
            <?php foreach ($menu as $key => $menu_item) { 

                agni_get_menu_item( $args, $menu_item, $depth );

            } ?>
            </ul>
        </div>
        <?php 

        return ob_get_clean();

    }
}

function agni_get_menu_children( $menu_array, $menu_item ){
    $children = array();
    if (!empty($menu_array)){
        foreach ($menu_array as $k=>$m) {
            if ($m->menu_item_parent == $menu_item->ID) {
                $children[$m->ID] = array();
                $children[$m->ID]['ID'] = $m->ID;
                $children[$m->ID]['title'] = $m->title;
                $children[$m->ID]['url'] = $m->url;
                unset($menu_array[$k]);
                $children[$m->ID]['children'] = agni_get_menu_children($menu_array, $m);
            }
        }
    };

    return $children;
}


function agni_get_menu_item( $args, $menu_item, $depth, $current_depth = 1 ){

    extract( $args );
    ?>
    <li>
        <a href="<?php echo esc_url( $menu_item['url'] ); ?>">
            <span><?php echo esc_html( $menu_item['title'] ); ?></span>
            <?php if( $hasMenuArrow && !empty($menu_item['children']) ){ ?>
                <i class="lni lni-chevron-down"></i>
            <?php } ?>
        </a>
        <?php if( $current_depth < $depth ){
            if( !empty($menu_item['children']) ){ 
                $current_depth = $current_depth + 1; ?>
                <ul class="sub-menu">
                    <?php foreach ($menu_item['children'] as $key => $submenu_item) {
                        agni_get_menu_item( $args, $submenu_item, $depth, $current_depth );
                    } ?>
                </ul>
            <?php } 
        } ?>
    </li>
    <?php 
}