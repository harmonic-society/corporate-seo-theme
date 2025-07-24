<?php
/**
 * Template Name: About
 * 会社概要ページテンプレート
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main about-page">
    
    <!-- ヒーローセクション -->
    <section class="about-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="title-label">社名の由来</span>
                    <span class="title-main">Harmonic Society <span class="equals">=</span> 「社会調和」</span>
                </h1>
                
                <div class="hero-values">
                    <h2 class="values-title">3つの調和</h2>
                    <div class="values-grid">
                        <div class="value-item" data-value="1">
                            <div class="value-circle">
                                <div class="value-icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="value-number">01</div>
                            </div>
                            <h3 class="value-title">優しいDXの推進</h3>
                        </div>
                        
                        <div class="value-item" data-value="2">
                            <div class="value-circle">
                                <div class="value-icon">
                                    <i class="fas fa-robot"></i>
                                </div>
                                <div class="value-number">02</div>
                            </div>
                            <h3 class="value-title">AIとの共存・共創</h3>
                        </div>
                        
                        <div class="value-item" data-value="3">
                            <div class="value-circle">
                                <div class="value-icon">
                                    <i class="fas fa-network-wired"></i>
                                </div>
                                <div class="value-number">03</div>
                            </div>
                            <h3 class="value-title">ポストWeb3.0</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="hero-wave">
            <svg viewBox="0 0 1440 200" preserveAspectRatio="none">
                <path d="M0,100 C320,20 640,180 960,100 C1280,20 1440,100 1440,100 L1440,200 L0,200 Z" fill="#f8f9fa"></path>
            </svg>
        </div>
    </section>

    <!-- ビジョン・ミッションセクション -->
    <section class="vision-section">
        <div class="container">
            <div class="vision-wrapper">
                <div class="vision-visual">
                    <div class="circle-wrapper">
                        <div class="circle circle-vision">
                            <h3>Vision</h3>
                        </div>
                        <div class="circle circle-purpose">
                            <h3>Purpose</h3>
                        </div>
                        <div class="circle circle-mission">
                            <h3>Mission</h3>
                        </div>
                        <div class="circle circle-values">
                            <h3>Values</h3>
                        </div>
                    </div>
                </div>
                
                <div class="vision-content">
                    <div class="vision-item">
                        <h3 class="item-title">Vision</h3>
                        <p class="item-text">Harmonic Society（社会の調和）</p>
                    </div>
                    
                    <div class="vision-item">
                        <h3 class="item-title">Purpose</h3>
                        <p class="item-text">純粋さを大切に<br>夢中でいられる社会をつくる</p>
                    </div>
                    
                    <div class="vision-item">
                        <h3 class="item-title">Mission</h3>
                        <p class="item-text">小さな一歩から調和をつくっていく</p>
                    </div>
                    
                    <div class="vision-item">
                        <h3 class="item-title">Values</h3>
                        <ul class="values-list">
                            <li><strong>Work as Life</strong>（仕事は生き方）</li>
                            <li><strong>0+1 Respects</strong>（クリエイターファースト）</li>
                            <li><strong>Connected, Anywhere</strong>（どこでも繋がる）</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 会社概要セクション -->
    <section class="company-info-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="title-en">About</span>
                    <span class="title-ja">会社概要</span>
                </h2>
            </div>
            
            <div class="company-info-wrapper">
                <div class="info-content">
                    <dl class="info-list">
                        <div class="info-item">
                            <dt>社名</dt>
                            <dd>Harmonic Society株式会社</dd>
                        </div>
                        
                        <div class="info-item">
                            <dt>本社</dt>
                            <dd>〒262-0033 千葉県千葉市花見川区幕張本郷3-31-8</dd>
                        </div>
                        
                        <div class="info-item">
                            <dt>HP</dt>
                            <dd><a href="https://harmonic-society.co.jp" target="_blank" rel="noopener">https://harmonic-society.co.jp</a></dd>
                        </div>
                        
                        <div class="info-item">
                            <dt>代表者</dt>
                            <dd>代表取締役　師田賢人</dd>
                        </div>
                        
                        <div class="info-item">
                            <dt>設立</dt>
                            <dd>2023年3月3日</dd>
                        </div>
                        
                        <div class="info-item">
                            <dt>資本金</dt>
                            <dd>1,000,000円</dd>
                        </div>
                    </dl>
                </div>
                
                <div class="info-illustration">
                    <div class="illustration-wrapper">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/about-illustration.svg" alt="会社概要イラスト" class="illustration">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTAセクション -->
    <section class="about-cta">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">調和ある社会を共に創りませんか？</h2>
                <p class="cta-text">Harmonic Societyは、テクノロジーと人間性の調和を通じて、<br>より良い社会の実現を目指しています。</p>
                <a href="<?php echo esc_url( get_contact_page_url() ); ?>" class="btn btn-primary btn-large">
                    お問い合わせはこちら
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

</main>

<?php get_footer();