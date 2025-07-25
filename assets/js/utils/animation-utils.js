/**
 * Animation Utilities
 * 共通のアニメーション関連ユーティリティ関数
 */
window.AnimationUtils = (function() {
    'use strict';

    /**
     * モバイルデバイスかどうかを判定
     */
    function isMobile() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ||
               (window.innerWidth <= 768);
    }

    /**
     * アニメーション削減設定を確認
     */
    function prefersReducedMotion() {
        return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    }

    /**
     * GPUアクセラレーションを有効化
     */
    function enableGPUAcceleration(element) {
        if (element) {
            element.style.transform = 'translateZ(0)';
            element.style.backfaceVisibility = 'hidden';
            element.style.perspective = '1000px';
        }
    }

    /**
     * スクロールイベントのスロットリング
     */
    function throttleScroll(func, delay = 16) {
        let lastCall = 0;
        let ticking = false;
        
        return function(...args) {
            const now = performance.now();
            
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    func.apply(this, args);
                    lastCall = now;
                    ticking = false;
                });
                ticking = true;
            }
        };
    }

    /**
     * デバウンス関数
     */
    function debounce(func, wait) {
        let timeout;
        
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func.apply(this, args);
            };
            
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    /**
     * 最適化されたIntersection Observer作成
     */
    function createOptimizedObserver(callback, options = {}) {
        const defaultOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };
        
        const observerOptions = { ...defaultOptions, ...options };
        
        return new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    callback(entry);
                }
            });
        }, observerOptions);
    }

    /**
     * パフォーマンス測定ヘルパー
     */
    function measurePerformance(name, func) {
        const startTime = performance.now();
        const result = func();
        const endTime = performance.now();
        
        console.log(`${name} took ${endTime - startTime}ms`);
        return result;
    }

    /**
     * RAF（RequestAnimationFrame）ベースのループ
     */
    function createRAFLoop(callback) {
        let running = false;
        let rafId = null;
        
        function loop() {
            callback();
            if (running) {
                rafId = requestAnimationFrame(loop);
            }
        }
        
        return {
            start() {
                if (!running) {
                    running = true;
                    loop();
                }
            },
            stop() {
                running = false;
                if (rafId) {
                    cancelAnimationFrame(rafId);
                    rafId = null;
                }
            }
        };
    }

    /**
     * 要素の可視性チェック
     */
    function isElementInViewport(element, offset = 0) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= -offset &&
            rect.left >= -offset &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) + offset &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth) + offset
        );
    }

    /**
     * スムーズスクロール
     */
    function smoothScrollTo(target, duration = 800) {
        const targetElement = typeof target === 'string' ? document.querySelector(target) : target;
        if (!targetElement) return;
        
        const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset;
        const startPosition = window.pageYOffset;
        const distance = targetPosition - startPosition;
        const startTime = performance.now();
        
        function ease(t) {
            return t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t;
        }
        
        function animation(currentTime) {
            const timeElapsed = currentTime - startTime;
            const progress = Math.min(timeElapsed / duration, 1);
            
            window.scrollTo(0, startPosition + distance * ease(progress));
            
            if (progress < 1) {
                requestAnimationFrame(animation);
            }
        }
        
        requestAnimationFrame(animation);
    }

    // Public API
    return {
        isMobile,
        prefersReducedMotion,
        enableGPUAcceleration,
        throttleScroll,
        debounce,
        createOptimizedObserver,
        measurePerformance,
        createRAFLoop,
        isElementInViewport,
        smoothScrollTo
    };
})();