/**
 * Mobile Menu Fix Script
 * 
 * モバイルメニューの動作を修正
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        // モバイルメニュー要素の取得
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const mobileMenu = document.querySelector('.mobile-menu');
        const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
        const body = document.body;

        if (!mobileMenuToggle || !mobileMenu) {
            console.warn('Mobile menu elements not found');
            return;
        }

        // クリックイベントを確実に設定
        function handleMenuToggle(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const isActive = mobileMenu.classList.contains('active');
            
            if (!isActive) {
                // メニューを開く
                mobileMenu.classList.add('active');
                mobileMenuToggle.classList.add('active');
                if (mobileMenuOverlay) {
                    mobileMenuOverlay.classList.add('active');
                }
                body.style.overflow = 'hidden';
                mobileMenuToggle.setAttribute('aria-expanded', 'true');
            } else {
                // メニューを閉じる
                closeMenu();
            }
        }

        function closeMenu() {
            mobileMenu.classList.remove('active');
            mobileMenuToggle.classList.remove('active');
            if (mobileMenuOverlay) {
                mobileMenuOverlay.classList.remove('active');
            }
            body.style.overflow = '';
            mobileMenuToggle.setAttribute('aria-expanded', 'false');
        }

        // 既存のイベントリスナーを削除して新しいものを追加
        const newToggle = mobileMenuToggle.cloneNode(true);
        mobileMenuToggle.parentNode.replaceChild(newToggle, mobileMenuToggle);
        
        // 新しい要素にイベントリスナーを追加
        newToggle.addEventListener('click', handleMenuToggle);
        newToggle.addEventListener('touchend', function(e) {
            e.preventDefault();
            handleMenuToggle(e);
        });

        // オーバーレイクリックで閉じる
        if (mobileMenuOverlay) {
            mobileMenuOverlay.addEventListener('click', closeMenu);
        }

        // ESCキーで閉じる
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                closeMenu();
            }
        });

        // メニュー内のリンクをクリックしたら閉じる
        const menuLinks = mobileMenu.querySelectorAll('a');
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                setTimeout(closeMenu, 100);
            });
        });

        // デバッグ情報
        console.log('Mobile menu fix initialized');
    });
})();