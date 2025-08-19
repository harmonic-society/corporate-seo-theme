<?php
/**
 * 実績一覧ページテンプレート
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main work-archive-page">
    <!-- ヒーローセクション -->
    <section class="work-hero">
        <div class="hero-bg-wrapper">
            <div class="hero-bg-pattern"></div>
            <div class="hero-gradient-1"></div>
            <div class="hero-gradient-2"></div>
            <div class="hero-particles">
                <span></span><span></span><span></span>
                <span></span><span></span><span></span>
            </div>
        </div>
        
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <span class="badge-text">Our Portfolio</span>
                </div>
                
                <h1 class="hero-title">
                    <span class="title-main">WORKS</span>
                    <span class="title-sub">私たちの実績</span>
                </h1>
                
                <p class="hero-description">
                    Harmonic Societyが手がけた数々のプロジェクト。<br>
                    お客様の成功と共に歩んできた軌跡をご覧ください。
                </p>
                
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number" data-count="150">0</span>
                        <span class="stat-label">プロジェクト</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" data-count="98">0</span>
                        <span class="stat-label">%顧客満足度</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" data-count="95">0</span>
                        <span class="stat-label">%リピート率</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="hero-scroll">
            <span class="scroll-text">Scroll</span>
            <span class="scroll-arrow"></span>
        </div>
    </section>

    <!-- カテゴリーフィルター -->
    <section class="work-filter-section">
        <div class="container">
            <div class="filter-wrapper">
                <button class="filter-btn active" data-filter="all">
                    <span class="filter-text">すべて</span>
                    <span class="filter-count"><?php echo wp_count_posts('work')->publish; ?></span>
                </button>
                
                <?php
                $categories = get_terms( array(
                    'taxonomy' => 'work_category',
                    'hide_empty' => true,
                ) );
                
                if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
                    foreach ( $categories as $category ) : ?>
                        <button class="filter-btn" data-filter="<?php echo esc_attr( $category->slug ); ?>">
                            <span class="filter-text"><?php echo esc_html( $category->name ); ?></span>
                            <span class="filter-count"><?php echo $category->count; ?></span>
                        </button>
                    <?php endforeach;
                endif; ?>
            </div>
        </div>
    </section>

    <!-- 実績グリッド -->
    <section class="work-grid-section">
        <div class="container">
            <?php if ( have_posts() ) : ?>
                <div class="work-grid">
                    <?php 
                    $work_count = 0;
                    while ( have_posts() ) : 
                        the_post(); 
                        $work_count++;
                        $categories = get_the_terms( get_the_ID(), 'work_category' );
                        $category_classes = '';
                        $category_names = array();
                        
                        if ( $categories && ! is_wp_error( $categories ) ) {
                            foreach ( $categories as $category ) {
                                $category_classes .= ' ' . $category->slug;
                                $category_names[] = $category->name;
                            }
                        }
                    ?>
                        <article class="work-item<?php echo esc_attr( $category_classes ); ?>" data-work="<?php echo $work_count; ?>">
                            <a href="<?php the_permalink(); ?>" class="work-link">
                                <div class="work-image-wrapper">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <div class="work-image">
                                            <?php the_post_thumbnail( 'large' ); ?>
                                        </div>
                                    <?php else : ?>
                                        <div class="work-image work-no-image">
                                            <div class="no-image-placeholder">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="work-overlay">
                                        <div class="overlay-content">
                                            <span class="overlay-icon">
                                                <i class="fas fa-arrow-right"></i>
                                            </span>
                                            <span class="overlay-text">詳細を見る</span>
                                        </div>
                                    </div>
                                    
                                    <div class="work-number">
                                        <?php echo sprintf( '%02d', $work_count ); ?>
                                    </div>
                                </div>
                                
                                <div class="work-info">
                                    <?php if ( ! empty( $category_names ) ) : ?>
                                        <div class="work-categories">
                                            <?php foreach ( $category_names as $cat_name ) : ?>
                                                <span class="work-category"><?php echo esc_html( $cat_name ); ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <h3 class="work-title"><?php the_title(); ?></h3>
                                    
                                    <?php if ( function_exists('get_field') && get_field('work_client') ) : ?>
                                        <div class="work-client">
                                            <span class="client-label">Client:</span>
                                            <span class="client-name"><?php the_field('work_client'); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="work-excerpt">
                                        <?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
                                    </div>
                                    
                                    <?php if ( function_exists('get_field') ) : 
                                        $technologies = get_field('work_technologies');
                                        if ( $technologies ) : ?>
                                            <div class="work-technologies">
                                                <?php 
                                                $tech_array = is_array($technologies) ? $technologies : explode(',', $technologies);
                                                foreach ( array_slice($tech_array, 0, 3) as $tech ) : ?>
                                                    <span class="tech-tag"><?php echo esc_html( trim($tech) ); ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif;
                                    endif; ?>
                                </div>
                            </a>
                        </article>
                    <?php endwhile; ?>
                </div>

                <!-- ページネーション -->
                <?php if ( get_the_posts_pagination() ) : ?>
                    <div class="work-pagination">
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
                <div class="no-works">
                    <div class="no-works-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h2>実績が見つかりませんでした</h2>
                    <p>現在、表示できる実績がありません。</p>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="back-home">
                        <i class="fas fa-home"></i>
                        ホームに戻る
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- CTAセクション -->
    <section class="work-cta-section">
        <div class="cta-bg-effect">
            <div class="cta-gradient"></div>
            <div class="cta-pattern"></div>
        </div>
        
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">
                    <span class="title-main">あなたのプロジェクトも</span>
                    <span class="title-sub">成功へと導きます</span>
                </h2>
                
                <p class="cta-description">
                    実績と経験を活かし、お客様のビジネスに最適なソリューションをご提供します。
                </p>
                
                <div class="cta-buttons">
                    <a href="<?php echo esc_url( get_contact_page_url() ); ?>" class="cta-button primary">
                        <span class="button-text">プロジェクトの相談をする</span>
                        <span class="button-icon"><i class="fas fa-arrow-right"></i></span>
                    </a>
                    
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ); ?>" class="cta-button secondary">
                        <span class="button-text">サービスを見る</span>
                        <span class="button-icon"><i class="fas fa-chevron-right"></i></span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>