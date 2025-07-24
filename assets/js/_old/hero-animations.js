/**
 * ヒーローセクションのアニメーション
 */
(function() {
    'use strict';

    // パララックス効果
    const heroSection = document.querySelector('.hero-section');
    const heroContent = document.querySelector('.hero-content');
    const particles = document.querySelectorAll('.particle');
    
    if (heroSection) {
        // スクロールパララックス
        let ticking = false;
        function updateParallax() {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            
            if (heroContent) {
                heroContent.style.transform = `translateY(${rate}px)`;
                heroContent.style.opacity = 1 - (scrolled / 800);
            }
            
            // パーティクルのパララックス
            particles.forEach((particle, index) => {
                const speed = 0.2 + (index * 0.1);
                particle.style.transform = `translateY(${scrolled * speed}px)`;
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
    
    // マウス追従エフェクト
    if (heroSection) {
        let mouseX = 0;
        let mouseY = 0;
        let currentX = 0;
        let currentY = 0;
        const speed = 0.05;
        
        heroSection.addEventListener('mousemove', (e) => {
            const rect = heroSection.getBoundingClientRect();
            mouseX = (e.clientX - rect.left - rect.width / 2) / rect.width;
            mouseY = (e.clientY - rect.top - rect.height / 2) / rect.height;
        });
        
        function animateParticles() {
            currentX += (mouseX - currentX) * speed;
            currentY += (mouseY - currentY) * speed;
            
            particles.forEach((particle, index) => {
                const moveX = currentX * (20 + index * 10);
                const moveY = currentY * (20 + index * 10);
                particle.style.transform = `translate(${moveX}px, ${moveY}px)`;
            });
            
            requestAnimationFrame(animateParticles);
        }
        
        animateParticles();
    }
    
    // タイピングエフェクト（オプション）
    const heroTitle = document.querySelector('.hero-title');
    if (heroTitle && heroTitle.dataset.typewriter === 'true') {
        const text = heroTitle.textContent;
        heroTitle.textContent = '';
        heroTitle.style.opacity = '1';
        
        let charIndex = 0;
        function typeWriter() {
            if (charIndex < text.length) {
                heroTitle.textContent += text.charAt(charIndex);
                charIndex++;
                setTimeout(typeWriter, 50);
            }
        }
        
        setTimeout(typeWriter, 500);
    }
    
    // 接続線アニメーション
    function initConnectionLines() {
        const svg = document.querySelector('.connection-lines');
        if (!svg) return;

        const width = 1920;
        const height = 1080;
        const nodes = [];
        const numNodes = 6;

        // ノードの生成
        for (let i = 0; i < numNodes; i++) {
            nodes.push({
                x: Math.random() * width,
                y: Math.random() * height,
                vx: (Math.random() - 0.5) * 0.3,
                vy: (Math.random() - 0.5) * 0.3
            });
        }

        // 線を描画
        function drawLines() {
            let paths = '';
            
            nodes.forEach((node, i) => {
                // ノードを移動
                node.x += node.vx;
                node.y += node.vy;

                // 画面端で反射
                if (node.x < 0 || node.x > width) node.vx *= -1;
                if (node.y < 0 || node.y > height) node.vy *= -1;

                // 近いノードと接続
                nodes.forEach((otherNode, j) => {
                    if (i < j) {
                        const distance = Math.sqrt(
                            Math.pow(node.x - otherNode.x, 2) + 
                            Math.pow(node.y - otherNode.y, 2)
                        );
                        
                        if (distance < 400) {
                            const opacity = 1 - (distance / 400);
                            paths += `<line x1="${node.x}" y1="${node.y}" 
                                          x2="${otherNode.x}" y2="${otherNode.y}" 
                                          stroke="url(#lineGradient)" 
                                          stroke-width="1" 
                                          opacity="${opacity * 0.3}"/>`;
                        }
                    }
                });

                // ノードを円として描画
                paths += `<circle cx="${node.x}" cy="${node.y}" r="2" 
                                fill="rgba(255,255,255,0.5)" 
                                opacity="0.6"/>`;
            });

            svg.innerHTML = `
                <defs>
                    <linearGradient id="lineGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#00c9a7;stop-opacity:0.5" />
                        <stop offset="100%" style="stop-color:#00867b;stop-opacity:0.2" />
                    </linearGradient>
                </defs>
                ${paths}
            `;

            requestAnimationFrame(drawLines);
        }

        drawLines();
    }

    // 初期化時に接続線アニメーションを開始
    initConnectionLines();
    
    // カウントアップアニメーション
    const countElements = document.querySelectorAll('.count-up');
    if (countElements.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const element = entry.target;
                    const target = parseInt(element.dataset.target);
                    const duration = parseInt(element.dataset.duration) || 2000;
                    const start = 0;
                    const increment = target / (duration / 16);
                    let current = start;
                    
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            current = target;
                            clearInterval(timer);
                        }
                        element.textContent = Math.floor(current).toLocaleString();
                    }, 16);
                    
                    observer.unobserve(element);
                }
            });
        }, { threshold: 0.5 });
        
        countElements.forEach(element => {
            observer.observe(element);
        });
    }
    
    // ボタンリップルエフェクト
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
    
})();