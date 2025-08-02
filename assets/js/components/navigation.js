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

    // スティッキーヘッダーのスクロール処理
    function initStickyHeader() {
        if (!header) return;
        
        let lastScrollTop = 0;
        const headerHeight = header.offsetHeight;
        
        function handleScroll() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // スクロール時にscrolledクラスを追加
            if (scrollTop > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
            
            // ヘッダーは常に表示（固定ヘッダーのため）
            // transform処理は削除
            
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        }
        
        // スクロールイベントの最適化
        let ticking = false;
        function requestTick() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    handleScroll();
                    ticking = false;
                });
                ticking = true;
            }
        }
        
        window.addEventListener('scroll', requestTick);
    }

    // サブメニューのホバー制御
    function initSubmenuControl() {
        const menuItems = document.querySelectorAll('.primary-menu > li');
        
        menuItems.forEach(item => {
            const link = item.querySelector('> a');
            const submenu = item.querySelector('.sub-menu');
            
            if (submenu) {
                let hoverTimeout;
                
                // リンクにマウスが入った時
                link.addEventListener('mouseenter', function() {
                    clearTimeout(hoverTimeout);
                    submenu.style.opacity = '1';
                    submenu.style.visibility = 'visible';
                    submenu.style.transform = 'translateY(0)';
                    submenu.style.pointerEvents = 'auto';
                });
                
                // リンクからマウスが出た時
                link.addEventListener('mouseleave', function(e) {
                    // サブメニューに移動する場合は閉じない
                    const relatedTarget = e.relatedTarget;
                    if (relatedTarget && submenu.contains(relatedTarget)) {
                        return;
                    }
                    
                    hoverTimeout = setTimeout(() => {
                        submenu.style.opacity = '0';
                        submenu.style.visibility = 'hidden';
                        submenu.style.transform = 'translateY(-10px)';
                        submenu.style.pointerEvents = 'none';
                    }, 100);
                });
                
                // サブメニューにマウスが入った時
                submenu.addEventListener('mouseenter', function() {
                    clearTimeout(hoverTimeout);
                });
                
                // サブメニューからマウスが出た時
                submenu.addEventListener('mouseleave', function() {
                    hoverTimeout = setTimeout(() => {
                        submenu.style.opacity = '0';
                        submenu.style.visibility = 'hidden';
                        submenu.style.transform = 'translateY(-10px)';
                        submenu.style.pointerEvents = 'none';
                    }, 100);
                });
            }
        });
    }

    // Initialize all navigation utilities
    initSmoothScroll();
    initLazyLoading();
    initFadeInAnimations();
    initStickyHeader();
    initSubmenuControl();
});