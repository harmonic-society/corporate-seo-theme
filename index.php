<?php
/**
 * メインテンプレートファイル
 * ブログ投稿一覧の表示に使用されます
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main blog-archive-modern">
    
    <!-- ブログヒーローセクション -->
    <section class="blog-hero-modern">
        <!-- 背景パターン -->
        <div class="blog-hero-bg">
            <div class="blog-hero-gradient"></div>
            <div class="blog-hero-pattern"></div>
        </div>

        <div class="container">
            <div class="blog-hero-content">
                <p class="blog-hero-label">Blog</p>

                <h1 class="blog-hero-title">
                    業務改善のヒントと<br>
                    最新情報をお届け
                </h1>

                <p class="blog-hero-description">
                    中小企業の業務効率化・システム導入に役立つ情報を発信しています
                </p>

                <!-- 記事数 -->
                <div class="blog-hero-meta">
                    <span class="blog-article-count">
                        <i class="fas fa-file-alt"></i>
                        <?php echo wp_count_posts()->publish; ?>件の記事
                    </span>
                </div>
            </div>
        </div>
    </section>
    
    <!-- 検索・フィルターセクション -->
    <section class="blog-filter-section">
        <div class="container">
            <!-- 検索フォーム -->
            <div class="blog-search-wrapper">
                <?php
                $blog_url = '';
                if ( get_option( 'page_for_posts' ) ) {
                    $blog_url = get_permalink( get_option( 'page_for_posts' ) );
                } elseif ( get_option( 'show_on_front' ) == 'posts' ) {
                    $blog_url = home_url( '/' );
                } else {
                    $blog_url = get_pagenum_link();
                }
                ?>
                <form class="blog-search-form-minimal" method="get" action="<?php echo esc_url( $blog_url ); ?>">
                    <div class="search-input-minimal">
                        <i class="fas fa-search search-icon-minimal"></i>
                        <input type="search"
                               name="s"
                               placeholder="記事を検索..."
                               value="<?php echo isset($_GET['s']) ? esc_attr($_GET['s']) : ''; ?>"
                               autocomplete="off">
                        <button type="submit" class="search-btn-minimal" aria-label="検索">
                            検索
                        </button>
                    </div>
                </form>
            </div>

            <!-- カテゴリータブ -->
            <div class="blog-category-tabs">
                <?php
                $current_cat = isset($_GET['cat']) ? intval($_GET['cat']) : 0;
                $categories = get_categories(array('hide_empty' => true));
                ?>
                <a href="<?php echo esc_url($blog_url); ?>" class="category-tab <?php echo $current_cat === 0 ? 'active' : ''; ?>">
                    すべて
                </a>
                <?php foreach ($categories as $category) : ?>
                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>"
                       class="category-tab <?php echo $current_cat === $category->term_id ? 'active' : ''; ?>">
                        <?php echo esc_html($category->name); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ブログコンテンツセクション -->
    <section class="blog-content-modern">
        <div class="container">
            
            <?php if ( have_posts() ) : ?>
                
                <!-- ツールバー -->
                <div class="blog-toolbar">
                    <div class="toolbar-left">
                        <span class="post-count">
                            <?php
                            global $wp_query;
                            echo number_format_i18n($wp_query->found_posts);
                            ?>件の記事
                        </span>
                    </div>

                    <div class="toolbar-right">
                        <!-- 並び替え -->
                        <div class="sort-buttons">
                            <button class="sort-btn active" data-sort="date">新着順</button>
                            <button class="sort-btn" data-sort="title">タイトル順</button>
                        </div>

                        <!-- ビュー切り替え -->
                        <div class="view-toggle">
                            <button class="view-btn active" data-view="grid" aria-label="グリッド表示">
                                <i class="fas fa-th-large"></i>
                            </button>
                            <button class="view-btn" data-view="list" aria-label="リスト表示">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- 記事グリッド -->
                <div class="blog-grid-modern" id="blogGrid">
                    <?php
                    $post_index = 0;
                    while ( have_posts() ) :
                        the_post();
                        $post_index++;
                        
                        $article_class = 'blog-article';
                        ?>
                        
                        <article id="post-<?php the_ID(); ?>" <?php post_class( $article_class ); ?> data-aos="fade-up" data-aos-delay="<?php echo $post_index * 50; ?>">
                            <a href="<?php the_permalink(); ?>" class="article-link">
                                
                                <!-- サムネイル -->
                                <div class="article-thumbnail">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?>
                                    <?php else : ?>
                                        <div class="thumbnail-placeholder">
                                            <div class="placeholder-icon">
                                                <i class="fas fa-feather-alt"></i>
                                            </div>
                                            <div class="placeholder-pattern"></div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- オーバーレイ -->
                                    <div class="thumbnail-overlay">
                                        <span class="read-article">
                                            <i class="fas fa-book-open"></i>
                                            記事を読む
                                        </span>
                                    </div>
                                    
                                </div>
                                
                                <!-- コンテンツ -->
                                <div class="article-content">
                                    <!-- メタ情報 -->
                                    <div class="article-meta">
                                        <?php
                                        $categories = get_the_category();
                                        if ( ! empty( $categories ) ) :
                                        ?>
                                            <span class="article-category" style="--category-color: <?php echo get_theme_mod( 'category_color_' . $categories[0]->term_id, '#10b981' ); ?>">
                                                <?php echo esc_html( $categories[0]->name ); ?>
                                            </span>
                                        <?php endif; ?>
                                        
                                        <time class="article-date" datetime="<?php echo get_the_date( 'c' ); ?>">
                                            <i class="far fa-calendar"></i>
                                            <?php echo get_the_date(); ?>
                                        </time>
                                        
                                        <!-- 読了時間 -->
                                        <span class="reading-time">
                                            <i class="far fa-clock"></i>
                                            <?php echo corporate_seo_get_reading_time(); ?>
                                        </span>
                                    </div>
                                    
                                    <!-- タイトル -->
                                    <h2 class="article-title">
                                        <?php the_title(); ?>
                                    </h2>
                                    
                                    <!-- 抜粋 -->
                                    <div class="article-excerpt">
                                        <?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
                                    </div>
                                    
                                    <!-- フッター -->
                                    <div class="article-footer">
                                        <!-- 著者情報 -->
                                        <div class="article-author">
                                            <?php echo get_avatar( get_the_author_meta( 'ID' ), 32 ); ?>
                                            <span><?php the_author(); ?></span>
                                        </div>
                                        
                                        <!-- タグ -->
                                        <?php
                                        $tags = get_the_tags();
                                        if ( $tags ) :
                                        ?>
                                            <div class="article-tags">
                                                <?php foreach ( array_slice($tags, 0, 3) as $tag ) : ?>
                                                    <span class="tag-item">#<?php echo $tag->name; ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                        </article>
                        
                    <?php endwhile; ?>
                </div>
                
                <!-- ページネーション -->
                <div class="pagination-modern">
                    <?php
                    // 現在のURLパラメータを保持
                    $url_params = array();
                    if ( ! empty( $_GET['s'] ) ) $url_params['s'] = $_GET['s'];
                    if ( ! empty( $_GET['tags'] ) ) $url_params['tags'] = $_GET['tags'];
                    if ( ! empty( $_GET['category'] ) ) $url_params['category'] = $_GET['category'];
                    if ( ! empty( $_GET['period'] ) ) $url_params['period'] = $_GET['period'];
                    
                    the_posts_pagination( array(
                        'mid_size'  => 2,
                        'prev_text' => '<i class="fas fa-chevron-left"></i><span>前へ</span>',
                        'next_text' => '<span>次へ</span><i class="fas fa-chevron-right"></i>',
                        'before_page_number' => '<span class="page-label">',
                        'after_page_number' => '</span>',
                        'add_args' => $url_params
                    ) );
                    ?>
                </div>
                
            <?php else : ?>
                
                <!-- 記事が見つからない場合 -->
                <div class="no-posts-modern">
                    <div class="no-posts-illustration">
                        <i class="fas fa-search"></i>
                        <div class="search-rays">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    
                    <h2>お探しの記事が見つかりませんでした</h2>
                    <p>別のキーワードで検索するか、カテゴリーから探してみてください。</p>
                    
                    <!-- 人気記事の提案 -->
                    <div class="popular-posts-suggestion">
                        <h3>人気の記事</h3>
                        <?php
                        $popular_posts = new WP_Query( array(
                            'posts_per_page' => 3,
                            'orderby' => 'comment_count',
                            'order' => 'DESC'
                        ) );
                        
                        if ( $popular_posts->have_posts() ) :
                        ?>
                            <div class="suggestion-grid">
                                <?php while ( $popular_posts->have_posts() ) : $popular_posts->the_post(); ?>
                                    <a href="<?php the_permalink(); ?>" class="suggestion-item">
                                        <h4><?php the_title(); ?></h4>
                                        <span class="suggestion-date"><?php echo get_the_date(); ?></span>
                                    </a>
                                <?php endwhile; ?>
                            </div>
                        <?php 
                        endif;
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
                
            <?php endif; ?>
            
        </div>
    </section>
    
</main>

<?php get_footer(); ?>