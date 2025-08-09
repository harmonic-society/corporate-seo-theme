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
            <div class="contact-wrapper">
                
                <!-- 左側：コンタクトフォーム -->
                <div class="contact-form-area">
                    <div class="form-header">
                        <h2 class="form-title">お問い合わせフォーム</h2>
                        <p class="form-subtitle">必要事項をご記入の上、送信してください</p>
                    </div>
                    
                    <?php 
                    // カスタムコンタクトフォームを表示
                    echo do_shortcode('[custom_contact_form]');
                    ?>
                </div>
                
                <!-- 右側：LINE CTA -->
                <div class="contact-info-area">
                    <!-- LINE公式アカウントCTA -->
                    <?php get_template_part( 'template-parts/line-cta' ); ?>
                    
                    <!-- FAQ -->
                    <div class="faq-card">
                        <h3 class="faq-title">よくあるご質問</h3>
                        
                        <div class="faq-item">
                            <button class="faq-question" aria-expanded="false">
                                <span>初回相談は無料ですか？</span>
                                <i class="fas fa-plus"></i>
                            </button>
                            <div class="faq-answer">
                                <p>はい、初回のご相談は無料で承っております。お気軽にお問い合わせください。</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <button class="faq-question" aria-expanded="false">
                                <span>対応エリアを教えてください</span>
                                <i class="fas fa-plus"></i>
                            </button>
                            <div class="faq-answer">
                                <p>全国対応可能です。オンラインでのご相談も承っております。</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <button class="faq-question" aria-expanded="false">
                                <span>返信までどのくらいかかりますか？</span>
                                <i class="fas fa-plus"></i>
                            </button>
                            <div class="faq-answer">
                                <p>原則として24時間以内にご返信いたします。土日祝日の場合は翌営業日となります。</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    
    <!-- LINE CTAセクション -->
    <section class="contact-cta">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">LINEで気軽にご相談ください</h2>
                <p class="cta-text">
                    まずはLINE公式アカウントを友だち追加して、<br>
                    お気軽にご相談ください
                </p>
                <div class="cta-features">
                    <div class="cta-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>AI活用事例を配信</span>
                    </div>
                    <div class="cta-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>無料相談受付中</span>
                    </div>
                    <div class="cta-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>1対1で気軽に相談</span>
                    </div>
                </div>
                <div class="cta-button-wrapper" style="text-align: center; margin-top: 2rem;">
                    <a href="https://lin.ee/mQTrmxJ" 
                       class="btn btn-primary btn-lg" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 1rem 2.5rem; background-color: #06C755; color: white; text-decoration: none; border-radius: 50px; font-weight: bold;">
                        <i class="fab fa-line" style="font-size: 1.3rem;"></i>
                        <span>LINE友だち追加はこちら</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
    
</main>

<?php get_footer();