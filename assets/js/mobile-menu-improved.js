/**
 * Mobile Menu Improved
 * 
 * 改善されたモバイルメニュー機能
 */

(function() {
    'use strict';

    // DOM要素の取得
    let toggle, menu, overlay, body;
    let isOpen = false;
    let scrollPosition = 0;

    /**
     * 初期化
     */
    function init() {
        // 要素の取得
        toggle = document.querySelector('.mobile-menu-toggle');
        menu = document.querySelector('.mobile-menu');
        overlay = document.querySelector('.mobile-menu-overlay');
        body = document.body;

        if (!toggle || !menu) {
            console.warn('Mobile menu elements not found');
            return;
        }

        // イベントリスナーの設定
        setupEventListeners();
        
        // 初期状態の設定
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', 'メニューを開く');
        
        console.log('Mobile menu improved initialized');
    }

    /**
     * イベントリスナーの設定
     */
    function setupEventListeners() {
        // トグルボタンのクリック
        toggle.addEventListener('click', handleToggleClick);
        
        // オーバーレイのクリック
        if (overlay) {
            overlay.addEventListener('click', closeMenu);
        }
        
        // ESCキーでの閉じる
        document.addEventListener('keydown', handleKeyDown);
        
        // メニュー内のリンククリック
        const menuLinks = menu.querySelectorAll('a[href^="#"], a[href^="/"]');
        menuLinks.forEach(link => {
            link.addEventListener('click', handleLinkClick);
        });
        
        // ウィンドウリサイズ時の処理
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                if (window.innerWidth > 768 && isOpen) {
                    closeMenu();
                }
            }, 250);
        });
    }

    /**
     * トグルボタンクリックハンドラ
     */
    function handleToggleClick(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (isOpen) {
            closeMenu();
        } else {
            openMenu();
        }
    }

    /**
     * メニューを開く
     */
    function openMenu() {
        if (isOpen) return;
        
        // スクロール位置を保存
        scrollPosition = window.pageYOffset;
        
        // クラスの追加
        toggle.classList.add('active');
        menu.classList.add('active');
        if (overlay) {
            overlay.classList.add('active');
        }
        
        // ボディのスクロールを無効化
        body.style.position = 'fixed';
        body.style.top = `-${scrollPosition}px`;
        body.style.width = '100%';
        body.classList.add('mobile-menu-open');
        
        // ARIA属性の更新
        toggle.setAttribute('aria-expanded', 'true');
        toggle.setAttribute('aria-label', 'メニューを閉じる');
        
        // フォーカス管理
        const firstLink = menu.querySelector('a, button');
        if (firstLink) {
            setTimeout(() => firstLink.focus(), 300);
        }
        
        isOpen = true;
    }

    /**
     * メニューを閉じる
     */
    function closeMenu() {
        if (!isOpen) return;
        
        // クラスの削除
        toggle.classList.remove('active');
        menu.classList.remove('active');
        if (overlay) {
            overlay.classList.remove('active');
        }
        
        // ボディのスクロールを復元
        body.style.position = '';
        body.style.top = '';
        body.style.width = '';
        body.classList.remove('mobile-menu-open');
        
        // スクロール位置を復元
        window.scrollTo(0, scrollPosition);
        
        // ARIA属性の更新
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', 'メニューを開く');
        
        // フォーカスをトグルボタンに戻す
        toggle.focus();
        
        isOpen = false;
    }

    /**
     * キーボードイベントハンドラ
     */
    function handleKeyDown(e) {
        if (e.key === 'Escape' && isOpen) {
            closeMenu();
        }
    }

    /**
     * リンククリックハンドラ
     */
    function handleLinkClick(e) {
        // 外部リンクの場合は何もしない
        if (e.currentTarget.hostname !== window.location.hostname) {
            return;
        }
        
        // 少し遅延してからメニューを閉じる（スムーズな遷移のため）
        setTimeout(() => {
            closeMenu();
        }, 100);
    }

    /**
     * DOMContentLoaded時に初期化
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();