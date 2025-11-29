<?php
/**
 * Template Name: Thanks
 * お問い合わせ完了ページテンプレート
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main thanks-page">
    
    <!-- サンクスヒーローセクション -->
    <section class="thanks-hero">
        <!-- 動的背景 -->
        <div class="thanks-bg-animation">
            <div class="bg-gradient"></div>
            <div class="confetti-wrapper">
                <div class="confetti"></div>
                <div class="confetti"></div>
                <div class="confetti"></div>
                <div class="confetti"></div>
                <div class="confetti"></div>
                <div class="confetti"></div>
                <div class="confetti"></div>
                <div class="confetti"></div>
                <div class="confetti"></div>
                <div class="confetti"></div>
            </div>
            <div class="success-ripple">
                <div class="ripple"></div>
                <div class="ripple"></div>
                <div class="ripple"></div>
            </div>
        </div>
        
        <div class="container">
            <div class="hero-content">
                <!-- 成功アイコン -->
                <div class="success-icon-wrapper">
                    <div class="success-icon">
                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
                            <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                        </svg>
                    </div>
                </div>
                
                <h1 class="thanks-title">
                    <span class="title-main">送信完了しました</span>
                    <span class="title-sub">Thank You for Your Inquiry</span>
                </h1>
                
                <div class="thanks-message">
                    <p>お問い合わせいただき、誠にありがとうございます。</p>
                    <p>内容を確認の上、<strong>24時間以内</strong>に担当者よりご連絡させていただきます。</p>
                </div>
                
                <!-- メール確認の通知 -->
                <div class="email-notice">
                    <i class="fas fa-envelope-open-text"></i>
                    <p>ご入力いただいたメールアドレスに確認メールを送信しました。<br>
                    メールが届かない場合は、迷惑メールフォルダをご確認ください。</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 次のステップセクション -->
    <section class="next-steps">
        <div class="container">
            <h2 class="section-title">今後の流れ</h2>
            
            <div class="timeline">
                <div class="timeline-item active">
                    <div class="timeline-marker">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="timeline-content">
                        <h3>お問い合わせ受付</h3>
                        <p>お問い合わせを受け付けました</p>
                        <span class="timeline-time">完了</span>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-marker">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="timeline-content">
                        <h3>担当者確認</h3>
                        <p>専門スタッフが内容を確認します</p>
                        <span class="timeline-time">1-3時間以内</span>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-marker">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div class="timeline-content">
                        <h3>ご連絡</h3>
                        <p>メールまたはお電話でご連絡します</p>
                        <span class="timeline-time">24時間以内</span>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-marker">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div class="timeline-content">
                        <h3>ご相談開始</h3>
                        <p>詳細なヒアリングを実施します</p>
                        <span class="timeline-time">日程調整後</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- その他のアクション -->
    <section class="other-actions">
        <div class="container">
            <div class="actions-grid">
                <!-- 緊急の場合 -->
                <div class="action-card urgent">
                    <div class="card-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3>お急ぎの場合</h3>
                    <p>オンラインでお打ち合わせをご予約いただけます</p>
                    <a href="https://calendar.app.google/prkDu7TEhWaSzDjN8" class="btn-booking" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-calendar-check"></i>
                        今すぐ予約する
                    </a>
                    <span class="booking-note">24時間予約受付中</span>
                </div>
                
                <!-- よくある質問 -->
                <div class="action-card faq">
                    <div class="card-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <h3>よくあるご質問</h3>
                    <p>お客様からよくいただく質問をまとめました</p>
                    <a href="<?php echo home_url('/faq/'); ?>" class="btn-secondary">
                        FAQを見る
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                
                <!-- サービス紹介 -->
                <div class="action-card services">
                    <div class="card-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h3>サービス一覧</h3>
                    <p>Harmonic Societyの提供サービスをご覧ください</p>
                    <a href="<?php echo get_post_type_archive_link('service'); ?>" class="btn-secondary">
                        サービスを見る
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- おすすめコンテンツ -->
    <section class="recommended-content">
        <div class="container">
            <h2 class="section-title">お役立ち情報</h2>
            
            <div class="content-grid">
                <?php
                // 最新のブログ記事を3件取得
                $recent_posts = get_posts(array(
                    'post_type' => 'post',
                    'posts_per_page' => 3,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
                
                if ($recent_posts) :
                    foreach ($recent_posts as $post) : setup_postdata($post);
                ?>
                    <article class="content-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="card-thumbnail">
                                <?php the_post_thumbnail('medium'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="card-content">
                            <div class="card-meta">
                                <time datetime="<?php echo get_the_date('c'); ?>">
                                    <?php echo get_the_date(); ?>
                                </time>
                                <?php 
                                $categories = get_the_category();
                                if (!empty($categories)) : ?>
                                    <span class="category">
                                        <?php echo esc_html($categories[0]->name); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <h3 class="card-title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            <p class="card-excerpt">
                                <?php echo wp_trim_words(get_the_excerpt(), 40); ?>
                            </p>
                            <a href="<?php the_permalink(); ?>" class="read-more">
                                続きを読む
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                    </article>
                <?php 
                    endforeach;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- ホームへ戻るボタン -->
    <div class="back-to-home">
        <a href="<?php echo home_url(); ?>" class="btn-primary btn-large">
            <i class="fas fa-home"></i>
            ホームへ戻る
        </a>
    </div>
    
</main>

<?php get_footer();