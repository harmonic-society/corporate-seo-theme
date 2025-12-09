<?php
/**
 * Blog Card Shortcode
 * 記事本文内でリッチなブログカードを表示するショートコード
 *
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register blog card shortcode
 */
function corporate_seo_pro_register_blog_card_shortcode() {
    add_shortcode( 'blog_card', 'corporate_seo_pro_blog_card_shortcode' );
}
add_action( 'init', 'corporate_seo_pro_register_blog_card_shortcode' );

/**
 * Blog card shortcode callback
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output.
 */
function corporate_seo_pro_blog_card_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'id'             => '',
            'url'            => '',
            'style'          => 'default',
            'show_excerpt'   => 'true',
            'show_thumbnail' => 'true',
            'show_category'  => 'true',
            'show_date'      => 'true',
        ),
        $atts,
        'blog_card'
    );

    // Get post object.
    $post = corporate_seo_pro_get_blog_card_post( $atts );

    if ( ! $post ) {
        return corporate_seo_pro_blog_card_error_message();
    }

    // Generate and return card HTML.
    return corporate_seo_pro_render_blog_card( $post, $atts );
}

/**
 * Get post object from ID or URL
 *
 * @param array $atts Shortcode attributes.
 * @return WP_Post|null Post object or null.
 */
function corporate_seo_pro_get_blog_card_post( $atts ) {
    if ( ! empty( $atts['id'] ) ) {
        $post = get_post( intval( $atts['id'] ) );
        if ( $post && 'publish' === $post->post_status ) {
            return $post;
        }
    }

    if ( ! empty( $atts['url'] ) ) {
        $post_id = url_to_postid( esc_url( $atts['url'] ) );
        if ( $post_id ) {
            $post = get_post( $post_id );
            if ( $post && 'publish' === $post->post_status ) {
                return $post;
            }
        }
    }

    return null;
}

/**
 * Render blog card HTML
 *
 * @param WP_Post $post Post object.
 * @param array   $atts Shortcode attributes.
 * @return string HTML output.
 */
function corporate_seo_pro_render_blog_card( $post, $atts ) {
    // Convert string booleans.
    $show_excerpt   = filter_var( $atts['show_excerpt'], FILTER_VALIDATE_BOOLEAN );
    $show_thumbnail = filter_var( $atts['show_thumbnail'], FILTER_VALIDATE_BOOLEAN );
    $show_category  = filter_var( $atts['show_category'], FILTER_VALIDATE_BOOLEAN );
    $show_date      = filter_var( $atts['show_date'], FILTER_VALIDATE_BOOLEAN );

    // Sanitize style.
    $valid_styles = array( 'default', 'compact', 'full' );
    $style        = in_array( $atts['style'], $valid_styles, true ) ? $atts['style'] : 'default';

    // Get post data.
    $permalink  = get_permalink( $post );
    $title      = get_the_title( $post );
    $date       = get_the_date( '', $post );
    $datetime   = get_the_date( 'c', $post );
    $categories = get_the_category( $post->ID );

    // Get excerpt.
    $excerpt = '';
    if ( $show_excerpt && 'compact' !== $style ) {
        if ( $post->post_excerpt ) {
            $excerpt = wp_trim_words( strip_tags( $post->post_excerpt ), 40, '...' );
        } else {
            $excerpt = wp_trim_words( strip_tags( $post->post_content ), 40, '...' );
        }
    }

    // Start output buffering.
    ob_start();

    // Inline styles to override any conflicting CSS
    $card_style = 'display:flex!important;flex-direction:row!important;flex-wrap:nowrap!important;align-items:stretch!important;';
    $thumbnail_style = 'display:block!important;flex:0 0 200px!important;width:200px!important;min-width:200px!important;max-width:200px!important;height:150px!important;overflow:hidden!important;';
    $content_style = 'display:block!important;flex:1 1 auto!important;min-width:0!important;padding:1.25rem!important;';
    $img_style = 'width:200px!important;height:150px!important;object-fit:cover!important;margin:0!important;';

    // Build HTML without whitespace to prevent wpautop issues
    $html = '<a href="' . esc_url( $permalink ) . '" class="blog-card-shortcode blog-card-shortcode--' . esc_attr( $style ) . '" style="' . esc_attr( $card_style ) . '">';

    if ( $show_thumbnail ) {
        $html .= '<span class="blog-card-thumbnail" style="' . esc_attr( $thumbnail_style ) . '">';
        if ( has_post_thumbnail( $post->ID ) ) {
            $html .= get_the_post_thumbnail( $post->ID, 'medium', array(
                'loading' => 'lazy',
                'alt'     => esc_attr( $title ),
                'style'   => $img_style,
            ) );
        } else {
            $html .= '<span class="blog-card-thumbnail--placeholder"><i class="fas fa-file-alt"></i></span>';
        }
        $html .= '</span>';
    }

    $html .= '<span class="blog-card-content" style="' . esc_attr( $content_style ) . '">';

    if ( ( $show_category && ! empty( $categories ) ) || $show_date ) {
        $html .= '<span class="blog-card-meta" style="display:flex!important;align-items:center!important;gap:0.75rem!important;margin-bottom:0.5rem!important;">';
        if ( $show_category && ! empty( $categories ) ) {
            $html .= '<span class="blog-card-category">' . esc_html( $categories[0]->name ) . '</span>';
        }
        if ( $show_date && 'compact' !== $style ) {
            $html .= '<time class="blog-card-date" datetime="' . esc_attr( $datetime ) . '">' . esc_html( $date ) . '</time>';
        }
        $html .= '</span>';
    }

    $html .= '<span class="blog-card-title" style="display:block!important;font-size:1.0625rem!important;font-weight:600!important;color:#1f2937!important;line-height:1.5!important;margin:0 0 0.5rem 0!important;">' . esc_html( $title ) . '</span>';

    if ( $show_excerpt && ! empty( $excerpt ) && 'compact' !== $style ) {
        $html .= '<span class="blog-card-excerpt" style="display:block!important;font-size:0.875rem!important;color:#6b7280!important;line-height:1.6!important;margin:0!important;">' . esc_html( $excerpt ) . '</span>';
    }

    $html .= '</span></a>';

    echo $html;

    return ob_get_clean();
}

/**
 * Error message when post not found
 *
 * @return string HTML output.
 */
function corporate_seo_pro_blog_card_error_message() {
    if ( current_user_can( 'edit_posts' ) ) {
        return '<div class="blog-card-error">' . esc_html__( '指定された記事が見つかりません。IDまたはURLを確認してください。', 'corporate-seo-pro' ) . '</div>';
    }
    return '';
}

/**
 * Clear blog card cache when post is updated
 *
 * @param int $post_id Post ID.
 */
function corporate_seo_pro_clear_blog_card_cache( $post_id ) {
    delete_transient( 'blog_card_' . $post_id );
}
add_action( 'save_post', 'corporate_seo_pro_clear_blog_card_cache' );
add_action( 'delete_post', 'corporate_seo_pro_clear_blog_card_cache' );

/**
 * Auto-convert internal URLs to blog cards
 * 自サイトURLを自動的にブログカードに変換
 *
 * @param string $content Post content.
 * @return string Modified content.
 */
function corporate_seo_pro_auto_blog_card( $content ) {
    // Only process on singular posts/pages and main query.
    if ( ! is_singular( array( 'post', 'page' ) ) || ! in_the_loop() || ! is_main_query() ) {
        return $content;
    }

    // Check if auto blog card is enabled (default: true).
    if ( ! apply_filters( 'corporate_seo_pro_enable_auto_blog_card', true ) ) {
        return $content;
    }

    // Skip if already contains blog card (prevent double processing).
    if ( strpos( $content, 'blog-card-shortcode' ) !== false ) {
        return $content;
    }

    // Get site URL for matching.
    $site_url  = home_url();
    $site_host = wp_parse_url( $site_url, PHP_URL_HOST );

    // Helper function to convert URL to blog card.
    $convert_url_to_card = function( $url ) use ( $site_host ) {
        // Parse URL to check if it's an internal link.
        $url_host = wp_parse_url( $url, PHP_URL_HOST );

        // Only convert internal URLs.
        if ( $url_host !== $site_host ) {
            return null;
        }

        // Try to get post ID from URL.
        $post_id = url_to_postid( $url );

        if ( ! $post_id ) {
            return null;
        }

        $post = get_post( $post_id );

        // Check if post exists and is published.
        if ( ! $post || 'publish' !== $post->post_status ) {
            return null;
        }

        // Prevent self-referencing.
        if ( $post_id === get_the_ID() ) {
            return null;
        }

        // Render blog card.
        $atts = array(
            'id'             => $post_id,
            'url'            => '',
            'style'          => 'default',
            'show_excerpt'   => 'true',
            'show_thumbnail' => 'true',
            'show_category'  => 'true',
            'show_date'      => 'true',
        );

        return corporate_seo_pro_render_blog_card( $post, $atts );
    };

    // Pattern 1: Match standalone URLs in paragraph.
    // Matches: <p>https://example.com/post-slug/</p>
    $content = preg_replace_callback(
        '#<p>\s*(https?://[^\s<>"\']+)\s*</p>#i',
        function( $matches ) use ( $convert_url_to_card ) {
            $card = $convert_url_to_card( $matches[1] );
            return $card ? $card : $matches[0];
        },
        $content
    );

    // Pattern 2: Match WordPress auto-linked URLs.
    // Matches: <p><a href="URL">URL</a></p>
    $content = preg_replace_callback(
        '#<p>\s*<a\s+href=["\']?(https?://[^"\'>\s]+)["\']?[^>]*>https?://[^<]+</a>\s*</p>#i',
        function( $matches ) use ( $convert_url_to_card ) {
            $card = $convert_url_to_card( $matches[1] );
            return $card ? $card : $matches[0];
        },
        $content
    );

    return $content;
}
// Priority 15: After wpautop (10), make_clickable (9), but BEFORE auto_internal_links (20).
add_filter( 'the_content', 'corporate_seo_pro_auto_blog_card', 15 );

/**
 * Register Gutenberg block for blog card
 */
function corporate_seo_pro_register_blog_card_block() {
    // ブロックエディタが利用可能か確認
    if ( ! function_exists( 'register_block_type' ) ) {
        return;
    }

    // エディタ用スクリプトを登録
    wp_register_script(
        'corporate-seo-pro-blog-card-block',
        get_template_directory_uri() . '/assets/js/admin/blog-card-block.js',
        array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'wp-api-fetch' ),
        wp_get_theme()->get( 'Version' ),
        true
    );

    // エディタ用スタイルを登録
    wp_register_style(
        'corporate-seo-pro-blog-card-block-editor',
        get_template_directory_uri() . '/assets/css/admin/blog-card-block.css',
        array( 'wp-edit-blocks' ),
        wp_get_theme()->get( 'Version' )
    );

    // フロント用スタイルを登録
    wp_register_style(
        'corporate-seo-pro-blog-card-block-style',
        get_template_directory_uri() . '/assets/css/components/blog-card.css',
        array(),
        wp_get_theme()->get( 'Version' )
    );

    // ブロックを登録
    register_block_type( 'corporate-seo-pro/blog-card', array(
        'editor_script'   => 'corporate-seo-pro-blog-card-block',
        'editor_style'    => 'corporate-seo-pro-blog-card-block-editor',
        'style'           => 'corporate-seo-pro-blog-card-block-style',
        'render_callback' => 'corporate_seo_pro_blog_card_block_render',
        'attributes'      => array(
            'postId' => array(
                'type'    => 'number',
                'default' => 0,
            ),
            'style' => array(
                'type'    => 'string',
                'default' => 'default',
            ),
            'showExcerpt' => array(
                'type'    => 'boolean',
                'default' => true,
            ),
            'showThumbnail' => array(
                'type'    => 'boolean',
                'default' => true,
            ),
            'showCategory' => array(
                'type'    => 'boolean',
                'default' => true,
            ),
            'showDate' => array(
                'type'    => 'boolean',
                'default' => true,
            ),
        ),
    ) );
}
add_action( 'init', 'corporate_seo_pro_register_blog_card_block' );

/**
 * Render callback for blog card block
 *
 * @param array $attributes Block attributes.
 * @return string Rendered HTML.
 */
function corporate_seo_pro_blog_card_block_render( $attributes ) {
    $post_id = isset( $attributes['postId'] ) ? intval( $attributes['postId'] ) : 0;

    if ( ! $post_id ) {
        return '';
    }

    $post = get_post( $post_id );

    if ( ! $post || 'publish' !== $post->post_status ) {
        return corporate_seo_pro_blog_card_error_message();
    }

    $atts = array(
        'id'             => $post_id,
        'url'            => '',
        'style'          => isset( $attributes['style'] ) ? $attributes['style'] : 'default',
        'show_excerpt'   => isset( $attributes['showExcerpt'] ) ? ( $attributes['showExcerpt'] ? 'true' : 'false' ) : 'true',
        'show_thumbnail' => isset( $attributes['showThumbnail'] ) ? ( $attributes['showThumbnail'] ? 'true' : 'false' ) : 'true',
        'show_category'  => isset( $attributes['showCategory'] ) ? ( $attributes['showCategory'] ? 'true' : 'false' ) : 'true',
        'show_date'      => isset( $attributes['showDate'] ) ? ( $attributes['showDate'] ? 'true' : 'false' ) : 'true',
    );

    return corporate_seo_pro_render_blog_card( $post, $atts );
}
