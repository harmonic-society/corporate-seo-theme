/**
 * Animation Utilities for Mobile Performance
 * 
 * モバイルパフォーマンスを最適化するためのユーティリティ関数群
 */

(function() {
    'use strict';

    // グローバルに AnimationUtils を公開
    window.AnimationUtils = {
        /**
         * スクロールイベントのスロットリング
         * requestAnimationFrameを使用してパフォーマンスを最適化
         */
        throttleScroll: function(callback) {
            let ticking = false;
            
            return function() {
                if (!ticking) {
                    window.requestAnimationFrame(function() {
                        callback();
                        ticking = false;
                    });
                    ticking = true;
                }
            };
        },

        /**
         * デバウンス関数
         * 連続的なイベントを制御
         */
        debounce: function(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        /**
         * モバイルデバイスの検出
         */
        isMobile: function() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ||
                   window.innerWidth <= 768;
        },

        /**
         * タッチデバイスの検出
         */
        isTouchDevice: function() {
            return 'ontouchstart' in window ||
                   navigator.maxTouchPoints > 0 ||
                   navigator.msMaxTouchPoints > 0;
        },

        /**
         * アニメーション削減設定の確認
         */
        prefersReducedMotion: function() {
            return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        },

        /**
         * GPUアクセラレーションのヒント追加
         */
        enableGPUAcceleration: function(element) {
            element.style.transform = 'translateZ(0)';
            element.style.willChange = 'transform';
        },

        /**
         * アニメーション終了後のクリーンアップ
         */
        cleanupGPUAcceleration: function(element) {
            element.style.willChange = 'auto';
        },

        /**
         * パフォーマンス最適化されたIntersection Observer
         */
        createOptimizedObserver: function(callback, options = {}) {
            const defaultOptions = {
                root: null,
                rootMargin: '50px',
                threshold: 0.1
            };
            
            const mergedOptions = { ...defaultOptions, ...options };
            
            return new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // アニメーション削減設定を確認
                        if (!this.prefersReducedMotion()) {
                            callback(entry);
                        }
                    }
                });
            }, mergedOptions);
        },

        /**
         * モバイル用のタッチイベント最適化
         */
        optimizeTouchEvents: function(element, handlers) {
            const options = { passive: true };
            
            if (handlers.start) {
                element.addEventListener('touchstart', handlers.start, options);
            }
            if (handlers.move) {
                element.addEventListener('touchmove', handlers.move, options);
            }
            if (handlers.end) {
                element.addEventListener('touchend', handlers.end, options);
            }
            
            // クリーンアップ関数を返す
            return function() {
                if (handlers.start) {
                    element.removeEventListener('touchstart', handlers.start, options);
                }
                if (handlers.move) {
                    element.removeEventListener('touchmove', handlers.move, options);
                }
                if (handlers.end) {
                    element.removeEventListener('touchend', handlers.end, options);
                }
            };
        },

        /**
         * アニメーションフレームのキャンセル可能なラッパー
         */
        createAnimationLoop: function(callback) {
            let animationId = null;
            let isRunning = false;
            
            const loop = () => {
                if (isRunning) {
                    callback();
                    animationId = requestAnimationFrame(loop);
                }
            };
            
            return {
                start: function() {
                    if (!isRunning) {
                        isRunning = true;
                        loop();
                    }
                },
                stop: function() {
                    isRunning = false;
                    if (animationId) {
                        cancelAnimationFrame(animationId);
                        animationId = null;
                    }
                }
            };
        },

        /**
         * DOM更新のバッチ処理
         */
        batchDOMUpdates: function(updates) {
            requestAnimationFrame(() => {
                const fragment = document.createDocumentFragment();
                updates(fragment);
                document.body.appendChild(fragment);
            });
        },

        /**
         * スムーズスクロールの最適化実装
         */
        smoothScrollTo: function(target, duration = 800) {
            const start = window.pageYOffset;
            const distance = target - start;
            const startTime = performance.now();
            
            const easeInOutCubic = (t) => {
                return t < 0.5 
                    ? 4 * t * t * t 
                    : 1 - Math.pow(-2 * t + 2, 3) / 2;
            };
            
            const scroll = (currentTime) => {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const easeProgress = easeInOutCubic(progress);
                
                window.scrollTo(0, start + distance * easeProgress);
                
                if (progress < 1) {
                    requestAnimationFrame(scroll);
                }
            };
            
            requestAnimationFrame(scroll);
        }
    };

})();