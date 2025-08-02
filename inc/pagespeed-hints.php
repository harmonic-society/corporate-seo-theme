<?php
/**
 * PageSpeed Module Hints
 * 
 * PageSpeed Moduleに対するヒントを提供
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * PageSpeed用のHTMLコメントを出力
 */
function corporate_seo_pro_pagespeed_hints() {
    // Contact pageの場合
    if ( is_page_template( 'page-contact.php' ) ) {
        echo "\n<!-- PageSpeed Module Configuration Hints -->\n";
        echo "<!-- ModPagespeedDisallow: */contact-new.js -->\n";
        echo "<!-- ModPagespeedDisallow: */contact-fallback.js -->\n";
        echo "<!-- Critical JavaScript - Do not defer or async -->\n";
    }
    
    // 全ページ共通
    echo "<!-- ModPagespeedDisallow: */mobile-menu.js -->\n";
    echo "<!-- ModPagespeedDisallow: */navigation.js -->\n";
}
add_action( 'wp_head', 'corporate_seo_pro_pagespeed_hints', 1 );

/**
 * CSPヘッダーの調整（Contact pageのみ）
 */
function corporate_seo_pro_adjust_csp_headers() {
    if ( is_page_template( 'page-contact.php' ) ) {
        // より緩和されたCSPポリシー
        $csp = "default-src 'self' 'unsafe-inline' 'unsafe-eval' https: data: blob:; ";
        $csp .= "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google-analytics.com https://www.googletagmanager.com https://ajax.googleapis.com https://cdnjs.cloudflare.com https://webfonts.xserver.jp; ";
        $csp .= "style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://fonts.googleapis.com; ";
        $csp .= "font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com; ";
        $csp .= "img-src 'self' data: https: blob:; ";
        $csp .= "connect-src 'self' https:;";
        
        header( "Content-Security-Policy: " . $csp );
    }
}
add_action( 'send_headers', 'corporate_seo_pro_adjust_csp_headers' );

/**
 * 特定のスクリプトにdata属性を追加
 */
function corporate_seo_pro_add_script_attributes( $tag, $handle, $src ) {
    // Contact form関連のスクリプト
    $critical_scripts = array(
        'corporate-seo-pro-contact',
        'corporate-seo-pro-contact-fallback',
        'corporate-seo-pro-navigation',
        'corporate-seo-pro-mobile-menu'
    );
    
    if ( in_array( $handle, $critical_scripts ) ) {
        // PageSpeedに対して最適化を無効化するヒント
        $tag = str_replace( ' src=', ' data-pagespeed-no-defer data-cfasync="false" src=', $tag );
    }
    
    return $tag;
}
add_filter( 'script_loader_tag', 'corporate_seo_pro_add_script_attributes', 10, 3 );

/**
 * 重要なスタイルシートにpreloadヒントを追加
 */
function corporate_seo_pro_preload_critical_styles() {
    $critical_styles = array(
        'style.css',
        'assets/css/base/base.css',
        'assets/css/base/typography.css',
        'assets/css/components/navigation.css',
    );
    
    foreach ( $critical_styles as $style ) {
        $url = get_template_directory_uri() . '/' . $style;
        echo '<link rel="preload" href="' . esc_url( $url ) . '" as="style">' . "\n";
    }
}
add_action( 'wp_head', 'corporate_seo_pro_preload_critical_styles', 2 );

/**
 * robots.txtにPageSpeed用の指示を追加
 */
function corporate_seo_pro_robots_txt( $output, $public ) {
    if ( $public ) {
        $output .= "\n# PageSpeed Module\n";
        $output .= "User-agent: *\n";
        $output .= "Disallow: /*?PageSpeed=*\n";
        $output .= "Disallow: /*?ModPagespeed=*\n";
    }
    return $output;
}
add_filter( 'robots_txt', 'corporate_seo_pro_robots_txt', 10, 2 );