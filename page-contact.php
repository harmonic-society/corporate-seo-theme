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
                
                <!-- 右側：コンタクト情報 -->
                <div class="contact-info-area">
                    <div class="info-card">
                        <h3 class="info-title">直接のお問い合わせ</h3>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">お電話でのお問い合わせ</span>
                                <a href="tel:08069464006" class="info-link phone-link">
                                    <span class="phone-number">080-6946-4006</span>
                                    <span class="phone-hours">平日 9:00-18:00</span>
                                </a>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">メールでのお問い合わせ</span>
                                <a href="mailto:koushiki@harmonic-society.co.jp" class="info-link">
                                    koushiki@harmonic-society.co.jp
                                </a>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">オフィス所在地</span>
                                <address class="info-address">
                                    〒262-0033<br>
                                    千葉県千葉市花見川区幕張本郷3-31-8
                                </address>
                            </div>
                        </div>
                    </div>
                    
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
    
    
    <!-- CTAセクション -->
    <section class="contact-cta">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">まずは気軽にご相談ください</h2>
                <p class="cta-text">
                    あなたのビジネスの課題を、<br>
                    私たちと一緒に解決していきましょう
                </p>
                <div class="cta-features">
                    <div class="cta-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>初回相談無料</span>
                    </div>
                    <div class="cta-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>24時間以内返信</span>
                    </div>
                    <div class="cta-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>全国対応可能</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
</main>

<?php get_footer();