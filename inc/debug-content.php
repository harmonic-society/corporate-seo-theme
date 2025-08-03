<?php
/**
 * コンテンツデバッグ用関数
 *
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * コンテンツのフィルター処理をデバッグ
 */
function corporate_seo_pro_debug_content_filters( $content ) {
    // デバッグモードでない場合は何もしない
    if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
        return $content;
    }
    
    // 管理画面では処理しない
    if ( is_admin() ) {
        return $content;
    }
    
    // デバッグ情報をエラーログに記録
    error_log( '=== Content Debug Start ===' );
    error_log( 'Original content length: ' . strlen( $content ) );
    error_log( 'Contains http:// or https://: ' . ( preg_match( '/https?:\/\//', $content ) ? 'Yes' : 'No' ) );
    
    // URLパターンを検索
    preg_match_all( '/https?:\/\/[^\s<>"]+/i', $content, $matches );
    if ( ! empty( $matches[0] ) ) {
        error_log( 'Found URLs: ' . print_r( $matches[0], true ) );
    } else {
        error_log( 'No URLs found in content' );
    }
    
    // 現在のユーザー権限を確認
    $current_user = wp_get_current_user();
    error_log( 'Current user can unfiltered_html: ' . ( current_user_can( 'unfiltered_html' ) ? 'Yes' : 'No' ) );
    error_log( 'Current user roles: ' . print_r( $current_user->roles, true ) );
    
    error_log( '=== Content Debug End ===' );
    
    return $content;
}

// デバッグフィルターを追加（優先度1で早期に実行）
add_filter( 'the_content', 'corporate_seo_pro_debug_content_filters', 1 );

/**
 * 保存時のコンテンツをデバッグ
 */
function corporate_seo_pro_debug_save_content( $data, $postarr ) {
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        error_log( '=== Save Content Debug ===' );
        error_log( 'Post type: ' . $data['post_type'] );
        error_log( 'Content has URLs: ' . ( preg_match( '/https?:\/\//', $data['post_content'] ) ? 'Yes' : 'No' ) );
        
        // 保存前後のコンテンツを比較
        if ( isset( $postarr['ID'] ) && $postarr['ID'] > 0 ) {
            $old_post = get_post( $postarr['ID'] );
            if ( $old_post ) {
                error_log( 'Old content has URLs: ' . ( preg_match( '/https?:\/\//', $old_post->post_content ) ? 'Yes' : 'No' ) );
            }
        }
    }
    
    return $data;
}
add_filter( 'wp_insert_post_data', 'corporate_seo_pro_debug_save_content', 10, 2 );

/**
 * ユーザー権限によるコンテンツフィルタリングを一時的に無効化（テスト用）
 */
function corporate_seo_pro_allow_unfiltered_html( $caps, $cap, $user_id, $args ) {
    // 投稿編集時にunfiltered_html権限を許可
    if ( $cap === 'unfiltered_html' ) {
        // 編集者以上の権限を持つユーザーに許可
        $user = get_userdata( $user_id );
        if ( $user && ( $user->has_cap( 'edit_posts' ) || $user->has_cap( 'edit_pages' ) ) ) {
            $caps = array( 'unfiltered_html' );
        }
    }
    
    return $caps;
}
// この行のコメントを外すと、編集者以上にunfiltered_html権限を付与します
// add_filter( 'map_meta_cap', 'corporate_seo_pro_allow_unfiltered_html', 10, 4 );