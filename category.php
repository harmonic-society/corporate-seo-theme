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
    
    <!-- 検索セクション -->
    <section class="blog-search-section">
        <div class="container">
            <div class="search-wrapper">
                <form class="blog-search-form" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <div class="search-inner">
                        <div class="search-input-wrapper">
                            <input type="search" 
                                   name="s" 
                                   class="search-input" 
                                   placeholder="キーワードを入力してください..." 
                                   value="<?php echo get_search_query(); ?>"
                                   autocomplete="off">
                            <input type="hidden" name="post_type" value="post">
                            <button type="submit" class="search-submit">
                                <i class="fas fa-search"></i>
                                <span class="submit-text">検索</span>
                            </button>
                        </div>
                        
                        <!-- カテゴリーフィルター -->
                        <div class="search-filters">
                            <div class="filter-label">カテゴリー:</div>
                            <div class="category-filters">
                                <label class="category-filter">
                                    <input type="radio" name="category_name" value="">
                                    <span class="filter-text">すべて</span>
                                </label>
                                <?php
                                $categories = get_categories();
                                foreach ( $categories as $cat ) :
                                ?>
                                    <label class="category-filter <?php echo ($category->term_id === $cat->term_id) ? 'active' : ''; ?>">
                                        <input type="radio" name="category_name" value="<?php echo esc_attr( $cat->slug ); ?>" <?php echo ($category->term_id === $cat->term_id) ? 'checked' : ''; ?>>
                                        <span class="filter-text"><?php echo esc_html( $cat->name ); ?></span>
                                        <span class="filter-count"><?php echo esc_html( $cat->count ); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- ソートオプション -->
                        <div class="search-options">
                            <div class="sort-options">
                                <label class="sort-label">並び替え:</label>
                                <select name="orderby" class="sort-select">
                                    <option value="date">新しい順</option>
                                    <option value="title">タイトル順</option>
                                    <option value="comment_count">人気順</option>
                                </select>
                            </div>
                            
                            <div class="view-options">
                                <button type="button" class="view-option active" data-view="grid">
                                    <i class="fas fa-th"></i>
                                </button>
                                <button type="button" class="view-option" data-view="list">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 検索候補表示エリア -->
                    <div class="search-suggestions hidden">
                        <div class="suggestions-list"></div>
                    </div>
                </form>
                
                <!-- 人気タグ -->
                <div class="popular-tags">
                    <h3 class="tags-title"><i class="fas fa-tags"></i> 人気のタグ</h3>
                    <div class="tags-list">
                        <?php
                        $tags = get_tags( array(
                            'orderby' => 'count',
                            'order' => 'DESC',
                            'number' => 10
                        ) );
                        foreach ( $tags as $tag ) :
                        ?>
                            <a href="<?php echo get_tag_link( $tag->term_id ); ?>" class="tag-item">
                                <span class="tag-name"><?php echo esc_html( $tag->name ); ?></span>
                                <span class="tag-count"><?php echo esc_html( $tag->count ); ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="news-section">
        <div class="container">
            <!-- カテゴリー情報 -->
            <div class="category-info">
                <h2 class="category-title">
                    <i class="fas fa-folder"></i>
                    「<?php single_cat_title(); ?>」の記事一覧
                </h2>
                <p class="category-count">
                    <?php echo esc_html( $category->count ); ?>件の記事があります
                </p>
            </div>
            
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
                    <p><?php _e( 'このカテゴリーには記事がありません。', 'corporate-seo-pro' ); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>