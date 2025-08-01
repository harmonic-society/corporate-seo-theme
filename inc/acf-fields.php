<?php
/**
 * ACF Field Group Registration
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register ACF field groups for services
 */
function corporate_seo_pro_register_acf_fields() {
    if ( ! function_exists('acf_add_local_field_group') ) {
        return;
    }

    // サービス詳細情報フィールドグループ
    acf_add_local_field_group(array(
        'key' => 'group_service_fields',
        'title' => 'サービス詳細情報',
        'fields' => array(
            // サービスサブタイトル
            array(
                'key' => 'field_service_subtitle',
                'label' => 'サービスサブタイトル',
                'name' => 'service_subtitle',
                'type' => 'text',
                'instructions' => 'サービスのサブタイトルを入力してください',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '例：お客様のビジネスを次のレベルへ',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            // サービス所要時間
            array(
                'key' => 'field_service_duration',
                'label' => 'サービス所要時間',
                'name' => 'service_duration',
                'type' => 'text',
                'instructions' => 'サービスの標準的な所要時間を入力してください',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '例：1〜3ヶ月',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            // サービス価格
            array(
                'key' => 'field_service_price',
                'label' => 'サービス価格',
                'name' => 'service_price',
                'type' => 'text',
                'instructions' => 'サービスの価格帯を入力してください',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '例：¥100,000〜',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            // サービスの特徴
            array(
                'key' => 'field_service_features',
                'label' => 'サービスの特徴',
                'name' => 'service_features',
                'type' => 'repeater',
                'instructions' => 'サービスの主な特徴を入力してください（3つ推奨）',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'collapsed' => 'field_feature_title',
                'min' => 0,
                'max' => 6,
                'layout' => 'block',
                'button_label' => '特徴を追加',
                'sub_fields' => array(
                    array(
                        'key' => 'field_feature_icon',
                        'label' => 'アイコンクラス',
                        'name' => 'icon',
                        'type' => 'text',
                        'instructions' => 'Font Awesomeのアイコンクラスを入力（例：fas fa-rocket）',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '25',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => 'fas fa-check-circle',
                        'placeholder' => 'fas fa-rocket',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_feature_title',
                        'label' => '特徴タイトル',
                        'name' => 'title',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '75',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '例：迅速な対応',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_feature_description',
                        'label' => '特徴説明',
                        'name' => 'description',
                        'type' => 'textarea',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => 'この特徴の詳細な説明を入力してください',
                        'maxlength' => '',
                        'rows' => 3,
                        'new_lines' => '',
                    ),
                ),
            ),
            // 料金プラン
            array(
                'key' => 'field_service_plans',
                'label' => '料金プラン',
                'name' => 'service_plans',
                'type' => 'repeater',
                'instructions' => 'サービスの料金プランを入力してください',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'collapsed' => 'field_plan_name',
                'min' => 0,
                'max' => 6,
                'layout' => 'block',
                'button_label' => 'プランを追加',
                'sub_fields' => array(
                    array(
                        'key' => 'field_plan_name',
                        'label' => 'プラン名',
                        'name' => 'name',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '例：スタンダードプラン',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_plan_price',
                        'label' => '価格',
                        'name' => 'price',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '30',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '例：¥100,000〜',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_plan_recommended',
                        'label' => 'おすすめプラン',
                        'name' => 'recommended',
                        'type' => 'true_false',
                        'instructions' => 'このプランをおすすめとして表示する',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '20',
                            'class' => '',
                            'id' => '',
                        ),
                        'message' => '',
                        'default_value' => 0,
                        'ui' => 1,
                        'ui_on_text' => 'おすすめ',
                        'ui_off_text' => '',
                    ),
                    array(
                        'key' => 'field_plan_features',
                        'label' => 'プラン機能',
                        'name' => 'features',
                        'type' => 'textarea',
                        'instructions' => 'このプランに含まれる機能を改行区切りで入力',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => "基本機能\nメールサポート\n月次レポート",
                        'maxlength' => '',
                        'rows' => 4,
                        'new_lines' => 'br',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'service',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => 'Harmonic Societyのサービス詳細ページで使用するカスタムフィールド',
        'show_in_rest' => 1,
    ));

    // ニュースリリース詳細情報フィールドグループ
    acf_add_local_field_group(array(
        'key' => 'group_news_fields',
        'title' => 'ニュースリリース詳細情報',
        'fields' => array(
            // OGP画像
            array(
                'key' => 'field_news_ogp_image',
                'label' => 'OGP画像',
                'name' => 'news_ogp_image',
                'type' => 'image',
                'instructions' => 'SNSでシェアされる際に表示される画像を設定してください。推奨サイズ：1200x630px',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
                'min_width' => '',
                'min_height' => '',
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => 'jpg,jpeg,png',
            ),
            // OGPタイトル（オプション）
            array(
                'key' => 'field_news_ogp_title',
                'label' => 'OGPタイトル',
                'name' => 'news_ogp_title',
                'type' => 'text',
                'instructions' => 'SNS用のタイトルを個別に設定する場合に入力してください（空欄の場合は投稿タイトルが使用されます）',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => 60,
            ),
            // OGP説明文（オプション）
            array(
                'key' => 'field_news_ogp_description',
                'label' => 'OGP説明文',
                'name' => 'news_ogp_description',
                'type' => 'textarea',
                'instructions' => 'SNS用の説明文を個別に設定する場合に入力してください（空欄の場合は自動生成されます）',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => 160,
                'rows' => 3,
                'new_lines' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'news',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => 'ニュースリリースのSNSシェア用設定',
        'show_in_rest' => 1,
    ));
}
add_action('acf/init', 'corporate_seo_pro_register_acf_fields');

/**
 * ACF JSON保存ディレクトリの設定
 */
function corporate_seo_pro_acf_json_save_point( $path ) {
    // 保存先を指定
    $path = get_stylesheet_directory() . '/acf-json';
    
    // ディレクトリが存在しない場合は作成
    if ( ! file_exists( $path ) ) {
        wp_mkdir_p( $path );
    }
    
    return $path;
}
add_filter('acf/settings/save_json', 'corporate_seo_pro_acf_json_save_point');

/**
 * ACF JSON読み込みディレクトリの設定
 */
function corporate_seo_pro_acf_json_load_point( $paths ) {
    // 現在のパスを削除
    unset($paths[0]);
    
    // 新しいパスを追加
    $paths[] = get_stylesheet_directory() . '/acf-json';
    
    return $paths;
}
add_filter('acf/settings/load_json', 'corporate_seo_pro_acf_json_load_point');