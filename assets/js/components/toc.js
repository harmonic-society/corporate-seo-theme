/**
 * 記事詳細ページの目次機能
 * 圧倒的に優れたUXを提供する目次システム
 */

(function() {
    'use strict';

    // DOM要素の取得
    let tocList, mobileTocList, tocProgressBar, entryContent, articleToc;
    let scrollIndicator, scrollIndicatorBar;
    let headings = [];
    let headingOffsets = [];
    let currentActiveIndex = -1;
    let isScrolling = false;

    /**
     * 初期化
     */
    function init() {
        // DOM要素の取得
        tocList = document.getElementById('tocList');
        mobileTocList = document.getElementById('mobileTocList');
        tocProgressBar = document.getElementById('tocProgressBar');
        entryContent = document.querySelector('.entry-content');
        articleToc = document.getElementById('articleToc');
        scrollIndicator = document.getElementById('scrollIndicator');
        scrollIndicatorBar = document.getElementById('scrollIndicatorBar');

        if (!entryContent) return;

        // h2見出しを取得
        headings = entryContent.querySelectorAll('h2');
        
        if (headings.length === 0) {
            // 見出しがない場合は目次を非表示
            if (articleToc) articleToc.style.display = 'none';
            const mobileToc = document.getElementById('mobileToc');
            if (mobileToc) mobileToc.style.display = 'none';
            return;
        }

        // 目次を生成
        generateToc();
        
        // イベントリスナーを設定
        setupEventListeners();
        
        // 初期状態を設定
        updateActiveHeading();
        updateProgressBar();
    }

    /**
     * 目次を生成
     */
    function generateToc() {
        let tocHTML = '';
        
        headings.forEach((heading, index) => {
            // IDがない場合は生成
            if (!heading.id) {
                heading.id = 'toc-heading-' + index;
            }
            
            // 見出しテキストを取得
            const headingText = heading.textContent.trim();
            const number = (index + 1) + '.';
            
            // 目次アイテムを生成
            tocHTML += `
                <li class="toc-item">
                    <a href="#${heading.id}" class="toc-link" data-index="${index}">
                        <span class="toc-number">${number}</span>
                        <span class="toc-text">${headingText}</span>
                    </a>
                </li>
            `;
        });
        
        // デスクトップ用目次
        if (tocList) {
            tocList.innerHTML = tocHTML;
        }
        
        // モバイル用目次
        if (mobileTocList) {
            mobileTocList.innerHTML = tocHTML;
        }
    }

    /**
     * イベントリスナーの設定
     */
    function setupEventListeners() {
        // スムーススクロール
        document.querySelectorAll('.toc-link').forEach(link => {
            link.addEventListener('click', handleTocLinkClick);
        });

        // スクロールイベント
        let scrollTimer;
        window.addEventListener('scroll', () => {
            isScrolling = true;
            clearTimeout(scrollTimer);
            
            // スクロール終了を検知
            scrollTimer = setTimeout(() => {
                isScrolling = false;
            }, 150);
            
            requestAnimationFrame(() => {
                updateActiveHeading();
                updateProgressBar();
                checkTocVisibility();
            });
        });

        // リサイズイベント
        window.addEventListener('resize', debounce(() => {
            updateHeadingOffsets();
        }, 250));
    }

    /**
     * 目次リンクのクリックハンドラー
     */
    function handleTocLinkClick(e) {
        e.preventDefault();
        
        const targetId = this.getAttribute('href').substring(1);
        const targetElement = document.getElementById(targetId);
        
        if (targetElement) {
            const headerHeight = document.querySelector('.site-header')?.offsetHeight || 60;
            const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;
            
            // スムーススクロール
            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
            
            // モバイル目次を閉じる
            const mobileToc = document.getElementById('mobileToc');
            if (mobileToc && window.innerWidth < 1400) {
                mobileToc.classList.add('collapsed');
            }
        }
    }

    /**
     * アクティブな見出しを更新
     */
    function updateActiveHeading() {
        const scrollPosition = window.pageYOffset;
        const headerHeight = document.querySelector('.site-header')?.offsetHeight || 60;
        
        // 各見出しの位置を更新
        updateHeadingOffsets();
        
        // 現在の位置に対応する見出しを探す
        let activeIndex = -1;
        
        // ビューポートの中央を基準にする
        const viewportMiddle = scrollPosition + window.innerHeight / 3;
        
        for (let i = headingOffsets.length - 1; i >= 0; i--) {
            if (viewportMiddle >= headingOffsets[i]) {
                activeIndex = i;
                break;
            }
        }
        
        // 最後のセクションの特別処理
        if (scrollPosition + window.innerHeight >= document.documentElement.scrollHeight - 50) {
            activeIndex = headingOffsets.length - 1;
        }
        
        // アクティブな見出しが変わった場合のみ更新
        if (activeIndex !== currentActiveIndex) {
            updateActiveTocItem(activeIndex);
            currentActiveIndex = activeIndex;
        }
    }

    /**
     * 見出しのオフセット位置を更新
     */
    function updateHeadingOffsets() {
        const headerHeight = document.querySelector('.site-header')?.offsetHeight || 60;
        headingOffsets = [];
        
        headings.forEach(heading => {
            const rect = heading.getBoundingClientRect();
            const offsetTop = rect.top + window.pageYOffset;
            headingOffsets.push(offsetTop - headerHeight - 50);
        });
    }

    /**
     * アクティブな目次アイテムを更新
     */
    function updateActiveTocItem(activeIndex) {
        // すべてのリンクから active クラスを削除
        document.querySelectorAll('.toc-link').forEach(link => {
            link.classList.remove('active');
        });
        
        // 新しいアクティブ状態を設定
        if (activeIndex >= 0) {
            document.querySelectorAll(`[data-index="${activeIndex}"]`).forEach(link => {
                link.classList.add('active');
                
                // デスクトップ目次でアクティブアイテムを表示領域に保つ
                if (window.innerWidth >= 1400 && !isScrolling) {
                    const tocNav = link.closest('.toc-nav');
                    if (tocNav) {
                        const linkRect = link.getBoundingClientRect();
                        const navRect = tocNav.getBoundingClientRect();
                        
                        if (linkRect.top < navRect.top + 20 || linkRect.bottom > navRect.bottom - 20) {
                            link.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }
                    }
                }
            });
        }
    }

    /**
     * プログレスバーを更新
     */
    function updateProgressBar() {
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollPercent = (scrollTop / (documentHeight - windowHeight)) * 100;
        
        // 目次内のプログレスバー
        if (tocProgressBar) {
            tocProgressBar.style.width = Math.min(scrollPercent, 100) + '%';
        }
        
        // 画面上部のスクロールインジケーター
        if (scrollIndicatorBar) {
            scrollIndicatorBar.style.width = Math.min(scrollPercent, 100) + '%';
            
            // スクロールしたら表示
            if (scrollIndicator && scrollTop > 100) {
                scrollIndicator.classList.add('visible');
            } else if (scrollIndicator) {
                scrollIndicator.classList.remove('visible');
            }
        }
    }

    /**
     * 目次の表示/非表示を制御
     */
    function checkTocVisibility() {
        if (!articleToc || window.innerWidth < 1400) return;
        
        const scrollTop = window.pageYOffset;
        const entryContentRect = entryContent.getBoundingClientRect();
        const entryContentBottom = entryContentRect.bottom + scrollTop;
        
        // 記事の終わりを過ぎたら目次を非表示
        if (scrollTop > entryContentBottom - window.innerHeight) {
            articleToc.classList.add('hidden');
        } else {
            articleToc.classList.remove('hidden');
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
     * モバイル目次の開閉
     */
    window.toggleMobileToc = function() {
        const mobileToc = document.getElementById('mobileToc');
        if (mobileToc) {
            mobileToc.classList.toggle('collapsed');
        }
    };

    // DOMContentLoaded で初期化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();