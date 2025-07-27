<?php
/**
 * ブログ記事詳細ページテンプレート
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main single-post">
    <!-- スクロールインジケーター -->
    <div class="scroll-indicator" id="scrollIndicator">
        <div class="scroll-indicator-bar" id="scrollIndicatorBar"></div>
    </div>
    
    <?php while ( have_posts() ) : the_post(); ?>
        
        <!-- ヒーローセクション -->
        <article id="post-<?php the_ID(); ?>" <?php post_class('post-single'); ?>>
            <header class="entry-hero">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="entry-hero-image">
                        <?php the_post_thumbnail('full', array('loading' => 'eager')); ?>
                        <div class="entry-hero-overlay"></div>
                    </div>
                <?php endif; ?>
                
                <div class="entry-hero-content">
                    <div class="container-narrow">
                        <!-- カテゴリーバッジ -->
                        <div class="entry-categories">
                            <?php
                            $categories = get_the_category();
                            if ( ! empty( $categories ) ) {
                                foreach ( $categories as $category ) {
                                    echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" class="category-badge">' . esc_html( $category->name ) . '</a>';
                                }
                            }
                            ?>
                        </div>
                        
                        <!-- タイトル -->
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                        
                        <!-- メタ情報 -->
                        <div class="entry-meta">
                            <div class="meta-author">
                                <?php echo get_avatar( get_the_author_meta( 'ID' ), 48 ); ?>
                                <div class="author-info">
                                    <span class="author-name"><?php the_author(); ?></span>
                                    <time class="entry-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                        <?php echo get_the_date(); ?>
                                    </time>
                                </div>
                            </div>
                            
                            <div class="meta-stats">
                                <span class="reading-time">
                                    <i class="far fa-clock"></i>
                                    <span><?php echo corporate_seo_get_reading_time(); ?></span>
                                </span>
                                <?php if ( comments_open() ) : ?>
                                    <span class="comment-count">
                                        <i class="far fa-comment"></i>
                                        <span><?php comments_number( '0', '1', '%' ); ?></span>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- 記事本文 -->
            <div class="entry-content-wrapper">
                <div class="container">
                    <div class="content-area">
                        <!-- モバイル用目次 -->
                        <div class="mobile-toc" id="mobileToc">
                            <div class="toc-header" onclick="toggleMobileToc()">
                                <h3 class="toc-title">
                                    <i class="fas fa-list"></i>
                                    目次
                                </h3>
                                <button class="mobile-toc-toggle">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                            <nav class="toc-nav">
                                <ul class="toc-list" id="mobileTocList">
                                    <!-- 動的に生成 -->
                                </ul>
                            </nav>
                        </div>
                        
                        <!-- 記事本文 -->
                        <div class="entry-content">
                            <?php
                            the_content();
                        
                            wp_link_pages( array(
                                'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'ページ:', 'corporate-seo-pro' ) . '</span>',
                                'after'       => '</div>',
                                'link_before' => '<span>',
                                'link_after'  => '</span>',
                                'pagelink'    => '<span class="screen-reader-text">' . __( 'ページ', 'corporate-seo-pro' ) . ' </span>%',
                                'separator'   => '<span class="screen-reader-text">, </span>',
                            ) );
                            ?>
                            
                            <!-- タグ -->
                            <?php
                            $tags = get_the_tags();
                            if ( $tags ) : ?>
                                <div class="entry-tags">
                                    <i class="fas fa-tags"></i>
                                    <?php
                                    foreach ( $tags as $tag ) {
                                        echo '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" class="tag-link">#' . esc_html( $tag->name ) . '</a>';
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 目次（固定サイドバー） -->
            <aside class="article-toc" id="articleToc">
                <div class="toc-inner">
                    <div class="toc-header">
                        <h3 class="toc-title">
                            <i class="fas fa-list"></i>
                            目次
                        </h3>
                    </div>
                    <nav class="toc-nav">
                        <ul class="toc-list" id="tocList">
                            <!-- 動的に生成 -->
                        </ul>
                    </nav>
                    <div class="toc-progress">
                        <div class="toc-progress-bar" id="tocProgressBar"></div>
                    </div>
                </div>
            </aside>

            <!-- CTAセクション -->
            <div class="blog-cta-section">
                <div class="container-narrow">
                    <div class="blog-cta-wrapper">
                        <div class="blog-cta-bg">
                            <div class="cta-gradient-1"></div>
                            <div class="cta-gradient-2"></div>
                            <div class="cta-pattern"></div>
                        </div>
                        
                        <div class="blog-cta-content">
                            <div class="cta-icon">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            
                            <h3 class="cta-title">
                                ビジネスの成長をサポートします
                            </h3>
                            
                            <p class="cta-description">
                                Harmonic Societyは、最新のテクノロジーとクリエイティブな発想で、<br class="pc-only">
                                お客様のビジネス課題を解決します。
                            </p>
                            
                            <div class="cta-features">
                                <div class="cta-feature">
                                    <i class="fas fa-check-circle"></i>
                                    <span>豊富な実績と経験</span>
                                </div>
                                <div class="cta-feature">
                                    <i class="fas fa-check-circle"></i>
                                    <span>最新技術への対応</span>
                                </div>
                                <div class="cta-feature">
                                    <i class="fas fa-check-circle"></i>
                                    <span>親身なサポート体制</span>
                                </div>
                            </div>
                            
                            <div class="cta-buttons">
                                <a href="<?php echo esc_url( get_contact_page_url() ); ?>" class="cta-button primary">
                                    <span>無料相談を申し込む</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                                <a href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ); ?>" class="cta-button secondary">
                                    <span>サービスを見る</span>
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- シェアボタン -->
            <?php if ( get_theme_mod( 'show_share_buttons', true ) ) : ?>
                <div class="share-section">
                    <div class="container-narrow">
                        <div class="share-wrapper">
                            <h3 class="share-title"><?php esc_html_e( 'この記事をシェア', 'corporate-seo-pro' ); ?></h3>
                            <div class="share-buttons">
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>&text=<?php echo urlencode( get_the_title() ); ?>" 
                                   class="share-button share-twitter" 
                                   target="_blank" 
                                   rel="noopener noreferrer">
                                    <i class="fab fa-twitter"></i>
                                    <span>Twitter</span>
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" 
                                   class="share-button share-facebook" 
                                   target="_blank" 
                                   rel="noopener noreferrer">
                                    <i class="fab fa-facebook-f"></i>
                                    <span>Facebook</span>
                                </a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode( get_permalink() ); ?>&title=<?php echo urlencode( get_the_title() ); ?>" 
                                   class="share-button share-linkedin" 
                                   target="_blank" 
                                   rel="noopener noreferrer">
                                    <i class="fab fa-linkedin-in"></i>
                                    <span>LinkedIn</span>
                                </a>
                                <a href="https://line.me/R/msg/text/?<?php echo urlencode( get_the_title() . ' ' . get_permalink() ); ?>" 
                                   class="share-button share-line" 
                                   target="_blank" 
                                   rel="noopener noreferrer">
                                    <i class="fab fa-line"></i>
                                    <span>LINE</span>
                                </a>
                                <button class="share-button share-copy" data-url="<?php echo esc_url( get_permalink() ); ?>">
                                    <i class="fas fa-link"></i>
                                    <span><?php esc_html_e( 'リンクをコピー', 'corporate-seo-pro' ); ?></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- 著者情報 -->
            <div class="author-section">
                <div class="container-narrow">
                    <div class="author-box">
                        <div class="author-avatar">
                            <?php echo get_avatar( get_the_author_meta( 'ID' ), 120 ); ?>
                        </div>
                        <div class="author-details">
                            <h3 class="author-name">
                                <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
                                    <?php the_author(); ?>
                                </a>
                            </h3>
                            <?php if ( get_the_author_meta( 'description' ) ) : ?>
                                <p class="author-bio"><?php the_author_meta( 'description' ); ?></p>
                            <?php endif; ?>
                            <div class="author-social">
                                <?php
                                $author_socials = array(
                                    'twitter' => get_the_author_meta( 'twitter' ),
                                    'facebook' => get_the_author_meta( 'facebook' ),
                                    'linkedin' => get_the_author_meta( 'linkedin' ),
                                );
                                
                                foreach ( $author_socials as $platform => $url ) {
                                    if ( $url ) {
                                        echo '<a href="' . esc_url( $url ) . '" class="author-social-link" target="_blank" rel="noopener noreferrer">';
                                        echo '<i class="fab fa-' . esc_attr( $platform ) . '"></i>';
                                        echo '</a>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 関連記事 -->
            <?php if ( get_theme_mod( 'show_related_posts', true ) ) : ?>
                <div class="related-posts-section">
                    <div class="container">
                        <h2 class="section-title"><?php esc_html_e( '関連記事', 'corporate-seo-pro' ); ?></h2>
                        <?php
                        $related_posts = corporate_seo_pro_get_related_posts( get_the_ID(), 3 );
                        if ( $related_posts->have_posts() ) : ?>
                            <div class="related-posts-grid">
                                <?php while ( $related_posts->have_posts() ) : $related_posts->the_post(); ?>
                                    <article class="related-post">
                                        <a href="<?php the_permalink(); ?>" class="related-post-link">
                                            <?php if ( has_post_thumbnail() ) : ?>
                                                <div class="related-post-image">
                                                    <?php the_post_thumbnail( 'corporate-featured', array( 'loading' => 'lazy' ) ); ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="related-post-content">
                                                <div class="related-post-meta">
                                                    <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                                        <?php echo get_the_date(); ?>
                                                    </time>
                                                </div>
                                                <h3 class="related-post-title"><?php the_title(); ?></h3>
                                                <p class="related-post-excerpt">
                                                    <?php echo wp_trim_words( get_the_excerpt(), 15 ); ?>
                                                </p>
                                            </div>
                                        </a>
                                    </article>
                                <?php endwhile; ?>
                            </div>
                            <?php wp_reset_postdata(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- コメント -->
            <?php if ( comments_open() || get_comments_number() ) : ?>
                <div class="comments-section">
                    <div class="container-narrow">
                        <?php comments_template(); ?>
                    </div>
                </div>
            <?php endif; ?>

        </article>

        <!-- 前後の記事ナビゲーション -->
        <nav class="post-navigation">
            <div class="container">
                <div class="nav-links">
                    <?php
                    $prev_post = get_previous_post();
                    $next_post = get_next_post();
                    ?>
                    
                    <?php if ( $prev_post ) : ?>
                        <div class="nav-previous">
                            <a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>" rel="prev">
                                <span class="nav-subtitle"><?php esc_html_e( '前の記事', 'corporate-seo-pro' ); ?></span>
                                <span class="nav-title"><?php echo esc_html( get_the_title( $prev_post ) ); ?></span>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( $next_post ) : ?>
                        <div class="nav-next">
                            <a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" rel="next">
                                <span class="nav-subtitle"><?php esc_html_e( '次の記事', 'corporate-seo-pro' ); ?></span>
                                <span class="nav-title"><?php echo esc_html( get_the_title( $next_post ) ); ?></span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

    <?php endwhile; ?>
</main>

<?php get_footer();