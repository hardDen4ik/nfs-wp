<?php 

if( !function_exists('dynamic_blocks_agni_posts') ){
    function dynamic_blocks_agni_posts( $attributes, $content = '' ){
        
        extract( $attributes );

        $args = array(
            'isBlock' => true,
            'disableSidebar' => true,
            'pagination' => $pagination,
            'display_style' => $display_style,
            'showTitle' => $post_title,
            'showCategory' => $post_category,
            'showExcerpt' => $post_desc,
            'showDate' => $post_date,
            'showAuthor' => $post_author,
            'showThumbnail' => $post_thumbnail
        );

        $args['posts_per_page'] = $count;

        if( isset( $post_ids ) && !empty( $post_ids ) ){
            $args['post__in'] = $post_ids;
        }

        if( isset( $category_ids ) && !empty( $category_ids ) ){
            $args['category__in'] = $category_ids;
        }

        switch( $order_by ){
            case '1':
                $args['order'] = 'ASC';
                $args['orderby'] = 'title';
                break;
            case '2':
                $args['order'] = 'DESC';
                $args['orderby'] = 'title';
                break;
            case '4': 
                $args['order'] = 'DESC';
                $args['orderby'] = 'modified';
                break;
            case '5': 
                $args['order'] = 'ASC';
                $args['orderby'] = 'menu_order';
                break;
        }


        $blog_classnames = array(
            'agni-block-posts',
            $customClassName
        );


        $posts_header_classes = array(
            'agni-block-posts-header',
            'has-header-style-' . $header_style,
            'has-header-align-' . $header_align,
            !empty( $headerFilled ) ? 'has-header-background' : '',
            !empty( $headerOutlined ) ? 'has-header-border' : '',

        );

        ob_start();

        ?>
        <div class="<?php echo esc_attr( AgniBuilderHelper::prepare_classes( $blog_classnames ) ); ?>">
            <?php if( !empty($headingText) || !empty($buttonText) || !empty($pagination_classes) ){ ?>
                <div class="<?php echo esc_attr( AgniBuilderHelper::prepare_classes( $posts_header_classes ) ); ?>">
                    <?php if( !empty($headingText) ){ ?>
                        <?php if( !empty($headingUrl) ){ ?>
                            <?php $headingText = '<a href="'.esc_url($headingUrl).'">'.wp_kses($headingText, 'title').'</a>' ?>
                        <?php } ?>
                        <?php echo wp_kses( '<h'.$headingLevel.' class="agni-block-posts-heading">'.$headingText.'</h'.$headingLevel.'>', 'title' ); ?>
                    <?php } ?>
                             
                    <?php if( !empty($buttonText) ){ 
                        ?><a class="agni-block-posts-btn" href="<?php echo esc_url( isset($buttonUrl) ? $buttonUrl : '' ); ?>" target="<?php echo esc_attr( $buttonTarget ); ?>" rel="<?php echo esc_attr( $rel ); ?>"><?php echo esc_html( $buttonText ); ?></a><?php 
                        } ?>
                </div>
            <?php } ?>
            <?php do_action( 'agni_posts', $args ); ?>
        </div>
        <?php 

        return ob_get_clean();

    }
}
