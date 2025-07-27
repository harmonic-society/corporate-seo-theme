<?php
/**
 * 内部リンク構造の最適化
 *
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 関連記事の自動表示
 */
function corporate_seo_pro_related_posts( $post_id = null, $args = array() ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $defaults = array(
        'posts_per_page' => 6,
        'post_type' => get_post_type( $post_id ),
        'orderby' => 'relevance',
        'echo' => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    // 関連記事の取得
    $related_posts = corporate_seo_pro_get_related_posts_extended( $post_id, $args );
    
    if ( empty( $related_posts ) ) {
        return '';
    }
    
    $output = '<div class="related-posts">';
    $output .= '<h2 class="related-posts-title">' . __( '関連記事', 'corporate-seo-pro' ) . '</h2>';
    $output .= '<div class="related-posts-grid">';
    
    foreach ( $related_posts as $related_post ) {
        $output .= corporate_seo_pro_get_post_card( $related_post );
    }
    
    $output .= '</div></div>';
    
    if ( $args['echo'] ) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * 関連記事を取得（拡張版）
 */
function corporate_seo_pro_get_related_posts_extended( $post_id, $args ) {
    $post = get_post( $post_id );
    
    if ( ! $post ) {
        return array();
    }
    
    // タグとカテゴリーを取得
    $tags = wp_get_post_tags( $post_id, array( 'fields' => 'ids' ) );
    $categories = wp_get_post_categories( $post_id, array( 'fields' => 'ids' ) );
    
    $query_args = array(
        'post_type' => $args['post_type'],
        'posts_per_page' => $args['posts_per_page'],
        'post__not_in' => array( $post_id ),
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    );
    
    // タグベースの関連性
    if ( ! empty( $tags ) ) {
        $query_args['tag__in'] = $tags;
    }
    
    // カテゴリーベースの関連性
    if ( ! empty( $categories ) ) {
        $query_args['category__in'] = $categories;
    }
    
    // カスタム投稿タイプの場合、タクソノミーを考慮
    if ( 'post' !== $args['post_type'] ) {
        $taxonomies = get_object_taxonomies( $args['post_type'] );
        foreach ( $taxonomies as $taxonomy ) {
            $terms = wp_get_post_terms( $post_id, $taxonomy, array( 'fields' => 'ids' ) );
            if ( ! empty( $terms ) ) {
                $query_args['tax_query'][] = array(
                    'taxonomy' => $taxonomy,
                    'field' => 'term_id',
                    'terms' => $terms,
                );
            }
        }
        
        if ( ! empty( $query_args['tax_query'] ) ) {
            $query_args['tax_query']['relation'] = 'OR';
        }
    }
    
    // 関連性でソート
    if ( 'relevance' === $args['orderby'] ) {
        add_filter( 'posts_clauses', 'corporate_seo_pro_orderby_relevance', 10, 2 );
    }
    
    $related_query = new WP_Query( $query_args );
    
    if ( 'relevance' === $args['orderby'] ) {
        remove_filter( 'posts_clauses', 'corporate_seo_pro_orderby_relevance', 10 );
    }
    
    return $related_query->posts;
}

/**
 * 関連性によるソート
 */
function corporate_seo_pro_orderby_relevance( $clauses, $query ) {
    global $wpdb;
    
    // タグとカテゴリーの一致数でソート
    $clauses['fields'] .= ", (
        SELECT COUNT(*) 
        FROM {$wpdb->term_relationships} AS tr1
        INNER JOIN {$wpdb->term_relationships} AS tr2 ON tr1.term_taxonomy_id = tr2.term_taxonomy_id
        WHERE tr1.object_id = {$wpdb->posts}.ID 
        AND tr2.object_id = " . get_the_ID() . "
    ) AS relevance_score";
    
    $clauses['orderby'] = 'relevance_score DESC, ' . $clauses['orderby'];
    
    return $clauses;
}

/**
 * 記事カードのHTML生成
 */
function corporate_seo_pro_get_post_card( $post ) {
    $output = '<article class="related-post-card">';
    
    // アイキャッチ画像
    if ( has_post_thumbnail( $post->ID ) ) {
        $output .= '<a href="' . get_permalink( $post ) . '" class="related-post-thumbnail">';
        $output .= get_the_post_thumbnail( $post->ID, 'medium', array(
            'loading' => 'lazy',
            'alt' => get_the_title( $post ),
        ) );
        $output .= '</a>';
    }
    
    $output .= '<div class="related-post-content">';
    
    // カテゴリー
    $categories = get_the_category( $post->ID );
    if ( $categories ) {
        $output .= '<div class="related-post-meta">';
        $output .= '<a href="' . get_category_link( $categories[0] ) . '" class="related-post-category">';
        $output .= esc_html( $categories[0]->name );
        $output .= '</a>';
        $output .= '</div>';
    }
    
    // タイトル
    $output .= '<h3 class="related-post-title">';
    $output .= '<a href="' . get_permalink( $post ) . '">';
    $output .= get_the_title( $post );
    $output .= '</a>';
    $output .= '</h3>';
    
    // 抜粋
    $excerpt = wp_trim_words( strip_tags( $post->post_content ), 20, '...' );
    $output .= '<p class="related-post-excerpt">' . $excerpt . '</p>';
    
    $output .= '</div></article>';
    
    return $output;
}

/**
 * 自動内部リンク生成
 */
function corporate_seo_pro_auto_internal_links( $content ) {
    if ( ! is_singular() || ! in_the_loop() || ! is_main_query() ) {
        return $content;
    }
    
    // キーワードとリンクのマッピング
    $keywords = corporate_seo_pro_get_keyword_links();
    
    if ( empty( $keywords ) ) {
        return $content;
    }
    
    // 既存のリンクとHTMLタグを保護
    $content = corporate_seo_pro_protect_existing_links( $content );
    
    foreach ( $keywords as $keyword => $url ) {
        // 現在のページへのリンクは作成しない
        if ( $url === get_permalink() ) {
            continue;
        }
        
        // キーワードを内部リンクに変換（大文字小文字を区別しない）
        $pattern = '/\b(' . preg_quote( $keyword, '/' ) . ')\b(?![^<]*>)/i';
        $replacement = '<a href="' . esc_url( $url ) . '" class="auto-internal-link">$1</a>';
        
        // 最初の出現箇所のみ置換
        $content = preg_replace( $pattern, $replacement, $content, 1 );
    }
    
    // 保護したコンテンツを復元
    $content = corporate_seo_pro_restore_protected_content( $content );
    
    return $content;
}
add_filter( 'the_content', 'corporate_seo_pro_auto_internal_links', 20 );

/**
 * キーワードとリンクのマッピング取得
 */
function corporate_seo_pro_get_keyword_links() {
    $keywords = array();
    
    // 重要なページのキーワード
    $important_pages = array(
        '会社概要' => '/about/',
        '企業情報' => '/company/',
        'サービス' => '/service/',
        'お問い合わせ' => '/contact/',
        '実績' => '/work/',
        '事例' => '/work/',
    );
    
    foreach ( $important_pages as $keyword => $path ) {
        $url = home_url( $path );
        if ( corporate_seo_pro_url_exists( $url ) ) {
            $keywords[ $keyword ] = $url;
        }
    }
    
    // サービス投稿タイプ
    $services = get_posts( array(
        'post_type' => 'service',
        'posts_per_page' => 20,
        'post_status' => 'publish',
    ) );
    
    foreach ( $services as $service ) {
        $keywords[ $service->post_title ] = get_permalink( $service );
        
        // カスタムキーワード
        $custom_keywords = get_post_meta( $service->ID, '_seo_keywords', true );
        if ( $custom_keywords ) {
            $custom_keywords = explode( ',', $custom_keywords );
            foreach ( $custom_keywords as $custom_keyword ) {
                $custom_keyword = trim( $custom_keyword );
                if ( $custom_keyword ) {
                    $keywords[ $custom_keyword ] = get_permalink( $service );
                }
            }
        }
    }
    
    // カテゴリー
    $categories = get_categories( array(
        'hide_empty' => true,
        'number' => 20,
    ) );
    
    foreach ( $categories as $category ) {
        $keywords[ $category->name ] = get_category_link( $category );
    }
    
    return apply_filters( 'corporate_seo_pro_keyword_links', $keywords );
}

/**
 * URLの存在確認
 */
function corporate_seo_pro_url_exists( $url ) {
    $url_id = url_to_postid( $url );
    return $url_id > 0;
}

/**
 * 既存のリンクとHTMLタグを保護
 */
function corporate_seo_pro_protect_existing_links( $content ) {
    // リンクタグを一時的に置換
    $content = preg_replace_callback( '/<a[^>]*>.*?<\/a>/is', function( $matches ) {
        return '<!--LINK' . base64_encode( $matches[0] ) . 'LINK-->';
    }, $content );
    
    // その他のHTMLタグも保護
    $content = preg_replace_callback( '/<[^>]+>/is', function( $matches ) {
        return '<!--TAG' . base64_encode( $matches[0] ) . 'TAG-->';
    }, $content );
    
    return $content;
}

/**
 * 保護したコンテンツを復元
 */
function corporate_seo_pro_restore_protected_content( $content ) {
    // リンクタグを復元
    $content = preg_replace_callback( '/<!--LINK(.+?)LINK-->/is', function( $matches ) {
        return base64_decode( $matches[1] );
    }, $content );
    
    // その他のHTMLタグを復元
    $content = preg_replace_callback( '/<!--TAG(.+?)TAG-->/is', function( $matches ) {
        return base64_decode( $matches[1] );
    }, $content );
    
    return $content;
}

/**
 * パンくずリストの生成
 */
function corporate_seo_pro_breadcrumbs( $args = array() ) {
    $defaults = array(
        'separator' => ' &gt; ',
        'home_text' => __( 'ホーム', 'corporate-seo-pro' ),
        'show_current' => true,
        'echo' => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    if ( is_front_page() ) {
        return '';
    }
    
    $output = '<nav class="breadcrumbs" aria-label="Breadcrumb">';
    $output .= '<ol class="breadcrumb-list">';
    
    // ホーム
    $output .= '<li class="breadcrumb-item">';
    $output .= '<a href="' . home_url() . '">' . $args['home_text'] . '</a>';
    $output .= '</li>';
    
    if ( is_category() || is_single() ) {
        // カテゴリーアーカイブまたは投稿
        if ( is_single() ) {
            $categories = get_the_category();
            if ( $categories ) {
                $category = $categories[0];
                $cat_parents = get_category_parents( $category, true, '||' );
                $cat_parents = explode( '||', $cat_parents );
                
                foreach ( $cat_parents as $parent ) {
                    if ( ! empty( $parent ) ) {
                        $output .= '<li class="breadcrumb-item">' . $parent . '</li>';
                    }
                }
            }
            
            if ( $args['show_current'] ) {
                $output .= '<li class="breadcrumb-item active" aria-current="page">';
                $output .= get_the_title();
                $output .= '</li>';
            }
        } else {
            $output .= '<li class="breadcrumb-item active" aria-current="page">';
            $output .= single_cat_title( '', false );
            $output .= '</li>';
        }
    } elseif ( is_page() ) {
        // 固定ページ
        global $post;
        if ( $post->post_parent ) {
            $ancestors = array_reverse( get_post_ancestors( $post->ID ) );
            foreach ( $ancestors as $ancestor ) {
                $output .= '<li class="breadcrumb-item">';
                $output .= '<a href="' . get_permalink( $ancestor ) . '">' . get_the_title( $ancestor ) . '</a>';
                $output .= '</li>';
            }
        }
        
        if ( $args['show_current'] ) {
            $output .= '<li class="breadcrumb-item active" aria-current="page">';
            $output .= get_the_title();
            $output .= '</li>';
        }
    } elseif ( is_tag() ) {
        $output .= '<li class="breadcrumb-item active" aria-current="page">';
        $output .= 'タグ: ' . single_tag_title( '', false );
        $output .= '</li>';
    } elseif ( is_author() ) {
        $output .= '<li class="breadcrumb-item active" aria-current="page">';
        $output .= '著者: ' . get_the_author();
        $output .= '</li>';
    } elseif ( is_search() ) {
        $output .= '<li class="breadcrumb-item active" aria-current="page">';
        $output .= '検索結果: ' . get_search_query();
        $output .= '</li>';
    } elseif ( is_404() ) {
        $output .= '<li class="breadcrumb-item active" aria-current="page">';
        $output .= 'ページが見つかりません';
        $output .= '</li>';
    }
    
    $output .= '</ol></nav>';
    
    // CSS
    $output .= '<style>
    .breadcrumbs {
        margin: 1rem 0;
        font-size: 0.875rem;
    }
    .breadcrumb-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .breadcrumb-item {
        display: flex;
        align-items: center;
    }
    .breadcrumb-item::after {
        content: ">";
        margin: 0 0.5rem;
        color: #6b7280;
    }
    .breadcrumb-item:last-child::after {
        display: none;
    }
    .breadcrumb-item a {
        color: #3b82f6;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .breadcrumb-item a:hover {
        color: #2563eb;
        text-decoration: underline;
    }
    .breadcrumb-item.active {
        color: #374151;
        font-weight: 500;
    }
    </style>';
    
    if ( $args['echo'] ) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * コンテンツハブの作成
 */
function corporate_seo_pro_content_hub( $hub_id, $args = array() ) {
    $defaults = array(
        'title' => '',
        'posts_per_page' => 10,
        'show_categories' => true,
        'show_tags' => true,
        'echo' => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $output = '<div class="content-hub" id="hub-' . esc_attr( $hub_id ) . '">';
    
    if ( $args['title'] ) {
        $output .= '<h2 class="hub-title">' . esc_html( $args['title'] ) . '</h2>';
    }
    
    // カテゴリー一覧
    if ( $args['show_categories'] ) {
        $categories = get_categories( array(
            'parent' => 0,
            'hide_empty' => true,
        ) );
        
        if ( $categories ) {
            $output .= '<div class="hub-categories">';
            $output .= '<h3>カテゴリー</h3>';
            $output .= '<ul class="category-list">';
            
            foreach ( $categories as $category ) {
                $output .= '<li>';
                $output .= '<a href="' . get_category_link( $category ) . '">';
                $output .= $category->name . ' (' . $category->count . ')';
                $output .= '</a>';
                
                // サブカテゴリー
                $subcategories = get_categories( array(
                    'parent' => $category->term_id,
                    'hide_empty' => true,
                ) );
                
                if ( $subcategories ) {
                    $output .= '<ul class="subcategory-list">';
                    foreach ( $subcategories as $subcategory ) {
                        $output .= '<li>';
                        $output .= '<a href="' . get_category_link( $subcategory ) . '">';
                        $output .= $subcategory->name . ' (' . $subcategory->count . ')';
                        $output .= '</a>';
                        $output .= '</li>';
                    }
                    $output .= '</ul>';
                }
                
                $output .= '</li>';
            }
            
            $output .= '</ul></div>';
        }
    }
    
    // タグクラウド
    if ( $args['show_tags'] ) {
        $tags = get_tags( array(
            'orderby' => 'count',
            'order' => 'DESC',
            'number' => 30,
        ) );
        
        if ( $tags ) {
            $output .= '<div class="hub-tags">';
            $output .= '<h3>人気のタグ</h3>';
            $output .= '<div class="tag-cloud">';
            
            foreach ( $tags as $tag ) {
                $size = ( $tag->count > 10 ) ? 'large' : ( ( $tag->count > 5 ) ? 'medium' : 'small' );
                $output .= '<a href="' . get_tag_link( $tag ) . '" class="tag-' . $size . '">';
                $output .= $tag->name;
                $output .= '</a> ';
            }
            
            $output .= '</div></div>';
        }
    }
    
    $output .= '</div>';
    
    if ( $args['echo'] ) {
        echo $output;
    } else {
        return $output;
    }
}