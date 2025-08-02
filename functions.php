<?php
/**
 * Corporate SEO Pro Theme Functions
 *
 * @package Corporate_SEO_Pro
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Define theme constants
 */
define( 'CORPORATE_SEO_PRO_VERSION', wp_get_theme()->get( 'Version' ) );
define( 'CORPORATE_SEO_PRO_DIR', get_template_directory() );
define( 'CORPORATE_SEO_PRO_URI', get_template_directory_uri() );

/**
 * Load theme includes via autoloader
 * 
 * This autoloader manages all theme functionality includes
 * making the functions.php file cleaner and more maintainable
 */
require_once CORPORATE_SEO_PRO_DIR . '/inc/autoloader.php';

/**
 * Theme initialization
 * 
 * All theme functionality is now modularized into separate files:
 * 
 * - /inc/theme-setup.php       : Basic theme setup and support
 * - /inc/navigation.php        : Menu registration and management
 * - /inc/assets.php           : Scripts and styles enqueuing
 * - /inc/widgets.php          : Widget areas registration
 * - /inc/post-types.php       : Custom post types and taxonomies
 * - /inc/template-functions.php: Template helper functions
 * - /inc/filters.php          : WordPress filters and modifications
 * - /inc/customizer.php       : Theme customizer settings
 * - /inc/seo-functions.php    : SEO related functionality
 * - /inc/performance.php      : Performance optimizations
 * - /inc/structured-data.php  : Schema.org structured data
 * - /inc/class-nav-walker.php : Custom navigation walker
 * - /inc/class-mobile-menu-walker.php : Mobile menu walker
 */

/**
 * Custom functions that don't fit into other categories
 * can be added below this comment
 */

// Add any theme-specific custom functions here if needed

// Check if ACF is active and show notice if not
add_action('admin_notices', 'corporate_seo_pro_acf_notice');
function corporate_seo_pro_acf_notice() {
    if ( ! function_exists('get_field') && current_user_can('manage_options') ) {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p><?php _e('Corporate SEO Pro テーマは Advanced Custom Fields Pro プラグインが必要です。プラグインをインストール・有効化してください。', 'corporate-seo-pro'); ?></p>
        </div>
        <?php
    }
}

/**
 * フォールバックフォームの処理
 */
add_action( 'admin_post_corporate_seo_pro_contact_form', 'corporate_seo_pro_handle_contact_form' );
add_action( 'admin_post_nopriv_corporate_seo_pro_contact_form', 'corporate_seo_pro_handle_contact_form' );

function corporate_seo_pro_handle_contact_form() {
    // Nonceチェック
    if ( ! isset( $_POST['contact_form_nonce'] ) || ! wp_verify_nonce( $_POST['contact_form_nonce'], 'corporate_seo_pro_contact_form' ) ) {
        wp_die( 'セキュリティチェックに失敗しました。' );
    }
    
    // データの取得とサニタイズ
    $name    = sanitize_text_field( $_POST['contact_name'] );
    $email   = sanitize_email( $_POST['contact_email'] );
    $phone   = sanitize_text_field( $_POST['contact_phone'] );
    $subject = sanitize_text_field( $_POST['contact_subject'] );
    $message = sanitize_textarea_field( $_POST['contact_message'] );
    
    // メール送信先の取得
    $to = get_option( 'admin_email' );
    
    // メールヘッダー
    $headers = array(
        'From: ' . $name . ' <' . $email . '>',
        'Reply-To: ' . $email,
        'Content-Type: text/plain; charset=UTF-8'
    );
    
    // メール本文
    $body = "お名前: " . $name . "\n";
    $body .= "メールアドレス: " . $email . "\n";
    if ( ! empty( $phone ) ) {
        $body .= "電話番号: " . $phone . "\n";
    }
    $body .= "件名: " . $subject . "\n\n";
    $body .= "お問い合わせ内容:\n" . $message;
    
    // メール送信
    $sent = wp_mail( $to, '[お問い合わせ] ' . $subject, $body, $headers );
    
    // リダイレクト先のURL
    $redirect_url = wp_get_referer() ? wp_get_referer() : home_url();
    
    if ( $sent ) {
        // 成功時のメッセージをセッションに保存（または一時的なオプションとして保存）
        set_transient( 'contact_form_message_' . session_id(), '送信が完了しました。お問い合わせありがとうございます。', 60 );
        $redirect_url = add_query_arg( 'contact_sent', 'success', $redirect_url );
    } else {
        // エラー時のメッセージ
        set_transient( 'contact_form_message_' . session_id(), '送信に失敗しました。もう一度お試しください。', 60 );
        $redirect_url = add_query_arg( 'contact_sent', 'error', $redirect_url );
    }
    
    wp_redirect( $redirect_url );
    exit;
}