<?php
/**
 * フロントページテンプレート
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main front-page-main">
    
    <!-- Hero Section -->
    <?php if ( get_theme_mod( 'show_hero_section', true ) ) : ?>
        <?php get_template_part( 'template-parts/hero', 'modern' ); ?>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'show_about_section', true ) ) : ?>
        <section class="about-section-modern">
            <div class="container">
                <div class="about-modern-wrapper about-centered">
                    <div class="about-content-side">
                        <div class="about-header-modern">
                            <span class="section-label"><?php esc_html_e( 'About Us', 'corporate-seo-pro' ); ?></span>
                            <h2 class="about-title-modern">
                                <span class="title-line"><?php echo esc_html( get_theme_mod( 'about_title', __( '私たちについて', 'corporate-seo-pro' ) ) ); ?></span>
                            </h2>
                        </div>

                        <div class="about-text-modern">
                            <?php echo wp_kses_post( wpautop( get_theme_mod( 'about_content', __( 'ここに会社の説明文を入力してください。', 'corporate-seo-pro' ) ) ) ); ?>
                        </div>

                        <?php if ( get_theme_mod( 'about_button_text' ) ) : ?>
                            <div class="about-action-modern">
                                <a href="<?php echo esc_url( get_theme_mod( 'about_button_url', '#' ) ); ?>" class="about-btn-modern">
                                    <span class="btn-text"><?php echo esc_html( get_theme_mod( 'about_button_text' ) ); ?></span>
                                    <span class="btn-icon"><i class="fas fa-arrow-right"></i></span>
                                    <span class="btn-bg"></span>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if ( get_theme_mod( 'about_signature' ) ) : ?>
                            <div class="about-signature-modern">
                                <div class="signature-line"></div>
                                <span class="signature-text"><?php echo esc_html( get_theme_mod( 'about_signature' ) ); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'show_services_section', true ) ) : ?>
        <section class="services-section services-modern">
            <div class="container">
                <div class="section-header centered">
                    <span class="section-label"><?php esc_html_e( 'Our Services', 'corporate-seo-pro' ); ?></span>
                    <h2 class="section-title"><?php echo esc_html( get_theme_mod( 'services_title', __( 'サービス', 'corporate-seo-pro' ) ) ); ?></h2>
                    <p class="section-description"><?php echo esc_html( get_theme_mod( 'services_description', __( '一社一社に合わせた、カスタム型の支援を行っています。', 'corporate-seo-pro' ) ) ); ?></p>
                </div>

                <?php
                // Get featured services from Customizer settings
                $featured_services = array();
                for ( $i = 1; $i <= 3; $i++ ) {
                    $service_slug = get_theme_mod( 'featured_service_' . $i );
                    if ( ! empty( $service_slug ) ) {
                        $featured_services[] = $service_slug;
                    }
                }

                // If no services are selected in Customizer, use default order
                if ( empty( $featured_services ) ) {
                    $featured_services = array(
                        'hp-production',           // ホームページ制作
                        'ai-support',              // AI活用サポート
                        'web-app-co-developing',   // Webアプリ共同開発
                    );
                }

                // Build query arguments to fetch services in the specified order
                $services_query = new WP_Query( array(
                    'post_type'      => 'service',
                    'post_name__in'  => $featured_services,
                    'posts_per_page' => 3,
                    'orderby'        => 'post_name__in',
                ) );

                // Reorder posts to match the custom order
                $ordered_posts = array();
                if ( $services_query->have_posts() ) {
                    $all_posts = $services_query->posts;

                    foreach ( $featured_services as $slug ) {
                        foreach ( $all_posts as $post ) {
                            if ( $post->post_name === $slug ) {
                                $ordered_posts[] = $post;
                                break;
                            }
                        }
                    }

                    $services_query->posts = $ordered_posts;
                    $services_query->post_count = count( $ordered_posts );
                }

                if ( $services_query->have_posts() ) : ?>
                    <div class="services-grid-alternating">
                        <?php
                        $service_count = 0;
                        while ( $services_query->have_posts() ) :
                            $services_query->the_post();
                            $service_count++;

                            // Get service features from ACF Repeater field (same as single-service.php)
                            $features = array();
                            if ( function_exists('have_rows') && have_rows('service_features') ) {
                                while ( have_rows('service_features') ) {
                                    the_row();
                                    $features[] = array(
                                        'icon' => get_sub_field('icon'),
                                        'title' => get_sub_field('title'),
                                        'description' => get_sub_field('description')
                                    );
                                }
                            }

                            // Fallback default features if not set
                            if ( empty($features) ) {
                                $features = array(
                                    array('title' => '高品質なサービス提供'),
                                    array('title' => '柔軟なカスタマイズ対応'),
                                    array('title' => '専任担当者によるサポート')
                                );
                            }
                        ?>
                            <article class="service-card-alternating" data-service="<?php echo $service_count; ?>" data-scroll-animate>
                                <div class="service-card-image">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
                                    <?php else : ?>
                                        <div class="service-image-placeholder">
                                            <i class="fas fa-layer-group"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="service-card-number-badge"><?php echo str_pad($service_count, 2, '0', STR_PAD_LEFT); ?></div>
                                </div>

                                <div class="service-card-content-alt">
                                    <div class="service-card-header">
                                        <h3 class="service-card-title-alt">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                    </div>

                                    <p class="service-card-excerpt-alt"><?php echo wp_trim_words( get_the_excerpt(), 35, '...' ); ?></p>

                                    <?php if ( ! empty( $features ) ) : ?>
                                        <ul class="service-features-list">
                                            <?php
                                            $feature_count = 0;
                                            foreach ( $features as $feature ) :
                                                if ( $feature_count >= 3 ) break; // Limit to 3 features
                                                $feature_title = isset( $feature['title'] ) ? $feature['title'] : '';
                                                if ( $feature_title ) :
                                            ?>
                                                <li><?php echo esc_html( $feature_title ); ?></li>
                                            <?php
                                                $feature_count++;
                                                endif;
                                            endforeach;
                                            ?>
                                        </ul>
                                    <?php endif; ?>

                                    <div class="service-card-footer-alt">
                                        <a href="<?php the_permalink(); ?>" class="service-link-button">
                                            詳しく見る
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>

                    <div class="services-cta">
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ); ?>" class="btn btn-outline-large">
                            <?php esc_html_e( 'すべてのサービスを見る', 'corporate-seo-pro' ); ?>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'show_features_section', true ) ) : ?>
        <section class="features-section-refined">
            <!-- Background Elements -->
            <div class="features-refined-bg">
                <div class="refined-bg-gradient"></div>
                <div class="refined-diagonal-lines"></div>
                <div class="refined-accent-circle circle-1"></div>
                <div class="refined-accent-circle circle-2"></div>
            </div>

            <div class="container">
                <!-- Section Header -->
                <div class="features-header-refined">
                    <div class="header-top-line">
                        <span class="line-left"></span>
                        <span class="header-label-refined"><?php esc_html_e( 'Why Choose Us', 'corporate-seo-pro' ); ?></span>
                        <span class="line-right"></span>
                    </div>
                    <h2 class="features-title-refined">
                        <?php echo esc_html( get_theme_mod( 'features_title', __( '選ばれる理由', 'corporate-seo-pro' ) ) ); ?>
                    </h2>
                    <?php if ( get_theme_mod( 'features_description' ) ) : ?>
                        <p class="features-subtitle-refined"><?php echo esc_html( get_theme_mod( 'features_description' ) ); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Features Grid -->
                <div class="features-grid-refined">
                    <?php for ( $i = 1; $i <= 3; $i++ ) : ?>
                        <?php if ( get_theme_mod( 'feature_' . $i . '_title' ) ) : ?>
                            <article class="feature-card-refined" data-index="<?php echo $i; ?>">
                                <div class="card-inner">
                                    <!-- Card Top Accent -->
                                    <div class="card-top-accent"></div>

                                    <!-- Number Badge -->
                                    <div class="feature-number-refined">
                                        <span class="number-text"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></span>
                                        <span class="number-line"></span>
                                    </div>

                                    <!-- Icon Area -->
                                    <div class="feature-icon-refined">
                                        <div class="icon-bg-shape"></div>
                                        <div class="icon-inner">
                                            <?php if ( get_theme_mod( 'feature_' . $i . '_icon' ) ) : ?>
                                                <i class="<?php echo esc_attr( get_theme_mod( 'feature_' . $i . '_icon' ) ); ?>"></i>
                                            <?php else : ?>
                                                <i class="fas fa-check-circle"></i>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="feature-content-refined">
                                        <h3 class="feature-heading-refined">
                                            <?php echo esc_html( get_theme_mod( 'feature_' . $i . '_title' ) ); ?>
                                        </h3>
                                        <p class="feature-text-refined">
                                            <?php echo esc_html( get_theme_mod( 'feature_' . $i . '_description' ) ); ?>
                                        </p>
                                    </div>

                                    <!-- Bottom Indicator -->
                                    <div class="feature-indicator">
                                        <span class="indicator-dot"></span>
                                        <span class="indicator-line"></span>
                                    </div>
                                </div>

                                <!-- Hover Effect Layer -->
                                <div class="card-hover-layer"></div>
                            </article>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>

                <!-- Bottom Decorative Element -->
                <div class="features-bottom-element">
                    <div class="bottom-line"></div>
                    <div class="bottom-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="bottom-line"></div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'show_news_release_section', true ) ) : ?>
        <section class="news-release-section">
            <div class="container">
                <div class="section-header">
                    <span class="section-label"><?php echo esc_html( get_theme_mod( 'news_release_label', __( 'News Release', 'corporate-seo-pro' ) ) ); ?></span>
                    <h2 class="section-title"><?php echo esc_html( get_theme_mod( 'news_release_title', __( 'ニュースリリース', 'corporate-seo-pro' ) ) ); ?></h2>
                    <?php if ( get_theme_mod( 'news_release_description' ) ) : ?>
                        <p class="section-description"><?php echo esc_html( get_theme_mod( 'news_release_description' ) ); ?></p>
                    <?php endif; ?>
                </div>

                <?php
                $news_release_query = new WP_Query( array(
                    'post_type'      => 'news',
                    'posts_per_page' => 3,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ) );

                if ( $news_release_query->have_posts() ) : ?>
                    <div class="news-release-list">
                        <?php while ( $news_release_query->have_posts() ) : $news_release_query->the_post(); ?>
                            <article class="news-release-item">
                                <a href="<?php the_permalink(); ?>" class="news-release-link">
                                    <div class="news-release-date">
                                        <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                            <?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?>
                                        </time>
                                    </div>
                                    <div class="news-release-content">
                                        <h3 class="news-release-title"><?php the_title(); ?></h3>
                                        <?php if ( has_excerpt() ) : ?>
                                            <p class="news-release-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 50, '...' ); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="news-release-arrow">
                                        <span aria-hidden="true">→</span>
                                    </div>
                                </a>
                            </article>
                        <?php endwhile; ?>
                    </div>
                    <?php wp_reset_postdata(); ?>

                    <div class="section-footer">
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'news' ) ); ?>" class="btn btn-outline">
                            <?php esc_html_e( 'すべてのニュースを見る', 'corporate-seo-pro' ); ?>
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                <?php else : ?>
                    <p class="no-posts"><?php esc_html_e( 'ニュースリリースはまだありません。', 'corporate-seo-pro' ); ?></p>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'show_news_section', true ) ) : ?>
        <section class="blog-section">
            <div class="container">
                <div class="section-header centered">
                    <span class="section-label"><?php esc_html_e( 'Blog', 'corporate-seo-pro' ); ?></span>
                    <h2 class="section-title"><?php echo esc_html( get_theme_mod( 'news_title', __( 'ブログ', 'corporate-seo-pro' ) ) ); ?></h2>
                </div>

                <?php
                $blog_query = new WP_Query( array(
                    'post_type'      => 'post',
                    'posts_per_page' => 9,
                ) );

                if ( $blog_query->have_posts() ) : ?>
                    <div class="blog-grid-3x3">
                        <?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>
                            <article class="blog-card">
                                <a href="<?php the_permalink(); ?>" class="blog-card-link">
                                    <div class="blog-card-image">
                                        <?php if ( has_post_thumbnail() ) : ?>
                                            <?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
                                        <?php else : ?>
                                            <div class="blog-card-placeholder">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="blog-card-content">
                                        <time class="blog-card-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                            <?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?>
                                        </time>
                                        <h3 class="blog-card-title"><?php the_title(); ?></h3>
                                    </div>
                                </a>
                            </article>
                        <?php endwhile; ?>
                    </div>
                    <?php wp_reset_postdata(); ?>

                    <div class="section-footer">
                        <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="btn btn-outline-large">
                            <?php esc_html_e( 'すべての記事を見る', 'corporate-seo-pro' ); ?>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'show_cta_section', true ) ) : ?>
        <section class="cta-section cta-homepage">
            <div class="cta-background">
                <div class="cta-gradient"></div>
                <div class="cta-pattern"></div>
                <div class="cta-glow"></div>
            </div>

            <div class="container">
                <div class="cta-content">
                    <h2 class="cta-title">
                        <?php echo esc_html( get_theme_mod( 'cta_title', __( '業務の"散らかり"を、1つのシステムで解決しませんか？', 'corporate-seo-pro' ) ) ); ?>
                    </h2>

                    <div class="cta-button-wrapper">
                        <a href="<?php echo esc_url( get_theme_mod( 'cta_button_url', get_contact_page_url() ) ); ?>" class="cta-button">
                            <span class="cta-button-text"><?php echo esc_html( get_theme_mod( 'cta_button_text', __( '無料で相談してみる', 'corporate-seo-pro' ) ) ); ?></span>
                            <span class="cta-button-icon">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </a>
                        <div class="cta-button-shadow"></div>
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