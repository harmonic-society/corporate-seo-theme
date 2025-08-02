<?php
/**
 * Contact Form 7 Force Fix
 * 管理画面のエラーを強制的に回避する最終手段
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Contact Form 7のエディタパネルを完全に置き換え
 */
add_action('init', function() {
    if (!is_admin()) {
        return;
    }
    
    // Contact Form 7の管理画面でのみ実行
    if (isset($_GET['page']) && strpos($_GET['page'], 'wpcf7') !== false) {
        // エラーを起こすメソッドを上書き
        add_filter('wpcf7_editor_panels', function($panels) {
            // messagesパネルを独自の実装で置き換え
            if (isset($panels['messages'])) {
                $panels['messages']['callback'] = 'corporate_seo_pro_safe_messages_panel';
            }
            return $panels;
        }, 1);
        
        // メッセージプロパティを強制的に配列にする
        add_filter('wpcf7_contact_form_properties', function($properties) {
            // messagesを強制的に配列にする
            if (isset($properties['messages']) && !is_array($properties['messages'])) {
                $properties['messages'] = array();
            }
            
            // messagesが存在しない場合も配列を設定
            if (!isset($properties['messages'])) {
                $properties['messages'] = array();
            }
            
            // additional_settingsのクリーンアップ
            if (isset($properties['additional_settings'])) {
                $properties['additional_settings'] = preg_replace(
                    '/skip_mail:\s*on_sent_ok\s*\n?/i',
                    '',
                    $properties['additional_settings']
                );
            }
            
            return $properties;
        }, 1);
        
        // Contact Form 7のデータ取得メソッドをフック
        add_filter('wpcf7_messages', function($messages) {
            if (!is_array($messages)) {
                return get_default_cf7_messages();
            }
            return $messages;
        }, 1);
    }
});

/**
 * 安全なメッセージパネル
 */
function corporate_seo_pro_safe_messages_panel($post) {
    $messages = get_default_cf7_messages();
    ?>
    <div class="notice notice-warning">
        <p><strong>メッセージ設定は一時的に無効化されています。</strong></p>
        <p>Contact Form 7のデータベースエラーを修正中です。</p>
    </div>
    
    <h3>デフォルトメッセージ</h3>
    <table class="form-table">
        <?php foreach ($messages as $key => $message): ?>
        <tr>
            <th scope="row">
                <label for="wpcf7-message-<?php echo esc_attr($key); ?>">
                    <?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?>
                </label>
            </th>
            <td>
                <input type="text" 
                       id="wpcf7-message-<?php echo esc_attr($key); ?>" 
                       name="wpcf7-messages[<?php echo esc_attr($key); ?>]" 
                       class="large-text" 
                       value="<?php echo esc_attr($message); ?>" 
                       readonly />
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <div class="notice notice-info">
        <p>メッセージをカスタマイズする場合は、テーマのfunctions.phpで <code>wpcf7_messages</code> フィルターを使用してください。</p>
    </div>
    <?php
}

/**
 * デフォルトメッセージを取得
 */
function get_default_cf7_messages() {
    return array(
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

/**
 * フォーム保存時にメッセージデータを修正
 */
add_action('wpcf7_save_contact_form', function($contact_form) {
    $properties = $contact_form->get_properties();
    
    // messagesを配列にする
    if (isset($properties['messages']) && !is_array($properties['messages'])) {
        $properties['messages'] = get_default_cf7_messages();
        $contact_form->set_properties($properties);
    }
    
    // additional_settingsをクリーンアップ
    if (isset($properties['additional_settings'])) {
        $properties['additional_settings'] = preg_replace(
            '/skip_mail:\s*on_sent_ok\s*\n?/i',
            '',
            $properties['additional_settings']
        );
        $contact_form->set_properties($properties);
    }
}, 1);