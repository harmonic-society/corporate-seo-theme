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