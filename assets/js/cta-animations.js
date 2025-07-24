/**
 * CTA Section Animations - Mobile Optimized
 */
document.addEventListener('DOMContentLoaded', function() {
    const ctaSection = document.querySelector('.cta-section');
    if (!ctaSection) return;

    // モバイル判定（より厳密に）
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || 
                     window.innerWidth <= 768;
    
    // モバイルの場合は完全にアニメーションを無効化
    if (isMobile) {
        // 全てのアニメーション要素を削除または非表示
        const animatedElements = ctaSection.querySelectorAll('.cta-particles, .cta-glow, .cta-shapes, .cta-conical');
        animatedElements.forEach(el => {
            el.style.display = 'none';
        });
        
        // グラデーションを静的に
        const gradient = ctaSection.querySelector('.cta-gradient');
        if (gradient) {
            gradient.style.animation = 'none';
            gradient.style.background = 'linear-gradient(135deg, rgba(0,134,123,0.15) 0%, rgba(255,107,53,0.15) 100%)';
        }
        
        // CTAセクションに軽量クラスを追加
        ctaSection.classList.add('mobile-optimized');
        
        // シンプルなフェードインのみ
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    observer.unobserve(entry.target);
                }
            });
        });
        
        ctaSection.style.opacity = '0';
        ctaSection.style.transition = 'opacity 0.5s ease';
        observer.observe(ctaSection);
        
        return; // これ以降の処理をスキップ
    }

    // デスクトップのみの処理
    const observerOptions = {
        threshold: 0.3,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    observer.observe(ctaSection);
});

// モバイル用の最適化CSS
const mobileStyle = document.createElement('style');
mobileStyle.textContent = `
    /* モバイル最適化 */
    .cta-section.mobile-optimized {
        min-height: auto !important;
    }
    
    .cta-section.mobile-optimized * {
        animation: none !important;
        animation-delay: 0s !important;
        animation-duration: 0s !important;
    }
    
    .cta-section.mobile-optimized .cta-background > * {
        display: none !important;
    }
    
    .cta-section.mobile-optimized .cta-gradient {
        position: static !important;
        background: linear-gradient(135deg, rgba(0,134,123,0.1) 0%, rgba(255,107,53,0.1) 100%) !important;
    }
    
    @media (max-width: 768px) {
        /* 全てのtransformアニメーションを無効化 */
        .cta-section *,
        .cta-section *::before,
        .cta-section *::after {
            transform: none !important;
            animation: none !important;
        }
        
        /* ボタンのホバーエフェクトも簡素化 */
        .cta-button {
            transition: background-color 0.2s ease !important;
        }
        
        .cta-button:hover {
            transform: none !important;
        }
        
        /* アイコンアニメーション無効化 */
        .cta-feature-icon i {
            animation: none !important;
        }
        
        /* 電話アイコン */
        .cta-phone-icon {
            animation: none !important;
        }
    }
`;
document.head.appendChild(mobileStyle);