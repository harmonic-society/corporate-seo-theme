<?php
/**
 * サービス一覧ページテンプレート - Creative & Stylish Design
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main service-archive-page service-archive-modern">
    <!-- Hero Header -->
    <header class="service-hero">
        <div class="hero-background">
            <div class="hero-gradient"></div>
            <div class="hero-mesh"></div>
            <div class="hero-particles"></div>
        </div>
        <div class="container">
            <div class="hero-content">
                <span class="hero-badge">
                    <i class="fas fa-layer-group"></i>
                    <span>Our Solutions</span>
                </span>
                <h1 class="hero-title">
                    <span class="title-line-1">革新的なサービスで</span>
                    <span class="title-line-2">ビジネスを加速させる</span>
                </h1>
                <p class="hero-description">
                    Harmonic Societyが提供する、テクノロジーと人間性の調和による<br>
                    最先端のソリューションをご覧ください
                </p>
            </div>
        </div>
        <div class="hero-wave">
            <svg viewBox="0 0 1440 120" preserveAspectRatio="none">
                <path d="M0,40 C480,100 960,100 1440,40 L1440,120 L0,120 Z" fill="#ffffff"></path>
            </svg>
        </div>
    </header>

    <!-- Chiba Subscription Banner (非表示) -->
    <?php /*
    <div class="container">
        <?php get_template_part( 'template-parts/chiba-subscription-banner' ); ?>
    </div>
    */ ?>

    <!-- Services Grid Section -->
    <section class="services-showcase">
        <div class="container">
            <?php if ( have_posts() ) : ?>
                <div class="services-grid-creative">
                    <?php
                    $service_count = 0;
                    while ( have_posts() ) :
                        the_post();
                        $service_count++;
                        $is_featured = ($service_count <= 2); // 最初の2つを大きく表示
                    ?>
                        <article class="service-item-creative <?php echo $is_featured ? 'featured' : ''; ?>" data-service="<?php echo $service_count; ?>">
                            <a href="<?php the_permalink(); ?>" class="service-card-creative">
                                <!-- Background Image -->
                                <div class="service-bg">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'large' ); ?>
                                    <?php else : ?>
                                        <div class="service-bg-placeholder">
                                            <i class="fas fa-layer-group"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="service-overlay-gradient"></div>
                                </div>

                                <!-- Service Number Badge -->
                                <div class="service-badge">
                                    <span class="badge-number"><?php echo sprintf( '%02d', $service_count ); ?></span>
                                </div>

                                <!-- Content -->
                                <div class="service-content-wrapper">
                                    <div class="service-icon-box">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>

                                    <h3 class="service-title-creative"><?php the_title(); ?></h3>

                                    <p class="service-excerpt-creative">
                                        <?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
                                    </p>

                                    <?php if ( function_exists('get_field') ) :
                                        $features = get_field('service_features');
                                        if ( $features && is_array($features) && count($features) > 0 ) : ?>
                                            <ul class="service-tags">
                                                <?php
                                                foreach ( array_slice($features, 0, 3) as $feature ) :
                                                    if ( is_array($feature) && isset($feature['title']) ) {
                                                        $feature_text = $feature['title'];
                                                    } else if ( is_string($feature) ) {
                                                        $feature_text = $feature;
                                                    } else {
                                                        continue;
                                                    }
                                                ?>
                                                    <li><?php echo esc_html( $feature_text ); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif;
                                    endif; ?>

                                    <div class="service-cta-arrow">
                                        <span>詳細を見る</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </div>
                                </div>

                                <!-- Hover Effect -->
                                <div class="service-hover-effect"></div>
                            </a>
                        </article>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <?php if ( get_the_posts_pagination() ) : ?>
                    <div class="service-pagination-modern">
                        <?php
                        the_posts_pagination( array(
                            'mid_size'  => 2,
                            'prev_text' => '<i class="fas fa-chevron-left"></i>',
                            'next_text' => '<i class="fas fa-chevron-right"></i>',
                            'type'      => 'list',
                        ) );
                        ?>
                    </div>
                <?php endif; ?>

            <?php else : ?>
                <div class="no-services-modern">
                    <div class="no-services-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h2>サービスが見つかりませんでした</h2>
                    <p>現在、表示できるサービスがありません。</p>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-back-home">
                        <i class="fas fa-home"></i>
                        ホームに戻る
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="service-cta-modern">
        <div class="cta-background">
            <div class="cta-gradient-1"></div>
            <div class="cta-gradient-2"></div>
        </div>
        <div class="container">
            <div class="cta-content">
                <span class="cta-label">Contact Us</span>
                <h2 class="cta-title">ビジネスの成長を共に</h2>
                <p class="cta-description">
                    お客様のビジネスに最適なソリューションをご提案します。<br>
                    まずはお気軽にご相談ください。
                </p>
                <div class="cta-buttons">
                    <a href="<?php echo esc_url( get_contact_page_url() ); ?>" class="btn-cta-primary">
                        <span>無料相談を申し込む</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="tel:080-6946-4006" class="btn-cta-secondary">
                        <i class="fas fa-phone"></i>
                        <span>電話で相談する</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>