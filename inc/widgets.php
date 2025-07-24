<?php
/**
 * Widget Areas Registration
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * サイドバーの登録
 */
function corporate_seo_pro_widgets_init() {
    // メインサイドバー
    register_sidebar( array(
        'name'          => esc_html__( 'サイドバー', 'corporate-seo-pro' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'サイドバーウィジェットエリア', 'corporate-seo-pro' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    // フッターウィジェット1
    register_sidebar( array(
        'name'          => esc_html__( 'フッターウィジェット 1', 'corporate-seo-pro' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'フッターの最初のウィジェットエリア', 'corporate-seo-pro' ),
        'before_widget' => '<section id="%1$s" class="widget footer-widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    // フッターウィジェット2
    register_sidebar( array(
        'name'          => esc_html__( 'フッターウィジェット 2', 'corporate-seo-pro' ),
        'id'            => 'footer-2',
        'description'   => esc_html__( 'フッターの2番目のウィジェットエリア', 'corporate-seo-pro' ),
        'before_widget' => '<section id="%1$s" class="widget footer-widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    // フッターウィジェット3
    register_sidebar( array(
        'name'          => esc_html__( 'フッターウィジェット 3', 'corporate-seo-pro' ),
        'id'            => 'footer-3',
        'description'   => esc_html__( 'フッターの3番目のウィジェットエリア', 'corporate-seo-pro' ),
        'before_widget' => '<section id="%1$s" class="widget footer-widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'corporate_seo_pro_widgets_init' );