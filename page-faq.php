<?php
/**
 * Template Name: FAQ
 * よくある質問ページテンプレート
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main faq-page">
    
    <!-- FAQヒーローセクション -->
    <section class="faq-hero">
        <div class="faq-bg-pattern">
            <div class="pattern-grid"></div>
            <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
            </div>
        </div>
        
        <div class="container">
            <div class="hero-content">
                <span class="faq-subtitle">FAQ</span>
                <h1 class="faq-title">よくあるご質問</h1>
                <p class="faq-description">
                    お客様からよくいただくご質問をまとめました。<br>
                    お探しの情報が見つからない場合は、お気軽にお問い合わせください。
                </p>
                
                <!-- 検索ボックス -->
                <div class="faq-search">
                    <input type="text" class="faq-search-input" placeholder="キーワードで検索...">
                    <button class="faq-search-button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQカテゴリータブ -->
    <section class="faq-categories">
        <div class="container">
            <div class="category-tabs">
                <button class="category-tab active" data-category="all">
                    <i class="fas fa-th-large"></i>
                    すべて
                </button>
                <button class="category-tab" data-category="services">
                    <i class="fas fa-briefcase"></i>
                    サービスについて
                </button>
                <button class="category-tab" data-category="pricing">
                    <i class="fas fa-yen-sign"></i>
                    料金・プロセス
                </button>
                <button class="category-tab" data-category="ai">
                    <i class="fas fa-robot"></i>
                    AI活用
                </button>
                <button class="category-tab" data-category="web">
                    <i class="fas fa-globe"></i>
                    Web制作
                </button>
                <button class="category-tab" data-category="crowdfunding">
                    <i class="fas fa-hand-holding-heart"></i>
                    クラウドファンディング
                </button>
                <button class="category-tab" data-category="company">
                    <i class="fas fa-building"></i>
                    会社について
                </button>
            </div>
        </div>
    </section>

    <!-- FAQコンテンツ -->
    <section class="faq-content">
        <div class="container">
            <div class="faq-wrapper">
                
                <!-- サービスについて -->
                <div class="faq-category" data-category="services">
                    <h2 class="category-title">
                        <i class="fas fa-briefcase"></i>
                        サービスについて
                    </h2>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span class="question-text">どのようなサービスを提供していますか？</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Harmonic Societyでは、以下の4つの主要サービスを提供しています：</p>
                            <ul>
                                <li><strong>Web制作:</strong> B2B企業向けの専門的なWebサイト制作</li>
                                <li><strong>Webアプリ共同開発:</strong> アイデアを持つ起業家とのアプリ開発パートナーシップ</li>
                                <li><strong>クラウドファンディング支援:</strong> CAMPFIRE公式パートナーとしてのキャンペーン支援</li>
                                <li><strong>AI活用支援:</strong> 業務効率化のためのAI導入コンサルティング</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span class="question-text">どのような企業に適していますか？</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>当社のサービスは特に以下のような企業様に適しています：</p>
                            <ul>
                                <li>中小企業でDX（デジタルトランスフォーメーション）を推進したい企業</li>
                                <li>人手不足や業務効率化の課題を抱える企業</li>
                                <li>新しいビジネスアイデアを持つ起業家・個人事業主</li>
                                <li>クラウドファンディングで資金調達を検討している方</li>
                                <li>AIを活用して競争力を高めたい企業</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span class="question-text">初回相談は無料ですか？</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>はい、初回相談は無料で承っております。お客様のビジネス課題や目標をお聞きし、当社がどのようにお役に立てるかをご提案させていただきます。お気軽にお問い合わせください。</p>
                        </div>
                    </div>
                </div>

                <!-- 料金・プロセスについて -->
                <div class="faq-category" data-category="pricing">
                    <h2 class="category-title">
                        <i class="fas fa-yen-sign"></i>
                        料金・プロセスについて
                    </h2>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span class="question-text">サービスの料金体系はどのようになっていますか？</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>プロジェクトの規模や内容によって異なりますが、AI活用により従来の開発費用の約1/5程度でサービスを提供しています。具体的な料金は、初回の無料相談でお客様のニーズをお聞きした上でお見積りをご提示いたします。</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span class="question-text">プロジェクトの一般的な期間はどのくらいですか？</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>AI技術の活用により、従来の開発期間の約1/10に短縮しています：</p>
                            <ul>
                                <li><strong>初期プロトタイプ:</strong> 2-4週間</li>
                                <li><strong>Webサイト制作:</strong> 1-2ヶ月</li>
                                <li><strong>Webアプリ開発:</strong> 2-3ヶ月</li>
                                <li><strong>AI導入支援:</strong> 1-3ヶ月（段階的導入）</li>
                            </ul>
                            <p>※プロジェクトの規模や要件により変動します。</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span class="question-text">プロジェクトの進め方を教えてください</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>当社では「スモールスタート」アプローチを採用しています：</p>
                            <ol>
                                <li><strong>ヒアリング＆分析:</strong> 現状の課題と目標を明確化</li>
                                <li><strong>小規模プロジェクトの企画:</strong> 2-4週間で実施可能な範囲を定義</li>
                                <li><strong>実施＆モニタリング:</strong> リアルタイムでデータを収集</li>
                                <li><strong>検証＆改善:</strong> 結果を評価し、次のステップを計画</li>
                                <li><strong>段階的拡大:</strong> 成功事例を基に徐々に範囲を拡大</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- AI活用について -->
                <div class="faq-category" data-category="ai">
                    <h2 class="category-title">
                        <i class="fas fa-robot"></i>
                        AI活用について
                    </h2>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span class="question-text">AI活用支援では具体的に何をしてもらえますか？</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>AI活用支援では以下のサポートを提供しています：</p>
                            <ul>
                                <li><strong>業務分析:</strong> AI導入に適した業務の特定</li>
                                <li><strong>ツール選定:</strong> 最適なAIツールの推奨と導入支援</li>
                                <li><strong>プロトタイプ開発:</strong> 小規模な実証実験の実施</li>
                                <li><strong>社内教育:</strong> AIツールの使い方トレーニング</li>
                                <li><strong>継続的改善:</strong> 導入後の効果測定と最適化</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span class="question-text">AIを使うことでどのような効果が期待できますか？</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>AI活用により以下の効果が期待できます：</p>
                            <ul>
                                <li><strong>業務効率化:</strong> 繰り返し作業の自動化により作業時間を大幅削減</li>
                                <li><strong>コスト削減:</strong> 人件費の削減と生産性向上</li>
                                <li><strong>品質向上:</strong> ヒューマンエラーの削減</li>
                                <li><strong>24時間対応:</strong> AIチャットボットなどによる顧客対応の向上</li>
                                <li><strong>データ活用:</strong> ビッグデータ分析による意思決定の精度向上</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span class="question-text">AI導入にはどのような準備が必要ですか？</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>特別な準備は必要ありません。当社が以下をサポートします：</p>
                            <ul>
                                <li>現在の業務フローの整理と文書化</li>
                                <li>データの整備（必要に応じて）</li>
                                <li>社内の理解と協力体制の構築</li>
                                <li>段階的な導入計画の策定</li>
                            </ul>
                            <p>まずは小さな業務から始めることで、リスクを最小限に抑えながら効果を実感できます。</p>
                        </div>
                    </div>
                </div>

                <!-- Web制作について -->
                <div class="faq-category" data-category="web">
                    <h2 class="category-title">
                        <i class="fas fa-globe"></i>
                        Web制作について
                    </h2>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span class="question-text">どのようなWebサイトを制作できますか？</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>以下のようなWebサイトの制作実績があります：</p>
                            <ul>
                                <li><strong>コーポレートサイト:</strong> 企業の信頼性を高めるプロフェッショナルなサイト</li>
                                <li><strong>サービスサイト:</strong> 製品やサービスの魅力を伝える特化型サイト</li>
                                <li><strong>ランディングページ:</strong> コンバージョン率の高い集客用ページ</li>
                                <li><strong>ECサイト:</strong> オンラインショップの構築</li>
                                <li><strong>メディアサイト:</strong> SEOに強いコンテンツ配信サイト</li>
                            </ul>
                            <p>特にB2B企業向けの専門性の高い商材・サービスのWeb制作を得意としています。</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span class="question-text">SEO対策は含まれていますか？</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>はい、すべてのWeb制作プランにSEO対策が含まれています：</p>
                            <ul>
                                <li>キーワード調査と戦略立案</li>
                                <li>技術的SEO（サイト速度、モバイル対応など）</li>
                                <li>コンテンツSEO（見出し構造、メタ情報の最適化）</li>
                                <li>内部リンク構造の最適化</li>
                                <li>Google Analytics/Search Consoleの設定</li>
                            </ul>
                            <p>さらに、AI活用により継続的なコンテンツ更新もサポートしています。</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span class="question-text">既存サイトのリニューアルも可能ですか？</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>はい、既存サイトのリニューアルも承っています。まずは現状分析を行い、以下の観点から改善提案をいたします：</p>
                            <ul>
                                <li>デザインの現代化とユーザビリティ向上</li>
                                <li>スマートフォン対応（レスポンシブデザイン）</li>
                                <li>表示速度の改善</li>
                                <li>SEO対策の強化</li>
                                <li>コンバージョン率の向上</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- クラウドファンディングについて -->
                <div class="faq-category" data-category="crowdfunding">
                    <h2 class="category-title">
                        <i class="fas fa-hand-holding-heart"></i>
                        クラウドファンディングについて
                    </h2>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span class="question-text">CAMPFIRE公式パートナーとは何ですか？</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>CAMPFIRE公式パートナーとは、日本最大級のクラウドファンディングプラットフォーム「CAMPFIRE」から認定を受けた支援事業者です。豊富な実績とノウハウを持ち、プロジェクトの成功率を高めるための専門的なサポートを提供できます。</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span class="question-text">クラウドファンディング支援の内容を教えてください</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>以下の包括的なサポートを提供しています：</p>
                            <ul>
                                <li><strong>企画立案:</strong> プロジェクトコンセプトの明確化</li>
                                <li><strong>ページ制作:</strong> 魅力的なプロジェクトページの作成</li>
                                <li><strong>リターン設計:</strong> 支援者に喜ばれるリターンの企画</li>
                                <li><strong>PR戦略:</strong> SNSやメディアを活用した拡散戦略</li>
                                <li><strong>運営サポート:</strong> 期間中の更新や支援者対応</li>
                            </ul>
                            <p>実績：AAヘルスダイナミクス様のプロジェクトで約180万円の資金調達に成功</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span class="question-text">どのようなプロジェクトが向いていますか？</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>特に以下のようなプロジェクトに適しています：</p>
                            <ul>
                                <li>新製品・サービスの開発資金調達</li>
                                <li>社会課題解決型のプロジェクト</li>
                                <li>地域活性化や文化振興プロジェクト</li>
                                <li>スタートアップの初期資金調達</li>
                                <li>既存事業の新規展開</li>
                            </ul>
                            <p>プロジェクトの成功可能性について、無料相談で診断いたします。</p>
                        </div>
                    </div>
                </div>

                <!-- 会社について -->
                <div class="faq-category" data-category="company">
                    <h2 class="category-title">
                        <i class="fas fa-building"></i>
                        会社について
                    </h2>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span class="question-text">Harmonic Societyとはどのような会社ですか？</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Harmonic Society株式会社は2023年3月に設立された、AI技術とデジタルマーケティングを融合させたソリューションを提供する会社です。「分断された世界に調和をもたらす」をビジョンに掲げ、テクノロジーと人間性の調和を目指しています。</p>
                            <ul>
                                <li><strong>設立:</strong> 2023年3月3日</li>
                                <li><strong>代表:</strong> 諸田 健人</li>
                                <li><strong>所在地:</strong> 千葉県千葉市花見川区幕張本郷3-31-8</li>
                                <li><strong>資本金:</strong> 100万円</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span class="question-text">会社の強みは何ですか？</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>当社の主な強みは以下の通りです：</p>
                            <ul>
                                <li><strong>AI技術の先進的活用:</strong> 最新のAI技術を実務に応用</li>
                                <li><strong>コスト効率:</strong> AI活用により従来の1/5のコストでサービス提供</li>
                                <li><strong>スピード:</strong> 開発期間を従来の1/10に短縮</li>
                                <li><strong>人間中心のアプローチ:</strong> テクノロジーと人間性のバランス</li>
                                <li><strong>実績:</strong> クラウドファンディング成功実績など</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span class="question-text">対応エリアを教えてください</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>全国対応可能です。オンラインでの打ち合わせを基本としているため、地域を問わずサービスを提供できます。必要に応じて、対面でのミーティングも可能です（交通費別途）。</p>
                        </div>
                    </div>
                </div>

                <!-- お問い合わせ・サポートについて -->
                <div class="faq-category" data-category="support">
                    <h2 class="category-title">
                        <i class="fas fa-headset"></i>
                        お問い合わせ・サポート
                    </h2>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span class="question-text">問い合わせ方法を教えてください</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>以下の方法でお問い合わせいただけます：</p>
                            <ul>
                                <li><strong>Webフォーム:</strong> 24時間受付（推奨）</li>
                                <li><strong>電話:</strong> 080-6946-4006（平日9:00-18:00）</li>
                                <li><strong>メール:</strong> koushiki@harmonic-society.co.jp</li>
                            </ul>
                            <p>お問い合わせは原則24時間以内にご返信いたします。</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span class="question-text">契約後のサポート体制について教えてください</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>プロジェクト完了後も継続的なサポートを提供しています：</p>
                            <ul>
                                <li><strong>保守サポート:</strong> Webサイトの更新や修正対応</li>
                                <li><strong>運用支援:</strong> Google Analyticsを活用した改善提案</li>
                                <li><strong>技術サポート:</strong> トラブル時の迅速な対応</li>
                                <li><strong>コンサルティング:</strong> 継続的な成長戦略の立案</li>
                            </ul>
                            <p>サポート内容は契約プランにより異なります。詳細はお問い合わせください。</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span class="question-text">秘密保持契約（NDA）は結べますか？</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>はい、ご要望に応じて秘密保持契約（NDA）を締結いたします。お客様の重要な情報やアイデアを守るため、プロジェクト開始前にNDAを結ぶことも可能です。詳細はお問い合わせ時にご相談ください。</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- お問い合わせCTA -->
    <section class="faq-cta">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">お探しの情報は見つかりましたか？</h2>
                <p class="cta-text">
                    ご不明な点がございましたら、お気軽にお問い合わせください。<br>
                    専門スタッフが丁寧にご対応いたします。
                </p>
                <div class="cta-buttons">
                    <a href="<?php echo get_contact_page_url(); ?>" class="btn-primary">
                        <i class="fas fa-envelope"></i>
                        お問い合わせはこちら
                    </a>
                    <a href="tel:08069464006" class="btn-secondary">
                        <i class="fas fa-phone"></i>
                        080-6946-4006
                    </a>
                </div>
            </div>
        </div>
    </section>
    
</main>

<?php get_footer();