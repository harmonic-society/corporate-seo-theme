/**
 * GA4 Event Tracking for Corporate SEO Pro Theme
 * 
 * @package Corporate_SEO_Pro
 */

(function() {
    'use strict';

    /**
     * GA4イベントを送信する関数
     * @param {string} eventName イベント名
     * @param {Object} parameters イベントパラメータ
     */
    function sendGA4Event(eventName, parameters = {}) {
        // gtagが存在する場合のみ送信
        if (typeof gtag !== 'undefined') {
            gtag('event', eventName, parameters);
            console.log('GA4 Event sent:', eventName, parameters);
        } else {
            console.warn('gtag is not defined. Make sure Google Analytics is properly loaded.');
        }
    }

    /**
     * CTAクリックイベントを追跡
     * @param {HTMLElement} element クリックされた要素
     * @param {string} ctaType CTAのタイプ
     * @param {string} ctaLabel CTAのラベル
     */
    function trackCTAClick(element, ctaType, ctaLabel) {
        const eventParams = {
            'event_category': 'CTA',
            'event_label': ctaLabel,
            'cta_type': ctaType,
            'cta_text': element.textContent.trim(),
            'cta_url': element.href || '',
            'page_url': window.location.href,
            'page_title': document.title
        };

        // コンバージョンイベントとして送信
        sendGA4Event('cta_click', eventParams);

        // 特定のCTAタイプに応じた追加イベント
        switch(ctaType) {
            case 'line_add_friend':
                sendGA4Event('line_friend_add_click', eventParams);
                break;
            case 'contact_form':
                sendGA4Event('contact_form_click', eventParams);
                break;
            case 'free_consultation':
                sendGA4Event('free_consultation_click', eventParams);
                break;
            case 'phone_call':
                sendGA4Event('phone_call_click', eventParams);
                break;
        }
    }

    /**
     * LINE CTAボタンのイベント設定
     */
    function setupLineCTAEvents() {
        // LINE友だち追加ボタン
        const lineAddButtons = document.querySelectorAll('.line-add-friend-btn, a[href*="lin.ee"], a[href*="line.me"]');
        lineAddButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                trackCTAClick(this, 'line_add_friend', 'LINE友だち追加');
            });
        });

        // QRコード表示ボタン
        const qrButtons = document.querySelectorAll('.line-qr-button');
        qrButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                sendGA4Event('line_qr_modal_open', {
                    'event_category': 'CTA',
                    'event_label': 'LINE QRコード表示',
                    'page_url': window.location.href
                });
            });
        });
    }

    /**
     * お問い合わせ・無料相談ボタンのイベント設定
     */
    function setupContactCTAEvents() {
        // お問い合わせページへのリンク
        const contactLinks = document.querySelectorAll('a[href*="/contact"], a[href*="contact-form"], .cta-button');
        contactLinks.forEach(link => {
            // LINE CTAボタンは除外
            if (!link.classList.contains('line-add-friend-btn') && !link.href.includes('lin.ee')) {
                link.addEventListener('click', function(e) {
                    const isFreeTrial = this.textContent.includes('無料') || this.textContent.includes('Free');
                    const ctaType = isFreeTrial ? 'free_consultation' : 'contact_form';
                    const ctaLabel = isFreeTrial ? '無料相談申し込み' : 'お問い合わせ';
                    trackCTAClick(this, ctaType, ctaLabel);
                });
            }
        });

        // 電話番号リンク
        const phoneLinks = document.querySelectorAll('a[href^="tel:"]');
        phoneLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                trackCTAClick(this, 'phone_call', '電話でのお問い合わせ');
            });
        });
    }

    /**
     * ナビゲーションCTAボタンのイベント設定
     */
    function setupNavigationCTAEvents() {
        // ヘッダーのCTAボタン
        const navCTAButtons = document.querySelectorAll('.nav-cta-button, .header-cta, .mobile-cta-button');
        navCTAButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const location = this.closest('header') ? 'header' : 'navigation';
                const ctaLabel = `ナビゲーション_${location}`;
                
                // LINE CTAかお問い合わせCTAかを判定
                if (this.href && this.href.includes('lin.ee')) {
                    trackCTAClick(this, 'line_add_friend', ctaLabel + '_LINE');
                } else {
                    trackCTAClick(this, 'contact_form', ctaLabel + '_お問い合わせ');
                }
            });
        });
    }

    /**
     * フッターCTAボタンのイベント設定
     */
    function setupFooterCTAEvents() {
        // フッターのスマホ固定バー
        const footerBarButtons = document.querySelectorAll('.mobile-footer-bar .footer-bar-button');
        footerBarButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const buttonType = this.querySelector('.footer-bar-label').textContent;
                let ctaType = 'contact_form';
                let ctaLabel = 'フッターバー_' + buttonType;

                if (this.href && this.href.includes('lin.ee')) {
                    ctaType = 'line_add_friend';
                } else if (this.href && this.href.includes('tel:')) {
                    ctaType = 'phone_call';
                }

                trackCTAClick(this, ctaType, ctaLabel);
            });
        });

        // フッター内のCTAボタン
        const footerCTAs = document.querySelectorAll('footer .cta-button, footer .btn-primary');
        footerCTAs.forEach(button => {
            button.addEventListener('click', function(e) {
                trackCTAClick(this, 'contact_form', 'フッター_CTA');
            });
        });
    }

    /**
     * サービスページのCTAボタンのイベント設定
     */
    function setupServiceCTAEvents() {
        // 料金プランのCTAボタン
        const pricingCTAs = document.querySelectorAll('.pricing-cta, .plan-cta-button');
        pricingCTAs.forEach(button => {
            button.addEventListener('click', function(e) {
                const planName = this.closest('.pricing-plan')?.querySelector('.plan-name')?.textContent || 'Unknown';
                trackCTAClick(this, 'contact_form', `料金プラン_${planName}`);
            });
        });

        // サービス詳細のCTAボタン
        const serviceCTAs = document.querySelectorAll('.service-cta, .service-contact-button');
        serviceCTAs.forEach(button => {
            button.addEventListener('click', function(e) {
                const serviceName = document.querySelector('h1')?.textContent || 'Unknown Service';
                trackCTAClick(this, 'contact_form', `サービス詳細_${serviceName}`);
            });
        });
    }

    /**
     * フォーム送信イベントの設定
     */
    function setupFormSubmitEvents() {
        // Contact Form 7のフォーム送信イベント
        document.addEventListener('wpcf7mailsent', function(event) {
            sendGA4Event('form_submit', {
                'event_category': 'Form',
                'event_label': 'Contact Form 7',
                'form_id': event.detail.contactFormId,
                'form_name': event.detail.unitTag,
                'page_url': window.location.href
            });

            // コンバージョンイベントとして送信
            sendGA4Event('generate_lead', {
                'value': 0,
                'currency': 'JPY',
                'form_type': 'contact_form'
            });
        });

        // 通常のフォーム送信
        const forms = document.querySelectorAll('form.contact-form, form#contactForm');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                sendGA4Event('form_start', {
                    'event_category': 'Form',
                    'event_label': 'Form Submit Attempt',
                    'form_id': this.id || 'unknown',
                    'page_url': window.location.href
                });
            });
        });
    }

    /**
     * スクロール深度の追跡
     */
    function setupScrollDepthTracking() {
        let scrollDepths = [25, 50, 75, 90, 100];
        let reachedDepths = [];

        function checkScrollDepth() {
            const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrolled = window.scrollY;
            const scrollPercentage = Math.round((scrolled / scrollHeight) * 100);

            scrollDepths.forEach(depth => {
                if (scrollPercentage >= depth && !reachedDepths.includes(depth)) {
                    reachedDepths.push(depth);
                    sendGA4Event('scroll', {
                        'event_category': 'Engagement',
                        'event_label': `${depth}%`,
                        'scroll_depth': depth,
                        'page_url': window.location.href
                    });
                }
            });
        }

        // デバウンス処理
        let scrollTimer;
        window.addEventListener('scroll', function() {
            clearTimeout(scrollTimer);
            scrollTimer = setTimeout(checkScrollDepth, 100);
        });
    }

    /**
     * ページ滞在時間の追跡
     */
    function setupTimeOnPageTracking() {
        let startTime = Date.now();
        let timeThresholds = [10, 30, 60, 180, 300]; // 秒単位
        let sentThresholds = [];

        setInterval(function() {
            const timeOnPage = Math.round((Date.now() - startTime) / 1000);
            
            timeThresholds.forEach(threshold => {
                if (timeOnPage >= threshold && !sentThresholds.includes(threshold)) {
                    sentThresholds.push(threshold);
                    sendGA4Event('time_on_page', {
                        'event_category': 'Engagement',
                        'event_label': `${threshold}秒以上`,
                        'time_seconds': threshold,
                        'page_url': window.location.href
                    });
                }
            });
        }, 5000); // 5秒ごとにチェック
    }

    /**
     * 初期化関数
     */
    function initGA4Events() {
        // DOMContentLoadedを待つ
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    }

    function init() {
        // 各CTAイベントの設定
        setupLineCTAEvents();
        setupContactCTAEvents();
        setupNavigationCTAEvents();
        setupFooterCTAEvents();
        setupServiceCTAEvents();
        setupFormSubmitEvents();
        
        // エンゲージメントトラッキング
        setupScrollDepthTracking();
        setupTimeOnPageTracking();

        // ページビューイベント（初回）
        sendGA4Event('page_view_enhanced', {
            'page_location': window.location.href,
            'page_title': document.title,
            'page_referrer': document.referrer,
            'user_agent': navigator.userAgent,
            'screen_resolution': `${window.screen.width}x${window.screen.height}`,
            'viewport_size': `${window.innerWidth}x${window.innerHeight}`
        });

        console.log('GA4 Event Tracking initialized successfully');
    }

    // 初期化実行
    initGA4Events();

    // グローバルに公開（デバッグ用）
    window.corporateSEOGA4 = {
        sendEvent: sendGA4Event,
        trackCTA: trackCTAClick
    };

})();