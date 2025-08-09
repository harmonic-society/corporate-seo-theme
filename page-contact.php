<?php
/**
 * Template Name: Contact
 * お問い合わせページテンプレート
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main contact-page">
    
    <!-- コンタクトヒーローセクション -->
    <section class="contact-hero">
        <!-- 動的背景 -->
        <div class="contact-bg-animation">
            <div class="bg-gradient"></div>
            <div class="bg-particles">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="bg-waves">
                <div class="wave wave-1"></div>
                <div class="wave wave-2"></div>
                <div class="wave wave-3"></div>
            </div>
        </div>
        
        <div class="container">
            <div class="hero-content">
                <span class="contact-subtitle">Contact Us</span>
                <h1 class="contact-title">
                    <span class="title-line">共に創る</span>
                    <span class="title-line">未来への第一歩</span>
                </h1>
                <p class="contact-description">
                    あなたのビジョンを実現するパートナーとして、<br>
                    Harmonic Societyがお手伝いします
                </p>
                
                <!-- 信頼性指標 -->
                <div class="trust-indicators">
                    <div class="indicator">
                        <span class="indicator-number count-up" data-target="98">0</span>
                        <span class="indicator-suffix">%</span>
                        <span class="indicator-label">顧客満足度</span>
                    </div>
                    <div class="indicator">
                        <span class="indicator-number count-up" data-target="24">0</span>
                        <span class="indicator-suffix">時間</span>
                        <span class="indicator-label">以内に返信</span>
                    </div>
                    <div class="indicator">
                        <span class="indicator-number count-up" data-target="150">0</span>
                        <span class="indicator-suffix">+</span>
                        <span class="indicator-label">プロジェクト実績</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- スクロールプロンプト -->
        <div class="scroll-prompt">
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    <!-- コンタクトメインセクション -->
    <section class="contact-main">
        <div class="container">
            <!-- ワンカラムフォーム -->
            <div class="contact-form-single">
                <div class="form-header">
                    <h2 class="form-title">お問い合わせフォーム</h2>
                    <p class="form-subtitle">必要事項をご記入の上、送信してください</p>
                </div>
                
                <?php 
                // カスタムコンタクトフォームを表示
                echo do_shortcode('[custom_contact_form]');
                ?>
            </div>
            
            <!-- LINE CTA -->
            <div class="contact-line-cta">
                <?php get_template_part( 'template-parts/line-cta' ); ?>
            </div>
        </div>
    </section>
    
</main>

<?php get_footer();