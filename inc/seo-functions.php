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
        if ( empty( $description ) ) {
            $description = '千葉県千葉市のホームページ制作会社。SEO対策、レスポンシブデザイン、WordPress開発に強みを持つWeb制作サービスを提供しています。';
        }
    }

    if ( ! empty( $description ) ) {
        echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
    }

    // 地域キーワード
    if ( is_home() || is_front_page() ) {
        echo '<meta name="keywords" content="ホームページ制作,千葉市,千葉県,Web制作,SEO対策,WordPress,レスポンシブデザイン,Webマーケティング">' . "\n";
        echo '<meta name="geo.region" content="JP-12">' . "\n";
        echo '<meta name="geo.placename" content="千葉市">' . "\n";
        echo '<meta property="og:locale" content="ja_JP">' . "\n";
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
    
}
add_action( 'wp_head', 'corporate_seo_pro_meta_tags', 1 );

/**
 * Canonical URLの出力（拡張版）
 *
 * すべてのページタイプに対応したcanonical URL設定
 */
function corporate_seo_pro_canonical_url() {
    $canonical_url = '';

    // フロントページ
    if ( is_front_page() ) {
        $canonical_url = home_url( '/' );
    }
    // 個別投稿・固定ページ
    elseif ( is_singular() ) {
        $canonical_url = get_permalink();
    }
    // カテゴリーアーカイブ
    elseif ( is_category() ) {
        $canonical_url = get_category_link( get_queried_object_id() );
    }
    // タグアーカイブ
    elseif ( is_tag() ) {
        $canonical_url = get_tag_link( get_queried_object_id() );
    }
    // タクソノミーアーカイブ
    elseif ( is_tax() ) {
        $term = get_queried_object();
        if ( $term && ! is_wp_error( $term ) ) {
            $canonical_url = get_term_link( $term );
        }
    }
    // 投稿タイプアーカイブ
    elseif ( is_post_type_archive() ) {
        $post_type = get_queried_object();
        if ( $post_type ) {
            $canonical_url = get_post_type_archive_link( $post_type->name );
        }
    }
    // 著者アーカイブ
    elseif ( is_author() ) {
        $canonical_url = get_author_posts_url( get_queried_object_id() );
    }
    // 日付アーカイブ
    elseif ( is_date() ) {
        if ( is_year() ) {
            $canonical_url = get_year_link( get_the_date( 'Y' ) );
        } elseif ( is_month() ) {
            $canonical_url = get_month_link( get_the_date( 'Y' ), get_the_date( 'm' ) );
        } elseif ( is_day() ) {
            $canonical_url = get_day_link( get_the_date( 'Y' ), get_the_date( 'm' ), get_the_date( 'd' ) );
        }
    }
    // ホームページ（ブログページ）
    elseif ( is_home() ) {
        $page_for_posts = get_option( 'page_for_posts' );
        if ( $page_for_posts ) {
            $canonical_url = get_permalink( $page_for_posts );
        } else {
            $canonical_url = home_url( '/' );
        }
    }

    // canonical URLを出力
    if ( ! empty( $canonical_url ) && ! is_wp_error( $canonical_url ) ) {
        echo '<link rel="canonical" href="' . esc_url( $canonical_url ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'corporate_seo_pro_canonical_url', 2 );

/**
 * ページネーション用のprev/nextリンク
 */
function corporate_seo_pro_pagination_links() {
    global $wp_query;

    // アーカイブページでのページネーション
    if ( ! is_singular() && $wp_query->max_num_pages > 1 ) {
        $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

        // 前のページ
        if ( $paged > 1 ) {
            $prev_url = get_pagenum_link( $paged - 1 );
            echo '<link rel="prev" href="' . esc_url( $prev_url ) . '">' . "\n";
        }

        // 次のページ
        if ( $paged < $wp_query->max_num_pages ) {
            $next_url = get_pagenum_link( $paged + 1 );
            echo '<link rel="next" href="' . esc_url( $next_url ) . '">' . "\n";
        }
    }
}
add_action( 'wp_head', 'corporate_seo_pro_pagination_links', 3 );

/**
 * 検索結果ページにnoindexを追加
 */
function corporate_seo_pro_search_noindex() {
    if ( is_search() ) {
        echo '<meta name="robots" content="noindex,follow">' . "\n";
    }
}
add_action( 'wp_head', 'corporate_seo_pro_search_noindex', 1 );

/**
 * robots.txtの最適化（AIクローラー対応版）
 */
function corporate_seo_pro_robots_txt( $output, $public ) {
    if ( '1' == $public ) {
        // クリーンアップ
        $output = "User-agent: *\n";
        $output .= "Allow: /\n";
        $output .= "Crawl-delay: 1\n\n";

        // ===================================
        // AI Crawlers - Welcome（生成AI対応）
        // ===================================
        $output .= "# AI Crawlers - Welcome\n\n";

        // GPTBot (OpenAI/ChatGPT)
        $output .= "User-agent: GPTBot\n";
        $output .= "Allow: /\n";
        $output .= "Disallow: /wp-admin/\n";
        $output .= "Disallow: /*?*\n\n";

        // ChatGPT-User (ChatGPT browse mode)
        $output .= "User-agent: ChatGPT-User\n";
        $output .= "Allow: /\n";
        $output .= "Disallow: /wp-admin/\n\n";

        // ClaudeBot (Anthropic/Claude)
        $output .= "User-agent: ClaudeBot\n";
        $output .= "Allow: /\n";
        $output .= "Disallow: /wp-admin/\n";
        $output .= "Disallow: /*?*\n\n";

        // Claude-Web (Anthropic Claude Web)
        $output .= "User-agent: Claude-Web\n";
        $output .= "Allow: /\n";
        $output .= "Disallow: /wp-admin/\n\n";

        // PerplexityBot
        $output .= "User-agent: PerplexityBot\n";
        $output .= "Allow: /\n";
        $output .= "Disallow: /wp-admin/\n";
        $output .= "Disallow: /*?*\n\n";

        // Google-Extended (Gemini/Bard AI training)
        $output .= "User-agent: Google-Extended\n";
        $output .= "Allow: /\n";
        $output .= "Disallow: /wp-admin/\n\n";

        // Amazonbot (Amazon/Alexa)
        $output .= "User-agent: Amazonbot\n";
        $output .= "Allow: /\n";
        $output .= "Disallow: /wp-admin/\n\n";

        // CCBot (Common Crawl - AI training data)
        $output .= "User-agent: CCBot\n";
        $output .= "Allow: /\n";
        $output .= "Disallow: /wp-admin/\n\n";

        // Cohere AI
        $output .= "User-agent: cohere-ai\n";
        $output .= "Allow: /\n";
        $output .= "Disallow: /wp-admin/\n\n";

        // Meta AI (Facebook/Meta)
        $output .= "User-agent: FacebookBot\n";
        $output .= "Allow: /\n";
        $output .= "Disallow: /wp-admin/\n\n";

        // Bytespider (ByteDance/TikTok AI)
        $output .= "User-agent: Bytespider\n";
        $output .= "Allow: /\n";
        $output .= "Disallow: /wp-admin/\n\n";

        // ===================================
        // WordPress関連ディレクトリ
        // ===================================
        $output .= "# WordPress Core\n";
        $output .= "User-agent: *\n";
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

        // ===================================
        // 検索結果とフィードのブロック
        // ===================================
        $output .= "# Search & Feeds\n";
        $output .= "Disallow: /?s=\n";
        $output .= "Disallow: /search/\n";
        $output .= "Disallow: /feed/\n";
        $output .= "Disallow: */feed/\n";
        $output .= "Disallow: */trackback/\n";
        $output .= "Disallow: */comment-page-\n";
        $output .= "Disallow: */comments/\n\n";

        // ===================================
        // パラメータ付きURLのブロック
        // ===================================
        $output .= "# URL Parameters\n";
        $output .= "Disallow: /*?*\n";
        $output .= "Disallow: /*?\n";
        $output .= "Allow: /*?utm_*\n";
        $output .= "Allow: /*?page=\n\n";

        // ===================================
        // ファイルタイプ
        // ===================================
        $output .= "# File Types\n";
        $output .= "Disallow: /*.pdf$\n";
        $output .= "Allow: /wp-content/uploads/*.pdf$\n\n";

        // ===================================
        // 特定のボット向け設定
        // ===================================
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

        $output .= "# Bingbot\n";
        $output .= "User-agent: Bingbot\n";
        $output .= "Allow: /\n";
        $output .= "Disallow: /wp-admin/\n";
        $output .= "Allow: /wp-admin/admin-ajax.php\n\n";

        // ===================================
        // 悪意のあるボットのブロック
        // ===================================
        $output .= "# Bad Bots\n";
        $bad_bots = array( 'AhrefsBot', 'SemrushBot', 'DotBot', 'MJ12bot', 'BLEXBot', 'DataForSeoBot' );
        foreach ( $bad_bots as $bot ) {
            $output .= "User-agent: $bot\n";
            $output .= "Disallow: /\n\n";
        }

        // ===================================
        // サイトマップとAIコンテンツ
        // ===================================
        $output .= "# Sitemaps & AI Content\n";
        $output .= "Sitemap: " . home_url( '/wp-sitemap.xml' ) . "\n";
        $output .= "Sitemap: " . home_url( '/?sitemap=news' ) . "\n";
        $output .= "# LLMs.txt: " . home_url( '/llms.txt' ) . "\n";
        $output .= "# LLMs-Full.txt: " . home_url( '/llms-full.txt' ) . "\n";
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
        $site_name = get_bloginfo( 'name' );
        $site_description = get_bloginfo( 'description' );

        // トップページのタイトルに地域情報を含める
        $title['title'] = $site_name;
        if ( ! empty( $site_description ) ) {
            $title['tagline'] = $site_description . ' | 千葉県千葉市';
        } else {
            $title['tagline'] = '千葉県千葉市のホームページ制作';
        }
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