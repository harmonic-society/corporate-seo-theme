/**
 * モバイルメニュー機能（シンプル版）
 */
document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    const toggleButton = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    const mobileOverlay = document.querySelector('.mobile-menu-overlay');
    
    if (!toggleButton || !mobileMenu) {
        console.log('Mobile menu elements not found');
        return;
    }
    
    console.log('Mobile menu initialized');
    
    // メニュー開閉
    toggleButton.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const isOpen = mobileMenu.classList.contains('active');
        
        if (isOpen) {
            // 閉じる
            mobileMenu.classList.remove('active');
            toggleButton.classList.remove('active');
            if (mobileOverlay) mobileOverlay.classList.remove('active');
            document.body.style.overflow = '';
        } else {
            // 開く
            mobileMenu.classList.add('active');
            toggleButton.classList.add('active');
            if (mobileOverlay) mobileOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    });
    
    // オーバーレイクリックで閉じる
    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', function() {
            mobileMenu.classList.remove('active');
            toggleButton.classList.remove('active');
            mobileOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
    
    // メニューリンククリックで閉じる
    const menuLinks = mobileMenu.querySelectorAll('a');
    menuLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            mobileMenu.classList.remove('active');
            toggleButton.classList.remove('active');
            if (mobileOverlay) mobileOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    });
});