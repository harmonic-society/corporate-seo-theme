/**
 * Single Service Page Modern JavaScript
 *
 * サービス詳細ページのインタラクション
 */

(function() {
    'use strict';

    // FAQアコーディオン機能
    function initFAQAccordion() {
        const faqItems = document.querySelectorAll('.faq-item-modern');

        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question-modern');

            if (question) {
                question.addEventListener('click', function() {
                    const isActive = item.classList.contains('active');

                    // 他のすべてのアイテムを閉じる
                    faqItems.forEach(otherItem => {
                        otherItem.classList.remove('active');
                        const otherQuestion = otherItem.querySelector('.faq-question-modern');
                        if (otherQuestion) {
                            otherQuestion.setAttribute('aria-expanded', 'false');
                        }
                    });

                    // クリックされたアイテムをトグル
                    if (!isActive) {
                        item.classList.add('active');
                        question.setAttribute('aria-expanded', 'true');
                    }
                });
            }
        });
    }

    // スムーススクロール
    function initSmoothScroll() {
        const smoothScrollLinks = document.querySelectorAll('a.smooth-scroll, a[href^="#"]');

        smoothScrollLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href && href.startsWith('#')) {
                    const targetId = href.substring(1);
                    const targetElement = document.getElementById(targetId);

                    if (targetElement) {
                        e.preventDefault();
                        const headerOffset = 100;
                        const elementPosition = targetElement.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
    }

    // スクロールアニメーション（フェードイン）
    function initScrollAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // アニメーション対象の要素（モダン + フォールバックテンプレート両方対応）
        const animatedElements = document.querySelectorAll(
            '.feature-card-modern, .feature-card, .pricing-card-modern, .pricing-card, .process-step-modern, .faq-item-modern, .value-prop-item'
        );

        animatedElements.forEach((el, index) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
            observer.observe(el);
        });
    }

    // パララックス効果（ヒーローセクション）
    function initParallaxEffect() {
        const heroSection = document.querySelector('.service-hero-dynamic');
        if (!heroSection) return;

        const gradientLayers = heroSection.querySelectorAll('.gradient-layer');
        const shapes = heroSection.querySelectorAll('.shape');

        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const heroHeight = heroSection.offsetHeight;

            if (scrolled < heroHeight) {
                gradientLayers.forEach((layer, index) => {
                    const speed = 0.3 + (index * 0.1);
                    layer.style.transform = `translateY(${scrolled * speed}px)`;
                });

                shapes.forEach((shape, index) => {
                    const speed = 0.2 + (index * 0.05);
                    shape.style.transform = `translateY(${scrolled * speed}px) rotate(${scrolled * 0.1}deg)`;
                });
            }
        });
    }

    // 料金カードのインタラクション（フォールバックテンプレート対応）
    function initPricingInteractions() {
        const pricingCards = document.querySelectorAll('.pricing-card');
        pricingCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
            const cta = card.querySelector('.plan-cta');
            if (cta) {
                card.style.cursor = 'pointer';
                card.addEventListener('click', function(e) {
                    if (e.target !== cta && !cta.contains(e.target)) {
                        cta.click();
                    }
                });
            }
        });
    }

    // 初期化
    function init() {
        // DOM読み込み完了後に実行
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                initFAQAccordion();
                initSmoothScroll();
                initScrollAnimations();
                initParallaxEffect();
                initPricingInteractions();
            });
        } else {
            initFAQAccordion();
            initSmoothScroll();
            initScrollAnimations();
            initParallaxEffect();
            initPricingInteractions();
        }
    }

    init();
})();