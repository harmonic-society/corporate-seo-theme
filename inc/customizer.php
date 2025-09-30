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
    
    // セカンダリボタン
    $wp_customize->add_setting( 'hero_button2_text', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'hero_button2_text', array(
        'label'   => __( 'セカンダリボタンテキスト', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_hero',
        'type'    => 'text',
    ) );
    
    $wp_customize->add_setting( 'hero_button2_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    
    $wp_customize->add_control( 'hero_button2_url', array(
        'label'   => __( 'セカンダリボタンURL', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_hero',
        'type'    => 'url',
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
    
    // タイトル
    $wp_customize->add_setting( 'hero_title', array(
        'default'           => get_bloginfo( 'name' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'hero_title', array(
        'label'   => __( 'ヒーロータイトル', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_hero',
        'type'    => 'text',
    ) );
    
    // 説明文
    $wp_customize->add_setting( 'hero_description', array(
        'default'           => get_bloginfo( 'description' ),
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    
    $wp_customize->add_control( 'hero_description', array(
        'label'   => __( 'ヒーロー説明文', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_hero',
        'type'    => 'textarea',
    ) );
    
    // プライマリボタン
    $wp_customize->add_setting( 'hero_button_text', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'hero_button_text', array(
        'label'   => __( 'プライマリボタンテキスト', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_hero',
        'type'    => 'text',
    ) );
    
    $wp_customize->add_setting( 'hero_button_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    
    $wp_customize->add_control( 'hero_button_url', array(
        'label'   => __( 'プライマリボタンURL', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_hero',
        'type'    => 'url',
    ) );
    
    // ヒーローバリエーション
    $wp_customize->add_setting( 'hero_style', array(
        'default'           => 'image',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'hero_style', array(
        'label'   => __( 'ヒーロースタイル', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_hero',
        'type'    => 'select',
        'choices' => array(
            'gradient'     => __( 'グラデーション', 'corporate-seo-pro' ),
            'image'        => __( '背景画像', 'corporate-seo-pro' ),
            'video'        => __( '背景動画', 'corporate-seo-pro' ),
            'particles'    => __( 'パーティクル', 'corporate-seo-pro' ),
            'geometric'    => __( '幾何学模様', 'corporate-seo-pro' ),
        ),
    ) );
    
    // 背景画像
    $wp_customize->add_setting( 'hero_background_image', array(
        'default'           => 'https://harmonic-society.co.jp/wp-content/uploads/2024/10/GettyImages-981641584-scaled.jpg',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'hero_background_image', array(
        'label'       => __( 'ヒーロー背景画像', 'corporate-seo-pro' ),
        'section'     => 'corporate_seo_hero',
        'description' => __( 'ヒーロースタイルが「背景画像」の場合に使用されます。推奨サイズ: 1920×1080px', 'corporate-seo-pro' ),
    ) ) );
    
    // 背景動画
    $wp_customize->add_setting( 'hero_background_video', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    
    $wp_customize->add_control( 'hero_background_video', array(
        'label'       => __( 'ヒーロー背景動画URL', 'corporate-seo-pro' ),
        'section'     => 'corporate_seo_hero',
        'type'        => 'url',
        'description' => __( 'MP4形式の動画URLを入力してください', 'corporate-seo-pro' ),
    ) );
    
    // オーバーレイの濃度
    $wp_customize->add_setting( 'hero_overlay_opacity', array(
        'default'           => '0.5',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'hero_overlay_opacity', array(
        'label'       => __( 'オーバーレイの濃度', 'corporate-seo-pro' ),
        'section'     => 'corporate_seo_hero',
        'type'        => 'select',
        'choices'     => array(
            '0'    => __( 'なし', 'corporate-seo-pro' ),
            '0.3'  => __( '薄い', 'corporate-seo-pro' ),
            '0.5'  => __( '中間', 'corporate-seo-pro' ),
            '0.7'  => __( '濃い', 'corporate-seo-pro' ),
        ),
        'description' => __( '背景画像・動画使用時のオーバーレイ', 'corporate-seo-pro' ),
    ) );
    
    // アニメーション設定
    $wp_customize->add_setting( 'hero_animation', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    
    $wp_customize->add_control( 'hero_animation', array(
        'label'   => __( 'ヒーローアニメーションを有効化', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_hero',
        'type'    => 'checkbox',
    ) );
    
    // パーティクル設定
    $wp_customize->add_setting( 'hero_particles_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'hero_particles_color', array(
        'label'   => __( 'パーティクルの色', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_hero',
    ) ) );
    
    // ヒーロー特徴セクション
    for ( $i = 1; $i <= 3; $i++ ) {
        // 特徴タイトル
        $wp_customize->add_setting( 'hero_feature_' . $i . '_title', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        
        $wp_customize->add_control( 'hero_feature_' . $i . '_title', array(
            'label'   => sprintf( __( 'ヒーロー特徴%dタイトル', 'corporate-seo-pro' ), $i ),
            'section' => 'corporate_seo_hero',
            'type'    => 'text',
        ) );
        
        // 特徴説明
        $wp_customize->add_setting( 'hero_feature_' . $i . '_desc', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        
        $wp_customize->add_control( 'hero_feature_' . $i . '_desc', array(
            'label'   => sprintf( __( 'ヒーロー特徴%d説明', 'corporate-seo-pro' ), $i ),
            'section' => 'corporate_seo_hero',
            'type'    => 'text',
        ) );
        
        // 特徴アイコン
        $wp_customize->add_setting( 'hero_feature_' . $i . '_icon', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        
        $wp_customize->add_control( 'hero_feature_' . $i . '_icon', array(
            'label'   => sprintf( __( 'ヒーロー特徴%dアイコン', 'corporate-seo-pro' ), $i ),
            'section' => 'corporate_seo_hero',
            'type'    => 'text',
            'description' => __( 'Font Awesomeのクラス名（例: fas fa-rocket）', 'corporate-seo-pro' ),
        ) );
    }
    
    // グラデーション設定
    $wp_customize->add_setting( 'hero_gradient_start', array(
        'default'           => '#10b981',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'hero_gradient_start', array(
        'label'   => __( 'グラデーション開始色', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_hero',
    ) ) );
    
    $wp_customize->add_setting( 'hero_gradient_end', array(
        'default'           => '#059669',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'hero_gradient_end', array(
        'label'   => __( 'グラデーション終了色', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_hero',
    ) ) );
    
    // テキスト色
    $wp_customize->add_setting( 'hero_text_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'hero_text_color', array(
        'label'   => __( 'ヒーローテキスト色', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_hero',
    ) ) );
    
    // ヒーローの高さ
    $wp_customize->add_setting( 'hero_height', array(
        'default'           => 'default',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'hero_height', array(
        'label'   => __( 'ヒーローセクションの高さ', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_hero',
        'type'    => 'select',
        'choices' => array(
            'small'    => __( '小（50vh）', 'corporate-seo-pro' ),
            'default'  => __( '標準（70vh）', 'corporate-seo-pro' ),
            'large'    => __( '大（90vh）', 'corporate-seo-pro' ),
            'full'     => __( 'フルスクリーン（100vh）', 'corporate-seo-pro' ),
        ),
    ) );
    
    // テキスト配置
    $wp_customize->add_setting( 'hero_text_align', array(
        'default'           => 'center',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'hero_text_align', array(
        'label'   => __( 'テキスト配置', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_hero',
        'type'    => 'select',
        'choices' => array(
            'left'   => __( '左寄せ', 'corporate-seo-pro' ),
            'center' => __( '中央', 'corporate-seo-pro' ),
            'right'  => __( '右寄せ', 'corporate-seo-pro' ),
        ),
    ) );
    
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
        'default'           => '',
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
        'default'           => __( 'お問い合わせはこちら', 'corporate-seo-pro' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'cta_title', array(
        'label'   => __( 'CTAタイトル', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'text',
    ) );
    
    $wp_customize->add_setting( 'cta_description', array(
        'default'           => __( 'お気軽にお問い合わせください', 'corporate-seo-pro' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'cta_description', array(
        'label'   => __( 'CTA説明文', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'text',
    ) );
    
    $wp_customize->add_setting( 'cta_button_text', array(
        'default'           => __( '今すぐお問い合わせ', 'corporate-seo-pro' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'cta_button_text', array(
        'label'   => __( 'CTAボタンテキスト', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'text',
    ) );
    
    $wp_customize->add_setting( 'cta_button_url', array(
        'default'           => '#contact',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    
    $wp_customize->add_control( 'cta_button_url', array(
        'label'   => __( 'CTAボタンURL', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'url',
    ) );
    
    $wp_customize->add_setting( 'cta_phone', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'cta_phone', array(
        'label'   => __( 'CTA電話番号', 'corporate-seo-pro' ),
        'section' => 'corporate_seo_front_sections',
        'type'    => 'tel',
        'description' => __( '例: 03-1234-5678', 'corporate-seo-pro' ),
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