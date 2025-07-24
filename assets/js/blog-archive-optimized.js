/**
 * Blog Archive ページのJavaScript（最適化版）
 */
(function() {
    'use strict';

    // AnimationUtilsが読み込まれているか確認
    if (typeof AnimationUtils === 'undefined') {
        console.error('AnimationUtils is required for blog-archive-optimized.js');
        return;
    }

    const utils = AnimationUtils;
    const isMobile = utils.isMobile();
    const prefersReducedMotion = utils.prefersReducedMotion();

    /**
     * 初期化
     */
    document.addEventListener('DOMContentLoaded', function() {
        // モバイルまたはアニメーション削減設定時は軽量版を実行
        if (!isMobile && !prefersReducedMotion) {
            initHeroAnimations();
            initCardAnimations();
        }
        
        initSearchFunctionality();
        initFilterTabs();
        initLoadMore();
        initReadingProgress();
        
        if (!isMobile) {
            initStats();
        }
    });

    /**
     * ヒーローセクションのアニメーション（最適化版）
     */
    function initHeroAnimations() {
        const heroSection = document.querySelector('.blog-hero-modern');
        if (!heroSection) return;

        // グラデーションレイヤーとフローティングエレメントを取得
        const gradientLayers = heroSection.querySelectorAll('.gradient-layer');
        const floatingElements = heroSection.querySelectorAll('.floating-element');
        
        // GPUアクセラレーションを有効化
        [...gradientLayers, ...floatingElements].forEach(el => {
            utils.enableGPUAcceleration(el);
        });

        // スロットリングされたパララックス効果
        const updateParallax = utils.throttleScroll(() => {
            const scrolled = window.pageYOffset;
            const parallaxSpeed = 0.5;
            
            // 画面外の場合はスキップ
            const rect = heroSection.getBoundingClientRect();
            if (rect.bottom < 0 || rect.top > window.innerHeight) {
                return;
            }
            
            // グラデーションレイヤーのパララックス
            gradientLayers.forEach((layer, index) => {
                const speed = parallaxSpeed * (index + 1) * 0.3;
                layer.style.transform = `translate3d(0, ${scrolled * speed}px, 0)`;
            });
            
            // フローティングエレメントのパララックス
            floatingElements.forEach((element, index) => {
                const speed = parallaxSpeed * (index + 1) * 0.2;
                element.style.transform = `translate3d(0, ${scrolled * -speed}px, 0)`;
            });
        });
        
        window.addEventListener('scroll', updateParallax, { passive: true });
    }

    /**
     * 検索機能（最適化版）
     */
    function initSearchFunctionality() {
        const searchInput = document.querySelector('.search-input');
        const searchSuggestions = document.querySelector('.search-suggestions');
        const searchContainer = document.querySelector('.search-container');
        
        if (!searchInput || !searchSuggestions) return;
        
        // フォーカス時のサジェスト表示
        searchInput.addEventListener('focus', function() {
            if (this.value === '') {
                searchSuggestions.style.display = 'block';
                searchSuggestions.classList.add('fade-in');
            }
        });
        
        // 検索入力時の処理（デバウンス済み）
        const handleSearchInput = utils.debounce(function() {
            if (this.value.length > 2) {
                // Ajax検索の実装箇所
                searchSuggestions.style.display = 'none';
            } else if (this.value === '') {
                searchSuggestions.style.display = 'block';
            }
        }, 300);
        
        searchInput.addEventListener('input', handleSearchInput);
        
        // クリック外でサジェストを隠す
        document.addEventListener('click', function(e) {
            if (!searchContainer.contains(e.target)) {
                searchSuggestions.style.display = 'none';
            }
        });
        
        // タグクリック時の検索
        searchSuggestions.addEventListener('click', function(e) {
            if (e.target.classList.contains('suggestion-tag')) {
                searchInput.value = e.target.textContent;
                searchSuggestions.style.display = 'none';
                searchInput.form.submit();
            }
        });
    }

    /**
     * カードアニメーション（最適化版）
     */
    function initCardAnimations() {
        // Intersection Observerで効率的にアニメーション
        const observer = utils.createOptimizedObserver((entry) => {
            const card = entry.target;
            card.classList.add('visible');
            
            // アニメーション完了後にwill-changeをクリーンアップ
            card.addEventListener('animationend', () => {
                card.style.willChange = 'auto';
            }, { once: true });
            
            observer.unobserve(card);
        }, {
            threshold: 0.1,
            rootMargin: '50px'
        });
        
        document.querySelectorAll('.blog-article').forEach(card => {
            // 初期状態でGPUアクセラレーションを準備
            card.style.willChange = 'opacity, transform';
            observer.observe(card);
        });
    }

    /**
     * フィルタータブ機能
     */
    function initFilterTabs() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const blogGrid = document.querySelector('.blog-grid-modern');
        
        if (!filterButtons.length || !blogGrid) return;
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // アクティブ状態の切り替え
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const category = this.dataset.category;
                const articles = blogGrid.querySelectorAll('.blog-article');
                
                // フィルタリングアニメーション
                articles.forEach(article => {
                    const articleCategory = article.dataset.category;
                    
                    if (category === 'all' || articleCategory === category) {
                        article.style.display = 'block';
                        setTimeout(() => {
                            article.classList.add('visible');
                        }, 10);
                    } else {
                        article.classList.remove('visible');
                        setTimeout(() => {
                            article.style.display = 'none';
                        }, 300);
                    }
                });
            });
        });
    }

    /**
     * もっと読むボタン
     */
    function initLoadMore() {
        const loadMoreBtn = document.querySelector('.load-more-btn');
        if (!loadMoreBtn) return;
        
        loadMoreBtn.addEventListener('click', function() {
            this.classList.add('loading');
            
            // 実際のAjax読み込み処理をここに実装
            setTimeout(() => {
                this.classList.remove('loading');
                // 新しい記事を追加
            }, 1000);
        });
    }

    /**
     * 読了時間プログレスバー
     */
    function initReadingProgress() {
        const progressBar = document.querySelector('.reading-progress');
        if (!progressBar) return;
        
        const updateProgress = utils.throttleScroll(() => {
            const windowHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight;
            const scrolled = window.scrollY;
            const progress = (scrolled / (documentHeight - windowHeight)) * 100;
            
            progressBar.style.width = `${Math.min(progress, 100)}%`;
        });
        
        window.addEventListener('scroll', updateProgress, { passive: true });
    }

    /**
     * 統計カウンター（最適化版）
     */
    function initStats() {
        const observer = utils.createOptimizedObserver((entry) => {
            const statNumber = entry.target;
            if (!statNumber.dataset.counted) {
                statNumber.dataset.counted = 'true';
                
                const target = parseInt(statNumber.dataset.count, 10);
                const duration = 2000;
                const startTime = performance.now();
                
                const animate = (currentTime) => {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    
                    // イージング関数
                    const easeOutQuad = t => t * (2 - t);
                    const current = Math.floor(target * easeOutQuad(progress));
                    
                    statNumber.textContent = current.toLocaleString();
                    
                    if (progress < 1) {
                        requestAnimationFrame(animate);
                    } else {
                        statNumber.textContent = target.toLocaleString();
                    }
                };
                
                requestAnimationFrame(animate);
            }
        }, { threshold: 0.5 });
        
        document.querySelectorAll('.stat-number').forEach(stat => {
            observer.observe(stat);
        });
    }

    /**
     * リップルエフェクト（モバイルのみ）
     */
    if (isMobile) {
        document.addEventListener('click', function(e) {
            const article = e.target.closest('.blog-article');
            if (!article) return;
            
            const ripple = document.createElement('span');
            ripple.className = 'ripple';
            
            const rect = article.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            
            article.appendChild(ripple);
            
            ripple.addEventListener('animationend', () => {
                ripple.remove();
            });
        });
    }

})();