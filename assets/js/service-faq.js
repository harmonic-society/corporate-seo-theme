/**
 * サービスFAQアコーディオン機能
 */
document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    if (faqItems.length === 0) return;
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');
        
        if (!question || !answer) return;
        
        // クリックイベントの設定
        question.addEventListener('click', function() {
            const isActive = item.classList.contains('active');
            
            // 他のアイテムを閉じる（オプション：同時に一つだけ開く場合）
            // faqItems.forEach(otherItem => {
            //     if (otherItem !== item) {
            //         otherItem.classList.remove('active');
            //         otherItem.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
            //     }
            // });
            
            // 現在のアイテムをトグル
            if (isActive) {
                item.classList.remove('active');
                question.setAttribute('aria-expanded', 'false');
            } else {
                item.classList.add('active');
                question.setAttribute('aria-expanded', 'true');
            }
        });
        
        // Enterキーでも動作するように
        question.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                question.click();
            }
        });
    });
});