/**
 * Table of Contents (目次) functionality
 */
(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        const tocList = document.getElementById('tocList');
        const tocContainer = document.getElementById('tocContainer');
        const tocToggle = document.getElementById('tocToggle');
        const tocContent = document.getElementById('tocContent');
        const tocProgressBar = document.getElementById('tocProgressBar');
        const entryContent = document.querySelector('.entry-content');
        
        if (!tocList || !entryContent) return;

        // 見出しを取得（h2、h3、h4）
        const headings = entryContent.querySelectorAll('h2, h3, h4');
        
        if (headings.length === 0) {
            // 見出しがない場合は目次を非表示
            tocContainer.style.display = 'none';
            return;
        }

        // 見出しにIDを付与し、目次を生成
        let tocHTML = '';
        const headingOffsets = [];
        let h2Count = 0;
        let h3Count = 0;
        
        let h4Count = 0;
        
        headings.forEach((heading, index) => {
            // IDがない場合は生成
            if (!heading.id) {
                heading.id = 'heading-' + index;
            }
            
            // 見出しテキストを取得（HTMLタグを除去）
            const headingText = heading.textContent.trim();
            
            // 見出しレベルを判定
            const level = heading.tagName.toLowerCase();
            let levelClass = '';
            if (level === 'h3') {
                levelClass = 'toc-item-sub';
            } else if (level === 'h4') {
                levelClass = 'toc-item-sub-sub';
            }
            
            // 番号付け
            let number = '';
            if (level === 'h2') {
                h2Count++;
                h3Count = 0;
                h4Count = 0;
                number = h2Count + '.';
            } else if (level === 'h3') {
                h3Count++;
                h4Count = 0;
                number = h2Count + '.' + h3Count;
            } else if (level === 'h4') {
                h4Count++;
                number = h2Count + '.' + h3Count + '.' + h4Count;
            }
            
            // 目次アイテムを追加
            tocHTML += `
                <li class="toc-item ${levelClass}">
                    <a href="#${heading.id}" class="toc-link" data-index="${index}">
                        <span class="toc-number">${number}</span>
                        <span class="toc-text">${headingText}</span>
                    </a>
                </li>
            `;
        });
        
        tocList.innerHTML = tocHTML;

        // スムーススクロール
        const tocLinks = tocList.querySelectorAll('.toc-link');
        tocLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    const headerHeight = document.querySelector('.site-header').offsetHeight || 0;
                    const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // 現在の見出しをハイライト
        let currentActiveIndex = -1;
        
        function updateActiveHeading() {
            const scrollPosition = window.pageYOffset;
            const headerHeight = document.querySelector('.site-header').offsetHeight || 0;
            
            // 各見出しの位置を更新
            headingOffsets.length = 0;
            headings.forEach((heading) => {
                headingOffsets.push(heading.offsetTop - headerHeight - 50);
            });
            
            // 現在の位置に対応する見出しを探す
            let activeIndex = -1;
            for (let i = headingOffsets.length - 1; i >= 0; i--) {
                if (scrollPosition >= headingOffsets[i]) {
                    activeIndex = i;
                    break;
                }
            }
            
            // アクティブな見出しが変わった場合のみ更新
            if (activeIndex !== currentActiveIndex) {
                // 前のアクティブ状態を削除
                tocLinks.forEach(link => {
                    link.classList.remove('active');
                    link.parentElement.classList.remove('active');
                });
                
                // 新しいアクティブ状態を設定
                if (activeIndex >= 0) {
                    const activeLink = tocList.querySelector(`[data-index="${activeIndex}"]`);
                    if (activeLink) {
                        activeLink.classList.add('active');
                        activeLink.parentElement.classList.add('active');
                    }
                }
                
                currentActiveIndex = activeIndex;
            }
            
            // プログレスバーの更新
            updateProgressBar();
        }

        // プログレスバーの更新
        function updateProgressBar() {
            const windowHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight;
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const scrollPercent = (scrollTop / (documentHeight - windowHeight)) * 100;
            
            if (tocProgressBar) {
                tocProgressBar.style.width = scrollPercent + '%';
            }
        }

        // スクロールイベント（パフォーマンス最適化）
        let ticking = false;
        function requestTick() {
            if (!ticking) {
                window.requestAnimationFrame(updateActiveHeading);
                ticking = true;
                setTimeout(() => { ticking = false; }, 100);
            }
        }
        
        window.addEventListener('scroll', requestTick);
        updateActiveHeading(); // 初期状態を設定

        // 目次の開閉
        if (tocToggle) {
            tocToggle.addEventListener('click', function() {
                const isOpen = tocContainer.classList.contains('toc-collapsed');
                const isMobile = window.innerWidth <= 768;
                
                if (isOpen) {
                    tocContainer.classList.remove('toc-collapsed');
                    this.innerHTML = '<i class="fas fa-chevron-down"></i>';
                    if (isMobile) {
                        localStorage.setItem('tocStateMobile', 'open');
                    } else {
                        localStorage.setItem('tocState', 'open');
                    }
                } else {
                    tocContainer.classList.add('toc-collapsed');
                    this.innerHTML = '<i class="fas fa-chevron-up"></i>';
                    if (isMobile) {
                        localStorage.setItem('tocStateMobile', 'collapsed');
                    } else {
                        localStorage.setItem('tocState', 'collapsed');
                    }
                }
            });
            
            // 保存された状態を復元（PCのみ）
            if (window.innerWidth > 768) {
                const savedState = localStorage.getItem('tocState');
                if (savedState === 'collapsed') {
                    tocContainer.classList.add('toc-collapsed');
                    tocToggle.innerHTML = '<i class="fas fa-chevron-up"></i>';
                }
            }
        }

        // スティッキー目次の位置調整
        function adjustTocPosition() {
            if (window.innerWidth <= 1200) {
                // モバイル・タブレットでは相対位置
                tocContainer.style.position = 'relative';
                tocContainer.style.top = 'auto';
                tocContainer.style.maxHeight = 'none'; // モバイルでは高さ制限なし
            } else {
                // PCではstickyを維持し、ヘッダー高さに応じてtopを調整
                const header = document.querySelector('.site-header');
                if (header) {
                    const headerHeight = header.offsetHeight;
                    const additionalOffset = 20; // 追加の余白
                    tocContainer.style.position = 'sticky';
                    tocContainer.style.top = (headerHeight + additionalOffset) + 'px';
                    
                    // max-heightも動的に調整
                    const maxHeight = window.innerHeight - headerHeight - additionalOffset - 20;
                    tocContainer.style.maxHeight = maxHeight + 'px';
                }
            }
        }
        
        // 初期設定とリサイズ・スクロール時の調整
        window.addEventListener('resize', adjustTocPosition);
        window.addEventListener('scroll', function() {
            // スクロール時にヘッダーの高さが変わる場合の対応
            if (window.innerWidth > 1200) {
                requestAnimationFrame(adjustTocPosition);
            }
        });
        adjustTocPosition(); // 初期状態を設定

        // モバイルでの目次表示設定
        function setMobileTocState() {
            if (window.innerWidth <= 768) {
                // モバイルでは初期状態で表示（ユーザーが折りたたむことは可能）
                const savedState = localStorage.getItem('tocStateMobile');
                if (savedState === 'collapsed') {
                    tocContainer.classList.add('toc-collapsed');
                    if (tocToggle) {
                        tocToggle.innerHTML = '<i class="fas fa-chevron-up"></i>';
                    }
                }
            }
        }
        
        setMobileTocState();
    });
})();