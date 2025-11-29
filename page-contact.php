<?php
/**
 * Template Name: Contact
 * お問い合わせページテンプレート
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main contact-page">

    <!-- コンタクトメインセクション -->
    <section class="contact-main">
        <div class="container">
            <!-- ワンカラムフォーム -->
            <div class="contact-form-single">
                <div class="form-header">
                    <h2 class="form-title">お問い合わせフォーム</h2>
                    <p class="form-subtitle">必要事項をご記入の上、送信してください</p>
                </div>

                <!-- オンライン予約CTA -->
                <div class="quick-booking-cta">
                    <div class="quick-booking-content">
                        <span class="quick-booking-badge">お急ぎの方</span>
                        <p class="quick-booking-text">オンラインでお打ち合わせをご予約いただけます</p>
                    </div>
                    <a href="https://calendar.app.google/prkDu7TEhWaSzDjN8" target="_blank" rel="noopener noreferrer" class="quick-booking-button">
                        <i class="fas fa-calendar-check"></i>
                        今すぐ予約する
                    </a>
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