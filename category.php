<?php
/**
 * カテゴリーアーカイブテンプレート
 *
 * @package Corporate_SEO_Pro
 */

get_header(); 

// カテゴリー情報を取得
$category = get_queried_object();
?>

<main id="main" class="site-main blog-index">
    <!-- パンくずリスト -->
    <div class="breadcrumb-wrapper">
        <div class="container">
            <?php get_template_part( 'template-parts/breadcrumb' ); ?>
        </div>
    </div>

    <header class="page-header blog-header">
        <div class="container">
            <h1 class="page-title">
                <?php single_cat_title(); ?>
            </h1>
            <?php if ( category_description() ) : ?>
                <p class="page-subtitle"><?php echo category_description(); ?></p>
            <?php else : ?>
                <p class="page-subtitle">「<?php single_cat_title(); ?>」カテゴリーの記事一覧</p>
            <?php endif; ?>
        </div>
    </header>
    
    <!-- 検索・フィルターセクション -->
    <section class="blog-filter-section">
        <div class="container">
            <div class="filter-wrapper">
                <!-- カテゴリーフィルター -->
                <div class="category-filter-tabs">
                    <a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>" class="filter-tab">
                        すべて
                    </a>
                    <?php
                    $categories = get_categories();
                    foreach ( $categories as $cat ) :
                    ?>
                        <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" 
                           class="filter-tab <?php echo ($category->term_id === $cat->term_id) ? 'active' : ''; ?>">
                            <?php echo esc_html( $cat->name ); ?>
                            <span class="count">(<?php echo esc_html( $cat->count ); ?>)</span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    
    <!-- ブログ記事一覧 -->
    <section class="blog-articles-section">
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
                    'prev_text' => '<i class="fas fa-chevron-left"></i> 前へ',
                    'next_text' => '次へ <i class="fas fa-chevron-right"></i>',
                    'class' => 'blog-pagination',
                ) );
                ?>

            <?php else : ?>
                <div class="no-content-wrapper">
                    <div class="no-content-message">
                        <i class="fas fa-folder-open"></i>
                        <h2>このカテゴリーには記事がありません</h2>
                        <p>申し訳ございません。「<?php single_cat_title(); ?>」カテゴリーの記事は現在ありません。</p>
                        <a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>" class="btn btn-primary">
                            すべての記事を見る
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- サイドコンテンツ -->
    <section class="blog-sidebar-section">
        <div class="container">
            <div class="sidebar-grid">
                <!-- 人気記事 -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">
                        <i class="fas fa-fire"></i> 人気の記事
                    </h3>
                    <?php
                    $popular_posts = new WP_Query( array(
                        'posts_per_page' => 5,
                        'meta_key' => 'post_views_count',
                        'orderby' => 'meta_value_num',
                        'order' => 'DESC',
                        'cat' => $category->term_id,
                    ) );
                    
                    if ( $popular_posts->have_posts() ) : ?>
                        <ul class="popular-posts-list">
                            <?php while ( $popular_posts->have_posts() ) : $popular_posts->the_post(); ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php if ( has_post_thumbnail() ) : ?>
                                            <div class="post-thumb">
                                                <?php the_post_thumbnail( 'thumbnail' ); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="post-info">
                                            <h4><?php the_title(); ?></h4>
                                            <time><?php echo get_the_date(); ?></time>
                                        </div>
                                    </a>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php endif;
                    wp_reset_postdata();
                    ?>
                </div>

                <!-- 関連タグ -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">
                        <i class="fas fa-tags"></i> 関連タグ
                    </h3>
                    <?php
                    $tags = get_tags( array(
                        'orderby' => 'count',
                        'order' => 'DESC',
                        'number' => 20,
                    ) );
                    
                    if ( $tags ) : ?>
                        <div class="tag-cloud">
                            <?php foreach ( $tags as $tag ) : ?>
                                <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" 
                                   class="tag-link">
                                    <?php echo esc_html( $tag->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>