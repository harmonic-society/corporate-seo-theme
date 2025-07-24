<?php
/**
 * 検索結果ページテンプレート
 *
 * @package Corporate_SEO_Pro
 */

get_header(); 

// 検索クエリを取得
$search_query = get_search_query();
?>

<main id="main" class="site-main search-results">
    <header class="page-header">
        <div class="container">
            <h1 class="page-title">
                <?php if ( !empty( $search_query ) ) : ?>
                    <span class="archive-label"><?php _e( '検索結果', 'corporate-seo-pro' ); ?></span>
                    <?php printf( esc_html__( '「%s」', 'corporate-seo-pro' ), '<span>' . esc_html( $search_query ) . '</span>' ); ?>
                <?php else : ?>
                    <span class="archive-label"><?php _e( '検索結果', 'corporate-seo-pro' ); ?></span>
                <?php endif; ?>
            </h1>
            <?php if ( have_posts() ) : ?>
                <p class="search-result-count">
                    <?php
                    global $wp_query;
                    printf(
                        esc_html( _n( '%s件の記事が見つかりました', '%s件の記事が見つかりました', $wp_query->found_posts, 'corporate-seo-pro' ) ),
                        '<span>' . number_format_i18n( $wp_query->found_posts ) . '</span>'
                    );
                    ?>
                </p>
            <?php endif; ?>
        </div>
    </header>

    <section class="news-section">
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
                <div class="no-results">
                    <div class="no-results-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h2><?php _e( '記事が見つかりませんでした', 'corporate-seo-pro' ); ?></h2>
                    <p><?php _e( '他のキーワードで再度検索してみてください。', 'corporate-seo-pro' ); ?></p>
                    
                    <div class="search-form-wrapper">
                        <?php get_search_form(); ?>
                    </div>
                    
                    <div class="search-suggestions">
                        <h3><?php _e( '人気のキーワード', 'corporate-seo-pro' ); ?></h3>
                        <?php
                        $popular_searches = get_theme_mod( 'popular_searches', array( 'WordPress', 'SEO', 'デザイン', 'マーケティング' ) );
                        if ( ! empty( $popular_searches ) ) : ?>
                            <div class="tag-cloud">
                                <?php foreach ( $popular_searches as $term ) : ?>
                                    <a href="<?php echo esc_url( home_url( '/?s=' . urlencode( $term ) ) ); ?>" class="tag-link">
                                        <?php echo esc_html( $term ); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer();