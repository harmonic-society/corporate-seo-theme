<?php
/**
 * Contact Form 7 Template Fix
 * テンプレートエラーを修正
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Contact Form 7のデフォルトテンプレートを修正
 */
add_filter('wpcf7_default_template', function($template, $prop) {
    if ($prop === 'messages') {
        // messagesが配列でない場合は配列を返す
        if (!is_array($template)) {
            return array(
                'mail_sent_ok' => __('Thank you for your message. It has been sent.', 'contact-form-7'),
                'mail_sent_ng' => __('There was an error trying to send your message. Please try again later.', 'contact-form-7'),
                'validation_error' => __('One or more fields have an error. Please check and try again.', 'contact-form-7'),
                'spam' => __('There was an error trying to send your message. Please try again later.', 'contact-form-7'),
                'accept_terms' => __('You must accept the terms and conditions before sending your message.', 'contact-form-7'),
                'invalid_required' => __('The field is required.', 'contact-form-7'),
                'invalid_too_long' => __('The field is too long.', 'contact-form-7'),
                'invalid_too_short' => __('The field is too short.', 'contact-form-7'),
                'invalid_date' => __('The date format is incorrect.', 'contact-form-7'),
                'date_too_early' => __('The date is before the earliest one allowed.', 'contact-form-7'),
                'date_too_late' => __('The date is after the latest one allowed.', 'contact-form-7'),
                'upload_failed' => __('There was an unknown error uploading the file.', 'contact-form-7'),
                'upload_file_type_invalid' => __('You are not allowed to upload files of this type.', 'contact-form-7'),
                'upload_file_too_large' => __('The file is too big.', 'contact-form-7'),
                'upload_failed_php_error' => __('There was an error uploading the file.', 'contact-form-7'),
                'invalid_number' => __('The number format is invalid.', 'contact-form-7'),
                'number_too_small' => __('The number is smaller than the minimum allowed.', 'contact-form-7'),
                'number_too_large' => __('The number is larger than the maximum allowed.', 'contact-form-7'),
                'quiz_answer_not_correct' => __('The answer to the quiz is incorrect.', 'contact-form-7'),
                'invalid_email' => __('The e-mail address entered is invalid.', 'contact-form-7'),
                'invalid_url' => __('The URL is invalid.', 'contact-form-7'),
                'invalid_tel' => __('The telephone number is invalid.', 'contact-form-7')
            );
        }
    }
    return $template;
}, 1, 2);

/**
 * get_defaultメソッドをフック
 */
add_action('plugins_loaded', function() {
    if (class_exists('WPCF7_ContactFormTemplate')) {
        // 既存のmessagesメソッドを上書き
        add_filter('wpcf7_contact_form_default_pack', function($properties) {
            if (!isset($properties['messages']) || !is_array($properties['messages'])) {
                $properties['messages'] = array(
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
            return $properties;
        }, 1);
    }
}, 20);

/**
 * エラーハンドラーで強制的に対処
 */
add_action('init', function() {
    if (is_admin() && isset($_GET['page']) && strpos($_GET['page'], 'wpcf7') !== false) {
        // 致命的エラーを回避
        register_shutdown_function(function() {
            $error = error_get_last();
            if ($error && $error['type'] === E_ERROR && strpos($error['file'], 'contact-form-7') !== false) {
                // エラーページの代わりに管理画面にリダイレクト
                if (!headers_sent()) {
                    wp_redirect(admin_url('admin.php?page=wpcf7&cf7_error=template'));
                    exit;
                }
            }
        });
    }
});