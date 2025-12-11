/**
 * Download Modal JavaScript
 *
 * 資料ダウンロードモーダルの制御
 *
 * @package Corporate_SEO_Pro
 */

(function() {
    'use strict';

    // DOM要素
    let modal = null;
    let form = null;
    let emailInput = null;
    let submitBtn = null;
    let closeBtn = null;
    let overlay = null;
    let successView = null;
    let formView = null;
    let triggers = null;

    // 初期化
    function init() {
        modal = document.getElementById('download-modal');
        if (!modal) return;

        form = document.getElementById('download-form');
        emailInput = document.getElementById('download-email');
        submitBtn = modal.querySelector('.download-submit-btn');
        closeBtn = modal.querySelector('.download-modal-close');
        overlay = modal.querySelector('.download-modal-overlay');
        successView = modal.querySelector('.download-success');
        formView = modal.querySelector('.download-form');
        triggers = document.querySelectorAll('[data-open-download-modal]');

        bindEvents();
    }

    // イベントバインド
    function bindEvents() {
        // トリガーボタン（ナビゲーションの資料ダウンロードボタン）
        triggers.forEach(function(trigger) {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                openModal();
            });
        });

        // 閉じるボタン
        if (closeBtn) {
            closeBtn.addEventListener('click', closeModal);
        }

        // オーバーレイクリック
        if (overlay) {
            overlay.addEventListener('click', closeModal);
        }

        // Escキーで閉じる
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('is-active')) {
                closeModal();
            }
        });

        // フォーム送信
        if (form) {
            form.addEventListener('submit', handleSubmit);
        }

        // 成功画面の閉じるボタン
        const successCloseBtn = modal.querySelector('.download-close-btn');
        if (successCloseBtn) {
            successCloseBtn.addEventListener('click', closeModal);
        }

        // メール入力時のバリデーション
        if (emailInput) {
            emailInput.addEventListener('input', clearError);
            emailInput.addEventListener('blur', validateEmail);
        }
    }

    // モーダルを開く
    function openModal() {
        modal.classList.add('is-active');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('download-modal-open');

        // フォームをリセット
        resetForm();

        // フォーカスをメール入力欄に
        setTimeout(function() {
            if (emailInput) {
                emailInput.focus();
            }
        }, 100);

        // GA4イベント（モーダル表示）
        if (typeof gtag === 'function') {
            gtag('event', 'download_modal_open', {
                event_category: 'engagement',
                event_label: 'company_presentation'
            });
        }
    }

    // モーダルを閉じる
    function closeModal() {
        modal.classList.remove('is-active');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('download-modal-open');

        // フォーカスを元に戻す
        const activeElement = document.activeElement;
        if (activeElement && modal.contains(activeElement)) {
            activeElement.blur();
        }
    }

    // フォームリセット
    function resetForm() {
        if (form) {
            form.reset();
        }
        clearError();

        // フォーム表示、成功画面非表示
        if (formView) {
            formView.style.display = 'block';
        }
        if (successView) {
            successView.style.display = 'none';
        }

        // ボタン状態リセット
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.querySelector('.btn-text').style.display = 'flex';
            submitBtn.querySelector('.btn-loading').style.display = 'none';
        }
    }

    // エラー表示クリア
    function clearError() {
        if (emailInput) {
            emailInput.classList.remove('error');
        }
        const errorMsg = modal.querySelector('.error-message');
        if (errorMsg) {
            errorMsg.textContent = '';
        }
    }

    // メールバリデーション
    function validateEmail() {
        if (!emailInput) return true;

        const email = emailInput.value.trim();
        const errorMsg = modal.querySelector('.error-message');

        if (!email) {
            showError('メールアドレスを入力してください。');
            return false;
        }

        // メールフォーマットチェック
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showError('有効なメールアドレスを入力してください。');
            return false;
        }

        clearError();
        return true;
    }

    // エラー表示
    function showError(message) {
        if (emailInput) {
            emailInput.classList.add('error');
        }
        const errorMsg = modal.querySelector('.error-message');
        if (errorMsg) {
            errorMsg.textContent = message;
        }
    }

    // フォーム送信処理
    function handleSubmit(e) {
        e.preventDefault();

        if (!validateEmail()) {
            return;
        }

        const email = emailInput.value.trim();
        const nonce = form.querySelector('#download_nonce').value;
        const downloadUrl = form.querySelector('input[name="download_url"]').value;

        // ボタンをローディング状態に
        submitBtn.disabled = true;
        submitBtn.querySelector('.btn-text').style.display = 'none';
        submitBtn.querySelector('.btn-loading').style.display = 'flex';

        // AJAX送信
        const formData = new URLSearchParams();
        formData.append('action', 'process_download_form');
        formData.append('email', email);
        formData.append('nonce', nonce);
        formData.append('download_url', downloadUrl);

        fetch(corporate_seo_pro_ajax.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: formData.toString()
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                // GA4イベント（ダウンロード完了）
                if (typeof gtag === 'function') {
                    gtag('event', 'download_material', {
                        event_category: 'lead_generation',
                        event_label: 'company_presentation',
                        email_domain: email.split('@')[1] || 'unknown'
                    });
                }

                // 成功画面を表示
                showSuccess();

                // ダウンロードを開始（新しいタブで開く）
                window.open(downloadUrl, '_blank', 'noopener,noreferrer');
            } else {
                // エラー表示
                showError(data.data.message || 'エラーが発生しました。');
                resetButton();
            }
        })
        .catch(function(error) {
            console.error('Download form error:', error);
            showError('通信エラーが発生しました。再度お試しください。');
            resetButton();
        });
    }

    // ボタン状態リセット
    function resetButton() {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.querySelector('.btn-text').style.display = 'flex';
            submitBtn.querySelector('.btn-loading').style.display = 'none';
        }
    }

    // 成功画面表示
    function showSuccess() {
        if (formView) {
            formView.style.display = 'none';
        }
        if (successView) {
            successView.style.display = 'block';
        }

        // プライバシーリンクも非表示
        const privacyNote = modal.querySelector('.download-privacy');
        if (privacyNote) {
            privacyNote.style.display = 'none';
        }
    }

    // DOMContentLoaded時に初期化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // =====================================================
    // ブログ記事CTA用のダウンロードフォーム処理
    // =====================================================

    /**
     * ブログCTAフォームの初期化
     */
    function initBlogDownloadForm() {
        const blogForm = document.getElementById('blog-download-form');
        if (!blogForm) return;

        const emailInput = blogForm.querySelector('input[name="email"]');
        const submitBtn = blogForm.querySelector('.blog-download-cta-submit');
        const errorEl = blogForm.querySelector('.blog-download-cta-error');

        // フォーム送信イベント
        blogForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleBlogFormSubmit(blogForm, emailInput, submitBtn, errorEl);
        });

        // 入力時にエラーをクリア
        if (emailInput) {
            emailInput.addEventListener('input', function() {
                if (errorEl) {
                    errorEl.textContent = '';
                    errorEl.style.display = 'none';
                }
                emailInput.classList.remove('error');
            });
        }
    }

    /**
     * ブログCTAフォームの送信処理
     */
    function handleBlogFormSubmit(form, emailInput, submitBtn, errorEl) {
        const email = emailInput.value.trim();

        // バリデーション
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email || !emailRegex.test(email)) {
            showBlogFormError(errorEl, emailInput, '有効なメールアドレスを入力してください。');
            return;
        }

        // フォームデータ取得
        const nonce = form.querySelector('#download_nonce').value;
        const downloadUrl = form.querySelector('input[name="download_url"]').value;
        const source = form.querySelector('input[name="source"]').value;
        const sourcePostId = form.querySelector('input[name="source_post_id"]').value;

        // ローディング表示
        submitBtn.disabled = true;
        submitBtn.querySelector('.btn-text').style.display = 'none';
        submitBtn.querySelector('.btn-loading').style.display = 'inline-flex';

        // AJAX送信
        const formData = new URLSearchParams();
        formData.append('action', 'process_download_form');
        formData.append('email', email);
        formData.append('nonce', nonce);
        formData.append('download_url', downloadUrl);
        formData.append('source', source);
        formData.append('source_post_id', sourcePostId);

        fetch(corporate_seo_pro_ajax.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: formData.toString()
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                // GA4イベント（ダウンロード完了）
                if (typeof gtag === 'function') {
                    gtag('event', 'download_material', {
                        event_category: 'lead_generation',
                        event_label: 'blog_article_download',
                        source_post_id: sourcePostId,
                        email_domain: email.split('@')[1] || 'unknown'
                    });
                }

                // 成功表示
                showBlogFormSuccess(form);

                // ダウンロードを開始（新しいタブで開く）
                window.open(downloadUrl, '_blank', 'noopener,noreferrer');
            } else {
                // エラー表示
                showBlogFormError(errorEl, emailInput, data.data.message || 'エラーが発生しました。');
                resetBlogFormButton(submitBtn);
            }
        })
        .catch(function(error) {
            console.error('Blog download form error:', error);
            showBlogFormError(errorEl, emailInput, '通信エラーが発生しました。再度お試しください。');
            resetBlogFormButton(submitBtn);
        });
    }

    /**
     * ブログフォームのエラー表示
     */
    function showBlogFormError(errorEl, emailInput, message) {
        if (errorEl) {
            errorEl.textContent = message;
            errorEl.style.display = 'block';
        }
        if (emailInput) {
            emailInput.classList.add('error');
        }
    }

    /**
     * ブログフォームのボタン状態リセット
     */
    function resetBlogFormButton(submitBtn) {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.querySelector('.btn-text').style.display = 'inline-flex';
            submitBtn.querySelector('.btn-loading').style.display = 'none';
        }
    }

    /**
     * ブログフォームの成功表示
     */
    function showBlogFormSuccess(form) {
        const section = form.closest('.blog-download-cta-card');
        if (!section) return;

        // フォームを非表示
        form.style.display = 'none';

        // プライバシーポリシーも非表示
        const privacyEl = section.querySelector('.blog-download-cta-privacy');
        if (privacyEl) {
            privacyEl.style.display = 'none';
        }

        // 成功メッセージを表示
        const successEl = section.querySelector('.blog-download-cta-success');
        if (successEl) {
            successEl.style.display = 'flex';
        }
    }

    // ブログCTAフォームの初期化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initBlogDownloadForm);
    } else {
        initBlogDownloadForm();
    }
})();
