<?php
/**
 * Theme Setup Functions
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Theme setup
 */
function corporate_seo_pro_setup() {
    // 翻訳ファイルの読み込み
    load_theme_textdomain( 'corporate-seo-pro', get_template_directory() . '/languages' );

    // 自動フィードリンク
    add_theme_support( 'automatic-feed-links' );

    // タイトルタグ
    add_theme_support( 'title-tag' );

    // アイキャッチ画像
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'corporate-hero', 1280, 750, true );
    add_image_size( 'corporate-hero-mobile', 768, 600, true );
    add_image_size( 'corporate-featured', 800, 600, true );
    add_image_size( 'corporate-square', 600, 600, true );

    // HTML5マークアップ
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // カスタムロゴ
    add_theme_support( 'custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    // カスタムヘッダー
    add_theme_support( 'custom-header', array(
        'default-image'      => '',
        'default-text-color' => '000000',
        'width'              => 1920,
        'height'             => 400,
        'flex-height'        => true,
    ) );

    // ブロックエディターのサポート
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'custom-spacing' );
    add_theme_support( 'custom-units', array( 'rem', 'em' ) );
}
add_action( 'after_setup_theme', 'corporate_seo_pro_setup' );

/**
 * コンテンツ幅の設定
 */
function corporate_seo_pro_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'corporate_seo_pro_content_width', 1200 );
}
add_action( 'after_setup_theme', 'corporate_seo_pro_content_width', 0 );

/**
 * bodyクラスを追加
 */
function corporate_seo_pro_body_classes( $classes ) {
    if ( ! is_singular() ) {
        $classes[] = 'archive-layout';
    }
    
    if ( is_page() ) {
        $classes[] = 'page-layout';
    }
    
    return $classes;
}
add_filter( 'body_class', 'corporate_seo_pro_body_classes' );