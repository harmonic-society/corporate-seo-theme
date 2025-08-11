<?php
/**
 * Modern Hero Section Template
 * 
 * Harmonic Society - シンプルでモダンな洗練されたデザイン
 */
?>

<section class="hero-modern">
    <!-- 背景画像 -->
    <?php 
    $hero_bg_image = get_theme_mod( 'hero_modern_bg_image', '' );
    if ( $hero_bg_image ) : 
    ?>
    <div class="hero-bg-image" style="background-image: url('<?php echo esc_url( $hero_bg_image ); ?>');"></div>
    <div class="hero-bg-overlay" style="opacity: <?php echo esc_attr( get_theme_mod( 'hero_bg_overlay_opacity', '0.8' ) ); ?>"></div>
    <?php else : ?>
    <!-- 動的背景グラデーション -->
    <div class="hero-gradient-bg"></div>
    <?php endif; ?>
    
    <!-- 幾何学的アクセント -->
    <div class="hero-geometric-accent">
        <div class="geometric-shape"></div>
        <div class="geometric-shape"></div>
        <div class="geometric-shape"></div>
    </div>
    
    <!-- メインコンテンツ -->
    <div class="hero-content-modern">
        <div class="hero-content-inner">
            <!-- テキストセクション -->
            <div class="hero-text-section">
                <div class="hero-subtitle">AI活用 × 集客設計 × Web制作</div>
                
                <h1 class="hero-title-modern">
                    つくるだけで終わらせない。<br>
                    <span class="hero-title-gradient">成果まで一緒に。</span>
                </h1>
                
                <p class="hero-description">
                    LP制作、LINE運用、広告設計までワンストップ。<br>
                    AI活用で、最短距離の集客を実現します。
                </p>
                
                <div class="hero-cta-group">
                    <a href="/contact" class="hero-btn-primary">
                        <span>無料相談してみる</span>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M7 13L10 10L7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10 10H3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </a>
                    
                    <a href="/works" class="hero-btn-secondary">
                        <span>制作事例を見る</span>
                    </a>
                </div>
            </div>
            
            <!-- ビジュアルセクション -->
            <?php 
            $hero_image = get_theme_mod( 'hero_modern_image', '' );
            if ( $hero_image ) : 
            ?>
            <div class="hero-visual-section">
                <div class="hero-visual-container">
                    <div class="hero-image-wrapper">
                        <img src="<?php echo esc_url( $hero_image ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="hero-featured-image">
                        <div class="hero-image-overlay"></div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- スクロールインジケーター -->
    <div class="hero-scroll-indicator">
        <div class="scroll-mouse">
            <div class="scroll-wheel"></div>
        </div>
    </div>
</section>