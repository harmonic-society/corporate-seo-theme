/**
 * Contact Form Fallback JavaScript
 * PageSpeed Module対応版
 */
(function() {
    'use strict';

    console.log('Contact form fallback script loaded');

    // グローバルにcontact_ajaxオブジェクトが定義されていない場合のフォールバック
    if (typeof contact_ajax === 'undefined') {
        window.contact_ajax = {
            ajax_url: '/wp-admin/admin-ajax.php',
            nonce: ''
        };
    }

    // DOM要素
    let form, submitButton, messageArea;
    let isSubmitting = false;

    // 初期化（jQueryを使わない版）
    function init() {
        console.log('Initializing contact form...');
        
        form = document.getElementById('custom-contact-form');
        if (!form) {
            console.error('Contact form not found!');
            // フォームが見つからない場合、少し待ってから再試行
            setTimeout(init, 100);
            return;
        }
        
        console.log('Form found:', form);
        submitButton = document.getElementById('submit-button');
        messageArea = form.querySelector('.form-messages');

        // イベントリスナーの設定
        setupEventListeners();
        
        // 文字数カウンターの初期化
        initCharacterCounter();
    }

    /**
     * イベントリスナーの設定
     */
    function setupEventListeners() {
        // フォーム送信
        form.addEventListener('submit', handleFormSubmit);
        console.log('Form submit listener attached');

        // リアルタイムバリデーション
        const inputs = form.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });

            input.addEventListener('input', function() {
                if (this.classList.contains('error')) {
                    validateField(this);
                }
            });
        });

        // チェックボックスのバリデーション
        const checkbox = form.querySelector('input[type="checkbox"]');
        if (checkbox) {
            checkbox.addEventListener('change', function() {
                validateField(this);
            });
        }
    }

    /**
     * フォーム送信処理（非同期を使わない版）
     */
    function handleFormSubmit(e) {
        e.preventDefault();
        console.log('Form submit triggered');

        // 重複送信防止
        if (isSubmitting) {
            console.log('Already submitting');
            return;
        }

        // 全フィールドのバリデーション
        const isValid = validateForm();
        if (!isValid) {
            showMessage('入力内容に誤りがあります。赤色で表示されている項目を確認してください。', 'error');
            return;
        }

        isSubmitting = true;
        setSubmitButtonState('loading');

        // FormDataの作成
        const formData = new FormData(form);

        // XMLHttpRequestを使用（fetchの代わり）
        const xhr = new XMLHttpRequest();
        xhr.open('POST', contact_ajax.ajax_url || '/wp-admin/admin-ajax.php');
        
        xhr.onload = function() {
            console.log('XHR Response:', xhr.responseText);
            try {
                const data = JSON.parse(xhr.responseText);
                
                if (data.success) {
                    showMessage('お問い合わせを受け付けました。確認画面へ移動します...', 'success');
                    
                    // thanksページへリダイレクト
                    setTimeout(function() {
                        window.location.href = data.data.redirect || '/thanks/';
                    }, 1000);
                } else {
                    // エラー処理
                    if (data.data && data.data.errors) {
                        Object.keys(data.data.errors).forEach(function(fieldName) {
                            const field = form.querySelector('[name="' + fieldName + '"]');
                            if (field) {
                                showFieldError(field, data.data.errors[fieldName]);
                            }
                        });
                    }
                    
                    showMessage(data.data.message || '送信に失敗しました。入力内容を確認してください。', 'error');
                    setSubmitButtonState('default');
                    isSubmitting = false;
                }
            } catch (error) {
                console.error('JSON parse error:', error);
                showMessage('通信エラーが発生しました。時間をおいて再度お試しください。', 'error');
                setSubmitButtonState('default');
                isSubmitting = false;
            }
        };

        xhr.onerror = function() {
            console.error('XHR error');
            showMessage('通信エラーが発生しました。時間をおいて再度お試しください。', 'error');
            setSubmitButtonState('default');
            isSubmitting = false;
        };

        xhr.send(formData);
    }

    /**
     * フォーム全体のバリデーション
     */
    function validateForm() {
        let isValid = true;
        const inputs = form.querySelectorAll('.form-control, input[type="checkbox"]');
        
        inputs.forEach(function(input) {
            if (!validateField(input)) {
                isValid = false;
            }
        });

        return isValid;
    }

    /**
     * フィールドのバリデーション
     */
    function validateField(field) {
        const validation = field.dataset.validation;
        if (!validation) return true;

        const rules = validation.split('|');
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';

        // 各ルールをチェック
        for (let i = 0; i < rules.length; i++) {
            const rule = rules[i];
            const ruleParts = rule.split(':');
            const ruleName = ruleParts[0];
            const ruleValue = ruleParts[1];

            switch (ruleName) {
                case 'required':
                    if (field.type === 'checkbox') {
                        if (!field.checked) {
                            errorMessage = 'この項目は必須です。';
                            isValid = false;
                        }
                    } else if (!value) {
                        errorMessage = 'この項目は必須です。';
                        isValid = false;
                    }
                    break;

                case 'email':
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (value && !emailRegex.test(value)) {
                        errorMessage = '有効なメールアドレスを入力してください。';
                        isValid = false;
                    }
                    break;

                case 'phone':
                    const phoneRegex = /^[0-9\-\+\(\)\s]*$/;
                    if (value && !phoneRegex.test(value)) {
                        errorMessage = '電話番号の形式が正しくありません。';
                        isValid = false;
                    }
                    break;

                case 'min':
                    if (value && value.length < parseInt(ruleValue)) {
                        errorMessage = ruleValue + '文字以上で入力してください。';
                        isValid = false;
                    }
                    break;

                case 'max':
                    if (value && value.length > parseInt(ruleValue)) {
                        errorMessage = ruleValue + '文字以内で入力してください。';
                        isValid = false;
                    }
                    break;
            }

            if (!isValid) break;
        }

        // エラー表示の更新
        if (isValid) {
            clearFieldError(field);
        } else {
            showFieldError(field, errorMessage);
        }

        return isValid;
    }

    /**
     * フィールドエラーの表示
     */
    function showFieldError(field, message) {
        field.classList.add('error');
        field.classList.remove('valid');
        
        const errorElement = field.parentElement.querySelector('.error-message');
        if (errorElement) {
            errorElement.textContent = message;
        }
    }

    /**
     * フィールドエラーのクリア
     */
    function clearFieldError(field) {
        field.classList.remove('error');
        if (field.value) {
            field.classList.add('valid');
        }
        
        const errorElement = field.parentElement.querySelector('.error-message');
        if (errorElement) {
            errorElement.textContent = '';
        }
    }

    /**
     * メッセージの表示
     */
    function showMessage(message, type) {
        messageArea.textContent = message;
        messageArea.className = 'form-messages show ' + type;
        
        // 一定時間後に非表示（エラー以外）
        if (type !== 'error') {
            setTimeout(function() {
                messageArea.classList.remove('show');
            }, 5000);
        }
    }

    /**
     * 送信ボタンの状態変更
     */
    function setSubmitButtonState(state) {
        const btnText = submitButton.querySelector('.btn-text');
        const btnLoading = submitButton.querySelector('.btn-loading');

        switch (state) {
            case 'loading':
                submitButton.disabled = true;
                btnText.style.display = 'none';
                btnLoading.style.display = 'inline-block';
                break;
            case 'default':
                submitButton.disabled = false;
                btnText.style.display = 'inline-block';
                btnLoading.style.display = 'none';
                break;
        }
    }

    /**
     * 文字数カウンターの初期化
     */
    function initCharacterCounter() {
        const textarea = form.querySelector('textarea[name="your_message"]');
        if (!textarea) return;

        const counter = textarea.parentElement.querySelector('.character-count');
        if (!counter) return;

        const currentSpan = counter.querySelector('.current');
        const maxSpan = counter.querySelector('.max');
        const maxLength = 2000;

        // 初期値
        currentSpan.textContent = textarea.value.length;

        // 入力時の更新
        textarea.addEventListener('input', function() {
            const length = this.value.length;
            currentSpan.textContent = length;

            // 色の変更
            if (length > maxLength) {
                counter.classList.add('error');
                counter.classList.remove('warning');
            } else if (length > maxLength * 0.8) {
                counter.classList.add('warning');
                counter.classList.remove('error');
            } else {
                counter.classList.remove('warning', 'error');
            }
        });
    }

    // DOMContentLoadedまたは即座に実行
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        // すでに読み込まれている場合
        init();
    }

})();