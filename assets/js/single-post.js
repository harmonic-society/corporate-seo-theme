/**
 * ブログ詳細ページの機能
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // シェアボタンのコピー機能
    const copyButton = document.querySelector('.share-copy');
    if (copyButton) {
        copyButton.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            navigator.clipboard.writeText(url).then(() => {
                const originalText = this.querySelector('span').textContent;
                this.querySelector('span').textContent = 'コピーしました！';
                this.classList.add('copied');
                
                setTimeout(() => {
                    this.querySelector('span').textContent = originalText;
                    this.classList.remove('copied');
                }, 2000);
            });
        });
    }

    // スムーススクロール
    const tocLinks = document.querySelectorAll('.toc-link');
    tocLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // 読了プログレスバー
    const createProgressBar = () => {
        const progressBar = document.createElement('div');
        progressBar.className = 'reading-progress';
        progressBar.innerHTML = '<div class="reading-progress-bar"></div>';
        document.body.appendChild(progressBar);

        const updateProgress = () => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const progress = (scrollTop / scrollHeight) * 100;
            
            const progressBarFill = document.querySelector('.reading-progress-bar');
            if (progressBarFill) {
                progressBarFill.style.width = progress + '%';
            }
        };

        window.addEventListener('scroll', updateProgress);
        updateProgress();
    };

    if (document.querySelector('.single-post')) {
        createProgressBar();
    }

    // 画像の拡大表示
    const contentImages = document.querySelectorAll('.entry-content img');
    contentImages.forEach(img => {
        img.addEventListener('click', function() {
            const overlay = document.createElement('div');
            overlay.className = 'image-overlay';
            overlay.innerHTML = `
                <div class="image-overlay-content">
                    <img src="${this.src}" alt="${this.alt}">
                    <button class="image-overlay-close">&times;</button>
                </div>
            `;
            
            document.body.appendChild(overlay);
            document.body.style.overflow = 'hidden';
            
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay || e.target.classList.contains('image-overlay-close')) {
                    overlay.remove();
                    document.body.style.overflow = '';
                }
            });
        });
    });

    // ヘッダーのスクロール制御
    let lastScrollTop = 0;
    const header = document.querySelector('.site-header');
    const heroHeight = document.querySelector('.entry-hero') ? document.querySelector('.entry-hero').offsetHeight : 0;
    
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > heroHeight) {
            if (scrollTop > lastScrollTop) {
                // 下スクロール
                header.classList.add('header-hidden');
            } else {
                // 上スクロール
                header.classList.remove('header-hidden');
            }
        } else {
            header.classList.remove('header-hidden');
        }
        
        lastScrollTop = scrollTop;
    });

    // コメントフォームのアニメーション
    const commentForm = document.querySelector('#commentform');
    if (commentForm) {
        const inputs = commentForm.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
        });
    }
});

// 読了プログレスバー用のスタイル
const style = document.createElement('style');
style.textContent = `
    .reading-progress {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: rgba(0,0,0,0.1);
        z-index: 9999;
    }

    .reading-progress-bar {
        height: 100%;
        background: var(--primary-color);
        width: 0;
        transition: width 0.2s ease;
    }

    .share-copy.copied {
        background: #4caf50 !important;
    }

    .image-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.9);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        cursor: zoom-out;
    }

    .image-overlay-content {
        position: relative;
        max-width: 90%;
        max-height: 90%;
    }

    .image-overlay img {
        max-width: 100%;
        max-height: 90vh;
        object-fit: contain;
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    }

    .image-overlay-close {
        position: absolute;
        top: -40px;
        right: 0;
        background: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-size: 24px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .image-overlay-close:hover {
        background: var(--primary-color);
        color: white;
        transform: rotate(90deg);
    }

    .header-hidden {
        transform: translateY(-100%);
        transition: transform 0.3s ease;
    }

    .site-header {
        transition: transform 0.3s ease;
    }

    .entry-content img {
        cursor: zoom-in;
        transition: transform 0.3s ease;
    }

    .entry-content img:hover {
        transform: scale(1.02);
    }
`;
document.head.appendChild(style);