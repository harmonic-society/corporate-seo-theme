<?php
/**
 * Template Name: About
 * 会社概要ページテンプレート - 信頼感と理念を伝える
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main about-page">

    <!-- ヒーローセクション - 企業理念を前面に -->
    <section class="about-hero">
        <div class="hero-background">
            <div class="hero-particles"></div>
        </div>
        <div class="container">
            <div class="hero-content">
                <p class="hero-catchphrase">私たちが目指すのは</p>
                <h1 class="hero-title">
                    <span class="title-main">Harmonic Society</span>
                    <span class="title-sub">社会の調和</span>
                </h1>
                <p class="hero-description">
                    テクノロジーと人間性の調和を通じて、<br class="sp-only">
                    純粋さを大切に夢中でいられる社会をつくる
                </p>
                <div class="hero-scroll">
                    <span>Scroll</span>
                    <div class="scroll-line"></div>
                </div>
            </div>
        </div>
        <div class="hero-wave">
            <svg viewBox="0 0 1440 200" preserveAspectRatio="none">
                <path d="M0,100 C320,20 640,180 960,100 C1280,20 1440,100 1440,100 L1440,200 L0,200 Z" fill="#ffffff"></path>
            </svg>
        </div>
    </section>

    <!-- 代表メッセージセクション -->
    <section class="ceo-message-section">
        <div class="container">
            <div class="section-header">
                <span class="section-label">Message</span>
                <h2 class="section-title">代表メッセージ</h2>
            </div>
            <div class="ceo-content">
                <div class="ceo-image-wrapper">
                    <div class="ceo-image">
                        <?php
                        $ceo_image = get_theme_mod( 'ceo_profile_image' );
                        if ( $ceo_image ) :
                            ?>
                            <img src="<?php echo esc_url( $ceo_image ); ?>" alt="代表取締役 師田 賢人" class="ceo-photo">
                        <?php else : ?>
                            <div class="image-placeholder">
                                <i class="fas fa-user-tie"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="ceo-info">
                        <p class="ceo-role">代表取締役</p>
                        <p class="ceo-name">師田 賢人</p>
                        <p class="ceo-name-en">Kento Morota</p>
                    </div>
                </div>
                <div class="ceo-text">
                    <div class="message-quote">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <div class="message-content">
                        <p class="message-paragraph">
                            Harmonic Society株式会社は、「社会の調和」という理念のもと、<br>
                            テクノロジーと人間性の調和を実現することを使命としています。
                        </p>
                        <p class="message-paragraph">
                            現代社会において、デジタル技術の進化は目覚ましいものがありますが、<br>
                            その一方で、人と人とのつながりや、純粋に何かに夢中になれる時間が<br>
                            失われつつあるのではないかと感じています。
                        </p>
                        <p class="message-paragraph">
                            私たちは、優しいDXの推進、AIとの共存・共創、そしてポストWeb3.0の<br>
                            時代における新しい価値創造を通じて、誰もが純粋さを大切にし、<br>
                            夢中でいられる社会の実現を目指します。
                        </p>
                        <p class="message-paragraph">
                            小さな一歩から始まる調和が、やがて大きな変化をもたらすと信じて、<br>
                            私たちは日々挑戦を続けています。
                        </p>
                    </div>
                    <div class="message-signature">
                        <p>Harmonic Society株式会社</p>
                        <p class="signature-name">代表取締役　師田 賢人</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ビジョン・ミッション・バリューセクション -->
    <section class="vision-mission-section">
        <div class="container">
            <div class="section-header centered">
                <span class="section-label">Our Philosophy</span>
                <h2 class="section-title">企業理念</h2>
                <p class="section-description">
                    私たちの行動指針と、実現したい未来
                </p>
            </div>

            <div class="philosophy-grid">
                <!-- Vision -->
                <div class="philosophy-card vision-card">
                    <div class="card-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3 class="card-title">
                        <span class="title-en">Vision</span>
                        <span class="title-ja">ビジョン</span>
                    </h3>
                    <div class="card-content">
                        <p class="card-main-text">Harmonic Society</p>
                        <p class="card-sub-text">社会の調和</p>
                        <p class="card-description">
                            テクノロジーと人間性、効率と創造性、<br>
                            個人と社会が調和する未来を目指します
                        </p>
                    </div>
                </div>

                <!-- Purpose -->
                <div class="philosophy-card purpose-card">
                    <div class="card-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3 class="card-title">
                        <span class="title-en">Purpose</span>
                        <span class="title-ja">パーパス</span>
                    </h3>
                    <div class="card-content">
                        <p class="card-main-text">純粋さを大切に</p>
                        <p class="card-main-text">夢中でいられる社会をつくる</p>
                        <p class="card-description">
                            誰もが純粋な気持ちで挑戦し、<br>
                            夢中になれる環境を創造します
                        </p>
                    </div>
                </div>

                <!-- Mission -->
                <div class="philosophy-card mission-card">
                    <div class="card-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3 class="card-title">
                        <span class="title-en">Mission</span>
                        <span class="title-ja">ミッション</span>
                    </h3>
                    <div class="card-content">
                        <p class="card-main-text">小さな一歩から</p>
                        <p class="card-main-text">調和をつくっていく</p>
                        <p class="card-description">
                            一つ一つの取り組みを大切に、<br>
                            着実に理想の社会に近づけます
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3つの調和セクション -->
    <section class="three-harmonies-section">
        <div class="container">
            <div class="section-header centered">
                <span class="section-label">Three Harmonies</span>
                <h2 class="section-title">3つの調和</h2>
                <p class="section-description">
                    私たちが実現する3つの社会調和
                </p>
            </div>

            <div class="harmonies-grid">
                <div class="harmony-card" data-harmony="1">
                    <div class="harmony-number">01</div>
                    <div class="harmony-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3 class="harmony-title">優しいDXの推進</h3>
                    <p class="harmony-description">
                        デジタル技術を人々に寄り添う形で活用し、<br>
                        誰もが恩恵を受けられるDXを推進します。<br>
                        技術のための技術ではなく、人のための技術を。
                    </p>
                    <ul class="harmony-features">
                        <li><i class="fas fa-check-circle"></i> 使いやすいUI/UX設計</li>
                        <li><i class="fas fa-check-circle"></i> アクセシビリティ重視</li>
                        <li><i class="fas fa-check-circle"></i> 段階的な導入支援</li>
                    </ul>
                </div>

                <div class="harmony-card" data-harmony="2">
                    <div class="harmony-number">02</div>
                    <div class="harmony-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3 class="harmony-title">AIとの共存・共創</h3>
                    <p class="harmony-description">
                        AIを脅威ではなく、協力者として捉え、<br>
                        人間とAIが互いの強みを活かす関係を構築します。<br>
                        創造性と効率性の両立を実現します。
                    </p>
                    <ul class="harmony-features">
                        <li><i class="fas fa-check-circle"></i> AI活用支援・教育</li>
                        <li><i class="fas fa-check-circle"></i> 人間中心のAI設計</li>
                        <li><i class="fas fa-check-circle"></i> 倫理的なAI開発</li>
                    </ul>
                </div>

                <div class="harmony-card" data-harmony="3">
                    <div class="harmony-number">03</div>
                    <div class="harmony-icon">
                        <i class="fas fa-network-wired"></i>
                    </div>
                    <h3 class="harmony-title">ポストWeb3.0</h3>
                    <p class="harmony-description">
                        次世代のWebテクノロジーを活用し、<br>
                        より透明性が高く、分散型の社会基盤を構築します。<br>
                        新しい価値交換の仕組みを創造します。
                    </p>
                    <ul class="harmony-features">
                        <li><i class="fas fa-check-circle"></i> ブロックチェーン活用</li>
                        <li><i class="fas fa-check-circle"></i> 分散型システム構築</li>
                        <li><i class="fas fa-check-circle"></i> 新しい価値創造</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- バリューセクション -->
    <section class="values-section">
        <div class="container">
            <div class="section-header centered">
                <span class="section-label">Values</span>
                <h2 class="section-title">行動指針</h2>
                <p class="section-description">
                    日々の業務で大切にしている3つの価値観
                </p>
            </div>

            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h3 class="value-title">Work as Life</h3>
                    <p class="value-subtitle">仕事は生き方</p>
                    <p class="value-description">
                        仕事と生活を分けるのではなく、<br>
                        仕事そのものが人生の一部であり、<br>
                        自己実現の手段であると考えます。<br>
                        情熱を持って取り組める環境を大切にします。
                    </p>
                </div>

                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h3 class="value-title">0+1 Respects</h3>
                    <p class="value-subtitle">クリエイターファースト</p>
                    <p class="value-description">
                        ゼロから1を生み出す創造者を尊重し、<br>
                        その独創性と挑戦を全力でサポートします。<br>
                        クリエイターが純粋に創造に集中できる<br>
                        環境づくりを最優先に考えます。
                    </p>
                </div>

                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-globe-americas"></i>
                    </div>
                    <h3 class="value-title">Connected, Anywhere</h3>
                    <p class="value-subtitle">どこでも繋がる</p>
                    <p class="value-description">
                        場所や時間に縛られることなく、<br>
                        世界中のどこからでも最高のパフォーマンスを<br>
                        発揮できる働き方を実現します。<br>
                        真のリモートワークの可能性を追求します。
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 企業沿革セクション -->
    <section class="history-section">
        <div class="container">
            <div class="section-header centered">
                <span class="section-label">History</span>
                <h2 class="section-title">企業沿革</h2>
                <p class="section-description">
                    小さな一歩から始まった私たちの歩み
                </p>
            </div>

            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-marker">
                        <div class="marker-dot"></div>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date">2023.03</div>
                        <h3 class="timeline-title">会社設立</h3>
                        <p class="timeline-description">
                            Harmonic Society株式会社を設立。<br>
                            「社会の調和」という理念を掲げ、<br>
                            テクノロジーで社会をより良くする挑戦を開始
                        </p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-marker">
                        <div class="marker-dot"></div>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date">2023.06</div>
                        <h3 class="timeline-title">事業開始</h3>
                        <p class="timeline-description">
                            SEO支援・Web制作サービスを開始。<br>
                            中小企業のデジタルマーケティング支援に注力
                        </p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-marker">
                        <div class="marker-dot"></div>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date">2024.01</div>
                        <h3 class="timeline-title">AI支援サービス開始</h3>
                        <p class="timeline-description">
                            AIツール活用支援・導入コンサルティングを開始。<br>
                            中小企業のAI活用を積極的にサポート
                        </p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-marker">
                        <div class="marker-dot active"></div>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date">2025.現在</div>
                        <h3 class="timeline-title">更なる成長へ</h3>
                        <p class="timeline-description">
                            「社会の調和」という理念のもと、<br>
                            新しい価値創造に挑戦し続けています
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 会社概要セクション -->
    <section class="company-info-section">
        <div class="container">
            <div class="section-header centered">
                <span class="section-label">Company Information</span>
                <h2 class="section-title">会社概要</h2>
            </div>

            <div class="company-info-card">
                <dl class="info-list">
                    <div class="info-item">
                        <dt><i class="fas fa-building"></i> 社名</dt>
                        <dd>Harmonic Society株式会社</dd>
                    </div>

                    <div class="info-item">
                        <dt><i class="fas fa-user-tie"></i> 代表者</dt>
                        <dd>代表取締役　師田 賢人</dd>
                    </div>

                    <div class="info-item">
                        <dt><i class="fas fa-calendar-alt"></i> 設立</dt>
                        <dd>2023年3月3日</dd>
                    </div>

                    <div class="info-item">
                        <dt><i class="fas fa-yen-sign"></i> 資本金</dt>
                        <dd>1,000,000円</dd>
                    </div>

                    <div class="info-item">
                        <dt><i class="fas fa-map-marker-alt"></i> 本社所在地</dt>
                        <dd>〒262-0033<br>千葉県千葉市花見川区幕張本郷3-31-8</dd>
                    </div>

                    <div class="info-item">
                        <dt><i class="fas fa-globe"></i> ウェブサイト</dt>
                        <dd><a href="https://harmonic-society.co.jp" target="_blank" rel="noopener">https://harmonic-society.co.jp</a></dd>
                    </div>

                    <div class="info-item">
                        <dt><i class="fas fa-briefcase"></i> 事業内容</dt>
                        <dd>
                            • SEO支援・Webマーケティング支援<br>
                            • Web制作・Webサイト構築<br>
                            • AIツール活用支援・導入コンサルティング<br>
                            • DX推進支援
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </section>

    <!-- CTAセクション -->
    <section class="about-cta">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">調和ある社会を共に創りませんか？</h2>
                <p class="cta-text">
                    Harmonic Societyは、テクノロジーと人間性の調和を通じて、<br>
                    より良い社会の実現を目指しています。<br>
                    あなたのビジネスの成長を、私たちが全力でサポートします。
                </p>
                <a href="<?php echo esc_url( get_contact_page_url() ); ?>" class="btn btn-primary btn-large">
                    お問い合わせはこちら
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

</main>

<?php get_footer();