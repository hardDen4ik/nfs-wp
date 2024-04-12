<?php

add_filter( 'agni_slider_slide', 'cartify_slider_slide', 10, 1 );

add_filter( 'agni_slider_block_processor', 'cartify_slider_block_processor', 10, 2 );

add_filter( 'agni_slider_block_group', 'cartify_slider_group', 10, 3 );
add_filter( 'agni_slider_block_text', 'cartify_slider_text', 10, 1 );
add_filter( 'agni_slider_block_buttons', 'cartify_slider_buttons', 10, 1 );
add_filter( 'agni_slider_block_image', 'cartify_slider_image', 10, 1 );
add_filter( 'agni_slider_block_video', 'cartify_slider_video', 10, 1 );
add_filter( 'agni_slider_block_countdown', 'cartify_slider_countdown', 10, 1 );
add_filter( 'agni_slider_block_contentblock', 'cartify_slider_contentblock', 10, 1 );
add_filter( 'agni_slider_block_separator', 'cartify_slider_separator', 10, 1 );

function cartify_slider_slide( $slide ){

    $slide_settings = $slide['settings'];
    $slide_content = $slide['content'];


    $slide_background_player_class = $slide_parallax_class = $slide_parallax_attrs = '';

        $slide_background_parallax = '';
    $slide_content_animation = '';

    $slide_background_choice = isset( $slide_settings['background-choice'] ) ? $slide_settings['background-choice'] : 'color';
    $slide_background_video_choice = isset( $slide_settings['background-video-choice'] ) ? $slide_settings['background-video-choice']: 'self';
    $slide_background_video_selfhosted = isset( $slide_settings['background-video-selfhosted'] ) ? $slide_settings['background-video-selfhosted']: '';
    $slide_background_video_external_link = isset( $slide_settings['background-video-external-link'] ) ? $slide_settings['background-video-external-link']: '';
    $slide_background_video_code = isset( $slide_settings['background-video-code'] ) ? $slide_settings['background-video-code']: '';
    $slide_background_video_fallback = isset( $slide_settings['background-video-fallback'] ) ? $slide_settings['background-video-fallback']: '';

    $slide_background_parallax = isset( $slide_settings['background-parallax'] ) ? $slide_settings['background-parallax']: array( 'desktop' => '0', 'tab'=> '0', 'mobile'=> '0' );

    $slide_content_width_choice = isset( $slide_settings['content-width-choice'] ) ? $slide_settings['content-width-choice'] : 'constrained';
    $slide_content_placement = isset( $slide_settings['content-placement'] ) ? $slide_settings['content-placement']: 'lc';
    $slide_content_animation = isset( $slide_settings['content-animation'] ) ? $slide_settings['content-animation']: 'fade-in-top';
    $slide_classname = isset( $slide_settings['className'])?$slide_settings['className']: '';


//     $slide_background_video_selfhosted = 'demo.agnidesigns.local/wp-content/uploads/2013/12/2014-slider-mobile-behavior-1.mov';
//     $slide_background_video_external_link = "vimeo.com/73351257"; //"www.youtube.com/watch?v=ABatIcDswh4";
//     $slide_background_video_fallback = 'demo.agnidesigns.local/wp-content/uploads/2008/06/windmill.jpg';
// $slide_background_video_code = "autoPlay: true,
// loop: true,
// mute: true,
// startAt: 0,
// stopAt: 0,
// vol: 50";



    if (strpos($slide_background_video_external_link, 'youtu') > 0) {
        wp_enqueue_script( 'cartify-mb.ytplayer' );
        $slide_background_player_class = 'player-yt';
    } 
    elseif (strpos($slide_background_video_external_link, 'vimeo') > 0) {
        wp_enqueue_script( 'cartify-mb.vimeo_player' );
        $slide_background_player_class = 'player-vimeo';
    } 



    // $slide_background_player_class = 'player-yt';
    // $slide_background_player_class = 'player-vimeo';
    $random_number = rand(10000, 99999);
    $slide_background_video_container_id = 'agni_slide_bg_'.$random_number;

    $slide_classes = array(
        "agni-slide",
        !empty( $slide_content_animation ) ? 'has-animation' : '',
        $slide_classname
    );

    $slide_bg_classes = array(
        "agni-slide__bg-" . $slide_background_choice,
        $slide_background_video_container_id,

    );


    $slide_contents_classes = array(
        "agni-slide__contents",
        // "fullwidth",
        $slide_content_width_choice,
        $slide_content_placement
    );

    ?>


            <div class="<?php echo esc_attr( cartify_prepare_classes($slide_classes) ); ?>">
        <?php if( !empty($slide_background_parallax) && in_array( !0, array_values($slide_background_parallax) ) ){ 
        wp_enqueue_script( 'cartify-rellax' );

        ?>
        <div class="agni-slide__bg parallax" data-rellax-speed="-6" data-rellax-xs-speed="-5" data-rellax-mobile-speed="3" data-rellax-tablet-speed="-8" data-rellax-desktop-speed="-6">
        <?php } 
        else{ ?>
        <div class="agni-slide__bg">
        <?php } ?>
            <div class="<?php echo esc_attr( cartify_prepare_classes($slide_bg_classes) ); ?>">
            <?php if( $slide_background_choice == 'video' ){ 
                if( $slide_background_video_choice != 'self' ) { ?> 
                    <a id="bgndVideo_<?php echo esc_attr( $random_number ); ?>" class="player <?php echo esc_attr( $slide_background_player_class );?>" data-property="{videoURL:'<?php echo esc_url( $slide_background_video_external_link ); ?>',containment:'.<?php echo esc_attr( $slide_background_video_container_id ); ?>', coverImage:'<?php echo esc_url( $slide_background_video_fallback ); ?>', useOnMobile:false, showControls:true, autoPlay:true, loop:true, vol:50, mute:true, startAt:10, stopAt:150, opacity:1, <?php echo esc_attr( $slide_background_video_code ); ?>}"></a>
                <?php } 
                else{ ?>
                    <video autoplay loop muted poster="<?php echo esc_url( $slide_background_video_fallback); ?>">
                        <source src="<?php echo esc_url( $slide_background_video_selfhosted ); ?>" type="video/mp4">
                    </video>
            <?php }
            }  ?>
            </div>
            <div class="agni-slide__bg-overlay"></div>
        </div>
        <div class="<?php echo esc_attr( cartify_prepare_classes($slide_contents_classes) ); ?>">
            <?php foreach ($slide_content as $key => $slide_block) {
                // $slide_block_id = $slide_block['id'];
                // $slide_block_settings = $slide_block['settings'];
                // $slide_block_settings['content-animation'] = $slide_settings['content-animation'];

                                // $this->get_block($slide_block, $slide_settings);
                apply_filters( 'agni_slider_block_processor', $slide_block, $slide_settings );
            } ?>
        </div>
    </div>
    <?php
}

function cartify_slider_block_processor($slide_block, $slide_settings){
    $slide_block_id = $slide_block['id'];
    $slide_block_settings = $slide_block['settings'];
    $slide_block_settings['content-animation'] = $slide_settings['content-animation'];

    switch( $slide_block_id ){
        case 'text':
            apply_filters( 'agni_slider_block_text', $slide_block_settings );
            break;
        case 'buttons':
            apply_filters( 'agni_slider_block_buttons', $slide_block_settings );
            break;
        case 'image':
            apply_filters( 'agni_slider_block_image', $slide_block_settings );
            break;
        case 'video':
            apply_filters( 'agni_slider_block_video', $slide_block_settings );
            break;
        case 'countdown':
            apply_filters( 'agni_slider_block_countdown', $slide_block_settings );
            break;
        case 'content-block': 
            apply_filters( 'agni_slider_block_contentblock', $slide_block_settings );
            break;
        case 'separator': 
            apply_filters( 'agni_slider_block_separator', $slide_block_settings );
            break;
        case 'group':
            apply_filters( 'agni_slider_block_group', $slide_block, $slide_block_settings, $slide_settings );
            break;
    }
}

if( !function_exists( 'cartify_slider_group' ) ){
    function cartify_slider_group( $block, $block_options, $slide_settings ){

        $block_classname = isset( $block_options['className'] )?$block_options['className']: '';

        $content_animation = isset( $block_options['content-animation'] )?$block_options['content-animation']: '';

        $agni_slide_group_classnames = array(
            'agni-slide-group',
            $block_classname,
            // !empty( $slide_text_bg_color ) ? 'has-background' : '',
            !empty( $content_animation ) ? $content_animation : ''
        );

        ?>
        <div class="<?php echo esc_attr( cartify_prepare_classes( $agni_slide_group_classnames ) ); ?>">
            <?php if(true){ ?>
            <div class="agni-slide-group__bg"></div>
            <?php } ?>
            <div class="agni-slide-group__contents">
                <?php foreach ($block['content'] as $key => $innerBlock) { ?>                    
                    <?php apply_filters( 'agni_slider_block_processor', $innerBlock, $slide_settings ); // apply_filters( 'agni_slider_block_image', $slide_block_settings ); ?>
                <?php } ?>
            </div>
        </div>
        <?php
    }
}


if( !function_exists( 'cartify_slider_text' ) ){
    function cartify_slider_text( $block_options ){

        $slide_text = isset( $block_options['text'] )?$block_options['text']: 'This is Slide Text';
        $slide_text_tag = isset( $block_options['tag'] )?$block_options['tag']: 'h1';
        $slide_text_bg_color = isset( $block_options['cssText-background-color'] )?$block_options['cssText-background-color']: '';

                $slide_text_content_animation = isset( $block_options['content-animation'] )?$block_options['content-animation']: '';
        $slide_text_classname = isset( $block_options['className'] )?$block_options['className']: '';

        $agni_slide_text_classnames = array(
            'agni-slide-text',
            $slide_text_classname,
            !empty( $slide_text_bg_color ) ? 'has-background' : '',
            !empty( $slide_text_content_animation ) ? $slide_text_content_animation : ''
        );

        // $data_animista = array(
        //     "name" => "fade-in-top", 
        //     "offset" => "100%"
        // )
        /*  data-animista-options="<?php echo esc_attr( json_encode( $data_animista ) ); ?>" */

        ?>
        <div class="<?php echo esc_attr( cartify_prepare_classes($agni_slide_text_classnames) );?>">
            <<?php echo esc_attr( $slide_text_tag ); ?>><?php echo esc_html( $slide_text ); ?></<?php echo esc_attr( $slide_text_tag ); ?>>
        </div>
        <?php
    }
}


if( !function_exists( 'cartify_slider_buttons' ) ){
    function cartify_slider_buttons( $block_options ){

        $slide_buttons_target = isset( $block_options['btn-target'] )?$block_options['btn-target']: '_self';
        $slide_button_repeatable = isset( $block_options['btn-repeatable'] )?$block_options['btn-repeatable']: '';
        $slide_buttons_content_animation = isset( $block_options['content-animation'] )?$block_options['content-animation']: '';
        $slide_buttons_classname = isset( $block_options['className'] )?$block_options['className']: '';

        $agni_slide_buttons_classnames = array(
            'agni-slide-buttons',
            $slide_buttons_classname,
            !empty( $slide_buttons_content_animation ) ? $slide_buttons_content_animation : ''
        )

        ?>

        <?php if( !empty($slide_button_repeatable) ){ ?>
            <div class="<?php echo esc_attr( join(' ', $agni_slide_buttons_classnames) ); ?>"><?php 
                foreach ($slide_button_repeatable as $index => $button) {

                    $btn_text = $button['btn_text'];
                    $btn_link = (isset($button['btn_link']) && !empty($button['btn_link']))? $button['btn_link']: '#';
                    $btn_size = (isset($button['btn_size']) && !empty($button['btn_size']))?'btn-'.$button['btn_size']:'';

                    $btn_classes = array(
                        'btn',
                        $btn_size,
                        'btn-' . $index
                    )
                    ?><a class="<?php echo join(' ', array_filter($btn_classes)); ?>" href="<?php echo esc_url( $btn_link ); ?>" target="<?php echo esc_attr( $slide_buttons_target ); ?>"><?php 
                        echo esc_html( $btn_text ); 
                    ?></a><?php
                } 
            ?></div>
        <?php }
    }
} 


if( !function_exists( 'cartify_slider_image' ) ){
    function cartify_slider_image( $block_options ){

        $slide_image_url = isset( $block_options['url'] )?$block_options['url']: '';
        $visibility = isset( $block_options['visibility'] )?$block_options['visibility']: '';
        $slide_image_content_animation = isset( $block_options['content-animation'] )?$block_options['content-animation']: '';
        $slide_image_classname = isset( $block_options['className'] )?$block_options['className']: '';


        $agni_slide_image_classnames = array(
            'agni-slide-image',
            $slide_image_classname,
            !empty( $slide_image_content_animation ) ? $slide_image_content_animation : ''
        );

        if( !empty( $visibility ) ){
            if( !$visibility['desktop'] ){
                $agni_slide_image_classnames[] = 'hide-xl';
            }
            if( !$visibility['laptop'] ){
                $agni_slide_image_classnames[] = 'hide-md-lg hide-lg-xl';
            }
            if( !$visibility['tab'] ){
                $agni_slide_image_classnames[] = 'hide-sm-md';
            }
            if( !$visibility['mobile'] ){
                $agni_slide_image_classnames[] = 'hide-sm-max';
            }
        }

        ?>
        <div class="<?php echo esc_attr( cartify_prepare_classes($agni_slide_image_classnames) ); ?>">
            <img src="<?php echo esc_url( $slide_image_url ); ?>" alt=""/>
        </div>
        <?php
    }
}

if( !function_exists( 'cartify_slider_video' ) ){
    function cartify_slider_video( $block_options ){

        $player_class = '';

        $choice = isset( $block_options['choice'] ) ? $block_options['choice']: 'self';
        $selfhosted = isset( $block_options['selfhosted'] ) ? $block_options['selfhosted']: '';
        $external_link = isset( $block_options['external-link'] ) ? $block_options['external-link']: '';
        $autoplay = isset( $block_options['autoplay'] ) ? $block_options['autoplay']: '';
        $loop = isset( $block_options['loop'] ) ? $block_options['loop']: '';
        $muted = isset( $block_options['muted'] ) ? $block_options['muted']: '';
        $options = isset( $block_options['options'] ) ? $block_options['options']: '';
        $fallback = isset( $block_options['fallback'] ) ? $block_options['fallback']: '';

        // print_r( $options );

        // $selfhosted = 'demo.agnidesigns.local/wp-content/uploads/2021/04/pexels-tima-miroshnichenko-6262756.mp4';
        // $external_link = "vimeo.com/73351257"; //"www.youtube.com/watch?v=ABatIcDswh4";
        // $fallback = 'demo.agnidesigns.local/wp-content/uploads/2008/06/windmill.jpg';
        // $code = "autoPlay: true,
        // loop: true,
        // mute: true,
        // startAt: 0,
        // stopAt: 0,
        // vol: 50";

        $visibility = isset( $block_options['visibility'] ) ? $block_options['visibility']: '';

        $content_animation = isset( $block_options['content-animation'] )?$block_options['content-animation']: '';
        $classname = isset( $block_options['className'] )?$block_options['className']: '';

        $video_visibility_classname = '';

        if( !empty( $visibility ) ){
            if( !$visibility['desktop'] ){
                $video_visibility_classname = 'hide-xl';
            }
            if( !$visibility['laptop'] ){
                $video_visibility_classname = 'hide-md-lg hide-lg-xl';
            }
            if( !$visibility['tab'] ){
                $video_visibility_classname = 'hide-sm-md';
            }
            if( !$visibility['mobile'] ){
                $video_visibility_classname = 'hide-sm-max';
            }
        }


        if (strpos($external_link, 'youtu') > 0) {
            wp_enqueue_script( 'cartify-mb.ytplayer' );
            $player_class = 'player-yt';
        } 
        elseif (strpos($external_link, 'vimeo') > 0) {
            wp_enqueue_script( 'cartify-mb.vimeo_player' );
            $player_class = 'player-vimeo';
        } 


        $random_number = rand(10000, 99999);
        $container_id = 'agni_video_'.$random_number;


        $video_container_classes = array(
            'agni-slide-video-container',
            $container_id,

        );

        $video_classnames = array(
            'agni-slide-video',
            $video_visibility_classname,
            $classname,
            !empty( $content_animation ) ? $content_animation : ''
        );


        ?>
        <div class="<?php echo esc_attr( cartify_prepare_classes($video_classnames) ); ?>">
            <div class="<?php echo esc_attr( cartify_prepare_classes($video_container_classes) ); ?>">
            <?php if( $choice != 'self' ) { ?> 
                <a id="bgndVideo_<?php echo esc_attr( $random_number ); ?>" class="player <?php echo esc_attr( $player_class );?>" data-property="{videoURL:'<?php echo esc_url( $external_link ); ?>',containment:'.<?php echo esc_attr( $container_id ); ?>', coverImage:'<?php echo esc_url( $fallback ); ?>', useOnMobile:false, showControls:true, opacity:1, <?php echo esc_attr( $options ); ?>}"></a>
            <?php } 
            else{ ?>
                <video 
                    <?php echo esc_attr( $autoplay ? "autoplay" : '' ); ?> 
                    <?php echo esc_attr( $loop ? "loop" : '' ); ?> 
                    <?php echo esc_attr( $muted ? "muted" : '' ); ?> 
                    poster="<?php echo esc_url( $fallback ); ?>"
                >
                    <source src="<?php echo esc_url( $selfhosted ); ?>" type="video/mp4" />
                </video>
            <?php } ?>
            </div>
            <div class="agni-slide-video-controls">
                <button class="agni-slide-video-controls--play"><i class="lni lni-play"></i></button>
            </div>
        </div>
        <?php

    }
}

if( !function_exists( 'cartify_slider_countdown' ) ){
    function cartify_slider_countdown( $block_options ){

        $start_date = isset( $block_options['startDate'] )?$block_options['startDate']: '';
        $end_date = isset( $block_options['endDate'] )?$block_options['endDate']: '';
        $display_style = isset( $block_options['displayStyle'] )?$block_options['displayStyle']: '1';

        $content_animation = isset( $block_options['content-animation'] )?$block_options['content-animation']: '';
        $countdown_classname = isset( $block_options['className'] )?$block_options['className']: '';

        $date = new DateTime($start_date);

        $start_date = strtotime($start_date);
        $end_date = strtotime($end_date);

                $classnames = array(
            'agni-slide-countdown',
            'style-' . $display_style,
            $countdown_classname,
            !empty( $content_animation ) ? $content_animation : ''
        );


        ?>
        <div class="<?php echo esc_attr( cartify_prepare_classes( $classnames ) ); ?>" data-countdown-startdate="<?php echo esc_attr( $start_date ); ?>" data-countdown-enddate="<?php echo esc_attr( $end_date ); ?>">

                    <div class="agni-slide-countdown-container">
                <?php if( $end_date <= current_time( 'timestamp' ) && !empty($afterText) ){ ?>
                    <div class="agni-slide-countdown-after"><?php echo esc_html( $afterText ); ?></div>
                <?php } ?>
                <?php if( $start_date > current_time( 'timestamp' ) && !empty($beforeText) ){ ?>
                    <div class="agni-slide-countdown-before"><?php echo esc_html( $beforeText ); ?></div>
                <?php } ?>
                <?php if( $start_date <= current_time( 'timestamp' ) && $end_date > current_time( 'timestamp' ) ){ ?> 
                    <div class="agni-slide-countdown-holder">
                        <div class="agni-slide-countdown-holder--days">
                            <span class="days"></span>
                            <div class="agni-slide-countdown-holder__label"><?php echo esc_html__('Days', 'cartify'); ?></div>
                        </div>
                        <div class="agni-slide-countdown-holder--hours">
                            <span class="hours"></span>
                            <div class="agni-slide-countdown-holder__label"><?php echo esc_html__('Hrs', 'cartify'); ?></div>
                        </div>
                        <div class="agni-slide-countdown-holder--minutes">
                            <span class="minutes"></span>
                            <div class="agni-slide-countdown-holder__label"><?php echo esc_html__('Mins', 'cartify'); ?></div>
                        </div>
                        <div class="agni-slide-countdown-holder--seconds">
                            <span class="seconds"></span>
                            <div class="agni-slide-countdown-holder__label"><?php echo esc_html__('Secs', 'cartify'); ?></div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php 
    }
}

if( !function_exists( 'cartify_slider_contentblock' ) ){
    function cartify_slider_contentblock( $block_options ){

                $block_id = isset( $block_options['block'] ) ? $block_options['block'] : '';
        $classname = isset( $block_options['className'] ) ?$block_options['className'] : '';

        $content_animation = isset( $block_options['content-animation'] ) ? $block_options['content-animation'] : '';


        $classnames = array(
            'agni-slide-block',
            !empty( $content_animation ) ? $content_animation : '',
            $classname
        );
        ?>
        <div class="<?php echo esc_attr( cartify_prepare_classes( $classnames ) ) ?>">
            <?php 
            echo apply_filters( 'agni_content_block', $block_id );
            ?>
        </div>
        <?php
    }
}


if( !function_exists( 'cartify_slider_separator' ) ){
    function cartify_slider_separator( $block_options ){

        $classname = isset( $block_options['className'] ) ? $block_options['className'] : '';

        $content_animation = isset( $block_options['content-animation'] ) ? $block_options['content-animation'] : '';


        $classnames = array(
            'agni-slide-separator',
            !empty( $content_animation ) ? $content_animation : '',
            $classname
        );
        ?>
        <div class="<?php echo esc_attr( cartify_prepare_classes( $classnames ) ) ?>">
            <div class="agni-slide-separator-line"></div>
        </div>
        <?php
    }
}

?>