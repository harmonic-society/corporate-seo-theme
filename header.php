<?php
/**
 * ヘッダーテンプレート
 *
 * @package Corporate_SEO_Pro
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
    
    <!-- Force disable all submenus and fix text colors -->
    <style>
        .sub-menu,
        .primary-menu .sub-menu,
        .main-navigation .sub-menu,
        .menu .sub-menu,
        .nav-menu .sub-menu,
        ul.sub-menu,
        li .sub-menu,
        .menu-item .sub-menu {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            height: 0 !important;
            overflow: hidden !important;
            position: absolute !important;
            left: -99999px !important;
            top: -99999px !important;
            pointer-events: none !important;
            max-height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: none !important;
        }
        
        /* Force service feature list text to be dark */
        .service-features .feature-list li,
        .service-archive-item .feature-list li,
        .service-card .feature-list li,
        .feature-list li {
            color: #1f2937 !important;
        }
    </style>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'コンテンツへスキップ', 'corporate-seo-pro' ); ?></a>

    <header id="masthead" class="site-header" style="position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; width: 100% !important; z-index: 99999 !important; height: 80px !important; background-color: #ffffff !important; box-shadow: 0 2px 10px rgba(0, 134, 123, 0.1) !important;">
        <div class="header-container">
            <div class="site-branding">
                <?php if ( has_custom_logo() ) : ?>
                    <div class="site-logo">
                        <?php the_custom_logo(); ?>
                    </div>
                <?php else : ?>
                    <h1 class="site-title">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                            <?php bloginfo( 'name' ); ?>
                        </a>
                    </h1>
                    <?php
                    $description = get_bloginfo( 'description', 'display' );
                    if ( $description || is_customize_preview() ) :
                    ?>
                        <p class="site-description"><?php echo $description; ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <nav id="site-navigation" class="main-navigation">
                <button class="mobile-menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <span class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                    <span class="screen-reader-text"><?php esc_html_e( 'メニュー', 'corporate-seo-pro' ); ?></span>
                </button>

                <div class="nav-wrapper">
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'menu_class'     => 'nav-menu',
                        'fallback_cb'    => function() {
                            echo '<ul class="nav-menu">';
                            wp_list_pages( array(
                                'title_li' => '',
                                'depth'    => 1,
                            ) );
                            echo '</ul>';
                        },
                    ) );
                    ?>
                    
                    <div class="nav-cta">
                        <?php
                        $contact_page = get_page_by_path( 'contact' );
                        $contact_url = $contact_page ? get_permalink( $contact_page ) : '#contact';
                        ?>
                        <a href="<?php echo esc_url( $contact_url ); ?>" class="nav-cta-button">
                            <span class="cta-text"><?php esc_html_e( 'お問い合わせ', 'corporate-seo-pro' ); ?></span>
                            <span class="cta-icon"><i class="fas fa-arrow-right"></i></span>
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <!-- モバイルメニュー -->
        <div class="mobile-menu" aria-hidden="true" role="navigation">
            <!-- 閉じるボタン -->
            <button class="mobile-menu-close" aria-label="<?php esc_attr_e( 'メニューを閉じる', 'corporate-seo-pro' ); ?>">
                <span></span>
                <span></span>
            </button>
            
            <div class="mobile-menu-content">
                <nav class="mobile-menu-nav">
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'mobile',
                        'menu_id'        => 'mobile-navigation',
                        'container'      => false,
                        'menu_class'     => 'mobile-menu-list',
                        'fallback_cb'    => function() {
                            wp_nav_menu( array(
                                'theme_location' => 'primary',
                                'menu_id'        => 'mobile-navigation-fallback',
                                'container'      => false,
                                'menu_class'     => 'mobile-menu-list',
                                'fallback_cb'    => function() {
                                    echo '<ul class="mobile-menu-list">';
                                    wp_list_pages( array(
                                        'title_li' => '',
                                        'depth'    => 2,
                                    ) );
                                    echo '</ul>';
                                },
                            ) );
                        },
                    ) );
                    ?>
                </nav>
                
                <!-- モバイルメニューアクション -->
                <div class="mobile-menu-actions">
                    <a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-primary">
                        <span><?php esc_html_e( 'お問い合わせ', 'corporate-seo-pro' ); ?></span>
                    </a>
                </div>
                
                <!-- ソーシャルリンク（オプション） -->
                <?php if ( has_nav_menu( 'social' ) ) : ?>
                    <div class="mobile-menu-social">
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'social',
                            'container'      => false,
                            'menu_class'     => 'social-links',
                            'depth'          => 1,
                            'link_before'    => '<span class="screen-reader-text">',
                            'link_after'     => '</span>',
                        ) );
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- モバイルメニューオーバーレイ -->
        <div class="mobile-menu-overlay" aria-hidden="true"></div>
    </header>

    <!-- ヘッダー分のスペーサー -->
    <div class="header-spacer" style="height: 80px; width: 100%;"></div>
    <style>
        @media (max-width: 768px) {
            .site-header {
                height: 60px !important;
            }
            .header-spacer {
                height: 60px !important;
            }
        }
        @media (max-width: 782px) {
            .admin-bar .site-header {
                top: 46px !important;
            }
            .admin-bar .header-spacer {
                height: 106px !important;
            }
        }
        @media (min-width: 783px) {
            .admin-bar .site-header {
                top: 32px !important;
            }
            .admin-bar .header-spacer {
                height: 112px !important;
            }
        }
    </style>

    <div id="content" class="site-content">