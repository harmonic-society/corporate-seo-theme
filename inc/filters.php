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
    
    // ブログページ（ホーム）、フロントページ、または投稿アーカイブページの場合
    if ( $query->is_home() || ( $query->is_front_page() && get_option( 'show_on_front' ) == 'posts' ) ) {
        // 必ず投稿タイプを「post」に限定（サービスや実績を除外）
        $query->set( 'post_type', 'post' );
        
        // フィルターが何か適用されているかチェック
        $has_filters = false;
        
        // 検索キーワード（空の場合は除外）
        if ( isset( $_GET['s'] ) && $_GET['s'] !== '' ) {
            $query->set( 's', sanitize_text_field( $_GET['s'] ) );
            $has_filters = true;
        }
        
        // タグフィルター
        if ( isset( $_GET['tags'] ) && ! empty( $_GET['tags'] ) ) {
            $tags = explode( ',', sanitize_text_field( $_GET['tags'] ) );
            
            // タグスラッグをタグIDに変換（大文字小文字を考慮）
            $tag_ids = array();
            foreach ( $tags as $tag_slug ) {
                // まず、そのままのスラッグで検索
                $tag = get_term_by( 'slug', $tag_slug, 'post_tag' );
                
                // 見つからない場合は小文字で検索
                if ( ! $tag ) {
                    $tag = get_term_by( 'slug', strtolower( $tag_slug ), 'post_tag' );
                }
                
                // それでも見つからない場合は名前で検索
                if ( ! $tag ) {
                    $tag = get_term_by( 'name', $tag_slug, 'post_tag' );
                }
                
                if ( $tag ) {
                    $tag_ids[] = $tag->term_id;
                }
            }
            
            if ( ! empty( $tag_ids ) ) {
                $query->set( 'tag__in', $tag_ids );
                $has_filters = true;
                
                // デバッグ用
                if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                    error_log( 'Tag IDs for filtering: ' . print_r( $tag_ids, true ) );
                }
            }
        }
        
        // カテゴリーフィルター
        if ( isset( $_GET['category'] ) && ! empty( $_GET['category'] ) ) {
            $category_ids = array_map( 'intval', $_GET['category'] );
            $query->set( 'category__in', $category_ids );
            $has_filters = true;
        }
        
        // 期間フィルター
        if ( isset( $_GET['period'] ) && $_GET['period'] !== 'all' && $_GET['period'] !== '' ) {
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
                $has_filters = true;
            }
        }
        
        // デバッグ用：フィルター適用状況をログに記録
        if ( $has_filters && defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( 'Blog filters applied: ' . print_r( $_GET, true ) );
            error_log( 'Query vars: ' . print_r( $query->query_vars, true ) );
        }
    }
}
add_action( 'pre_get_posts', 'corporate_seo_pro_filter_blog_query', 99 );