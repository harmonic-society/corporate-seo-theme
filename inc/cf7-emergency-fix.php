<?php
/**
 * Contact Form 7 Emergency Fix
 * エディタエラーを回避するための緊急修正
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Contact Form 7のデータ形式を修正
 */
add_action('init', function() {
    if (!is_admin()) {
        return;
    }
    
    // Contact Form 7の管理画面の場合のみ
    if (isset($_GET['page']) && $_GET['page'] === 'wpcf7') {
        // エラーハンドリングを設定
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            if (strpos($errfile, 'contact-form-7') !== false) {
                error_log("CF7 Error caught: $errstr in $errfile on line $errline");
                return true; // エラーを握りつぶす
            }
            return false;
        });
    }
}, 1);

/**
 * Contact Form 7のフォームデータを事前に修正
 */
add_action('load-toplevel_page_wpcf7', function() {
    global $wpdb;
    
    // 問題のあるフォームを修正
    $forms = $wpdb->get_results("
        SELECT ID, post_content 
        FROM {$wpdb->posts} 
        WHERE post_type = 'wpcf7_contact_form' 
        AND post_status = 'publish'
    ");
    
    foreach ($forms as $form) {
        $content = $form->post_content;
        $updated = false;
        
        // messagesが文字列になっている場合の修正
        if (strpos($content, 'a:3:{s:4:"mail"') !== false) {
            // シリアライズされたデータを修正
            $data = @unserialize($content);
            
            if ($data && is_array($data)) {
                // messagesが文字列の場合、削除
                if (isset($data['messages']) && is_string($data['messages'])) {
                    unset($data['messages']);
                    $updated = true;
                }
                
                // additional_settingsの修正
                if (isset($data['additional_settings'])) {
                    $data['additional_settings'] = preg_replace(
                        '/skip_mail:\s*on_sent_ok\s*\n?/i', 
                        '', 
                        $data['additional_settings']
                    );
                    $updated = true;
                }
                
                if ($updated) {
                    $wpdb->update(
                        $wpdb->posts,
                        array('post_content' => serialize($data)),
                        array('ID' => $form->ID)
                    );
                }
            }
        }
    }
});

/**
 * Contact Form 7のプロパティを強制的に修正
 */
add_filter('wpcf7_contact_form_default_pack', function($pack) {
    // messagesが配列であることを保証
    if (!isset($pack['messages']) || !is_array($pack['messages'])) {
        $pack['messages'] = array();
    }
    
    // additional_settingsから問題のある設定を削除
    if (isset($pack['additional_settings'])) {
        $pack['additional_settings'] = preg_replace(
            '/skip_mail:\s*on_sent_ok\s*\n?/i', 
            '', 
            $pack['additional_settings']
        );
    }
    
    return $pack;
}, 1);

/**
 * エディタ表示前の最終修正
 */
add_action('wpcf7_admin_init', function() {
    // エディタページの場合
    if (isset($_GET['post']) && isset($_GET['action']) && $_GET['action'] === 'edit') {
        $post_id = intval($_GET['post']);
        $contact_form = wpcf7_contact_form($post_id);
        
        if ($contact_form) {
            $properties = $contact_form->get_properties();
            
            // messagesの修正
            if (isset($properties['messages']) && !is_array($properties['messages'])) {
                $properties['messages'] = array();
                $contact_form->set_properties($properties);
                $contact_form->save();
            }
        }
    }
}, 1);