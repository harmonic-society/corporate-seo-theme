<?php
/**
 * URL自動リンク変換機能
 *
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * WordPressのKSESフィルターでURLが削除されないように設定
 */
function corporate_seo_pro_allow_post_urls() {
    // 投稿者以上の権限を持つユーザーにunfiltered_html権限を付与
    $role = get_role( 'author' );
    if ( $role ) {
        $role->add_cap( 'unfiltered_html' );
    }
    
    $role = get_role( 'editor' );
    if ( $role ) {
        $role->add_cap( 'unfiltered_html' );
    }
}
// 初回のみ実行（テーマ有効化時）
add_action( 'after_switch_theme', 'corporate_seo_pro_allow_post_urls' );

/**
 * コンテンツ内のURLを保護
 */
function corporate_seo_pro_protect_urls_in_content( $content ) {
    // URLパターンを一時的に保護
    $content = preg_replace_callback(
        '/(https?:\/\/[^\s<>"]+)/i',
        function( $matches ) {
            return '<!--url:' . base64_encode( $matches[1] ) . '-->';
        },
        $content
    );
    
    return $content;
}

/**
 * 保護したURLを復元
 */
function corporate_seo_pro_restore_urls_in_content( $content ) {
    // 保護したURLを復元
    $content = preg_replace_callback(
        '/<!--url:([^-]+)-->/i',
        function( $matches ) {
            return base64_decode( $matches[1] );
        },
        $content
    );
    
    return $content;
}

/**
 * プレーンテキストのURLを自動的にクリック可能なリンクに変換
 */
function corporate_seo_pro_make_urls_clickable( $content ) {
    // 管理画面では処理しない
    if ( is_admin() ) {
        return $content;
    }
    
    // メインループ内の投稿コンテンツのみを処理
    if ( ! is_singular() || ! in_the_loop() || ! is_main_query() ) {
        return $content;
    }
    
    // すでにリンクになっているURLや、HTMLタグ内のURLは除外
    // make_clickableフィルターを適用してプレーンテキストのURLをリンクに変換
    $content = make_clickable( $content );
    
    return $content;
}

// the_contentフィルターに追加（優先度9：早めに実行してURLを保護）
add_filter( 'the_content', 'corporate_seo_pro_protect_urls_in_content', 5 );
add_filter( 'the_content', 'corporate_seo_pro_restore_urls_in_content', 8 );
add_filter( 'the_content', 'corporate_seo_pro_make_urls_clickable', 10 );

/**
 * コメント内のURLも自動的にリンクに変換
 */
add_filter( 'comment_text', 'make_clickable', 9 );

/**
 * 抜粋内のURLも自動的にリンクに変換（必要に応じて）
 */
function corporate_seo_pro_excerpt_make_clickable( $excerpt ) {
    if ( ! is_admin() ) {
        $excerpt = make_clickable( $excerpt );
    }
    return $excerpt;
}
add_filter( 'the_excerpt', 'corporate_seo_pro_excerpt_make_clickable', 10 );

/**
 * ACFフィールドのテキストエリアでもURLを自動リンク化（オプション）
 */
function corporate_seo_pro_acf_make_clickable( $value, $post_id, $field ) {
    // テキストエリアまたはWYSIWYGフィールドの場合
    if ( in_array( $field['type'], array( 'textarea', 'wysiwyg' ) ) ) {
        // WYSIWYGフィールドは既にリンクが処理されているので除外
        if ( $field['type'] === 'textarea' ) {
            $value = make_clickable( $value );
        }
    }
    
    return $value;
}
// ACFがアクティブな場合のみフィルターを追加
if ( function_exists( 'get_field' ) ) {
    add_filter( 'acf/format_value', 'corporate_seo_pro_acf_make_clickable', 10, 3 );
}

/**
 * URLリンクにカスタムクラスを追加（スタイリング用）
 */
function corporate_seo_pro_add_url_link_class( $content ) {
    // make_clickableで生成されたリンクにクラスを追加
    $content = preg_replace(
        '/<a\s+href=["\'](https?:\/\/[^"\']+)["\']\s*>/i',
        '<a href="$1" class="external-link" target="_blank" rel="noopener noreferrer">',
        $content
    );
    
    return $content;
}
// 外部リンクにクラスとtarget="_blank"を追加する場合はこのフィルターを有効化
// add_filter( 'the_content', 'corporate_seo_pro_add_url_link_class', 11 );