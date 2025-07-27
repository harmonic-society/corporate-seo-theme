<?php
/**
 * Corporate SEO Pro Theme Functions
 *
 * @package Corporate_SEO_Pro
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Define theme constants
 */
define( 'CORPORATE_SEO_PRO_VERSION', wp_get_theme()->get( 'Version' ) );
define( 'CORPORATE_SEO_PRO_DIR', get_template_directory() );
define( 'CORPORATE_SEO_PRO_URI', get_template_directory_uri() );

/**
 * Load theme includes via autoloader
 * 
 * This autoloader manages all theme functionality includes
 * making the functions.php file cleaner and more maintainable
 */
require_once CORPORATE_SEO_PRO_DIR . '/inc/autoloader.php';

/**
 * Theme initialization
 * 
 * All theme functionality is now modularized into separate files:
 * 
 * - /inc/theme-setup.php       : Basic theme setup and support
 * - /inc/navigation.php        : Menu registration and management
 * - /inc/assets.php           : Scripts and styles enqueuing
 * - /inc/widgets.php          : Widget areas registration
 * - /inc/post-types.php       : Custom post types and taxonomies
 * - /inc/template-functions.php: Template helper functions
 * - /inc/filters.php          : WordPress filters and modifications
 * - /inc/customizer.php       : Theme customizer settings
 * - /inc/seo-functions.php    : SEO related functionality
 * - /inc/performance.php      : Performance optimizations
 * - /inc/structured-data.php  : Schema.org structured data
 * - /inc/class-nav-walker.php : Custom navigation walker
 * - /inc/class-mobile-menu-walker.php : Mobile menu walker
 */

/**
 * Custom functions that don't fit into other categories
 * can be added below this comment
 */

// Add any theme-specific custom functions here if needed

// Check if ACF is active and show notice if not
add_action('admin_notices', 'corporate_seo_pro_acf_notice');
function corporate_seo_pro_acf_notice() {
    if ( ! function_exists('get_field') && current_user_can('manage_options') ) {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p><?php _e('Corporate SEO Pro テーマは Advanced Custom Fields Pro プラグインが必要です。プラグインをインストール・有効化してください。', 'corporate-seo-pro'); ?></p>
        </div>
        <?php
    }
}