<?php
/**
 * WordPress Filters and Modifications
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

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
 * ブログページでフィルター条件を適用
 */
function corporate_seo_pro_filter_blog_query( $query ) {
    // 管理画面やメインクエリ以外は処理しない
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }
    
    // ブログページ（ホーム）の場合のみ処理
    if ( $query->is_home() ) {
        // 必ず投稿タイプを「post」に限定（サービスや実績を除外）
        $query->set( 'post_type', 'post' );
        
        // 検索キーワード
        if ( isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
            $query->set( 's', sanitize_text_field( $_GET['s'] ) );
            $query->is_search = true;
        }
        
        // タグフィルター
        if ( isset( $_GET['tags'] ) && ! empty( $_GET['tags'] ) ) {
            $tags = explode( ',', sanitize_text_field( $_GET['tags'] ) );
            $query->set( 'tag_slug__in', $tags );
        }
        
        // カテゴリーフィルター
        if ( isset( $_GET['category'] ) && ! empty( $_GET['category'] ) ) {
            $category_ids = array_map( 'intval', $_GET['category'] );
            $query->set( 'category__in', $category_ids );
        }
        
        // 期間フィルター
        if ( isset( $_GET['period'] ) && $_GET['period'] !== 'all' ) {
            $date_query = array();
            
            switch ( $_GET['period'] ) {
                case 'week':
                    $date_query[] = array(
                        'after' => '1 week ago',
                    );
                    break;
                case 'month':
                    $date_query[] = array(
                        'after' => '1 month ago',
                    );
                    break;
                case '3months':
                    $date_query[] = array(
                        'after' => '3 months ago',
                    );
                    break;
            }
            
            if ( ! empty( $date_query ) ) {
                $query->set( 'date_query', $date_query );
            }
        }
    }
}
add_action( 'pre_get_posts', 'corporate_seo_pro_filter_blog_query' );