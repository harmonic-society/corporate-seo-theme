/**
 * Contact Form 7 Debug Helper
 * フォーム送信の問題をデバッグするためのヘルパー
 */
(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        // Contact Form 7フォームの存在確認
        const cf7Forms = document.querySelectorAll('.wpcf7-form');
        
        if (cf7Forms.length === 0) {
            console.error('Contact Form 7 form not found on this page');
            return;
        }
        
        console.log('Contact Form 7 forms found:', cf7Forms.length);
        
        // 各フォームにデバッグリスナーを追加
        cf7Forms.forEach((form, index) => {
            console.log(`Form ${index + 1} ID:`, form.getAttribute('id'));
            
            // フォーム送信前のチェック
            form.addEventListener('submit', function(e) {
                console.log('Form submit event triggered');
                
                // 必須フィールドのチェック
                const requiredFields = form.querySelectorAll('[required], [aria-required="true"]');
                let hasEmptyFields = false;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        console.warn('Empty required field:', field.name || field.id);
                        hasEmptyFields = true;
                    }
                });
                
                if (hasEmptyFields) {
                    console.error('Form has empty required fields');
                }
            });
        });
        
        // Contact Form 7のすべてのイベントをログ
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
                console.log(`CF7 Event: ${eventName}`, event.detail);
                
                // エラーの詳細を表示
                if (event.detail.apiResponse) {
                    console.log('API Response:', event.detail.apiResponse);
                    
                    if (event.detail.apiResponse.invalid_fields) {
                        console.log('Invalid fields:', event.detail.apiResponse.invalid_fields);
                    }
                    
                    if (event.detail.apiResponse.message) {
                        console.log('Response message:', event.detail.apiResponse.message);
                    }
                }
            });
        });
        
        // AJAXエラーのキャッチ
        if (window.jQuery) {
            jQuery(document).ajaxError(function(event, jqXHR, settings, thrownError) {
                if (settings.url && settings.url.includes('admin-ajax.php')) {
                    console.error('AJAX Error:', {
                        url: settings.url,
                        status: jqXHR.status,
                        statusText: jqXHR.statusText,
                        responseText: jqXHR.responseText,
                        error: thrownError
                    });
                }
            });
        }
        
        // フォームのバリデーション状態をチェック
        setInterval(() => {
            const invalidFields = document.querySelectorAll('.wpcf7-not-valid');
            if (invalidFields.length > 0) {
                console.log('Invalid fields detected:', invalidFields.length);
                invalidFields.forEach(field => {
                    const tip = field.parentElement.querySelector('.wpcf7-not-valid-tip');
                    if (tip) {
                        console.log(`Validation error on ${field.name}:`, tip.textContent);
                    }
                });
            }
        }, 2000);
        
        // REST APIエンドポイントの確認
        if (window.wpcf7) {
            console.log('CF7 Configuration:', {
                apiSettings: window.wpcf7.apiSettings,
                cached: window.wpcf7.cached
            });
            
            // REST APIのテスト
            if (window.wpcf7.apiSettings && window.wpcf7.apiSettings.root) {
                fetch(window.wpcf7.apiSettings.root + 'contact-form-7/v1')
                    .then(response => response.json())
                    .then(data => console.log('CF7 REST API is accessible:', data))
                    .catch(error => console.error('CF7 REST API error:', error));
            }
        }
    });
    
    // グローバル関数として公開（コンソールから手動でテスト可能）
    window.testCF7Form = function() {
        const form = document.querySelector('.wpcf7-form');
        if (!form) {
            console.error('No CF7 form found');
            return;
        }
        
        // フォームデータの取得
        const formData = new FormData(form);
        const data = {};
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        
        console.log('Form data:', data);
        console.log('Form action:', form.getAttribute('action'));
        console.log('Form method:', form.getAttribute('method'));
        
        // 送信ボタンの状態
        const submitButton = form.querySelector('[type="submit"]');
        if (submitButton) {
            console.log('Submit button:', {
                disabled: submitButton.disabled,
                text: submitButton.textContent,
                classes: submitButton.className
            });
        }
    };
    
})();