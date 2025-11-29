<?php
/**
 * メルマガ登録CTAセクション
 *
 * @package Corporate_SEO_Pro
 */
?>

<section class="newsletter-cta">
    <div class="newsletter-cta-background">
        <div class="newsletter-cta-gradient"></div>
        <div class="newsletter-cta-pattern"></div>
        <div class="newsletter-cta-glow"></div>
    </div>

    <div class="container">
        <div class="newsletter-cta-content">
            <div class="newsletter-cta-icon">
                <i class="fas fa-envelope-open-text"></i>
            </div>

            <h2 class="newsletter-cta-title">
                最新の業務改善ノウハウを<br class="sp-only">メールでお届けします
            </h2>

            <p class="newsletter-cta-description">
                毎朝、新着記事の更新情報をお届け。<br>
                業務効率化・システム開発のヒントを見逃さない。
            </p>

            <form id="newsletter-form" class="newsletter-form">
                <?php wp_nonce_field( 'newsletter_subscribe', 'newsletter_nonce' ); ?>
                <div class="newsletter-form-group">
                    <input type="email"
                           name="email"
                           id="newsletter-email"
                           required
                           placeholder="メールアドレスを入力"
                           autocomplete="email">
                    <button type="submit" class="newsletter-submit-btn">
                        <span class="btn-text"><?php esc_html_e( '登録する', 'corporate-seo-pro' ); ?></span>
                        <span class="btn-icon"><i class="fas fa-paper-plane"></i></span>
                        <span class="btn-loading"><i class="fas fa-spinner fa-spin"></i></span>
                    </button>
                </div>
                <div class="newsletter-form-message" aria-live="polite"></div>
            </form>

            <p class="newsletter-privacy">
                <i class="fas fa-lock"></i>
                <?php esc_html_e( 'プライバシーポリシーに基づき、安全に管理します', 'corporate-seo-pro' ); ?>
            </p>
        </div>
    </div>

    <div class="newsletter-particles">
        <div class="newsletter-particle"></div>
        <div class="newsletter-particle"></div>
        <div class="newsletter-particle"></div>
        <div class="newsletter-particle"></div>
        <div class="newsletter-particle"></div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('newsletter-form');
    const messageEl = form.querySelector('.newsletter-form-message');
    const submitBtn = form.querySelector('.newsletter-submit-btn');
    const emailInput = form.querySelector('#newsletter-email');

    if (!form) return;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const email = emailInput.value.trim();
        const nonce = form.querySelector('#newsletter_nonce').value;

        if (!email) {
            showMessage('メールアドレスを入力してください', 'error');
            return;
        }

        // ローディング状態
        submitBtn.classList.add('is-loading');
        submitBtn.disabled = true;

        try {
            const formData = new FormData();
            formData.append('action', 'newsletter_subscribe');
            formData.append('email', email);
            formData.append('nonce', nonce);

            const response = await fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                showMessage(result.data.message, 'success');
                form.reset();

                // GA4イベント（もしgtag関数があれば）
                if (typeof gtag === 'function') {
                    gtag('event', 'newsletter_subscribe', {
                        event_category: 'lead_generation',
                        event_label: 'blog_cta'
                    });
                }
            } else {
                showMessage(result.data.message || 'エラーが発生しました', 'error');
            }
        } catch (error) {
            console.error('Newsletter subscription error:', error);
            showMessage('通信エラーが発生しました。もう一度お試しください。', 'error');
        } finally {
            submitBtn.classList.remove('is-loading');
            submitBtn.disabled = false;
        }
    });

    function showMessage(message, type) {
        messageEl.textContent = message;
        messageEl.className = 'newsletter-form-message ' + type;

        // 成功メッセージは5秒後に消す
        if (type === 'success') {
            setTimeout(() => {
                messageEl.textContent = '';
                messageEl.className = 'newsletter-form-message';
            }, 5000);
        }
    }
});
</script>
