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
        const filterChips = document.querySelectorAll('.filter-chip');
        const filterDropdowns = document.querySelectorAll('.filter-dropdown');
        const viewOptions = document.querySelectorAll('.view-option');
        const sortSelect = document.querySelector('.sort-select');
        const activeFiltersContainer = document.querySelector('.active-filters');
        const blogGrid = document.querySelector('.blog-grid-modern');

        if (!searchForm) {
            console.log('Search form not found');
            return;
        }
        
        if (!searchInput) {
            console.log('Search input not found');
            return;
        }
        
        // デバッグ情報
        console.log('Filter chips found:', filterChips.length);
        console.log('Filter dropdowns found:', filterDropdowns.length);
        
        // アクティブフィルターの管理
        let activeFilters = {
            tags: [],
            categories: [],
            period: 'all'
        };

        // フィルターチップのクリックイベント
        if (filterChips.length > 0) {
            filterChips.forEach(chip => {
                chip.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const tag = this.getAttribute('data-tag');
                    console.log('Filter chip clicked:', tag);
                    
                    if (this.classList.contains('active')) {
                        this.classList.remove('active');
                        activeFilters.tags = activeFilters.tags.filter(t => t !== tag);
                    } else {
                        this.classList.add('active');
                        activeFilters.tags.push(tag);
                    }
                    
                    updateActiveFilters();
                    applyFilters();
                });
            });
        } else {
            console.log('No filter chips found on page');
        }

        // フィルタードロップダウンの制御
        if (filterDropdowns.length > 0) {
            filterDropdowns.forEach(dropdown => {
                const toggle = dropdown.querySelector('.filter-dropdown-toggle');
                const menu = dropdown.querySelector('.filter-dropdown-menu');
                
                if (!toggle || !menu) {
                    console.log('Dropdown elements not found');
                    return;
                }
                
                toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Dropdown toggle clicked');
                
                // 他のドロップダウンを閉じる
                filterDropdowns.forEach(other => {
                    if (other !== dropdown) {
                        other.classList.remove('active');
                        other.querySelector('.filter-dropdown-toggle').classList.remove('active');
                    }
                });
                
                // 現在のドロップダウンをトグル
                dropdown.classList.toggle('active');
                this.classList.toggle('active');
            });
            
            // チェックボックス/ラジオボタンの変更イベント
            const inputs = menu.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (input.type === 'checkbox') {
                        // カテゴリーフィルター
                        const categoryId = parseInt(input.value);
                        if (input.checked) {
                            activeFilters.categories.push(categoryId);
                        } else {
                            activeFilters.categories = activeFilters.categories.filter(id => id !== categoryId);
                        }
                    } else if (input.type === 'radio') {
                        // 期間フィルター
                        activeFilters.period = input.value;
                    }
                    
                    updateActiveFilters();
                    applyFilters();
                });
            });
        });
        }

        // アクティブフィルターの表示を更新
        function updateActiveFilters() {
            if (!activeFiltersContainer) return;
            
            activeFiltersContainer.innerHTML = '';
            
            // タグフィルター
            activeFilters.tags.forEach(tag => {
                const filterTag = document.createElement('div');
                filterTag.className = 'active-filter-tag';
                filterTag.innerHTML = `
                    <i class="fas fa-hashtag"></i>
                    <span>${tag}</span>
                    <button type="button" data-type="tag" data-value="${tag}">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                activeFiltersContainer.appendChild(filterTag);
            });
            
            // カテゴリーフィルター
            activeFilters.categories.forEach(categoryId => {
                const categoryLabel = document.querySelector(`input[value="${categoryId}"]`)?.nextElementSibling?.textContent || 'Category';
                const filterTag = document.createElement('div');
                filterTag.className = 'active-filter-tag';
                filterTag.innerHTML = `
                    <i class="fas fa-folder"></i>
                    <span>${categoryLabel}</span>
                    <button type="button" data-type="category" data-value="${categoryId}">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                activeFiltersContainer.appendChild(filterTag);
            });
            
            // 期間フィルター
            if (activeFilters.period !== 'all') {
                const periodLabel = document.querySelector(`input[value="${activeFilters.period}"]`)?.nextElementSibling?.textContent || 'Period';
                const filterTag = document.createElement('div');
                filterTag.className = 'active-filter-tag';
                filterTag.innerHTML = `
                    <i class="fas fa-calendar"></i>
                    <span>${periodLabel}</span>
                    <button type="button" data-type="period" data-value="${activeFilters.period}">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                activeFiltersContainer.appendChild(filterTag);
            }
            
            // 削除ボタンのイベントリスナー
            activeFiltersContainer.querySelectorAll('button').forEach(btn => {
                btn.addEventListener('click', function() {
                    const type = this.getAttribute('data-type');
                    const value = this.getAttribute('data-value');
                    
                    if (type === 'tag') {
                        activeFilters.tags = activeFilters.tags.filter(t => t !== value);
                        document.querySelector(`[data-tag="${value}"]`)?.classList.remove('active');
                    } else if (type === 'category') {
                        activeFilters.categories = activeFilters.categories.filter(id => id !== parseInt(value));
                        const checkbox = document.querySelector(`input[value="${value}"]`);
                        if (checkbox) checkbox.checked = false;
                    } else if (type === 'period') {
                        activeFilters.period = 'all';
                        document.querySelector('input[value="all"]').checked = true;
                    }
                    
                    updateActiveFilters();
                    applyFilters();
                });
            });
        }

        // フィルターを適用（hiddenフィールドを更新するだけ）
        function applyFilters() {
            // 既存のhiddenフィールドを削除
            const existingHiddenInputs = searchForm.querySelectorAll('input[type="hidden"]:not([name="nonce"])');
            existingHiddenInputs.forEach(input => input.remove());
            
            // タグフィルター
            if (activeFilters.tags.length > 0) {
                const tagInput = document.createElement('input');
                tagInput.type = 'hidden';
                tagInput.name = 'tags';
                tagInput.value = activeFilters.tags.join(',');
                searchForm.appendChild(tagInput);
            }
            
            // カテゴリーフィルター
            activeFilters.categories.forEach(categoryId => {
                const categoryInput = document.createElement('input');
                categoryInput.type = 'hidden';
                categoryInput.name = 'category[]';
                categoryInput.value = categoryId;
                searchForm.appendChild(categoryInput);
            });
            
            // 期間フィルター
            if (activeFilters.period !== 'all') {
                const periodInput = document.createElement('input');
                periodInput.type = 'hidden';
                periodInput.name = 'period';
                periodInput.value = activeFilters.period;
                searchForm.appendChild(periodInput);
            }
            
            // 検索実行ボタンの表示状態を更新
            updateSearchButton();
        }
        
        // 検索実行ボタンの表示状態を更新
        function updateSearchButton() {
            const searchExecuteBtn = document.querySelector('.search-execute-btn');
            if (!searchExecuteBtn) return;
            
            // フィルターが選択されているか、検索キーワードが入力されているかチェック
            const hasFilters = activeFilters.tags.length > 0 || 
                             activeFilters.categories.length > 0 || 
                             activeFilters.period !== 'all' ||
                             searchInput.value.trim() !== '';
            
            if (hasFilters) {
                searchExecuteBtn.classList.add('has-filters');
            } else {
                searchExecuteBtn.classList.remove('has-filters');
            }
        }

        // ドロップダウンを閉じる
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.filter-dropdown')) {
                filterDropdowns.forEach(dropdown => {
                    dropdown.classList.remove('active');
                    dropdown.querySelector('.filter-dropdown-toggle').classList.remove('active');
                });
            }
        });

        // ビューオプションの切り替え
        if (viewOptions) {
            viewOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const viewType = this.dataset.view;

                    // アクティブクラスの更新
                    viewOptions.forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');

                    // ビューの切り替え
                    if (blogGrid) {
                        if (viewType === 'list') {
                            blogGrid.classList.add('list-view');
                            localStorage.setItem('blogViewType', 'list');
                        } else {
                            blogGrid.classList.remove('list-view');
                            localStorage.setItem('blogViewType', 'grid');
                        }
                    }
                });
            });

            // 保存されたビュータイプを復元
            const savedViewType = localStorage.getItem('blogViewType');
            if (savedViewType === 'list' && blogGrid) {
                blogGrid.classList.add('list-view');
                document.querySelector('[data-view="list"]')?.classList.add('active');
                document.querySelector('[data-view="grid"]')?.classList.remove('active');
            }
        }

        // ソート変更時の処理
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                searchForm.submit();
            });
        }

        // 検索入力の最適化
        searchInput.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });

        searchInput.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
        
        // 検索入力の変更を監視
        searchInput.addEventListener('input', function() {
            updateSearchButton();
        });

        // Enterキーでの送信
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchForm.submit();
            }
        });

        // ローディング状態の管理
        searchForm.addEventListener('submit', function() {
            this.classList.add('loading');
            searchInput.blur();
        });

        // URLパラメータから初期フィルターを設定
        const urlParams = new URLSearchParams(window.location.search);
        
        // カテゴリーパラメータ
        const categoryParams = urlParams.getAll('category[]');
        categoryParams.forEach(categoryId => {
            const checkbox = document.querySelector(`input[value="${categoryId}"]`);
            if (checkbox) {
                checkbox.checked = true;
                activeFilters.categories.push(parseInt(categoryId));
            }
        });
        
        // 期間パラメータ
        const periodParam = urlParams.get('period');
        if (periodParam) {
            const radio = document.querySelector(`input[value="${periodParam}"]`);
            if (radio) {
                radio.checked = true;
                activeFilters.period = periodParam;
            }
        }
        
        // タグパラメータ
        const tagsParam = urlParams.get('tags');
        if (tagsParam) {
            const tags = tagsParam.split(',');
            tags.forEach(tag => {
                const chip = document.querySelector(`[data-tag="${tag}"]`);
                if (chip) {
                    chip.classList.add('active');
                    activeFilters.tags.push(tag);
                }
            });
        }
        
        // 初期フィルターの表示を更新
        updateActiveFilters();
        updateSearchButton();
    });

    // WordPressのREST APIエンドポイント設定
    window.wp_vars = window.wp_vars || {
        rest_url: '/wp-json/'
    };
})();