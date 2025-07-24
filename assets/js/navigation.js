/**
 * ナビゲーション機能
 */
(function() {
    'use strict';

    // DOM要素の取得
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mainNavigation = document.querySelector('.main-navigation');
    const menuItems = document.querySelectorAll('.main-navigation a');
    const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');

    // モバイルメニューの切り替え
    if (mobileMenuToggle && mainNavigation) {
        mobileMenuToggle.addEventListener('click', function() {
            const isOpen = mainNavigation.classList.contains('active');
            
            if (isOpen) {
                mainNavigation.classList.remove('active');
                mobileMenuToggle.classList.remove('active');
                if (mobileMenuOverlay) mobileMenuOverlay.classList.remove('active');
                document.body.style.overflow = '';
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                if (typeof corporateSEOPro !== 'undefined') {
                    mobileMenuToggle.setAttribute('aria-label', corporateSEOPro.menuOpen);
                }
            } else {
                mainNavigation.classList.add('active');
                mobileMenuToggle.classList.add('active');
                if (mobileMenuOverlay) mobileMenuOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
                mobileMenuToggle.setAttribute('aria-expanded', 'true');
                if (typeof corporateSEOPro !== 'undefined') {
                    mobileMenuToggle.setAttribute('aria-label', corporateSEOPro.menuClose);
                }
            }
        });
        
        // オーバーレイクリックでメニューを閉じる
        if (mobileMenuOverlay) {
            mobileMenuOverlay.addEventListener('click', function() {
                mainNavigation.classList.remove('active');
                mobileMenuToggle.classList.remove('active');
                mobileMenuOverlay.classList.remove('active');
                document.body.style.overflow = '';
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
            });
        }

        // メニュー項目クリック時にメニューを閉じる
        menuItems.forEach(function(item) {
            item.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    mainNavigation.classList.remove('active');
                    mobileMenuToggle.setAttribute('aria-expanded', 'false');
                }
            });
        });

        // ウィンドウリサイズ時の処理
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth > 768) {
                    mainNavigation.classList.remove('active');
                    mobileMenuToggle.setAttribute('aria-expanded', 'false');
                }
            }, 250);
        });
    }

    // スクロール時のヘッダー処理
    const header = document.querySelector('.site-header');
    let lastScrollTop = 0;
    let scrollTimer;

    window.addEventListener('scroll', function() {
        clearTimeout(scrollTimer);
        scrollTimer = setTimeout(function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // スクロール時のヘッダースタイル変更
            if (scrollTop > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
            
            lastScrollTop = scrollTop;
        }, 50);
    });

    // スムーススクロール
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

    // アクセシビリティ: Escキーでメニューを閉じる
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mainNavigation && mainNavigation.classList.contains('active')) {
            mainNavigation.classList.remove('active');
            mobileMenuToggle.setAttribute('aria-expanded', 'false');
            mobileMenuToggle.focus();
        }
    });

    // フォーカストラップ（モバイルメニュー開いている時）
    if (mainNavigation) {
        const focusableElements = mainNavigation.querySelectorAll(
            'a[href], button, input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        if (focusableElements.length > 0) {
            const firstFocusable = focusableElements[0];
            const lastFocusable = focusableElements[focusableElements.length - 1];
            
            mainNavigation.addEventListener('keydown', function(e) {
                if (e.key === 'Tab' && mainNavigation.classList.contains('active')) {
                    if (e.shiftKey) { // Shift + Tab
                        if (document.activeElement === firstFocusable) {
                            e.preventDefault();
                            lastFocusable.focus();
                        }
                    } else { // Tab
                        if (document.activeElement === lastFocusable) {
                            e.preventDefault();
                            firstFocusable.focus();
                        }
                    }
                }
            });
        }
    }

    // 遅延読み込みの初期化
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

    // フェードインアニメーション
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

})();