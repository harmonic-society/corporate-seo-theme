<?php
/**
 * Custom Post Types Registration
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * カスタム投稿タイプの登録
 */
function corporate_seo_pro_register_post_types() {
    // サービス投稿タイプ
    corporate_seo_pro_register_service_post_type();
    
    // 実績投稿タイプ
    corporate_seo_pro_register_work_post_type();
    
    // 実績カテゴリータクソノミー
    corporate_seo_pro_register_work_taxonomy();
    
    // ニュースリリース投稿タイプ
    corporate_seo_pro_register_news_post_type();
}
add_action( 'init', 'corporate_seo_pro_register_post_types' );

/**
 * サービス投稿タイプの登録
 */
function corporate_seo_pro_register_service_post_type() {
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
}

/**
 * 実績投稿タイプの登録
 */
function corporate_seo_pro_register_work_post_type() {
    $labels = array(
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
    );
    
    $args = array(
        'label'                 => __( '実績', 'corporate-seo-pro' ),
        'description'           => __( 'Harmonic Societyの実績', 'corporate-seo-pro' ),
        'labels'                => $labels,
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
    
    register_post_type( 'work', $args );
}

/**
 * 実績カテゴリータクソノミーの登録
 */
function corporate_seo_pro_register_work_taxonomy() {
    $labels = array(
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
    );
    
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rewrite'                    => array( 'slug' => 'work-category' ),
    );
    
    register_taxonomy( 'work_category', array( 'work' ), $args );
}

/**
 * ニュースリリース投稿タイプの登録
 */
function corporate_seo_pro_register_news_post_type() {
    $labels = array(
        'name'                  => _x( 'ニュースリリース', 'Post Type General Name', 'corporate-seo-pro' ),
        'singular_name'         => _x( 'ニュースリリース', 'Post Type Singular Name', 'corporate-seo-pro' ),
        'menu_name'             => __( 'ニュースリリース', 'corporate-seo-pro' ),
        'name_admin_bar'        => __( 'ニュースリリース', 'corporate-seo-pro' ),
        'archives'              => __( 'ニュースリリース一覧', 'corporate-seo-pro' ),
        'attributes'            => __( 'ニュースリリースの属性', 'corporate-seo-pro' ),
        'parent_item_colon'     => __( '親ニュース:', 'corporate-seo-pro' ),
        'all_items'             => __( 'すべてのニュース', 'corporate-seo-pro' ),
        'add_new_item'          => __( '新しいニュースを追加', 'corporate-seo-pro' ),
        'add_new'               => __( '新規追加', 'corporate-seo-pro' ),
        'new_item'              => __( '新しいニュース', 'corporate-seo-pro' ),
        'edit_item'             => __( 'ニュースを編集', 'corporate-seo-pro' ),
        'update_item'           => __( 'ニュースを更新', 'corporate-seo-pro' ),
        'view_item'             => __( 'ニュースを見る', 'corporate-seo-pro' ),
        'view_items'            => __( 'ニュースを見る', 'corporate-seo-pro' ),
        'search_items'          => __( 'ニュースを検索', 'corporate-seo-pro' ),
        'not_found'             => __( 'ニュースが見つかりません', 'corporate-seo-pro' ),
        'not_found_in_trash'    => __( 'ゴミ箱にニュースはありません', 'corporate-seo-pro' ),
    );
    
    $args = array(
        'label'                 => __( 'ニュースリリース', 'corporate-seo-pro' ),
        'description'           => __( '企業のニュースリリース', 'corporate-seo-pro' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'excerpt' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-megaphone',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => array( 'slug' => 'news' ),
    );
    
    register_post_type( 'news', $args );
}

/**
 * カスタム投稿タイプ登録後のリライトルールフラッシュ
 */
function corporate_seo_pro_flush_rewrite_rules() {
    // ニュースリリース投稿タイプが登録されているかチェック
    if ( ! get_option( 'corporate_seo_pro_news_flushed' ) ) {
        flush_rewrite_rules();
        update_option( 'corporate_seo_pro_news_flushed', true );
    }
}
add_action( 'init', 'corporate_seo_pro_flush_rewrite_rules', 20 );