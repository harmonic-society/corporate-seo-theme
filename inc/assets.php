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
        'header-cta-fix'       => '/assets/css/header-cta-fix.css',
        'service-acf-fix'      => '/assets/css/service-acf-fix.css',
        'service-inquiry-fix'  => '/assets/css/service-inquiry-fix.css',
        'service-grid'         => '/assets/css/service-grid.css',
        'service-archive-mobile-fix' => '/assets/css/service-archive-mobile-fix.css',
        'mobile-menu-unified'  => '/assets/css/mobile-menu-unified.css',
        'mobile-menu-button-fix' => '/assets/css/mobile-menu-button-fix.css',
        'mobile-overflow-fix'  => '/assets/css/mobile-overflow-fix.css',
        'mobile-logo-fix'      => '/assets/css/mobile-logo-fix.css',
        'mobile-menu-modal'    => '/assets/css/mobile-menu-modal.css',
        'hamburger-fix'        => '/assets/css/hamburger-fix.css',
        'tablet-optimization'  => '/assets/css/tablet-optimization.css',
        'tablet-touch-styles'  => '/assets/css/tablet-touch-styles.css',
        'page-about'           => '/assets/css/page-about.css',
        'page-services-archive'=> '/assets/css/page-services-archive.css',
        'page-blog-archive'    => '/assets/css/page-blog-archive.css',
        'page-works-archive'   => '/assets/css/page-works-archive.css',
        'page-contact'         => '/assets/css/page-contact.css',
        'news-release'         => '/assets/css/components/news-release.css',
    );
    
    // 依存関係の設定
    $dependencies = array(
        'utilities-refactored' => array( 'corporate-seo-pro-utilities' ),
        'cleanup'              => array( 'corporate-seo-pro-style' ),
        'header-footer-override'=> array( 'corporate-seo-pro-header-footer' ),
        'header-cta-fix'       => array( 'corporate-seo-pro-header-footer' ),
        'service-archive-mobile-fix' => array( 'corporate-seo-pro-style' ),
        'mobile-menu-unified'  => array( 'corporate-seo-pro-navigation' ),
        'mobile-menu-button-fix' => array( 'corporate-seo-pro-mobile-menu-unified' ),
        'mobile-overflow-fix'  => array( 'corporate-seo-pro-style' ),
        'mobile-logo-fix'      => array( 'corporate-seo-pro-navigation' ),
        'mobile-menu-modal'    => array( 'corporate-seo-pro-style' ),
        'hamburger-fix'        => array( 'corporate-seo-pro-mobile-menu-modal' ),
        'tablet-optimization'  => array( 'corporate-seo-pro-style', 'corporate-seo-pro-typography', 'corporate-seo-pro-utilities' ),
        'tablet-touch-styles'  => array( 'corporate-seo-pro-tablet-optimization' ),
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
        'tablet-detection'     => '/assets/js/utils/tablet-detection.js',
        'mobile-menu-modal'    => '/assets/js/mobile-menu-modal.js',
        'tablet-optimizations' => '/assets/js/tablet-optimizations.js',
        'tablet-menu-enhancements' => '/assets/js/tablet-menu-enhancements.js',
        'theme'                => '/assets/js/theme.js',
    );
    
    // 依存関係の設定
    $dependencies = array(
        'tablet-optimizations' => array( 'corporate-seo-pro-tablet-detection' ),
        'tablet-menu-enhancements' => array( 'corporate-seo-pro-tablet-detection', 'corporate-seo-pro-mobile-menu-modal' ),
        'theme' => array( 'corporate-seo-pro-animation-utils', 'corporate-seo-pro-tablet-detection' ),
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
        // Service mobile image fix CSS
        wp_enqueue_style( 
            'corporate-seo-pro-service-mobile-image-fix', 
            get_template_directory_uri() . '/assets/css/service-mobile-image-fix.css', 
            array( 'corporate-seo-pro-style' ), 
            $version 
        );
        // Hero feature fix CSS
        wp_enqueue_style( 
            'corporate-seo-pro-hero-feature-fix', 
            get_template_directory_uri() . '/assets/css/hero-feature-fix.css', 
            array( 'corporate-seo-pro-style' ), 
            $version 
        );
        
        // Hero description animation fix CSS
        wp_enqueue_style( 
            'corporate-seo-pro-hero-description-fix', 
            get_template_directory_uri() . '/assets/css/hero-description-fix.css', 
            array( 'corporate-seo-pro-hero-modern' ), 
            $version 
        );
        
        wp_enqueue_script( 
            'corporate-seo-pro-hero-animations', 
            get_template_directory_uri() . '/assets/js/hero-animations-optimized.js', 
            array( 'corporate-seo-pro-animation-utils' ), 
            $version, 
            true 
        );
        
        // Hero feature fix JS
        wp_enqueue_script( 
            'corporate-seo-pro-hero-feature-fix', 
            get_template_directory_uri() . '/assets/js/hero-feature-fix.js', 
            array(), 
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
        // ブログアーカイブページCSS
        if ( is_home() ) {
            wp_enqueue_style( 
                'corporate-seo-pro-page-blog-archive', 
                get_template_directory_uri() . '/assets/css/page-blog-archive.css', 
                array( 'corporate-seo-pro-style' ), 
                $version 
            );
        }
        
        // Blog archive mobile fix CSS
        wp_enqueue_style( 
            'corporate-seo-pro-blog-archive-mobile-fix', 
            get_template_directory_uri() . '/assets/css/blog-archive-mobile-fix.css', 
            array( 'corporate-seo-pro-style' ), 
            $version,
            'screen and (max-width: 768px)'
        );
        
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
        wp_enqueue_style( 
            'corporate-seo-pro-page-about', 
            get_template_directory_uri() . '/assets/css/page-about.css', 
            array( 'corporate-seo-pro-style' ), 
            $version 
        );
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
        wp_enqueue_style( 
            'corporate-seo-pro-page-contact', 
            get_template_directory_uri() . '/assets/css/page-contact.css', 
            array( 'corporate-seo-pro-style' ), 
            $version 
        );
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
        // サービスアーカイブページ
        if ( is_post_type_archive( 'service' ) ) {
            wp_enqueue_style( 
                'corporate-seo-pro-page-services-archive', 
                get_template_directory_uri() . '/assets/css/page-services-archive.css', 
                array( 'corporate-seo-pro-style' ), 
                $version 
            );
        }
        
        // サービス詳細ページ
        if ( is_singular( 'service' ) ) {
            wp_enqueue_style( 
                'corporate-seo-pro-single-service', 
                get_template_directory_uri() . '/assets/css/pages/single-service.css', 
                array( 'corporate-seo-pro-style' ), 
                $version 
            );
            
            // サービス詳細ページのモバイル修正CSS
            wp_enqueue_style( 
                'corporate-seo-pro-single-service-mobile-fix', 
                get_template_directory_uri() . '/assets/css/single-service-mobile-fix.css', 
                array( 'corporate-seo-pro-single-service' ), 
                $version 
            );
            
            // サービス詳細ページの特徴セクション修正CSS
            wp_enqueue_style( 
                'corporate-seo-pro-single-service-features-fix', 
                get_template_directory_uri() . '/assets/css/single-service-features-fix.css', 
                array( 'corporate-seo-pro-single-service' ), 
                $version 
            );
        }
        
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
        
        // サービス詳細ページ用のお問い合わせセクションモバイル修正CSS
        if ( is_singular( 'service' ) ) {
            wp_enqueue_style( 
                'corporate-seo-pro-single-service-inquiry-mobile-fix', 
                get_template_directory_uri() . '/assets/css/single-service-inquiry-mobile-fix.css', 
                array( 'corporate-seo-pro-service-inquiry-fix' ), 
                $version 
            );
            
            // サービス詳細ページの統合修正CSS（最高優先度）
            wp_enqueue_style( 
                'corporate-seo-pro-single-service-unified-fix', 
                get_template_directory_uri() . '/assets/css/single-service-unified-fix.css', 
                array( 
                    'corporate-seo-pro-style',
                    'corporate-seo-pro-cleanup',
                    'corporate-seo-pro-single-service',
                    'corporate-seo-pro-single-service-mobile-fix',
                    'corporate-seo-pro-single-service-features-fix',
                    'corporate-seo-pro-single-service-inquiry-mobile-fix'
                ), 
                $version 
            );
        }
    }
    
    // 実績ページ
    if ( is_post_type_archive( 'work' ) || is_singular( 'work' ) || is_tax( 'work_category' ) ) {
        // 実績アーカイブページ
        if ( is_post_type_archive( 'work' ) ) {
            wp_enqueue_style( 
                'corporate-seo-pro-page-works-archive', 
                get_template_directory_uri() . '/assets/css/page-works-archive.css', 
                array( 'corporate-seo-pro-style' ), 
                $version 
            );
        }
        
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