/**
 * Aboutページのアニメーション
 */
document.addEventListener('DOMContentLoaded', function() {
    // 円形ビジュアルのホバー効果
    const circles = document.querySelectorAll('.circle');
    circles.forEach(circle => {
        circle.addEventListener('mouseenter', function() {
            this.style.transform = 'translate(-50%, -50%) scale(1.05)';
        });
        
        circle.addEventListener('mouseleave', function() {
            this.style.transform = 'translate(-50%, -50%) scale(1)';
        });
    });

    // スクロールアニメーション
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                
                // 円形アニメーション
                if (entry.target.classList.contains('circle-wrapper')) {
                    const circles = entry.target.querySelectorAll('.circle');
                    circles.forEach((circle, index) => {
                        setTimeout(() => {
                            circle.style.animation = 'scaleIn 0.6s ease-out forwards';
                        }, index * 100);
                    });
                }
                
                // 会社情報アニメーション
                if (entry.target.classList.contains('info-list')) {
                    const items = entry.target.querySelectorAll('.info-item');
                    items.forEach((item, index) => {
                        setTimeout(() => {
                            item.style.animation = 'slideInLeft 0.6s ease-out forwards';
                            item.style.opacity = '1';
                        }, index * 50);
                    });
                }
            }
        });
    }, observerOptions);

    // 監視対象の要素
    const animatedElements = document.querySelectorAll('.circle-wrapper, .info-list, .vision-item');
    animatedElements.forEach(element => {
        observer.observe(element);
    });

    // パララックス効果
    const heroSection = document.querySelector('.about-hero');
    if (heroSection) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallaxSpeed = 0.5;
            
            if (scrolled < window.innerHeight) {
                heroSection.style.transform = `translateY(${scrolled * parallaxSpeed}px)`;
            }
        });
    }

    // 値のカウントアップアニメーション
    const countUp = (element, start, end, duration) => {
        let current = start;
        const increment = (end - start) / (duration / 16);
        const timer = setInterval(() => {
            current += increment;
            if (current >= end) {
                current = end;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current).toLocaleString();
        }, 16);
    };

    // 資本金のカウントアップ
    const capitalElement = document.querySelector('[data-count="capital"]');
    if (capitalElement) {
        const observerCount = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    countUp(capitalElement, 0, 1000000, 1000);
                    observerCount.unobserve(entry.target);
                }
            });
        });
        observerCount.observe(capitalElement);
    }
});

// CSS アニメーション
const style = document.createElement('style');
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

    .info-item {
        opacity: 0;
    }

    .vision-item {
        transition: all 0.3s ease;
    }

    .vision-item:hover {
        transform: translateX(10px);
    }

    .circle {
        cursor: pointer;
        opacity: 0;
    }

    .value-item {
        cursor: pointer;
    }

    .illustration-wrapper svg {
        filter: drop-shadow(0 20px 40px rgba(0,0,0,0.1));
    }
`;
document.head.appendChild(style);