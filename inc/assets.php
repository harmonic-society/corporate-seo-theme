<?php
/**
 * Scripts and Styles Enqueue Functions
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * スクリプトとスタイルの読み込み
 */
function corporate_seo_pro_scripts() {
    $theme_version = wp_get_theme()->get( 'Version' );
    
    // CSS files
    corporate_seo_pro_enqueue_styles( $theme_version );
    
    // JavaScript files
    corporate_seo_pro_enqueue_scripts( $theme_version );
    
    // Conditional assets
    corporate_seo_pro_enqueue_conditional_assets( $theme_version );
    
    // External libraries
    corporate_seo_pro_enqueue_libraries();
}
add_action( 'wp_enqueue_scripts', 'corporate_seo_pro_scripts' );

/**
 * CSSファイルの読み込み
 */
function corporate_seo_pro_enqueue_styles( $version ) {
    // メインスタイルシート
    wp_enqueue_style( 'corporate-seo-pro-style', get_stylesheet_uri(), array(), $version );
    
    // モジュール化されたCSSファイル
    $css_files = array(
        'typography'           => '/assets/css/typography.css',
        'navigation'           => '/assets/css/navigation.css',
        'buttons'              => '/assets/css/buttons.css',
        'hero'                 => '/assets/css/hero-section.css',
        'hero-modern'          => '/assets/css/hero-modern.css',
        'forms'                => '/assets/css/forms.css',
        'blog-archive'         => '/assets/css/blog-archive.css',
        'utilities'            => '/assets/css/utilities.css',
        'utilities-refactored' => '/assets/css/utilities-refactored.css',
        'cleanup'              => '/assets/css/cleanup.css',
        'header-footer'        => '/assets/css/header-footer-harmony.css',
        'header-footer-override'=> '/assets/css/header-footer-override.css',
        'footer-layout-fix'    => '/assets/css/footer-layout-fix.css',
        'footer-grid-override' => '/assets/css/footer-grid-override.css',
        'header-cta-fix'       => '/assets/css/header-cta-fix.css',
        'service-acf-fix'      => '/assets/css/service-acf-fix.css',
        'service-inquiry-fix'  => '/assets/css/service-inquiry-fix.css',
        'service-grid'         => '/assets/css/service-grid.css',
        'service-archive-mobile-fix' => '/assets/css/service-archive-mobile-fix.css',
        'mobile-menu-unified'  => '/assets/css/mobile-menu-unified.css',
    );
    
    // 依存関係の設定
    $dependencies = array(
        'utilities-refactored' => array( 'corporate-seo-pro-utilities' ),
        'cleanup'              => array( 'corporate-seo-pro-style' ),
        'header-footer-override'=> array( 'corporate-seo-pro-header-footer' ),
        'footer-layout-fix'    => array( 'corporate-seo-pro-header-footer' ),
        'footer-grid-override' => array( 'corporate-seo-pro-footer-layout-fix' ),
        'header-cta-fix'       => array( 'corporate-seo-pro-header-footer' ),
        'service-archive-mobile-fix' => array( 'corporate-seo-pro-style' ),
        'mobile-menu-unified'  => array( 'corporate-seo-pro-navigation' ),
    );
    
    foreach ( $css_files as $handle => $file ) {
        $deps = isset( $dependencies[ $handle ] ) ? $dependencies[ $handle ] : array();
        wp_enqueue_style( 
            'corporate-seo-pro-' . $handle, 
            get_template_directory_uri() . $file, 
            $deps, 
            $version 
        );
    }
}

/**
 * JavaScriptファイルの読み込み
 */
function corporate_seo_pro_enqueue_scripts( $version ) {
    // 共通スクリプト
    $js_files = array(
        'animation-utils'      => '/assets/js/utils/animation-utils.js',
        'mobile-menu-unified'  => '/assets/js/mobile-menu-unified.js',
        'theme'                => '/assets/js/theme.js',
    );
    
    // 依存関係の設定
    $dependencies = array(
        'theme' => array( 'corporate-seo-pro-animation-utils' ),
    );
    
    foreach ( $js_files as $handle => $file ) {
        $deps = isset( $dependencies[ $handle ] ) ? $dependencies[ $handle ] : array();
        wp_enqueue_script( 
            'corporate-seo-pro-' . $handle, 
            get_template_directory_uri() . $file, 
            $deps, 
            $version, 
            true 
        );
    }
    
    // ローカライズ
    wp_localize_script( 'corporate-seo-pro-theme', 'corporateSEOPro', array(
        'menuOpen'  => esc_html__( 'メニューを開く', 'corporate-seo-pro' ),
        'menuClose' => esc_html__( 'メニューを閉じる', 'corporate-seo-pro' ),
    ) );
}

/**
 * 条件付きアセットの読み込み
 */
function corporate_seo_pro_enqueue_conditional_assets( $version ) {
    // フロントページ
    if ( is_front_page() ) {
        wp_enqueue_script( 
            'corporate-seo-pro-hero-animations', 
            get_template_directory_uri() . '/assets/js/hero-animations-optimized.js', 
            array( 'corporate-seo-pro-animation-utils' ), 
            $version, 
            true 
        );
        wp_enqueue_script( 
            'corporate-seo-pro-hero-modern', 
            get_template_directory_uri() . '/assets/js/hero-modern.js', 
            array( 'corporate-seo-pro-animation-utils' ), 
            $version, 
            true 
        );
    }
    
    // ブログ詳細ページ
    if ( is_singular( 'post' ) ) {
        wp_enqueue_script( 
            'corporate-seo-pro-single', 
            get_template_directory_uri() . '/assets/js/single-post.js', 
            array(), 
            $version, 
            true 
        );
        wp_enqueue_script( 
            'corporate-seo-pro-toc', 
            get_template_directory_uri() . '/assets/js/toc.js', 
            array(), 
            $version, 
            true 
        );
    }
    
    // 検索ページ
    if ( is_search() ) {
        wp_enqueue_style( 
            'corporate-seo-pro-blog-search', 
            get_template_directory_uri() . '/assets/css/blog-search.css', 
            array( 'corporate-seo-pro-style' ), 
            $version 
        );
        wp_enqueue_script( 
            'corporate-seo-pro-blog-search', 
            get_template_directory_uri() . '/assets/js/blog-search.js', 
            array(), 
            $version, 
            true 
        );
        
        // REST APIのURLをJavaScriptに渡す
        wp_localize_script( 'corporate-seo-pro-blog-search', 'wp_vars', array(
            'rest_url' => esc_url_raw( rest_url() ),
            'nonce' => wp_create_nonce( 'wp_rest' ),
        ) );
    }
    
    // ブログアーカイブ
    if ( is_home() || is_archive() || is_category() || is_tag() ) {
        wp_enqueue_script( 
            'corporate-seo-pro-blog-archive', 
            get_template_directory_uri() . '/assets/js/blog-archive-optimized.js', 
            array( 'corporate-seo-pro-animation-utils' ), 
            $version, 
            true 
        );
    }
    
    // Aboutページ
    if ( is_page_template( 'page-about.php' ) ) {
        wp_enqueue_script( 
            'corporate-seo-pro-about', 
            get_template_directory_uri() . '/assets/js/about-animations-optimized.js', 
            array( 'corporate-seo-pro-animation-utils' ), 
            $version, 
            true 
        );
    }
    
    // Contactページ
    if ( is_page_template( 'page-contact.php' ) ) {
        wp_enqueue_script( 
            'corporate-seo-pro-contact', 
            get_template_directory_uri() . '/assets/js/contact.js', 
            array(), 
            $version, 
            true 
        );
        wp_enqueue_script( 
            'corporate-seo-pro-cf7-mobile-fix', 
            get_template_directory_uri() . '/assets/js/cf7-mobile-fix.js', 
            array(), 
            $version, 
            true 
        );
    }
    
    // サービス関連ページ
    if ( is_singular( 'service' ) || is_post_type_archive( 'service' ) || is_tax( 'service_category' ) ) {
        wp_enqueue_script( 
            'corporate-seo-pro-service', 
            get_template_directory_uri() . '/assets/js/single-service.js', 
            array(), 
            $version, 
            true 
        );
        
        // サービスページ用のACF修正CSS
        wp_enqueue_style( 
            'corporate-seo-pro-service-acf-fix', 
            get_template_directory_uri() . '/assets/css/service-acf-fix.css', 
            array( 'corporate-seo-pro-style' ), 
            $version 
        );
        
        // サービスページ用のCTA修正CSS
        wp_enqueue_style( 
            'corporate-seo-pro-service-inquiry-fix', 
            get_template_directory_uri() . '/assets/css/service-inquiry-fix.css', 
            array( 'corporate-seo-pro-style' ), 
            $version 
        );
    }
    
    // 実績ページ
    if ( is_post_type_archive( 'work' ) || is_singular( 'work' ) || is_tax( 'work_category' ) ) {
        wp_enqueue_script( 
            'corporate-seo-pro-work', 
            get_template_directory_uri() . '/assets/js/work-archive.js', 
            array( 'jquery' ), 
            $version, 
            true 
        );
    }
    
    // コメント返信
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

/**
 * 外部ライブラリの読み込み
 */
function corporate_seo_pro_enqueue_libraries() {
    // Font Awesome
    wp_enqueue_style( 
        'font-awesome', 
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', 
        array(), 
        '6.4.0' 
    );
}

/**
 * カスタムヘッダー用インラインスタイル
 */
function corporate_seo_pro_custom_header_styles() {
    $custom_css = "";
    
    // ヒーローセクションのグラデーション
    $custom_css .= "
        .hero-section.hero-gradient {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }
    ";
    
    if ( has_custom_header() ) {
        $custom_css .= "
            .hero-section {
                background-image: url(" . esc_url( get_header_image() ) . ");
                background-size: cover;
                background-position: center;
            }
        ";
    }
    
    wp_add_inline_style( 'corporate-seo-pro-style', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'corporate_seo_pro_custom_header_styles' );