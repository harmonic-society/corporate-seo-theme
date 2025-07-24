<?php
/**
 * フッターテンプレート
 *
 * @package Corporate_SEO_Pro
 */
?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="footer-main">
            <div class="container">
                <div class="footer-grid">
                    <!-- 会社情報 -->
                    <div class="footer-widget footer-info">
                        <h3 class="footer-title"><?php bloginfo( 'name' ); ?></h3>
                        <p class="footer-description">
                            <?php echo get_theme_mod( 'footer_description', get_bloginfo( 'description' ) ); ?>
                        </p>
                        
                        <!-- ソーシャルリンク -->
                        <?php if ( has_nav_menu( 'social' ) ) : ?>
                            <div class="footer-social">
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

                    <!-- フッターメニュー1 -->
                    <div class="footer-widget">
                        <h3 class="footer-title"><?php esc_html_e( 'サービス', 'corporate-seo-pro' ); ?></h3>
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer-1',
                            'container'      => false,
                            'menu_class'     => 'footer-menu',
                            'depth'          => 1,
                            'fallback_cb'    => false,
                        ) );
                        ?>
                    </div>

                    <!-- フッターメニュー2 -->
                    <div class="footer-widget">
                        <h3 class="footer-title"><?php esc_html_e( '会社情報', 'corporate-seo-pro' ); ?></h3>
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer-2',
                            'container'      => false,
                            'menu_class'     => 'footer-menu',
                            'depth'          => 1,
                            'fallback_cb'    => false,
                        ) );
                        ?>
                    </div>

                    <!-- コンタクト情報 -->
                    <div class="footer-widget footer-contact-widget">
                        <h3 class="footer-title"><?php esc_html_e( 'お問い合わせ', 'corporate-seo-pro' ); ?></h3>
                        <div class="footer-contact">
                            <?php if ( get_theme_mod( 'footer_phone' ) ) : ?>
                                <a href="tel:<?php echo esc_attr( get_theme_mod( 'footer_phone' ) ); ?>" class="footer-phone">
                                    <i class="fas fa-phone"></i>
                                    <?php echo esc_html( get_theme_mod( 'footer_phone' ) ); ?>
                                </a>
                            <?php endif; ?>
                            
                            <?php if ( get_theme_mod( 'footer_email' ) ) : ?>
                                <a href="mailto:<?php echo esc_attr( get_theme_mod( 'footer_email' ) ); ?>" class="footer-email">
                                    <i class="fas fa-envelope"></i>
                                    <?php echo esc_html( get_theme_mod( 'footer_email' ) ); ?>
                                </a>
                            <?php endif; ?>
                            
                            <?php
                            $contact_page = get_page_by_path( 'contact' );
                            $contact_url = $contact_page ? get_permalink( $contact_page ) : '#contact';
                            ?>
                            <a href="<?php echo esc_url( $contact_url ); ?>" class="footer-cta-button">
                                <?php esc_html_e( 'お問い合わせフォーム', 'corporate-seo-pro' ); ?>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 装飾要素 -->
            <div class="footer-decoration">
                <div class="decoration-circle decoration-circle-1"></div>
                <div class="decoration-circle decoration-circle-2"></div>
            </div>
        </div>

        <!-- フッター下部 -->
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-content">
                    <p class="copyright">
                        &copy; <?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?>. 
                        <?php esc_html_e( 'All rights reserved.', 'corporate-seo-pro' ); ?>
                    </p>
                    
                    <div class="footer-bottom-links">
                        <a href="<?php echo esc_url( get_privacy_policy_url() ); ?>">
                            <?php esc_html_e( 'プライバシーポリシー', 'corporate-seo-pro' ); ?>
                        </a>
                        <span class="separator">|</span>
                        <a href="<?php echo esc_url( home_url( '/terms' ) ); ?>">
                            <?php esc_html_e( '利用規約', 'corporate-seo-pro' ); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- ページトップへ戻るボタン -->
    <button id="scroll-to-top" class="scroll-to-top" aria-label="<?php esc_attr_e( 'ページトップへ戻る', 'corporate-seo-pro' ); ?>">
        <i class="fas fa-chevron-up"></i>
    </button>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>