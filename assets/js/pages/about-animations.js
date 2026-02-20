/**
 * Aboutページのアニメーション（最適化版）
 */
document.addEventListener('DOMContentLoaded', function() {
    // AnimationUtilsが読み込まれているか確認
    if (typeof AnimationUtils === 'undefined') {
        console.error('AnimationUtils is required for about-animations-optimized.js');
        return;
    }

    const utils = AnimationUtils;
    const isMobile = utils.isMobile();
    const prefersReducedMotion = utils.prefersReducedMotion();

    // モバイルまたはアニメーション削減設定時は簡易版を実行
    if (isMobile || prefersReducedMotion) {
        // フェードインのみ実行
        document.querySelectorAll('.circle-wrapper, .info-list, .vision-item').forEach(element => {
            element.style.opacity = '1';
            element.style.transform = 'none';
        });
        return;
    }

    // ==========================================================================
    // デスクトップ向けアニメーション
    // ==========================================================================

    // 円形ビジュアルのホバー効果（タッチデバイスでは無効）
    if (!utils.isTouchDevice()) {
        const circles = document.querySelectorAll('.circle');
        circles.forEach(circle => {
            // GPUアクセラレーションを有効化
            utils.enableGPUAcceleration(circle);
            
            circle.addEventListener('mouseenter', function() {
                this.style.transform = 'translate3d(-50%, -50%, 0) scale(1.05)';
            });
            
            circle.addEventListener('mouseleave', function() {
                this.style.transform = 'translate3d(-50%, -50%, 0) scale(1)';
            });
        });
    }

    // スクロールアニメーション（最適化されたIntersection Observer）
    const observer = utils.createOptimizedObserver((entry) => {
        const target = entry.target;
        target.classList.add('visible');
        
        // 円形アニメーション
        if (target.classList.contains('circle-wrapper')) {
            const circles = target.querySelectorAll('.circle');
            circles.forEach((circle, index) => {
                // GPUアクセラレーションを有効化
                utils.enableGPUAcceleration(circle);
                
                setTimeout(() => {
                    circle.style.animation = 'scaleIn 0.6s ease-out forwards';
                    // アニメーション終了後にGPUアクセラレーションをクリーンアップ
                    circle.addEventListener('animationend', () => {
                        utils.cleanupGPUAcceleration(circle);
                    }, { once: true });
                }, index * 100);
            });
        }
        
        // 会社情報アニメーション
        if (target.classList.contains('info-list')) {
            const items = target.querySelectorAll('.info-item');
            items.forEach((item, index) => {
                utils.enableGPUAcceleration(item);
                
                setTimeout(() => {
                    item.style.animation = 'slideInLeft 0.6s ease-out forwards';
                    item.style.opacity = '1';
                    
                    item.addEventListener('animationend', () => {
                        utils.cleanupGPUAcceleration(item);
                    }, { once: true });
                }, index * 50);
            });
        }
        
        // 監視を解除（一度だけアニメーション実行）
        observer.unobserve(target);
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    });

    // 監視対象の要素
    const animatedElements = document.querySelectorAll('.circle-wrapper, .info-list, .vision-item');
    animatedElements.forEach(element => {
        observer.observe(element);
    });

    // パララックス効果（スロットリング済み）
    const heroSection = document.querySelector('.about-hero');
    if (heroSection && !isMobile) {
        utils.enableGPUAcceleration(heroSection);
        
        const updateParallax = utils.throttleScroll(() => {
            const scrolled = window.pageYOffset;
            const parallaxSpeed = 0.5;
            
            if (scrolled < window.innerHeight) {
                heroSection.style.transform = `translate3d(0, ${scrolled * parallaxSpeed}px, 0)`;
            }
        });
        
        window.addEventListener('scroll', updateParallax, { passive: true });
    }

    // 値のカウントアップアニメーション（requestAnimationFrame使用）
    const countUp = (element, start, end, duration) => {
        const startTime = performance.now();
        
        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // イージング関数
            const easeOutQuad = progress => 1 - (1 - progress) * (1 - progress);
            const easedProgress = easeOutQuad(progress);
            
            const current = start + (end - start) * easedProgress;
            element.textContent = Math.floor(current);
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                element.textContent = end;
            }
        };
        
        requestAnimationFrame(animate);
    };

    // カウンターの監視
    const counterObserver = utils.createOptimizedObserver((entry) => {
        const counter = entry.target;
        if (!counter.dataset.counted) {
            counter.dataset.counted = 'true';
            const target = parseInt(counter.dataset.target || counter.textContent, 10);
            countUp(counter, 0, target, 2000);
        }
    }, { threshold: 0.5 });

    document.querySelectorAll('.counter').forEach(counter => {
        counterObserver.observe(counter);
    });

    // クリーンアップ
    window.addEventListener('beforeunload', () => {
        observer.disconnect();
        counterObserver.disconnect();
    });

    // 「3つの調和」セクションの可視性を保証
    function ensureValuesVisible() {
        const valueItems = document.querySelectorAll('.value-item');
        const valuesGrid = document.querySelector('.values-grid');
        const heroValues = document.querySelector('.hero-values');

        if (heroValues) {
            heroValues.style.display = 'block';
            heroValues.style.visibility = 'visible';
            heroValues.style.opacity = '1';
        }

        if (valuesGrid) {
            valuesGrid.style.display = 'grid';
            valuesGrid.style.visibility = 'visible';
            valuesGrid.style.opacity = '1';
        }

        valueItems.forEach(item => {
            item.style.animation = 'none';
            item.style.transform = 'none';
            item.style.opacity = '1';
            item.style.visibility = 'visible';
        });

        // アニメーションクラスの干渉を除去
        document.querySelectorAll('.fade-in, .fade-in-up, .fade-in-down, .animate').forEach(el => {
            if (el.closest('.hero-values')) {
                el.classList.remove('fade-in', 'fade-in-up', 'fade-in-down', 'animate');
            }
        });
    }

    ensureValuesVisible();
    // 他のスクリプトの干渉に備えた遅延チェック
    setTimeout(ensureValuesVisible, 1000);

    // アニメーションのCSS定義（JavaScriptから動的に追加）
    if (!document.getElementById('about-animations-styles')) {
        const style = document.createElement('style');
        style.id = 'about-animations-styles';
        style.textContent = `
            @keyframes scaleIn {
                from {
                    opacity: 0;
                    transform: translate(-50%, -50%) scale(0.8);
                }
                to {
                    opacity: 1;
                    transform: translate(-50%, -50%) scale(1);
                }
            }
            
            @keyframes slideInLeft {
                from {
                    opacity: 0;
                    transform: translateX(-30px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            
            /* モバイルではアニメーションを簡略化 */
            @media (max-width: 768px) {
                @keyframes scaleIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
                
                @keyframes slideInLeft {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
            }
        `;
        document.head.appendChild(style);
    }
});