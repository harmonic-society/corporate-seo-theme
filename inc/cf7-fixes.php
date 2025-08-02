<?php
/**
 * Contact Form 7 Fixes and Enhancements
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Fix REST API authentication for Contact Form 7
 */
add_filter( 'rest_authentication_errors', function( $result ) {
    // Contact Form 7のREST APIエンドポイントへのアクセスを許可
    if ( isset( $_SERVER['REQUEST_URI'] ) && strpos( $_SERVER['REQUEST_URI'], '/wp-json/contact-form-7/v1/' ) !== false ) {
        return null;
    }
    return $result;
}, 999 );

/**
 * Ensure nonce is properly set for Contact Form 7
 */
add_action( 'wp_enqueue_scripts', function() {
    if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
        // Contact Form 7のスクリプトが読み込まれているか確認
        if ( wp_script_is( 'contact-form-7', 'enqueued' ) ) {
            // nonceを正しく設定
            $wpcf7 = array(
                'apiSettings' => array(
                    'root' => esc_url_raw( rest_url( 'contact-form-7/v1' ) ),
                    'namespace' => 'contact-form-7/v1',
                ),
            );
            
            // nonceが存在する場合のみ追加
            if ( is_user_logged_in() ) {
                $wpcf7['apiSettings']['nonce'] = wp_create_nonce( 'wp_rest' );
            }
            
            wp_localize_script( 'contact-form-7', 'wpcf7', $wpcf7 );
        }
    }
}, 999 );

/**
 * Allow REST API access for Contact Form 7
 */
add_filter( 'rest_pre_dispatch', function( $result, $wp_rest_server, $request ) {
    $route = $request->get_route();
    
    // Contact Form 7のルートの場合
    if ( strpos( $route, '/contact-form-7/v1/' ) !== false ) {
        // 認証をバイパス
        return $result;
    }
    
    return $result;
}, 10, 3 );

/**
 * Fix Content Security Policy for Contact Form 7
 */
add_action( 'send_headers', function() {
    if ( is_page_template( 'page-contact.php' ) ) {
        // CSPヘッダーを削除（開発環境のみ）
        header_remove( 'Content-Security-Policy' );
        header_remove( 'X-Content-Security-Policy' );
    }
}, 999 );

/**
 * Contact Form 7 Configuration Check
 */
add_action( 'admin_notices', function() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    
    // Contact Form 7がインストールされているか確認
    if ( ! defined( 'WPCF7_VERSION' ) ) {
        return;
    }
    
    // REST APIが有効か確認
    $rest_url = get_rest_url( null, 'contact-form-7/v1' );
    $response = wp_remote_get( $rest_url );
    
    if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
        ?>
        <div class="notice notice-error">
            <p><strong>Contact Form 7 REST APIエラー:</strong> REST APIへのアクセスに問題があります。パーマリンク設定を確認してください。</p>
        </div>
        <?php
    }
} );

/**
 * Disable Contact Form 7 refill for better UX
 */
add_filter( 'wpcf7_autop_or_not', '__return_false' );

/**
 * Custom validation messages in Japanese
 */
add_filter( 'wpcf7_messages', function( $messages ) {
    return array_merge( $messages, array(
        'mail_sent_ok' => 'お問い合わせありがとうございます。送信が完了しました。',
        'mail_sent_ng' => '送信に失敗しました。時間をおいて再度お試しください。',
        'validation_error' => '入力内容に誤りがあります。確認してください。',
        'spam' => 'スパムの可能性があるため送信できません。',
        'accept_terms' => '承諾が必要です。',
        'invalid_required' => '必須項目です。',
        'invalid_too_long' => '入力が長すぎます。',
        'invalid_too_short' => '入力が短すぎます。',
        'upload_failed' => 'ファイルのアップロードに失敗しました。',
        'upload_file_type_invalid' => 'このファイル形式はアップロードできません。',
        'upload_file_too_large' => 'ファイルが大きすぎます。',
        'upload_failed_php_error' => 'ファイルのアップロード中にエラーが発生しました。',
        'invalid_date' => '日付の形式が正しくありません。',
        'date_too_early' => '日付が早すぎます。',
        'date_too_late' => '日付が遅すぎます。',
        'invalid_number' => '数値の形式が正しくありません。',
        'number_too_small' => '数値が小さすぎます。',
        'number_too_large' => '数値が大きすぎます。',
        'quiz_answer_not_correct' => 'クイズの答えが正しくありません。',
        'invalid_email' => 'メールアドレスの形式が正しくありません。',
        'invalid_url' => 'URLの形式が正しくありません。',
        'invalid_tel' => '電話番号の形式が正しくありません。'
    ) );
} );

/**
 * Add custom CSS for Contact Form 7 loading state
 */
add_action( 'wp_head', function() {
    if ( is_page_template( 'page-contact.php' ) ) {
        ?>
        <style>
        /* CF7 Loading State */
        .wpcf7.sending .wpcf7-submit {
            opacity: 0.7;
            cursor: not-allowed;
            position: relative;
        }
        
        .wpcf7.sending .wpcf7-submit::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            margin: auto;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            border: 2px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: cf7-spin 1s linear infinite;
        }
        
        @keyframes cf7-spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Response output animation */
        .wpcf7-response-output {
            animation: cf7-slide-down 0.3s ease-out;
        }
        
        @keyframes cf7-slide-down {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        </style>
        <?php
    }
} );

/**
 * Debug helper for Contact Form 7 issues
 */
if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
    add_action( 'wpcf7_before_send_mail', function( $contact_form ) {
        error_log( 'CF7 Debug: Form ID ' . $contact_form->id() . ' is being submitted' );
        error_log( 'CF7 Debug: Form data: ' . print_r( $_POST, true ) );
    } );
    
    add_action( 'wpcf7_mail_sent', function( $contact_form ) {
        error_log( 'CF7 Debug: Mail sent successfully for form ID ' . $contact_form->id() );
    } );
    
    add_action( 'wpcf7_mail_failed', function( $contact_form ) {
        error_log( 'CF7 Debug: Mail failed for form ID ' . $contact_form->id() );
    } );
}