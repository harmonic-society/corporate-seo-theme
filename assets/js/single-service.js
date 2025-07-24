/**
 * サービス詳細ページ用JavaScript
 */
(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        
        // スムーススクロール
        const smoothScrollLinks = document.querySelectorAll('.smooth-scroll');
        smoothScrollLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    const headerHeight = document.querySelector('.site-header').offsetHeight;
                    const targetPosition = targetElement.offsetTop - headerHeight - 20;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // FAQアコーディオン
        const faqItems = document.querySelectorAll('.faq-item');
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            
            question.addEventListener('click', function() {
                // 他のアイテムを閉じる
                faqItems.forEach(otherItem => {
                    if (otherItem !== item && otherItem.classList.contains('active')) {
                        otherItem.classList.remove('active');
                    }
                });
                
                // 現在のアイテムをトグル
                item.classList.toggle('active');
            });
        });

        // スクロールアニメーション
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // アニメーション要素を監視
        const animateElements = document.querySelectorAll(
            '.feature-card, .process-step, .pricing-card, .faq-item'
        );
        
        animateElements.forEach(el => {
            el.style.animationPlayState = 'paused';
            observer.observe(el);
        });

        // ヒーローパララックス効果
        const heroSection = document.querySelector('.service-hero');
        if (heroSection) {
            const bgGradient1 = heroSection.querySelector('.bg-gradient-1');
            const bgGradient2 = heroSection.querySelector('.bg-gradient-2');
            
            window.addEventListener('scroll', () => {
                const scrolled = window.pageYOffset;
                const rate = scrolled * -0.5;
                
                if (bgGradient1) {
                    bgGradient1.style.transform = `translateY(${rate * 0.3}px)`;
                }
                if (bgGradient2) {
                    bgGradient2.style.transform = `translateY(${rate * 0.2}px)`;
                }
            });
        }

        // プロセスタイムラインのアニメーション
        const processSteps = document.querySelectorAll('.process-step');
        if (processSteps.length > 0) {
            const timelineObserver = new IntersectionObserver(function(entries) {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('visible');
                        }, index * 100);
                    }
                });
            }, {
                threshold: 0.3
            });

            processSteps.forEach(step => {
                timelineObserver.observe(step);
            });
        }

        // 価格カードのホバーエフェクト
        const pricingCards = document.querySelectorAll('.pricing-card');
        pricingCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                pricingCards.forEach(otherCard => {
                    if (otherCard !== card) {
                        otherCard.style.opacity = '0.8';
                        otherCard.style.transform = 'scale(0.95)';
                    }
                });
            });

            card.addEventListener('mouseleave', function() {
                pricingCards.forEach(otherCard => {
                    otherCard.style.opacity = '1';
                    otherCard.style.transform = 'scale(1)';
                });
            });
        });

    });
})();