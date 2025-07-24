/**
 * ブログアーカイブページ機能
 * Harmonic Society - Corporate SEO Pro Theme
 */

(function() {
    'use strict';

    // デバイス判定
    const isMobile = window.matchMedia('(max-width: 768px)').matches;
    const isTablet = window.matchMedia('(min-width: 769px) and (max-width: 1024px)').matches;
    const isTouchDevice = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0);

    // DOM Ready
    document.addEventListener('DOMContentLoaded', function() {
        // モバイル・タブレットではアニメーションを制限
        if (!isMobile && !isTouchDevice) {
            initHeroAnimations();
            initCountAnimation();
        }
        
        initSearchFunctionality();
        initFilterPanel();
        initSortingAndView();
        initNewsletterForm();
        initLazyLoading();
        
        // タブレット専用の初期化
        if (isTablet) {
            initTabletOptimizations();
        }
        
        // モバイル専用の初期化
        if (isMobile) {
            initMobileOptimizations();
        }
    });

    /**
     * ヒーローセクションのアニメーション
     */
    function initHeroAnimations() {
        // パララックス効果
        const heroSection = document.querySelector('.blog-hero-modern');
        if (heroSection) {
            window.addEventListener('scroll', () => {
                const scrolled = window.pageYOffset;
                const parallaxSpeed = 0.5;
                
                // グラデーションレイヤーのパララックス
                const gradientLayers = heroSection.querySelectorAll('.gradient-layer');
                gradientLayers.forEach((layer, index) => {
                    const speed = parallaxSpeed * (index + 1) * 0.3;
                    layer.style.transform = `translateY(${scrolled * speed}px)`;
                });
                
                // フローティングエレメントのパララックス
                const floatingElements = heroSection.querySelectorAll('.floating-element');
                floatingElements.forEach((element, index) => {
                    const speed = parallaxSpeed * (index + 1) * 0.2;
                    element.style.transform = `translateY(${scrolled * -speed}px)`;
                });
            });
        }
    }

    /**
     * 検索機能
     */
    function initSearchFunctionality() {
        const searchInput = document.querySelector('.search-input');
        const searchSuggestions = document.querySelector('.search-suggestions');
        const searchContainer = document.querySelector('.search-container');
        
        if (!searchInput || !searchSuggestions) return;
        
        // 検索入力フォーカス時にサジェスト表示
        searchInput.addEventListener('focus', function() {
            if (this.value === '') {
                searchSuggestions.style.display = 'block';
                animateIn(searchSuggestions);
            }
        });
        
        // 検索入力時の処理
        searchInput.addEventListener('input', debounce(function() {
            if (this.value.length > 2) {
                // ここでAjaxによる動的なサジェスト取得を実装可能
                searchSuggestions.style.display = 'none';
            } else if (this.value === '') {
                searchSuggestions.style.display = 'block';
            }
        }, 300));
        
        // クリック外でサジェストを隠す
        document.addEventListener('click', function(e) {
            if (!searchContainer.contains(e.target)) {
                searchSuggestions.style.display = 'none';
            }
        });
        
        // エンターキー押下時の処理
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && this.value.trim() === '') {
                e.preventDefault();
            }
        });
    }

    /**
     * フィルターパネル
     */
    function initFilterPanel() {
        const filterToggle = document.querySelector('.filter-toggle');
        const filterPanel = document.querySelector('.filter-panel');
        const applyButton = document.querySelector('.apply-filters');
        const clearButton = document.querySelector('.clear-filters');
        
        if (!filterToggle || !filterPanel) return;
        
        // フィルターパネルの開閉
        filterToggle.addEventListener('click', function() {
            const isOpen = filterPanel.style.display === 'grid';
            if (isOpen) {
                animateOut(filterPanel, () => {
                    filterPanel.style.display = 'none';
                });
            } else {
                filterPanel.style.display = 'grid';
                animateIn(filterPanel);
            }
        });
        
        // フィルター適用
        if (applyButton) {
            applyButton.addEventListener('click', function() {
                const selectedCategories = [];
                const categoryCheckboxes = document.querySelectorAll('.filter-items input[name="category"]:checked');
                categoryCheckboxes.forEach(checkbox => {
                    selectedCategories.push(checkbox.value);
                });
                
                const selectedPeriod = document.querySelector('.filter-items input[name="period"]:checked');
                
                // URLパラメータを構築して再読み込み
                const params = new URLSearchParams(window.location.search);
                
                if (selectedCategories.length > 0) {
                    params.set('categories', selectedCategories.join(','));
                } else {
                    params.delete('categories');
                }
                
                if (selectedPeriod && selectedPeriod.value !== 'all') {
                    params.set('period', selectedPeriod.value);
                } else {
                    params.delete('period');
                }
                
                window.location.search = params.toString();
            });
        }
        
        // フィルタークリア
        if (clearButton) {
            clearButton.addEventListener('click', function() {
                document.querySelectorAll('.filter-items input').forEach(input => {
                    if (input.type === 'checkbox') {
                        input.checked = false;
                    } else if (input.type === 'radio' && input.value === 'all') {
                        input.checked = true;
                    }
                });
            });
        }
    }

    /**
     * ソートとビュー切り替え
     */
    function initSortingAndView() {
        const sortSelect = document.getElementById('sortPosts');
        const viewOptions = document.querySelectorAll('.view-option');
        const blogGrid = document.getElementById('blogGrid');
        
        // ソート機能
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                const sortValue = this.value;
                const params = new URLSearchParams(window.location.search);
                params.set('orderby', sortValue);
                window.location.search = params.toString();
            });
        }
        
        // ビュー切り替え
        viewOptions.forEach(option => {
            option.addEventListener('click', function() {
                const view = this.dataset.view;
                
                // アクティブ状態の切り替え
                viewOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                
                // グリッドクラスの切り替え
                if (view === 'list') {
                    blogGrid.classList.add('list-view');
                } else {
                    blogGrid.classList.remove('list-view');
                }
                
                // ローカルストレージに保存
                localStorage.setItem('blogViewPreference', view);
            });
        });
        
        // 保存されたビュー設定を復元
        const savedView = localStorage.getItem('blogViewPreference');
        if (savedView === 'list') {
            document.querySelector('[data-view="list"]').click();
        }
    }

    /**
     * カウントアップアニメーション
     */
    function initCountAnimation() {
        const statNumbers = document.querySelectorAll('.stat-number');
        
        if (statNumbers.length === 0) return;
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const element = entry.target;
                    const target = parseInt(element.dataset.count);
                    animateCount(element, target);
                    observer.unobserve(element);
                }
            });
        }, { threshold: 0.5 });
        
        statNumbers.forEach(number => {
            observer.observe(number);
        });
    }
    
    function animateCount(element, target) {
        const duration = 2000;
        const increment = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current);
        }, 16);
    }

    /**
     * ニュースレターフォーム
     */
    function initNewsletterForm() {
        const newsletterForm = document.querySelector('.newsletter-form');
        
        if (!newsletterForm) return;
        
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const emailInput = this.querySelector('input[type="email"]');
            const button = this.querySelector('button');
            const originalButtonContent = button.innerHTML;
            
            // ローディング状態
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> 送信中...';
            button.disabled = true;
            
            // ここで実際のニュースレター登録処理を実装
            // 今回はデモとして2秒後に成功表示
            setTimeout(() => {
                button.innerHTML = '<i class="fas fa-check"></i> 登録完了！';
                button.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                emailInput.value = '';
                
                // 3秒後に元に戻す
                setTimeout(() => {
                    button.innerHTML = originalButtonContent;
                    button.disabled = false;
                    button.style.background = '';
                }, 3000);
            }, 2000);
        });
    }

    /**
     * 遅延読み込み
     */
    function initLazyLoading() {
        const images = document.querySelectorAll('.article-thumbnail img');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.classList.add('loaded');
                        imageObserver.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px'
            });
            
            images.forEach(img => imageObserver.observe(img));
        }
    }

    /**
     * ユーティリティ関数
     */
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    function animateIn(element) {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'all 0.3s ease';
        
        setTimeout(() => {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, 10);
    }
    
    function animateOut(element, callback) {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            if (callback) callback();
        }, 300);
    }

    /**
     * Ajax読み込み（将来の実装用）
     */
    function loadMorePosts() {
        // 無限スクロールやもっと見るボタンの実装用
        // WordPressのREST APIを使用して追加の記事を取得
    }

    /**
     * 検索の自動補完（将来の実装用）
     */
    function initAutocomplete() {
        // 検索入力時の自動補完機能
        // WordPressのREST APIを使用してリアルタイムで候補を表示
    }
    
    /**
     * タブレット最適化
     */
    function initTabletOptimizations() {
        // タッチイベントの最適化
        document.querySelectorAll('.blog-article').forEach(article => {
            article.addEventListener('touchstart', function() {
                this.classList.add('touch-active');
            });
            
            article.addEventListener('touchend', function() {
                setTimeout(() => {
                    this.classList.remove('touch-active');
                }, 300);
            });
        });
        
        // グリッドレイアウトの調整
        const blogGrid = document.getElementById('blogGrid');
        if (blogGrid) {
            // ビューポートの向きに応じてグリッドを調整
            function adjustGridForOrientation() {
                if (window.innerWidth > window.innerHeight) {
                    // 横向き
                    blogGrid.style.gridTemplateColumns = 'repeat(3, 1fr)';
                } else {
                    // 縦向き
                    blogGrid.style.gridTemplateColumns = 'repeat(2, 1fr)';
                }
            }
            
            adjustGridForOrientation();
            window.addEventListener('orientationchange', adjustGridForOrientation);
        }
    }
    
    /**
     * モバイル最適化
     */
    function initMobileOptimizations() {
        // スクロールパフォーマンスの向上
        let ticking = false;
        function updateScrolling() {
            ticking = false;
        }
        
        document.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(updateScrolling);
                ticking = true;
            }
        }, { passive: true });
        
        // タッチイベントの最適化
        document.addEventListener('touchstart', () => {}, { passive: true });
        
        // 画像の遅延読み込みを強化
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                        }
                        imageObserver.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });
            
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
        
        // ビューポートの高さ修正（iOS対策）
        function setViewportHeight() {
            const vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        }
        
        setViewportHeight();
        window.addEventListener('resize', setViewportHeight);
        window.addEventListener('orientationchange', setViewportHeight);
    }

})();