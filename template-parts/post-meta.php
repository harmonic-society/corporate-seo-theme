<?php
/**
 * Template part for displaying post meta information
 *
 * @package Corporate_SEO_Pro
 */

// メタ情報のタイプを取得（デフォルトは'post'）
$meta_type = isset( $args['type'] ) ? $args['type'] : 'post';
$show_author = isset( $args['show_author'] ) ? $args['show_author'] : true;
$show_date = isset( $args['show_date'] ) ? $args['show_date'] : true;
$show_category = isset( $args['show_category'] ) ? $args['show_category'] : true;
$show_reading_time = isset( $args['show_reading_time'] ) ? $args['show_reading_time'] : true;
?>

<div class="post-meta">
    <?php if ( $meta_type === 'post' ) : ?>
        
        <?php if ( $show_date ) : ?>
            <span class="meta-item meta-date">
                <i class="far fa-calendar-alt"></i>
                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                    <?php echo get_the_date(); ?>
                </time>
            </span>
        <?php endif; ?>
        
        <?php if ( $show_author ) : ?>
            <span class="meta-item meta-author">
                <i class="far fa-user"></i>
                <?php the_author(); ?>
            </span>
        <?php endif; ?>
        
        <?php if ( $show_category ) : 
            $categories = get_the_category();
            if ( ! empty( $categories ) ) : ?>
                <span class="meta-item meta-category">
                    <i class="far fa-folder"></i>
                    <a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>">
                        <?php echo esc_html( $categories[0]->name ); ?>
                    </a>
                </span>
            <?php endif; 
        endif; ?>
        
        <?php if ( $show_reading_time && function_exists( 'corporate_seo_get_reading_time' ) ) : ?>
            <span class="meta-item meta-reading-time">
                <i class="far fa-clock"></i>
                <?php echo corporate_seo_get_reading_time(); ?>
            </span>
        <?php endif; ?>
        
    <?php elseif ( $meta_type === 'service' ) : ?>
        
        <?php 
        // ACF fields or post meta
        if ( function_exists('get_field') ) {
            $duration = get_field('service_duration');
            $price = get_field('service_price');
        } else {
            $duration = get_post_meta( get_the_ID(), '_service_duration', true );
            $price = get_post_meta( get_the_ID(), '_service_price', true );
        }
        ?>
        
        <?php if ( $duration ) : ?>
            <span class="meta-item meta-duration">
                <i class="far fa-clock"></i>
                所要時間: <?php echo esc_html( $duration ); ?>
            </span>
        <?php endif; ?>
        
        <?php if ( $price ) : ?>
            <span class="meta-item meta-price">
                <i class="fas fa-yen-sign"></i>
                料金: <?php echo esc_html( $price ); ?>
            </span>
        <?php endif; ?>
        
    <?php elseif ( $meta_type === 'work' ) : ?>
        
        <?php 
        $client = get_post_meta( get_the_ID(), '_work_client', true );
        $duration = get_post_meta( get_the_ID(), '_work_duration', true );
        $industry = get_post_meta( get_the_ID(), '_work_industry', true );
        ?>
        
        <?php if ( $client ) : ?>
            <span class="meta-item meta-client">
                <i class="far fa-building"></i>
                クライアント: <?php echo esc_html( $client ); ?>
            </span>
        <?php endif; ?>
        
        <?php if ( $duration ) : ?>
            <span class="meta-item meta-duration">
                <i class="far fa-calendar-alt"></i>
                期間: <?php echo esc_html( $duration ); ?>
            </span>
        <?php endif; ?>
        
        <?php if ( $industry ) : ?>
            <span class="meta-item meta-industry">
                <i class="fas fa-industry"></i>
                業界: <?php echo esc_html( $industry ); ?>
            </span>
        <?php endif; ?>
        
    <?php endif; ?>
    
    <?php
    // カスタムフック
    do_action( 'corporate_seo_pro_post_meta_after', $meta_type );
    ?>
</div>