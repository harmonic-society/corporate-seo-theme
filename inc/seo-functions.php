<?php
/**
 * SEO関連の機能
 *
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * メタタグの出力
 */
function corporate_seo_pro_meta_tags() {
    global $post;
    
    // メタディスクリプション
    $description = '';
    $og_title = '';
    $og_description = '';
    $og_image_url = '';
    $news_ogp_image = array();
    
    if ( is_singular() ) {
        $description = get_post_meta( $post->ID, '_corporate_seo_meta_description', true );
        if ( empty( $description ) ) {
            $description = wp_trim_words( strip_tags( $post->post_content ), 30, '...' );
        }
    } elseif ( is_category() || is_tag() ) {
        $description = term_description();
    } elseif ( is_home() || is_front_page() ) {
        $description = get_bloginfo( 'description' );
    }
    
    if ( ! empty( $description ) ) {
        echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
    }
    
    // OGPタグ
    if ( is_singular() ) {
        // OGPタイトル（ニュースリリースの場合はカスタムタイトルを確認）
        $og_title = get_the_title();
        if ( is_singular( 'news' ) && function_exists( 'get_field' ) ) {
            $custom_og_title = get_field( 'news_ogp_title' );
            if ( ! empty( $custom_og_title ) ) {
                $og_title = $custom_og_title;
            }
        }
        echo '<meta property="og:title" content="' . esc_attr( $og_title ) . '">' . "\n";
        echo '<meta property="og:type" content="article">' . "\n";
        echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '">' . "\n";
        
        // OGP画像
        $og_image_url = '';
        if ( is_singular( 'news' ) && function_exists( 'get_field' ) ) {
            $news_ogp_image = get_field( 'news_ogp_image' );
            if ( ! empty( $news_ogp_image ) && is_array( $news_ogp_image ) ) {
                $og_image_url = $news_ogp_image['url'];
            }
        }
        
        // カスタムOGP画像がない場合はアイキャッチ画像を使用
        if ( empty( $og_image_url ) && has_post_thumbnail() ) {
            $thumbnail_id = get_post_thumbnail_id();
            $thumbnail_url = wp_get_attachment_image_src( $thumbnail_id, 'large' );
            $og_image_url = $thumbnail_url[0];
        }
        
        if ( ! empty( $og_image_url ) ) {
            echo '<meta property="og:image" content="' . esc_url( $og_image_url ) . '">' . "\n";
            
            // 画像の幅と高さも出力
            if ( is_singular( 'news' ) && ! empty( $news_ogp_image ) && is_array( $news_ogp_image ) ) {
                if ( ! empty( $news_ogp_image['width'] ) ) {
                    echo '<meta property="og:image:width" content="' . esc_attr( $news_ogp_image['width'] ) . '">' . "\n";
                }
                if ( ! empty( $news_ogp_image['height'] ) ) {
                    echo '<meta property="og:image:height" content="' . esc_attr( $news_ogp_image['height'] ) . '">' . "\n";
                }
            }
        }
        
        // OGP説明文（ニュースリリースの場合はカスタム説明文を確認）
        $og_description = $description;
        if ( is_singular( 'news' ) && function_exists( 'get_field' ) ) {
            $custom_og_description = get_field( 'news_ogp_description' );
            if ( ! empty( $custom_og_description ) ) {
                $og_description = $custom_og_description;
            }
        }
        
        if ( ! empty( $og_description ) ) {
            echo '<meta property="og:description" content="' . esc_attr( $og_description ) . '">' . "\n";
        }
    }
    
    echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
    echo '<meta property="og:locale" content="ja_JP">' . "\n";
    
    // Twitter Card
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    if ( get_theme_mod( 'twitter_username' ) ) {
        echo '<meta name="twitter:site" content="@' . esc_attr( get_theme_mod( 'twitter_username' ) ) . '">' . "\n";
    }
    
    // Twitter Card用のメタタグも追加
    if ( is_singular() ) {
        if ( ! empty( $og_title ) ) {
            echo '<meta name="twitter:title" content="' . esc_attr( $og_title ) . '">' . "\n";
        }
        if ( ! empty( $og_description ) ) {
            echo '<meta name="twitter:description" content="' . esc_attr( $og_description ) . '">' . "\n";
        }
        if ( ! empty( $og_image_url ) ) {
            echo '<meta name="twitter:image" content="' . esc_url( $og_image_url ) . '">' . "\n";
        }
    }
    
    // Canonical URL
    if ( is_singular() ) {
        echo '<link rel="canonical" href="' . esc_url( get_permalink() ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'corporate_seo_pro_meta_tags', 1 );

/**
 * robots.txtの最適化
 */
function corporate_seo_pro_robots_txt( $output, $public ) {
    if ( '1' == $public ) {
        // クリーンアップ
        $output = "User-agent: *\n";
        $output .= "Allow: /\n\n";
        
        // クロール遅延（秒）
        $output .= "Crawl-delay: 1\n\n";
        
        // WordPress関連ディレクトリのブロック
        $output .= "# WordPress Core\n";
        $output .= "Disallow: /wp-admin/\n";
        $output .= "Allow: /wp-admin/admin-ajax.php\n";
        $output .= "Disallow: /wp-includes/\n";
        $output .= "Allow: /wp-includes/js/\n";
        $output .= "Allow: /wp-includes/css/\n";
        $output .= "Allow: /wp-includes/images/\n";
        $output .= "Allow: /wp-includes/fonts/\n";
        $output .= "Disallow: /wp-content/plugins/\n";
        $output .= "Disallow: /wp-content/cache/\n";
        $output .= "Disallow: /wp-content/themes/\n";
        $output .= "Allow: /wp-content/themes/*/assets/\n";
        $output .= "Allow: /wp-content/uploads/\n\n";
        
        // 検索結果とフィードのブロック
        $output .= "# Search & Feeds\n";
        $output .= "Disallow: /?s=\n";
        $output .= "Disallow: /search/\n";
        $output .= "Disallow: /feed/\n";
        $output .= "Disallow: */feed/\n";
        $output .= "Disallow: */trackback/\n";
        $output .= "Disallow: */comment-page-\n";
        $output .= "Disallow: */comments/\n\n";
        
        // パラメータ付きURLのブロック
        $output .= "# URL Parameters\n";
        $output .= "Disallow: /*?*\n";
        $output .= "Disallow: /*?\n";
        $output .= "Allow: /*?utm_*\n";
        $output .= "Allow: /*?page=\n\n";
        
        // ファイルタイプ
        $output .= "# File Types\n";
        $output .= "Disallow: /*.pdf$\n";
        $output .= "Allow: /wp-content/uploads/*.pdf$\n\n";
        
        // 特定のボット向け設定
        $output .= "# Googlebot\n";
        $output .= "User-agent: Googlebot\n";
        $output .= "Allow: /\n";
        $output .= "Disallow: /wp-admin/\n";
        $output .= "Allow: /wp-admin/admin-ajax.php\n\n";
        
        $output .= "# Googlebot Image\n";
        $output .= "User-agent: Googlebot-Image\n";
        $output .= "Allow: /wp-content/uploads/\n";
        $output .= "Disallow: /wp-content/themes/\n";
        $output .= "Allow: /wp-content/themes/*/assets/images/\n\n";
        
        // 悪意のあるボットのブロック
        $output .= "# Bad Bots\n";
        $bad_bots = array( 'AhrefsBot', 'SemrushBot', 'DotBot', 'MJ12bot' );
        foreach ( $bad_bots as $bot ) {
            $output .= "User-agent: $bot\n";
            $output .= "Disallow: /\n\n";
        }
        
        // サイトマップ
        $output .= "# Sitemaps\n";
        $output .= "Sitemap: " . home_url( '/wp-sitemap.xml' ) . "\n";
        $output .= "Sitemap: " . home_url( '/?sitemap=news' ) . "\n";
    }
    
    return $output;
}
add_filter( 'robots_txt', 'corporate_seo_pro_robots_txt', 10, 2 );

/**
 * XMLサイトマップのサポート
 */
function corporate_seo_pro_sitemap_support() {
    add_filter( 'wp_sitemaps_add_provider', function( $provider, $name ) {
        if ( 'users' === $name ) {
            return false;
        }
        return $provider;
    }, 10, 2 );
}
add_action( 'init', 'corporate_seo_pro_sitemap_support' );

/**
 * メタボックスの追加
 */
function corporate_seo_pro_add_meta_boxes() {
    add_meta_box(
        'corporate_seo_meta',
        __( 'SEO設定', 'corporate-seo-pro' ),
        'corporate_seo_pro_meta_box_callback',
        array( 'post', 'page', 'service' ),
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'corporate_seo_pro_add_meta_boxes' );

/**
 * メタボックスのコールバック
 */
function corporate_seo_pro_meta_box_callback( $post ) {
    wp_nonce_field( 'corporate_seo_pro_save_meta_box_data', 'corporate_seo_pro_meta_box_nonce' );
    
    $meta_description = get_post_meta( $post->ID, '_corporate_seo_meta_description', true );
    $meta_keywords = get_post_meta( $post->ID, '_corporate_seo_meta_keywords', true );
    $noindex = get_post_meta( $post->ID, '_corporate_seo_noindex', true );
    ?>
    <p>
        <label for="corporate_seo_meta_description"><?php _e( 'メタディスクリプション', 'corporate-seo-pro' ); ?></label>
        <textarea id="corporate_seo_meta_description" name="corporate_seo_meta_description" rows="3" style="width:100%;"><?php echo esc_textarea( $meta_description ); ?></textarea>
        <span class="description"><?php _e( '検索結果に表示される説明文です。160文字以内を推奨します。', 'corporate-seo-pro' ); ?></span>
    </p>
    <p>
        <label for="corporate_seo_meta_keywords"><?php _e( 'メタキーワード', 'corporate-seo-pro' ); ?></label>
        <input type="text" id="corporate_seo_meta_keywords" name="corporate_seo_meta_keywords" value="<?php echo esc_attr( $meta_keywords ); ?>" style="width:100%;">
        <span class="description"><?php _e( 'カンマ区切りでキーワードを入力してください。', 'corporate-seo-pro' ); ?></span>
    </p>
    <p>
        <label>
            <input type="checkbox" name="corporate_seo_noindex" value="1" <?php checked( $noindex, '1' ); ?>>
            <?php _e( '検索エンジンにインデックスさせない（noindex）', 'corporate-seo-pro' ); ?>
        </label>
    </p>
    <?php
}

/**
 * メタボックスの保存
 */
function corporate_seo_pro_save_meta_box_data( $post_id ) {
    if ( ! isset( $_POST['corporate_seo_pro_meta_box_nonce'] ) ) {
        return;
    }
    
    if ( ! wp_verify_nonce( $_POST['corporate_seo_pro_meta_box_nonce'], 'corporate_seo_pro_save_meta_box_data' ) ) {
        return;
    }
    
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    if ( isset( $_POST['corporate_seo_meta_description'] ) ) {
        update_post_meta( $post_id, '_corporate_seo_meta_description', sanitize_textarea_field( $_POST['corporate_seo_meta_description'] ) );
    }
    
    if ( isset( $_POST['corporate_seo_meta_keywords'] ) ) {
        update_post_meta( $post_id, '_corporate_seo_meta_keywords', sanitize_text_field( $_POST['corporate_seo_meta_keywords'] ) );
    }
    
    update_post_meta( $post_id, '_corporate_seo_noindex', isset( $_POST['corporate_seo_noindex'] ) ? '1' : '' );
}
add_action( 'save_post', 'corporate_seo_pro_save_meta_box_data' );

/**
 * noindexの処理
 */
function corporate_seo_pro_noindex() {
    if ( is_singular() ) {
        global $post;
        $noindex = get_post_meta( $post->ID, '_corporate_seo_noindex', true );
        if ( $noindex ) {
            echo '<meta name="robots" content="noindex,follow">' . "\n";
        }
    }
}
add_action( 'wp_head', 'corporate_seo_pro_noindex' );

/**
 * 画像の自動alt属性追加
 */
function corporate_seo_pro_auto_image_alt( $attr, $attachment ) {
    if ( empty( $attr['alt'] ) ) {
        $attr['alt'] = get_the_title( $attachment->ID );
    }
    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'corporate_seo_pro_auto_image_alt', 10, 2 );

/**
 * タイトルタグのカスタマイズ
 */
function corporate_seo_pro_document_title_parts( $title ) {
    if ( is_home() || is_front_page() ) {
        unset( $title['tagline'] );
    }
    
    if ( is_category() ) {
        $title['title'] = single_cat_title( '', false ) . ' | カテゴリー';
    }
    
    if ( is_tag() ) {
        $title['title'] = single_tag_title( '', false ) . ' | タグ';
    }
    
    if ( is_search() ) {
        $title['title'] = '「' . get_search_query() . '」の検索結果';
    }
    
    if ( is_404() ) {
        $title['title'] = '404 ページが見つかりません';
    }
    
    return $title;
}
add_filter( 'document_title_parts', 'corporate_seo_pro_document_title_parts' );

/**
 * タイトルセパレーターの変更
 */
function corporate_seo_pro_document_title_separator( $sep ) {
    return '|';
}
add_filter( 'document_title_separator', 'corporate_seo_pro_document_title_separator' );