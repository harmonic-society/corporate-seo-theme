/**
 * 関連サービスセクションのインタラクション
 */
(function() {
    'use strict';

    // DOM要素の取得
    const carousel = document.getElementById('related-services-carousel');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const prevButton = document.querySelector('.carousel-prev');
    const nextButton = document.querySelector('.carousel-next');
    const progressFill = document.querySelector('.progress-fill');
    
    if (!carousel) return;

    // カルーセルの状態管理
    let currentIndex = 0;
    let itemsPerView = 3;
    let totalItems = carousel.children.length;
    let maxIndex = Math.max(0, Math.ceil(totalItems / itemsPerView) - 1);
    
    // レスポンシブ対応
    function updateItemsPerView() {
        const width = window.innerWidth;
        if (width < 768) {
            itemsPerView = 1;
        } else if (width < 1024) {
            itemsPerView = 2;
        } else {
            itemsPerView = 3;
        }
        maxIndex = Math.max(0, Math.ceil(totalItems / itemsPerView) - 1);
        updateCarousel();
    }

    // カルーセルの更新
    function updateCarousel() {
        const translateX = -currentIndex * (100 / itemsPerView) * itemsPerView;
        carousel.style.transform = `translateX(${translateX}%)`;
        
        // プログレスバーの更新
        if (progressFill) {
            const progressPercentage = ((currentIndex + 1) / (maxIndex + 1)) * 100;
            progressFill.style.width = `${progressPercentage}%`;
        }
        
        // ボタンの有効/無効化
        if (prevButton) {
            prevButton.disabled = currentIndex === 0;
            prevButton.style.opacity = currentIndex === 0 ? '0.5' : '1';
        }
        if (nextButton) {
            nextButton.disabled = currentIndex === maxIndex;
            nextButton.style.opacity = currentIndex === maxIndex ? '0.5' : '1';
        }
    }

    // カルーセルナビゲーション
    if (prevButton) {
        prevButton.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', () => {
            if (currentIndex < maxIndex) {
                currentIndex++;
                updateCarousel();
            }
        });
    }

    // タッチ/スワイプ対応
    let touchStartX = 0;
    let touchEndX = 0;
    
    carousel.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });
    
    carousel.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });
    
    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0 && currentIndex < maxIndex) {
                // 左にスワイプ
                currentIndex++;
                updateCarousel();
            } else if (diff < 0 && currentIndex > 0) {
                // 右にスワイプ
                currentIndex--;
                updateCarousel();
            }
        }
    }

    // カテゴリーフィルター
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // アクティブクラスの切り替え
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // フィルタリング
            const cards = carousel.querySelectorAll('.related-service-card');
            let visibleCount = 0;
            
            cards.forEach(card => {
                if (filter === 'all' || card.classList.contains('category-' + filter)) {
                    card.style.display = 'block';
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.style.opacity = '1';
                    }, visibleCount * 50);
                    visibleCount++;
                } else {
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
            
            // カルーセルをリセット
            currentIndex = 0;
            totalItems = visibleCount;
            maxIndex = Math.max(0, Math.ceil(totalItems / itemsPerView) - 1);
            updateCarousel();
        });
    });

    // ホバーエフェクトの強化
    const cards = carousel.querySelectorAll('.related-service-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            // 他のカードを少し暗くする
            cards.forEach(otherCard => {
                if (otherCard !== card) {
                    otherCard.style.opacity = '0.7';
                    otherCard.style.filter = 'grayscale(20%)';
                }
            });
        });
        
        card.addEventListener('mouseleave', function() {
            // すべてのカードを元に戻す
            cards.forEach(otherCard => {
                otherCard.style.opacity = '1';
                otherCard.style.filter = 'none';
            });
        });
    });

    // Intersection Observerでアニメーション
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('animate-in');
                }, index * 100);
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // 関連サービスセクション全体の監視
    const relatedSection = document.querySelector('.service-related-modern');
    if (relatedSection) {
        observer.observe(relatedSection);
    }

    // キーボードナビゲーション
    document.addEventListener('keydown', (e) => {
        if (document.activeElement.closest('.service-related-modern')) {
            if (e.key === 'ArrowLeft' && currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            } else if (e.key === 'ArrowRight' && currentIndex < maxIndex) {
                currentIndex++;
                updateCarousel();
            }
        }
    });

    // 自動スクロール（オプション）
    let autoScrollInterval;
    const enableAutoScroll = false; // 必要に応じてtrueに変更
    
    if (enableAutoScroll) {
        function startAutoScroll() {
            autoScrollInterval = setInterval(() => {
                if (currentIndex < maxIndex) {
                    currentIndex++;
                } else {
                    currentIndex = 0;
                }
                updateCarousel();
            }, 5000);
        }
        
        function stopAutoScroll() {
            clearInterval(autoScrollInterval);
        }
        
        // マウスオーバーで自動スクロール停止
        carousel.addEventListener('mouseenter', stopAutoScroll);
        carousel.addEventListener('mouseleave', startAutoScroll);
        
        // 初期化
        startAutoScroll();
    }

    // 初期化
    window.addEventListener('resize', updateItemsPerView);
    updateItemsPerView();

    // CSSアニメーション用のクラスを追加
    const style = document.createElement('style');
    style.textContent = `
        .related-service-card {
            transition: all 0.3s ease;
        }
        
        .related-service-card.animate-in {
            animation: fadeInUp 0.6s ease forwards;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .services-carousel {
            transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
    `;
    document.head.appendChild(style);

})();