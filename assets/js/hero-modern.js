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
        gradientBg: heroSection.querySelector('.hero-gradient-bg'),
        geometricShapes: heroSection.querySelectorAll('.geometric-shape'),
        heroCard: heroSection.querySelector('.hero-card')
    };

    // スクロールパララックス
    let ticking = false;
    function updateParallax() {
        const scrolled = window.pageYOffset;
        
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

    // マウス追従エフェクト
    if (window.innerWidth > 768) {
        heroSection.addEventListener('mousemove', (e) => {
            const rect = heroSection.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width;
            const y = (e.clientY - rect.top) / rect.height;
            
            if (parallaxElements.heroCard) {
                const xOffset = (x - 0.5) * 20;
                const yOffset = (y - 0.5) * 20;
                parallaxElements.heroCard.style.transform = 
                    `perspective(1000px) rotateY(${xOffset * 0.5}deg) rotateX(${-yOffset * 0.5}deg)`;
            }
        });

        heroSection.addEventListener('mouseleave', () => {
            if (parallaxElements.heroCard) {
                parallaxElements.heroCard.style.transform = 
                    'perspective(1000px) rotateY(-5deg)';
            }
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

    // 統計カウントアップアニメーション
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px'
    };

    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumbers = entry.target.querySelectorAll('.stat-number');
                statNumbers.forEach(stat => {
                    const finalValue = stat.textContent;
                    const isPercentage = finalValue.includes('%');
                    const isMultiplier = finalValue.includes('x');
                    const is247 = finalValue.includes('/');
                    
                    if (isPercentage) {
                        const num = parseInt(finalValue);
                        animateCounter(stat, 0, num, 2000, '%');
                    } else if (isMultiplier) {
                        const num = parseFloat(finalValue);
                        animateCounter(stat, 0, num, 2000, 'x', true);
                    } else if (!is247) {
                        const num = parseInt(finalValue);
                        animateCounter(stat, 0, num, 2000);
                    }
                });
                statsObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    const heroStats = heroSection.querySelector('.hero-stats');
    if (heroStats) {
        statsObserver.observe(heroStats);
    }

    // カウンターアニメーション関数
    function animateCounter(element, start, end, duration, suffix = '', isFloat = false) {
        const startTime = performance.now();
        
        function updateCounter(currentTime) {
            const elapsedTime = currentTime - startTime;
            const progress = Math.min(elapsedTime / duration, 1);
            
            const easeOutQuart = 1 - Math.pow(1 - progress, 4);
            const currentValue = start + (end - start) * easeOutQuart;
            
            if (isFloat) {
                element.textContent = currentValue.toFixed(1) + suffix;
            } else {
                element.textContent = Math.floor(currentValue) + suffix;
            }
            
            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            }
        }
        
        requestAnimationFrame(updateCounter);
    }

    // AIチャットバブルの順次表示
    const chatBubbles = heroSection.querySelectorAll('.ai-chat-bubble');
    chatBubbles.forEach((bubble, index) => {
        bubble.style.opacity = '0';
        bubble.style.transform = 'translateX(-20px)';
        
        setTimeout(() => {
            bubble.style.opacity = '1';
            bubble.style.transform = 'translateX(0)';
        }, 1000 + (index * 500));
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