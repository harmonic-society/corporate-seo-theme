/**
 * Mobile Menu Modal
 * モーダルウィンドウ方式のモバイルメニュー
 */

(function() {
    'use strict';

    // DOM要素の取得
    let menuToggle;
    let modalOverlay;
    let modal;
    let closeButton;
    let menuLinks;
    let isOpen = false;

    // 初期化
    function init() {
        // モーダル要素の作成
        createModalElements();
        
        // イベントリスナーの設定
        setupEventListeners();
    }

    // モーダル要素の作成
    function createModalElements() {
        // 既存のモバイルメニューボタンを取得
        menuToggle = document.querySelector('.mobile-menu-toggle');
        if (!menuToggle) {
            console.warn('Mobile menu toggle button not found');
            return;
        }

        // オーバーレイの作成
        modalOverlay = document.createElement('div');
        modalOverlay.className = 'mobile-menu-modal-overlay';
        modalOverlay.setAttribute('aria-hidden', 'true');

        // モーダル本体の作成
        modal = document.createElement('div');
        modal.className = 'mobile-menu-modal';
        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-modal', 'true');
        modal.setAttribute('aria-labelledby', 'mobile-menu-modal-title');
        modal.setAttribute('aria-hidden', 'true');

        // モーダルの内容を構築
        const modalHTML = `
            <div class="mobile-menu-modal-header">
                <h2 id="mobile-menu-modal-title" class="mobile-menu-modal-title">メニュー</h2>
                <button type="button" class="mobile-menu-modal-close" aria-label="メニューを閉じる">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M18.3 5.71a1 1 0 0 0-1.42 0L12 10.59 7.11 5.7A1 1 0 1 0 5.7 7.11L10.59 12 5.7 16.89a1 1 0 0 0 1.42 1.42L12 13.41l4.88 4.88a1 1 0 0 0 1.42-1.42L13.41 12l4.88-4.88a1 1 0 0 0 0-1.41z"/>
                    </svg>
                </button>
            </div>
            <div class="mobile-menu-modal-body">
                <nav class="mobile-menu-modal-nav" id="mobile-menu-modal-nav">
                    <!-- メニューがここに挿入される -->
                </nav>
                <div class="mobile-menu-modal-social" id="mobile-menu-modal-social">
                    <!-- ソーシャルリンクがここに挿入される -->
                </div>
            </div>
            <div class="mobile-menu-modal-footer">
                <a href="#" class="mobile-menu-modal-cta" id="mobile-menu-modal-cta">
                    お問い合わせ
                </a>
            </div>
        `;

        modal.innerHTML = modalHTML;

        // 既存のメニュー内容をコピー
        copyMenuContent();

        // DOMに追加
        document.body.appendChild(modalOverlay);
        document.body.appendChild(modal);

        // 閉じるボタンの参照を取得
        closeButton = modal.querySelector('.mobile-menu-modal-close');
    }

    // 既存のメニュー内容をモーダルにコピー
    function copyMenuContent() {
        // 既存のモバイルメニューを探す
        const existingMenu = document.querySelector('.mobile-menu-nav ul, .main-navigation ul, #primary-menu, #mobile-navigation');
        const modalNav = modal.querySelector('#mobile-menu-modal-nav');
        
        if (existingMenu) {
            // メニューをクローン
            const menuClone = existingMenu.cloneNode(true);
            
            // クラス名を調整
            menuClone.className = 'mobile-menu-modal-list';
            
            // すべてのインラインスタイルを削除（横並びになっている可能性があるため）
            menuClone.style.cssText = '';
            const allElements = menuClone.querySelectorAll('*');
            allElements.forEach(el => {
                el.style.cssText = '';
            });
            
            modalNav.appendChild(menuClone);
        }

        // ソーシャルリンクをコピー
        const existingSocial = document.querySelector('.mobile-menu-social .social-links');
        const modalSocial = modal.querySelector('#mobile-menu-modal-social');
        
        if (existingSocial) {
            const socialClone = existingSocial.cloneNode(true);
            modalSocial.appendChild(socialClone);
        } else {
            modalSocial.style.display = 'none';
        }

        // お問い合わせリンクを設定
        const contactLink = document.querySelector('.header-cta a');
        const modalCTA = modal.querySelector('#mobile-menu-modal-cta');
        
        if (contactLink && modalCTA) {
            modalCTA.href = contactLink.href;
        }

        // メニューリンクの参照を更新
        menuLinks = modal.querySelectorAll('.mobile-menu-modal-list a');
    }

    // イベントリスナーの設定
    function setupEventListeners() {
        // メニュートグルボタン
        if (menuToggle) {
            menuToggle.addEventListener('click', toggleMenu);
        }

        // 閉じるボタン
        if (closeButton) {
            closeButton.addEventListener('click', closeMenu);
        }

        // オーバーレイクリック
        if (modalOverlay) {
            modalOverlay.addEventListener('click', closeMenu);
        }

        // メニューリンククリック
        menuLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                // アンカーリンクの場合はスムーズスクロール
                if (link.getAttribute('href').startsWith('#')) {
                    e.preventDefault();
                    const target = document.querySelector(link.getAttribute('href'));
                    if (target) {
                        closeMenu();
                        setTimeout(() => {
                            target.scrollIntoView({ behavior: 'smooth' });
                        }, 300);
                    }
                } else {
                    // 通常のリンクの場合は少し遅延してから遷移
                    setTimeout(() => {
                        closeMenu();
                    }, 100);
                }
            });
        });

        // ESCキーで閉じる
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && isOpen) {
                closeMenu();
            }
        });

        // フォーカストラップ
        modal.addEventListener('keydown', trapFocus);
    }

    // メニューの開閉
    function toggleMenu() {
        if (isOpen) {
            closeMenu();
        } else {
            openMenu();
        }
    }

    // メニューを開く
    function openMenu() {
        isOpen = true;
        document.body.classList.add('mobile-menu-modal-open');
        modalOverlay.classList.add('is-active');
        modal.classList.add('is-active');
        
        // ARIA属性の更新
        menuToggle.setAttribute('aria-expanded', 'true');
        modal.setAttribute('aria-hidden', 'false');
        modalOverlay.setAttribute('aria-hidden', 'false');
        
        // フォーカスを閉じるボタンに移動
        setTimeout(() => {
            closeButton.focus();
        }, 100);
    }

    // メニューを閉じる
    function closeMenu() {
        isOpen = false;
        document.body.classList.remove('mobile-menu-modal-open');
        modalOverlay.classList.remove('is-active');
        modal.classList.remove('is-active');
        
        // ARIA属性の更新
        menuToggle.setAttribute('aria-expanded', 'false');
        modal.setAttribute('aria-hidden', 'true');
        modalOverlay.setAttribute('aria-hidden', 'true');
        
        // フォーカスをトグルボタンに戻す
        menuToggle.focus();
    }

    // フォーカストラップ
    function trapFocus(e) {
        if (e.key !== 'Tab') return;
        
        const focusableElements = modal.querySelectorAll(
            'a[href], button, [tabindex]:not([tabindex="-1"])'
        );
        const firstFocusable = focusableElements[0];
        const lastFocusable = focusableElements[focusableElements.length - 1];
        
        if (e.shiftKey) {
            if (document.activeElement === firstFocusable) {
                e.preventDefault();
                lastFocusable.focus();
            }
        } else {
            if (document.activeElement === lastFocusable) {
                e.preventDefault();
                firstFocusable.focus();
            }
        }
    }

    // 初期化実行
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();