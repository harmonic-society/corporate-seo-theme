/**
 * Contact Form 7 Duplicate Submission Prevention
 * フォームの重複送信を防ぐ
 */
(function() {
    'use strict';

    let isSubmitting = false;
    let submissionCount = 0;
    const submissionTimestamps = [];
    const MIN_SUBMISSION_INTERVAL = 3000; // 最小送信間隔（ミリ秒）

    document.addEventListener('DOMContentLoaded', function() {
        console.log('CF7 Duplicate Prevention: Initializing...');
        
        // Contact Form 7のグローバルオブジェクトを確認
        if (typeof wpcf7 === 'undefined') {
            console.error('CF7 Duplicate Prevention: wpcf7 object not found');
            return;
        }
        
        // フォームを取得
        const forms = document.querySelectorAll('.wpcf7 form');
        console.log('CF7 Duplicate Prevention: Found forms:', forms.length);
        
        forms.forEach((form, index) => {
            console.log(`CF7 Duplicate Prevention: Processing form ${index + 1}`);
            
            // 送信ボタンを取得
            const submitButtons = form.querySelectorAll('input[type="submit"], button[type="submit"]');
            
            // フォーム送信前のイベント
            document.addEventListener('wpcf7beforesubmit', function(event) {
                if (event.target === form) {
                    const now = Date.now();
                    submissionCount++;
                    submissionTimestamps.push(now);
                    
                    console.log('CF7 Duplicate Prevention: Before submit event fired. Count:', submissionCount);
                    console.log('CF7 Duplicate Prevention: Timestamps:', submissionTimestamps);
                    
                    // 最近の送信をチェック
                    const recentSubmissions = submissionTimestamps.filter(ts => now - ts < MIN_SUBMISSION_INTERVAL);
                    if (recentSubmissions.length > 1) {
                        console.error('CF7 Duplicate Prevention: Multiple submissions detected within', MIN_SUBMISSION_INTERVAL, 'ms!');
                        event.preventDefault();
                        event.stopImmediatePropagation();
                        return false;
                    }
                    
                    if (isSubmitting) {
                        console.warn('CF7 Duplicate Prevention: Blocking duplicate submission!');
                        event.preventDefault();
                        event.stopImmediatePropagation();
                        return false;
                    }
                    
                    isSubmitting = true;
                    
                    // 送信ボタンを無効化
                    submitButtons.forEach(button => {
                        button.disabled = true;
                        button.style.opacity = '0.6';
                        button.style.cursor = 'not-allowed';
                        // ボタンのテキストを変更
                        button.dataset.originalText = button.value || button.textContent;
                        if (button.tagName === 'INPUT') {
                            button.value = '送信中...';
                        } else {
                            button.textContent = '送信中...';
                        }
                    });
                }
            }, true);
            
            // フォーム送信イベント（実際の送信）
            document.addEventListener('wpcf7submit', function(event) {
                if (event.target === form) {
                    console.log('CF7 Duplicate Prevention: Submit event fired. Status:', event.detail.status);
                }
            }, true);
            
            // 送信完了後のイベント
            const resetForm = function(event) {
                if (event.target === form) {
                    console.log('CF7 Duplicate Prevention: Resetting form state. Event type:', event.type);
                    isSubmitting = false;
                    
                    // 送信ボタンを再度有効化
                    setTimeout(() => {
                        submitButtons.forEach(button => {
                            button.disabled = false;
                            button.style.opacity = '';
                            button.style.cursor = '';
                            // ボタンのテキストを元に戻す
                            if (button.dataset.originalText) {
                                if (button.tagName === 'INPUT') {
                                    button.value = button.dataset.originalText;
                                } else {
                                    button.textContent = button.dataset.originalText;
                                }
                                delete button.dataset.originalText;
                            }
                        });
                    }, 2000); // 2秒後に再度有効化
                }
            };
            
            // 各種完了イベントでリセット
            document.addEventListener('wpcf7invalid', resetForm);
            document.addEventListener('wpcf7spam', resetForm);
            document.addEventListener('wpcf7mailsent', resetForm);
            document.addEventListener('wpcf7mailfailed', resetForm);
            
            // 送信ボタンのクリックイベントを監視
            submitButtons.forEach((button) => {
                button.addEventListener('click', function(e) {
                    console.log('CF7 Duplicate Prevention: Submit button clicked. isSubmitting:', isSubmitting);
                    
                    if (isSubmitting) {
                        console.warn('CF7 Duplicate Prevention: Preventing button click during submission');
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                });
            });
            
            // フォームのsubmitイベントも監視
            form.addEventListener('submit', function(e) {
                console.log('CF7 Duplicate Prevention: Form submit event. isSubmitting:', isSubmitting);
                
                if (isSubmitting) {
                    console.warn('CF7 Duplicate Prevention: Preventing form submit');
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            }, true);
        });
        
        // デバッグ情報を5秒ごとに出力
        if (window.location.search.includes('debug=cf7')) {
            setInterval(() => {
                console.log('CF7 Duplicate Prevention Status:', {
                    isSubmitting: isSubmitting,
                    submissionCount: submissionCount,
                    formsCount: document.querySelectorAll('.wpcf7 form').length
                });
            }, 5000);
        }
    });
    
    // グローバル関数として公開（デバッグ用）
    window.CF7DuplicatePrevention = {
        getStatus: function() {
            return {
                isSubmitting: isSubmitting,
                submissionCount: submissionCount,
                timestamps: submissionTimestamps,
                lastSubmission: submissionTimestamps[submissionTimestamps.length - 1] || null
            };
        },
        reset: function() {
            isSubmitting = false;
            submissionCount = 0;
            submissionTimestamps.length = 0;
            console.log('CF7 Duplicate Prevention: Manually reset');
        },
        analyze: function() {
            const now = Date.now();
            console.log('=== CF7 Duplicate Prevention Analysis ===');
            console.log('Current state:', {
                isSubmitting: isSubmitting,
                submissionCount: submissionCount,
                totalTimestamps: submissionTimestamps.length
            });
            
            if (submissionTimestamps.length > 1) {
                const intervals = [];
                for (let i = 1; i < submissionTimestamps.length; i++) {
                    intervals.push(submissionTimestamps[i] - submissionTimestamps[i-1]);
                }
                console.log('Submission intervals (ms):', intervals);
                
                const duplicates = intervals.filter(interval => interval < MIN_SUBMISSION_INTERVAL);
                if (duplicates.length > 0) {
                    console.error('Duplicate submissions detected:', duplicates.length);
                    console.error('Intervals less than', MIN_SUBMISSION_INTERVAL, 'ms:', duplicates);
                }
            }
            console.log('=====================================');
        }
    };
    
})();