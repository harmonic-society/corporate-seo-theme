/**
 * Contact Form 7 Mobile Fix
 * モバイルデバイスでのドロップダウン選択問題を修正
 */
(function() {
    'use strict';

    // モバイルデバイスの判定
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || 
                     window.innerWidth <= 768;

    if (!isMobile) return;

    // DOM Ready
    document.addEventListener('DOMContentLoaded', function() {
        // Contact Form 7のセレクトボックスを取得
        const cf7Selects = document.querySelectorAll('.wpcf7-select');
        
        cf7Selects.forEach(select => {
            // iOS Safari対策：フォーカス時の処理
            select.addEventListener('touchstart', function(e) {
                e.stopPropagation();
                this.focus();
            }, { passive: true });
            
            // Androidデバイス対策
            select.addEventListener('click', function(e) {
                e.stopPropagation();
                if (/Android/i.test(navigator.userAgent)) {
                    // Androidでセレクトボックスを強制的に開く
                    const event = new MouseEvent('mousedown', {
                        view: window,
                        bubbles: true,
                        cancelable: true
                    });
                    this.dispatchEvent(event);
                }
            });
            
            // 親要素のイベント伝播を防ぐ
            const wrapper = select.closest('.wpcf7-form-control-wrap');
            if (wrapper) {
                wrapper.addEventListener('touchstart', function(e) {
                    e.stopPropagation();
                }, { passive: true });
            }
            
            // 選択後の処理
            select.addEventListener('change', function() {
                // 値が選択されたことを親要素に通知
                if (this.value) {
                    this.classList.add('has-value');
                    if (wrapper) {
                        wrapper.classList.add('has-value');
                    }
                }
                
                // iOS でキーボードを確実に閉じる
                if (/iPhone|iPad|iPod/.test(navigator.userAgent)) {
                    this.blur();
                    // 一時的にreadonlyにしてキーボードを防ぐ
                    this.setAttribute('readonly', 'readonly');
                    setTimeout(() => {
                        this.removeAttribute('readonly');
                    }, 100);
                }
            });
            
            // タッチイベントの最適化
            let touchStartTime = 0;
            select.addEventListener('touchstart', function() {
                touchStartTime = Date.now();
            }, { passive: true });
            
            select.addEventListener('touchend', function(e) {
                const touchDuration = Date.now() - touchStartTime;
                // 短いタップの場合のみ処理
                if (touchDuration < 200) {
                    e.preventDefault();
                    this.click();
                }
            });
        });
        
        // Contact Form 7イベントリスナー
        if (typeof wpcf7 !== 'undefined') {
            // フォーム初期化時の処理
            document.addEventListener('wpcf7init', function(event) {
                const form = event.detail.contactForm.form;
                const selects = form.querySelectorAll('.wpcf7-select');
                
                selects.forEach(select => {
                    // 初期値がある場合はスタイルを適用
                    if (select.value) {
                        select.classList.add('has-value');
                        const wrapper = select.closest('.wpcf7-form-control-wrap');
                        if (wrapper) {
                            wrapper.classList.add('has-value');
                        }
                    }
                });
            });
        }
    });

    // ビューポートの高さ修正（キーボード表示時の対策）
    function updateViewportHeight() {
        const vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
    }

    window.addEventListener('resize', updateViewportHeight);
    updateViewportHeight();

})();