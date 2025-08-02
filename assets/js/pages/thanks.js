/**
 * Thanks Page Functionality
 * お問い合わせ完了ページの機能
 */
(function() {
    'use strict';

    // ページ読み込み完了時の処理
    document.addEventListener('DOMContentLoaded', function() {
        // 成功アイコンのアニメーション再生
        animateSuccessIcon();
        
        // タイムラインのアニメーション
        animateTimeline();
        
        // パララックス効果
        initParallaxEffect();
        
        
        // コンテンツカードのアニメーション
        observeContentCards();
        
        // フォーム送信のトラッキング（Analytics用）
        trackFormSubmission();
        
        // 自動リダイレクト（オプション）
        // initAutoRedirect();
    });

    /**
     * 成功アイコンのアニメーション
     */
    function animateSuccessIcon() {
        const successIcon = document.querySelector('.success-icon');
        if (successIcon) {
            // アイコンの pulse アニメーション
            setTimeout(() => {
                successIcon.classList.add('pulse');
            }, 1000);
            
            // pulse アニメーションのループ
            setInterval(() => {
                successIcon.classList.remove('pulse');
                setTimeout(() => {
                    successIcon.classList.add('pulse');
                }, 100);
            }, 5000);
        }
    }

    /**
     * タイムラインのアニメーション
     */
    function animateTimeline() {
        const timelineItems = document.querySelectorAll('.timeline-item');
        let currentStep = 1;
        
        // 現在のステップをハイライト
        function highlightStep(step) {
            timelineItems.forEach((item, index) => {
                if (index < step) {
                    item.classList.add('completed');
                }
                if (index === step - 1) {
                    item.classList.add('active');
                }
            });
        }
        
        // 初期表示
        highlightStep(currentStep);
        
        // プログレスバーのアニメーション
        const timeline = document.querySelector('.timeline');
        if (timeline) {
            const progressBar = document.createElement('div');
            progressBar.className = 'timeline-progress';
            timeline.appendChild(progressBar);
            
            // プログレスバーのアニメーション
            setTimeout(() => {
                progressBar.style.height = '25%';
            }, 1000);
        }
    }

    /**
     * パララックス効果
     */
    function initParallaxEffect() {
        const bgAnimation = document.querySelector('.thanks-bg-animation');
        const confettiElements = document.querySelectorAll('.confetti');
        
        if (!bgAnimation) return;
        
        let ticking = false;
        
        function updateParallax() {
            const scrolled = window.pageYOffset;
            
            // 背景のパララックス
            const bgGradient = bgAnimation.querySelector('.bg-gradient');
            if (bgGradient) {
                bgGradient.style.transform = `rotate(${scrolled * 0.1}deg) scale(${1 + scrolled * 0.0002})`;
            }
            
            // 紙吹雪のパララックス
            confettiElements.forEach((confetti, index) => {
                const speed = 0.5 + (index * 0.1);
                confetti.style.transform = `translateY(${scrolled * speed}px) rotate(${scrolled * (index + 1)}deg)`;
            });
            
            ticking = false;
        }
        
        function requestTick() {
            if (!ticking) {
                window.requestAnimationFrame(updateParallax);
                ticking = true;
            }
        }
        
        window.addEventListener('scroll', requestTick);
    }


    /**
     * コンテンツカードの遅延表示アニメーション
     */
    function observeContentCards() {
        const cards = document.querySelectorAll('.content-card, .action-card');
        
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('visible');
                        }, index * 100);
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '50px'
            });
            
            cards.forEach(card => {
                card.classList.add('observe-fade');
                observer.observe(card);
            });
        }
    }

    /**
     * フォーム送信のトラッキング
     */
    function trackFormSubmission() {
        // Google Analytics or other tracking
        if (typeof gtag !== 'undefined') {
            gtag('event', 'form_submission', {
                'event_category': 'Contact',
                'event_label': 'Success Page Viewed'
            });
        }
        
        // Facebook Pixel
        if (typeof fbq !== 'undefined') {
            fbq('track', 'Lead');
        }
    }

    /**
     * 自動リダイレクト（オプション）
     */
    function initAutoRedirect() {
        const redirectTime = 30; // 30秒後にリダイレクト
        let timeLeft = redirectTime;
        
        const redirectNotice = document.createElement('div');
        redirectNotice.className = 'redirect-notice';
        redirectNotice.innerHTML = `
            <p>このページは<span id="redirect-timer">${timeLeft}</span>秒後に自動的にホームページへ移動します</p>
            <button class="cancel-redirect">自動移動をキャンセル</button>
        `;
        
        document.querySelector('.thanks-hero').appendChild(redirectNotice);
        
        const timer = setInterval(() => {
            timeLeft--;
            document.getElementById('redirect-timer').textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(timer);
                window.location.href = '/';
            }
        }, 1000);
        
        // キャンセルボタン
        redirectNotice.querySelector('.cancel-redirect').addEventListener('click', () => {
            clearInterval(timer);
            redirectNotice.remove();
        });
    }

    /**
     * アニメーション用のCSS追加
     */
    const style = document.createElement('style');
    style.textContent = `
        /* Pulse Animation */
        .success-icon.pulse {
            animation: pulse 0.5s ease-out;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 10px 30px rgba(0, 134, 123, 0.3);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 15px 40px rgba(0, 134, 123, 0.4);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 10px 30px rgba(0, 134, 123, 0.3);
            }
        }
        
        /* Timeline Progress */
        .timeline-progress {
            position: absolute;
            left: 50%;
            top: 0;
            width: 2px;
            height: 0;
            background: var(--primary-color);
            transform: translateX(-50%);
            transition: height 2s ease-out;
            z-index: 0;
        }
        
        @media (max-width: 768px) {
            .timeline-progress {
                left: 30px;
            }
        }
        
        /* Timeline Completed State */
        .timeline-item.completed .timeline-marker {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .timeline-item.completed .timeline-marker i {
            color: white;
        }
        
        /* Fade In Up Animation */
        .fade-in-up {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        /* Observe Fade */
        .observe-fade {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease-out;
        }
        
        .observe-fade.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Redirect Notice */
        .redirect-notice {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        
        .redirect-notice p {
            margin-bottom: 0.5rem;
            color: var(--text-color);
        }
        
        #redirect-timer {
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .cancel-redirect {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.875rem;
            transition: background 0.3s ease;
        }
        
        .cancel-redirect:hover {
            background: var(--primary-dark);
        }
    `;
    document.head.appendChild(style);

})();