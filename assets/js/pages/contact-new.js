/**
 * Contact Form JavaScript
 * Ajax送信とリアルタイムバリデーション
 */
(function() {
    'use strict';

    // DOM要素
    let form, submitButton, messageArea;
    let isSubmitting = false;

    // 初期化
    document.addEventListener('DOMContentLoaded', function() {
        form = document.getElementById('custom-contact-form');
        if (!form) return;

        submitButton = document.getElementById('submit-button');
        messageArea = form.querySelector('.form-messages');

        // イベントリスナーの設定
        setupEventListeners();
        
        // 文字数カウンターの初期化
        initCharacterCounter();
        
        // ヒーローアニメーションなど既存の機能を維持
        initExistingFeatures();
    });

    /**
     * イベントリスナーの設定
     */
    function setupEventListeners() {
        // フォーム送信
        form.addEventListener('submit', handleFormSubmit);

        // リアルタイムバリデーション
        const inputs = form.querySelectorAll('.form-control');
        inputs.forEach(input => {
            // フォーカスアウト時のバリデーション
            input.addEventListener('blur', function() {
                validateField(this);
            });

            // 入力時のバリデーション（エラー状態の場合のみ）
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
     * フォーム送信処理
     */
    async function handleFormSubmit(e) {
        e.preventDefault();

        // 重複送信防止
        if (isSubmitting) return;

        // 全フィールドのバリデーション
        const isValid = validateForm();
        if (!isValid) {
            showMessage('入力内容に誤りがあります。赤色で表示されている項目を確認してください。', 'error');
            return;
        }

        isSubmitting = true;
        setSubmitButtonState('loading');

        try {
            // FormDataの作成
            const formData = new FormData(form);
            
            // Ajax送信
            const response = await fetch(contact_ajax.ajax_url, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            });

            const data = await response.json();

            if (data.success) {
                // 成功時の処理
                showMessage('お問い合わせを受け付けました。確認画面へ移動します...', 'success');
                
                // GAイベントの送信（もしGAが設定されていれば）
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'contact_form_submit', {
                        'event_category': 'engagement',
                        'event_label': formData.get('inquiry_type')
                    });
                }

                // thanksページへリダイレクト
                setTimeout(() => {
                    window.location.href = data.data.redirect || '/thanks/';
                }, 1000);
            } else {
                // エラー時の処理
                if (data.data && data.data.errors) {
                    // フィールドごとのエラー表示
                    Object.keys(data.data.errors).forEach(fieldName => {
                        const field = form.querySelector(`[name="${fieldName}"]`);
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
            console.error('Form submission error:', error);
            showMessage('通信エラーが発生しました。時間をおいて再度お試しください。', 'error');
            setSubmitButtonState('default');
            isSubmitting = false;
        }
    }

    /**
     * フォーム全体のバリデーション
     */
    function validateForm() {
        let isValid = true;
        const inputs = form.querySelectorAll('.form-control, input[type="checkbox"]');
        
        inputs.forEach(input => {
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
        for (const rule of rules) {
            const [ruleName, ruleValue] = rule.split(':');

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
                        errorMessage = `${ruleValue}文字以上で入力してください。`;
                        isValid = false;
                    }
                    break;

                case 'max':
                    if (value && value.length > parseInt(ruleValue)) {
                        errorMessage = `${ruleValue}文字以内で入力してください。`;
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
            setTimeout(() => {
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

    /**
     * 既存機能の初期化（hero animations等）
     */
    function initExistingFeatures() {
        // Count Up Animation
        const countElements = document.querySelectorAll('.contact-hero .count-up');
        if (countElements.length > 0) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const element = entry.target;
                        const target = parseInt(element.dataset.target);
                        const duration = 2000;
                        const increment = target / (duration / 16);
                        let current = 0;
                        
                        const timer = setInterval(() => {
                            current += increment;
                            if (current >= target) {
                                current = target;
                                clearInterval(timer);
                            }
                            element.textContent = Math.floor(current);
                        }, 16);
                        
                        observer.unobserve(element);
                    }
                });
            }, { threshold: 0.5 });
            
            countElements.forEach(element => {
                observer.observe(element);
            });
        }

        // FAQ Accordion
        const faqQuestions = document.querySelectorAll('.faq-question');
        faqQuestions.forEach(question => {
            question.addEventListener('click', function() {
                const isOpen = this.getAttribute('aria-expanded') === 'true';
                
                // Close all other questions
                faqQuestions.forEach(q => {
                    if (q !== this) {
                        q.setAttribute('aria-expanded', 'false');
                    }
                });
                
                // Toggle current question
                this.setAttribute('aria-expanded', !isOpen);
            });
        });

        // Smooth Scroll
        const scrollPrompt = document.querySelector('.scroll-prompt');
        if (scrollPrompt) {
            scrollPrompt.addEventListener('click', function() {
                const contactMain = document.querySelector('.contact-main');
                if (contactMain) {
                    contactMain.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        }
    }

})();