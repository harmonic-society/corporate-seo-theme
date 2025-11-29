<?php
/**
 * Download Modal Template
 *
 * 資料ダウンロード用モーダル
 * メールアドレス入力後にダウンロードリンクを表示
 *
 * @package Corporate_SEO_Pro
 */

// ダウンロードURL（Google Drive）
$download_url = 'https://docs.google.com/presentation/d/17HIvwsf-T2UUZzOQrGhi_QvHIE919OJA/edit?usp=sharing&ouid=103237259871738523873&rtpof=true&sd=true';
?>

<div id="download-modal" class="download-modal" aria-hidden="true" role="dialog" aria-labelledby="download-modal-title">
    <div class="download-modal-overlay" tabindex="-1"></div>
    <div class="download-modal-content">
        <!-- 閉じるボタン -->
        <button type="button" class="download-modal-close" aria-label="<?php esc_attr_e( '閉じる', 'corporate-seo-pro' ); ?>">
            <span></span>
            <span></span>
        </button>

        <!-- モーダルヘッダー -->
        <div class="download-modal-header">
            <div class="download-modal-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <h3 id="download-modal-title"><?php esc_html_e( '無料資料ダウンロード', 'corporate-seo-pro' ); ?></h3>
        </div>

        <!-- 説明文 -->
        <p class="download-modal-description">
            <?php esc_html_e( 'メールアドレスをご入力いただくと、すぐに資料をダウンロードいただけます。', 'corporate-seo-pro' ); ?>
        </p>

        <!-- フォーム -->
        <form id="download-form" class="download-form" novalidate>
            <?php wp_nonce_field( 'download_form_nonce', 'download_nonce' ); ?>
            <input type="hidden" name="download_url" value="<?php echo esc_url( $download_url ); ?>">

            <div class="form-group">
                <label for="download-email">
                    <?php esc_html_e( 'メールアドレス', 'corporate-seo-pro' ); ?>
                    <span class="required">*</span>
                </label>
                <input
                    type="email"
                    id="download-email"
                    name="email"
                    required
                    placeholder="example@company.co.jp"
                    autocomplete="email"
                >
                <span class="error-message" aria-live="polite"></span>
            </div>

            <button type="submit" class="download-submit-btn">
                <span class="btn-text">
                    <i class="fas fa-download"></i>
                    <?php esc_html_e( '資料をダウンロード', 'corporate-seo-pro' ); ?>
                </span>
                <span class="btn-loading" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i>
                    <?php esc_html_e( '送信中...', 'corporate-seo-pro' ); ?>
                </span>
            </button>
        </form>

        <!-- プライバシーポリシーリンク -->
        <p class="download-privacy">
            <?php
            printf(
                /* translators: %s: privacy policy link */
                esc_html__( '%sに同意の上、送信してください。', 'corporate-seo-pro' ),
                '<a href="' . esc_url( home_url( '/privacy-policy/' ) ) . '" target="_blank" rel="noopener">' . esc_html__( 'プライバシーポリシー', 'corporate-seo-pro' ) . '</a>'
            );
            ?>
        </p>

        <!-- 成功メッセージ（初期非表示） -->
        <div class="download-success" style="display: none;">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h4><?php esc_html_e( 'ありがとうございます！', 'corporate-seo-pro' ); ?></h4>
            <p><?php esc_html_e( '資料のダウンロードが開始されました。', 'corporate-seo-pro' ); ?></p>
            <button type="button" class="download-close-btn">
                <?php esc_html_e( '閉じる', 'corporate-seo-pro' ); ?>
            </button>
        </div>
    </div>
</div>
