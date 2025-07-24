<?php
/**
 * パフォーマンス最適化機能
 *
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 不要なヘッダー情報の削除
 */
function corporate_seo_pro_clean_head() {
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'corporate_seo_pro_clean_head' );

/**
 * DNS プリフェッチの追加
 */
function corporate_seo_pro_dns_prefetch() {
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//ajax.googleapis.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//www.google-analytics.com">' . "\n";
}
add_action( 'wp_head', 'corporate_seo_pro_dns_prefetch', 0 );

/**
 * 画像の遅延読み込み
 */
function corporate_seo_pro_add_loading_lazy( $content ) {
    if ( ! is_feed() && ! is_preview() ) {
        $content = preg_replace( '/<img(.*?)src=/i', '<img$1loading="lazy" src=', $content );
    }
    return $content;
}
add_filter( 'the_content', 'corporate_seo_pro_add_loading_lazy' );

/**
 * スクリプトにdefer属性を追加
 */
function corporate_seo_pro_defer_scripts( $tag, $handle ) {
    $defer_scripts = array(
        'corporate-seo-pro-navigation',
        'comment-reply',
    );
    
    if ( in_array( $handle, $defer_scripts ) ) {
        return str_replace( ' src', ' defer src', $tag );
    }
    
    return $tag;
}
add_filter( 'script_loader_tag', 'corporate_seo_pro_defer_scripts', 10, 2 );

/**
 * 不要なブロックエディタースタイルを削除
 */
function corporate_seo_pro_remove_block_styles() {
    if ( ! is_admin() ) {
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
        wp_dequeue_style( 'wc-block-style' );
    }
}
add_action( 'wp_enqueue_scripts', 'corporate_seo_pro_remove_block_styles', 100 );

/**
 * 画像サイズの最適化
 */
function corporate_seo_pro_optimize_image_sizes() {
    // JPEG画質の設定
    add_filter( 'jpeg_quality', function( $quality ) {
        return 85;
    } );
    
    // 大きな画像のしきい値を設定
    add_filter( 'big_image_size_threshold', function( $threshold ) {
        return 1920;
    } );
}
add_action( 'after_setup_theme', 'corporate_seo_pro_optimize_image_sizes' );

/**
 * リビジョンの制限
 */
function corporate_seo_pro_limit_revisions( $num, $post ) {
    return 5;
}
add_filter( 'wp_revisions_to_keep', 'corporate_seo_pro_limit_revisions', 10, 2 );

/**
 * 自動保存の間隔を変更
 */
function corporate_seo_pro_autosave_interval() {
    return 120; // 2分
}
add_filter( 'autosave_interval', 'corporate_seo_pro_autosave_interval' );

/**
 * Gravatarの遅延読み込み
 */
function corporate_seo_pro_lazy_load_gravatar( $avatar ) {
    $avatar = str_replace( 'src=', 'loading="lazy" src=', $avatar );
    return $avatar;
}
add_filter( 'get_avatar', 'corporate_seo_pro_lazy_load_gravatar' );

/**
 * クエリ文字列の削除
 */
function corporate_seo_pro_remove_query_strings( $src ) {
    if ( ! is_admin() ) {
        $parts = explode( '?ver', $src );
        return $parts[0];
    }
    return $src;
}
add_filter( 'script_loader_src', 'corporate_seo_pro_remove_query_strings', 15, 1 );
add_filter( 'style_loader_src', 'corporate_seo_pro_remove_query_strings', 15, 1 );

/**
 * Heartbeat APIの最適化
 */
function corporate_seo_pro_optimize_heartbeat() {
    // 管理画面以外では無効化
    if ( ! is_admin() ) {
        wp_deregister_script( 'heartbeat' );
    }
}
add_action( 'init', 'corporate_seo_pro_optimize_heartbeat', 1 );

/**
 * プリコネクトの追加
 */
function corporate_seo_pro_resource_hints( $hints, $relation_type ) {
    if ( 'preconnect' === $relation_type ) {
        $hints[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }
    return $hints;
}
add_filter( 'wp_resource_hints', 'corporate_seo_pro_resource_hints', 10, 2 );

/**
 * oEmbedの無効化
 */
function corporate_seo_pro_disable_oembed() {
    wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'corporate_seo_pro_disable_oembed' );

/**
 * CSSの最適化
 */
function corporate_seo_pro_optimize_css_delivery() {
    if ( ! is_admin() ) {
        // Critical CSSをインライン化
        $critical_css = "
            :root {
                --primary-color: #1e3a8a;
                --secondary-color: #3b82f6;
                --accent-color: #f59e0b;
                --text-color: #1f2937;
                --header-height: 80px;
            }
            body {
                margin: 0;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans JP', sans-serif;
                color: var(--text-color);
            }
            .site-header {
                background: #fff;
                height: var(--header-height);
                position: sticky;
                top: 0;
                z-index: 1000;
            }
            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 20px;
            }
        ";
        
        echo '<style id="corporate-seo-critical-css">' . $critical_css . '</style>';
    }
}
add_action( 'wp_head', 'corporate_seo_pro_optimize_css_delivery', 1 );

/**
 * WebPのサポート
 */
function corporate_seo_pro_webp_support( $mime_types ) {
    $mime_types['webp'] = 'image/webp';
    return $mime_types;
}
add_filter( 'upload_mimes', 'corporate_seo_pro_webp_support' );

/**
 * ブラウザキャッシュの設定
 */
function corporate_seo_pro_browser_cache_headers() {
    if ( ! is_admin() ) {
        header( 'Cache-Control: public, max-age=31536000' );
        header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + 31536000 ) . ' GMT' );
    }
}
add_action( 'send_headers', 'corporate_seo_pro_browser_cache_headers' );