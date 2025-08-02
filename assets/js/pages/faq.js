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
                    answer.style.maxHeight = answer.scrollHeight + 'px';
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
    `;
    document.head.appendChild(style);

})();