/**
 * Navigation Utilities
 * Smooth scroll, lazy loading, and fade-in animations
 * 
 * @package Corporate_SEO_Pro
 */
document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    const header = document.querySelector('.site-header');

    // スムーススクロール
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                
                if (href !== '#' && href !== '#0') {
                    const target = document.querySelector(href);
                    
                    if (target) {
                        e.preventDefault();
                        
                        const headerHeight = header ? header.offsetHeight : 0;
                        const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;
                        
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
    }

    // 遅延読み込みの初期化
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const lazyImages = document.querySelectorAll('img[loading="lazy"]');
            
            const imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                        }
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            lazyImages.forEach(function(img) {
                imageObserver.observe(img);
            });
        }
    }

    // フェードインアニメーション
    function initFadeInAnimations() {
        const fadeElements = document.querySelectorAll('.fade-in');
        
        if (fadeElements.length > 0) {
            const fadeObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, {
                threshold: 0.1
            });
            
            fadeElements.forEach(function(element) {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                element.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                fadeObserver.observe(element);
            });
        }
    }

    // Initialize all navigation utilities
    initSmoothScroll();
    initLazyLoading();
    initFadeInAnimations();
});