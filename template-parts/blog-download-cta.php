<?php
/**
 * ブログ記事用ダウンロード資料CTAセクション
 *
 * ACFフィールドで設定された資料をダウンロードするためのCTA
 * メールアドレス入力後にダウンロードリンクを提供
 *
 * @package Corporate_SEO_Pro
 */

// 直接アクセス禁止
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ACFが有効でない場合は表示しない
if ( ! function_exists( 'get_field' ) ) {
    return;
}

// CTAが無効の場合は何も表示しない
$enable_cta = get_field( 'enable_download_cta' );
if ( ! $enable_cta ) {
    return;
}

// URL種別の取得
$url_type = get_field( 'download_url_type' );
$download_url = '';

if ( 'upload' === $url_type ) {
    $download_url = get_field( 'download_file' );
} else {
    $download_url = get_field( 'download_external_url' );
}

// URLがない場合も表示しない
if ( empty( $download_url ) ) {
    return;
}

// その他のフィールド
$cta_title = get_field( 'download_cta_title' );
if ( empty( $cta_title ) ) {
    $cta_title = __( 'この記事の関連資料をダウンロード', 'corporate-seo-pro' );
}

$cta_description = get_field( 'download_cta_description' );
$material_name = get_field( 'download_material_name' );
if ( empty( $material_name ) ) {
    $material_name = __( '無料資料', 'corporate-seo-pro' );
}
?>

<section class="blog-download-cta-section">
    <div class="container">
        <div class="blog-download-cta-card">
            <!-- バッジ -->
            <div class="blog-download-cta-badge">
                <i class="fas fa-gift"></i>
                <?php esc_html_e( '無料ダウンロード', 'corporate-seo-pro' ); ?>
            </div>

            <!-- タイトル -->
            <h3 class="blog-download-cta-title"><?php echo esc_html( $cta_title ); ?></h3>

            <!-- 説明文 -->
            <?php if ( $cta_description ) : ?>
                <p class="blog-download-cta-description"><?php echo esc_html( $cta_description ); ?></p>
            <?php endif; ?>

            <!-- 資料名 -->
            <p class="blog-download-cta-material">
                <i class="fas fa-file-alt"></i>
                <?php echo esc_html( $material_name ); ?>
            </p>

            <!-- フォーム -->
            <form id="blog-download-form" class="blog-download-cta-form" novalidate>
                <?php wp_nonce_field( 'download_form_nonce', 'download_nonce' ); ?>
                <input type="hidden" name="download_url" value="<?php echo esc_url( $download_url ); ?>">
                <input type="hidden" name="source" value="blog_article">
                <input type="hidden" name="source_post_id" value="<?php echo esc_attr( get_the_ID() ); ?>">

                <div class="blog-download-cta-form-row">
                    <div class="blog-download-cta-input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input
                            type="email"
                            name="email"
                            id="blog-download-email"
                            required
                            placeholder="<?php esc_attr_e( 'メールアドレスを入力', 'corporate-seo-pro' ); ?>"
                            autocomplete="email"
                        >
                    </div>
                    <button type="submit" class="blog-download-cta-submit">
                        <span class="btn-text">
                            <i class="fas fa-download"></i>
                            <?php esc_html_e( 'ダウンロード', 'corporate-seo-pro' ); ?>
                        </span>
                        <span class="btn-loading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </button>
                </div>
                <span class="blog-download-cta-error" aria-live="polite"></span>
            </form>

            <!-- プライバシーポリシー -->
            <p class="blog-download-cta-privacy">
                <?php
                printf(
                    /* translators: %s: privacy policy link */
                    esc_html__( '%sに同意の上、送信してください。', 'corporate-seo-pro' ),
                    '<a href="' . esc_url( home_url( '/privacy-policy/' ) ) . '" target="_blank" rel="noopener">' . esc_html__( 'プライバシーポリシー', 'corporate-seo-pro' ) . '</a>'
                );
                ?>
            </p>

            <!-- 成功メッセージ（初期非表示） -->
            <div class="blog-download-cta-success" style="display: none;">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <p><?php esc_html_e( 'ダウンロードが開始されました！', 'corporate-seo-pro' ); ?></p>
            </div>
        </div>
    </div>
</section>
