<?php
/**
 * 高度なXMLサイトマップ機能
 *
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * カスタムサイトマップのサポート
 */
function corporate_seo_pro_sitemap_init() {
    // WordPressのコアサイトマップ機能を拡張
    add_filter( 'wp_sitemaps_enabled', '__return_true' );
    
    // サイトマップのインデックス数を増やす
    add_filter( 'wp_sitemaps_max_urls', function( $max_urls ) {
        return 2000;
    } );
    
    // 投稿タイプごとの優先度設定
    add_filter( 'wp_sitemaps_posts_query_args', 'corporate_seo_pro_sitemap_posts_query_args', 10, 2 );
    
    // タクソノミーの追加設定
    add_filter( 'wp_sitemaps_taxonomies_query_args', 'corporate_seo_pro_sitemap_taxonomies_query_args', 10, 2 );
    
    // 画像サイトマップのサポート
    add_action( 'init', 'corporate_seo_pro_image_sitemap_support' );
    
    // ニュースサイトマップ
    add_action( 'init', 'corporate_seo_pro_news_sitemap' );
}
add_action( 'init', 'corporate_seo_pro_sitemap_init' );

/**
 * 投稿タイプのサイトマップ設定
 */
function corporate_seo_pro_sitemap_posts_query_args( $args, $post_type ) {
    // noindexの投稿を除外
    $args['meta_query'] = array(
        'relation' => 'OR',
        array(
            'key' => '_corporate_seo_noindex',
            'compare' => 'NOT EXISTS',
        ),
        array(
            'key' => '_corporate_seo_noindex',
            'value' => '1',
            'compare' => '!=',
        ),
    );
    
    // 更新日時でソート
    $args['orderby'] = 'modified';
    $args['order'] = 'DESC';
    
    return $args;
}

/**
 * タクソノミーのサイトマップ設定
 */
function corporate_seo_pro_sitemap_taxonomies_query_args( $args, $taxonomy ) {
    // 空のタームを除外
    $args['hide_empty'] = true;
    
    // 最小投稿数を設定
    $args['count'] = true;
    
    return $args;
}

/**
 * サイトマップエントリーにカスタムデータを追加
 */
function corporate_seo_pro_sitemap_entry( $entry, $post, $post_type ) {
    // 優先度の設定
    $priority = corporate_seo_pro_get_sitemap_priority( $post );
    if ( $priority ) {
        $entry['priority'] = $priority;
    }
    
    // 更新頻度の設定
    $changefreq = corporate_seo_pro_get_changefreq( $post );
    if ( $changefreq ) {
        $entry['changefreq'] = $changefreq;
    }
    
    return $entry;
}
add_filter( 'wp_sitemaps_posts_entry', 'corporate_seo_pro_sitemap_entry', 10, 3 );

/**
 * 優先度を計算
 */
function corporate_seo_pro_get_sitemap_priority( $post ) {
    // フロントページ
    if ( $post->ID == get_option( 'page_on_front' ) ) {
        return 1.0;
    }
    
    // 重要なページ
    $important_pages = array( 'about', 'service', 'contact', 'company' );
    if ( in_array( $post->post_name, $important_pages ) ) {
        return 0.9;
    }
    
    // 投稿タイプによる優先度
    switch ( $post->post_type ) {
        case 'service':
            return 0.8;
        case 'page':
            return 0.7;
        case 'post':
            return 0.6;
        case 'work':
            return 0.5;
        default:
            return 0.4;
    }
}

/**
 * 更新頻度を決定
 */
function corporate_seo_pro_get_changefreq( $post ) {
    $days_old = ( time() - strtotime( $post->post_modified ) ) / DAY_IN_SECONDS;
    
    if ( $days_old < 7 ) {
        return 'daily';
    } elseif ( $days_old < 30 ) {
        return 'weekly';
    } elseif ( $days_old < 180 ) {
        return 'monthly';
    } else {
        return 'yearly';
    }
}

/**
 * 画像サイトマップのサポート
 */
function corporate_seo_pro_image_sitemap_support() {
    add_filter( 'wp_sitemaps_posts_entry', function( $entry, $post ) {
        $images = corporate_seo_pro_get_post_images( $post );
        
        if ( ! empty( $images ) ) {
            $entry['images'] = array();
            
            foreach ( $images as $image ) {
                $entry['images'][] = array(
                    'loc' => $image['url'],
                    'title' => $image['title'],
                    'caption' => $image['caption'],
                );
            }
        }
        
        return $entry;
    }, 10, 2 );
}

/**
 * 投稿内の画像を取得
 */
function corporate_seo_pro_get_post_images( $post ) {
    $images = array();
    
    // アイキャッチ画像
    if ( has_post_thumbnail( $post->ID ) ) {
        $thumbnail_id = get_post_thumbnail_id( $post->ID );
        $images[] = array(
            'url' => wp_get_attachment_url( $thumbnail_id ),
            'title' => get_the_title( $thumbnail_id ),
            'caption' => wp_get_attachment_caption( $thumbnail_id ),
        );
    }
    
    // 本文内の画像
    $content = $post->post_content;
    preg_match_all( '/<img[^>]+src="([^"]+)"[^>]*>/i', $content, $matches );
    
    if ( ! empty( $matches[1] ) ) {
        foreach ( $matches[1] as $image_url ) {
            $attachment_id = attachment_url_to_postid( $image_url );
            if ( $attachment_id ) {
                $images[] = array(
                    'url' => $image_url,
                    'title' => get_the_title( $attachment_id ),
                    'caption' => wp_get_attachment_caption( $attachment_id ),
                );
            }
        }
    }
    
    return $images;
}

/**
 * ニュースサイトマップの生成
 */
function corporate_seo_pro_news_sitemap() {
    if ( ! isset( $_GET['sitemap'] ) || $_GET['sitemap'] !== 'news' ) {
        return;
    }
    
    header( 'Content-Type: application/xml; charset=utf-8' );
    
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 1000,
        'date_query' => array(
            array(
                'after' => '2 days ago',
            ),
        ),
        'orderby' => 'date',
        'order' => 'DESC',
    );
    
    $posts = get_posts( $args );
    
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">';
    
    foreach ( $posts as $post ) {
        echo '<url>';
        echo '<loc>' . get_permalink( $post ) . '</loc>';
        echo '<news:news>';
        echo '<news:publication>';
        echo '<news:name>' . get_bloginfo( 'name' ) . '</news:name>';
        echo '<news:language>ja</news:language>';
        echo '</news:publication>';
        echo '<news:publication_date>' . get_the_date( 'c', $post ) . '</news:publication_date>';
        echo '<news:title>' . esc_xml( get_the_title( $post ) ) . '</news:title>';
        
        // キーワード
        $tags = get_the_tags( $post->ID );
        if ( $tags ) {
            $keywords = array();
            foreach ( $tags as $tag ) {
                $keywords[] = $tag->name;
            }
            echo '<news:keywords>' . esc_xml( implode( ', ', $keywords ) ) . '</news:keywords>';
        }
        
        echo '</news:news>';
        echo '</url>';
    }
    
    echo '</urlset>';
    exit;
}

/**
 * サイトマップのスタイルシート
 */
function corporate_seo_pro_sitemap_stylesheet() {
    $xsl = '<?xml version="1.0" encoding="UTF-8"?>
    <xsl:stylesheet version="2.0"
        xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
        xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
        <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
        <xsl:template match="/">
            <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                    <title>XMLサイトマップ - ' . get_bloginfo( 'name' ) . '</title>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                    <style type="text/css">
                        body {
                            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Noto Sans JP", sans-serif;
                            font-size: 14px;
                            color: #333;
                            background: #f8f9fa;
                            margin: 0;
                            padding: 20px;
                        }
                        .container {
                            max-width: 1200px;
                            margin: 0 auto;
                            background: white;
                            border-radius: 8px;
                            padding: 30px;
                            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                        }
                        h1 {
                            color: #1e3a8a;
                            font-size: 24px;
                            margin-bottom: 20px;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                        }
                        th {
                            background: #f1f5f9;
                            padding: 12px;
                            text-align: left;
                            font-weight: 600;
                        }
                        td {
                            padding: 12px;
                            border-bottom: 1px solid #e5e7eb;
                        }
                        tr:hover {
                            background: #f8fafc;
                        }
                        a {
                            color: #3b82f6;
                            text-decoration: none;
                        }
                        a:hover {
                            text-decoration: underline;
                        }
                        .priority {
                            font-weight: 600;
                            color: #059669;
                        }
                        .lastmod {
                            color: #6b7280;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h1>XMLサイトマップ</h1>
                        <p>このXMLサイトマップには <xsl:value-of select="count(sitemap:urlset/sitemap:url)"/> 個のURLが含まれています。</p>
                        <table>
                            <thead>
                                <tr>
                                    <th>URL</th>
                                    <th>優先度</th>
                                    <th>更新頻度</th>
                                    <th>最終更新日</th>
                                </tr>
                            </thead>
                            <tbody>
                                <xsl:for-each select="sitemap:urlset/sitemap:url">
                                    <tr>
                                        <td>
                                            <a href="{sitemap:loc}">
                                                <xsl:value-of select="sitemap:loc"/>
                                            </a>
                                        </td>
                                        <td class="priority">
                                            <xsl:value-of select="sitemap:priority"/>
                                        </td>
                                        <td>
                                            <xsl:value-of select="sitemap:changefreq"/>
                                        </td>
                                        <td class="lastmod">
                                            <xsl:value-of select="sitemap:lastmod"/>
                                        </td>
                                    </tr>
                                </xsl:for-each>
                            </tbody>
                        </table>
                    </div>
                </body>
            </html>
        </xsl:template>
    </xsl:stylesheet>';
    
    return $xsl;
}

/**
 * robots.txtにサイトマップを追加
 */
function corporate_seo_pro_robots_sitemap( $output, $public ) {
    if ( '1' == $public ) {
        $output .= "\n# Sitemaps\n";
        $output .= "Sitemap: " . home_url( '/wp-sitemap.xml' ) . "\n";
        $output .= "Sitemap: " . home_url( '/?sitemap=news' ) . "\n";
        
        // カスタム投稿タイプのサイトマップ
        $post_types = get_post_types( array( 'public' => true, '_builtin' => false ) );
        foreach ( $post_types as $post_type ) {
            $output .= "Sitemap: " . home_url( '/wp-sitemap-posts-' . $post_type . '-1.xml' ) . "\n";
        }
    }
    
    return $output;
}
add_filter( 'robots_txt', 'corporate_seo_pro_robots_sitemap', 10, 2 );