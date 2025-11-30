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
    // Add timestamp to force cache refresh
    $version_with_time = $theme_version . '.' . time();
    
    // CSS files
    corporate_seo_pro_enqueue_styles( $version_with_time );
    
    // JavaScript files
    corporate_seo_pro_enqueue_scripts( $version_with_time );
    
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

    // Common mobile optimization
    wp_enqueue_style(
        'corporate-seo-pro-mobile-common',
        get_template_directory_uri() . '/assets/css/base/mobile-common.css',
        array( 'corporate-seo-pro-responsive' ),
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

    // Modern footer styles
    wp_enqueue_style(
        'corporate-seo-pro-footer-modern',
        get_template_directory_uri() . '/assets/css/components/footer-modern.css',
        array( 'corporate-seo-pro-footer' ),
        $version
    );

    // Footer mobile optimization
    wp_enqueue_style(
        'corporate-seo-pro-footer-mobile',
        get_template_directory_uri() . '/assets/css/components/footer-mobile.css',
        array( 'corporate-seo-pro-footer-modern' ),
        $version
    );

    wp_enqueue_style(
        'corporate-seo-pro-mobile-menu',
        get_template_directory_uri() . '/assets/css/components/mobile-menu.css',
        array( 'corporate-seo-pro-navigation' ),
        $version
    );

    // Header mobile optimization
    wp_enqueue_style(
        'corporate-seo-pro-header-mobile',
        get_template_directory_uri() . '/assets/css/components/header-mobile.css',
        array( 'corporate-seo-pro-navigation', 'corporate-seo-pro-mobile-menu' ),
        $version
    );

    // Chiba Subscription Banner
    wp_enqueue_style(
        'corporate-seo-pro-chiba-banner',
        get_template_directory_uri() . '/assets/css/components/chiba-subscription-banner.css',
        array( 'corporate-seo-pro-base' ),
        $version
    );

    // Download Modal
    wp_enqueue_style(
        'corporate-seo-pro-download-modal',
        get_template_directory_uri() . '/assets/css/components/download-modal.css',
        array( 'corporate-seo-pro-base' ),
        $version
    );

    // Service component (used on front page and service pages)
    wp_enqueue_style(
        'corporate-seo-pro-service',
        get_template_directory_uri() . '/assets/css/components/service.css',
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
    
    // Enhanced Features Section - Load last to override other styles
    wp_enqueue_style(
        'corporate-seo-pro-features-enhanced',
        get_template_directory_uri() . '/assets/css/features-section-enhanced.css',
        array( 'corporate-seo-pro-style', 'corporate-seo-pro-color-scheme' ),
        $version
    );

    // Modern Features Section (Front page only)
    if ( is_front_page() ) {
        wp_enqueue_style(
            'corporate-seo-pro-features-modern',
            get_template_directory_uri() . '/assets/css/features-section-modern.css',
            array( 'corporate-seo-pro-features-enhanced' ),
            $version
        );

        // Front page mobile optimization
        wp_enqueue_style(
            'corporate-seo-pro-front-page-mobile',
            get_template_directory_uri() . '/assets/css/pages/front-page-mobile.css',
            array( 'corporate-seo-pro-features-modern' ),
            $version
        );
    }
    
    // Page-specific styles (loaded conditionally)
    corporate_seo_pro_enqueue_conditional_styles( $version );
}

/**
 * 条件付きスタイルの読み込み
 */
function corporate_seo_pro_enqueue_conditional_styles( $version ) {
    // Service pages (service.css is already loaded globally, just load single service styles)
    if ( is_singular( 'service' ) ) {
        wp_enqueue_style(
            'corporate-seo-pro-single-service',
            get_template_directory_uri() . '/assets/css/pages/single-service.css',
            array( 'corporate-seo-pro-service' ),
            $version
        );

        // Modern single service styles
        wp_enqueue_style(
            'corporate-seo-pro-single-service-modern',
            get_template_directory_uri() . '/assets/css/pages/single-service-modern.css',
            array( 'corporate-seo-pro-single-service' ),
            $version
        );

        // Single service mobile optimization
        wp_enqueue_style(
            'corporate-seo-pro-single-service-mobile',
            get_template_directory_uri() . '/assets/css/pages/single-service-mobile.css',
            array( 'corporate-seo-pro-single-service-modern' ),
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
        
        // Contact page specific styles
        if ( is_page_template( 'page-contact.php' ) || is_page( 'contact' ) ) {
            wp_enqueue_style(
                'corporate-seo-pro-contact',
                get_template_directory_uri() . '/assets/css/pages/contact.css',
                array( 'corporate-seo-pro-pages' ),
                $version
            );

            // Contact page mobile optimization
            wp_enqueue_style(
                'corporate-seo-pro-contact-mobile',
                get_template_directory_uri() . '/assets/css/pages/contact-mobile.css',
                array( 'corporate-seo-pro-contact' ),
                $version
            );
        }
    }
    
    // Work (実績) pages
    if ( is_post_type_archive( 'work' ) || is_singular( 'work' ) ) {
        wp_enqueue_style(
            'corporate-seo-pro-work-mobile',
            get_template_directory_uri() . '/assets/css/pages/work-mobile.css',
            array( 'corporate-seo-pro-pages' ),
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

        // Blog filters CSS
        wp_enqueue_style(
            'corporate-seo-pro-blog-filters',
            get_template_directory_uri() . '/assets/css/components/blog-filters.css',
            array( 'corporate-seo-pro-base' ),
            $version
        );

        // Blog mobile optimization
        wp_enqueue_style(
            'corporate-seo-pro-blog-mobile',
            get_template_directory_uri() . '/assets/css/pages/blog-mobile.css',
            array( 'corporate-seo-pro-news-release' ),
            $version
        );

        // Newsletter CTA CSS for single posts
        if ( is_single() ) {
            wp_enqueue_style(
                'corporate-seo-pro-newsletter-cta',
                get_template_directory_uri() . '/assets/css/components/newsletter-cta.css',
                array( 'corporate-seo-pro-base' ),
                $version
            );
        }
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
        'corporate-seo-pro-navigation', 
        get_template_directory_uri() . '/assets/js/components/navigation.js', 
        array(), 
        $version, 
        true 
    );
    
    wp_enqueue_script( 
        'corporate-seo-pro-mobile-menu', 
        get_template_directory_uri() . '/assets/js/components/mobile-menu.js', 
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

    // Download Modal
    wp_enqueue_script(
        'corporate-seo-pro-download-modal',
        get_template_directory_uri() . '/assets/js/download-modal.js',
        array(),
        $version,
        true
    );

    // Localize download modal script with AJAX URL
    wp_localize_script( 'corporate-seo-pro-download-modal', 'corporate_seo_pro_ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
    ) );
    
    // Sticky header
    wp_enqueue_script( 
        'corporate-seo-pro-sticky-header', 
        get_template_directory_uri() . '/assets/js/components/sticky-header-handler.js', 
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
            get_template_directory_uri() . '/assets/js/features/hero-animations.js',
            array( 'corporate-seo-pro-animation-utils' ),
            $version,
            true
        );

        wp_enqueue_script(
            'corporate-seo-pro-hero-modern',
            get_template_directory_uri() . '/assets/js/features/hero-modern.js',
            array( 'corporate-seo-pro-animation-utils' ),
            $version,
            true
        );

        // Service scroll animations
        wp_enqueue_script(
            'corporate-seo-pro-service-scroll-animation',
            get_template_directory_uri() . '/assets/js/service-scroll-animation.js',
            array(),
            $version,
            true
        );
    }
    
    // Single post
    if ( is_singular( 'post' ) ) {
        wp_enqueue_script( 
            'corporate-seo-pro-single', 
            get_template_directory_uri() . '/assets/js/pages/single-post.js', 
            array(), 
            $version, 
            true 
        );
        
        wp_enqueue_script( 
            'corporate-seo-pro-toc', 
            get_template_directory_uri() . '/assets/js/components/toc.js', 
            array(), 
            $version, 
            true 
        );
        
        // TOCのスタイルを追加
        wp_enqueue_style( 
            'corporate-seo-pro-toc', 
            get_template_directory_uri() . '/assets/css/components/toc.css', 
            array( 'corporate-seo-pro-base' ), 
            $version 
        );
        
        // 記事詳細ページの目次レイアウト調整
        wp_enqueue_style( 
            'corporate-seo-pro-single-post-toc', 
            get_template_directory_uri() . '/assets/css/pages/single-post-toc.css', 
            array( 'corporate-seo-pro-toc' ), 
            $version 
        );
    }
    
    // Search page
    if ( is_search() ) {
        wp_enqueue_script( 
            'corporate-seo-pro-blog-search', 
            get_template_directory_uri() . '/assets/js/features/blog-search.js', 
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
            get_template_directory_uri() . '/assets/js/features/blog-archive.js', 
            array( 'corporate-seo-pro-animation-utils' ), 
            $version, 
            true 
        );
        
        // Blog search functionality
        wp_enqueue_script( 
            'corporate-seo-pro-blog-search', 
            get_template_directory_uri() . '/assets/js/features/blog-search.js', 
            array(), 
            $version, 
            true 
        );
        
        wp_localize_script( 'corporate-seo-pro-blog-search', 'wp_vars', array(
            'rest_url' => esc_url_raw( rest_url() ),
            'nonce' => wp_create_nonce( 'wp_rest' ),
        ) );
        
        // Blog filters
        wp_enqueue_script( 
            'corporate-seo-pro-blog-filters', 
            get_template_directory_uri() . '/assets/js/features/blog-filters.js', 
            array(), 
            $version, 
            true 
        );
        
        // Ajax用の変数をローカライズ
        wp_localize_script( 'corporate-seo-pro-blog-filters', 'corporate_seo_pro_ajax', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'blog_filter_nonce' ),
        ) );
    }
    
    // About page
    if ( is_page_template( 'page-about.php' ) ) {
        wp_enqueue_script(
            'corporate-seo-pro-about',
            get_template_directory_uri() . '/assets/js/pages/about-animations.js',
            array( 'corporate-seo-pro-animation-utils' ),
            $version,
            true
        );

        // About values visibility fix script
        wp_enqueue_script(
            'corporate-seo-pro-about-values-fix',
            get_template_directory_uri() . '/assets/js/pages/about-values-fix.js',
            array(),
            $version,
            true
        );

        // About page styles - Dynamic & Trustworthy Design
        wp_enqueue_style(
            'corporate-seo-pro-about',
            get_template_directory_uri() . '/assets/css/pages/about.css',
            array( 'corporate-seo-pro-base', 'corporate-seo-pro-style', 'corporate-seo-pro-color-scheme' ),
            $version
        );

        // About page mobile optimization
        wp_enqueue_style(
            'corporate-seo-pro-about-mobile',
            get_template_directory_uri() . '/assets/css/pages/about-mobile.css',
            array( 'corporate-seo-pro-about' ),
            $version
        );
    }
    
    // Contact page
    if ( is_page_template( 'page-contact.php' ) ) {
        // Forms override CSS (チェックボックス表示修正)
        wp_enqueue_style( 
            'corporate-seo-pro-forms-override', 
            get_template_directory_uri() . '/assets/css/forms-override.css', 
            array( 'corporate-seo-pro-forms' ), 
            $version 
        );
        // Contact form script
        wp_enqueue_script( 
            'corporate-seo-pro-contact', 
            get_template_directory_uri() . '/assets/js/pages/contact.js', 
            array(), 
            $version, 
            true 
        );
        
        // Contact form Ajax
        $ajax_data = array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'contact_form_submit' ),
        );
        
        wp_localize_script( 'corporate-seo-pro-contact', 'contact_ajax', $ajax_data );
    }
    
    // Thanks page
    if ( is_page_template( 'page-thanks.php' ) ) {
        wp_enqueue_style( 
            'corporate-seo-pro-thanks', 
            get_template_directory_uri() . '/assets/css/pages/page-thanks.css', 
            array( 'corporate-seo-pro-base' ), 
            $version 
        );
        
        wp_enqueue_script( 
            'corporate-seo-pro-thanks', 
            get_template_directory_uri() . '/assets/js/pages/thanks.js', 
            array(), 
            $version, 
            true 
        );
    }
    
    // FAQ page
    if ( is_page_template( 'page-faq.php' ) ) {
        wp_enqueue_style( 
            'corporate-seo-pro-faq', 
            get_template_directory_uri() . '/assets/css/pages/page-faq.css', 
            array( 'corporate-seo-pro-base' ), 
            $version 
        );
        
        wp_enqueue_script( 
            'corporate-seo-pro-faq', 
            get_template_directory_uri() . '/assets/js/pages/faq.js', 
            array(), 
            $version, 
            true 
        );
    }
    
    // Service pages
    if ( is_singular( 'service' ) || is_post_type_archive( 'service' ) || is_tax( 'service_category' ) ) {
        // Modern single service JavaScript
        if ( is_singular( 'service' ) ) {
            wp_enqueue_script(
                'corporate-seo-pro-single-service-modern',
                get_template_directory_uri() . '/assets/js/pages/single-service-modern.js',
                array(),
                $version,
                true
            );
        }

        wp_enqueue_script(
            'corporate-seo-pro-service', 
            get_template_directory_uri() . '/assets/js/pages/single-service.js', 
            array(), 
            $version, 
            true 
        );
        
        // Service features enhancement for archive pages
        if ( is_post_type_archive( 'service' ) || is_tax( 'service_category' ) ) {
            wp_enqueue_script( 
                'corporate-seo-pro-service-features', 
                get_template_directory_uri() . '/assets/js/service-features.js', 
                array(), 
                $version, 
                true 
            );
        }
        
        // Related services script for single service pages
        if ( is_singular( 'service' ) ) {
            wp_enqueue_script( 
                'corporate-seo-pro-related-services', 
                get_template_directory_uri() . '/assets/js/features/related-services.js', 
                array( 'corporate-seo-pro-animation-utils' ), 
                $version, 
                true 
            );
            
            // FAQ accordion script
            wp_enqueue_script( 
                'corporate-seo-pro-service-faq', 
                get_template_directory_uri() . '/assets/js/service-faq.js', 
                array(), 
                $version, 
                true 
            );
        }
    }
    
    // Work pages
    if ( is_post_type_archive( 'work' ) || is_singular( 'work' ) || is_tax( 'work_category' ) ) {
        wp_enqueue_script( 
            'corporate-seo-pro-work', 
            get_template_directory_uri() . '/assets/js/pages/work-archive.js', 
            array( 'jquery' ), 
            $version, 
            true 
        );
    }
    
    // Comment reply
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
    
    // GA4 Event Tracking - Load on all pages
    wp_enqueue_script( 
        'corporate-seo-pro-ga4-events', 
        get_template_directory_uri() . '/assets/js/ga4-events.js', 
        array(), 
        $version, 
        true 
    );
}

/**
 * 外部ライブラリの読み込み
 */
function corporate_seo_pro_enqueue_libraries() {
    // Google Fonts - Cormorant Garamond（エレガントなセリフ体）
    wp_enqueue_style(
        'google-fonts-cormorant',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap',
        array(),
        null
    );

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