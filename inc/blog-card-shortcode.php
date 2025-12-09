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
    ?>
    <a href="<?php echo esc_url( $permalink ); ?>" class="blog-card-shortcode blog-card-shortcode--<?php echo esc_attr( $style ); ?>">
        <?php if ( $show_thumbnail ) : ?>
            <div class="blog-card-thumbnail">
                <?php if ( has_post_thumbnail( $post->ID ) ) : ?>
                    <?php echo get_the_post_thumbnail( $post->ID, 'medium', array(
                        'loading' => 'lazy',
                        'alt'     => esc_attr( $title ),
                    ) ); ?>
                <?php else : ?>
                    <div class="blog-card-thumbnail--placeholder">
                        <i class="fas fa-file-alt"></i>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="blog-card-content">
            <?php if ( ( $show_category && ! empty( $categories ) ) || $show_date ) : ?>
                <div class="blog-card-meta">
                    <?php if ( $show_category && ! empty( $categories ) ) : ?>
                        <span class="blog-card-category"><?php echo esc_html( $categories[0]->name ); ?></span>
                    <?php endif; ?>
                    <?php if ( $show_date && 'compact' !== $style ) : ?>
                        <time class="blog-card-date" datetime="<?php echo esc_attr( $datetime ); ?>"><?php echo esc_html( $date ); ?></time>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <h4 class="blog-card-title"><?php echo esc_html( $title ); ?></h4>

            <?php if ( $show_excerpt && ! empty( $excerpt ) && 'compact' !== $style ) : ?>
                <p class="blog-card-excerpt"><?php echo esc_html( $excerpt ); ?></p>
            <?php endif; ?>
        </div>
    </a>
    <?php
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

    // Get site URL for matching.
    $site_url  = home_url();
    $site_host = wp_parse_url( $site_url, PHP_URL_HOST );

    // Pattern 1: Match standalone URLs on their own paragraph.
    // Matches: <p>https://example.com/post-slug/</p>
    $pattern_plain = '#<p>\s*(https?://[^\s<>"\']+)\s*</p>#i';

    // Pattern 2: Match URLs that WordPress auto-linked (make_clickable).
    // Matches: <p><a href="...">URL</a></p>
    $pattern_linked = '#<p>\s*<a\s+href=["\']?(https?://[^"\'>\s]+)["\']?[^>]*>\s*https?://[^<]+</a>\s*</p>#i';

    // Pattern 3: Match figure/blockquote wrapped links (Gutenberg embed fallback).
    // Matches: <figure...><a href="...">URL</a></figure> or <blockquote><a...
    $pattern_figure = '#<(?:figure|blockquote)[^>]*>\s*<a\s+href=["\']?(https?://[^"\'>\s]+)["\']?[^>]*>[^<]*</a>\s*</(?:figure|blockquote)>#i';

    // Process each pattern.
    $patterns = array( $pattern_plain, $pattern_linked, $pattern_figure );

    foreach ( $patterns as $pattern ) {
        $content = preg_replace_callback(
            $pattern,
            function ( $matches ) use ( $site_host ) {
                $url = $matches[1];

                // Parse URL to check if it's an internal link.
                $url_host = wp_parse_url( $url, PHP_URL_HOST );

                // Only convert internal URLs.
                if ( $url_host !== $site_host ) {
                    return $matches[0];
                }

                // Try to get post ID from URL.
                $post_id = url_to_postid( $url );

                if ( ! $post_id ) {
                    return $matches[0];
                }

                $post = get_post( $post_id );

                // Check if post exists and is published.
                if ( ! $post || 'publish' !== $post->post_status ) {
                    return $matches[0];
                }

                // Prevent self-referencing.
                if ( $post_id === get_the_ID() ) {
                    return $matches[0];
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
            },
            $content
        );
    }

    return $content;
}
// Priority 25: After wpautop (10), make_clickable (9), but before most theme filters.
add_filter( 'the_content', 'corporate_seo_pro_auto_blog_card', 25 );
