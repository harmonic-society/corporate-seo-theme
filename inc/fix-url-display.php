<?php
/**
 * URL表示問題の修正
 *
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * WordPressのautoembed機能を再有効化
 */
function corporate_seo_pro_enable_autoembed() {
    global $wp_embed;
    
    // autoembedが無効になっている場合は再有効化
    if ( ! empty( $wp_embed ) && method_exists( $wp_embed, 'autoembed' ) ) {
        add_filter( 'the_content', array( $wp_embed, 'autoembed' ), 8 );
    }
}
add_action( 'init', 'corporate_seo_pro_enable_autoembed' );

/**
 * KSESフィルターの設定を調整
 */
function corporate_seo_pro_kses_allowed_html( $allowed_tags, $context ) {
    // postコンテキストの場合
    if ( $context === 'post' ) {
        // リンクタグの属性を確実に許可
        $allowed_tags['a'] = array(
            'href' => true,
            'title' => true,
            'target' => true,
            'rel' => true,
            'class' => true,
            'id' => true,
        );
    }
    
    return $allowed_tags;
}
add_filter( 'wp_kses_allowed_html', 'corporate_seo_pro_kses_allowed_html', 10, 2 );

/**
 * 投稿保存時のコンテンツフィルタリングを調整
 */
function corporate_seo_pro_pre_save_post_content( $data, $postarr ) {
    // 投稿タイプが post または page の場合
    if ( in_array( $data['post_type'], array( 'post', 'page' ) ) ) {
        // 投稿者が編集権限を持っている場合
        if ( current_user_can( 'edit_posts' ) ) {
            // コンテンツ内のURLを保持
            remove_filter( 'content_save_pre', 'wp_filter_post_kses' );
            remove_filter( 'content_filtered_save_pre', 'wp_filter_post_kses' );
        }
    }
    
    return $data;
}
add_filter( 'wp_insert_post_data', 'corporate_seo_pro_pre_save_post_content', 10, 2 );

/**
 * フロントエンドでのURL表示を確実にする
 */
function corporate_seo_pro_ensure_url_display( $content ) {
    // 既にフィルタリングされたかチェック
    if ( has_filter( 'the_content', 'convert_chars' ) ) {
        // convert_charsフィルターがURLを変換してしまう可能性があるため一時的に削除
        remove_filter( 'the_content', 'convert_chars' );
        
        // フィルター適用後に再度追加
        add_action( 'the_content', function( $content ) {
            add_filter( 'the_content', 'convert_chars' );
            return $content;
        }, 999 );
    }
    
    return $content;
}
add_filter( 'the_content', 'corporate_seo_pro_ensure_url_display', 1 );

/**
 * ビジュアルエディタでURLが削除されないように設定
 */
function corporate_seo_pro_tiny_mce_before_init( $init ) {
    // TinyMCEがURLを削除しないように設定
    $init['extended_valid_elements'] = 'a[href|target|rel|class|title]';
    $init['remove_script_host'] = false;
    $init['convert_urls'] = false;
    $init['relative_urls'] = false;
    
    return $init;
}
add_filter( 'tiny_mce_before_init', 'corporate_seo_pro_tiny_mce_before_init' );

/**
 * Classic EditorでもURLの自動リンク化を有効にする
 */
function corporate_seo_pro_mce_external_plugins( $plugins ) {
    // autolink プラグインを追加（URLを自動的にリンクに変換）
    $plugins['autolink'] = includes_url( 'js/tinymce/plugins/wplink/plugin.min.js' );
    
    return $plugins;
}
add_filter( 'mce_external_plugins', 'corporate_seo_pro_mce_external_plugins' );

/**
 * デバッグ：保存されたコンテンツを確認
 */
function corporate_seo_pro_check_saved_content( $post_id ) {
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        $post = get_post( $post_id );
        if ( $post && in_array( $post->post_type, array( 'post', 'page' ) ) ) {
            error_log( '=== Saved Content Check ===' );
            error_log( 'Post ID: ' . $post_id );
            error_log( 'Has URLs in content: ' . ( preg_match( '/https?:\/\//', $post->post_content ) ? 'Yes' : 'No' ) );
            
            // URLを抽出して表示
            preg_match_all( '/https?:\/\/[^\s<>"]+/i', $post->post_content, $matches );
            if ( ! empty( $matches[0] ) ) {
                error_log( 'Found URLs: ' . print_r( $matches[0], true ) );
            }
        }
    }
}
add_action( 'save_post', 'corporate_seo_pro_check_saved_content', 999 );