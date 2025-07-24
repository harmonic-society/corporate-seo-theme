/**
 * Header Navigation Script
 * 
 * Harmonic Society用のヘッダーナビゲーション機能
 */

(function() {
    'use strict';

    // DOM要素の取得
    const header = document.querySelector('.site-header');
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
    const scrollToTopButton = document.getElementById('scroll-to-top');
    const body = document.body;

    // 初期化
    let lastScrollTop = 0;
    let isScrolling = false;

    // ========================================================================
    // スクロール時のヘッダー処理
    // ========================================================================

    function handleScroll() {
        if (!isScrolling) {
            window.requestAnimationFrame(function() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                // スクロール時のヘッダークラス切り替え
                if (scrollTop > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }

                // スクロールトップボタンの表示制御
                if (scrollTop > 300) {
                    scrollToTopButton.classList.add('visible');
                } else {
                    scrollToTopButton.classList.remove('visible');
                }

                lastScrollTop = scrollTop;
                isScrolling = false;
            });

            isScrolling = true;
        }
    }

    // ========================================================================
    // モバイルメニューの制御
    // ========================================================================

    function toggleMobileMenu() {
        const isActive = mobileMenu.classList.contains('active');

        if (!isActive) {
            // メニューを開く
            mobileMenu.classList.add('active');
            mobileMenuOverlay.classList.add('active');
            mobileMenuToggle.classList.add('active');
            body.style.overflow = 'hidden';
            
            // アクセシビリティ
            mobileMenuToggle.setAttribute('aria-expanded', 'true');
        } else {
            // メニューを閉じる
            closeMobileMenu();
        }
    }

    function closeMobileMenu() {
        mobileMenu.classList.remove('active');
        mobileMenuOverlay.classList.remove('active');
        mobileMenuToggle.classList.remove('active');
        body.style.overflow = '';
        
        // アクセシビリティ
        mobileMenuToggle.setAttribute('aria-expanded', 'false');
    }

    // ========================================================================
    // スムーズスクロール
    // ========================================================================

    function smoothScrollTo(target) {
        const targetElement = document.querySelector(target);
        if (targetElement) {
            const headerHeight = header.offsetHeight;
            const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;

            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
        }
    }

    // ========================================================================
    // ドロップダウンメニューのアクセシビリティ
    // ========================================================================

    function setupDropdownAccessibility() {
        const menuItems = document.querySelectorAll('.nav-menu > li');

        menuItems.forEach(item => {
            const link = item.querySelector('a');
            const subMenu = item.querySelector('.sub-menu');

            if (subMenu) {
                // キーボードナビゲーション
                link.addEventListener('focus', () => {
                    item.classList.add('focus');
                });

                item.addEventListener('mouseleave', () => {
                    item.classList.remove('focus');
                });

                // サブメニュー内の最後のリンクからフォーカスが外れたら閉じる
                const subMenuLinks = subMenu.querySelectorAll('a');
                const lastSubMenuLink = subMenuLinks[subMenuLinks.length - 1];

                if (lastSubMenuLink) {
                    lastSubMenuLink.addEventListener('blur', (e) => {
                        // 次のフォーカス先がサブメニュー外の場合
                        setTimeout(() => {
                            if (!item.contains(document.activeElement)) {
                                item.classList.remove('focus');
                            }
                        }, 100);
                    });
                }
            }
        });
    }

    // ========================================================================
    // イベントリスナーの設定
    // ========================================================================

    // スクロールイベント
    window.addEventListener('scroll', handleScroll, { passive: true });

    // モバイルメニュートグル
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', toggleMobileMenu);
    }

    // オーバーレイクリックでメニューを閉じる
    if (mobileMenuOverlay) {
        mobileMenuOverlay.addEventListener('click', closeMobileMenu);
    }

    // ESCキーでモバイルメニューを閉じる
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
            closeMobileMenu();
        }
    });

    // スクロールトップボタン
    if (scrollToTopButton) {
        scrollToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // スムーズスクロールリンク
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            // #のみの場合は除外
            if (href !== '#') {
                e.preventDefault();
                smoothScrollTo(href);
                
                // モバイルメニューが開いている場合は閉じる
                if (mobileMenu.classList.contains('active')) {
                    closeMobileMenu();
                }
            }
        });
    });

    // モバイルメニュー内のリンククリックでメニューを閉じる
    document.querySelectorAll('.mobile-menu a').forEach(link => {
        link.addEventListener('click', () => {
            // 外部リンクでない場合
            if (!link.hasAttribute('target') || link.getAttribute('target') !== '_blank') {
                setTimeout(closeMobileMenu, 100);
            }
        });
    });

    // ========================================================================
    // 初期化処理
    // ========================================================================

    document.addEventListener('DOMContentLoaded', () => {
        // ドロップダウンメニューのアクセシビリティ設定
        setupDropdownAccessibility();
        
        // 初期スクロール位置のチェック
        handleScroll();
    });

    // ========================================================================
    // タッチデバイスでのホバー処理
    // ========================================================================

    if ('ontouchstart' in window) {
        document.querySelectorAll('.nav-menu > li').forEach(item => {
            const link = item.querySelector('a');
            const subMenu = item.querySelector('.sub-menu');

            if (subMenu) {
                link.addEventListener('touchend', (e) => {
                    if (!item.classList.contains('touch-active')) {
                        e.preventDefault();
                        
                        // 他のアクティブなメニューを閉じる
                        document.querySelectorAll('.nav-menu > li.touch-active').forEach(activeItem => {
                            if (activeItem !== item) {
                                activeItem.classList.remove('touch-active');
                            }
                        });
                        
                        item.classList.add('touch-active');
                    }
                });
            }
        });

        // タッチデバイスで外側をタップしたらメニューを閉じる
        document.addEventListener('touchend', (e) => {
            if (!e.target.closest('.nav-menu')) {
                document.querySelectorAll('.nav-menu > li.touch-active').forEach(item => {
                    item.classList.remove('touch-active');
                });
            }
        });
    }

})();