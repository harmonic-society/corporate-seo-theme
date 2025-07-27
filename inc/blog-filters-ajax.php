<?php
/**
 * ブログフィルターAjax処理
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Ajaxフィルター処理
 */
function corporate_seo_pro_filter_blog_posts() {
    // Nonceチェック
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'blog_filter_nonce' ) ) {
        wp_die( 'Security check failed' );
    }
    
    // フィルターデータの取得
    $filters = isset( $_POST['filters'] ) ? json_decode( stripslashes( $_POST['filters'] ), true ) : array();
    
    // クエリ引数の構築
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => get_option( 'posts_per_page' ),
        'paged' => isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 1,
    );
    
    // カテゴリーフィルター
    if ( ! empty( $filters['categories'] ) && is_array( $filters['categories'] ) ) {
        $args['category__in'] = array_map( 'intval', $filters['categories'] );
    }
    
    // 期間フィルター
    if ( ! empty( $filters['period'] ) && $filters['period'] !== 'all' ) {
        $date_query = array();
        
        switch ( $filters['period'] ) {
            case 'week':
                $date_query[] = array(
                    'after' => '1 week ago',
                );
                break;
            case 'month':
                $date_query[] = array(
                    'after' => '1 month ago',
                );
                break;
            case 'year':
                $date_query[] = array(
                    'after' => '1 year ago',
                );
                break;
        }
        
        if ( ! empty( $date_query ) ) {
            $args['date_query'] = $date_query;
        }
    }
    
    // 検索キーワード
    if ( ! empty( $filters['search'] ) ) {
        $args['s'] = sanitize_text_field( $filters['search'] );
    }
    
    // ソート順
    if ( ! empty( $filters['sort'] ) ) {
        switch ( $filters['sort'] ) {
            case 'date':
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
                break;
            case 'popular':
                $args['orderby'] = 'comment_count';
                $args['order'] = 'DESC';
                break;
            case 'title':
                $args['orderby'] = 'title';
                $args['order'] = 'ASC';
                break;
            case 'comments':
                $args['orderby'] = 'comment_count';
                $args['order'] = 'DESC';
                break;
        }
    }
    
    // クエリ実行
    $query = new WP_Query( $args );
    
    // HTML生成
    ob_start();
    
    if ( $query->have_posts() ) {
        $post_index = 0;
        while ( $query->have_posts() ) {
            $query->the_post();
            $post_index++;
            ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-article' ); ?> data-aos="fade-up" data-aos-delay="<?php echo $post_index * 50; ?>">
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
            
            <?php
        }
    } else {
        ?>
        <div class="no-posts-found">
            <i class="fas fa-search"></i>
            <h3>検索条件に一致する記事が見つかりませんでした</h3>
            <p>他の条件で検索してみてください。</p>
        </div>
        <?php
    }
    
    $html = ob_get_clean();
    
    // ページネーションHTML
    ob_start();
    
    $big = 999999999;
    echo paginate_links( array(
        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format' => '?paged=%#%',
        'current' => max( 1, $args['paged'] ),
        'total' => $query->max_num_pages,
        'mid_size' => 2,
        'prev_text' => '<i class="fas fa-chevron-left"></i><span>前へ</span>',
        'next_text' => '<span>次へ</span><i class="fas fa-chevron-right"></i>',
        'before_page_number' => '<span class="page-label">',
        'after_page_number' => '</span>'
    ) );
    
    $pagination = ob_get_clean();
    
    wp_reset_postdata();
    
    // JSON レスポンス
    wp_send_json_success( array(
        'html' => $html,
        'pagination' => $pagination,
        'found_posts' => $query->found_posts,
        'max_pages' => $query->max_num_pages,
    ) );
}
add_action( 'wp_ajax_filter_blog_posts', 'corporate_seo_pro_filter_blog_posts' );
add_action( 'wp_ajax_nopriv_filter_blog_posts', 'corporate_seo_pro_filter_blog_posts' );

/**
 * 読了時間を計算する関数（ヘルパー関数）
 */
if ( ! function_exists( 'corporate_seo_get_reading_time' ) ) {
    function corporate_seo_get_reading_time() {
        $content = get_the_content();
        $word_count = str_word_count( strip_tags( $content ) );
        $reading_time = ceil( $word_count / 200 ); // 1分間に200単語読むと仮定
        
        // 日本語の場合は文字数ベースで計算
        if ( function_exists( 'mb_strlen' ) ) {
            $char_count = mb_strlen( strip_tags( $content ), 'UTF-8' );
            $reading_time = ceil( $char_count / 400 ); // 1分間に400文字読むと仮定
        }
        
        return $reading_time . '分';
    }
}