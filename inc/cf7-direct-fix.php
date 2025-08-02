<?php
/**
 * Contact Form 7 Direct Database Fix
 * データベースの不正なデータを直接修正
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 管理画面の初期化時にデータベースを修正
 */
add_action('admin_init', function() {
    // Contact Form 7の管理画面以外では実行しない
    if (!isset($_GET['page']) || strpos($_GET['page'], 'wpcf7') === false) {
        return;
    }
    
    // 一度だけ実行するフラグ
    $fix_applied = get_option('cf7_messages_fix_applied_v2');
    if ($fix_applied) {
        return;
    }
    
    global $wpdb;
    
    // Contact Form 7のフォームを取得
    $forms = $wpdb->get_results("
        SELECT ID, post_content 
        FROM {$wpdb->posts} 
        WHERE post_type = 'wpcf7_contact_form' 
        AND post_status = 'publish'
    ");
    
    foreach ($forms as $form) {
        $updated = false;
        $content = $form->post_content;
        
        // シリアライズされたデータかチェック
        if (is_serialized($content)) {
            $data = @unserialize($content);
            
            if ($data && is_array($data)) {
                // messagesが存在し、配列でない場合
                if (isset($data['messages']) && !is_array($data['messages'])) {
                    // デフォルトのメッセージ配列を設定
                    $data['messages'] = array(
                        'mail_sent_ok' => 'ありがとうございます。メッセージは送信されました。',
                        'mail_sent_ng' => 'メッセージの送信に失敗しました。後でまたお試しください。',
                        'validation_error' => '入力内容に問題があります。確認して再度お試しください。',
                        'spam' => 'メッセージの送信に失敗しました。後でまたお試しください。',
                        'accept_terms' => '承諾が必要です。',
                        'invalid_required' => '必須項目です。',
                        'invalid_too_long' => '入力された文字列が長すぎます。',
                        'invalid_too_short' => '入力された文字列が短すぎます。',
                        'invalid_email' => '正しいメールアドレスの形式で入力してください。',
                        'invalid_url' => '正しいURLの形式で入力してください。',
                        'invalid_tel' => '正しい電話番号の形式で入力してください。',
                        'quiz_answer_not_correct' => 'クイズの答えが正しくありません。',
                        'invalid_date' => '日付の形式が正しくありません。',
                        'date_too_early' => '選択された日付が早すぎます。',
                        'date_too_late' => '選択された日付が遅すぎます。',
                        'upload_failed' => 'ファイルのアップロードに失敗しました。',
                        'upload_file_type_invalid' => '許可されていないファイル形式です。',
                        'upload_file_too_large' => 'アップロードされたファイルが大きすぎます。',
                        'upload_failed_php_error' => 'ファイルのアップロード中にエラーが発生しました。',
                        'invalid_number' => '数値の形式が正しくありません。',
                        'number_too_small' => '入力された数値が小さすぎます。',
                        'number_too_large' => '入力された数値が大きすぎます。',
                    );
                    $updated = true;
                }
                
                // additional_settingsから問題のある設定を削除
                if (isset($data['additional_settings']) && is_string($data['additional_settings'])) {
                    $cleaned = preg_replace('/skip_mail:\s*on_sent_ok\s*\n?/i', '', $data['additional_settings']);
                    if ($cleaned !== $data['additional_settings']) {
                        $data['additional_settings'] = $cleaned;
                        $updated = true;
                    }
                }
                
                if ($updated) {
                    $wpdb->update(
                        $wpdb->posts,
                        array('post_content' => serialize($data)),
                        array('ID' => $form->ID)
                    );
                    error_log("CF7 Direct Fix: Updated form ID {$form->ID}");
                }
            }
        }
    }
    
    // postmetaから_messagesメタデータを削除
    $wpdb->query("
        DELETE FROM {$wpdb->postmeta} 
        WHERE meta_key = '_messages' 
        AND post_id IN (
            SELECT ID FROM {$wpdb->posts} 
            WHERE post_type = 'wpcf7_contact_form'
        )
    ");
    
    // 修正完了フラグを設定
    update_option('cf7_messages_fix_applied_v2', true);
    
    // キャッシュをクリア
    wp_cache_flush();
    
    error_log("CF7 Direct Fix: Database fix completed");
}, 1);

/**
 * Contact Form 7のプロパティを取得する際の修正
 */
add_filter('wpcf7_contact_form_properties', function($properties, $instance) {
    // messagesが配列でない場合は修正
    if (isset($properties['messages']) && !is_array($properties['messages'])) {
        $properties['messages'] = array();
    }
    
    // messagesが存在しない場合はデフォルトを設定
    if (!isset($properties['messages']) || empty($properties['messages'])) {
        $properties['messages'] = wpcf7_messages();
    }
    
    return $properties;
}, 1, 2);

/**
 * エディタでエラーが発生する場合の回避策
 */
add_action('wpcf7_admin_init', function() {
    // エラーが発生する可能性のある箇所を上書き
    if (isset($_GET['post']) && $_GET['action'] === 'edit') {
        add_filter('wpcf7_editor_panels', function($panels) {
            // messagesパネルでエラーが出る場合は削除
            if (isset($panels['messages'])) {
                // 一時的にmessagesパネルを非表示
                // unset($panels['messages']);
                
                // または、カスタムパネルで置き換え
                $panels['messages'] = array(
                    'title' => __('Messages', 'contact-form-7'),
                    'callback' => 'corporate_seo_pro_cf7_messages_panel'
                );
            }
            return $panels;
        }, 1);
    }
});

/**
 * カスタムメッセージパネル
 */
function corporate_seo_pro_cf7_messages_panel($post) {
    ?>
    <div class="notice notice-info">
        <p>メッセージ設定は自動的にデフォルト値が使用されます。</p>
    </div>
    <?php
}