/**
 * Modern Hero Section JavaScript
 * 
 * インタラクティブな要素とアニメーション
 */

(function() {
    'use strict';

    // DOM要素の取得
    const heroSection = document.querySelector('.hero-modern');
    if (!heroSection) return;

    // パララックス効果
    const parallaxElements = {
        bgImage: heroSection.querySelector('.hero-bg-image'),
        gradientBg: heroSection.querySelector('.hero-gradient-bg'),
        geometricShapes: heroSection.querySelectorAll('.geometric-shape'),
        heroCard: heroSection.querySelector('.hero-card')
    };

    // スクロールパララックス
    let ticking = false;
    function updateParallax() {
        const scrolled = window.pageYOffset;
        
        if (parallaxElements.bgImage) {
            parallaxElements.bgImage.style.transform = `translateY(${scrolled * 0.3}px)`;
        }
        
        if (parallaxElements.gradientBg) {
            parallaxElements.gradientBg.style.transform = `translateY(${scrolled * 0.5}px)`;
        }
        
        parallaxElements.geometricShapes.forEach((shape, index) => {
            const speed = 0.3 + (index * 0.1);
            shape.style.transform = `translateY(${scrolled * speed}px) rotate(${scrolled * 0.1}deg)`;
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

    // 画像の3Dエフェクト
    const heroImageWrapper = heroSection.querySelector('.hero-image-wrapper');
    if (window.innerWidth > 768 && heroImageWrapper) {
        heroSection.addEventListener('mousemove', (e) => {
            const rect = heroSection.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width;
            const y = (e.clientY - rect.top) / rect.height;
            
            const xOffset = (x - 0.5) * 10;
            const yOffset = (y - 0.5) * 10;
            heroImageWrapper.style.transform = 
                `perspective(1000px) rotateY(${xOffset * 0.5}deg) rotateX(${-yOffset * 0.5}deg)`;
        });

        heroSection.addEventListener('mouseleave', () => {
            heroImageWrapper.style.transform = 
                'perspective(1000px) rotateY(-5deg)';
        });
    }

    // タイピングアニメーション
    const typeElements = document.querySelectorAll('.hero-title-gradient');
    typeElements.forEach(element => {
        const text = element.textContent;
        element.textContent = '';
        element.style.opacity = '1';
        
        let charIndex = 0;
        const typeInterval = setInterval(() => {
            if (charIndex < text.length) {
                element.textContent += text[charIndex];
                charIndex++;
            } else {
                clearInterval(typeInterval);
            }
        }, 100);
    });


    // スクロールインジケーターのクリックイベント
    const scrollIndicator = heroSection.querySelector('.hero-scroll-indicator');
    if (scrollIndicator) {
        scrollIndicator.addEventListener('click', () => {
            const nextSection = heroSection.nextElementSibling;
            if (nextSection) {
                nextSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }

})();