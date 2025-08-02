<?php
/**
 * Contact Form 7 Duplicate Submission Prevention (Server-side)
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * セッションベースの重複送信防止
 */
add_action( 'init', function() {
    if ( ! session_id() ) {
        session_start();
    }
} );

/**
 * フォーム送信前の処理
 */
add_action( 'wpcf7_before_send_mail', function( $contact_form ) {
    $submission = WPCF7_Submission::get_instance();
    
    if ( ! $submission ) {
        return;
    }
    
    // フォームIDを取得
    $form_id = $contact_form->id();
    
    // 現在のタイムスタンプ
    $current_time = time();
    
    // セッションキー
    $session_key = 'cf7_submission_' . $form_id;
    
    // 前回の送信時刻を確認
    if ( isset( $_SESSION[$session_key] ) ) {
        $last_submission_time = $_SESSION[$session_key];
        $time_diff = $current_time - $last_submission_time;
        
        // 5秒以内の再送信をブロック
        if ( $time_diff < 5 ) {
            error_log( 'CF7 Duplicate Prevention: Blocked duplicate submission for form ' . $form_id . ' (time diff: ' . $time_diff . ' seconds)' );
            
            // 送信をキャンセル
            $submission->skip_mail = true;
            
            // エラーメッセージを設定
            $submission->set_response( '送信処理中です。しばらくお待ちください。' );
            $submission->set_status( 'aborted' );
            
            return;
        }
    }
    
    // 送信時刻を記録
    $_SESSION[$session_key] = $current_time;
    
    // デバッグログ
    error_log( 'CF7 Duplicate Prevention: Form ' . $form_id . ' submission recorded at ' . date( 'Y-m-d H:i:s', $current_time ) );
}, 10, 1 );

/**
 * フォームデータのハッシュ値による重複チェック
 */
add_filter( 'wpcf7_validate', function( $result, $tags ) {
    $submission = WPCF7_Submission::get_instance();
    
    if ( ! $submission ) {
        return $result;
    }
    
    $form = $submission->get_contact_form();
    $form_id = $form->id();
    
    // 送信データを取得
    $posted_data = $submission->get_posted_data();
    
    // 重要なフィールドのみを使用してハッシュを生成
    $important_fields = array( 'your-name', 'your-email', 'your-message' );
    $hash_data = array();
    
    foreach ( $important_fields as $field ) {
        if ( isset( $posted_data[$field] ) ) {
            $hash_data[$field] = $posted_data[$field];
        }
    }
    
    // ハッシュ値を生成
    $data_hash = md5( serialize( $hash_data ) );
    $hash_key = 'cf7_hash_' . $form_id;
    
    // 同じハッシュ値が最近送信されていないか確認
    if ( isset( $_SESSION[$hash_key] ) && $_SESSION[$hash_key]['hash'] === $data_hash ) {
        $time_diff = time() - $_SESSION[$hash_key]['time'];
        
        // 30秒以内の同一内容送信をブロック
        if ( $time_diff < 30 ) {
            $result->invalidate( array(
                'type' => 'spam',
                'message' => '同じ内容が既に送信されています。'
            ) );
            
            error_log( 'CF7 Duplicate Prevention: Blocked duplicate content for form ' . $form_id );
        }
    }
    
    // ハッシュ値を保存
    $_SESSION[$hash_key] = array(
        'hash' => $data_hash,
        'time' => time()
    );
    
    return $result;
}, 10, 2 );

/**
 * メール送信成功後のクリーンアップ
 */
add_action( 'wpcf7_mail_sent', function( $contact_form ) {
    $form_id = $contact_form->id();
    
    // セッションから古いデータを削除（60秒後）
    $cleanup_key = 'cf7_cleanup_' . $form_id;
    $_SESSION[$cleanup_key] = time() + 60;
    
    error_log( 'CF7 Duplicate Prevention: Mail sent successfully for form ' . $form_id );
} );

/**
 * 管理画面に通知を表示
 */
add_action( 'admin_notices', function() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    
    // Contact Form 7がインストールされているか確認
    if ( ! defined( 'WPCF7_VERSION' ) ) {
        return;
    }
    
    // 重複送信防止が有効であることを通知（初回のみ）
    if ( get_option( 'cf7_duplicate_prevention_notice_dismissed' ) ) {
        return;
    }
    
    ?>
    <div class="notice notice-info is-dismissible" id="cf7-duplicate-prevention-notice">
        <p><strong>Contact Form 7 重複送信防止:</strong> サーバーサイドの重複送信防止機能が有効になりました。</p>
        <p>5秒以内の再送信と30秒以内の同一内容送信がブロックされます。</p>
    </div>
    <script>
    jQuery(document).ready(function($) {
        $('#cf7-duplicate-prevention-notice').on('click', '.notice-dismiss', function() {
            $.post(ajaxurl, {
                action: 'dismiss_cf7_duplicate_prevention_notice',
                _ajax_nonce: '<?php echo wp_create_nonce( 'dismiss_notice' ); ?>'
            });
        });
    });
    </script>
    <?php
} );

/**
 * 通知を非表示にするAJAXハンドラー
 */
add_action( 'wp_ajax_dismiss_cf7_duplicate_prevention_notice', function() {
    check_ajax_referer( 'dismiss_notice' );
    
    if ( current_user_can( 'manage_options' ) ) {
        update_option( 'cf7_duplicate_prevention_notice_dismissed', true );
    }
    
    wp_die();
} );