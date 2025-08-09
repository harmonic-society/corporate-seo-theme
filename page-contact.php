<?php
/**
 * Template Name: Contact
 * お問い合わせページテンプレート
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main contact-page">
    
    <!-- LINE CTAヒーローセクション -->
    <section class="contact-hero-line">
        <?php get_template_part( 'template-parts/line-cta' ); ?>
    </section>

    <!-- コンタクトメインセクション -->
    <section class="contact-main">
        <div class="container">
            <!-- ワンカラムフォーム -->
            <div class="contact-form-single">
                <div class="form-header">
                    <h2 class="form-title">お問い合わせフォーム</h2>
                    <p class="form-subtitle">必要事項をご記入の上、送信してください</p>
                </div>
                
                <?php 
                // カスタムコンタクトフォームを表示
                echo do_shortcode('[custom_contact_form]');
                ?>
            </div>
        </div>
    </section>
    
</main>

<?php get_footer();