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

/**
 * 高度なCritical CSS生成
 */
function corporate_seo_pro_generate_critical_css() {
    $critical_css = '';
    
    // Reset & Base styles
    $critical_css .= '
    /* Critical CSS for Core Web Vitals */
    * {
        box-sizing: border-box;
    }
    
    html {
        -webkit-text-size-adjust: 100%;
        font-size: 16px;
        scroll-behavior: smooth;
    }
    
    body {
        margin: 0;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Noto Sans JP", sans-serif;
        font-size: 1rem;
        line-height: 1.6;
        color: #1f2937;
        background-color: #fff;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    
    /* Layout Containers - Prevent CLS */
    .site-header {
        position: sticky;
        top: 0;
        z-index: 1000;
        background: #fff;
        min-height: 80px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    /* Typography - Above the fold */
    h1, h2, h3, h4, h5, h6 {
        margin-top: 0;
        margin-bottom: 1rem;
        font-weight: 700;
        line-height: 1.2;
        color: #111827;
    }
    
    h1 { font-size: 2.5rem; }
    h2 { font-size: 2rem; }
    h3 { font-size: 1.75rem; }
    
    p {
        margin-top: 0;
        margin-bottom: 1rem;
    }
    
    a {
        color: #3b82f6;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    a:hover {
        color: #2563eb;
        text-decoration: underline;
    }
    
    /* Images - Prevent CLS */
    img {
        max-width: 100%;
        height: auto;
        display: block;
    }
    
    /* Aspect ratio boxes for images */
    .aspect-ratio-box {
        position: relative;
        width: 100%;
        background: #f3f4f6;
    }
    
    .aspect-ratio-box::before {
        content: "";
        display: block;
        padding-top: 56.25%; /* 16:9 */
    }
    
    .aspect-ratio-box img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    /* Hero Section */
    .hero-section {
        min-height: 500px;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: white;
        padding: 4rem 0;
    }
    
    /* Navigation */
    .main-navigation {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 80px;
    }
    
    .main-navigation ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        gap: 2rem;
    }
    
    .main-navigation a {
        color: #374151;
        font-weight: 500;
        padding: 0.5rem 0;
        border-bottom: 2px solid transparent;
        transition: all 0.2s ease;
    }
    
    .main-navigation a:hover {
        color: #1e3a8a;
        border-bottom-color: #1e3a8a;
        text-decoration: none;
    }
    
    /* Buttons */
    .btn {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
        cursor: pointer;
        border: none;
        font-size: 1rem;
        line-height: 1.5;
    }
    
    .btn-primary {
        background-color: #3b82f6;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #2563eb;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    /* Grid System */
    .grid {
        display: grid;
        gap: 2rem;
    }
    
    @media (min-width: 768px) {
        .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-cols-4 { grid-template-columns: repeat(4, 1fr); }
    }
    
    /* Loading states */
    .skeleton {
        background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    /* Responsive utilities */
    @media (max-width: 767px) {
        .hide-mobile { display: none !important; }
        h1 { font-size: 2rem; }
        h2 { font-size: 1.5rem; }
        h3 { font-size: 1.25rem; }
        .hero-section { min-height: 400px; }
    }
    
    @media (min-width: 768px) {
        .hide-desktop { display: none !important; }
    }
    ';
    
    return $critical_css;
}

/**
 * インラインCritical CSSの出力
 */
function corporate_seo_pro_inline_critical_css() {
    if ( ! is_admin() ) {
        $critical_css = corporate_seo_pro_generate_critical_css();
        echo '<style id="critical-css">' . $critical_css . '</style>';
    }
}
add_action( 'wp_head', 'corporate_seo_pro_inline_critical_css', 1 );

/**
 * 非Critical CSSの遅延読み込み
 */
function corporate_seo_pro_defer_non_critical_css() {
    ?>
    <script>
    // Load non-critical CSS
    function loadCSS(href) {
        var link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = href;
        link.media = 'print';
        link.onload = function() { this.media = 'all'; };
        document.head.appendChild(link);
    }
    
    // Preload critical fonts
    if ('requestIdleCallback' in window) {
        requestIdleCallback(function() {
            var links = document.querySelectorAll('link[data-deferred="true"]');
            links.forEach(function(link) {
                link.removeAttribute('data-deferred');
                link.media = 'all';
            });
        });
    }
    </script>
    <?php
}
add_action( 'wp_footer', 'corporate_seo_pro_defer_non_critical_css', 20 );

/**
 * リソースヒントの高度な実装
 */
function corporate_seo_pro_advanced_resource_hints() {
    // Preconnect
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    
    // DNS Prefetch
    $domains = array(
        '//www.google-analytics.com',
        '//www.googletagmanager.com',
        '//ajax.googleapis.com',
        '//cdnjs.cloudflare.com',
    );
    
    foreach ( $domains as $domain ) {
        echo '<link rel="dns-prefetch" href="' . $domain . '">' . "\n";
    }
    
    // Preload critical resources
    $theme_uri = get_template_directory_uri();
    
    // Preload fonts
    echo '<link rel="preload" as="font" type="font/woff2" href="' . $theme_uri . '/assets/fonts/noto-sans-jp-v42-latin_japanese-regular.woff2" crossorigin>' . "\n";
    echo '<link rel="preload" as="font" type="font/woff2" href="' . $theme_uri . '/assets/fonts/noto-sans-jp-v42-latin_japanese-700.woff2" crossorigin>' . "\n";
    
    // Preload critical images
    if ( is_front_page() ) {
        $hero_image = get_theme_mod( 'hero_image' );
        if ( $hero_image ) {
            echo '<link rel="preload" as="image" href="' . esc_url( $hero_image ) . '">' . "\n";
        }
    }
}
add_action( 'wp_head', 'corporate_seo_pro_advanced_resource_hints', 2 );

/**
 * Lazy loading with native support and fallback
 */
function corporate_seo_pro_enhanced_lazy_loading() {
    ?>
    <script>
    // Enhanced lazy loading with Intersection Observer
    if ('loading' in HTMLImageElement.prototype) {
        // Native lazy loading is supported
        document.querySelectorAll('img[loading="lazy"]').forEach(img => {
            img.src = img.dataset.src || img.src;
        });
    } else {
        // Fallback for older browsers
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src || img.src;
                    img.classList.add('loaded');
                    observer.unobserve(img);
                }
            });
        }, {
            rootMargin: '50px 0px',
            threshold: 0.01
        });
        
        document.querySelectorAll('img[loading="lazy"]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    </script>
    <?php
}
add_action( 'wp_footer', 'corporate_seo_pro_enhanced_lazy_loading', 25 );

/**
 * Web Font最適化
 */
function corporate_seo_pro_optimize_web_fonts() {
    ?>
    <script>
    // Font loading optimization
    if ('fonts' in document) {
        Promise.all([
            document.fonts.load('400 1em Noto Sans JP'),
            document.fonts.load('700 1em Noto Sans JP')
        ]).then(() => {
            document.documentElement.classList.add('fonts-loaded');
        });
    }
    </script>
    <style>
    /* FOUT対策 */
    .fonts-loaded body {
        font-family: 'Noto Sans JP', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }
    </style>
    <?php
}
add_action( 'wp_head', 'corporate_seo_pro_optimize_web_fonts', 30 );

/**
 * Service Worker登録（PWA対応）
 */
function corporate_seo_pro_register_service_worker() {
    if ( ! is_admin() ) {
        ?>
        <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('<?php echo home_url( '/sw.js' ); ?>').then(registration => {
                    console.log('ServiceWorker registration successful');
                }).catch(err => {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
        </script>
        <?php
    }
}
add_action( 'wp_footer', 'corporate_seo_pro_register_service_worker', 30 );