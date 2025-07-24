<?php
/**
 * 実績詳細ページテンプレート
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main single-work">
    <?php while ( have_posts() ) : the_post(); ?>
        
        <!-- ヒーローセクション -->
        <section class="work-detail-hero">
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="hero-image-container">
                    <?php the_post_thumbnail( 'full', array( 'class' => 'hero-image' ) ); ?>
                    <div class="hero-overlay"></div>
                </div>
            <?php endif; ?>
            
            <div class="hero-content-wrapper">
                <div class="container">
                    <div class="hero-content">
                        <!-- パンくずリスト -->
                        <nav class="work-breadcrumb">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">ホーム</a>
                            <span class="separator">/</span>
                            <a href="<?php echo esc_url( get_post_type_archive_link( 'work' ) ); ?>">実績一覧</a>
                            <span class="separator">/</span>
                            <span class="current"><?php the_title(); ?></span>
                        </nav>
                        
                        <!-- カテゴリー -->
                        <?php
                        $categories = get_the_terms( get_the_ID(), 'work_category' );
                        if ( $categories && ! is_wp_error( $categories ) ) : ?>
                            <div class="work-categories">
                                <?php foreach ( $categories as $category ) : ?>
                                    <span class="category-badge"><?php echo esc_html( $category->name ); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- タイトル -->
                        <h1 class="work-title"><?php the_title(); ?></h1>
                        
                        <!-- クライアント情報 -->
                        <?php if ( function_exists('get_field') && get_field('work_client') ) : ?>
                            <div class="client-info">
                                <span class="client-label">Client</span>
                                <span class="client-name"><?php the_field('work_client'); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <!-- プロジェクト期間 -->
                        <?php if ( function_exists('get_field') && get_field('work_duration') ) : ?>
                            <div class="project-duration">
                                <i class="fas fa-calendar-alt"></i>
                                <span><?php the_field('work_duration'); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="hero-decoration">
                <div class="decoration-shape-1"></div>
                <div class="decoration-shape-2"></div>
            </div>
        </section>

        <!-- プロジェクト概要 -->
        <section class="work-overview">
            <div class="container">
                <div class="overview-grid">
                    <div class="overview-main">
                        <h2 class="section-title">
                            <span class="title-en">Project Overview</span>
                            <span class="title-ja">プロジェクト概要</span>
                        </h2>
                        
                        <div class="overview-content">
                            <?php the_content(); ?>
                        </div>
                    </div>
                    
                    <div class="overview-sidebar">
                        <!-- プロジェクト情報 -->
                        <div class="project-info-card">
                            <h3 class="card-title">プロジェクト情報</h3>
                            
                            <?php if ( function_exists('get_field') ) : ?>
                                <?php if ( get_field('work_url') ) : ?>
                                    <div class="info-item">
                                        <span class="info-label">URL</span>
                                        <a href="<?php the_field('work_url'); ?>" target="_blank" rel="noopener noreferrer" class="info-value link">
                                            <?php the_field('work_url'); ?>
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( get_field('work_team_size') ) : ?>
                                    <div class="info-item">
                                        <span class="info-label">チーム規模</span>
                                        <span class="info-value"><?php the_field('work_team_size'); ?>名</span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( get_field('work_role') ) : ?>
                                    <div class="info-item">
                                        <span class="info-label">担当領域</span>
                                        <span class="info-value"><?php the_field('work_role'); ?></span>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        
                        <!-- 使用技術 -->
                        <?php if ( function_exists('get_field') && get_field('work_technologies') ) : ?>
                            <div class="technologies-card">
                                <h3 class="card-title">使用技術</h3>
                                <div class="tech-tags">
                                    <?php 
                                    $technologies = get_field('work_technologies');
                                    $tech_array = is_array($technologies) ? $technologies : explode(',', $technologies);
                                    foreach ( $tech_array as $tech ) : ?>
                                        <span class="tech-tag">
                                            <i class="fas fa-code"></i>
                                            <?php echo esc_html( trim($tech) ); ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- 課題と解決策 -->
        <?php if ( function_exists('get_field') && (get_field('work_challenge') || get_field('work_solution')) ) : ?>
        <section class="work-challenge-solution">
            <div class="container">
                <div class="cs-grid">
                    <?php if ( get_field('work_challenge') ) : ?>
                        <div class="challenge-card">
                            <div class="card-icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <h3 class="card-title">課題</h3>
                            <div class="card-content">
                                <?php the_field('work_challenge'); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( get_field('work_solution') ) : ?>
                        <div class="solution-card">
                            <div class="card-icon">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <h3 class="card-title">解決策</h3>
                            <div class="card-content">
                                <?php the_field('work_solution'); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- プロジェクトギャラリー -->
        <?php if ( function_exists('get_field') && get_field('work_gallery') ) : ?>
        <section class="work-gallery">
            <div class="container">
                <h2 class="section-title text-center">
                    <span class="title-en">Gallery</span>
                    <span class="title-ja">プロジェクトギャラリー</span>
                </h2>
                
                <div class="gallery-grid">
                    <?php 
                    $images = get_field('work_gallery');
                    foreach ( $images as $image ) : ?>
                        <div class="gallery-item">
                            <a href="<?php echo esc_url( $image['url'] ); ?>" class="gallery-link" data-lightbox="work-gallery">
                                <img src="<?php echo esc_url( $image['sizes']['large'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>">
                                <div class="gallery-overlay">
                                    <i class="fas fa-search-plus"></i>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- 成果・実績 -->
        <?php if ( function_exists('get_field') && get_field('work_results') ) : ?>
        <section class="work-results">
            <div class="container">
                <h2 class="section-title text-center">
                    <span class="title-en">Results</span>
                    <span class="title-ja">成果・実績</span>
                </h2>
                
                <div class="results-content">
                    <?php the_field('work_results'); ?>
                </div>
                
                <?php if ( get_field('work_stats') ) : ?>
                    <div class="results-stats">
                        <?php 
                        $stats = get_field('work_stats');
                        foreach ( $stats as $stat ) : ?>
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="<?php echo esc_attr( $stat['icon'] ?? 'fas fa-chart-line' ); ?>"></i>
                                </div>
                                <div class="stat-number" data-count="<?php echo esc_attr( $stat['number'] ); ?>">0</div>
                                <div class="stat-label"><?php echo esc_html( $stat['label'] ); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- お客様の声 -->
        <?php if ( function_exists('get_field') && get_field('work_testimonial') ) : ?>
        <section class="work-testimonial">
            <div class="container">
                <div class="testimonial-card">
                    <div class="quote-icon">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    
                    <div class="testimonial-content">
                        <?php the_field('work_testimonial'); ?>
                    </div>
                    
                    <?php if ( get_field('work_testimonial_author') ) : ?>
                        <div class="testimonial-author">
                            <div class="author-info">
                                <div class="author-name"><?php the_field('work_testimonial_author'); ?></div>
                                <?php if ( get_field('work_testimonial_position') ) : ?>
                                    <div class="author-position"><?php the_field('work_testimonial_position'); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- 関連実績 -->
        <?php
        $related_works = new WP_Query( array(
            'post_type' => 'work',
            'posts_per_page' => 3,
            'post__not_in' => array( get_the_ID() ),
            'orderby' => 'rand'
        ) );
        
        if ( $related_works->have_posts() ) : ?>
            <section class="related-works">
                <div class="container">
                    <h2 class="section-title text-center">
                        <span class="title-en">Related Works</span>
                        <span class="title-ja">関連実績</span>
                    </h2>
                    
                    <div class="related-works-grid">
                        <?php while ( $related_works->have_posts() ) : $related_works->the_post(); ?>
                            <article class="related-work-card">
                                <a href="<?php the_permalink(); ?>" class="related-work-link">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <div class="related-work-image">
                                            <?php the_post_thumbnail( 'medium_large' ); ?>
                                            <div class="image-overlay">
                                                <span class="overlay-icon"><i class="fas fa-arrow-right"></i></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="related-work-content">
                                        <?php
                                        $categories = get_the_terms( get_the_ID(), 'work_category' );
                                        if ( $categories && ! is_wp_error( $categories ) ) : ?>
                                            <div class="work-category">
                                                <?php echo esc_html( $categories[0]->name ); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <h3 class="related-work-title"><?php the_title(); ?></h3>
                                        
                                        <?php if ( function_exists('get_field') && get_field('work_client') ) : ?>
                                            <div class="related-work-client"><?php the_field('work_client'); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            </article>
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>

        <!-- CTA -->
        <section class="work-detail-cta">
            <div class="cta-bg-wrapper">
                <div class="cta-bg-gradient"></div>
                <div class="cta-bg-pattern"></div>
            </div>
            
            <div class="container">
                <div class="cta-content">
                    <h2 class="cta-title">
                        <span class="title-main">次はあなたのプロジェクトを</span>
                        <span class="title-sub">成功へ導きます</span>
                    </h2>
                    
                    <p class="cta-description">
                        実績と経験を活かし、お客様のビジネスに最適なソリューションをご提供します。
                    </p>
                    
                    <div class="cta-buttons">
                        <a href="<?php echo esc_url( get_contact_page_url() ); ?>" class="cta-button primary">
                            <span class="button-text">プロジェクトの相談をする</span>
                            <span class="button-icon"><i class="fas fa-arrow-right"></i></span>
                        </a>
                        
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'work' ) ); ?>" class="cta-button secondary">
                            <span class="button-text">他の実績を見る</span>
                            <span class="button-icon"><i class="fas fa-chevron-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>