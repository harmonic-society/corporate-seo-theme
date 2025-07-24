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
        <!-- 動的グラデーション背景 -->
        <div class="hero-gradient-bg">
            <div class="gradient-layer gradient-1"></div>
            <div class="gradient-layer gradient-2"></div>
            <div class="gradient-layer gradient-3"></div>
        </div>
        
        <!-- フローティングエレメント -->
        <div class="floating-elements">
            <div class="floating-element element-1"><i class="fas fa-pen-nib"></i></div>
            <div class="floating-element element-2"><i class="fas fa-lightbulb"></i></div>
            <div class="floating-element element-3"><i class="fas fa-rocket"></i></div>
            <div class="floating-element element-4"><i class="fas fa-code"></i></div>
            <div class="floating-element element-5"><i class="fas fa-chart-line"></i></div>
        </div>
        
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-sparkles"></i>
                    <span>Insights & Innovation</span>
                </div>
                
                <h1 class="hero-title">
                    <span class="title-word">知識と</span>
                    <span class="title-word gradient-text">インスピレーション</span>
                    <span class="title-word">の泉</span>
                </h1>
                
                <p class="hero-description">
                    ビジネスの成長を加速させる最新のトレンド、<br class="pc-only">
                    実践的なノウハウ、革新的なアイデアをお届けします
                </p>
                
                <!-- 統計情報 -->
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number" data-count="<?php echo wp_count_posts()->publish; ?>">0</span>
                        <span class="stat-label">記事数</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" data-count="<?php echo count(get_categories()); ?>">0</span>
                        <span class="stat-label">カテゴリー</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" data-count="<?php echo count(get_tags()); ?>">0</span>
                        <span class="stat-label">タグ</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- スクロールインジケーター -->
        <div class="scroll-indicator">
            <div class="mouse-icon">
                <div class="mouse-wheel"></div>
            </div>
        </div>
    </section>
    
    <!-- 検索セクション -->
    <section class="blog-search-section">
        <div class="container">
            <div class="search-container">
                <!-- 検索フォーム -->
                <form class="blog-search-form" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <div class="search-form-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="search" 
                               name="s" 
                               class="search-input" 
                               placeholder="記事を検索... (キーワード、カテゴリー、タグ)" 
                               value="<?php echo get_search_query(); ?>"
                               autocomplete="off">
                        <button type="submit" class="search-submit">
                            <span class="button-text">検索</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </form>
                
                <!-- 詳細フィルターボタン -->
                <button class="filter-toggle" type="button">
                    <i class="fas fa-filter"></i>
                    <span>詳細フィルター</span>
                </button>
                
                <!-- 検索サジェスト -->
                <div class="search-suggestions hidden">
                    <div class="suggestions-header">
                        <span>人気の検索キーワード</span>
                    </div>
                    <div class="suggestions-list">
                        <a href="?s=マーケティング" class="suggestion-item">マーケティング</a>
                        <a href="?s=SEO" class="suggestion-item">SEO</a>
                        <a href="?s=デザイン" class="suggestion-item">デザイン</a>
                        <a href="?s=AI" class="suggestion-item">AI</a>
                        <a href="?s=DX" class="suggestion-item">DX</a>
                    </div>
                </div>
                
                <!-- フィルターパネル -->
                <div class="filter-panel hidden">
                    <div class="filter-panel-inner">
                        <div class="filter-group">
                            <h4>カテゴリー</h4>
                            <div class="filter-items">
                                <?php
                                $categories = get_categories();
                                foreach ($categories as $category) :
                                ?>
                                    <label class="filter-checkbox">
                                        <input type="checkbox" name="category" value="<?php echo $category->term_id; ?>">
                                        <span><?php echo $category->name; ?></span>
                                        <small>(<?php echo $category->count; ?>)</small>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="filter-group">
                            <h4>期間</h4>
                            <div class="filter-items">
                                <label class="filter-radio">
                                    <input type="radio" name="period" value="all" checked>
                                    <span>すべて</span>
                                </label>
                                <label class="filter-radio">
                                    <input type="radio" name="period" value="week">
                                    <span>過去1週間</span>
                                </label>
                                <label class="filter-radio">
                                    <input type="radio" name="period" value="month">
                                    <span>過去1ヶ月</span>
                                </label>
                                <label class="filter-radio">
                                    <input type="radio" name="period" value="year">
                                    <span>過去1年</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="filter-actions">
                            <button type="button" class="apply-filters">フィルターを適用</button>
                            <button type="button" class="clear-filters">クリア</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ブログコンテンツセクション -->
    <section class="blog-content-modern">
        <div class="container">
            
            <?php if ( have_posts() ) : ?>
                
                <!-- ソートオプション -->
                <div class="sort-options">
                    <div class="sort-label">並び替え：</div>
                    <select class="sort-select" id="sortPosts">
                        <option value="date">新着順</option>
                        <option value="popular">人気順</option>
                        <option value="title">タイトル順</option>
                        <option value="comments">コメント数順</option>
                    </select>
                    
                    <!-- ビュー切り替え -->
                    <div class="view-options">
                        <button class="view-option active" data-view="grid">
                            <i class="fas fa-th"></i>
                        </button>
                        <button class="view-option" data-view="list">
                            <i class="fas fa-list"></i>
                        </button>
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
                    the_posts_pagination( array(
                        'mid_size'  => 2,
                        'prev_text' => '<i class="fas fa-chevron-left"></i><span>前へ</span>',
                        'next_text' => '<span>次へ</span><i class="fas fa-chevron-right"></i>',
                        'before_page_number' => '<span class="page-label">',
                        'after_page_number' => '</span>'
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
    
    <!-- ニュースレター登録セクション -->
    <section class="newsletter-section">
        <div class="container">
            <div class="newsletter-wrapper">
                <div class="newsletter-content">
                    <div class="newsletter-icon">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <h3>最新情報をメールでお届け</h3>
                    <p>ビジネスに役立つ情報を定期的に配信します</p>
                </div>
                <form class="newsletter-form">
                    <input type="email" placeholder="メールアドレスを入力" required>
                    <button type="submit">
                        <span>登録する</span>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </section>
    
</main>

<?php get_footer(); ?>