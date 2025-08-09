<?php
/**
 * Template Name: Contact
 * お問い合わせページテンプレート
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main contact-page">
    
    <!-- LINE CTAヒーローセクション -->
    <section class="contact-hero-cta">
        <div class="container">
            <div class="hero-cta-content">
                <h1 class="hero-cta-title">LINEで気軽にご相談ください</h1>
                <p class="hero-cta-text">
                    まずはLINE公式アカウントを友だち追加して、<br>
                    お気軽にご相談ください
                </p>
                <div class="hero-cta-features">
                    <div class="hero-cta-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>AI活用事例を配信</span>
                    </div>
                    <div class="hero-cta-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>無料相談受付中</span>
                    </div>
                    <div class="hero-cta-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>1対1で気軽に相談</span>
                    </div>
                </div>
                <div class="hero-cta-button-wrapper">
                    <a href="https://lin.ee/mQTrmxJ" 
                       class="hero-cta-button" 
                       target="_blank" 
                       rel="noopener noreferrer">
                        <i class="fab fa-line"></i>
                        <span>LINE友だち追加はこちら</span>
                    </a>
                </div>
            </div>
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
        </div>
    </section>
    
</main>

<?php get_footer();