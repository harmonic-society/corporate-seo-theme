<?php
/**
 * 404エラーページテンプレート
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main error-404-page">
    <div class="error-404-container">
        <!-- 背景アニメーション -->
        <div class="error-bg-animation">
            <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
                <div class="shape shape-4"></div>
                <div class="shape shape-5"></div>
            </div>
            <div class="grid-pattern"></div>
        </div>
        
        <!-- メインコンテンツ -->
        <div class="error-content">
            <!-- 404アニメーション -->
            <div class="error-number-wrapper">
                <div class="error-number">
                    <span class="digit digit-4">4</span>
                    <span class="digit digit-0">
                        <div class="zero-planet">
                            <div class="planet-surface"></div>
                            <div class="planet-ring"></div>
                        </div>
                    </span>
                    <span class="digit digit-4">4</span>
                </div>
                <div class="error-shadow"></div>
            </div>
            
            <!-- エラーメッセージ -->
            <div class="error-message">
                <h1 class="error-title">ページが見つかりません</h1>
                <p class="error-description">
                    お探しのページは移動または削除された可能性があります。<br>
                    URLをご確認いただくか、以下のオプションをお試しください。
                </p>
            </div>
            
            <!-- 検索フォーム -->
            <div class="error-search">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <div class="search-wrapper">
                        <input type="search" 
                               class="search-field" 
                               placeholder="サイト内を検索..." 
                               value="<?php echo get_search_query(); ?>" 
                               name="s" />
                        <button type="submit" class="search-submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- アクションボタン -->
            <div class="error-actions">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="error-button primary">
                    <i class="fas fa-home"></i>
                    <span>ホームに戻る</span>
                </a>
                <button onclick="history.back()" class="error-button secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span>前のページに戻る</span>
                </button>
            </div>
            
            <!-- よく見られているページ -->
            <div class="error-suggestions">
                <h2 class="suggestions-title">よく見られているページ</h2>
                <div class="suggestions-grid">
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ); ?>" class="suggestion-card">
                        <div class="suggestion-icon">
                            <i class="fas fa-concierge-bell"></i>
                        </div>
                        <h3>サービス一覧</h3>
                        <p>提供サービスをご覧ください</p>
                    </a>
                    
                    <a href="<?php echo esc_url( home_url( '/about' ) ); ?>" class="suggestion-card">
                        <div class="suggestion-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3>会社概要</h3>
                        <p>私たちについて</p>
                    </a>
                    
                    <a href="<?php echo esc_url( home_url( '/blog' ) ); ?>" class="suggestion-card">
                        <div class="suggestion-icon">
                            <i class="fas fa-blog"></i>
                        </div>
                        <h3>ブログ</h3>
                        <p>最新情報をチェック</p>
                    </a>
                    
                    <a href="<?php echo esc_url( get_contact_page_url() ); ?>" class="suggestion-card">
                        <div class="suggestion-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3>お問い合わせ</h3>
                        <p>お気軽にご相談ください</p>
                    </a>
                </div>
            </div>
            
            <!-- 最新のブログ記事 -->
            <div class="error-recent-posts">
                <h2 class="recent-posts-title">最新の記事</h2>
                <div class="recent-posts-grid">
                    <?php
                    $recent_posts = new WP_Query( array(
                        'posts_per_page' => 3,
                        'post_status' => 'publish',
                        'orderby' => 'date',
                        'order' => 'DESC'
                    ) );
                    
                    if ( $recent_posts->have_posts() ) :
                        while ( $recent_posts->have_posts() ) : $recent_posts->the_post(); ?>
                            <article class="recent-post-card">
                                <a href="<?php the_permalink(); ?>" class="post-link">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <div class="post-thumbnail">
                                            <?php the_post_thumbnail( 'medium' ); ?>
                                        </div>
                                    <?php else : ?>
                                        <div class="post-thumbnail no-image">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="post-content">
                                        <time class="post-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                            <?php echo get_the_date(); ?>
                                        </time>
                                        <h3 class="post-title"><?php the_title(); ?></h3>
                                    </div>
                                </a>
                            </article>
                        <?php endwhile;
                        wp_reset_postdata();
                    endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 装飾的な要素 -->
    <div class="error-decoration">
        <div class="decoration-text top-left">404</div>
        <div class="decoration-text bottom-right">ERROR</div>
    </div>
</main>

<?php get_footer(); ?>