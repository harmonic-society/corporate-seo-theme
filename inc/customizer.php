<?php
/**
 * カスタマイザー設定
 *
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * カスタマイザーの登録
 */
function corporate_seo_pro_customize_register( $wp_customize ) {
    
    /**
     * ヒーローセクション
     */
    $wp_customize->add_section( 'corporate_seo_hero', array(
        'title'    => __( 'ヒーローセクション設定', 'corporate-seo-pro' ),
        'priority' => 30,
        'description' => __( 'トップページのヒーローセクション（メインビジュアル）の設定を行います。', 'corporate-seo-pro' ),
    ) );
    
    // ヒーローセクションの表示
    $wp_customize->add_setting( 'show_hero_section', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    
    $wp_customize->add_control( 'show_hero_section', array(
        'label'   => __( 'ヒーローセクションを表示', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_hero',
        'type'    => 'checkbox',
    ) );
    
    // モダンヒーロー背景画像
    $wp_customize->add_setting( 'hero_modern_bg_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'hero_modern_bg_image', array(
        'label'       => __( 'ヒーロー背景画像', 'corporate-seo-pro' ),
        'description' => __( 'ヒーローセクションの背景画像をアップロード（推奨: 1920x1080px以上）', 'corporate-seo-pro' ),
        'section'     => 'corporate_seo_hero',
        'settings'    => 'hero_modern_bg_image',
        'priority'    => 24,
    ) ) );
    
    // 背景オーバーレイの透明度
    $wp_customize->add_setting( 'hero_bg_overlay_opacity', array(
        'default'           => '0.8',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'hero_bg_overlay_opacity', array(
        'label'       => __( '背景オーバーレイの透明度', 'corporate-seo-pro' ),
        'description' => __( '0（透明）から1（不透明）の値を入力', 'corporate-seo-pro' ),
        'section'     => 'corporate_seo_hero',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 1,
            'step' => 0.1,
        ),
        'priority'    => 24.5,
    ) );
    
    // モダンヒーロー画像（右側）
    $wp_customize->add_setting( 'hero_modern_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'hero_modern_image', array(
        'label'       => __( 'モダンヒーロー画像（右側）', 'corporate-seo-pro' ),
        'description' => __( 'ヒーローセクションの右側に表示される画像をアップロード（推奨: 800x600px以上）', 'corporate-seo-pro' ),
        'section'     => 'corporate_seo_hero',
        'settings'    => 'hero_modern_image',
        'priority'    => 25,
    ) ) );
    
    /**
     * 会社情報
     */
    $wp_customize->add_section( 'corporate_seo_company', array(
        'title'    => __( '会社情報', 'corporate-seo-pro' ),
        'priority' => 40,
    ) );

    // 代表プロフィール写真（Aboutページ用）
    $wp_customize->add_setting( 'ceo_profile_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'ceo_profile_image', array(
        'label'       => __( '代表プロフィール写真', 'corporate-seo-pro' ),
        'description' => __( 'Aboutページの代表メッセージセクションに表示される写真（推奨: 600x600px以上の正方形）', 'corporate-seo-pro' ),
        'section'     => 'corporate_seo_company',
        'settings'    => 'ceo_profile_image',
        'priority'    => 5,
    ) ) );

    // 会社名
    $wp_customize->add_setting( 'company_name', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'company_name', array(
        'label'   => __( '会社名', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_company',
        'type'    => 'text',
    ) );
    
    // 住所
    $wp_customize->add_setting( 'company_address', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    
    $wp_customize->add_control( 'company_address', array(
        'label'   => __( '住所', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_company',
        'type'    => 'textarea',
    ) );
    
    // 電話番号
    $wp_customize->add_setting( 'company_phone', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'company_phone', array(
        'label'   => __( '電話番号', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_company',
        'type'    => 'tel',
    ) );
    
    // メールアドレス
    $wp_customize->add_setting( 'company_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
    ) );
    
    $wp_customize->add_control( 'company_email', array(
        'label'   => __( 'メールアドレス', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_company',
        'type'    => 'email',
    ) );
    
    /**
     * ソーシャルメディア
     */
    $wp_customize->add_section( 'corporate_seo_social', array(
        'title'    => __( 'ソーシャルメディア', 'corporate-seo-pro' ),
        'priority' => 50,
    ) );
    
    // Facebook
    $wp_customize->add_setting( 'facebook_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    
    $wp_customize->add_control( 'facebook_url', array(
        'label'   => __( 'Facebook URL', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_social',
        'type'    => 'url',
    ) );
    
    // Twitter
    $wp_customize->add_setting( 'twitter_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    
    $wp_customize->add_control( 'twitter_url', array(
        'label'   => __( 'Twitter URL', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_social',
        'type'    => 'url',
    ) );
    
    $wp_customize->add_setting( 'twitter_username', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'twitter_username', array(
        'label'       => __( 'Twitterユーザー名（@なし）', 'corporate-seo-pro' ),
        'section'     => 'corporate_seo_social',
        'type'        => 'text',
        'description' => __( 'Twitter Cardに使用されます', 'corporate-seo-pro' ),
    ) );
    
    // LinkedIn
    $wp_customize->add_setting( 'linkedin_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    
    $wp_customize->add_control( 'linkedin_url', array(
        'label'   => __( 'LinkedIn URL', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_social',
        'type'    => 'url',
    ) );
    
    // YouTube
    $wp_customize->add_setting( 'youtube_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    
    $wp_customize->add_control( 'youtube_url', array(
        'label'   => __( 'YouTube URL', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_social',
        'type'    => 'url',
    ) );
    
    /**
     * フロントページセクション
     */
    $wp_customize->add_section( 'corporate_seo_front_sections', array(
        'title'    => __( 'フロントページセクション', 'corporate-seo-pro' ),
        'priority' => 60,
    ) );
    
    // サービスセクション
    $wp_customize->add_setting( 'show_services_section', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    
    $wp_customize->add_control( 'show_services_section', array(
        'label'   => __( 'サービスセクションを表示', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'checkbox',
    ) );
    
    $wp_customize->add_setting( 'services_title', array(
        'default'           => __( 'サービス', 'corporate-seo-pro' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'services_title', array(
        'label'   => __( 'サービスセクションタイトル', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'services_description', array(
        'default'           => __( '一社一社に合わせた、カスタム型の支援を行っています。', 'corporate-seo-pro' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'services_description', array(
        'label'   => __( 'サービスセクション説明文', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'text',
    ) );

    // サービス選択設定（3つ）
    for ( $i = 1; $i <= 3; $i++ ) {
        $wp_customize->add_setting( 'featured_service_' . $i, array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ) );

        // サービス投稿のリストを取得
        $services = get_posts( array(
            'post_type'      => 'service',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ) );

        $service_choices = array( '' => __( '選択してください', 'corporate-seo-pro' ) );
        foreach ( $services as $service ) {
            $service_choices[ $service->post_name ] = $service->post_title;
        }

        $wp_customize->add_control( 'featured_service_' . $i, array(
            'label'       => sprintf( __( 'TOPページ表示サービス %d', 'corporate-seo-pro' ), $i ),
            'section'     => 'corporate_seo_front_sections',
            'type'        => 'select',
            'choices'     => $service_choices,
            'description' => $i === 1 ? __( 'TOPページに表示する3つのサービスを順番に選択してください', 'corporate-seo-pro' ) : '',
        ) );
    }

    // アバウトセクション
    $wp_customize->add_setting( 'show_about_section', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    
    $wp_customize->add_control( 'show_about_section', array(
        'label'   => __( 'アバウトセクションを表示', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'checkbox',
    ) );
    
    $wp_customize->add_setting( 'about_title', array(
        'default'           => __( '私たちについて', 'corporate-seo-pro' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'about_title', array(
        'label'   => __( 'アバウトセクションタイトル', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'text',
    ) );
    
    $wp_customize->add_setting( 'about_content', array(
        'default'           => __( 'Harmonic Society株式会社は、「社会の調和」という理念を掲げ、人と技術が無理なく共存できる未来を目指しています。

テクノロジーが急速に発展する一方で、「変化についていけない」「デジタルが苦手」という声を地域の中小企業の方々から多く聞きます。

私は、誰かを置き去りにするデジタル化ではなく、"その会社の働き方に合った、やさしいデジタル化" を広げたいと考えています。

AIやWebシステムは、正しく使えば忙しい現場を助け、人と人が向き合う時間を増やし、地域で挑戦する企業の力になります。

だからこそ私たちは、高度な専門用語や大掛かりなDXではなく、「必要なところを、必要な分だけ」改善する小さな一歩 を大切にしています。

その積み重ねが、やがて会社全体を変え、地域を変え、社会の調和につながっていく。

そう信じて、Harmonic Societyはこれからも中小企業の皆さまとともに歩み続けます。', 'corporate-seo-pro' ),
        'sanitize_callback' => 'wp_kses_post',
    ) );

    $wp_customize->add_control( 'about_content', array(
        'label'   => __( 'アバウトコンテンツ', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'textarea',
    ) );
    
    $wp_customize->add_setting( 'about_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'about_image', array(
        'label'   => __( 'アバウト画像', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
    ) ) );
    
    // ブランドステートメント署名
    $wp_customize->add_setting( 'about_signature', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'about_signature', array(
        'label'       => __( 'ブランドステートメント署名', 'corporate-seo-pro' ),
        'section'     => 'corporate_seo_front_sections',
        'type'        => 'text',
        'description' => __( '例：代表取締役 山田太郎', 'corporate-seo-pro' ),
    ) );
    
    // 選ばれる理由セクション
    $wp_customize->add_setting( 'show_features_section', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    
    $wp_customize->add_control( 'show_features_section', array(
        'label'   => __( '選ばれる理由セクションを表示', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'checkbox',
    ) );
    
    $wp_customize->add_setting( 'features_title', array(
        'default'           => __( '選ばれる理由', 'corporate-seo-pro' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'features_title', array(
        'label'   => __( '選ばれる理由タイトル', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'text',
    ) );
    
    $wp_customize->add_setting( 'features_description', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'features_description', array(
        'label'   => __( '選ばれる理由の説明文', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'text',
    ) );
    
    // 3つの理由の設定
    for ( $i = 1; $i <= 3; $i++ ) {
        // タイトル
        $wp_customize->add_setting( 'feature_' . $i . '_title', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        
        $wp_customize->add_control( 'feature_' . $i . '_title', array(
            'label'   => sprintf( __( '理由%dのタイトル', 'corporate-seo-pro' ), $i ),
            'section' => 'corporate_seo_front_sections',
            'type'    => 'text',
        ) );
        
        // 説明文
        $wp_customize->add_setting( 'feature_' . $i . '_description', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_textarea_field',
        ) );
        
        $wp_customize->add_control( 'feature_' . $i . '_description', array(
            'label'   => sprintf( __( '理由%dの説明文', 'corporate-seo-pro' ), $i ),
            'section' => 'corporate_seo_front_sections',
            'type'    => 'textarea',
        ) );
        
        // アイコン
        $wp_customize->add_setting( 'feature_' . $i . '_icon', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        
        $wp_customize->add_control( 'feature_' . $i . '_icon', array(
            'label'       => sprintf( __( '理由%dのアイコンクラス', 'corporate-seo-pro' ), $i ),
            'section'     => 'corporate_seo_front_sections',
            'type'        => 'text',
            'description' => __( 'Font Awesomeのクラス名（例: fas fa-check）', 'corporate-seo-pro' ),
        ) );
    }
    
    // ニュースリリースセクション
    $wp_customize->add_setting( 'show_news_release_section', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    
    $wp_customize->add_control( 'show_news_release_section', array(
        'label'   => __( 'ニュースリリースセクションを表示', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'checkbox',
    ) );
    
    $wp_customize->add_setting( 'news_release_label', array(
        'default'           => __( 'News Release', 'corporate-seo-pro' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'news_release_label', array(
        'label'   => __( 'ニュースリリースラベル', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'text',
    ) );
    
    $wp_customize->add_setting( 'news_release_title', array(
        'default'           => __( 'ニュースリリース', 'corporate-seo-pro' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'news_release_title', array(
        'label'   => __( 'ニュースリリースセクションタイトル', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'text',
    ) );
    
    $wp_customize->add_setting( 'news_release_description', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'news_release_description', array(
        'label'   => __( 'ニュースリリースセクション説明文', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'text',
    ) );
    
    // ニュースセクション
    $wp_customize->add_setting( 'show_news_section', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    
    $wp_customize->add_control( 'show_news_section', array(
        'label'   => __( 'ニュースセクションを表示', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'checkbox',
    ) );
    
    $wp_customize->add_setting( 'news_title', array(
        'default'           => __( '最新情報', 'corporate-seo-pro' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'news_title', array(
        'label'   => __( 'ニュースセクションタイトル', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'text',
    ) );
    
    // CTAセクション
    $wp_customize->add_setting( 'show_cta_section', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    
    $wp_customize->add_control( 'show_cta_section', array(
        'label'   => __( 'CTAセクションを表示', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'checkbox',
    ) );
    
    $wp_customize->add_setting( 'cta_title', array(
        'default'           => __( '業務の"散らかり"を、1つのシステムで解決しませんか？', 'corporate-seo-pro' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'cta_title', array(
        'label'   => __( 'TOPページCTAタイトル', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'cta_button_text', array(
        'default'           => __( '無料で相談してみる', 'corporate-seo-pro' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'cta_button_text', array(
        'label'   => __( 'TOPページCTAボタンテキスト', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'cta_button_url', array(
        'default'           => '#contact',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( 'cta_button_url', array(
        'label'   => __( 'TOPページCTAボタンURL', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'url',
    ) );

    // ブログ記事用CTA設定
    $wp_customize->add_setting( 'show_blog_cta_section', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );

    $wp_customize->add_control( 'show_blog_cta_section', array(
        'label'   => __( 'ブログ記事CTAセクションを表示', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'checkbox',
    ) );

    $wp_customize->add_setting( 'blog_cta_title', array(
        'default'           => __( 'ちょっとした業務の悩みも、気軽にご相談ください。', 'corporate-seo-pro' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'blog_cta_title', array(
        'label'   => __( 'ブログ記事CTAタイトル', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'blog_cta_button_text', array(
        'default'           => __( 'まずは話だけ聞いてもらう', 'corporate-seo-pro' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'blog_cta_button_text', array(
        'label'   => __( 'ブログ記事CTAボタンテキスト', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'blog_cta_button_url', array(
        'default'           => '#contact',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( 'blog_cta_button_url', array(
        'label'   => __( 'ブログ記事CTAボタンURL', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'url',
    ) );
    
    /**
     * SEO設定
     */
    $wp_customize->add_section( 'corporate_seo_settings', array(
        'title'    => __( 'SEO設定', 'corporate-seo-pro' ),
        'priority' => 70,
    ) );
    
    // 構造化データ
    $wp_customize->add_setting( 'enable_local_business_schema', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    
    $wp_customize->add_control( 'enable_local_business_schema', array(
        'label'       => __( 'ローカルビジネス構造化データを有効化', 'corporate-seo-pro' ),
        'section'     => 'corporate_seo_settings',
        'type'        => 'checkbox',
        'description' => __( '地域密着型ビジネスの場合に有効化してください', 'corporate-seo-pro' ),
    ) );
    
    $wp_customize->add_setting( 'business_type', array(
        'default'           => 'LocalBusiness',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'business_type', array(
        'label'   => __( 'ビジネスタイプ', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_settings',
        'type'    => 'select',
        'choices' => array(
            'LocalBusiness'    => __( '一般的なローカルビジネス', 'corporate-seo-pro' ),
            'Restaurant'       => __( 'レストラン', 'corporate-seo-pro' ),
            'Store'           => __( '店舗', 'corporate-seo-pro' ),
            'ProfessionalService' => __( '専門サービス', 'corporate-seo-pro' ),
        ),
    ) );

    // 自動内部リンク
    $wp_customize->add_setting( 'enable_auto_internal_links', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );

    $wp_customize->add_control( 'enable_auto_internal_links', array(
        'label'       => __( '自動内部リンクを有効化', 'corporate-seo-pro' ),
        'description' => __( '記事内のキーワードを自動的に関連ページにリンクします', 'corporate-seo-pro' ),
        'section'     => 'corporate_seo_settings',
        'type'        => 'checkbox',
    ) );

    // AI/LLMクローラー設定
    $wp_customize->add_setting( 'enable_ai_crawlers', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );

    $wp_customize->add_control( 'enable_ai_crawlers', array(
        'label'       => __( 'AIクローラーを許可', 'corporate-seo-pro' ),
        'description' => __( 'GPTBot、ClaudeBot等のAIクローラーにコンテンツを公開します', 'corporate-seo-pro' ),
        'section'     => 'corporate_seo_settings',
        'type'        => 'checkbox',
    ) );

    /**
     * その他の設定
     */
    $wp_customize->add_section( 'corporate_seo_misc', array(
        'title'    => __( 'その他の設定', 'corporate-seo-pro' ),
        'priority' => 80,
    ) );
    
    // シェアボタン
    $wp_customize->add_setting( 'show_share_buttons', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    
    $wp_customize->add_control( 'show_share_buttons', array(
        'label'   => __( '記事にシェアボタンを表示', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_misc',
        'type'    => 'checkbox',
    ) );
    
    // 関連記事
    $wp_customize->add_setting( 'show_related_posts', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );

    $wp_customize->add_control( 'show_related_posts', array(
        'label'   => __( '関連記事を表示', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_misc',
        'type'    => 'checkbox',
    ) );

    /**
     * ===================================
     * エンティティ・ナレッジグラフ設定
     * ===================================
     * GoogleナレッジパネルやAI Overviewでの企業情報表示に使用
     */
    $wp_customize->add_section( 'corporate_seo_entity', array(
        'title'       => __( 'エンティティ・ナレッジグラフ設定', 'corporate-seo-pro' ),
        'priority'    => 45,
        'description' => __( 'GoogleナレッジパネルやAI Overviewでの表示、構造化データ（Schema.org）に使用される企業情報です。', 'corporate-seo-pro' ),
    ) );

    // 正式法人名
    $wp_customize->add_setting( 'company_legal_name', array(
        'default'           => 'Harmonic Society株式会社',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'company_legal_name', array(
        'label'       => __( '正式法人名', 'corporate-seo-pro' ),
        'description' => __( '登記上の正式な法人名', 'corporate-seo-pro' ),
        'section'     => 'corporate_seo_entity',
        'type'        => 'text',
    ) );

    // 創業日
    $wp_customize->add_setting( 'company_founding_date', array(
        'default'           => '2023-03-03',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'company_founding_date', array(
        'label'       => __( '創業日', 'corporate-seo-pro' ),
        'description' => __( 'YYYY-MM-DD形式で入力（例：2023-03-03）', 'corporate-seo-pro' ),
        'section'     => 'corporate_seo_entity',
        'type'        => 'date',
    ) );

    // 創業者名
    $wp_customize->add_setting( 'company_founder_name', array(
        'default'           => '師田 賢人',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'company_founder_name', array(
        'label'   => __( '創業者氏名', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_entity',
        'type'    => 'text',
    ) );

    // 創業者役職
    $wp_customize->add_setting( 'company_founder_title', array(
        'default'           => '代表取締役',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'company_founder_title', array(
        'label'   => __( '創業者役職', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_entity',
        'type'    => 'text',
    ) );

    // 創業者画像
    $wp_customize->add_setting( 'company_founder_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'company_founder_image', array(
        'label'       => __( '創業者・代表者写真', 'corporate-seo-pro' ),
        'description' => __( 'Person Schemaに使用されます（推奨: 400x400px以上）', 'corporate-seo-pro' ),
        'section'     => 'corporate_seo_entity',
    ) ) );

    // 創業者経歴
    $wp_customize->add_setting( 'company_founder_description', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );

    $wp_customize->add_control( 'company_founder_description', array(
        'label'       => __( '創業者経歴・プロフィール', 'corporate-seo-pro' ),
        'description' => __( '簡潔な経歴や専門分野を記載', 'corporate-seo-pro' ),
        'section'     => 'corporate_seo_entity',
        'type'        => 'textarea',
    ) );

    // 企業スローガン
    $wp_customize->add_setting( 'company_slogan', array(
        'default'           => '社会の調和',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'company_slogan', array(
        'label'       => __( '企業スローガン', 'corporate-seo-pro' ),
        'description' => __( '企業の理念やキャッチフレーズ', 'corporate-seo-pro' ),
        'section'     => 'corporate_seo_entity',
        'type'        => 'text',
    ) );

    // 従業員数
    $wp_customize->add_setting( 'company_employee_count', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'company_employee_count', array(
        'label'       => __( '従業員数', 'corporate-seo-pro' ),
        'description' => __( '空欄の場合は出力されません', 'corporate-seo-pro' ),
        'section'     => 'corporate_seo_entity',
        'type'        => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 10000,
        ),
    ) );

    // 郵便番号
    $wp_customize->add_setting( 'company_postal_code', array(
        'default'           => '262-0033',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'company_postal_code', array(
        'label'   => __( '郵便番号', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_entity',
        'type'    => 'text',
    ) );

    // 都道府県
    $wp_customize->add_setting( 'company_address_region', array(
        'default'           => '千葉県',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'company_address_region', array(
        'label'   => __( '都道府県', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_entity',
        'type'    => 'text',
    ) );

    // 市区町村
    $wp_customize->add_setting( 'company_address_locality', array(
        'default'           => '千葉市花見川区',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'company_address_locality', array(
        'label'   => __( '市区町村', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_entity',
        'type'    => 'text',
    ) );

    // 番地
    $wp_customize->add_setting( 'company_street_address', array(
        'default'           => '幕張本郷3-31-8',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'company_street_address', array(
        'label'   => __( '番地・建物名', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_entity',
        'type'    => 'text',
    ) );

    // DUNS番号（オプション）
    $wp_customize->add_setting( 'company_duns_number', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'company_duns_number', array(
        'label'       => __( 'DUNS番号（任意）', 'corporate-seo-pro' ),
        'description' => __( 'D&B社が付与する企業識別番号', 'corporate-seo-pro' ),
        'section'     => 'corporate_seo_entity',
        'type'        => 'text',
    ) );
}
add_action( 'customize_register', 'corporate_seo_pro_customize_register' );

/**
 * カスタマイザーのプレビュー用スクリプト
 */
function corporate_seo_pro_customize_preview_js() {
    wp_enqueue_script( 
        'corporate-seo-pro-customizer-preview', 
        get_template_directory_uri() . '/assets/js/customizer-preview.js', 
        array( 'customize-preview' ), 
        wp_get_theme()->get( 'Version' ), 
        true 
    );
}
add_action( 'customize_preview_init', 'corporate_seo_pro_customize_preview_js' );