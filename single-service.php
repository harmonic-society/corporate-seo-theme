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
        
        <!-- サービスヒーローセクション - Dynamic & Cinematic -->
        <section class="service-hero-dynamic">
            <div class="hero-background-layers">
                <!-- Background Image Layer -->
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="hero-image-layer">
                        <?php the_post_thumbnail( 'full', array( 'class' => 'hero-bg-image' ) ); ?>
                        <div class="hero-image-overlay"></div>
                    </div>
                <?php endif; ?>

                <!-- Animated Gradient Layers -->
                <div class="gradient-layer gradient-1"></div>
                <div class="gradient-layer gradient-2"></div>
                <div class="gradient-layer gradient-3"></div>

                <!-- Geometric Shapes -->
                <div class="hero-shapes">
                    <div class="shape shape-1"></div>
                    <div class="shape shape-2"></div>
                    <div class="shape shape-3"></div>
                </div>

                <!-- Floating Particles -->
                <div class="hero-particles">
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                </div>
            </div>

            <div class="container">
                <div class="hero-content-modern">
                    <!-- Service Badge -->
                    <div class="service-badge-tag">
                        <i class="fas fa-star"></i>
                        <span>Premium Service</span>
                    </div>

                    <h1 class="service-title-dynamic">
                        <span class="title-decorator"></span>
                        <span class="title-main"><?php the_title(); ?></span>
                        <?php if ( function_exists('get_field') && get_field('service_subtitle') ) : ?>
                            <span class="title-sub"><?php the_field('service_subtitle'); ?></span>
                        <?php endif; ?>
                    </h1>

                    <div class="hero-description">
                        <?php
                        $excerpt = get_the_excerpt();
                        if ( $excerpt ) {
                            echo '<p>' . esc_html( wp_trim_words( $excerpt, 30, '...' ) ) . '</p>';
                        }
                        ?>
                    </div>

                    <div class="hero-actions-modern">
                        <a href="<?php echo esc_url( get_contact_page_url() ); ?>" class="btn-hero-primary">
                            <span class="btn-bg-effect"></span>
                            <span class="btn-content">
                                <span class="btn-text">お問い合わせ</span>
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </a>

                        <a href="#service-features" class="btn-hero-secondary smooth-scroll">
                            <span class="btn-content">
                                <span class="btn-text">詳細を見る</span>
                                <i class="fas fa-chevron-down"></i>
                            </span>
                        </a>
                    </div>

                    <!-- Key Features Preview -->
                    <?php if ( function_exists('get_field') && function_exists('have_rows') && have_rows('service_features') ) : ?>
                        <div class="hero-quick-features">
                            <?php
                            $feature_count = 0;
                            while ( have_rows('service_features') && $feature_count < 3 ) : the_row();
                                $feature_count++;
                                $icon = get_sub_field('icon') ? get_sub_field('icon') : 'fas fa-check-circle';
                                $title = get_sub_field('title') ? get_sub_field('title') : 'Feature';
                            ?>
                                <div class="quick-feature-item">
                                    <i class="<?php echo esc_attr( $icon ); ?>"></i>
                                    <span><?php echo esc_html( $title ); ?></span>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="hero-wave-divider">
                <svg viewBox="0 0 1440 120" preserveAspectRatio="none">
                    <path d="M0,40 Q360,100 720,60 T1440,40 L1440,120 L0,120 Z" fill="#ffffff"></path>
                </svg>
            </div>
        </section>

        <!-- サービス概要セクション - Enhanced -->
        <section class="service-overview-modern">
            <div class="container">
                <div class="overview-layout">
                    <div class="overview-content-area">
                        <div class="section-header-modern">
                            <div class="section-badge-small">
                                <span>Overview</span>
                            </div>
                            <h2 class="section-title-modern">
                                <span class="title-ja">サービス概要</span>
                                <span class="title-decorator-line"></span>
                            </h2>
                        </div>

                        <div class="service-lead-text">
                            <?php the_excerpt(); ?>
                        </div>

                        <div class="service-description-styled">
                            <?php the_content(); ?>
                        </div>

                        <!-- Value Propositions -->
                        <div class="value-props">
                            <div class="value-prop-item">
                                <div class="prop-icon">
                                    <i class="fas fa-bolt"></i>
                                </div>
                                <div class="prop-text">
                                    <h4>迅速な対応</h4>
                                    <p>スピーディーな導入サポート</p>
                                </div>
                            </div>
                            <div class="value-prop-item">
                                <div class="prop-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="prop-text">
                                    <h4>安心のサポート</h4>
                                    <p>導入後も手厚いフォロー</p>
                                </div>
                            </div>
                            <div class="value-prop-item">
                                <div class="prop-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="prop-text">
                                    <h4>確実な成果</h4>
                                    <p>データドリブンな改善提案</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="overview-visual-area">
                            <div class="visual-frame">
                                <div class="frame-decoration frame-top"></div>
                                <div class="frame-decoration frame-bottom"></div>
                                <div class="visual-image-container">
                                    <?php the_post_thumbnail( 'large', array( 'class' => 'service-visual-image' ) ); ?>
                                </div>
                                <div class="visual-gradient-overlay"></div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Chiba Subscription Banner (ホームページ制作サービスのみ表示) -->
        <?php
        // ホームページ制作サービスかどうかを判定
        $post_slug = get_post_field( 'post_name', get_the_ID() );
        $post_title = get_the_title();
        $is_homepage_service = (
            strpos($post_slug, 'homepage') !== false ||
            strpos($post_slug, 'website') !== false ||
            strpos($post_title, 'ホームページ') !== false ||
            strpos($post_title, 'Webサイト') !== false
        );

        if ( $is_homepage_service ) :
        ?>
            <div class="container">
                <?php get_template_part( 'template-parts/chiba-subscription-banner' ); ?>
            </div>
        <?php endif; ?>

        <!-- サービスの特徴セクション -->
        <?php
        $has_features = false;
        if ( function_exists('get_field') && function_exists('have_rows') ) {
            $has_features = have_rows('service_features');
        }
        
        if ( $has_features ) : ?>
        <section id="service-features" class="service-features-modern">
            <div class="features-bg-pattern"></div>
            <div class="container">
                <div class="section-header-centered">
                    <div class="section-badge-large">
                        <i class="fas fa-gem"></i>
                        <span>Features</span>
                    </div>
                    <h2 class="section-title-large">
                        <span class="title-ja">サービスの特徴</span>
                        <span class="title-accent">このサービスが選ばれる理由</span>
                    </h2>
                </div>

                <div class="features-showcase">
                    <?php
                    $feature_index = 0;
                    while ( have_rows('service_features') ) : the_row();
                        $feature_index++;
                        $icon = get_sub_field('icon') ? get_sub_field('icon') : 'fas fa-check-circle';
                        $title = get_sub_field('title') ? get_sub_field('title') : 'Feature';
                        $desc = get_sub_field('description') ? get_sub_field('description') : '';
                    ?>
                        <div class="feature-card-modern" data-feature="<?php echo $feature_index; ?>">
                            <div class="feature-number"><?php echo sprintf( '%02d', $feature_index ); ?></div>
                            <div class="feature-icon-modern">
                                <div class="icon-circle">
                                    <i class="<?php echo esc_attr( $icon ); ?>"></i>
                                </div>
                                <div class="icon-glow"></div>
                            </div>
                            <div class="feature-content">
                                <h3 class="feature-title-modern"><?php echo esc_html( $title ); ?></h3>
                                <p class="feature-description-modern"><?php echo esc_html( $desc ); ?></p>
                            </div>
                            <div class="feature-hover-effect"></div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <?php else : 
            // ACFが設定されていない場合はフォールバックを表示
            get_template_part( 'template-parts/service-features', 'fallback' );
        endif; ?>

        <!-- 料金プランセクション -->
        <?php 
        $has_plans = false;
        if ( function_exists('get_field') && function_exists('have_rows') ) {
            $has_plans = have_rows('service_plans');
        }
        
        if ( $has_plans ) : ?>
        <section class="service-pricing-modern">
            <div class="pricing-background">
                <div class="pricing-gradient"></div>
            </div>
            <div class="container">
                <div class="section-header-centered">
                    <div class="section-badge-large">
                        <i class="fas fa-tags"></i>
                        <span>Pricing</span>
                    </div>
                    <h2 class="section-title-large">
                        <span class="title-ja">料金プラン</span>
                        <span class="title-accent">最適なプランをお選びください</span>
                    </h2>
                </div>

                <div class="pricing-cards-wrapper">
                    <?php
                    $plan_index = 0;
                    while ( have_rows('service_plans') ) : the_row();
                        $plan_index++;
                        $is_recommended = get_sub_field('recommended');
                        $name = get_sub_field('name') ? get_sub_field('name') : 'プラン';
                        $price = get_sub_field('price');
                        $price_number = preg_replace('/[^0-9,]/', '', $price);
                        $price_unit = '';
                        if ( strpos($price, '〜') !== false ) {
                            $price_unit = '〜';
                        } elseif ( strpos($price, '/月') !== false ) {
                            $price_unit = '/月';
                        }
                    ?>
                        <div class="pricing-card-modern <?php echo $is_recommended ? 'is-recommended' : ''; ?>" data-plan="<?php echo $plan_index; ?>">
                            <?php if ( $is_recommended ) : ?>
                                <div class="recommended-badge-modern">
                                    <i class="fas fa-crown"></i>
                                    <span>おすすめ</span>
                                </div>
                            <?php endif; ?>

                            <div class="plan-header">
                                <h3 class="plan-name-modern"><?php echo esc_html( $name ); ?></h3>
                                <div class="plan-price-modern">
                                    <span class="price-currency-modern">¥</span>
                                    <span class="price-amount-modern"><?php echo $price_number ? esc_html( $price_number ) : '0'; ?></span>
                                    <span class="price-unit-modern"><?php echo esc_html( $price_unit ); ?></span>
                                </div>
                            </div>

                            <?php
                            $features = get_sub_field('features');
                            if ( $features ) :
                                $features_array = array_filter(array_map('trim', explode("\n", strip_tags($features))));
                                if ( !empty($features_array) ) :
                            ?>
                                <ul class="plan-features-modern">
                                    <?php foreach ( $features_array as $feature ) : ?>
                                        <li>
                                            <div class="feature-check">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <span><?php echo esc_html( $feature ); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php
                                endif;
                            endif; ?>

                            <a href="<?php echo esc_url( get_contact_page_url() ); ?>" class="plan-cta-modern">
                                <span class="cta-bg"></span>
                                <span class="cta-text">このプランで相談する</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>

                            <div class="plan-decoration"></div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <?php endif; // ACFが設定されていない場合は料金セクションを非表示 ?>

        <!-- サービスの流れセクション -->
        <?php 
        // デバッグ: ACFの状態を確認
        if ( defined('WP_DEBUG') && WP_DEBUG ) {
            echo '<!-- Debug: ACF Functions - get_field: ' . (function_exists('get_field') ? 'Yes' : 'No') . ', have_rows: ' . (function_exists('have_rows') ? 'Yes' : 'No') . ' -->';
            if ( function_exists('get_field') ) {
                $process_data = get_field('service_process');
                echo '<!-- Debug: service_process data: ' . ($process_data ? 'Found (' . count($process_data) . ' items)' : 'Not found') . ' -->';
            }
        }
        
        $has_process = false;
        if ( function_exists('get_field') && function_exists('have_rows') ) {
            $has_process = have_rows('service_process');
        }
        
        if ( $has_process ) : ?>
        <section class="service-process-modern">
            <div class="process-bg">
                <div class="process-pattern"></div>
            </div>
            <div class="container">
                <div class="section-header-centered">
                    <div class="section-badge-large">
                        <i class="fas fa-route"></i>
                        <span>Process</span>
                    </div>
                    <h2 class="section-title-large">
                        <span class="title-ja">サービスの流れ</span>
                        <span class="title-accent">ご契約からサービス開始まで</span>
                    </h2>
                </div>

                <div class="process-flow">
                    <?php
                    $step_count = 0;
                    $total_steps = 0;
                    while ( have_rows('service_process') ) { the_row(); $total_steps++; }

                    while ( have_rows('service_process') ) : the_row();
                        $step_count++;
                        $title = get_sub_field('title') ? get_sub_field('title') : 'ステップ ' . $step_count;
                        $desc = get_sub_field('description') ? get_sub_field('description') : '';
                        $is_last = ($step_count === $total_steps);
                    ?>
                        <div class="process-step-modern" data-step="<?php echo $step_count; ?>">
                            <div class="step-connector" <?php if ($is_last) echo 'style="display:none;"'; ?>></div>
                            <div class="step-badge">
                                <div class="badge-circle">
                                    <span class="badge-number">STEP <?php echo $step_count; ?></span>
                                </div>
                                <div class="badge-glow"></div>
                            </div>
                            <div class="step-card">
                                <h3 class="step-title-modern"><?php echo esc_html( $title ); ?></h3>
                                <p class="step-description-modern"><?php echo esc_html( $desc ); ?></p>
                                <div class="step-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <?php else : ?>
            <!-- デバッグ: サービスの流れが表示されない場合 -->
            <?php if ( current_user_can('manage_options') ) : ?>
                <section class="service-process-debug" style="background-color: #f0f0f0; padding: 20px; margin: 20px 0;">
                    <div class="container">
                        <p style="color: #666;">
                            <strong>管理者向けデバッグ情報:</strong><br>
                            「サービスの流れ」セクションが表示されていません。<br>
                            WordPressの管理画面でこのサービスを編集し、「サービスの流れ」にデータを追加してください。<br>
                            ACF Pro がインストール・有効化されていることを確認してください。
                        </p>
                    </div>
                </section>
            <?php endif; ?>
        <?php endif; ?>

        <!-- よくある質問セクション -->
        <?php 
        // デバッグ: FAQフィールドの状態を確認
        if ( defined('WP_DEBUG') && WP_DEBUG ) {
            if ( function_exists('get_field') ) {
                $faq_data = get_field('service_faq');
                echo '<!-- Debug: service_faq data: ' . ($faq_data ? 'Found (' . count($faq_data) . ' items)' : 'Not found') . ' -->';
            }
        }
        
        $has_faq = false;
        if ( function_exists('get_field') && function_exists('have_rows') ) {
            $has_faq = have_rows('service_faq');
        }
        
        if ( $has_faq ) : ?>
        <section class="service-faq-modern">
            <div class="container">
                <div class="section-header-centered">
                    <div class="section-badge-large">
                        <i class="fas fa-question-circle"></i>
                        <span>FAQ</span>
                    </div>
                    <h2 class="section-title-large">
                        <span class="title-ja">よくある質問</span>
                        <span class="title-accent">お客様からよくいただく質問</span>
                    </h2>
                </div>

                <div class="faq-accordion">
                    <?php
                    $faq_index = 0;
                    while ( have_rows('service_faq') ) : the_row();
                        $faq_index++;
                        $question = get_sub_field('question') ? get_sub_field('question') : '質問';
                        $answer = get_sub_field('answer') ? get_sub_field('answer') : '';
                    ?>
                        <div class="faq-item-modern" data-faq="<?php echo $faq_index; ?>">
                            <button class="faq-question-modern" type="button" aria-expanded="false">
                                <div class="question-left">
                                    <span class="question-badge">Q<?php echo $faq_index; ?></span>
                                    <span class="question-text-modern"><?php echo esc_html( $question ); ?></span>
                                </div>
                                <span class="question-toggle">
                                    <i class="fas fa-plus"></i>
                                </span>
                            </button>
                            <div class="faq-answer-modern">
                                <div class="answer-wrapper">
                                    <span class="answer-badge">A</span>
                                    <div class="answer-content-modern">
                                        <?php echo wp_kses_post( wpautop( $answer ) ); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <?php else : ?>
            <!-- デバッグ: FAQが表示されない場合 -->
            <?php if ( current_user_can('manage_options') ) : ?>
                <section class="service-faq-debug" style="background-color: #f0f0f0; padding: 20px; margin: 20px 0;">
                    <div class="container">
                        <p style="color: #666;">
                            <strong>管理者向けデバッグ情報:</strong><br>
                            「よくある質問」セクションが表示されていません。<br>
                            WordPressの管理画面でこのサービスを編集し、「よくある質問（FAQ）」にデータを追加してください。<br>
                            ACF Pro がインストール・有効化されていることを確認してください。
                        </p>
                    </div>
                </section>
            <?php endif; ?>
        <?php endif; ?>

        <!-- お問い合わせCTA -->
        <section class="service-cta">
            <div class="container">
                <h2>まずはお気軽にご相談ください</h2>
                <p>サービスに関するご質問やご相談は、下記フォームまたはお電話にてお気軽にお問い合わせください。</p>
                <div class="cta-buttons">
                    <a href="<?php echo esc_url( get_contact_page_url() ); ?>" class="btn-primary">
                        <i class="fas fa-envelope"></i>
                        お問い合わせフォーム
                    </a>
                    <a href="tel:080-6946-4006" class="btn-secondary">
                        <i class="fas fa-phone"></i>
                        080-6946-4006
                    </a>
                </div>
            </div>
        </section>

        <!-- 関連サービス - 新しいUXデザイン -->
        <?php
        // デバッグ情報（本番環境では削除）
        // echo '<!-- Debug: Single Service Page Loaded -->';
        
        // service_categoryタクソノミーが存在しない場合はシンプルな関連サービス取得
        $related_args = array(
            'post_type' => 'service',
            'posts_per_page' => 6,
            'post__not_in' => array( get_the_ID() ),
            'orderby' => 'menu_order date',
            'order' => 'DESC',
        );
        
        // タクソノミーが存在する場合のみカテゴリーフィルタリング
        if ( taxonomy_exists( 'service_category' ) ) {
            $current_categories = wp_get_post_terms( get_the_ID(), 'service_category', array( 'fields' => 'ids' ) );
            
            if ( !empty( $current_categories ) && !is_wp_error( $current_categories ) ) {
                $related_args['tax_query'] = array(
                    array(
                        'taxonomy' => 'service_category',
                        'field' => 'term_id',
                        'terms' => $current_categories,
                    ),
                );
            }
        }
        
        $related_services = new WP_Query( $related_args );
        
        // デバッグ: クエリ結果を確認
        if ( defined('WP_DEBUG') && WP_DEBUG ) {
            echo '<!-- Debug: Found ' . $related_services->found_posts . ' related services -->';
        }
        
        if ( $related_services->have_posts() ) : ?>
        <section class="service-related-modern">
            <div class="related-bg-effect">
                <div class="bg-gradient-effect"></div>
                <div class="bg-pattern-dots"></div>
            </div>
            
            <div class="container">
                <div class="related-header">
                    <div class="section-badge">
                        <i class="fas fa-link"></i>
                        <span>Related Services</span>
                    </div>
                    <h2 class="related-title">
                        <span class="title-main">おすすめの関連サービス</span>
                        <span class="title-sub">あなたのビジネスに最適なソリューションをご提案</span>
                    </h2>
                    
                    <!-- カテゴリーフィルター（タクソノミーが存在する場合のみ表示） -->
                    <?php 
                    if ( taxonomy_exists( 'service_category' ) ) :
                        $all_categories = get_terms( array(
                            'taxonomy' => 'service_category',
                            'hide_empty' => true,
                        ) );
                        
                        if ( !is_wp_error( $all_categories ) && !empty( $all_categories ) ) : ?>
                        <div class="category-filter">
                            <button class="filter-btn active" data-filter="all">
                                <span>すべて</span>
                            </button>
                            <?php foreach ( $all_categories as $category ) : ?>
                                <button class="filter-btn" data-filter="<?php echo esc_attr( $category->slug ); ?>">
                                    <span><?php echo esc_html( $category->name ); ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                        <?php endif;
                    endif; ?>
                </div>
                
                <div class="related-services-wrapper">
                    <div class="services-carousel" id="related-services-carousel">
                        <?php 
                        $service_index = 0;
                        while ( $related_services->have_posts() ) : $related_services->the_post(); 
                            $service_index++;
                            
                            // カテゴリー取得（タクソノミーが存在する場合のみ）
                            $category_classes = array();
                            $category_names = array();
                            
                            if ( taxonomy_exists( 'service_category' ) ) {
                                $categories = wp_get_post_terms( get_the_ID(), 'service_category' );
                                
                                if ( !is_wp_error( $categories ) && !empty( $categories ) ) {
                                    foreach ( $categories as $cat ) {
                                        $category_classes[] = 'category-' . $cat->slug;
                                        $category_names[] = $cat->name;
                                    }
                                }
                            }
                            
                            // おすすめ度（ランダムまたはACFフィールドから）
                            $is_recommended = ( $service_index <= 2 ) || ( rand(1, 10) > 7 );
                            
                            // 価格情報（ACFから取得）
                            $price_range = '';
                            if ( function_exists('get_field') ) {
                                $price_range = get_field('service_price', get_the_ID());
                            }
                            if ( empty( $price_range ) ) {
                                $price_range = 'お見積もり';
                            }
                        ?>
                        
                        <article class="related-service-card <?php echo implode( ' ', $category_classes ); ?>" data-service-id="<?php the_ID(); ?>">
                            <?php if ( $is_recommended ) : ?>
                                <div class="recommended-badge">
                                    <i class="fas fa-star"></i>
                                    <span>おすすめ</span>
                                </div>
                            <?php endif; ?>
                            
                            <a href="<?php the_permalink(); ?>" class="card-link">
                                <div class="card-image-wrapper">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'medium_large', array( 
                                            'class' => 'card-image',
                                            'loading' => 'lazy'
                                        ) ); ?>
                                    <?php else : ?>
                                        <div class="card-image-placeholder">
                                            <i class="fas fa-briefcase"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="card-overlay">
                                        <span class="view-detail">
                                            <i class="fas fa-arrow-right"></i>
                                            詳細を見る
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="card-content">
                                    <?php if ( !empty( $category_names ) ) : ?>
                                        <div class="card-categories">
                                            <?php foreach ( $category_names as $cat_name ) : ?>
                                                <span class="category-tag"><?php echo esc_html( $cat_name ); ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <h3 class="card-title"><?php the_title(); ?></h3>
                                    
                                    <p class="card-excerpt">
                                        <?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
                                    </p>
                                    
                                    <div class="card-meta">
                                        <div class="meta-item">
                                            <i class="fas fa-yen-sign"></i>
                                            <span><?php echo esc_html( $price_range ); ?></span>
                                        </div>
                                        
                                        <?php 
                                        // 導入期間（ACFから取得）
                                        $duration = '';
                                        if ( function_exists('get_field') ) {
                                            $duration = get_field('service_duration', get_the_ID());
                                        }
                                        if ( empty( $duration ) ) {
                                            $duration = '要相談';
                                        }
                                        ?>
                                        <div class="meta-item">
                                            <i class="fas fa-clock"></i>
                                            <span><?php echo esc_html( $duration ); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="card-footer">
                                        <span class="learn-more">
                                            詳しく見る
                                            <i class="fas fa-chevron-right"></i>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </article>
                        
                        <?php endwhile; ?>
                    </div>
                    
                    <!-- カルーセルナビゲーション -->
                    <div class="carousel-navigation">
                        <button class="carousel-prev" aria-label="前のサービス">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="carousel-next" aria-label="次のサービス">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    
                    <!-- プログレスインジケーター -->
                    <div class="carousel-progress">
                        <div class="progress-track">
                            <div class="progress-fill"></div>
                        </div>
                    </div>
                </div>
                
                <!-- すべてのサービスを見るボタン -->
                <div class="related-footer">
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ); ?>" class="view-all-services">
                        <span class="btn-text">すべてのサービスを見る</span>
                        <span class="btn-icon">
                            <i class="fas fa-th-large"></i>
                        </span>
                    </a>
                </div>
            </div>
        </section>
        <?php endif; wp_reset_postdata(); ?>
        
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>