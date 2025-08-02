<?php
/**
 * サービス一覧ページテンプレート
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main service-archive-page">
    <header class="service-archive-header">
        <div class="header-bg-effect">
            <div class="bg-gradient-1"></div>
            <div class="bg-gradient-2"></div>
            <div class="bg-pattern"></div>
        </div>
        <div class="container">
            <h1 class="service-archive-title">
                <span class="title-en">Services</span>
                <span class="title-ja">サービス一覧</span>
            </h1>
            <p class="service-archive-subtitle">
                Harmonic Societyが提供する革新的なソリューション
            </p>
        </div>
    </header>

    <section class="service-archive-section">
        <div class="container">
            <?php if ( have_posts() ) : ?>
                <div class="service-archive-grid">
                    <?php 
                    $service_count = 0;
                    while ( have_posts() ) : 
                        the_post(); 
                        $service_count++;
                    ?>
                        <article class="service-archive-item" data-service="<?php echo $service_count; ?>">
                            <div class="service-card">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="service-image-wrapper">
                                        <div class="service-image">
                                            <?php the_post_thumbnail( 'large' ); ?>
                                            <div class="service-overlay">
                                                <span class="overlay-icon">
                                                    <i class="fas fa-arrow-right"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="service-number">
                                            <?php echo sprintf( '%02d', $service_count ); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="service-info">
                                    <h3 class="service-name">
                                        <?php the_title(); ?>
                                    </h3>
                                    
                                    <div class="service-description">
                                        <?php echo wp_trim_words( get_the_excerpt(), 40, '...' ); ?>
                                    </div>
                                    
                                    <?php if ( function_exists('get_field') ) : ?>
                                        <div class="service-features">
                                            <?php 
                                            // カスタムフィールドから特徴を取得（例）
                                            $features = get_field('service_features');
                                            if ( $features && is_array($features) ) : ?>
                                                <ul class="feature-list">
                                                    <?php 
                                                    $feature_icons = ['fa-check-circle', 'fa-star', 'fa-rocket'];
                                                    $feature_count = 0;
                                                    foreach ( array_slice($features, 0, 3) as $feature ) : 
                                                        // ACFのリピーターフィールドの場合
                                                        if ( is_array($feature) && isset($feature['title']) ) {
                                                            $feature_text = $feature['title'];
                                                        } else if ( is_string($feature) ) {
                                                            $feature_text = $feature;
                                                        } else {
                                                            continue;
                                                        }
                                                        $icon = isset($feature_icons[$feature_count]) ? $feature_icons[$feature_count] : 'fa-check';
                                                        $feature_count++;
                                                    ?>
                                                        <li data-feature-icon="<?php echo esc_attr($icon); ?>" style="color: #1f2937 !important;">
                                                            <?php echo esc_html( $feature_text ); ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else : ?>
                                                <!-- フォールバック：デフォルトの特徴を表示 -->
                                                <ul class="feature-list">
                                                    <li data-feature-icon="fa-check-circle" style="color: #1f2937 !important;">高品質なサービス提供</li>
                                                    <li data-feature-icon="fa-star" style="color: #1f2937 !important;">豊富な実績と経験</li>
                                                    <li data-feature-icon="fa-rocket" style="color: #1f2937 !important;">迅速な対応と柔軟性</li>
                                                </ul>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="service-actions">
                                        <a href="<?php the_permalink(); ?>" class="service-link">
                                            <span class="link-text">詳細を見る</span>
                                            <span class="link-icon">
                                                <i class="fas fa-arrow-right"></i>
                                            </span>
                                        </a>
                                        
                                        <a href="<?php echo esc_url( get_contact_page_url() ); ?>?utm_source=service&utm_medium=page&utm_content=<?php echo urlencode( get_the_title() ); ?>" class="service-inquiry">
                                            <span class="inquiry-icon">
                                                <i class="fas fa-envelope"></i>
                                            </span>
                                            <span class="inquiry-text">お問い合わせ</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <!-- ページネーション -->
                <?php if ( get_the_posts_pagination() ) : ?>
                    <div class="service-pagination">
                        <?php
                        the_posts_pagination( array(
                            'mid_size'  => 2,
                            'prev_text' => '<i class="fas fa-chevron-left"></i><span>前へ</span>',
                            'next_text' => '<span>次へ</span><i class="fas fa-chevron-right"></i>',
                            'type'      => 'list',
                        ) );
                        ?>
                    </div>
                <?php endif; ?>

            <?php else : ?>
                <div class="no-services">
                    <div class="no-services-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h2>サービスが見つかりませんでした</h2>
                    <p>現在、表示できるサービスがありません。</p>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="back-home">
                        ホームに戻る
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="service-cta-section">
        <div class="container">
            <div class="service-cta-content">
                <h2 class="cta-title">最適なソリューションをご提案します</h2>
                <p class="cta-text">お客様のビジネスに最適なサービスをご提案いたします</p>
                <a href="<?php echo esc_url( get_contact_page_url() ); ?>" class="cta-button">
                    <span class="button-text">無料相談を申し込む</span>
                    <span class="button-icon">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </a>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>