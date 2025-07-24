<?php
/**
 * Template part for displaying blog/news card
 *
 * @package Corporate_SEO_Pro
 */

// カードクラスの設定
$card_classes = 'blog-article';
if ( isset( $args['class'] ) ) {
    $card_classes .= ' ' . $args['class'];
}

// グリッドレイアウトの判定
$is_grid = isset( $args['layout'] ) && $args['layout'] === 'grid';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $card_classes ); ?> data-category="<?php echo esc_attr( get_the_category()[0]->slug ?? '' ); ?>">
    <a href="<?php the_permalink(); ?>" class="article-link">
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="article-thumbnail">
                <?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
                <div class="article-overlay"></div>
            </div>
        <?php else : ?>
            <div class="article-thumbnail no-image">
                <div class="placeholder-icon">
                    <i class="fas fa-newspaper"></i>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="article-content">
            <div class="article-meta">
                <?php
                $categories = get_the_category();
                if ( ! empty( $categories ) ) : ?>
                    <span class="article-category"><?php echo esc_html( $categories[0]->name ); ?></span>
                <?php endif; ?>
                
                <time class="article-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                    <?php echo get_the_date(); ?>
                </time>
            </div>
            
            <h3 class="article-title"><?php the_title(); ?></h3>
            
            <?php if ( ! $is_grid ) : ?>
                <p class="article-excerpt">
                    <?php echo wp_trim_words( get_the_excerpt(), 30, '...' ); ?>
                </p>
            <?php endif; ?>
            
            <div class="article-footer">
                <span class="read-more">
                    続きを読む <i class="fas fa-arrow-right"></i>
                </span>
                
                <?php if ( function_exists( 'corporate_seo_get_reading_time' ) ) : ?>
                    <span class="reading-time">
                        <i class="far fa-clock"></i> <?php echo corporate_seo_get_reading_time(); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </a>
</article>