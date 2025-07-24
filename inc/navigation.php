<?php
/**
 * Navigation and Menu Functions
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * メニューの登録
 */
function corporate_seo_pro_register_menus() {
    register_nav_menus( array(
        'primary'   => esc_html__( 'プライマリーメニュー', 'corporate-seo-pro' ),
        'footer'    => esc_html__( 'フッターメニュー', 'corporate-seo-pro' ),
        'footer-1'  => esc_html__( 'フッターメニュー1（サービス）', 'corporate-seo-pro' ),
        'footer-2'  => esc_html__( 'フッターメニュー2（会社情報）', 'corporate-seo-pro' ),
        'social'    => esc_html__( 'ソーシャルメニュー', 'corporate-seo-pro' ),
        'mobile'    => esc_html__( 'モバイルメニュー', 'corporate-seo-pro' ),
    ) );
}
add_action( 'after_setup_theme', 'corporate_seo_pro_register_menus' );

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
    
    // フッターメニューの作成
    corporate_seo_pro_create_footer_menus();
}
add_action( 'after_setup_theme', 'corporate_seo_pro_create_default_menu' );

/**
 * フッターメニューの作成
 */
function corporate_seo_pro_create_footer_menus() {
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