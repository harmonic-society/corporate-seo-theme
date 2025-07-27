/**
 * ブログフィルター機能（高度なUX版）
 * Corporate SEO Pro Theme
 */

(function() {
    'use strict';

    // フィルター状態管理
    const filterState = {
        categories: [],
        period: 'all',
        search: '',
        sort: 'date',
        view: 'grid'
    };

    // URLパラメータの管理
    const urlParams = new URLSearchParams(window.location.search);
    
    document.addEventListener('DOMContentLoaded', function() {
        initializeFilters();
        initializeFilterToggle();
        initializeFilterControls();
        initializeSortAndView();
        initializeAjaxFiltering();
        restoreFilterState();
        initializeMobileOptimizations();
    });

    /**
     * フィルターの初期化
     */
    function initializeFilters() {
        // 既存のURLパラメータから状態を復元
        const categoryParam = urlParams.get('categories');
        if (categoryParam) {
            filterState.categories = categoryParam.split(',');
        }
        
        const periodParam = urlParams.get('period');
        if (periodParam) {
            filterState.period = periodParam;
        }
        
        const searchParam = urlParams.get('s');
        if (searchParam) {
            filterState.search = searchParam;
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.value = searchParam;
            }
        }
        
        const sortParam = urlParams.get('orderby');
        if (sortParam) {
            filterState.sort = sortParam;
        }
    }

    /**
     * フィルタートグルボタンの初期化
     */
    function initializeFilterToggle() {
        const filterToggle = document.querySelector('.filter-toggle');
        const filterPanel = document.querySelector('.filter-panel');
        
        if (!filterToggle || !filterPanel) return;

        // フィルターパネルの表示/非表示
        filterToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const isOpen = !filterPanel.classList.contains('hidden');
            
            if (isOpen) {
                // 閉じる
                filterPanel.classList.add('hidden');
                filterToggle.classList.remove('active');
                document.body.classList.remove('filter-open');
            } else {
                // 開く
                filterPanel.classList.remove('hidden');
                filterToggle.classList.add('active');
                document.body.classList.add('filter-open');
                
                // アニメーションのトリガー
                setTimeout(() => {
                    filterPanel.classList.add('show');
                }, 10);
            }
        });

        // パネル外クリックで閉じる
        document.addEventListener('click', function(e) {
            if (!filterToggle.contains(e.target) && !filterPanel.contains(e.target)) {
                if (!filterPanel.classList.contains('hidden')) {
                    filterPanel.classList.add('hidden');
                    filterPanel.classList.remove('show');
                    filterToggle.classList.remove('active');
                    document.body.classList.remove('filter-open');
                }
            }
        });
    }

    /**
     * フィルターコントロールの初期化
     */
    function initializeFilterControls() {
        // カテゴリーチェックボックス
        const categoryCheckboxes = document.querySelectorAll('.filter-checkbox input[name="category"]');
        categoryCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateCategoryFilter(this.value, this.checked);
                updateActiveFiltersDisplay();
            });
        });

        // 期間ラジオボタン
        const periodRadios = document.querySelectorAll('.filter-radio input[name="period"]');
        periodRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                filterState.period = this.value;
                updateActiveFiltersDisplay();
            });
        });

        // フィルター適用ボタン
        const applyButton = document.querySelector('.apply-filters');
        if (applyButton) {
            applyButton.addEventListener('click', function() {
                applyFilters();
            });
        }

        // フィルタークリアボタン
        const clearButton = document.querySelector('.clear-filters');
        if (clearButton) {
            clearButton.addEventListener('click', function() {
                clearAllFilters();
            });
        }
    }

    /**
     * ソートとビューオプションの初期化
     */
    function initializeSortAndView() {
        // ソートセレクト
        const sortSelect = document.querySelector('#sortPosts');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                filterState.sort = this.value;
                applyFilters();
            });
        }

        // ビュー切り替え
        const viewOptions = document.querySelectorAll('.view-option');
        viewOptions.forEach(option => {
            option.addEventListener('click', function() {
                const view = this.dataset.view;
                filterState.view = view;
                
                // ビューの更新
                viewOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                
                const blogGrid = document.querySelector('.blog-grid-modern');
                if (blogGrid) {
                    if (view === 'list') {
                        blogGrid.classList.add('list-view');
                    } else {
                        blogGrid.classList.remove('list-view');
                    }
                }
                
                // LocalStorageに保存
                localStorage.setItem('blogViewType', view);
            });
        });
    }

    /**
     * カテゴリーフィルターの更新
     */
    function updateCategoryFilter(categoryId, isChecked) {
        if (isChecked) {
            if (!filterState.categories.includes(categoryId)) {
                filterState.categories.push(categoryId);
            }
        } else {
            filterState.categories = filterState.categories.filter(id => id !== categoryId);
        }
    }

    /**
     * アクティブなフィルターの表示更新
     */
    function updateActiveFiltersDisplay() {
        const activeCount = filterState.categories.length + (filterState.period !== 'all' ? 1 : 0);
        const filterToggle = document.querySelector('.filter-toggle');
        
        if (filterToggle) {
            const badge = filterToggle.querySelector('.filter-count') || document.createElement('span');
            badge.className = 'filter-count';
            
            if (activeCount > 0) {
                badge.textContent = activeCount;
                if (!filterToggle.contains(badge)) {
                    filterToggle.appendChild(badge);
                }
            } else {
                badge.remove();
            }
        }
    }

    /**
     * フィルターの適用（Ajax）
     */
    function applyFilters() {
        // ローディング表示
        showLoadingOverlay();
        
        // URLパラメータの構築
        const params = new URLSearchParams();
        
        if (filterState.categories.length > 0) {
            params.append('categories', filterState.categories.join(','));
        }
        
        if (filterState.period !== 'all') {
            params.append('period', filterState.period);
        }
        
        if (filterState.search) {
            params.append('s', filterState.search);
        }
        
        if (filterState.sort !== 'date') {
            params.append('orderby', filterState.sort);
        }
        
        // Ajax リクエスト
        if (typeof corporate_seo_pro_ajax === 'undefined') {
            // Ajax未対応の場合はフォールバック
            submitFilterForm();
            hideLoadingOverlay();
            return;
        }
        
        const ajaxUrl = corporate_seo_pro_ajax.ajax_url;
        const formData = new FormData();
        formData.append('action', 'filter_blog_posts');
        formData.append('nonce', corporate_seo_pro_ajax.nonce);
        formData.append('filters', JSON.stringify(filterState));
        
        fetch(ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Ajax response:', data); // デバッグ用
            
            if (data && data.success && data.data) {
                // WordPressのwp_send_json_successはdata.dataにコンテンツを格納
                const responseData = data.data;
                
                if (responseData.html) {
                    updateBlogGrid(responseData.html);
                } else {
                    console.error('No HTML data in response');
                    updateBlogGrid('<div class="no-posts-found"><p>記事が見つかりませんでした。</p></div>');
                }
                
                if (responseData.pagination) {
                    updatePagination(responseData.pagination);
                }
                
                updateURL(params);
                
                // フィルターパネルを閉じる
                const filterPanel = document.querySelector('.filter-panel');
                const filterToggle = document.querySelector('.filter-toggle');
                if (filterPanel && !filterPanel.classList.contains('hidden')) {
                    filterPanel.classList.add('hidden');
                    filterPanel.classList.remove('show');
                    if (filterToggle) {
                        filterToggle.classList.remove('active');
                    }
                }
            } else if (data && data.success === false && data.data && data.data.message) {
                // WordPressのwp_send_json_errorの場合
                console.error('Filter error:', data.data.message);
                updateBlogGrid(`<div class="no-posts-found"><p>${data.data.message}</p></div>`);
            } else {
                console.error('Invalid response data:', data);
                // エラー時のフォールバック
                updateBlogGrid('<div class="no-posts-found"><p>エラーが発生しました。ページを再読み込みしてください。</p></div>');
            }
            hideLoadingOverlay();
        })
        .catch(error => {
            console.error('Filter error:', error);
            hideLoadingOverlay();
            
            // エラーメッセージを表示
            updateBlogGrid('<div class="no-posts-found"><p>通信エラーが発生しました。ページを再読み込みしてください。</p></div>');
            
            // 重大なエラーの場合は3秒後にページリロード
            setTimeout(() => {
                if (confirm('エラーが発生しました。ページを再読み込みしますか？')) {
                    window.location.href = '?' + params.toString();
                }
            }, 3000);
        });
    }

    /**
     * ブロググリッドの更新
     */
    function updateBlogGrid(html) {
        const blogGrid = document.querySelector('.blog-grid-modern');
        if (blogGrid) {
            // フェードアウトアニメーション
            blogGrid.style.opacity = '0';
            
            setTimeout(() => {
                blogGrid.innerHTML = html;
                
                // 新しい要素にアニメーションを適用
                const articles = blogGrid.querySelectorAll('.blog-article');
                articles.forEach((article, index) => {
                    article.style.opacity = '0';
                    article.style.transform = 'translateY(20px)';
                    
                    setTimeout(() => {
                        article.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                        article.style.opacity = '1';
                        article.style.transform = 'translateY(0)';
                    }, index * 50);
                });
                
                // グリッド全体をフェードイン
                blogGrid.style.opacity = '1';
            }, 300);
        }
    }

    /**
     * ページネーションの更新
     */
    function updatePagination(paginationHtml) {
        const paginationContainer = document.querySelector('.pagination-modern');
        if (paginationContainer && paginationHtml) {
            paginationContainer.innerHTML = paginationHtml;
        }
    }

    /**
     * URLの更新（履歴API）
     */
    function updateURL(params) {
        const newURL = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.history.pushState({ filters: filterState }, '', newURL);
    }

    /**
     * すべてのフィルターをクリア
     */
    function clearAllFilters() {
        // 状態をリセット
        filterState.categories = [];
        filterState.period = 'all';
        filterState.search = '';
        filterState.sort = 'date';
        
        // UIをリセット
        document.querySelectorAll('.filter-checkbox input').forEach(cb => cb.checked = false);
        
        const allPeriodRadio = document.querySelector('.filter-radio input[value="all"]');
        if (allPeriodRadio) allPeriodRadio.checked = true;
        
        const searchInput = document.querySelector('.search-input');
        if (searchInput) searchInput.value = '';
        
        const sortSelect = document.querySelector('#sortPosts');
        if (sortSelect) sortSelect.value = 'date';
        
        // フィルターを適用
        applyFilters();
        updateActiveFiltersDisplay();
    }

    /**
     * フィルター状態の復元
     */
    function restoreFilterState() {
        // カテゴリーの復元
        filterState.categories.forEach(categoryId => {
            const checkbox = document.querySelector(`.filter-checkbox input[value="${categoryId}"]`);
            if (checkbox) checkbox.checked = true;
        });
        
        // 期間の復元
        const periodRadio = document.querySelector(`.filter-radio input[value="${filterState.period}"]`);
        if (periodRadio) periodRadio.checked = true;
        
        // ソートの復元
        const sortSelect = document.querySelector('#sortPosts');
        if (sortSelect) sortSelect.value = filterState.sort;
        
        // ビューの復元
        const savedView = localStorage.getItem('blogViewType') || 'grid';
        const viewOption = document.querySelector(`.view-option[data-view="${savedView}"]`);
        if (viewOption) viewOption.click();
        
        updateActiveFiltersDisplay();
    }

    /**
     * Ajax非対応の場合の初期化
     */
    function initializeAjaxFiltering() {
        // Ajax変数が定義されていない場合のフォールバック
        if (typeof corporate_seo_pro_ajax === 'undefined') {
            console.warn('Ajax filtering not available. Using form submission fallback.');
            
            // フィルター適用ボタンでフォーム送信
            const applyButton = document.querySelector('.apply-filters');
            if (applyButton) {
                applyButton.removeEventListener('click', applyFilters);
                applyButton.addEventListener('click', function() {
                    submitFilterForm();
                });
            }
        }
    }

    /**
     * フォーム送信によるフィルタリング（フォールバック）
     */
    function submitFilterForm() {
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = window.location.pathname;
        
        // カテゴリー
        filterState.categories.forEach(categoryId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'cat[]';
            input.value = categoryId;
            form.appendChild(input);
        });
        
        // 期間
        if (filterState.period !== 'all') {
            const periodInput = document.createElement('input');
            periodInput.type = 'hidden';
            periodInput.name = 'period';
            periodInput.value = filterState.period;
            form.appendChild(periodInput);
        }
        
        // 検索
        if (filterState.search) {
            const searchInput = document.createElement('input');
            searchInput.type = 'hidden';
            searchInput.name = 's';
            searchInput.value = filterState.search;
            form.appendChild(searchInput);
        }
        
        // ソート
        if (filterState.sort !== 'date') {
            const sortInput = document.createElement('input');
            sortInput.type = 'hidden';
            sortInput.name = 'orderby';
            sortInput.value = filterState.sort;
            form.appendChild(sortInput);
        }
        
        document.body.appendChild(form);
        form.submit();
    }

    /**
     * ローディングオーバーレイの表示
     */
    function showLoadingOverlay() {
        let overlay = document.querySelector('.filter-loading-overlay');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.className = 'filter-loading-overlay';
            overlay.innerHTML = `
                <div class="loading-spinner">
                    <div class="spinner-circle"></div>
                    <span>フィルタリング中...</span>
                </div>
            `;
            document.body.appendChild(overlay);
        }
        overlay.classList.add('show');
    }

    /**
     * ローディングオーバーレイの非表示
     */
    function hideLoadingOverlay() {
        const overlay = document.querySelector('.filter-loading-overlay');
        if (overlay) {
            overlay.classList.remove('show');
        }
    }

    // ブラウザの戻る/進むボタン対応
    window.addEventListener('popstate', function(event) {
        if (event.state && event.state.filters) {
            filterState.categories = event.state.filters.categories || [];
            filterState.period = event.state.filters.period || 'all';
            filterState.search = event.state.filters.search || '';
            filterState.sort = event.state.filters.sort || 'date';
            
            restoreFilterState();
            applyFilters();
        }
    });

    /**
     * モバイル最適化
     */
    function initializeMobileOptimizations() {
        // タッチデバイスの検出
        const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
        
        if (isTouchDevice) {
            // フィルタートグルボタンのタッチイベント最適化
            const filterToggle = document.querySelector('.filter-toggle');
            if (filterToggle) {
                filterToggle.addEventListener('touchstart', function(e) {
                    e.preventDefault();
                    this.click();
                }, { passive: false });
            }
            
            // 適用・クリアボタンのタッチイベント最適化
            const applyButton = document.querySelector('.apply-filters');
            const clearButton = document.querySelector('.clear-filters');
            
            if (applyButton) {
                applyButton.addEventListener('touchstart', function(e) {
                    e.preventDefault();
                    this.click();
                }, { passive: false });
            }
            
            if (clearButton) {
                clearButton.addEventListener('touchstart', function(e) {
                    e.preventDefault();
                    this.click();
                }, { passive: false });
            }
            
            // ビューオプションのタッチイベント最適化
            const viewOptions = document.querySelectorAll('.view-option');
            viewOptions.forEach(option => {
                option.addEventListener('touchstart', function(e) {
                    e.preventDefault();
                    this.click();
                }, { passive: false });
            });
        }
        
        // 検索入力フィールドのフォーカス問題を修正
        const searchInput = document.querySelector('.search-input');
        if (searchInput) {
            searchInput.addEventListener('focus', function() {
                // モバイルでのスクロール位置を調整
                if (window.innerWidth <= 768) {
                    setTimeout(() => {
                        this.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 300);
                }
            });
        }
        
        // フィルターパネルのモバイル用調整
        const filterPanel = document.querySelector('.filter-panel');
        if (filterPanel && window.innerWidth <= 768) {
            // モバイルでパネルが開いた時にスクロールを無効化
            const filterToggle = document.querySelector('.filter-toggle');
            if (filterToggle) {
                const originalToggleClick = filterToggle.onclick;
                filterToggle.onclick = function(e) {
                    if (originalToggleClick) originalToggleClick.call(this, e);
                    
                    if (!filterPanel.classList.contains('hidden')) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = '';
                    }
                };
            }
        }
    }

})();