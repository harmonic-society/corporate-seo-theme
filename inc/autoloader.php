<?php
/**
 * Autoloader for theme includes
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Load all required theme files
 */
function corporate_seo_pro_load_includes() {
    $theme_dir = get_template_directory();
    
    // Core functionality files
    $includes = array(
        '/inc/theme-setup.php',        // Theme setup and support
        '/inc/navigation.php',         // Navigation and menus
        '/inc/assets.php',             // Scripts and styles
        '/inc/widgets.php',            // Widget areas
        '/inc/post-types.php',         // Custom post types
        '/inc/template-functions.php', // Template functions
        '/inc/filters.php',            // WordPress filters
        '/inc/class-nav-walker.php',   // Custom navigation walker
        '/inc/class-mobile-menu-walker.php', // Mobile menu walker
        '/inc/customizer.php',         // Theme customizer
        '/inc/seo-functions.php',      // SEO functionality
        '/inc/performance.php',        // Performance optimization
        '/inc/structured-data.php',    // Structured data
    );
    
    // Load each file
    foreach ( $includes as $file ) {
        $filepath = $theme_dir . $file;
        if ( file_exists( $filepath ) ) {
            require_once $filepath;
        } else {
            // Log error in development
            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                error_log( 'Corporate SEO Pro: Missing include file - ' . $file );
            }
        }
    }
}

// Initialize the autoloader
corporate_seo_pro_load_includes();