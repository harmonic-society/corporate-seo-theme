/**
 * ヒーローセクションのアニメーション（モバイル最適化版）
 */
(function() {
    'use strict';

    // AnimationUtilsが読み込まれているか確認
    if (typeof AnimationUtils === 'undefined') {
        console.error('AnimationUtils is required for hero-animations-optimized.js');
        return;
    }

    const utils = AnimationUtils;
    const isMobile = utils.isMobile();
    const prefersReducedMotion = utils.prefersReducedMotion();

    // DOM要素の取得
    const heroSection = document.querySelector('.hero-section');
    const heroContent = document.querySelector('.hero-content');
    const particles = document.querySelectorAll('.particle');
    
    if (!heroSection) return;

    // モバイルまたはアニメーション削減設定時は重いアニメーションを無効化
    if (isMobile || prefersReducedMotion) {
        // パーティクルを非表示
        particles.forEach(particle => {
            particle.style.display = 'none';
        });
        
        // 接続線アニメーションを無効化
        const connectionLines = document.querySelector('.connection-lines');
        if (connectionLines) {
            connectionLines.style.display = 'none';
        }
        
        return; // 重いアニメーションは実行しない
    }

    // ==========================================================================
    // デスクトップ向けアニメーション
    // ==========================================================================

    // パララックス効果（スロットリング済み）
    if (heroContent) {
        // GPUアクセラレーションを有効化
        utils.enableGPUAcceleration(heroContent);
        
        const updateParallax = utils.throttleScroll(() => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            
            // 画面外の場合はアニメーションをスキップ
            const rect = heroSection.getBoundingClientRect();
            if (rect.bottom < 0 || rect.top > window.innerHeight) {
                return;
            }
            
            heroContent.style.transform = `translate3d(0, ${rate}px, 0)`;
            heroContent.style.opacity = Math.max(0, 1 - (scrolled / 800));
            
            // パーティクルのパララックス
            particles.forEach((particle, index) => {
                const speed = 0.2 + (index * 0.1);
                particle.style.transform = `translate3d(0, ${scrolled * speed}px, 0)`;
            });
        });
        
        window.addEventListener('scroll', updateParallax, { passive: true });
    }
    
    // マウス追従エフェクト（デスクトップのみ）
    if (!utils.isTouchDevice()) {
        let mouseX = 0;
        let mouseY = 0;
        let currentX = 0;
        let currentY = 0;
        const speed = 0.05;
        
        // パーティクルにGPUアクセラレーションを追加
        particles.forEach(particle => {
            utils.enableGPUAcceleration(particle);
        });
        
        const animationLoop = utils.createAnimationLoop(() => {
            currentX += (mouseX - currentX) * speed;
            currentY += (mouseY - currentY) * speed;
            
            particles.forEach((particle, index) => {
                const moveX = currentX * (20 + index * 10);
                const moveY = currentY * (20 + index * 10);
                particle.style.transform = `translate3d(${moveX}px, ${moveY}px, 0)`;
            });
        });
        
        heroSection.addEventListener('mousemove', utils.debounce((e) => {
            const rect = heroSection.getBoundingClientRect();
            mouseX = (e.clientX - rect.left - rect.width / 2) / rect.width;
            mouseY = (e.clientY - rect.top - rect.height / 2) / rect.height;
        }, 16), { passive: true });
        
        // Intersection Observerで画面内にある時のみアニメーション実行
        const observer = utils.createOptimizedObserver((entry) => {
            if (entry.isIntersecting) {
                animationLoop.start();
            } else {
                animationLoop.stop();
            }
        });
        
        observer.observe(heroSection);
        
        // ページ離脱時のクリーンアップ
        window.addEventListener('beforeunload', () => {
            animationLoop.stop();
            observer.disconnect();
        });
    }
    
    // タイピングエフェクト（CSSアニメーションで置き換え推奨）
    const heroTitle = document.querySelector('.hero-title');
    if (heroTitle && heroTitle.dataset.typewriter === 'true') {
        // CSSクラスを追加してCSSアニメーションで処理
        heroTitle.classList.add('typewriter-animation');
    }
    
    // 接続線アニメーション（最適化版）
    function initConnectionLines() {
        const svg = document.querySelector('.connection-lines');
        if (!svg) return;

        const width = 1920;
        const height = 1080;
        const nodes = [];
        const numNodes = 6;
        
        // ノードの初期化
        for (let i = 0; i < numNodes; i++) {
            nodes.push({
                x: Math.random() * width,
                y: Math.random() * height,
                vx: (Math.random() - 0.5) * 0.5,
                vy: (Math.random() - 0.5) * 0.5
            });
        }
        
        // SVGの初期設定（一度だけ）
        let svgContent = '';
        for (let i = 0; i < nodes.length; i++) {
            for (let j = i + 1; j < nodes.length; j++) {
                svgContent += `<line class="connection-line" x1="0" y1="0" x2="0" y2="0" stroke="rgba(22, 160, 133, 0.1)" stroke-width="1"/>`;
            }
        }
        svg.innerHTML = svgContent;
        
        const lines = svg.querySelectorAll('.connection-line');
        let lineIndex = 0;
        
        // アニメーションループ
        const animationLoop = utils.createAnimationLoop(() => {
            // ノードの更新
            nodes.forEach(node => {
                node.x += node.vx;
                node.y += node.vy;
                
                if (node.x < 0 || node.x > width) node.vx *= -1;
                if (node.y < 0 || node.y > height) node.vy *= -1;
                
                node.x = Math.max(0, Math.min(width, node.x));
                node.y = Math.max(0, Math.min(height, node.y));
            });
            
            // ラインの更新（DOM操作を最小限に）
            lineIndex = 0;
            for (let i = 0; i < nodes.length; i++) {
                for (let j = i + 1; j < nodes.length; j++) {
                    const line = lines[lineIndex];
                    if (line) {
                        line.setAttribute('x1', nodes[i].x);
                        line.setAttribute('y1', nodes[i].y);
                        line.setAttribute('x2', nodes[j].x);
                        line.setAttribute('y2', nodes[j].y);
                        
                        const distance = Math.sqrt(
                            Math.pow(nodes[i].x - nodes[j].x, 2) + 
                            Math.pow(nodes[i].y - nodes[j].y, 2)
                        );
                        const opacity = Math.max(0, 1 - distance / 500) * 0.2;
                        line.style.opacity = opacity;
                    }
                    lineIndex++;
                }
            }
        });
        
        // Intersection Observerで画面内にある時のみアニメーション実行
        const observer = utils.createOptimizedObserver((entry) => {
            if (entry.isIntersecting) {
                animationLoop.start();
            } else {
                animationLoop.stop();
            }
        });
        
        observer.observe(svg);
    }
    
    // カウントアップアニメーション（Intersection Observer使用）
    const stats = document.querySelectorAll('.stat-number');
    if (stats.length > 0) {
        const observer = utils.createOptimizedObserver((entry) => {
            const stat = entry.target;
            if (!stat.dataset.counted) {
                stat.dataset.counted = 'true';
                const target = parseInt(stat.dataset.target, 10);
                const duration = 2000;
                const increment = target / (duration / 16);
                let current = 0;
                
                const countAnimation = () => {
                    current += increment;
                    if (current < target) {
                        stat.textContent = Math.floor(current);
                        requestAnimationFrame(countAnimation);
                    } else {
                        stat.textContent = target;
                    }
                };
                
                requestAnimationFrame(countAnimation);
            }
        }, { threshold: 0.5 });
        
        stats.forEach(stat => observer.observe(stat));
    }
    
    // 初期化
    if (!isMobile && !prefersReducedMotion) {
        initConnectionLines();
    }
    
    // パフォーマンスモニタリング（開発時のみ）
    if (window.location.hostname === 'localhost') {
        const measurePerformance = () => {
            if (performance.memory) {
                console.log('Memory usage:', {
                    used: (performance.memory.usedJSHeapSize / 1048576).toFixed(2) + ' MB',
                    total: (performance.memory.totalJSHeapSize / 1048576).toFixed(2) + ' MB'
                });
            }
        };
        
        setInterval(measurePerformance, 5000);
    }

})();