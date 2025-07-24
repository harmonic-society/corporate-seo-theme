<?php
/**
 * フロントページテンプレート
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main">
    
    <!-- Hero Section - Harmonic Society Style -->
    <?php if ( get_theme_mod( 'show_hero_section', true ) ) : ?>
    <?php 
    $hero_bg_image = get_theme_mod( 'hero_background_image', 'https://harmonic-society.co.jp/wp-content/uploads/2024/10/GettyImages-981641584-scaled.jpg' );
    // デバッグ: 画像URLを確認
    ?>
    <!-- Hero Background Image URL: <?php echo esc_html( $hero_bg_image ); ?> -->
    <section class="hero-section harmonic-hero" <?php if ( $hero_bg_image ) : ?>style="background-image: url('<?php echo esc_url( $hero_bg_image ); ?>') !important;"<?php endif; ?>>
        <div class="hero-bg-pattern">
            <div class="hero-gradient-primary"></div>
            <div class="hero-gradient-secondary"></div>
            <div class="geometric-pattern"></div>
        </div>
        
        <div class="hero-content">
            <div class="container">
                <div class="hero-main">
                    <div class="hero-badge">
                        <span class="badge-icon">✨</span>
                        <span class="badge-text">Innovation & Harmony</span>
                    </div>
                    
                    <h1 class="hero-title">
                        <?php 
                        $hero_title = get_theme_mod( 'hero_title', 'ビジネスと社会の調和を創造する' );
                        // タイトルを2行に分割する処理
                        if ( strpos( $hero_title, '調和' ) !== false ) {
                            $parts = explode( '調和', $hero_title, 2 );
                            echo '<span class="title-line-1">' . esc_html( $parts[0] ) . '</span>';
                            echo '<span class="title-line-2" data-text="調和' . esc_attr( $parts[1] ) . '">調和' . esc_html( $parts[1] ) . '</span>';
                        } else {
                            echo '<span class="title-line-1">' . esc_html( $hero_title ) . '</span>';
                        }
                        ?>
                    </h1>
                    
                    <p class="hero-lead">
                        <?php echo esc_html( get_theme_mod( 'hero_description', 'テクノロジーと人間性の融合で、持続可能な成長を実現' ) ); ?>
                    </p>
                    
                    <div class="hero-features">
                        <div class="feature-item">
                            <i class="fas fa-rocket"></i>
                            <span>成長戦略</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-sync-alt"></i>
                            <span>持続可能性</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-users"></i>
                            <span>共創価値</span>
                        </div>
                    </div>
                    
                    <div class="hero-actions">
                        <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="btn-harmonic btn-primary">
                            <span class="btn-inner">
                                <span class="btn-text">無料相談を申し込む</span>
                                <span class="btn-arrow">→</span>
                            </span>
                        </a>
                        <a href="#services" class="btn-harmonic btn-secondary">
                            <span class="btn-inner">
                                <span class="btn-text">サービスを見る</span>
                            </span>
                        </a>
                    </div>
                    
                    <div class="hero-trust">
                        <p class="trust-text">
                            <span class="trust-icon">🛡️</span>
                            <span>500社以上の企業様に選ばれています</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="hero-bottom-wave">
            <svg viewBox="0 0 1440 120" preserveAspectRatio="none">
                <path d="M0,40 C480,100 960,100 1440,40 L1440,120 L0,120 Z"></path>
            </svg>
        </div>
    </section>
    <?php endif; ?>
    
    <?php if ( get_theme_mod( 'show_services_section', true ) ) : ?>
        <section class="services-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title"><?php echo esc_html( get_theme_mod( 'services_title', __( 'サービス', 'corporate-seo-pro' ) ) ); ?></h2>
                    <p class="section-description"><?php echo esc_html( get_theme_mod( 'services_description', __( '私たちは幅広いサービスを提供しています', 'corporate-seo-pro' ) ) ); ?></p>
                </div>

                <?php
                $services_query = new WP_Query( array(
                    'post_type'      => 'service',
                    'posts_per_page' => 6,
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                ) );

                if ( $services_query->have_posts() ) : ?>
                    <div class="services-list">
                        <?php 
                        $service_count = 0;
                        while ( $services_query->have_posts() ) : 
                            $services_query->the_post(); 
                            $service_count++;
                            $is_even = ($service_count % 2 == 0);
                        ?>
                            <article class="service-item <?php echo $is_even ? 'service-item-reverse' : ''; ?>">
                                <div class="service-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php if ( has_post_thumbnail() ) : ?>
                                            <?php the_post_thumbnail( 'corporate-featured', array( 'loading' => 'lazy' ) ); ?>
                                        <?php else : ?>
                                            <div class="service-placeholder-image">
                                                <i class="fas fa-cog"></i>
                                                <span class="placeholder-text">Service</span>
                                            </div>
                                        <?php endif; ?>
                                    </a>
                                </div>
                                <div class="service-content">
                                    <h3 class="service-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    <div class="service-excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    <div class="service-meta">
                                        <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                                            <?php esc_html_e( '詳細を見る', 'corporate-seo-pro' ); ?>
                                            <span aria-hidden="true">→</span>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'show_about_section', true ) ) : ?>
        <section class="about-section brand-statement">
            <div class="container">
                <div class="brand-statement-content">
                    <div class="brand-statement-header">
                        <span class="brand-statement-label"><?php esc_html_e( 'Our Philosophy', 'corporate-seo-pro' ); ?></span>
                        <h2 class="brand-statement-title">
                            <?php echo esc_html( get_theme_mod( 'about_title', __( '私たちについて', 'corporate-seo-pro' ) ) ); ?>
                        </h2>
                    </div>
                    
                    <div class="brand-statement-text">
                        <?php echo wp_kses_post( wpautop( get_theme_mod( 'about_content', __( 'ここに会社の説明文を入力してください。', 'corporate-seo-pro' ) ) ) ); ?>
                    </div>
                    
                    <?php if ( get_theme_mod( 'about_button_text' ) ) : ?>
                        <div class="brand-statement-action">
                            <a href="<?php echo esc_url( get_theme_mod( 'about_button_url', '#' ) ); ?>" class="btn btn-outline">
                                <?php echo esc_html( get_theme_mod( 'about_button_text' ) ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( get_theme_mod( 'about_signature' ) ) : ?>
                        <div class="brand-statement-signature">
                            <?php echo esc_html( get_theme_mod( 'about_signature' ) ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'show_features_section', true ) ) : ?>
        <section class="features-section">
            <div class="container">
                <div class="section-header">
                    <span class="section-label"><?php esc_html_e( 'Why Choose Us', 'corporate-seo-pro' ); ?></span>
                    <h2 class="section-title"><?php echo esc_html( get_theme_mod( 'features_title', __( '選ばれる理由', 'corporate-seo-pro' ) ) ); ?></h2>
                    <?php if ( get_theme_mod( 'features_description' ) ) : ?>
                        <p class="section-description"><?php echo esc_html( get_theme_mod( 'features_description' ) ); ?></p>
                    <?php endif; ?>
                </div>
                <div class="features-grid">
                    <?php for ( $i = 1; $i <= 3; $i++ ) : ?>
                        <?php if ( get_theme_mod( 'feature_' . $i . '_title' ) ) : ?>
                            <div class="feature-item" data-feature="<?php echo $i; ?>">
                                <div class="feature-circle">
                                    <div class="feature-number"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></div>
                                    <?php if ( get_theme_mod( 'feature_' . $i . '_icon' ) ) : ?>
                                        <i class="<?php echo esc_attr( get_theme_mod( 'feature_' . $i . '_icon' ) ); ?>"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="feature-content">
                                    <h3 class="feature-title"><?php echo esc_html( get_theme_mod( 'feature_' . $i . '_title' ) ); ?></h3>
                                    <p class="feature-description"><?php echo esc_html( get_theme_mod( 'feature_' . $i . '_description' ) ); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'show_news_section', true ) ) : ?>
        <section class="news-section">
            <div class="container">
                <div class="section-header">
                    <span class="section-label"><?php esc_html_e( 'Latest News', 'corporate-seo-pro' ); ?></span>
                    <h2 class="section-title"><?php echo esc_html( get_theme_mod( 'news_title', __( '最新情報', 'corporate-seo-pro' ) ) ); ?></h2>
                </div>

                <?php
                $news_query = new WP_Query( array(
                    'post_type'      => 'post',
                    'posts_per_page' => 3,
                ) );

                if ( $news_query->have_posts() ) : ?>
                    <div class="news-grid">
                        <?php 
                        $post_count = 0;
                        while ( $news_query->have_posts() ) : 
                            $news_query->the_post(); 
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
                    <?php wp_reset_postdata(); ?>
                    
                    <div class="section-footer">
                        <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="btn btn-primary">
                            <?php esc_html_e( 'すべての記事を見る', 'corporate-seo-pro' ); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'show_cta_section', true ) ) : ?>
        <section class="cta-section">
            <div class="cta-background">
                <div class="cta-gradient"></div>
                <div class="cta-pattern"></div>
                <div class="cta-glow"></div>
            </div>
            
            <div class="container">
                <div class="cta-content">
                    <div class="cta-badge">
                        <span><?php esc_html_e( 'Contact Us', 'corporate-seo-pro' ); ?></span>
                    </div>
                    
                    <h2 class="cta-title">
                        <?php echo esc_html( get_theme_mod( 'cta_title', __( 'お問い合わせはこちら', 'corporate-seo-pro' ) ) ); ?>
                    </h2>
                    
                    <p class="cta-description">
                        <?php echo esc_html( get_theme_mod( 'cta_description', __( 'お気軽にお問い合わせください', 'corporate-seo-pro' ) ) ); ?>
                    </p>
                    
                    <div class="cta-features">
                        <div class="cta-feature">
                            <i class="fas fa-clock"></i>
                            <span><?php esc_html_e( '24時間受付', 'corporate-seo-pro' ); ?></span>
                        </div>
                        <div class="cta-feature">
                            <i class="fas fa-comments"></i>
                            <span><?php esc_html_e( '無料相談', 'corporate-seo-pro' ); ?></span>
                        </div>
                        <div class="cta-feature">
                            <i class="fas fa-shield-alt"></i>
                            <span><?php esc_html_e( '秘密厳守', 'corporate-seo-pro' ); ?></span>
                        </div>
                    </div>
                    
                    <div class="cta-button-wrapper">
                        <a href="<?php echo esc_url( get_theme_mod( 'cta_button_url', get_contact_page_url() ) ); ?>" class="cta-button">
                            <span class="cta-button-text"><?php echo esc_html( get_theme_mod( 'cta_button_text', 'CONTACT US' ) ); ?></span>
                            <span class="cta-button-icon">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </a>
                        <div class="cta-button-shadow"></div>
                    </div>
                    
                    <div class="cta-phone">
                        <span><?php esc_html_e( 'お電話でのお問い合わせ', 'corporate-seo-pro' ); ?></span>
                        <a href="tel:<?php echo esc_attr( get_theme_mod( 'cta_phone', '080-6946-4006' ) ); ?>" class="cta-phone-number">
                            <i class="fas fa-phone"></i>
                            <?php echo esc_html( get_theme_mod( 'cta_phone', '080-6946-4006' ) ); ?>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="cta-particles">
                <div class="cta-particle"></div>
                <div class="cta-particle"></div>
                <div class="cta-particle"></div>
                <div class="cta-particle"></div>
                <div class="cta-particle"></div>
            </div>
        </section>
    <?php endif; ?>

</main>

<?php get_footer();