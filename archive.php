<?php
/**
 * アーカイブページテンプレート
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main archive-page">
    <header class="archive-header">
        <div class="container">
            <h1 class="archive-title">
                <?php
                if ( is_category() ) {
                    echo '<span class="archive-label">' . __( 'カテゴリー', 'corporate-seo-pro' ) . '</span>';
                    single_cat_title();
                } elseif ( is_tag() ) {
                    echo '<span class="archive-label">' . __( 'タグ', 'corporate-seo-pro' ) . '</span>';
                    single_tag_title();
                } elseif ( is_author() ) {
                    echo '<span class="archive-label">' . __( '投稿者', 'corporate-seo-pro' ) . '</span>';
                    the_author();
                } elseif ( is_year() ) {
                    echo '<span class="archive-label">' . __( '年別アーカイブ', 'corporate-seo-pro' ) . '</span>';
                    echo get_the_date( 'Y年' );
                } elseif ( is_month() ) {
                    echo '<span class="archive-label">' . __( '月別アーカイブ', 'corporate-seo-pro' ) . '</span>';
                    echo get_the_date( 'Y年n月' );
                } elseif ( is_day() ) {
                    echo '<span class="archive-label">' . __( '日別アーカイブ', 'corporate-seo-pro' ) . '</span>';
                    echo get_the_date( 'Y年n月j日' );
                } elseif ( is_post_type_archive() ) {
                    post_type_archive_title();
                } else {
                    _e( 'ブログ', 'corporate-seo-pro' );
                }
                ?>
            </h1>
            
            <?php
            // カテゴリーやタグの説明文
            $description = get_the_archive_description();
            if ( $description ) : ?>
                <div class="archive-description">
                    <?php echo $description; ?>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <section class="news-section archive-posts">
        <div class="container">
            <?php if ( have_posts() ) : ?>
                <div class="news-grid">
                    <?php 
                    $post_count = 0;
                    while ( have_posts() ) : 
                        the_post(); 
                        $post_count++;
                        $categories = get_the_category();
                    ?>
                        <article class="news-item" data-post="<?php echo $post_count; ?>">
                            <a href="<?php the_permalink(); ?>" class="news-link">
                                <div class="news-date-wrapper">
                                    <time class="news-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                        <span class="date-day"><?php echo esc_html( get_the_date( 'd' ) ); ?></span>
                                        <span class="date-month"><?php echo esc_html( get_the_date( 'M' ) ); ?></span>
                                        <span class="date-year"><?php echo esc_html( get_the_date( 'Y' ) ); ?></span>
                                    </time>
                                </div>
                                
                                <div class="news-content">
                                    <?php if ( ! empty( $categories ) ) : ?>
                                        <span class="news-category"><?php echo esc_html( $categories[0]->name ); ?></span>
                                    <?php endif; ?>
                                    
                                    <h3 class="news-title">
                                        <?php the_title(); ?>
                                        <span class="news-arrow">→</span>
                                    </h3>
                                    
                                    <div class="news-excerpt">
                                        <?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
                                    </div>
                                </div>
                                
                                <div class="news-hover-effect"></div>
                            </a>
                        </article>
                    <?php endwhile; ?>
                </div>

                <!-- ページネーション -->
                <div class="pagination-wrapper">
                    <?php
                    the_posts_pagination( array(
                        'mid_size'  => 2,
                        'prev_text' => '<i class="fas fa-chevron-left"></i>',
                        'next_text' => '<i class="fas fa-chevron-right"></i>',
                        'type'      => 'list',
                    ) );
                    ?>
                </div>

            <?php else : ?>
                <div class="no-posts">
                    <p><?php _e( '記事が見つかりませんでした。', 'corporate-seo-pro' ); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer();