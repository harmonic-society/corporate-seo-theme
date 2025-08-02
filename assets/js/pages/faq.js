/**
 * FAQ Page Functionality
 * よくある質問ページの機能
 */
(function() {
    'use strict';

    // DOM要素の取得
    let searchInput;
    let categoryTabs;
    let faqItems;
    let faqQuestions;
    let faqCategories;

    // 初期化
    document.addEventListener('DOMContentLoaded', function() {
        initElements();
        
        if (!searchInput || !categoryTabs.length || !faqItems.length) {
            console.warn('FAQ: Required elements not found');
            return;
        }
        
        // イベントリスナーの設定
        setupSearchFunctionality();
        setupCategoryTabs();
        setupAccordion();
        
        // 初期表示
        showAllCategories();
        
        // URLパラメータからカテゴリーを取得
        handleUrlParameters();
        
        // モバイル向けの改善
        setupMobileEnhancements();
    });

    /**
     * DOM要素の初期化
     */
    function initElements() {
        searchInput = document.querySelector('.faq-search-input');
        categoryTabs = document.querySelectorAll('.category-tab');
        faqItems = document.querySelectorAll('.faq-item');
        faqQuestions = document.querySelectorAll('.faq-question');
        faqCategories = document.querySelectorAll('.faq-category');
    }

    /**
     * 検索機能の設定
     */
    function setupSearchFunctionality() {
        const searchButton = document.querySelector('.faq-search-button');
        
        // 検索入力のイベント
        searchInput.addEventListener('input', debounce(performSearch, 300));
        
        // 検索ボタンのクリック
        if (searchButton) {
            searchButton.addEventListener('click', performSearch);
        }
        
        // Enterキーでの検索
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSearch();
            }
        });
    }

    /**
     * 検索の実行
     */
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        
        if (!searchTerm) {
            // 検索語がない場合は全て表示
            showAllCategories();
            clearHighlights();
            return;
        }
        
        // すべてのカテゴリーを表示
        showAllCategories();
        
        // 各FAQ項目を検索
        let hasResults = false;
        
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question .question-text').textContent.toLowerCase();
            const answer = item.querySelector('.faq-answer').textContent.toLowerCase();
            
            if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                item.style.display = 'block';
                highlightSearchTerm(item, searchTerm);
                hasResults = true;
            } else {
                item.style.display = 'none';
            }
        });
        
        // カテゴリーの表示/非表示
        faqCategories.forEach(category => {
            const visibleItems = category.querySelectorAll('.faq-item[style="display: block;"]');
            if (visibleItems.length === 0) {
                category.style.display = 'none';
            } else {
                category.style.display = 'block';
            }
        });
        
        // 結果がない場合
        if (!hasResults) {
            showNoResults();
        } else {
            hideNoResults();
        }
    }

    /**
     * 検索語のハイライト
     */
    function highlightSearchTerm(item, searchTerm) {
        clearHighlights();
        
        const elementsToHighlight = [
            item.querySelector('.faq-question .question-text'),
            item.querySelector('.faq-answer')
        ];
        
        elementsToHighlight.forEach(element => {
            if (element) {
                const text = element.innerHTML;
                const regex = new RegExp(`(${escapeRegExp(searchTerm)})`, 'gi');
                element.innerHTML = text.replace(regex, '<span class="search-highlight">$1</span>');
            }
        });
        
        item.classList.add('highlighted');
    }

    /**
     * ハイライトのクリア
     */
    function clearHighlights() {
        document.querySelectorAll('.search-highlight').forEach(highlight => {
            const parent = highlight.parentNode;
            parent.replaceChild(document.createTextNode(highlight.textContent), highlight);
            parent.normalize();
        });
        
        document.querySelectorAll('.faq-item').forEach(item => {
            item.classList.remove('highlighted');
        });
    }

    /**
     * カテゴリータブの設定
     */
    function setupCategoryTabs() {
        categoryTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const category = this.getAttribute('data-category');
                
                // アクティブタブの更新
                categoryTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // カテゴリーの表示切替
                if (category === 'all') {
                    showAllCategories();
                } else {
                    filterByCategory(category);
                }
                
                // 検索をクリア
                searchInput.value = '';
                clearHighlights();
                
                // URLを更新
                updateUrl(category);
            });
        });
    }

    /**
     * カテゴリーでフィルタリング
     */
    function filterByCategory(category) {
        faqCategories.forEach(cat => {
            if (cat.getAttribute('data-category') === category) {
                cat.style.display = 'block';
                cat.classList.add('active');
                // アニメーションをリトリガー
                cat.style.animation = 'none';
                setTimeout(() => {
                    cat.style.animation = 'fadeInUp 0.5s ease forwards';
                }, 10);
            } else {
                cat.style.display = 'none';
                cat.classList.remove('active');
            }
        });
        
        // すべてのFAQ項目を表示
        faqItems.forEach(item => {
            item.style.display = 'block';
        });
    }

    /**
     * すべてのカテゴリーを表示
     */
    function showAllCategories() {
        faqCategories.forEach((cat, index) => {
            cat.style.display = 'block';
            cat.classList.add('active');
            // 段階的なアニメーション
            cat.style.animation = 'none';
            setTimeout(() => {
                cat.style.animation = `fadeInUp 0.5s ease ${index * 0.1}s forwards`;
            }, 10);
        });
        
        faqItems.forEach(item => {
            item.style.display = 'block';
        });
    }

    /**
     * アコーディオンの設定
     */
    function setupAccordion() {
        faqQuestions.forEach(question => {
            question.addEventListener('click', function() {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                const answer = this.nextElementSibling;
                
                // 他のすべてのFAQを閉じる（オプション）
                // closeAllAccordions();
                
                // 現在のFAQの開閉
                if (isExpanded) {
                    this.setAttribute('aria-expanded', 'false');
                    answer.style.maxHeight = '0';
                } else {
                    this.setAttribute('aria-expanded', 'true');
                    // コンテンツの実際の高さを計算して設定
                    answer.style.maxHeight = 'none';
                    const scrollHeight = answer.scrollHeight;
                    answer.style.maxHeight = '0';
                    
                    // 次のフレームで高さを設定（アニメーションのため）
                    requestAnimationFrame(() => {
                        answer.style.maxHeight = scrollHeight + 'px';
                    });
                }
            });
        });
    }

    /**
     * すべてのアコーディオンを閉じる
     */
    function closeAllAccordions() {
        faqQuestions.forEach(question => {
            question.setAttribute('aria-expanded', 'false');
            const answer = question.nextElementSibling;
            answer.style.maxHeight = '0';
        });
    }

    /**
     * URLパラメータの処理
     */
    function handleUrlParameters() {
        const params = new URLSearchParams(window.location.search);
        const category = params.get('category');
        const search = params.get('search');
        
        if (category) {
            const tab = document.querySelector(`[data-category="${category}"]`);
            if (tab) {
                tab.click();
            }
        }
        
        if (search) {
            searchInput.value = search;
            performSearch();
        }
    }

    /**
     * URLの更新
     */
    function updateUrl(category) {
        const url = new URL(window.location);
        if (category === 'all') {
            url.searchParams.delete('category');
        } else {
            url.searchParams.set('category', category);
        }
        window.history.pushState({}, '', url);
    }

    /**
     * 検索結果なしの表示
     */
    function showNoResults() {
        hideNoResults();
        
        const noResultsDiv = document.createElement('div');
        noResultsDiv.className = 'no-results';
        noResultsDiv.innerHTML = `
            <i class="fas fa-search"></i>
            <p>「${searchInput.value}」に関する質問は見つかりませんでした。</p>
            <p>別のキーワードでお試しください。</p>
        `;
        
        const faqWrapper = document.querySelector('.faq-wrapper');
        faqWrapper.appendChild(noResultsDiv);
    }

    /**
     * 検索結果なし表示を削除
     */
    function hideNoResults() {
        const noResults = document.querySelector('.no-results');
        if (noResults) {
            noResults.remove();
        }
    }

    /**
     * デバウンス関数
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

    /**
     * 正規表現エスケープ
     */
    function escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    /**
     * モバイル向けの改善
     */
    function setupMobileEnhancements() {
        // モバイルデバイスの検出
        const isMobile = window.innerWidth <= 768;
        
        if (isMobile) {
            // カテゴリータブのスクロール位置を中央に
            const activeTab = document.querySelector('.category-tab.active');
            const tabsContainer = document.querySelector('.category-tabs');
            
            if (activeTab && tabsContainer) {
                const scrollLeft = activeTab.offsetLeft - (tabsContainer.offsetWidth - activeTab.offsetWidth) / 2;
                tabsContainer.scrollLeft = scrollLeft;
            }
            
            // スワイプヒントの追加
            const categoriesSection = document.querySelector('.faq-categories');
            if (categoriesSection && tabsContainer.scrollWidth > tabsContainer.offsetWidth) {
                categoriesSection.classList.add('has-scroll');
                
                // スクロール状態の監視
                tabsContainer.addEventListener('scroll', function() {
                    const isAtStart = this.scrollLeft <= 10;
                    const isAtEnd = this.scrollLeft >= this.scrollWidth - this.offsetWidth - 10;
                    
                    categoriesSection.classList.toggle('at-start', isAtStart);
                    categoriesSection.classList.toggle('at-end', isAtEnd);
                });
                
                // 初期状態のチェック
                tabsContainer.dispatchEvent(new Event('scroll'));
            }
            
            // タッチイベントの最適化
            let touchStartX = 0;
            let scrollStartX = 0;
            
            tabsContainer.addEventListener('touchstart', function(e) {
                touchStartX = e.touches[0].clientX;
                scrollStartX = this.scrollLeft;
            }, { passive: true });
            
            tabsContainer.addEventListener('touchmove', function(e) {
                const touchDeltaX = touchStartX - e.touches[0].clientX;
                this.scrollLeft = scrollStartX + touchDeltaX;
            }, { passive: true });
            
            // クリックによる自動スクロール
            categoryTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // タブをビューポートの中央に配置
                    const scrollLeft = this.offsetLeft - (tabsContainer.offsetWidth - this.offsetWidth) / 2;
                    tabsContainer.scrollTo({
                        left: scrollLeft,
                        behavior: 'smooth'
                    });
                });
            });
        }
        
        // ビューポートの高さ調整（モバイルブラウザ対応）
        function setViewportHeight() {
            const vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        }
        
        setViewportHeight();
        window.addEventListener('resize', setViewportHeight);
        window.addEventListener('orientationchange', setViewportHeight);
    }

    /**
     * FAQ構造化データの生成（SEO対策）
     */
    function generateStructuredData() {
        const faqData = {
            "@context": "https://schema.org",
            "@type": "FAQPage",
            "mainEntity": []
        };
        
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question .question-text').textContent;
            const answer = item.querySelector('.faq-answer').textContent.trim();
            
            faqData.mainEntity.push({
                "@type": "Question",
                "name": question,
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": answer
                }
            });
        });
        
        // 構造化データをページに追加
        const script = document.createElement('script');
        script.type = 'application/ld+json';
        script.textContent = JSON.stringify(faqData);
        document.head.appendChild(script);
    }

    // 構造化データの生成
    generateStructuredData();

    // スタイルの追加
    const style = document.createElement('style');
    style.textContent = `
        /* No Results Style */
        .no-results {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-light);
        }
        
        .no-results i {
            font-size: 3rem;
            color: #e5e7eb;
            margin-bottom: 1rem;
            display: block;
        }
        
        .no-results p {
            margin: 0.5rem 0;
        }
        
        /* Smooth Height Transition */
        .faq-answer {
            transition: max-height 0.3s ease, padding 0.3s ease, opacity 0.3s ease;
            opacity: 0;
        }
        
        .faq-question[aria-expanded="true"] + .faq-answer {
            opacity: 1;
        }
        
        /* Mobile Scroll Indicators */
        @media (max-width: 768px) {
            .faq-categories {
                position: relative;
                overflow: visible;
            }
            
            .faq-categories::before,
            .faq-categories::after {
                content: '';
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                width: 24px;
                height: 24px;
                border-radius: 50%;
                background: var(--primary-color);
                color: white;
                display: none;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                z-index: 10;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            }
            
            .faq-categories.has-scroll:not(.at-start)::before {
                content: '‹';
                left: 0;
                display: flex;
            }
            
            .faq-categories.has-scroll:not(.at-end)::after {
                content: '›';
                right: 0;
                display: flex;
            }
            
            /* Smooth scroll behavior for mobile */
            .category-tabs {
                scroll-behavior: smooth;
                -webkit-overflow-scrolling: touch;
            }
            
            /* Active tab highlight on mobile */
            .category-tab.active {
                box-shadow: 0 2px 8px rgba(0, 134, 123, 0.3);
            }
            
            /* Improve touch targets */
            .faq-question {
                min-height: 48px;
                cursor: pointer;
                user-select: none;
                -webkit-tap-highlight-color: transparent;
            }
            
            /* Prevent text selection on FAQ toggle */
            .faq-question:active {
                background-color: rgba(0, 134, 123, 0.05);
            }
            
            /* Container padding adjustments */
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            /* Fix viewport height for mobile browsers */
            .faq-hero {
                min-height: calc(var(--vh, 1vh) * 100);
            }
        }
    `;
    document.head.appendChild(style);

})();