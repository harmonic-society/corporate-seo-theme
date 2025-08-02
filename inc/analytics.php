<?php
/**
 * Google Tag Manager Integration
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add Google Tag Manager code to head
 */
function corporate_seo_pro_gtm_head_code() {
    // Only output if not in admin area and tracking is enabled
    if ( ! is_admin() && ! is_customize_preview() && corporate_seo_pro_should_track() ) {
        ?>
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-TC49SZ59');</script>
        <!-- End Google Tag Manager -->
        <?php
    }
}
add_action( 'wp_head', 'corporate_seo_pro_gtm_head_code', 1 );

/**
 * Add Google Tag Manager noscript code after opening body tag
 */
function corporate_seo_pro_gtm_body_code() {
    // Only output if not in admin area and tracking is enabled
    if ( ! is_admin() && ! is_customize_preview() && corporate_seo_pro_should_track() ) {
        ?>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TC49SZ59"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        <?php
    }
}
add_action( 'wp_body_open', 'corporate_seo_pro_gtm_body_code', 1 );

/**
 * Add GTM settings to customizer
 */
function corporate_seo_pro_gtm_customizer( $wp_customize ) {
    // GTM Section
    $wp_customize->add_section( 'gtm_settings', array(
        'title'    => __( 'Google Tag Manager', 'corporate-seo-pro' ),
        'priority' => 160,
        'description' => __( 'Google Tag Managerの設定', 'corporate-seo-pro' ),
    ) );
    
    // Enable/Disable tracking for logged in users
    $wp_customize->add_setting( 'gtm_disable_logged_in', array(
        'default'           => true,
        'sanitize_callback' => 'corporate_seo_pro_sanitize_checkbox',
        'transport'         => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'gtm_disable_logged_in', array(
        'label'       => __( 'ログインユーザーのトラッキングを無効化', 'corporate-seo-pro' ),
        'description' => __( '管理者やログインユーザーのアクセスをトラッキングから除外します。', 'corporate-seo-pro' ),
        'section'     => 'gtm_settings',
        'type'        => 'checkbox',
    ) );
}
add_action( 'customize_register', 'corporate_seo_pro_gtm_customizer' );

/**
 * Sanitize checkbox
 */
function corporate_seo_pro_sanitize_checkbox( $input ) {
    return ( isset( $input ) && true == $input ) ? true : false;
}

/**
 * Check if tracking should be disabled for current user
 */
function corporate_seo_pro_should_track() {
    // Check if tracking is disabled for logged in users
    $disable_logged_in = get_theme_mod( 'gtm_disable_logged_in', true );
    
    if ( $disable_logged_in && is_user_logged_in() ) {
        return false;
    }
    
    return true;
}


