<?php
/**
 * アーカイブページテンプレート
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main archive-page">
    <!-- パンくずリスト -->
    <div class="breadcrumb-wrapper">
        <div class="container">
            <?php get_template_part( 'template-parts/breadcrumb' ); ?>
        </div>
    </div>

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
                <div class="blog-grid-modern">
                    <?php 
                    while ( have_posts() ) : 
                        the_post(); 
                        // 共通ブログカードコンポーネントを使用
                        get_template_part( 'template-parts/content', 'blog-card' );
                    endwhile; 
                    ?>
                </div>

                <?php
                // ページネーション
                the_posts_pagination( array(
                    'mid_size' => 2,
                    'prev_text' => '<i class="fas fa-chevron-left"></i>',
                    'next_text' => '<i class="fas fa-chevron-right"></i>',
                ) );
                ?>

            <?php else : ?>
                <div class="no-content-wrapper">
                    <div class="no-content-message">
                        <i class="fas fa-search"></i>
                        <h2><?php _e( 'コンテンツが見つかりませんでした', 'corporate-seo-pro' ); ?></h2>
                        <p><?php _e( '申し訳ございません。お探しのコンテンツは見つかりませんでした。', 'corporate-seo-pro' ); ?></p>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
                            <?php _e( 'トップページへ戻る', 'corporate-seo-pro' ); ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>