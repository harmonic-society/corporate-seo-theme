<?php
/**
 * Sticky Header Inline CSS
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add inline CSS for sticky header with highest priority
 */
function corporate_seo_pro_sticky_header_inline_css() {
    ?>
    <style id="sticky-header-override">
        /* Force sticky header on all devices with maximum specificity */
        html body #page .site-header,
        html body .site-header,
        html body #masthead,
        .site-header {
            position: sticky !important;
            position: -webkit-sticky !important;
            top: 0 !important;
            z-index: 1000 !important;
            left: 0 !important;
            right: 0 !important;
        }
        
        /* Admin bar adjustment */
        html body.admin-bar .site-header {
            top: 32px !important;
        }
        
        @media (max-width: 782px) {
            html body.admin-bar .site-header {
                top: 46px !important;
            }
        }
        
        /* Prevent any fixed positioning */
        body #page .site-header[style*="position: fixed"] {
            position: sticky !important;
            position: -webkit-sticky !important;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'corporate_seo_pro_sticky_header_inline_css', 999 );