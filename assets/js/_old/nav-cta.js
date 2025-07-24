/**
 * Navigation CTA Button Animations
 */
(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        const navCtaButton = document.querySelector('.nav-cta-button');
        
        if (!navCtaButton) return;

        // マウス追従エフェクト
        navCtaButton.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const deltaX = (x - centerX) / centerX;
            const deltaY = (y - centerY) / centerY;
            
            this.style.transform = `perspective(1000px) rotateY(${deltaX * 10}deg) rotateX(${-deltaY * 10}deg)`;
        });

        navCtaButton.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });

        // クリック時のリップルエフェクト
        navCtaButton.addEventListener('click', function(e) {
            // リップル要素を作成
            const ripple = document.createElement('span');
            ripple.classList.add('cta-ripple');
            
            // クリック位置を計算
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            
            this.appendChild(ripple);
            
            // アニメーション後に削除
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });

        // スクロール時の視差効果 - 無効化（スティッキーヘッダーと競合するため）
        /*
        let ticking = false;
        function updateParallax() {
            const scrolled = window.pageYOffset;
            const speed = 0.5;
            
            if (navCtaButton) {
                navCtaButton.style.transform = `translateY(${scrolled * speed * 0.1}px)`;
            }
            
            ticking = false;
        }
        
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(updateParallax);
                ticking = true;
            }
        });
        */

        // タイピングエフェクト（オプション）
        const ctaText = navCtaButton.querySelector('.cta-text');
        if (ctaText && ctaText.dataset.typing === 'true') {
            const originalText = ctaText.textContent;
            ctaText.textContent = '';
            
            let charIndex = 0;
            function typeText() {
                if (charIndex < originalText.length) {
                    ctaText.textContent += originalText.charAt(charIndex);
                    charIndex++;
                    setTimeout(typeText, 100);
                }
            }
            
            // ビューポートに入ったら開始
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        typeText();
                        observer.unobserve(entry.target);
                    }
                });
            });
            
            observer.observe(navCtaButton);
        }
    });
})();