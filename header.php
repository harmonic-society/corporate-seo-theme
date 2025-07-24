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
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'コンテンツへスキップ', 'corporate-seo-pro' ); ?></a>

    <header id="masthead" class="site-header">
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
        <div class="mobile-menu">
            <div class="mobile-menu-inner">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'mobile',
                    'menu_id'        => 'mobile-menu',
                    'container'      => false,
                    'menu_class'     => 'mobile-menu-list',
                    'fallback_cb'    => function() {
                        wp_nav_menu( array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'mobile-menu-fallback',
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
                
                <!-- モバイルCTA -->
                <div class="mobile-menu-cta">
                    <a href="<?php echo esc_url( $contact_url ); ?>" class="mobile-cta-button">
                        <span class="cta-icon"><i class="fas fa-envelope"></i></span>
                        <span class="cta-text"><?php esc_html_e( 'お問い合わせ', 'corporate-seo-pro' ); ?></span>
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
        <div class="mobile-menu-overlay"></div>
    </header>

    <div id="content" class="site-content">