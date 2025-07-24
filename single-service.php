<?php
/**
 * サービス詳細ページテンプレート
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main single-service">
    <?php while ( have_posts() ) : the_post(); ?>
        
        <!-- パンくずリスト -->
        <div class="breadcrumb-wrapper">
            <div class="container">
                <?php get_template_part( 'template-parts/breadcrumb' ); ?>
            </div>
        </div>
        
        <!-- サービスヒーローセクション -->
        <section class="service-hero">
            <div class="hero-bg-effect">
                <div class="bg-gradient-1"></div>
                <div class="bg-gradient-2"></div>
                <div class="bg-pattern"></div>
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="hero-image-overlay">
                        <?php the_post_thumbnail( 'full', array( 'class' => 'hero-bg-image' ) ); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="container">
                <div class="hero-content">
                    <h1 class="service-title">
                        <span class="title-main"><?php the_title(); ?></span>
                        <?php if ( function_exists('get_field') && get_field('service_subtitle') ) : ?>
                            <span class="title-sub"><?php the_field('service_subtitle'); ?></span>
                        <?php endif; ?>
                    </h1>
                    
                    <!-- メタ情報コンポーネント -->
                    <?php get_template_part( 'template-parts/post-meta', null, array( 'type' => 'service' ) ); ?>
                    
                    <div class="hero-actions">
                        <a href="#service-inquiry" class="btn-primary smooth-scroll">
                            <span class="btn-text">お問い合わせ</span>
                            <span class="btn-icon"><i class="fas fa-arrow-right"></i></span>
                        </a>
                        
                        <a href="#service-features" class="btn-secondary smooth-scroll">
                            <span class="btn-text">詳細を見る</span>
                            <span class="btn-icon"><i class="fas fa-chevron-down"></i></span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="hero-scroll-indicator">
                <span class="scroll-text">Scroll</span>
                <span class="scroll-line"></span>
            </div>
        </section>

        <!-- サービス概要セクション -->
        <section class="service-overview">
            <div class="container">
                <div class="overview-grid">
                    <div class="overview-content">
                        <h2 class="section-title">
                            <span class="title-en">Overview</span>
                            <span class="title-ja">サービス概要</span>
                        </h2>
                        
                        <div class="service-lead">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <div class="service-description">
                            <?php the_content(); ?>
                        </div>
                    </div>
                    
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="overview-visual">
                            <div class="visual-container">
                                <?php the_post_thumbnail( 'large', array( 'class' => 'service-image' ) ); ?>
                                <div class="visual-decoration"></div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- サービスの特徴セクション -->
        <?php if ( function_exists('get_field') && have_rows('service_features') ) : ?>
        <section id="service-features" class="service-features">
            <div class="container">
                <h2 class="section-title text-center">
                    <span class="title-en">Features</span>
                    <span class="title-ja">サービスの特徴</span>
                </h2>
                
                <div class="features-grid">
                    <?php while ( have_rows('service_features') ) : the_row(); ?>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <?php if ( get_sub_field('feature_icon') ) : ?>
                                    <i class="<?php the_sub_field('feature_icon'); ?>"></i>
                                <?php endif; ?>
                            </div>
                            <h3 class="feature-title"><?php the_sub_field('feature_title'); ?></h3>
                            <p class="feature-description"><?php the_sub_field('feature_description'); ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- 料金プランセクション -->
        <?php if ( function_exists('get_field') && have_rows('service_plans') ) : ?>
        <section class="service-pricing">
            <div class="container">
                <h2 class="section-title text-center">
                    <span class="title-en">Pricing</span>
                    <span class="title-ja">料金プラン</span>
                </h2>
                
                <div class="pricing-grid">
                    <?php while ( have_rows('service_plans') ) : the_row(); ?>
                        <div class="pricing-card <?php echo get_sub_field('plan_recommended') ? 'recommended' : ''; ?>">
                            <?php if ( get_sub_field('plan_recommended') ) : ?>
                                <div class="recommended-badge">おすすめ</div>
                            <?php endif; ?>
                            
                            <h3 class="plan-name"><?php the_sub_field('plan_name'); ?></h3>
                            <div class="plan-price">
                                <span class="price-currency">¥</span>
                                <span class="price-amount"><?php the_sub_field('plan_price'); ?></span>
                                <span class="price-unit"><?php the_sub_field('plan_unit'); ?></span>
                            </div>
                            
                            <?php if ( have_rows('plan_features') ) : ?>
                                <ul class="plan-features">
                                    <?php while ( have_rows('plan_features') ) : the_row(); ?>
                                        <li>
                                            <i class="fas fa-check"></i>
                                            <?php the_sub_field('feature'); ?>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            <?php endif; ?>
                            
                            <a href="#service-inquiry" class="plan-cta smooth-scroll">
                                このプランで相談する
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- お問い合わせセクション -->
        <section id="service-inquiry" class="service-inquiry">
            <div class="container">
                <div class="inquiry-box">
                    <h2 class="inquiry-title">
                        <span class="title-main">お問い合わせ・ご相談</span>
                        <span class="title-sub">まずはお気軽にご相談ください</span>
                    </h2>
                    
                    <div class="inquiry-content">
                        <p class="inquiry-lead">
                            サービスに関するご質問やご相談は、下記フォームまたはお電話にてお気軽にお問い合わせください。
                        </p>
                        
                        <div class="inquiry-actions">
                            <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn-contact">
                                <i class="fas fa-envelope"></i>
                                お問い合わせフォーム
                            </a>
                            
                            <a href="tel:03-1234-5678" class="btn-phone">
                                <i class="fas fa-phone"></i>
                                03-1234-5678
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 関連サービス -->
        <?php
        $related_services = new WP_Query( array(
            'post_type' => 'service',
            'posts_per_page' => 3,
            'post__not_in' => array( get_the_ID() ),
            'orderby' => 'rand',
        ) );
        
        if ( $related_services->have_posts() ) : ?>
        <section class="related-services">
            <div class="container">
                <h2 class="section-title text-center">
                    <span class="title-en">Related Services</span>
                    <span class="title-ja">関連サービス</span>
                </h2>
                
                <?php
                // グリッドコンポーネントを使用
                set_query_var( 'grid_query', $related_services );
                get_template_part( 'template-parts/content-grid', null, array(
                    'post_type' => 'service',
                    'columns' => 3,
                    'show_pagination' => false,
                ) );
                ?>
            </div>
        </section>
        <?php endif; wp_reset_postdata(); ?>
        
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>