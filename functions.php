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
 * Contact Form 7の緊急修正を読み込み
 */
if (is_admin() && defined('WPCF7_VERSION')) {
    require_once CORPORATE_SEO_PRO_DIR . '/inc/cf7-emergency-fix.php';
    require_once CORPORATE_SEO_PRO_DIR . '/inc/cf7-direct-fix.php';
    require_once CORPORATE_SEO_PRO_DIR . '/inc/cf7-force-fix.php';
    require_once CORPORATE_SEO_PRO_DIR . '/inc/cf7-template-fix.php';
}

/**
 * Load theme includes via autoloader
 * 
 * This autoloader manages all theme functionality includes
 * making the functions.php file cleaner and more maintainable
 */
require_once CORPORATE_SEO_PRO_DIR . '/inc/autoloader.php';

/**
 * シンプルコンタクトフォームを読み込み（CF7の代替）
 */
require_once CORPORATE_SEO_PRO_DIR . '/inc/simple-contact-form.php';

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

/**
 * Contact Form 7の不正な設定を修正（緊急対応）
 */
add_filter('wpcf7_contact_form_properties', function($properties, $contact_form) {
    // additional_settingsが文字列であることを確認
    if (isset($properties['additional_settings']) && is_string($properties['additional_settings'])) {
        // skip_mail: on_sent_okの行を削除
        $properties['additional_settings'] = preg_replace(
            '/skip_mail:\s*on_sent_ok\s*\n?/i', 
            '', 
            $properties['additional_settings']
        );
    }
    
    // messagesプロパティの修正（エラーの原因）
    if (isset($properties['messages']) && is_string($properties['messages'])) {
        // 文字列の場合は配列に変換
        $properties['messages'] = array();
    }
    
    return $properties;
}, 1, 2); // 優先度を1に変更して早期に処理

/**
 * Contact Form 7のメッセージ設定を修正
 */
add_filter('wpcf7_messages', function($messages) {
    // メッセージが配列でない場合は修正
    if (!is_array($messages)) {
        $messages = array(
            'mail_sent_ok' => 'ありがとうございます。メッセージは送信されました。',
            'mail_sent_ng' => 'メッセージの送信に失敗しました。後でまたお試しください。',
            'validation_error' => '入力内容に問題があります。確認して再度お試しください。',
            'spam' => 'メッセージの送信に失敗しました。後でまたお試しください。',
            'accept_terms' => '承諾が必要です。',
            'invalid_required' => '必須項目です。',
            'invalid_too_long' => '入力された文字列が長すぎます。',
            'invalid_too_short' => '入力された文字列が短すぎます。',
        );
    }
    return $messages;
}, 1);

/**
 * Contact Form 7 送信後のリダイレクト処理
 */
add_action( 'wp_footer', 'corporate_seo_pro_cf7_redirect_script' );
function corporate_seo_pro_cf7_redirect_script() {
    // Contact pageのみで実行
    if ( ! is_page_template( 'page-contact.php' ) ) {
        return;
    }
    
    // Contact Form 7が有効な場合のみ
    if ( ! function_exists( 'wpcf7' ) ) {
        return;
    }
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // 重複防止用のフラグ
        let isRedirecting = false;
        let mailSentCount = 0;
        
        // Contact Form 7のイベントリスナー
        document.addEventListener('wpcf7mailsent', function(event) {
            mailSentCount++;
            console.log('CF7 mailsent event fired. Count:', mailSentCount);
            
            // 既にリダイレクト中の場合は処理をスキップ
            if (isRedirecting) {
                console.warn('CF7: Already redirecting, skipping duplicate mailsent event');
                return;
            }
            
            // リダイレクトフラグを設定
            isRedirecting = true;
            
            // thanksページへリダイレクト
            console.log('CF7: Redirecting to thanks page...');
            window.location.href = '<?php echo esc_url( home_url( '/thanks/' ) ); ?>';
        }, false);
        
        // フォーム送信エラー時のハンドリング
        document.addEventListener('wpcf7invalid', function(event) {
            console.log('Validation failed:', event.detail);
            isRedirecting = false;
        }, false);
        
        document.addEventListener('wpcf7spam', function(event) {
            console.log('Spam detected:', event.detail);
            isRedirecting = false;
        }, false);
        
        document.addEventListener('wpcf7mailfailed', function(event) {
            console.log('Mail sending failed:', event.detail);
            alert('送信に失敗しました。もう一度お試しください。');
            isRedirecting = false;
        }, false);
        
        // デバッグ用：フォーム送信イベント
        document.addEventListener('wpcf7submit', function(event) {
            console.log('Form submitted:', event.detail);
        }, false);
    });
    </script>
    <?php
}

// Contact Form 7 REST API fixes are now handled in inc/cf7-fixes.php

/**
 * Completely disable all submenus
 * サブメニューを完全に無効化
 */
// Remove all submenu items from the menu
add_filter( 'wp_nav_menu_objects', function( $items ) {
    foreach ( $items as $key => $item ) {
        if ( $item->menu_item_parent != 0 ) {
            unset( $items[$key] );
        }
    }
    return $items;
}, 999 );

// Remove menu-item-has-children class
add_filter( 'nav_menu_css_class', function( $classes, $item ) {
    $classes = array_diff( $classes, array( 'menu-item-has-children' ) );
    return $classes;
}, 999, 2 );

// Force depth to 1 (no submenus)
add_filter( 'wp_nav_menu_args', function( $args ) {
    $args['depth'] = 1;
    return $args;
}, 999 );

// Remove submenu from admin
add_action( 'admin_head', function() {
    ?>
    <style>
        #menu-to-edit .menu-item-depth-1,
        #menu-to-edit .menu-item-depth-2,
        #menu-to-edit .menu-item-depth-3,
        #menu-to-edit .menu-item-depth-4,
        #menu-to-edit .menu-item-depth-5 {
            display: none !important;
        }
    </style>
    <?php
} );