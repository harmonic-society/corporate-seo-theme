/**
 * Mobile Menu - Simplified & Optimized
 * UXに優れたモバイル・タブレット対応メニュー
 * 
 * @package Corporate_SEO_Pro
 */
(function() {
    'use strict';

    // 状態管理
    let isMenuOpen = false;
    let scrollPosition = 0;

    // DOM要素のキャッシュ
    const elements = {
        toggle: null,
        menu: null,
        overlay: null,
        closeButton: null,
        body: document.body,
        menuLinks: null
    };

    /**
     * 初期化
     */
    function init() {
        // DOM要素の取得
        elements.toggle = document.querySelector('.mobile-menu-toggle');
        elements.menu = document.querySelector('.mobile-menu');
        
        if (!elements.toggle || !elements.menu) {
            console.warn('Mobile menu elements not found');
            return;
        }

        // オーバーレイの作成または取得
        createOrGetOverlay();
        
        // 閉じるボタンの取得
        elements.closeButton = elements.menu.querySelector('.mobile-menu-close');
        
        // メニューリンクの取得
        elements.menuLinks = elements.menu.querySelectorAll('a');
        
        // イベントリスナーの設定
        setupEventListeners();
        
        // 初期状態の設定
        elements.menu.setAttribute('aria-hidden', 'true');
        elements.toggle.setAttribute('aria-expanded', 'false');
        
        console.log('Mobile menu initialized successfully');
    }

    /**
     * オーバーレイの作成または取得
     */
    function createOrGetOverlay() {
        elements.overlay = document.querySelector('.mobile-menu-overlay');
        
        if (!elements.overlay) {
            elements.overlay = document.createElement('div');
            elements.overlay.className = 'mobile-menu-overlay';
            elements.overlay.setAttribute('aria-hidden', 'true');
            document.body.appendChild(elements.overlay);
        }
    }

    /**
     * イベントリスナーの設定
     */
    function setupEventListeners() {
        // トグルボタンのクリック
        elements.toggle.addEventListener('click', handleToggleClick);
        
        // 閉じるボタンのクリック
        if (elements.closeButton) {
            elements.closeButton.addEventListener('click', closeMenu);
        }
        
        // オーバーレイのクリック
        elements.overlay.addEventListener('click', closeMenu);
        
        // メニューリンクのクリック
        elements.menuLinks.forEach(link => {
            link.addEventListener('click', handleLinkClick);
        });
        
        // サブメニューの開閉処理
        setupSubmenuToggle();
        
        // キーボードナビゲーション
        document.addEventListener('keydown', handleKeyDown);
        
        // ウィンドウリサイズ
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                if (window.innerWidth > 1024 && isMenuOpen) {
                    closeMenu();
                }
            }, 250);
        });
        
        // スワイプジェスチャー（タッチデバイス用）
        if ('ontouchstart' in window) {
            setupSwipeGestures();
        }
    }

    /**
     * トグルボタンのクリックハンドラー
     */
    function handleToggleClick(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (isMenuOpen) {
            closeMenu();
        } else {
            openMenu();
        }
    }

    /**
     * メニューを開く
     */
    function openMenu() {
        if (isMenuOpen) return;
        
        isMenuOpen = true;
        
        // スクロール位置を保存
        scrollPosition = window.pageYOffset;
        
        // クラスの追加（アニメーション用）
        elements.toggle.classList.add('is-active');
        elements.menu.classList.add('is-active');
        elements.overlay.classList.add('is-active');
        elements.body.classList.add('mobile-menu-open');
        
        // スクロールを防止
        elements.body.style.top = `-${scrollPosition}px`;
        
        // ARIA属性の更新
        elements.toggle.setAttribute('aria-expanded', 'true');
        elements.menu.setAttribute('aria-hidden', 'false');
        elements.overlay.setAttribute('aria-hidden', 'false');
        
        // フォーカスをメニューに移動
        setTimeout(() => {
            const firstLink = elements.menu.querySelector('a');
            if (firstLink) firstLink.focus();
        }, 300);
        
        // カスタムイベントの発火
        document.dispatchEvent(new CustomEvent('mobileMenuOpened'));
    }

    /**
     * メニューを閉じる
     */
    function closeMenu() {
        if (!isMenuOpen) return;
        
        isMenuOpen = false;
        
        // クラスの削除
        elements.toggle.classList.remove('is-active');
        elements.menu.classList.remove('is-active');
        elements.overlay.classList.remove('is-active');
        elements.body.classList.remove('mobile-menu-open');
        
        // スクロール位置を復元
        elements.body.style.top = '';
        window.scrollTo(0, scrollPosition);
        
        // ARIA属性の更新
        elements.toggle.setAttribute('aria-expanded', 'false');
        elements.menu.setAttribute('aria-hidden', 'true');
        elements.overlay.setAttribute('aria-hidden', 'true');
        
        // フォーカスをトグルボタンに戻す
        elements.toggle.focus();
        
        // カスタムイベントの発火
        document.dispatchEvent(new CustomEvent('mobileMenuClosed'));
    }

    /**
     * メニューリンクのクリックハンドラー
     */
    function handleLinkClick(e) {
        const href = e.currentTarget.getAttribute('href');
        
        // アンカーリンクの場合
        if (href && href.startsWith('#')) {
            e.preventDefault();
            const target = document.querySelector(href);
            
            if (target) {
                closeMenu();
                
                // メニューが閉じるのを待ってからスクロール
                setTimeout(() => {
                    const offset = 80; // ヘッダーの高さ分のオフセット
                    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - offset;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }, 300);
            }
        } else {
            // 通常のリンクの場合、少し遅延してからメニューを閉じる
            setTimeout(() => closeMenu(), 100);
        }
    }

    /**
     * キーボードイベントハンドラー
     */
    function handleKeyDown(e) {
        if (!isMenuOpen) return;
        
        switch(e.key) {
            case 'Escape':
                closeMenu();
                break;
                
            case 'Tab':
                // タブトラップの実装
                trapFocus(e);
                break;
        }
    }

    /**
     * フォーカストラップ
     */
    function trapFocus(e) {
        const focusableElements = elements.menu.querySelectorAll(
            'a, button, [tabindex]:not([tabindex="-1"])'
        );
        
        if (focusableElements.length === 0) return;
        
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];
        
        if (e.shiftKey && document.activeElement === firstElement) {
            e.preventDefault();
            lastElement.focus();
        } else if (!e.shiftKey && document.activeElement === lastElement) {
            e.preventDefault();
            firstElement.focus();
        }
    }

    /**
     * スワイプジェスチャーの設定
     */
    function setupSwipeGestures() {
        let touchStartX = 0;
        let touchEndX = 0;
        
        elements.menu.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        
        elements.menu.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, { passive: true });
        
        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;
            
            // 右から左にスワイプ（メニューを閉じる）
            if (diff > swipeThreshold) {
                closeMenu();
            }
        }
    }

    /**
     * サブメニューの開閉設定
     */
    function setupSubmenuToggle() {
        const menuItemsWithChildren = elements.menu.querySelectorAll('.menu-item-has-children');
        
        menuItemsWithChildren.forEach(item => {
            const link = item.querySelector('a');
            const submenu = item.querySelector('.sub-menu');
            
            if (link && submenu) {
                // リンクの後にトグルボタンを追加
                const toggleButton = document.createElement('button');
                toggleButton.className = 'submenu-toggle';
                toggleButton.setAttribute('aria-expanded', 'false');
                toggleButton.innerHTML = '<span class="screen-reader-text">サブメニューを開く</span>';
                
                link.parentNode.insertBefore(toggleButton, submenu);
                
                // トグルボタンのクリックイベント
                toggleButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    const isOpen = item.classList.contains('is-open');
                    
                    if (isOpen) {
                        item.classList.remove('is-open');
                        toggleButton.setAttribute('aria-expanded', 'false');
                    } else {
                        // 他の開いているサブメニューを閉じる
                        menuItemsWithChildren.forEach(otherItem => {
                            if (otherItem !== item) {
                                otherItem.classList.remove('is-open');
                                otherItem.querySelector('.submenu-toggle')?.setAttribute('aria-expanded', 'false');
                            }
                        });
                        
                        item.classList.add('is-open');
                        toggleButton.setAttribute('aria-expanded', 'true');
                    }
                });
            }
        });
    }

    /**
     * Public API
     */
    window.MobileMenu = {
        open: openMenu,
        close: closeMenu,
        toggle: () => isMenuOpen ? closeMenu() : openMenu(),
        isOpen: () => isMenuOpen
    };

    // 初期化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();