<?php
/**
 * Blog Card Shortcode
 * 記事本文内でリッチなブログカードを表示するショートコード
 *
 * 使用方法:
 * [blog_card id="123"]
 * [blog_card url="https://example.com/post-slug/"]
 * [blog_card id="123" style="compact"]
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

    $post = corporate_seo_pro_get_blog_card_post( $atts );

    if ( ! $post ) {
        return corporate_seo_pro_blog_card_error_message();
    }

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
 * Note: Uses <span> instead of <div> to prevent wpautop from breaking
 * the HTML structure inside <a> tags.
 *
 * @param WP_Post $post Post object.
 * @param array   $atts Shortcode attributes.
 * @return string HTML output.
 */
function corporate_seo_pro_render_blog_card( $post, $atts ) {
    $show_excerpt   = filter_var( $atts['show_excerpt'], FILTER_VALIDATE_BOOLEAN );
    $show_thumbnail = filter_var( $atts['show_thumbnail'], FILTER_VALIDATE_BOOLEAN );
    $show_category  = filter_var( $atts['show_category'], FILTER_VALIDATE_BOOLEAN );
    $show_date      = filter_var( $atts['show_date'], FILTER_VALIDATE_BOOLEAN );

    $valid_styles = array( 'default', 'compact', 'full' );
    $style        = in_array( $atts['style'], $valid_styles, true ) ? $atts['style'] : 'default';

    $permalink  = get_permalink( $post );
    $title      = get_the_title( $post );
    $date       = get_the_date( '', $post );
    $datetime   = get_the_date( 'c', $post );
    $categories = get_the_category( $post->ID );

    $excerpt = '';
    if ( $show_excerpt && 'compact' !== $style ) {
        $excerpt = $post->post_excerpt
            ? wp_trim_words( strip_tags( $post->post_excerpt ), 40, '...' )
            : wp_trim_words( strip_tags( $post->post_content ), 40, '...' );
    }

    // Build HTML as string to prevent wpautop issues
    $html = '<a href="' . esc_url( $permalink ) . '" class="blog-card-shortcode blog-card-shortcode--' . esc_attr( $style ) . '">';

    if ( $show_thumbnail ) {
        $html .= '<span class="blog-card-thumbnail">';
        if ( has_post_thumbnail( $post->ID ) ) {
            $html .= get_the_post_thumbnail( $post->ID, 'medium', array(
                'loading' => 'lazy',
                'alt'     => esc_attr( $title ),
            ) );
        } else {
            $html .= '<span class="blog-card-thumbnail--placeholder"><i class="fas fa-file-alt"></i></span>';
        }
        $html .= '</span>';
    }

    $html .= '<span class="blog-card-content">';

    if ( ( $show_category && ! empty( $categories ) ) || ( $show_date && 'compact' !== $style ) ) {
        $html .= '<span class="blog-card-meta">';
        if ( $show_category && ! empty( $categories ) ) {
            $html .= '<span class="blog-card-category">' . esc_html( $categories[0]->name ) . '</span>';
        }
        if ( $show_date && 'compact' !== $style ) {
            $html .= '<time class="blog-card-date" datetime="' . esc_attr( $datetime ) . '">' . esc_html( $date ) . '</time>';
        }
        $html .= '</span>';
    }

    $html .= '<span class="blog-card-title">' . esc_html( $title ) . '</span>';

    if ( $show_excerpt && ! empty( $excerpt ) && 'compact' !== $style ) {
        $html .= '<span class="blog-card-excerpt">' . esc_html( $excerpt ) . '</span>';
    }

    $html .= '</span></a>';

    return $html;
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
 * Auto-convert internal URLs to blog cards
 *
 * @param string $content Post content.
 * @return string Modified content.
 */
function corporate_seo_pro_auto_blog_card( $content ) {
    if ( ! is_singular( array( 'post', 'page' ) ) || ! in_the_loop() || ! is_main_query() ) {
        return $content;
    }

    if ( ! apply_filters( 'corporate_seo_pro_enable_auto_blog_card', true ) ) {
        return $content;
    }

    if ( strpos( $content, 'blog-card-shortcode' ) !== false ) {
        return $content;
    }

    $site_host = wp_parse_url( home_url(), PHP_URL_HOST );

    $convert_url_to_card = function( $url ) use ( $site_host ) {
        $url_host = wp_parse_url( $url, PHP_URL_HOST );

        if ( $url_host !== $site_host ) {
            return null;
        }

        $post_id = url_to_postid( $url );
        if ( ! $post_id || $post_id === get_the_ID() ) {
            return null;
        }

        $post = get_post( $post_id );
        if ( ! $post || 'publish' !== $post->post_status ) {
            return null;
        }

        return corporate_seo_pro_render_blog_card( $post, array(
            'id'             => $post_id,
            'url'            => '',
            'style'          => 'default',
            'show_excerpt'   => 'true',
            'show_thumbnail' => 'true',
            'show_category'  => 'true',
            'show_date'      => 'true',
        ) );
    };

    // Pattern 1: Standalone URL in paragraph
    $content = preg_replace_callback(
        '#<p>\s*(https?://[^\s<>"\']+)\s*</p>#i',
        function( $matches ) use ( $convert_url_to_card ) {
            $card = $convert_url_to_card( $matches[1] );
            return $card ?: $matches[0];
        },
        $content
    );

    // Pattern 2: Auto-linked URL in paragraph
    $content = preg_replace_callback(
        '#<p>\s*<a\s+href=["\']?(https?://[^"\'>\s]+)["\']?[^>]*>https?://[^<]+</a>\s*</p>#i',
        function( $matches ) use ( $convert_url_to_card ) {
            $card = $convert_url_to_card( $matches[1] );
            return $card ?: $matches[0];
        },
        $content
    );

    return $content;
}
add_filter( 'the_content', 'corporate_seo_pro_auto_blog_card', 15 );

/**
 * Register Gutenberg block for blog card
 */
function corporate_seo_pro_register_blog_card_block() {
    if ( ! function_exists( 'register_block_type' ) ) {
        return;
    }

    wp_register_script(
        'corporate-seo-pro-blog-card-block',
        get_template_directory_uri() . '/assets/js/admin/blog-card-block.js',
        array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'wp-api-fetch' ),
        wp_get_theme()->get( 'Version' ),
        true
    );

    wp_register_style(
        'corporate-seo-pro-blog-card-block-editor',
        get_template_directory_uri() . '/assets/css/admin/blog-card-block.css',
        array( 'wp-edit-blocks' ),
        wp_get_theme()->get( 'Version' )
    );

    wp_register_style(
        'corporate-seo-pro-blog-card-block-style',
        get_template_directory_uri() . '/assets/css/components/blog-card.css',
        array(),
        wp_get_theme()->get( 'Version' )
    );

    register_block_type( 'corporate-seo-pro/blog-card', array(
        'editor_script'   => 'corporate-seo-pro-blog-card-block',
        'editor_style'    => 'corporate-seo-pro-blog-card-block-editor',
        'style'           => 'corporate-seo-pro-blog-card-block-style',
        'render_callback' => 'corporate_seo_pro_blog_card_block_render',
        'attributes'      => array(
            'postId'        => array( 'type' => 'number', 'default' => 0 ),
            'style'         => array( 'type' => 'string', 'default' => 'default' ),
            'showExcerpt'   => array( 'type' => 'boolean', 'default' => true ),
            'showThumbnail' => array( 'type' => 'boolean', 'default' => true ),
            'showCategory'  => array( 'type' => 'boolean', 'default' => true ),
            'showDate'      => array( 'type' => 'boolean', 'default' => true ),
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

    return corporate_seo_pro_render_blog_card( $post, array(
        'id'             => $post_id,
        'url'            => '',
        'style'          => $attributes['style'] ?? 'default',
        'show_excerpt'   => ( $attributes['showExcerpt'] ?? true ) ? 'true' : 'false',
        'show_thumbnail' => ( $attributes['showThumbnail'] ?? true ) ? 'true' : 'false',
        'show_category'  => ( $attributes['showCategory'] ?? true ) ? 'true' : 'false',
        'show_date'      => ( $attributes['showDate'] ?? true ) ? 'true' : 'false',
    ) );
}
