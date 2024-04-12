<?php  


// add_filter( 'agni_dynamic_block_agni_countdown', 'dynamic_block_agni_countdown', 2 );

function dynamic_block_agni_countdown( $attributes, $content = '' ){
    
    extract( $attributes );

    $start_date = isset($dateFrom) ? strtotime($dateFrom) : '';
    $end_date = isset($dateTo) ? strtotime($dateTo) : '';


    
    $countdown_classes = array(
        'agni-block-countdown',
        'agni-sale-countdown',
        !empty( $timerIcon ) ? 'has-timer' : '',
        'style-' . $displayStyle,
        (isset($showLabel) && ($showLabel == '1')) ? 'has-label' : '',
        !empty( $align ) ? 'has-text-align-' . $align : '',
        $customClassName
    );

    // if( $start_date > current_time( 'timestamp' ) || $end_date <= current_time( 'timestamp' ) ){
    //     return;
    // }


    ob_start();

    ?>
    <div class="<?php echo esc_attr( AgniBuilderHelper::prepare_classes( $countdown_classes ) ); ?>" data-countdown-startdate="<?php echo esc_attr( $start_date ); ?>" data-countdown-enddate="<?php echo esc_attr( $end_date ); ?>">
        <?php if( $timerIcon == 1 && $end_date > current_time( 'timestamp' ) && $start_date <= current_time( 'timestamp' ) ){ ?>
            <div class="agni-block-countdown-timer">
                <svg height="20px" width="20px">
                    <circle cx="9" cy="9" r="9"></circle>
                    <circle cx="9" cy="9" r="9"></circle>
                </svg>
            </div>
        <?php } ?>
        <div class="agni-block-countdown-container">
            <?php if( $end_date <= current_time( 'timestamp' ) && !empty($afterText) ){ ?>
                <div class="agni-block-countdown-after"><?php echo esc_html( $afterText ); ?></div>
            <?php } ?>
            <?php if( $start_date > current_time( 'timestamp' ) && !empty($beforeText) ){ ?>
                <div class="agni-block-countdown-before"><?php echo esc_html( $beforeText ); ?></div>
            <?php } ?>
            <?php if( $start_date <= current_time( 'timestamp' ) && $end_date > current_time( 'timestamp' ) ){ ?> 
                <?php if( !empty( $prefixText ) ){
                    ?><div class="agni-block-countdown-prefix"><?php echo esc_html( $prefixText ) ?></div><?php 
                } ?>
                <div class="agni-block-countdown-holder">
                    <div class="agni-block-countdown-holder--days">
                        <span class="days"></span>
                        <div class="agni-block-countdown-holder__label"><?php echo esc_html__( 'Days', 'agni-builder' ); ?></div>
                    </div>
                    <div class="agni-block-countdown-holder--hours">
                        <span class="hours"></span>
                        <div class="agni-block-countdown-holder__label"><?php echo esc_html__( 'Hrs', 'agni-builder' ); ?></div>
                    </div>
                    <div class="agni-block-countdown-holder--minutes">
                        <span class="minutes"></span>
                        <div class="agni-block-countdown-holder__label"><?php echo esc_html__( 'Mins', 'agni-builder' ); ?></div>
                    </div>
                    <div class="agni-block-countdown-holder--seconds">
                        <span class="seconds"></span>
                        <div class="agni-block-countdown-holder__label"><?php echo esc_html__( 'Secs', 'agni-builder' ); ?></div>
                    </div>
                </div>
                <?php if( !empty( $suffixText ) ){
                    ?><div class="agni-block-countdown-suffix"><?php echo esc_html( $suffixText ) ?></div><?php 
                } ?>
            <?php } ?>
        </div>
    </div>
    <?php

    return ob_get_clean();

}
