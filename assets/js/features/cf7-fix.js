/**
 * Contact Form 7 Fix
 * フォーム送信の問題を修正
 */
(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        console.log('CF7 Fix: Initializing...');
        
        // Contact Form 7のグローバルオブジェクトを確認
        if (typeof wpcf7 === 'undefined') {
            console.error('CF7 Fix: wpcf7 object not found. Contact Form 7 may not be loaded properly.');
            return;
        }
        
        // APIの設定を確認
        console.log('CF7 Fix: API Settings:', wpcf7.apiSettings);
        
        // フォームを取得
        const forms = document.querySelectorAll('.wpcf7 form');
        console.log('CF7 Fix: Found forms:', forms.length);
        
        forms.forEach((form, index) => {
            console.log(`CF7 Fix: Processing form ${index + 1}`, form);
            
            // フォームの属性を確認
            const formId = form.closest('.wpcf7').getAttribute('id');
            console.log('CF7 Fix: Form wrapper ID:', formId);
            
            // data-wpcf7-id属性を確認（重要）
            const cf7Id = form.querySelector('input[name="_wpcf7"]');
            if (cf7Id) {
                console.log('CF7 Fix: Form ID input value:', cf7Id.value);
            } else {
                console.error('CF7 Fix: Missing _wpcf7 hidden input field');
            }
            
            // 送信ボタンを確認
            const submitButtons = form.querySelectorAll('input[type="submit"], button[type="submit"]');
            console.log('CF7 Fix: Submit buttons found:', submitButtons.length);
            
            submitButtons.forEach((button) => {
                console.log('CF7 Fix: Submit button:', {
                    type: button.type,
                    value: button.value,
                    disabled: button.disabled,
                    className: button.className
                });
                
                // 送信ボタンのクリックイベントを監視
                button.addEventListener('click', function(e) {
                    console.log('CF7 Fix: Submit button clicked');
                    
                    // フォームの状態を確認
                    const formElement = this.closest('form');
                    console.log('CF7 Fix: Form element:', formElement);
                    console.log('CF7 Fix: Form action:', formElement.action);
                    console.log('CF7 Fix: Form method:', formElement.method);
                    
                    // バリデーションの状態を確認
                    const invalidFields = formElement.querySelectorAll(':invalid');
                    if (invalidFields.length > 0) {
                        console.log('CF7 Fix: Invalid fields:', invalidFields.length);
                        invalidFields.forEach((field) => {
                            console.log('CF7 Fix: Invalid field:', {
                                name: field.name,
                                type: field.type,
                                value: field.value,
                                validationMessage: field.validationMessage
                            });
                        });
                    }
                }, true); // キャプチャフェーズで実行
            });
            
            // フォーム送信イベントを監視
            form.addEventListener('submit', function(e) {
                console.log('CF7 Fix: Form submit event fired');
                console.log('CF7 Fix: Form data:', new FormData(this));
            }, true);
            
            // wpcf7submit イベントを手動でトリガーするヘルパー関数
            window.triggerCF7Submit = function() {
                const event = new CustomEvent('wpcf7submit', {
                    detail: {
                        id: formId,
                        inputs: Array.from(form.elements).map(el => ({
                            name: el.name,
                            value: el.value
                        }))
                    }
                });
                document.dispatchEvent(event);
                console.log('CF7 Fix: Manually triggered wpcf7submit event');
            };
        });
        
        // AJAX設定の確認
        if (window.jQuery && jQuery.ajaxSetup) {
            console.log('CF7 Fix: jQuery AJAX settings:', jQuery.ajaxSetup());
        }
        
        // フォームの初期化を再実行
        if (typeof wpcf7.init === 'function') {
            console.log('CF7 Fix: Re-initializing CF7 forms...');
            document.querySelectorAll('.wpcf7 > form').forEach(form => {
                wpcf7.init(form);
            });
        }
        
        // REST APIのテスト
        if (wpcf7.apiSettings && wpcf7.apiSettings.root) {
            const testUrl = wpcf7.apiSettings.root + 'contact-form-7/v1/contact-forms';
            console.log('CF7 Fix: Testing REST API:', testUrl);
            
            fetch(testUrl, {
                headers: {
                    'X-WP-Nonce': wpcf7.apiSettings.nonce || ''
                }
            })
            .then(response => {
                console.log('CF7 Fix: REST API response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('CF7 Fix: REST API data:', data);
            })
            .catch(error => {
                console.error('CF7 Fix: REST API error:', error);
            });
        }
        
        // すべてのCF7イベントを監視
        const cf7Events = [
            'wpcf7invalid',
            'wpcf7spam',
            'wpcf7mailsent',
            'wpcf7mailfailed',
            'wpcf7submit',
            'wpcf7beforesubmit'
        ];
        
        cf7Events.forEach(eventName => {
            document.addEventListener(eventName, function(event) {
                console.log(`CF7 Fix: Event ${eventName} fired:`, event.detail);
            });
        });
        
        // フォールバック送信処理
        window.submitCF7Fallback = function() {
            const form = document.querySelector('.wpcf7 form');
            if (!form) {
                console.error('CF7 Fix: No form found');
                return;
            }
            
            const formData = new FormData(form);
            const submitUrl = form.action || wpcf7.apiSettings.root + 'contact-form-7/v1/contact-forms/' + form.querySelector('input[name="_wpcf7"]').value + '/feedback';
            
            console.log('CF7 Fix: Attempting fallback submission to:', submitUrl);
            
            fetch(submitUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-WP-Nonce': wpcf7.apiSettings.nonce || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('CF7 Fix: Fallback submission response:', data);
                if (data.status === 'mail_sent') {
                    window.location.href = '/thanks/';
                }
            })
            .catch(error => {
                console.error('CF7 Fix: Fallback submission error:', error);
            });
        };
    });
    
})();