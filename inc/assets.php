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
    
    // Base styles (order matters!)
    wp_enqueue_style( 
        'corporate-seo-pro-base', 
        get_template_directory_uri() . '/assets/css/base/base.css', 
        array( 'corporate-seo-pro-style' ), 
        $version 
    );
    
    wp_enqueue_style( 
        'corporate-seo-pro-typography', 
        get_template_directory_uri() . '/assets/css/base/typography.css', 
        array( 'corporate-seo-pro-base' ), 
        $version 
    );
    
    wp_enqueue_style( 
        'corporate-seo-pro-responsive', 
        get_template_directory_uri() . '/assets/css/base/responsive.css', 
        array( 'corporate-seo-pro-base' ), 
        $version 
    );
    
    // Utilities
    wp_enqueue_style( 
        'corporate-seo-pro-utilities', 
        get_template_directory_uri() . '/assets/css/utilities/utilities.css', 
        array( 'corporate-seo-pro-base' ), 
        $version 
    );
    
    // Components
    wp_enqueue_style( 
        'corporate-seo-pro-navigation', 
        get_template_directory_uri() . '/assets/css/components/navigation.css', 
        array( 'corporate-seo-pro-base' ), 
        $version 
    );
    
    wp_enqueue_style( 
        'corporate-seo-pro-buttons', 
        get_template_directory_uri() . '/assets/css/components/buttons.css', 
        array( 'corporate-seo-pro-base' ), 
        $version 
    );
    
    wp_enqueue_style( 
        'corporate-seo-pro-forms', 
        get_template_directory_uri() . '/assets/css/components/forms.css', 
        array( 'corporate-seo-pro-base' ), 
        $version 
    );
    
    wp_enqueue_style( 
        'corporate-seo-pro-hero', 
        get_template_directory_uri() . '/assets/css/components/hero.css', 
        array( 'corporate-seo-pro-base' ), 
        $version 
    );
    
    wp_enqueue_style( 
        'corporate-seo-pro-footer', 
        get_template_directory_uri() . '/assets/css/components/footer.css', 
        array( 'corporate-seo-pro-base' ), 
        $version 
    );
    
    // Color scheme
    wp_enqueue_style( 
        'corporate-seo-pro-color-scheme', 
        get_template_directory_uri() . '/assets/css/color-scheme-teal.css', 
        array( 'corporate-seo-pro-base' ), 
        $version 
    );
    
    // Page-specific styles (loaded conditionally)
    corporate_seo_pro_enqueue_conditional_styles( $version );
}

/**
 * 条件付きスタイルの読み込み
 */
function corporate_seo_pro_enqueue_conditional_styles( $version ) {
    // Service pages
    if ( is_singular( 'service' ) || is_post_type_archive( 'service' ) || is_tax( 'service_category' ) ) {
        wp_enqueue_style( 
            'corporate-seo-pro-service', 
            get_template_directory_uri() . '/assets/css/components/service.css', 
            array( 'corporate-seo-pro-base' ), 
            $version 
        );
    }
    
    // All page templates
    if ( is_page() ) {
        wp_enqueue_style( 
            'corporate-seo-pro-pages', 
            get_template_directory_uri() . '/assets/css/pages/pages.css', 
            array( 'corporate-seo-pro-base' ), 
            $version 
        );
    }
    
    // News/Blog components
    if ( is_home() || is_archive() || is_single() || is_category() || is_tag() ) {
        wp_enqueue_style( 
            'corporate-seo-pro-news-release', 
            get_template_directory_uri() . '/assets/css/components/news-release.css', 
            array( 'corporate-seo-pro-base' ), 
            $version 
        );
    }
}

/**
 * JavaScriptファイルの読み込み
 */
function corporate_seo_pro_enqueue_scripts( $version ) {
    // Utility scripts
    wp_enqueue_script( 
        'corporate-seo-pro-animation-utils', 
        get_template_directory_uri() . '/assets/js/utils/animation-utils.js', 
        array(), 
        $version, 
        true 
    );
    
    wp_enqueue_script( 
        'corporate-seo-pro-tablet-detection', 
        get_template_directory_uri() . '/assets/js/utils/tablet-detection.js', 
        array(), 
        $version, 
        true 
    );
    
    // Navigation
    wp_enqueue_script( 
        'corporate-seo-pro-mobile-menu', 
        get_template_directory_uri() . '/assets/js/mobile-menu-modal.js', 
        array(), 
        $version, 
        true 
    );
    
    // Main theme script
    wp_enqueue_script( 
        'corporate-seo-pro-theme', 
        get_template_directory_uri() . '/assets/js/theme.js', 
        array( 'corporate-seo-pro-animation-utils', 'corporate-seo-pro-tablet-detection' ), 
        $version, 
        true 
    );
    
    // Sticky header
    wp_enqueue_script( 
        'corporate-seo-pro-sticky-header', 
        get_template_directory_uri() . '/assets/js/sticky-header-handler.js', 
        array( 'corporate-seo-pro-theme' ), 
        $version, 
        true 
    );
    
    // Localization
    wp_localize_script( 'corporate-seo-pro-theme', 'corporateSEOPro', array(
        'menuOpen'  => esc_html__( 'メニューを開く', 'corporate-seo-pro' ),
        'menuClose' => esc_html__( 'メニューを閉じる', 'corporate-seo-pro' ),
    ) );
    
    // Conditional scripts
    corporate_seo_pro_enqueue_conditional_scripts( $version );
}

/**
 * 条件付きスクリプトの読み込み
 */
function corporate_seo_pro_enqueue_conditional_scripts( $version ) {
    // Front page
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
    
    // Single post
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
    
    // Search page
    if ( is_search() ) {
        wp_enqueue_script( 
            'corporate-seo-pro-blog-search', 
            get_template_directory_uri() . '/assets/js/blog-search.js', 
            array(), 
            $version, 
            true 
        );
        
        wp_localize_script( 'corporate-seo-pro-blog-search', 'wp_vars', array(
            'rest_url' => esc_url_raw( rest_url() ),
            'nonce' => wp_create_nonce( 'wp_rest' ),
        ) );
    }
    
    // Blog archive
    if ( is_home() || is_archive() || is_category() || is_tag() ) {
        wp_enqueue_script( 
            'corporate-seo-pro-blog-archive', 
            get_template_directory_uri() . '/assets/js/blog-archive-optimized.js', 
            array( 'corporate-seo-pro-animation-utils' ), 
            $version, 
            true 
        );
    }
    
    // About page
    if ( is_page_template( 'page-about.php' ) ) {
        wp_enqueue_script( 
            'corporate-seo-pro-about', 
            get_template_directory_uri() . '/assets/js/about-animations-optimized.js', 
            array( 'corporate-seo-pro-animation-utils' ), 
            $version, 
            true 
        );
    }
    
    // Contact page
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
    
    // Service pages
    if ( is_singular( 'service' ) || is_post_type_archive( 'service' ) || is_tax( 'service_category' ) ) {
        wp_enqueue_script( 
            'corporate-seo-pro-service', 
            get_template_directory_uri() . '/assets/js/single-service.js', 
            array(), 
            $version, 
            true 
        );
    }
    
    // Work pages
    if ( is_post_type_archive( 'work' ) || is_singular( 'work' ) || is_tax( 'work_category' ) ) {
        wp_enqueue_script( 
            'corporate-seo-pro-work', 
            get_template_directory_uri() . '/assets/js/work-archive.js', 
            array( 'jquery' ), 
            $version, 
            true 
        );
    }
    
    // Comment reply
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