<?php
/**
 * テーマの機能とセットアップ
 *
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * テーマのセットアップ
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

    // メニューの登録
    register_nav_menus( array(
        'primary'   => esc_html__( 'プライマリーメニュー', 'corporate-seo-pro' ),
        'footer'    => esc_html__( 'フッターメニュー', 'corporate-seo-pro' ),
        'footer-1'  => esc_html__( 'フッターメニュー1（サービス）', 'corporate-seo-pro' ),
        'footer-2'  => esc_html__( 'フッターメニュー2（会社情報）', 'corporate-seo-pro' ),
        'social'    => esc_html__( 'ソーシャルメニュー', 'corporate-seo-pro' ),
        'mobile'    => esc_html__( 'モバイルメニュー', 'corporate-seo-pro' ),
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
 * デフォルトメニューの作成
 */
function corporate_seo_pro_create_default_menu() {
    // プライマリーメニューが存在しない場合のみ作成
    $menu_name = 'Primary Menu';
    $menu_exists = wp_get_nav_menu_object( $menu_name );
    
    if ( ! $menu_exists ) {
        $menu_id = wp_create_nav_menu( $menu_name );
        
        // メニュー項目を追加
        $menu_items = array(
            'HOME' => home_url( '/' ),
            'ABOUT' => home_url( '/about/' ),
            'SERVICE' => home_url( '/services/' ),
            'BLOG' => home_url( '/blog/' ),
            'WORKS' => home_url( '/works/' ),
            'CONTACT' => home_url( '/contact/' )
        );
        
        $position = 1;
        foreach ( $menu_items as $title => $url ) {
            wp_update_nav_menu_item( $menu_id, 0, array(
                'menu-item-title'   => $title,
                'menu-item-url'     => $url,
                'menu-item-status'  => 'publish',
                'menu-item-position' => $position++
            ) );
        }
        
        // メニューを位置に割り当て
        $locations = get_theme_mod( 'nav_menu_locations' );
        $locations['primary'] = $menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );
    }
    
    // フッターメニュー1（サービス）のデフォルト作成
    $footer1_menu_name = 'Footer Menu 1';
    $footer1_exists = wp_get_nav_menu_object( $footer1_menu_name );
    
    if ( ! $footer1_exists ) {
        $footer1_id = wp_create_nav_menu( $footer1_menu_name );
        
        // フッターメニュー1の項目を追加（実際のサービス投稿から取得）
        $footer1_items = array();
        
        // 実際のサービス投稿を取得
        $services = get_posts( array(
            'post_type'      => 'service',
            'posts_per_page' => 6,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
            'post_status'    => 'publish'
        ) );
        
        if ( ! empty( $services ) ) {
            foreach ( $services as $service ) {
                $footer1_items[ $service->post_title ] = get_permalink( $service->ID );
            }
        } else {
            // サービス投稿がない場合のデフォルト
            $footer1_items = array(
                'AIコンサルティング' => home_url( '/services/#ai-consulting' ),
                'DXソリューション' => home_url( '/services/#dx-solution' ),
                'データ分析' => home_url( '/services/#data-analysis' ),
                'システム開発' => home_url( '/services/#system-development' )
            );
        }
        
        $position = 1;
        foreach ( $footer1_items as $title => $url ) {
            wp_update_nav_menu_item( $footer1_id, 0, array(
                'menu-item-title'   => $title,
                'menu-item-url'     => $url,
                'menu-item-status'  => 'publish',
                'menu-item-position' => $position++
            ) );
        }
        
        // メニューを位置に割り当て
        $locations = get_theme_mod( 'nav_menu_locations' );
        $locations['footer-1'] = $footer1_id;
        set_theme_mod( 'nav_menu_locations', $locations );
    }
    
    // フッターメニュー2（会社情報）のデフォルト作成
    $footer2_menu_name = 'Footer Menu 2';
    $footer2_exists = wp_get_nav_menu_object( $footer2_menu_name );
    
    if ( ! $footer2_exists ) {
        $footer2_id = wp_create_nav_menu( $footer2_menu_name );
        
        // フッターメニュー2の項目を追加
        $footer2_items = array(
            '会社概要' => home_url( '/about/' ),
            '代表メッセージ' => home_url( '/about/#message' ),
            '採用情報' => home_url( '/careers/' ),
            'ニュース' => home_url( '/news/' )
        );
        
        $position = 1;
        foreach ( $footer2_items as $title => $url ) {
            wp_update_nav_menu_item( $footer2_id, 0, array(
                'menu-item-title'   => $title,
                'menu-item-url'     => $url,
                'menu-item-status'  => 'publish',
                'menu-item-position' => $position++
            ) );
        }
        
        // メニューを位置に割り当て
        $locations = get_theme_mod( 'nav_menu_locations' );
        $locations['footer-2'] = $footer2_id;
        set_theme_mod( 'nav_menu_locations', $locations );
    }
}
add_action( 'after_setup_theme', 'corporate_seo_pro_create_default_menu' );

/**
 * フォールバックメニュー
 */
function corporate_seo_pro_fallback_menu() {
    ?>
    <ul id="primary-menu" class="menu">
        <li class="menu-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="menu-text">HOME</span><span class="menu-line"></span></a></li>
        <li class="menu-item"><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><span class="menu-text">ABOUT</span><span class="menu-line"></span></a></li>
        <li class="menu-item"><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><span class="menu-text">SERVICE</span><span class="menu-line"></span></a></li>
        <li class="menu-item"><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><span class="menu-text">BLOG</span><span class="menu-line"></span></a></li>
        <li class="menu-item"><a href="<?php echo esc_url( home_url( '/works/' ) ); ?>"><span class="menu-text">WORKS</span><span class="menu-line"></span></a></li>
        <li class="menu-item"><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><span class="menu-text">CONTACT</span><span class="menu-line"></span></a></li>
    </ul>
    <?php
}

/**
 * コンテンツ幅の設定
 */
function corporate_seo_pro_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'corporate_seo_pro_content_width', 1200 );
}
add_action( 'after_setup_theme', 'corporate_seo_pro_content_width', 0 );

/**
 * スクリプトとスタイルの読み込み
 */
function corporate_seo_pro_scripts() {
    // メインスタイルシート
    wp_enqueue_style( 'corporate-seo-pro-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
    
    // モジュール化されたCSSファイル
    wp_enqueue_style( 'corporate-seo-pro-typography', get_template_directory_uri() . '/assets/css/typography.css', array(), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'corporate-seo-pro-navigation', get_template_directory_uri() . '/assets/css/navigation.css', array(), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'corporate-seo-pro-buttons', get_template_directory_uri() . '/assets/css/buttons.css', array(), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'corporate-seo-pro-hero', get_template_directory_uri() . '/assets/css/hero-section.css', array(), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'corporate-seo-pro-hero-modern', get_template_directory_uri() . '/assets/css/hero-modern.css', array(), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'corporate-seo-pro-forms', get_template_directory_uri() . '/assets/css/forms.css', array(), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'corporate-seo-pro-blog-archive', get_template_directory_uri() . '/assets/css/blog-archive.css', array(), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'corporate-seo-pro-utilities', get_template_directory_uri() . '/assets/css/utilities.css', array(), wp_get_theme()->get( 'Version' ) );
    
    // リファクタリングされたユーティリティCSS（!important削減版）
    wp_enqueue_style( 'corporate-seo-pro-utilities-refactored', get_template_directory_uri() . '/assets/css/utilities-refactored.css', array('corporate-seo-pro-utilities'), wp_get_theme()->get( 'Version' ) );
    
    // クリーンアップCSS（競合を解決）
    wp_enqueue_style( 'corporate-seo-pro-cleanup', get_template_directory_uri() . '/assets/css/cleanup.css', array('corporate-seo-pro-style'), wp_get_theme()->get( 'Version' ) );
    
    // モバイルアニメーション修正CSS（全ページで読み込み）
    wp_enqueue_style( 'corporate-seo-pro-mobile-animations-fix', get_template_directory_uri() . '/assets/css/mobile-animations-fix.css', array('corporate-seo-pro-style'), wp_get_theme()->get( 'Version' ) );
    
    // Harmonic Society ヘッダー＆フッターデザイン
    wp_enqueue_style( 'corporate-seo-pro-header-footer', get_template_directory_uri() . '/assets/css/header-footer-harmony.css', array('corporate-seo-pro-style'), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'corporate-seo-pro-header-footer-override', get_template_directory_uri() . '/assets/css/header-footer-override.css', array('corporate-seo-pro-header-footer'), wp_get_theme()->get( 'Version' ) );
    
    // モバイルメニュー修正CSS
    wp_enqueue_style( 'corporate-seo-pro-mobile-menu-fix', get_template_directory_uri() . '/assets/css/mobile-menu-fix.css', array('corporate-seo-pro-header-footer'), wp_get_theme()->get( 'Version' ) );
    
    // アニメーションユーティリティ（全ページで必要）
    wp_enqueue_script( 'corporate-seo-pro-animation-utils', get_template_directory_uri() . '/assets/js/utils/animation-utils.js', array(), wp_get_theme()->get( 'Version' ), true );
    
    // ヘッダーナビゲーションスクリプト
    wp_enqueue_script( 'corporate-seo-pro-header-nav', get_template_directory_uri() . '/assets/js/header-navigation.js', array(), wp_get_theme()->get( 'Version' ), true );
    
    // モバイルメニュー修正スクリプト
    wp_enqueue_script( 'corporate-seo-pro-mobile-menu-fix', get_template_directory_uri() . '/assets/js/mobile-menu-fix.js', array(), wp_get_theme()->get( 'Version' ), true );
    
    // 統合されたメインスクリプト
    wp_enqueue_script( 'corporate-seo-pro-theme', get_template_directory_uri() . '/assets/js/theme.js', array('corporate-seo-pro-animation-utils'), wp_get_theme()->get( 'Version' ), true );
    
    // モダンヒーローセクション用スクリプト
    if ( is_front_page() ) {
        // 最適化されたhero-animationsを使用
        wp_enqueue_script( 'corporate-seo-pro-hero-animations', get_template_directory_uri() . '/assets/js/hero-animations-optimized.js', array('corporate-seo-pro-animation-utils'), wp_get_theme()->get( 'Version' ), true );
        wp_enqueue_script( 'corporate-seo-pro-hero-modern', get_template_directory_uri() . '/assets/js/hero-modern.js', array('corporate-seo-pro-animation-utils'), wp_get_theme()->get( 'Version' ), true );
    }
    
    // レスポンシブナビゲーション用
    wp_localize_script( 'corporate-seo-pro-theme', 'corporateSEOPro', array(
        'menuOpen'  => esc_html__( 'メニューを開く', 'corporate-seo-pro' ),
        'menuClose' => esc_html__( 'メニューを閉じる', 'corporate-seo-pro' ),
    ) );
    
    // ブログ詳細ページスクリプト
    if ( is_singular( 'post' ) ) {
        wp_enqueue_script( 'corporate-seo-pro-single', get_template_directory_uri() . '/assets/js/single-post.js', array(), wp_get_theme()->get( 'Version' ), true );
        wp_enqueue_script( 'corporate-seo-pro-toc', get_template_directory_uri() . '/assets/js/toc.js', array(), wp_get_theme()->get( 'Version' ), true );
    }
    
    // Font Awesomeの読み込み（全ページで使用）
    wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0' );
    
    // ブログページ用のスタイルとスクリプト
    if ( is_search() ) {
        wp_enqueue_style( 'corporate-seo-pro-blog-search', get_template_directory_uri() . '/assets/css/blog-search.css', array('corporate-seo-pro-style'), wp_get_theme()->get( 'Version' ) );
        wp_enqueue_script( 'corporate-seo-pro-blog-search', get_template_directory_uri() . '/assets/js/blog-search.js', array(), wp_get_theme()->get( 'Version' ), true );
        
        // REST APIのURLをJavaScriptに渡す
        wp_localize_script( 'corporate-seo-pro-blog-search', 'wp_vars', array(
            'rest_url' => esc_url_raw( rest_url() ),
            'nonce' => wp_create_nonce( 'wp_rest' ),
        ) );
    }
    
    // ブログアーカイブページのスタイルとスクリプト
    if ( is_home() || is_archive() || is_category() || is_tag() ) {
        wp_enqueue_style( 'corporate-seo-pro-blog-archive', get_template_directory_uri() . '/assets/css/blog-archive.css', array('corporate-seo-pro-style'), wp_get_theme()->get( 'Version' ) );
        // 最適化されたblog-archiveスクリプトを使用
        wp_enqueue_script( 'corporate-seo-pro-blog-archive', get_template_directory_uri() . '/assets/js/blog-archive-optimized.js', array('corporate-seo-pro-animation-utils'), wp_get_theme()->get( 'Version' ), true );
    }
    
    
    // Aboutページのアニメーションスクリプト
    if ( is_page_template( 'page-about.php' ) ) {
        // 最適化されたabout-animationsスクリプトを使用
        wp_enqueue_script( 'corporate-seo-pro-about', get_template_directory_uri() . '/assets/js/about-animations-optimized.js', array('corporate-seo-pro-animation-utils'), wp_get_theme()->get( 'Version' ), true );
    }
    
    // Contactページのスクリプト
    if ( is_page_template( 'page-contact.php' ) ) {
        wp_enqueue_script( 'corporate-seo-pro-contact', get_template_directory_uri() . '/assets/js/contact.js', array(), wp_get_theme()->get( 'Version' ), true );
        
        // Contact Form 7 モバイル修正スクリプト
        wp_enqueue_script( 'corporate-seo-pro-cf7-mobile-fix', get_template_directory_uri() . '/assets/js/cf7-mobile-fix.js', array(), wp_get_theme()->get( 'Version' ), true );
        
        // Google Maps API（APIキーが必要）
        // wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap', array( 'corporate-seo-pro-contact' ), null, true );
    }
    
    // サービス詳細ページ用のスクリプト
    if ( is_singular( 'service' ) ) {
        wp_enqueue_script( 'corporate-seo-pro-service', get_template_directory_uri() . '/assets/js/single-service.js', array(), wp_get_theme()->get( 'Version' ), true );
    }
    
    // 実績ページ用のスクリプト
    if ( is_post_type_archive( 'work' ) || is_singular( 'work' ) || is_tax( 'work_category' ) ) {
        wp_enqueue_script( 'corporate-seo-pro-work', get_template_directory_uri() . '/assets/js/work-archive.js', array( 'jquery' ), wp_get_theme()->get( 'Version' ), true );
    }

    // コメント返信スクリプト
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'corporate_seo_pro_scripts' );

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

/**
 * サイドバーの登録
 */
function corporate_seo_pro_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'サイドバー', 'corporate-seo-pro' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'サイドバーウィジェットエリア', 'corporate-seo-pro' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'フッターウィジェット 1', 'corporate-seo-pro' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'フッターの最初のウィジェットエリア', 'corporate-seo-pro' ),
        'before_widget' => '<section id="%1$s" class="widget footer-widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'フッターウィジェット 2', 'corporate-seo-pro' ),
        'id'            => 'footer-2',
        'description'   => esc_html__( 'フッターの2番目のウィジェットエリア', 'corporate-seo-pro' ),
        'before_widget' => '<section id="%1$s" class="widget footer-widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

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

/**
 * 読了時間を計算する関数
 */
function corporate_seo_get_reading_time() {
    $content = get_post_field( 'post_content', get_the_ID() );
    $content = strip_tags( $content );
    
    // 日本語の文字数をカウント
    // 日本語は1分あたり400文字で計算（一般的な読書速度）
    $japanese_count = mb_strlen( preg_replace( '/[a-zA-Z0-9\s]/', '', $content ), 'UTF-8' );
    
    // 英数字の単語数をカウント
    $english_words = str_word_count( preg_replace( '/[^a-zA-Z0-9\s]/', ' ', $content ) );
    
    // 読了時間を計算
    // 日本語: 400文字/分、英語: 200単語/分
    $japanese_time = $japanese_count / 400;
    $english_time = $english_words / 200;
    
    $reading_time = ceil( $japanese_time + $english_time );
    
    // 最小値を1分に設定
    if ( $reading_time < 1 ) {
        $reading_time = 1;
    }
    
    if ( $reading_time == 1 ) {
        return '1分で読める';
    } else {
        return $reading_time . '分で読める';
    }
}

/**
 * カスタムナビゲーションウォーカー
 */
require get_template_directory() . '/inc/class-nav-walker.php';

/**
 * モバイルメニューウォーカー
 */
require get_template_directory() . '/inc/class-mobile-menu-walker.php';

/**
 * カスタマイザーの設定
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * SEO機能
 */
require get_template_directory() . '/inc/seo-functions.php';

/**
 * パフォーマンス最適化
 */
require get_template_directory() . '/inc/performance.php';

/**
 * 構造化データ
 */
require get_template_directory() . '/inc/structured-data.php';

/**
 * カスタム投稿タイプ（サービス）
 */
function corporate_seo_pro_register_post_types() {
    $labels = array(
        'name'                  => esc_html_x( 'サービス', 'Post type general name', 'corporate-seo-pro' ),
        'singular_name'         => esc_html_x( 'サービス', 'Post type singular name', 'corporate-seo-pro' ),
        'menu_name'             => esc_html_x( 'サービス', 'Admin Menu text', 'corporate-seo-pro' ),
        'add_new'               => esc_html__( '新規追加', 'corporate-seo-pro' ),
        'add_new_item'          => esc_html__( '新しいサービスを追加', 'corporate-seo-pro' ),
        'edit_item'             => esc_html__( 'サービスを編集', 'corporate-seo-pro' ),
        'new_item'              => esc_html__( '新しいサービス', 'corporate-seo-pro' ),
        'view_item'             => esc_html__( 'サービスを表示', 'corporate-seo-pro' ),
        'search_items'          => esc_html__( 'サービスを検索', 'corporate-seo-pro' ),
        'not_found'             => esc_html__( 'サービスが見つかりません', 'corporate-seo-pro' ),
        'not_found_in_trash'    => esc_html__( 'ゴミ箱にサービスが見つかりません', 'corporate-seo-pro' ),
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'services' ),
        'capability_type'       => 'post',
        'has_archive'           => true,
        'hierarchical'          => false,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-admin-tools',
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        'show_in_rest'          => true,
    );

    register_post_type( 'service', $args );
    
    // 実績（Works）のカスタム投稿タイプ
    $work_args = array(
        'labels'                => array(
            'name'                  => _x( '実績', 'Post Type General Name', 'corporate-seo-pro' ),
            'singular_name'         => _x( '実績', 'Post Type Singular Name', 'corporate-seo-pro' ),
            'menu_name'             => __( '実績', 'corporate-seo-pro' ),
            'name_admin_bar'        => __( '実績', 'corporate-seo-pro' ),
            'archives'              => __( '実績一覧', 'corporate-seo-pro' ),
            'attributes'            => __( '実績の属性', 'corporate-seo-pro' ),
            'parent_item_colon'     => __( '親実績:', 'corporate-seo-pro' ),
            'all_items'             => __( 'すべての実績', 'corporate-seo-pro' ),
            'add_new_item'          => __( '新しい実績を追加', 'corporate-seo-pro' ),
            'add_new'               => __( '新規追加', 'corporate-seo-pro' ),
            'new_item'              => __( '新しい実績', 'corporate-seo-pro' ),
            'edit_item'             => __( '実績を編集', 'corporate-seo-pro' ),
            'update_item'           => __( '実績を更新', 'corporate-seo-pro' ),
            'view_item'             => __( '実績を見る', 'corporate-seo-pro' ),
            'view_items'            => __( '実績を見る', 'corporate-seo-pro' ),
            'search_items'          => __( '実績を検索', 'corporate-seo-pro' ),
            'not_found'             => __( '実績が見つかりません', 'corporate-seo-pro' ),
            'not_found_in_trash'    => __( 'ゴミ箱に実績はありません', 'corporate-seo-pro' ),
        ),
        'label'                 => __( '実績', 'corporate-seo-pro' ),
        'description'           => __( 'Harmonic Societyの実績', 'corporate-seo-pro' ),
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        'taxonomies'            => array( 'work_category' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 6,
        'menu_icon'             => 'dashicons-portfolio',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => array( 'slug' => 'works' ),
    );
    
    register_post_type( 'work', $work_args );
    
    // 実績のカテゴリー（タクソノミー）
    $cat_args = array(
        'labels' => array(
            'name'                       => _x( '実績カテゴリー', 'Taxonomy General Name', 'corporate-seo-pro' ),
            'singular_name'              => _x( '実績カテゴリー', 'Taxonomy Singular Name', 'corporate-seo-pro' ),
            'menu_name'                  => __( 'カテゴリー', 'corporate-seo-pro' ),
            'all_items'                  => __( 'すべてのカテゴリー', 'corporate-seo-pro' ),
            'parent_item'                => __( '親カテゴリー', 'corporate-seo-pro' ),
            'parent_item_colon'          => __( '親カテゴリー:', 'corporate-seo-pro' ),
            'new_item_name'              => __( '新しいカテゴリー名', 'corporate-seo-pro' ),
            'add_new_item'               => __( '新しいカテゴリーを追加', 'corporate-seo-pro' ),
            'edit_item'                  => __( 'カテゴリーを編集', 'corporate-seo-pro' ),
            'update_item'                => __( 'カテゴリーを更新', 'corporate-seo-pro' ),
            'view_item'                  => __( 'カテゴリーを見る', 'corporate-seo-pro' ),
            'separate_items_with_commas' => __( 'カテゴリーをカンマで区切る', 'corporate-seo-pro' ),
            'add_or_remove_items'        => __( 'カテゴリーを追加または削除', 'corporate-seo-pro' ),
            'choose_from_most_used'      => __( 'よく使われているカテゴリーから選択', 'corporate-seo-pro' ),
            'popular_items'              => __( '人気のカテゴリー', 'corporate-seo-pro' ),
            'search_items'               => __( 'カテゴリーを検索', 'corporate-seo-pro' ),
            'not_found'                  => __( 'カテゴリーが見つかりません', 'corporate-seo-pro' ),
            'no_terms'                   => __( 'カテゴリーなし', 'corporate-seo-pro' ),
            'items_list'                 => __( 'カテゴリーリスト', 'corporate-seo-pro' ),
            'items_list_navigation'      => __( 'カテゴリーリストナビゲーション', 'corporate-seo-pro' ),
        ),
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rewrite'                    => array( 'slug' => 'work-category' ),
    );
    
    register_taxonomy( 'work_category', array( 'work' ), $cat_args );
}
add_action( 'init', 'corporate_seo_pro_register_post_types' );

/**
 * 抜粋の文字数を変更
 */
function corporate_seo_pro_excerpt_length( $length ) {
    return is_home() ? 30 : 55;
}
add_filter( 'excerpt_length', 'corporate_seo_pro_excerpt_length', 999 );

/**
 * 抜粋の末尾を変更
 */
function corporate_seo_pro_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'corporate_seo_pro_excerpt_more' );

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

/**
 * 読了時間の計算
 */
function corporate_seo_pro_reading_time() {
    $content = get_post_field( 'post_content', get_the_ID() );
    $word_count = str_word_count( strip_tags( $content ) );
    $reading_time = ceil( $word_count / 200 ); // 200文字/分で計算
    
    if ( $reading_time < 1 ) {
        $reading_time = 1;
    }
    
    return sprintf( _n( '%s分で読めます', '%s分で読めます', $reading_time, 'corporate-seo-pro' ), $reading_time );
}

/**
 * 関連記事の取得
 */
function corporate_seo_pro_get_related_posts( $post_id, $number_posts = 3 ) {
    $args = array(
        'post_type' => 'post',
        'post__not_in' => array( $post_id ),
        'posts_per_page' => $number_posts,
        'orderby' => 'rand',
    );
    
    // カテゴリーベースで関連記事を取得
    $categories = wp_get_post_categories( $post_id );
    if ( $categories ) {
        $args['category__in'] = $categories;
    }
    
    // タグベースでも検索
    $tags = wp_get_post_tags( $post_id );
    if ( $tags ) {
        $tag_ids = array();
        foreach ( $tags as $tag ) {
            $tag_ids[] = $tag->term_id;
        }
        $args['tag__in'] = $tag_ids;
    }
    
    return new WP_Query( $args );
}

/**
 * パンくずリスト
 */
function corporate_seo_pro_breadcrumb() {
    if ( is_front_page() ) {
        return;
    }

    echo '<nav class="breadcrumb" aria-label="' . esc_attr__( 'パンくずリスト', 'corporate-seo-pro' ) . '">';
    echo '<ol itemscope itemtype="https://schema.org/BreadcrumbList">';

    // ホーム
    echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
    echo '<a itemprop="item" href="' . esc_url( home_url( '/' ) ) . '">';
    echo '<span itemprop="name">' . esc_html__( 'ホーム', 'corporate-seo-pro' ) . '</span>';
    echo '</a>';
    echo '<meta itemprop="position" content="1" />';
    echo '</li>';

    $position = 2;

    // 投稿ページ
    if ( is_single() ) {
        $categories = get_the_category();
        if ( $categories ) {
            $category = $categories[0];
            echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            echo '<a itemprop="item" href="' . esc_url( get_category_link( $category->term_id ) ) . '">';
            echo '<span itemprop="name">' . esc_html( $category->name ) . '</span>';
            echo '</a>';
            echo '<meta itemprop="position" content="' . $position . '" />';
            echo '</li>';
            $position++;
        }

        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . get_the_title() . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';
    }

    // 固定ページ
    elseif ( is_page() ) {
        global $post;
        if ( $post && $post->post_parent ) {
            $ancestors = array_reverse( get_post_ancestors( $post->ID ) );
            foreach ( $ancestors as $ancestor ) {
                echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                echo '<a itemprop="item" href="' . esc_url( get_permalink( $ancestor ) ) . '">';
                echo '<span itemprop="name">' . get_the_title( $ancestor ) . '</span>';
                echo '</a>';
                echo '<meta itemprop="position" content="' . $position . '" />';
                echo '</li>';
                $position++;
            }
        }

        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . get_the_title() . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';
    }

    // カテゴリーアーカイブ
    elseif ( is_category() ) {
        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . single_cat_title( '', false ) . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';
    }

    // 検索結果
    elseif ( is_search() ) {
        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . esc_html__( '検索結果', 'corporate-seo-pro' ) . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';
    }

    echo '</ol>';
    echo '</nav>';
}



/**
 * お問い合わせページのURLを取得
 */
function get_contact_page_url() {
    // まず、IDでページを検索（最も確実）
    $page_id = get_option('page_on_front');
    
    // Template Name: Contact を使用しているページを検索
    $args = array(
        'post_type' => 'page',
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => '_wp_page_template',
                'value' => 'page-contact.php',
                'compare' => '='
            )
        ),
        'posts_per_page' => 1
    );
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        $query->the_post();
        $url = get_permalink();
        wp_reset_postdata();
        return $url;
    }
    
    // テンプレートが見つからない場合は、スラッグで検索
    $contact_page = get_page_by_path('contact');
    if ($contact_page && $contact_page->post_status == 'publish') {
        return get_permalink($contact_page->ID);
    }
    
    // それでも見つからない場合は、タイトルで検索
    $pages = get_pages(array(
        'post_type' => 'page',
        'post_status' => 'publish'
    ));
    
    foreach ($pages as $page) {
        if (strpos(strtolower($page->post_title), 'contact') !== false || 
            strpos($page->post_title, 'お問い合わせ') !== false ||
            strpos($page->post_title, '問い合わせ') !== false) {
            return get_permalink($page->ID);
        }
    }
    
    // デフォルトとしてホームURLを返す
    return home_url('/contact/');
}