<?php
/**
 * フッターテンプレート
 *
 * @package Corporate_SEO_Pro
 */
?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="footer-main">
            <div class="container">
                <div class="footer-grid">
                    <!-- 会社情報 -->
                    <div class="footer-widget footer-info">
                        <h3 class="footer-title"><?php bloginfo( 'name' ); ?></h3>
                        <p class="footer-description">
                            <?php echo get_theme_mod( 'footer_description', get_bloginfo( 'description' ) ); ?>
                        </p>

                        <!-- 所在地情報 -->
                        <div class="footer-location" itemscope itemtype="https://schema.org/Organization">
                            <meta itemprop="name" content="<?php bloginfo( 'name' ); ?>">
                            <div class="footer-address" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>
                                    <span itemprop="addressRegion">千葉県</span><span itemprop="addressLocality">千葉市</span>
                                </span>
                            </div>
                            <?php if ( get_theme_mod( 'company_address' ) ) : ?>
                                <div class="footer-full-address">
                                    <span itemprop="streetAddress"><?php echo esc_html( get_theme_mod( 'company_address' ) ); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- ソーシャルリンク -->
                        <?php if ( has_nav_menu( 'social' ) ) : ?>
                            <div class="footer-social">
                                <?php
                                wp_nav_menu( array(
                                    'theme_location' => 'social',
                                    'container'      => false,
                                    'menu_class'     => 'social-links',
                                    'depth'          => 1,
                                    'link_before'    => '<span class="screen-reader-text">',
                                    'link_after'     => '</span>',
                                ) );
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- フッターメニュー1 -->
                    <div class="footer-widget">
                        <h3 class="footer-title"><?php esc_html_e( 'サービス', 'corporate-seo-pro' ); ?></h3>
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer-1',
                            'container'      => false,
                            'menu_class'     => 'footer-menu',
                            'depth'          => 1,
                            'fallback_cb'    => function() {
                                // 実際のサービス投稿を取得
                                $services = get_posts( array(
                                    'post_type'      => 'service',
                                    'posts_per_page' => 6,
                                    'orderby'        => 'menu_order',
                                    'order'          => 'ASC',
                                    'post_status'    => 'publish'
                                ) );
                                
                                echo '<ul class="footer-menu">';
                                
                                if ( ! empty( $services ) ) {
                                    foreach ( $services as $service ) {
                                        echo '<li><a href="' . esc_url( get_permalink( $service->ID ) ) . '">' . esc_html( $service->post_title ) . '</a></li>';
                                    }
                                } else {
                                    // サービスがない場合は全サービス一覧へのリンク
                                    echo '<li><a href="' . esc_url( home_url( '/services/' ) ) . '">サービス一覧</a></li>';
                                }
                                
                                echo '</ul>';
                            },
                        ) );
                        ?>
                    </div>

                    <!-- フッターメニュー2 -->
                    <div class="footer-widget">
                        <h3 class="footer-title"><?php esc_html_e( '会社情報', 'corporate-seo-pro' ); ?></h3>
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer-2',
                            'container'      => false,
                            'menu_class'     => 'footer-menu',
                            'depth'          => 1,
                            'fallback_cb'    => function() {
                                echo '<ul class="footer-menu">';
                                echo '<li><a href="' . esc_url( home_url( '/about/' ) ) . '">会社概要</a></li>';
                                echo '<li><a href="' . esc_url( home_url( '/about/#message' ) ) . '">代表メッセージ</a></li>';
                                echo '<li><a href="' . esc_url( home_url( '/careers/' ) ) . '">採用情報</a></li>';
                                echo '<li><a href="' . esc_url( home_url( '/news/' ) ) . '">ニュース</a></li>';
                                echo '</ul>';
                            },
                        ) );
                        ?>
                    </div>

                    <!-- コンタクト情報 -->
                    <div class="footer-widget footer-contact-widget">
                        <h3 class="footer-title"><?php esc_html_e( 'お問い合わせ', 'corporate-seo-pro' ); ?></h3>
                        <div class="footer-contact">
                            <div class="footer-cta-buttons">
                                <!-- お問い合わせフォーム -->
                                <?php
                                $contact_page = get_page_by_path( 'contact' );
                                $contact_url = $contact_page ? get_permalink( $contact_page ) : home_url( '/contact/' );
                                ?>
                                <a href="<?php echo esc_url( $contact_url ); ?>" class="footer-cta-button footer-cta-form">
                                    <i class="fas fa-envelope"></i>
                                    <span><?php esc_html_e( 'お問い合わせフォーム', 'corporate-seo-pro' ); ?></span>
                                </a>

                                <!-- 資料ダウンロード -->
                                <button type="button" class="footer-cta-button footer-cta-download" data-open-download-modal>
                                    <i class="fas fa-file-download"></i>
                                    <span><?php esc_html_e( '資料ダウンロード', 'corporate-seo-pro' ); ?></span>
                                </button>

                                <!-- 今すぐ予約する -->
                                <a href="https://calendar.app.google/prkDu7TEhWaSzDjN8" class="footer-cta-button footer-cta-booking" target="_blank" rel="noopener noreferrer">
                                    <i class="fas fa-calendar-check"></i>
                                    <span><?php esc_html_e( '今すぐ予約する', 'corporate-seo-pro' ); ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 装飾要素 -->
            <div class="footer-decoration">
                <div class="decoration-circle decoration-circle-1"></div>
                <div class="decoration-circle decoration-circle-2"></div>
            </div>
        </div>

        <!-- フッター下部 -->
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-content">
                    <p class="copyright">
                        &copy; <?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?> | 千葉県千葉市のWebシステム開発.
                        <?php esc_html_e( 'All rights reserved.', 'corporate-seo-pro' ); ?>
                    </p>

                    <div class="footer-bottom-links">
                        <a href="<?php echo esc_url( get_privacy_policy_url() ); ?>">
                            <?php esc_html_e( 'プライバシーポリシー', 'corporate-seo-pro' ); ?>
                        </a>
                        <span class="separator">|</span>
                        <a href="<?php echo esc_url( home_url( '/terms' ) ); ?>">
                            <?php esc_html_e( '利用規約', 'corporate-seo-pro' ); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- ページトップへ戻るボタン -->
    <button id="scroll-to-top" class="scroll-to-top" aria-label="<?php esc_attr_e( 'ページトップへ戻る', 'corporate-seo-pro' ); ?>">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- スマホフッターバー（オンライン予約CTA） -->
    <div id="mobile-footer-bar" class="mobile-footer-bar">
        <button class="mobile-footer-close" aria-label="閉じる">×</button>
        <a href="https://calendar.app.google/prkDu7TEhWaSzDjN8" target="_blank" rel="noopener noreferrer" class="mobile-footer-link">
            <div class="mobile-footer-content">
                <div class="booking-icon-wrapper">
                    <i class="fas fa-calendar-check"></i>
                    <span class="pulse-ring"></span>
                </div>
                <div class="mobile-footer-text">
                    <span class="mobile-footer-title">無料相談を予約する</span>
                    <span class="mobile-footer-subtitle">24時間オンライン予約受付中</span>
                </div>
                <div class="mobile-footer-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- スマホフッターバーのCSS -->
    <style>
        /* スマホフッターバー */
        .mobile-footer-bar {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #00867b 0%, #10b981 100%);
            box-shadow: 0 -4px 20px rgba(0, 134, 123, 0.4);
            z-index: 9998;
            animation: slideUp 0.5s ease-out;
            transition: transform 0.3s ease;
        }

        .mobile-footer-bar.hidden {
            transform: translateY(100%);
        }

        /* 閉じるボタン */
        .mobile-footer-close {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 24px;
            height: 24px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            transition: all 0.3s ease;
        }

        .mobile-footer-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        /* リンク全体 */
        .mobile-footer-link {
            display: block;
            text-decoration: none;
            padding: 12px 16px;
            position: relative;
            overflow: hidden;
        }

        .mobile-footer-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: shimmer 3s infinite;
        }

        /* コンテンツ */
        .mobile-footer-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* 予約アイコン */
        .booking-icon-wrapper {
            position: relative;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .booking-icon-wrapper i {
            font-size: 22px;
            color: #00867b;
        }

        /* パルスアニメーション */
        .pulse-ring {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 48px;
            height: 48px;
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        /* テキスト部分 */
        .mobile-footer-text {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .mobile-footer-title {
            color: white;
            font-size: 16px;
            font-weight: bold;
            line-height: 1.2;
        }

        .mobile-footer-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 12px;
            line-height: 1.2;
        }

        /* 矢印 */
        .mobile-footer-arrow {
            color: white;
            font-size: 20px;
            animation: arrowMove 1.5s ease-in-out infinite;
        }

        /* アニメーション定義 */
        @keyframes slideUp {
            from {
                transform: translateY(100%);
            }
            to {
                transform: translateY(0);
            }
        }

        @keyframes shimmer {
            to {
                left: 100%;
            }
        }

        @keyframes pulse {
            0% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
            100% {
                transform: translate(-50%, -50%) scale(1.5);
                opacity: 0;
            }
        }

        @keyframes arrowMove {
            0%, 100% {
                transform: translateX(0);
            }
            50% {
                transform: translateX(5px);
            }
        }

        /* モバイルのみ表示 */
        @media (max-width: 768px) {
            .mobile-footer-bar {
                display: block;
            }

            /* ページトップボタンの位置調整 */
            .mobile-footer-bar:not(.hidden) ~ .scroll-to-top {
                bottom: 90px;
            }
        }

        /* タブレット以上は非表示 */
        @media (min-width: 769px) {
            .mobile-footer-bar {
                display: none !important;
            }
        }
    </style>

    <!-- スマホフッターバーのJavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const footerBar = document.getElementById('mobile-footer-bar');
            const closeBtn = document.querySelector('.mobile-footer-close');

            if (footerBar && closeBtn) {
                // 閉じるボタンのクリックイベント
                closeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    footerBar.classList.add('hidden');

                    // Cookieに保存（1日間）
                    const date = new Date();
                    date.setTime(date.getTime() + (24 * 60 * 60 * 1000));
                    document.cookie = "mobileFooterHidden=true; expires=" + date.toUTCString() + "; path=/";
                });

                // Cookieをチェック
                if (document.cookie.includes('mobileFooterHidden=true')) {
                    footerBar.classList.add('hidden');
                }

                // 3秒後に自動で表示（初回のみ）
                if (!footerBar.classList.contains('hidden')) {
                    setTimeout(function() {
                        footerBar.style.animation = 'slideUp 0.5s ease-out';
                    }, 3000);
                }
            }
        });
    </script>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
