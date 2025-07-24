<?php
/**
 * サービス詳細ページテンプレート
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main single-service">
    <?php while ( have_posts() ) : the_post(); ?>
        
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
                    <nav class="service-breadcrumb">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">ホーム</a>
                        <span class="separator">/</span>
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ); ?>">サービス一覧</a>
                        <span class="separator">/</span>
                        <span class="current"><?php the_title(); ?></span>
                    </nav>
                    
                    <h1 class="service-title">
                        <span class="title-main"><?php the_title(); ?></span>
                        <?php if ( function_exists('get_field') && get_field('service_subtitle') ) : ?>
                            <span class="title-sub"><?php the_field('service_subtitle'); ?></span>
                        <?php endif; ?>
                    </h1>
                    
                    <div class="hero-meta">
                        <?php if ( function_exists('get_field') && get_field('service_duration') ) : ?>
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <span><?php the_field('service_duration'); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( function_exists('get_field') && get_field('service_price') ) : ?>
                            <div class="meta-item">
                                <i class="fas fa-yen-sign"></i>
                                <span><?php the_field('service_price'); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
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
                            <div class="visual-wrapper">
                                <?php the_post_thumbnail( 'large', array( 'class' => 'overview-image' ) ); ?>
                                <div class="visual-decoration"></div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- サービス特徴セクション -->
        <section id="service-features" class="service-features-section">
            <div class="container">
                <h2 class="section-title text-center">
                    <span class="title-en">Features</span>
                    <span class="title-ja">サービスの特徴</span>
                </h2>
                
                <div class="features-grid">
                    <?php 
                    // カスタムフィールドまたはデフォルトの特徴を表示
                    $features = function_exists('get_field') ? get_field('service_features') : null;
                    if ( !$features ) {
                        // デフォルトの特徴（例）
                        $features = array(
                            array(
                                'icon' => 'fas fa-rocket',
                                'title' => '迅速な対応',
                                'description' => 'お客様のご要望に素早くお応えし、スピーディーな課題解決を実現します。'
                            ),
                            array(
                                'icon' => 'fas fa-shield-alt',
                                'title' => '高品質保証',
                                'description' => '業界最高水準の品質基準を設け、安心してご利用いただけるサービスを提供します。'
                            ),
                            array(
                                'icon' => 'fas fa-users',
                                'title' => '専門チーム',
                                'description' => '経験豊富な専門家チームが、お客様のプロジェクトを成功へと導きます。'
                            )
                        );
                    }
                    
                    foreach ( $features as $index => $feature ) : ?>
                        <div class="feature-card" data-feature="<?php echo $index + 1; ?>">
                            <div class="feature-icon">
                                <i class="<?php echo esc_attr( isset($feature['icon']) ? $feature['icon'] : 'fas fa-check-circle' ); ?>"></i>
                            </div>
                            <h3 class="feature-title"><?php echo esc_html( isset($feature['title']) ? $feature['title'] : '' ); ?></h3>
                            <p class="feature-description"><?php echo esc_html( isset($feature['description']) ? $feature['description'] : '' ); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- サービスプロセスセクション -->
        <?php if ( (function_exists('get_field') && get_field('service_process')) || !function_exists('get_field') ) : ?>
        <section class="service-process-section">
            <div class="container">
                <h2 class="section-title text-center">
                    <span class="title-en">Process</span>
                    <span class="title-ja">サービスの流れ</span>
                </h2>
                
                <div class="process-timeline">
                    <?php 
                    $processes = function_exists('get_field') ? get_field('service_process') : null;
                    if ( !$processes ) {
                        // デフォルトのプロセス
                        $processes = array(
                            array('title' => 'お問い合わせ', 'description' => 'まずはお気軽にご相談ください'),
                            array('title' => 'ヒアリング', 'description' => 'お客様のご要望を詳しくお伺いします'),
                            array('title' => 'ご提案', 'description' => '最適なソリューションをご提案します'),
                            array('title' => '実施', 'description' => 'プロフェッショナルチームが実行します'),
                            array('title' => 'フォローアップ', 'description' => '継続的なサポートを提供します')
                        );
                    }
                    
                    foreach ( $processes as $index => $process ) : ?>
                        <div class="process-step" data-step="<?php echo $index + 1; ?>">
                            <div class="step-number"><?php echo sprintf( '%02d', $index + 1 ); ?></div>
                            <div class="step-content">
                                <h4 class="step-title"><?php echo esc_html( isset($process['title']) ? $process['title'] : '' ); ?></h4>
                                <p class="step-description"><?php echo esc_html( isset($process['description']) ? $process['description'] : '' ); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- 価格・プランセクション -->
        <?php if ( (function_exists('get_field') && get_field('service_plans')) || !function_exists('get_field') ) : ?>
        <section class="service-pricing-section">
            <div class="container">
                <h2 class="section-title text-center">
                    <span class="title-en">Pricing</span>
                    <span class="title-ja">料金プラン</span>
                </h2>
                
                <div class="pricing-grid">
                    <?php 
                    $plans = function_exists('get_field') ? get_field('service_plans') : null;
                    if ( !$plans ) {
                        // デフォルトのプラン例
                        $plans = array(
                            array(
                                'name' => 'ベーシックプラン',
                                'price' => '¥50,000〜',
                                'features' => array('基本機能', 'メールサポート', '月1回のレポート'),
                                'recommended' => false
                            ),
                            array(
                                'name' => 'スタンダードプラン',
                                'price' => '¥100,000〜',
                                'features' => array('全機能利用可能', '優先サポート', '週次レポート', 'カスタマイズ対応'),
                                'recommended' => true
                            ),
                            array(
                                'name' => 'プレミアムプラン',
                                'price' => 'お見積もり',
                                'features' => array('フルカスタマイズ', '24時間サポート', 'リアルタイムレポート', '専任担当者'),
                                'recommended' => false
                            )
                        );
                    }
                    
                    foreach ( $plans as $plan ) : ?>
                        <div class="pricing-card <?php echo (isset($plan['recommended']) && $plan['recommended']) ? 'recommended' : ''; ?>">
                            <?php if ( isset($plan['recommended']) && $plan['recommended'] ) : ?>
                                <div class="recommended-badge">おすすめ</div>
                            <?php endif; ?>
                            
                            <h3 class="plan-name"><?php echo esc_html( isset($plan['name']) ? $plan['name'] : '' ); ?></h3>
                            <div class="plan-price"><?php echo esc_html( isset($plan['price']) ? $plan['price'] : '' ); ?></div>
                            
                            <ul class="plan-features">
                                <?php 
                                $features = isset($plan['features']) ? $plan['features'] : array();
                                
                                // ACFのテキストエリアから改行区切りの機能リストを処理
                                if ( is_string($features) ) {
                                    $features = array_filter(array_map('trim', explode("\n", $features)));
                                }
                                
                                foreach ( $features as $feature ) : 
                                    // ACFのリピーターフィールドの場合
                                    if ( is_array($feature) && isset($feature['feature']) ) {
                                        $feature_text = $feature['feature'];
                                    } else {
                                        $feature_text = $feature;
                                    }
                                ?>
                                    <li><i class="fas fa-check"></i> <?php echo wp_kses_post( $feature_text ); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            
                            <a href="#service-inquiry" class="plan-cta smooth-scroll">
                                このプランで相談する
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- FAQ セクション -->
        <?php if ( (function_exists('get_field') && get_field('service_faq')) || !function_exists('get_field') ) : ?>
        <section class="service-faq-section">
            <div class="container">
                <h2 class="section-title text-center">
                    <span class="title-en">FAQ</span>
                    <span class="title-ja">よくある質問</span>
                </h2>
                
                <div class="faq-accordion">
                    <?php 
                    $faqs = function_exists('get_field') ? get_field('service_faq') : null;
                    if ( !$faqs ) {
                        // デフォルトのFAQ
                        $faqs = array(
                            array(
                                'question' => 'サービスの導入期間はどのくらいですか？',
                                'answer' => 'プロジェクトの規模により異なりますが、通常2週間〜1ヶ月程度での導入が可能です。'
                            ),
                            array(
                                'question' => 'サポート体制について教えてください。',
                                'answer' => '専任の担当者がつき、メール・電話・チャットでのサポートを提供しています。'
                            ),
                            array(
                                'question' => '他社からの乗り換えは可能ですか？',
                                'answer' => 'はい、可能です。データ移行のサポートも含めて対応いたします。'
                            )
                        );
                    }
                    
                    foreach ( $faqs as $index => $faq ) : ?>
                        <div class="faq-item" data-faq="<?php echo $index + 1; ?>">
                            <button class="faq-question">
                                <span class="question-text"><?php echo esc_html( isset($faq['question']) ? $faq['question'] : '' ); ?></span>
                                <span class="question-icon"><i class="fas fa-plus"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p><?php echo esc_html( isset($faq['answer']) ? $faq['answer'] : '' ); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- お問い合わせCTAセクション -->
        <section id="service-inquiry" class="service-inquiry-section">
            <div class="container">
                <div class="inquiry-wrapper">
                    <div class="inquiry-content">
                        <h2 class="inquiry-title">
                            <span class="title-main">お問い合わせ・ご相談</span>
                            <span class="title-sub">まずはお気軽にご相談ください</span>
                        </h2>
                        
                        <p class="inquiry-description">
                            <?php the_title(); ?>に関するご質問やお見積もりのご依頼など、<br>
                            お気軽にお問い合わせください。専門スタッフが丁寧にご対応いたします。
                        </p>
                        
                        <div class="inquiry-buttons">
                            <a href="<?php echo esc_url( get_contact_page_url() ); ?>?utm_source=service&utm_medium=detail&utm_content=<?php echo urlencode( get_the_title() ); ?>" class="btn-inquiry-primary">
                                <span class="btn-icon"><i class="fas fa-envelope"></i></span>
                                <span class="btn-text">お問い合わせフォーム</span>
                            </a>
                            
                            <a href="tel:08069464006" class="btn-inquiry-secondary">
                                <span class="btn-icon"><i class="fas fa-phone"></i></span>
                                <span class="btn-text">電話で相談する</span>
                            </a>
                        </div>
                    </div>
                    
                    <div class="inquiry-decoration">
                        <div class="decoration-circle-1"></div>
                        <div class="decoration-circle-2"></div>
                        <div class="decoration-circle-3"></div>
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
            'orderby' => 'rand'
        ) );
        
        if ( $related_services->have_posts() ) : ?>
            <section class="related-services-section">
                <div class="container">
                    <h2 class="section-title text-center">
                        <span class="title-en">Related Services</span>
                        <span class="title-ja">関連サービス</span>
                    </h2>
                    
                    <div class="related-services-grid">
                        <?php while ( $related_services->have_posts() ) : $related_services->the_post(); ?>
                            <article class="related-service-card">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="related-service-image">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail( 'medium' ); ?>
                                            <div class="image-overlay">
                                                <span class="overlay-icon"><i class="fas fa-arrow-right"></i></span>
                                            </div>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="related-service-content">
                                    <h3 class="related-service-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    <p class="related-service-excerpt">
                                        <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
                                    </p>
                                    <a href="<?php the_permalink(); ?>" class="related-service-link">
                                        詳細を見る <i class="fas fa-chevron-right"></i>
                                    </a>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>