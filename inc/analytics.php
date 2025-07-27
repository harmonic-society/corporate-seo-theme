<?php
/**
 * Google Analytics (GA4) Integration
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add GA4 tracking code to head
 */
function corporate_seo_pro_ga4_tracking_code() {
    // Get GA4 measurement ID from customizer
    $ga4_id = get_theme_mod( 'ga4_measurement_id', '' );
    
    // Only output if ID is set and not in admin area
    if ( ! empty( $ga4_id ) && ! is_admin() && ! is_customize_preview() ) {
        ?>
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $ga4_id ); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?php echo esc_js( $ga4_id ); ?>');
        </script>
        <?php
    }
}
add_action( 'wp_head', 'corporate_seo_pro_ga4_tracking_code', 1 );

/**
 * Add GA4 settings to customizer
 */
function corporate_seo_pro_ga4_customizer( $wp_customize ) {
    // GA4 Section
    $wp_customize->add_section( 'ga4_settings', array(
        'title'    => __( 'Google Analytics 4', 'corporate-seo-pro' ),
        'priority' => 160,
        'description' => __( 'Google Analytics 4の測定IDを設定します。', 'corporate-seo-pro' ),
    ) );
    
    // GA4 Measurement ID
    $wp_customize->add_setting( 'ga4_measurement_id', array(
        'default'           => 'G-DWENPTLE20',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'ga4_measurement_id', array(
        'label'       => __( 'GA4測定ID', 'corporate-seo-pro' ),
        'description' => __( 'Google Analytics 4の測定ID（G-XXXXXXXXXX形式）を入力してください。', 'corporate-seo-pro' ),
        'section'     => 'ga4_settings',
        'type'        => 'text',
        'placeholder' => 'G-XXXXXXXXXX',
    ) );
    
    // Enable/Disable tracking for logged in users
    $wp_customize->add_setting( 'ga4_disable_logged_in', array(
        'default'           => true,
        'sanitize_callback' => 'corporate_seo_pro_sanitize_checkbox',
        'transport'         => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'ga4_disable_logged_in', array(
        'label'       => __( 'ログインユーザーのトラッキングを無効化', 'corporate-seo-pro' ),
        'description' => __( '管理者やログインユーザーのアクセスをトラッキングから除外します。', 'corporate-seo-pro' ),
        'section'     => 'ga4_settings',
        'type'        => 'checkbox',
    ) );
}
add_action( 'customize_register', 'corporate_seo_pro_ga4_customizer' );

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
    $disable_logged_in = get_theme_mod( 'ga4_disable_logged_in', true );
    
    if ( $disable_logged_in && is_user_logged_in() ) {
        return false;
    }
    
    return true;
}

/**
 * Modify GA4 output based on user status
 */
function corporate_seo_pro_conditional_ga4() {
    if ( ! corporate_seo_pro_should_track() ) {
        remove_action( 'wp_head', 'corporate_seo_pro_ga4_tracking_code', 1 );
    }
}
add_action( 'init', 'corporate_seo_pro_conditional_ga4' );

/**
 * Add custom events tracking (optional)
 */
function corporate_seo_pro_ga4_custom_events() {
    $ga4_id = get_theme_mod( 'ga4_measurement_id', '' );
    
    if ( ! empty( $ga4_id ) && ! is_admin() && corporate_seo_pro_should_track() ) {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Track contact form submissions
            const contactForms = document.querySelectorAll('.wpcf7-form');
            contactForms.forEach(function(form) {
                form.addEventListener('wpcf7mailsent', function(event) {
                    gtag('event', 'contact_form_submit', {
                        'event_category': 'engagement',
                        'event_label': 'Contact Form'
                    });
                });
            });
            
            // Track phone number clicks
            const phoneLinks = document.querySelectorAll('a[href^="tel:"]');
            phoneLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    gtag('event', 'phone_click', {
                        'event_category': 'engagement',
                        'event_label': link.getAttribute('href')
                    });
                });
            });
            
            // Track external link clicks
            const externalLinks = document.querySelectorAll('a[href^="http"]:not([href*="' + window.location.hostname + '"])');
            externalLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    gtag('event', 'external_link_click', {
                        'event_category': 'outbound',
                        'event_label': link.getAttribute('href')
                    });
                });
            });
        });
        </script>
        <?php
    }
}
add_action( 'wp_footer', 'corporate_seo_pro_ga4_custom_events' );