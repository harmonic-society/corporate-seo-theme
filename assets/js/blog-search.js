/**
 * ブログ検索機能JavaScript
 * Corporate SEO Pro Theme
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        // 要素の取得
        const searchForm = document.querySelector('.blog-search-form');
        const searchInput = document.querySelector('.search-input');
        const categoryFilters = document.querySelectorAll('.category-filter input');
        const viewOptions = document.querySelectorAll('.view-option');
        const sortSelect = document.querySelector('.sort-select');
        const suggestionsContainer = document.querySelector('.search-suggestions');
        const suggestionsList = document.querySelector('.suggestions-list');
        const newsSection = document.querySelector('.news-section');

        if (!searchForm) {
            console.log('Search form not found');
            return;
        }
        
        if (!searchInput) {
            console.log('Search input not found');
            return;
        }
        
        // 検索入力フィールドの基本的な動作確認
        console.log('Search input element:', searchInput);
        console.log('Search input disabled:', searchInput.disabled);
        console.log('Search input readonly:', searchInput.readOnly);

        // 検索候補の管理
        let searchTimeout;
        let currentSuggestions = [];

        // 検索候補の表示
        function showSuggestions(query) {
            if (query.length < 2) {
                hideSuggestions();
                return;
            }

            // ローディング表示
            suggestionsList.innerHTML = '<div class="suggestion-item"><i class="fas fa-spinner fa-spin suggestion-icon"></i><span class="suggestion-text">検索中...</span></div>';
            suggestionsContainer.style.display = 'block';

            // Ajax検索（WordPressのREST APIを使用）
            fetch(`${wp_vars.rest_url}wp/v2/posts?search=${encodeURIComponent(query)}&per_page=5`)
                .then(response => response.json())
                .then(posts => {
                    if (posts.length === 0) {
                        suggestionsList.innerHTML = '<div class="suggestion-item"><i class="fas fa-info-circle suggestion-icon"></i><span class="suggestion-text">検索結果が見つかりませんでした</span></div>';
                        return;
                    }

                    currentSuggestions = posts;
                    suggestionsList.innerHTML = posts.map((post, index) => {
                        const title = highlightMatch(post.title.rendered, query);
                        return `
                            <div class="suggestion-item" data-index="${index}">
                                <i class="fas fa-file-alt suggestion-icon"></i>
                                <span class="suggestion-text">${title}</span>
                            </div>
                        `;
                    }).join('');

                    // 候補アイテムのクリックイベント
                    document.querySelectorAll('.suggestion-item').forEach(item => {
                        item.addEventListener('click', function() {
                            const index = this.dataset.index;
                            if (currentSuggestions[index]) {
                                window.location.href = currentSuggestions[index].link;
                            }
                        });
                    });
                })
                .catch(error => {
                    console.error('Search error:', error);
                    suggestionsList.innerHTML = '<div class="suggestion-item"><i class="fas fa-exclamation-circle suggestion-icon"></i><span class="suggestion-text">エラーが発生しました</span></div>';
                });
        }

        // 検索候補を隠す
        function hideSuggestions() {
            suggestionsContainer.style.display = 'none';
            currentSuggestions = [];
        }

        // マッチ部分をハイライト
        function highlightMatch(text, query) {
            const regex = new RegExp(`(${escapeRegExp(query)})`, 'gi');
            return text.replace(regex, '<strong>$1</strong>');
        }

        // 正規表現エスケープ
        function escapeRegExp(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }

        // 検索入力のフォーカス処理
        searchInput.addEventListener('focus', function(e) {
            // 入力を確実にする
            this.removeAttribute('readonly');
            this.removeAttribute('disabled');
            
            // iOSでの入力問題を回避
            if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
                this.style.fontSize = '16px';
            }
            
            console.log('Search input focused');
        });
        
        // 入力フィールドのクリックイベント
        searchInput.addEventListener('click', function(e) {
            e.stopPropagation();
            this.focus();
        });
        
        // 検索入力イベント
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            searchTimeout = setTimeout(() => {
                showSuggestions(query);
            }, 300);
        });

        // フォーカス外れたら候補を隠す
        document.addEventListener('click', function(e) {
            if (!searchForm.contains(e.target)) {
                hideSuggestions();
            }
        });

        // カテゴリーフィルターの切り替え
        categoryFilters.forEach(filter => {
            filter.addEventListener('change', function() {
                // アクティブクラスの更新
                document.querySelectorAll('.category-filter').forEach(label => {
                    label.classList.remove('active');
                });
                this.closest('.category-filter').classList.add('active');

                // フォームを送信
                searchForm.submit();
            });
        });

        // ビューオプションの切り替え
        viewOptions.forEach(option => {
            option.addEventListener('click', function() {
                const viewType = this.dataset.view;

                // アクティブクラスの更新
                viewOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');

                // ビューの切り替え
                if (viewType === 'list') {
                    newsSection.classList.add('list-view');
                    localStorage.setItem('blogViewType', 'list');
                } else {
                    newsSection.classList.remove('list-view');
                    localStorage.setItem('blogViewType', 'grid');
                }
            });
        });

        // 保存されたビュータイプを復元
        const savedViewType = localStorage.getItem('blogViewType');
        if (savedViewType === 'list') {
            newsSection.classList.add('list-view');
            document.querySelector('[data-view="list"]').classList.add('active');
            document.querySelector('[data-view="grid"]').classList.remove('active');
        }

        // ソート変更時の処理
        sortSelect.addEventListener('change', function() {
            searchForm.submit();
        });

        // Enterキーでの送信を改善
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                hideSuggestions();
            }
        });

        // ローディング状態の管理
        searchForm.addEventListener('submit', function() {
            this.classList.add('loading');
        });

        // URLパラメータからカテゴリーを選択
        const urlParams = new URLSearchParams(window.location.search);
        const selectedCategory = urlParams.get('category_name');
        if (selectedCategory) {
            const categoryInput = document.querySelector(`input[name="category_name"][value="${selectedCategory}"]`);
            if (categoryInput) {
                categoryInput.checked = true;
                categoryInput.closest('.category-filter').classList.add('active');
                document.querySelector('.category-filter input[value=""]').closest('.category-filter').classList.remove('active');
            }
        }

        // スムーススクロール
        if (window.location.search) {
            const resultsHeader = document.querySelector('.search-results-header');
            if (resultsHeader) {
                setTimeout(() => {
                    resultsHeader.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 100);
            }
        }
    });

    // WordPressのREST APIエンドポイント設定
    window.wp_vars = window.wp_vars || {
        rest_url: '/wp-json/'
    };
})();