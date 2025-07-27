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
        <?php 
        $has_features = false;
        if ( function_exists('get_field') && function_exists('have_rows') ) {
            $has_features = have_rows('service_features');
        }
        
        if ( $has_features ) : ?>
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
                                <?php 
                                $icon = get_sub_field('icon');
                                if ( $icon ) : ?>
                                    <i class="<?php echo esc_attr( $icon ); ?>"></i>
                                <?php else : ?>
                                    <i class="fas fa-check-circle"></i>
                                <?php endif; ?>
                            </div>
                            <h3 class="feature-title">
                                <?php 
                                $title = get_sub_field('title');
                                echo $title ? esc_html( $title ) : 'Feature';
                                ?>
                            </h3>
                            <p class="feature-description">
                                <?php 
                                $desc = get_sub_field('description');
                                echo $desc ? esc_html( $desc ) : '';
                                ?>
                            </p>
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
        <section class="service-pricing">
            <div class="container">
                <h2 class="section-title text-center">
                    <span class="title-en">Pricing</span>
                    <span class="title-ja">料金プラン</span>
                </h2>
                
                <div class="pricing-grid">
                    <?php while ( have_rows('service_plans') ) : the_row(); ?>
                        <div class="pricing-card <?php echo get_sub_field('recommended') ? 'recommended' : ''; ?>">
                            <?php if ( get_sub_field('recommended') ) : ?>
                                <div class="recommended-badge">おすすめ</div>
                            <?php endif; ?>
                            
                            <h3 class="plan-name">
                                <?php 
                                $name = get_sub_field('name');
                                echo $name ? esc_html( $name ) : 'プラン';
                                ?>
                            </h3>
                            <div class="plan-price">
                                <?php 
                                $price = get_sub_field('price');
                                // Extract number from price string (e.g., "¥100,000〜" -> "100,000")
                                $price_number = preg_replace('/[^0-9,]/', '', $price);
                                $price_unit = '';
                                if ( strpos($price, '〜') !== false ) {
                                    $price_unit = '〜';
                                } elseif ( strpos($price, '/月') !== false ) {
                                    $price_unit = '/月';
                                }
                                ?>
                                <span class="price-currency">¥</span>
                                <span class="price-amount">
                                    <?php echo $price_number ? esc_html( $price_number ) : '0'; ?>
                                </span>
                                <span class="price-unit">
                                    <?php echo esc_html( $price_unit ); ?>
                                </span>
                            </div>
                            
                            <?php 
                            $features = get_sub_field('features');
                            if ( $features ) : 
                                // Convert line breaks to array
                                $features_array = array_filter(array_map('trim', explode("\n", strip_tags($features))));
                                if ( !empty($features_array) ) :
                            ?>
                                <ul class="plan-features">
                                    <?php foreach ( $features_array as $feature ) : ?>
                                        <li>
                                            <i class="fas fa-check"></i>
                                            <?php echo esc_html( $feature ); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php 
                                endif;
                            endif; ?>
                            
                            <a href="#service-inquiry" class="plan-cta smooth-scroll">
                                このプランで相談する
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <?php endif; // ACFが設定されていない場合は料金セクションを非表示 ?>

        <!-- お問い合わせCTA -->
        <section class="service-cta">
            <div class="container">
                <h2>まずはお気軽にご相談ください</h2>
                <p>サービスに関するご質問やご相談は、下記フォームまたはお電話にてお気軽にお問い合わせください。</p>
                <div class="cta-buttons">
                    <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn-primary">
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
        // 現在のサービスのカテゴリーを取得
        $current_categories = wp_get_post_terms( get_the_ID(), 'service_category', array( 'fields' => 'ids' ) );
        
        // 関連サービスを取得（同じカテゴリーを優先）
        $related_args = array(
            'post_type' => 'service',
            'posts_per_page' => 6,
            'post__not_in' => array( get_the_ID() ),
            'orderby' => 'menu_order date',
            'order' => 'DESC',
        );
        
        if ( !empty( $current_categories ) ) {
            $related_args['tax_query'] = array(
                array(
                    'taxonomy' => 'service_category',
                    'field' => 'term_id',
                    'terms' => $current_categories,
                ),
            );
        }
        
        $related_services = new WP_Query( $related_args );
        
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
                    
                    <!-- カテゴリーフィルター -->
                    <?php 
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
                    <?php endif; ?>
                </div>
                
                <div class="related-services-wrapper">
                    <div class="services-carousel" id="related-services-carousel">
                        <?php 
                        $service_index = 0;
                        while ( $related_services->have_posts() ) : $related_services->the_post(); 
                            $service_index++;
                            
                            // カテゴリー取得
                            $categories = wp_get_post_terms( get_the_ID(), 'service_category' );
                            $category_classes = array();
                            $category_names = array();
                            
                            if ( !is_wp_error( $categories ) && !empty( $categories ) ) {
                                foreach ( $categories as $cat ) {
                                    $category_classes[] = 'category-' . $cat->slug;
                                    $category_names[] = $cat->name;
                                }
                            }
                            
                            // おすすめ度（ランダムまたはACFフィールドから）
                            $is_recommended = ( $service_index <= 2 ) || ( rand(1, 10) > 7 );
                            
                            // 価格情報（ACFから取得、なければダミー）
                            $price_range = '';
                            if ( function_exists('get_field') ) {
                                $price_range = get_field('service_price_range');
                            }
                            if ( empty( $price_range ) ) {
                                $price_ranges = array( '¥50,000〜', '¥100,000〜', '¥200,000〜', 'お見積もり' );
                                $price_range = $price_ranges[array_rand($price_ranges)];
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
                                        // 導入期間（ダミーデータ）
                                        $durations = array( '最短1週間', '2週間〜', '1ヶ月〜', '要相談' );
                                        $duration = $durations[array_rand($durations)];
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