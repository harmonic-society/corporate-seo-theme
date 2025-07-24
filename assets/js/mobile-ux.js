/**
 * モバイルUX改善JavaScript
 * Corporate SEO Pro Theme
 */

(function() {
    'use strict';

    // モバイル判定
    const isMobile = () => {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || 
               window.innerWidth <= 768;
    };

    // タッチデバイス判定
    const isTouchDevice = () => {
        return 'ontouchstart' in window || navigator.maxTouchPoints > 0;
    };

    // DOM Ready
    document.addEventListener('DOMContentLoaded', function() {
        if (!isMobile()) return;

        // モバイルクラスを追加
        document.body.classList.add('is-mobile');
        if (isTouchDevice()) {
            document.body.classList.add('is-touch');
        }

        // 各種初期化
        initMobileMenu();
        initSmoothScroll();
        initLazyLoading();
        optimizeAnimations();
        improveFormUX();
        initTouchGestures();
        fixViewportHeight();
        initFastClick();
    });

    /**
     * モバイルメニューの改善
     */
    function initMobileMenu() {
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        const mobileMenu = document.querySelector('.mobile-menu');
        const menuLinks = document.querySelectorAll('.mobile-menu-link');
        const body = document.body;

        if (!menuToggle || !mobileMenu) return;

        // メニュートグル
        menuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const isOpen = mobileMenu.classList.contains('active');
            
            if (isOpen) {
                closeMenu();
            } else {
                openMenu();
            }
        });

        // メニューを開く
        function openMenu() {
            mobileMenu.classList.add('active');
            menuToggle.classList.add('active');
            body.style.overflow = 'hidden';
            
            // アクセシビリティ
            menuToggle.setAttribute('aria-expanded', 'true');
            mobileMenu.setAttribute('aria-hidden', 'false');
        }

        // メニューを閉じる
        function closeMenu() {
            mobileMenu.classList.remove('active');
            menuToggle.classList.remove('active');
            body.style.overflow = '';
            
            // アクセシビリティ
            menuToggle.setAttribute('aria-expanded', 'false');
            mobileMenu.setAttribute('aria-hidden', 'true');
        }

        // メニューリンククリックで閉じる
        menuLinks.forEach(link => {
            link.addEventListener('click', () => {
                closeMenu();
            });
        });

        // 外側クリックで閉じる
        document.addEventListener('click', function(e) {
            if (mobileMenu.classList.contains('active') && 
                !mobileMenu.contains(e.target) && 
                !menuToggle.contains(e.target)) {
                closeMenu();
            }
        });

        // ESCキーで閉じる
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                closeMenu();
            }
        });
    }

    /**
     * スムーススクロール
     */
    function initSmoothScroll() {
        const links = document.querySelectorAll('a[href^="#"]');
        
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#') return;
                
                const target = document.querySelector(href);
                if (!target) return;
                
                e.preventDefault();
                
                const headerHeight = document.querySelector('.site-header').offsetHeight || 60;
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            });
        });
    }

    /**
     * 画像の遅延読み込み
     */
    function initLazyLoading() {
        const images = document.querySelectorAll('img[data-src]');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });

            images.forEach(img => imageObserver.observe(img));
        } else {
            // Fallback
            images.forEach(img => {
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
            });
        }
    }

    /**
     * アニメーションの最適化
     */
    function optimizeAnimations() {
        // 全てのアニメーション要素を取得
        const animatedElements = document.querySelectorAll('[class*="animate"], [class*="fade"], [class*="slide"]');
        
        // Intersection Observerで必要な時だけアニメーション
        const animationObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                } else {
                    entry.target.classList.remove('animate-in');
                }
            });
        }, {
            threshold: 0.1
        });

        animatedElements.forEach(el => {
            // モバイルでは簡素なアニメーションに置き換え
            el.style.animationDuration = '0.3s';
            el.style.animationDelay = '0s';
            animationObserver.observe(el);
        });

        // スクロール時のパフォーマンス最適化
        let ticking = false;
        function updateAnimations() {
            ticking = false;
        }

        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(updateAnimations);
                ticking = true;
            }
        }, { passive: true });
    }

    /**
     * フォームUXの改善
     */
    function improveFormUX() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            // 入力フィールドのフォーカス改善
            const inputs = form.querySelectorAll('input, textarea, select');
            
            inputs.forEach(input => {
                // ラベルクリックでフォーカス
                const label = form.querySelector(`label[for="${input.id}"]`);
                if (label) {
                    label.style.cursor = 'pointer';
                }
                
                // セレクトボックスの特別な処理
                if (input.tagName === 'SELECT') {
                    // タッチイベントの改善
                    input.addEventListener('touchstart', function(e) {
                        e.stopPropagation();
                    }, { passive: true });
                    
                    // クリックイベントの改善
                    input.addEventListener('click', function(e) {
                        e.stopPropagation();
                        // iOS でセレクトボックスを強制的に開く
                        if (/iPhone|iPad|iPod/.test(navigator.userAgent)) {
                            this.focus();
                        }
                    });
                    
                    // 変更イベントの処理
                    input.addEventListener('change', function() {
                        this.parentElement.classList.add('has-value');
                        // iOS でキーボードを閉じる
                        if (/iPhone|iPad|iPod/.test(navigator.userAgent)) {
                            this.blur();
                        }
                    });
                }
                
                // フォーカス時の処理
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('is-focused');
                    
                    // キーボードが開いた時のスクロール調整（セレクトボックス以外）
                    if (this.tagName !== 'SELECT') {
                        setTimeout(() => {
                            const rect = this.getBoundingClientRect();
                            const viewportHeight = window.innerHeight;
                            
                            if (rect.bottom > viewportHeight * 0.5) {
                                const scrollTop = window.pageYOffset + rect.top - viewportHeight * 0.3;
                                window.scrollTo({
                                    top: scrollTop,
                                    behavior: 'smooth'
                                });
                            }
                        }, 300);
                    }
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('is-focused');
                    if (this.value) {
                        this.parentElement.classList.add('has-value');
                    } else {
                        this.parentElement.classList.remove('has-value');
                    }
                });
            });
            
            // フォーム送信時の処理
            form.addEventListener('submit', function(e) {
                const submitButton = this.querySelector('[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.textContent = '送信中...';
                }
            });
        });
    }

    /**
     * タッチジェスチャーの実装
     */
    function initTouchGestures() {
        let touchStartX = 0;
        let touchStartY = 0;
        let touchEndX = 0;
        let touchEndY = 0;

        document.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
            touchStartY = e.changedTouches[0].screenY;
        }, { passive: true });

        document.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            touchEndY = e.changedTouches[0].screenY;
            handleGesture();
        }, { passive: true });

        function handleGesture() {
            const swipeThreshold = 50;
            const verticalThreshold = 100;
            
            // 横スワイプ
            if (Math.abs(touchEndX - touchStartX) > swipeThreshold && 
                Math.abs(touchEndY - touchStartY) < verticalThreshold) {
                
                if (touchEndX < touchStartX) {
                    // 左スワイプ
                    handleSwipeLeft();
                }
                if (touchEndX > touchStartX) {
                    // 右スワイプ
                    handleSwipeRight();
                }
            }
        }

        function handleSwipeLeft() {
            // カルーセルなどの次へ
            const activeCarousel = document.querySelector('.carousel.active');
            if (activeCarousel) {
                const nextButton = activeCarousel.querySelector('.carousel-next');
                if (nextButton) nextButton.click();
            }
        }

        function handleSwipeRight() {
            // カルーセルなどの前へ
            const activeCarousel = document.querySelector('.carousel.active');
            if (activeCarousel) {
                const prevButton = activeCarousel.querySelector('.carousel-prev');
                if (prevButton) prevButton.click();
            }
        }
    }

    /**
     * ビューポート高さの修正（iOS対策）
     */
    function fixViewportHeight() {
        // First we get the viewport height and we multiple it by 1% to get a value for a vh unit
        let vh = window.innerHeight * 0.01;
        // Then we set the value in the --vh custom property to the root of the document
        document.documentElement.style.setProperty('--vh', `${vh}px`);

        // We listen to the resize event
        window.addEventListener('resize', () => {
            // We execute the same script as before
            let vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        });
    }

    /**
     * FastClickの実装（タップ遅延の解消）
     */
    function initFastClick() {
        if (!isTouchDevice()) return;

        document.addEventListener('touchstart', function() {}, { passive: true });

        // タップ可能な要素
        const tappableElements = document.querySelectorAll('a, button, input, select, textarea, .tappable');
        
        tappableElements.forEach(element => {
            let touchStartTime = 0;
            let touchStartX = 0;
            let touchStartY = 0;

            element.addEventListener('touchstart', function(e) {
                touchStartTime = Date.now();
                touchStartX = e.touches[0].pageX;
                touchStartY = e.touches[0].pageY;
            }, { passive: true });

            element.addEventListener('touchend', function(e) {
                const touchEndTime = Date.now();
                const touchEndX = e.changedTouches[0].pageX;
                const touchEndY = e.changedTouches[0].pageY;

                // タップと判定する条件
                const tapDuration = touchEndTime - touchStartTime;
                const tapDistance = Math.sqrt(
                    Math.pow(touchEndX - touchStartX, 2) + 
                    Math.pow(touchEndY - touchStartY, 2)
                );

                if (tapDuration < 200 && tapDistance < 10) {
                    e.preventDefault();
                    // クリックイベントを発火
                    const clickEvent = new MouseEvent('click', {
                        view: window,
                        bubbles: true,
                        cancelable: true
                    });
                    this.dispatchEvent(clickEvent);
                }
            });
        });
    }

    /**
     * パフォーマンスモニタリング
     */
    function monitorPerformance() {
        // FPS監視
        let lastTime = performance.now();
        let frames = 0;
        let fps = 0;

        function checkFPS() {
            frames++;
            const currentTime = performance.now();
            
            if (currentTime >= lastTime + 1000) {
                fps = Math.round((frames * 1000) / (currentTime - lastTime));
                
                // 低FPSの場合はさらにアニメーションを削減
                if (fps < 30) {
                    document.body.classList.add('reduce-animations');
                }
                
                frames = 0;
                lastTime = currentTime;
            }
            
            requestAnimationFrame(checkFPS);
        }

        // パフォーマンスが問題になる場合のみ有効化
        // checkFPS();
    }

})();