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
    // Hero section variables
    $hero_style = get_theme_mod( 'hero_style', 'image' );
    $hero_bg_image = get_theme_mod( 'hero_background_image', 'https://harmonic-society.co.jp/wp-content/uploads/2024/10/GettyImages-981641584-scaled.jpg' );
    $hero_bg_video = get_theme_mod( 'hero_background_video', '' );
    $hero_overlay = get_theme_mod( 'hero_overlay_opacity', '0.5' );
    $hero_animation = get_theme_mod( 'hero_animation', true );
    $hero_height = get_theme_mod( 'hero_height', 'default' );
    $hero_text_align = get_theme_mod( 'hero_text_align', 'center' );
    $hero_text_color = get_theme_mod( 'hero_text_color', '#ffffff' );
    $gradient_start = get_theme_mod( 'hero_gradient_start', '#10b981' );
    $gradient_end = get_theme_mod( 'hero_gradient_end', '#059669' );
    
    $hero_classes = 'hero-section harmonic-hero';
    $hero_classes .= ' hero-' . $hero_style;
    $hero_classes .= ' hero-height-' . $hero_height;
    $hero_classes .= $hero_animation ? ' animations-enabled' : '';
    ?>
    <section class="<?php echo esc_attr( $hero_classes ); ?>" style="color: <?php echo esc_attr( $hero_text_color ); ?>">
        <!-- 背景処理 -->
        <?php if ( $hero_style === 'image' ) : ?>
            <div class="hero-bg-image" style="background-image: url('<?php echo esc_url( $hero_bg_image ); ?>');"></div>
        <?php elseif ( $hero_style === 'gradient' ) : ?>
            <!-- 背景画像をグラデーションの下に表示 -->
            <div class="hero-bg-image" style="background-image: url('<?php echo esc_url( $hero_bg_image ); ?>');"></div>
        <?php endif; ?>
        
        <?php if ( $hero_style === 'video' && $hero_bg_video ) : ?>
            <div class="hero-video-container">
                <video autoplay muted loop playsinline>
                    <source src="<?php echo esc_url( $hero_bg_video ); ?>" type="video/mp4">
                </video>
            </div>
        <?php endif; ?>
        
        <?php if ( $hero_style === 'gradient' ) : ?>
            <div class="hero-gradient-overlay" style="background: linear-gradient(135deg, <?php echo esc_attr( $gradient_start ); ?> 0%, <?php echo esc_attr( $gradient_end ); ?> 100%); opacity: 0.9;"></div>
        <?php elseif ( $hero_style === 'particles' || $hero_style === 'geometric' ) : ?>
            <div class="hero-gradient-overlay" style="background: linear-gradient(135deg, <?php echo esc_attr( $gradient_start ); ?> 0%, <?php echo esc_attr( $gradient_end ); ?> 100%)"></div>
        <?php endif; ?>
        
        <?php if ( $hero_overlay !== '0' && ( $hero_style === 'image' || $hero_style === 'video' ) ) : ?>
            <div class="hero-overlay" style="opacity: <?php echo esc_attr( $hero_overlay ); ?>"></div>
        <?php endif; ?>
        
        <?php if ( $hero_style === 'particles' ) : ?>
            <div class="hero-particles" data-color="<?php echo esc_attr( get_theme_mod( 'hero_particles_color', '#ffffff' ) ); ?>"></div>
        <?php endif; ?>
        
        <?php if ( $hero_style === 'geometric' ) : ?>
            <div class="hero-geometric-pattern"></div>
        <?php endif; ?>
        
        <div class="hero-content" style="text-align: <?php echo esc_attr( $hero_text_align ); ?>">
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
                        <?php for ( $i = 1; $i <= 3; $i++ ) : ?>
                            <?php 
                            $feature_title = get_theme_mod( 'hero_feature_' . $i . '_title' );
                            $feature_desc = get_theme_mod( 'hero_feature_' . $i . '_desc' );
                            $feature_icon = get_theme_mod( 'hero_feature_' . $i . '_icon' );
                            
                            // デフォルト値
                            if ( $i == 1 && !$feature_title ) {
                                $feature_title = '成長戦略';
                                $feature_icon = 'fas fa-rocket';
                            } elseif ( $i == 2 && !$feature_title ) {
                                $feature_title = '持続可能性';
                                $feature_icon = 'fas fa-sync-alt';
                            } elseif ( $i == 3 && !$feature_title ) {
                                $feature_title = '共創価値';
                                $feature_icon = 'fas fa-users';
                            }
                            
                            if ( $feature_title ) :
                            ?>
                            <div class="hero-feature feature-item">
                                <div class="feature-icon">
                                    <?php if ( $feature_icon ) : ?>
                                        <i class="<?php echo esc_attr( $feature_icon ); ?>"></i>
                                    <?php endif; ?>
                                </div>
                                <h3><?php echo esc_html( $feature_title ); ?></h3>
                                <?php if ( $feature_desc ) : ?>
                                    <p><?php echo esc_html( $feature_desc ); ?></p>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    
                    <div class="hero-actions">
                        <?php 
                        $button1_text = get_theme_mod( 'hero_button_text', '無料相談を申し込む' );
                        $button1_url = get_theme_mod( 'hero_button_url', get_permalink( get_page_by_path( 'contact' ) ) );
                        $button2_text = get_theme_mod( 'hero_button2_text', 'サービスを見る' );
                        $button2_url = get_theme_mod( 'hero_button2_url', '#services' );
                        ?>
                        
                        <?php if ( $button1_text ) : ?>
                        <a href="<?php echo esc_url( $button1_url ); ?>" class="btn-harmonic btn-primary">
                            <span class="btn-inner">
                                <span class="btn-text"><?php echo esc_html( $button1_text ); ?></span>
                                <span class="btn-arrow">→</span>
                            </span>
                        </a>
                        <?php endif; ?>
                        
                        <?php if ( $button2_text ) : ?>
                        <a href="<?php echo esc_url( $button2_url ); ?>" class="btn-harmonic btn-secondary">
                            <span class="btn-inner">
                                <span class="btn-text"><?php echo esc_html( $button2_text ); ?></span>
                            </span>
                        </a>
                        <?php endif; ?>
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