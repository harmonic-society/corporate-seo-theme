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

/**
 * 企業エンティティ設定オプションページの登録
 */
function corporate_seo_pro_register_entity_options_page() {
    if ( ! function_exists( 'acf_add_options_page' ) ) {
        return;
    }

    acf_add_options_page( array(
        'page_title'    => __( '企業エンティティ設定（ナレッジグラフ用）', 'corporate-seo-pro' ),
        'menu_title'    => __( 'エンティティ設定', 'corporate-seo-pro' ),
        'menu_slug'     => 'company-entity-settings',
        'capability'    => 'manage_options',
        'parent_slug'   => 'themes.php',
        'position'      => false,
        'icon_url'      => 'dashicons-networking',
        'redirect'      => false,
        'updated_message' => __( '企業エンティティ情報を更新しました', 'corporate-seo-pro' ),
    ) );
}
add_action( 'acf/init', 'corporate_seo_pro_register_entity_options_page' );

/**
 * 企業エンティティ情報フィールドグループの登録
 */
function corporate_seo_pro_register_entity_fields() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    acf_add_local_field_group( array(
        'key'    => 'group_company_entity',
        'title'  => __( '企業エンティティ情報（ナレッジグラフ・構造化データ用）', 'corporate-seo-pro' ),
        'fields' => array(
            // ========== 受賞歴 ==========
            array(
                'key'          => 'field_company_awards',
                'label'        => __( '受賞歴', 'corporate-seo-pro' ),
                'name'         => 'company_awards',
                'type'         => 'repeater',
                'instructions' => __( '企業として受賞した賞を登録してください。Schema.orgのawardプロパティに使用されます。', 'corporate-seo-pro' ),
                'min'          => 0,
                'max'          => 20,
                'layout'       => 'block',
                'button_label' => __( '受賞歴を追加', 'corporate-seo-pro' ),
                'sub_fields'   => array(
                    array(
                        'key'      => 'field_award_name',
                        'label'    => __( '賞名', 'corporate-seo-pro' ),
                        'name'     => 'name',
                        'type'     => 'text',
                        'required' => 1,
                        'wrapper'  => array( 'width' => '40' ),
                    ),
                    array(
                        'key'            => 'field_award_date',
                        'label'          => __( '受賞日', 'corporate-seo-pro' ),
                        'name'           => 'date',
                        'type'           => 'date_picker',
                        'display_format' => 'Y年m月d日',
                        'return_format'  => 'Y-m-d',
                        'wrapper'        => array( 'width' => '20' ),
                    ),
                    array(
                        'key'     => 'field_award_issuer',
                        'label'   => __( '授与機関', 'corporate-seo-pro' ),
                        'name'    => 'issuer',
                        'type'    => 'text',
                        'wrapper' => array( 'width' => '40' ),
                    ),
                    array(
                        'key'  => 'field_award_description',
                        'label' => __( '説明', 'corporate-seo-pro' ),
                        'name' => 'description',
                        'type' => 'textarea',
                        'rows' => 2,
                    ),
                ),
            ),

            // ========== 認定資格・認証 ==========
            array(
                'key'          => 'field_company_credentials',
                'label'        => __( '認定資格・認証', 'corporate-seo-pro' ),
                'name'         => 'company_credentials',
                'type'         => 'repeater',
                'instructions' => __( 'ISO認証、プライバシーマーク、各種認定資格など。Schema.orgのhasCredentialプロパティに使用されます。', 'corporate-seo-pro' ),
                'min'          => 0,
                'max'          => 20,
                'layout'       => 'block',
                'button_label' => __( '資格・認証を追加', 'corporate-seo-pro' ),
                'sub_fields'   => array(
                    array(
                        'key'      => 'field_credential_name',
                        'label'    => __( '資格・認証名', 'corporate-seo-pro' ),
                        'name'     => 'name',
                        'type'     => 'text',
                        'required' => 1,
                        'wrapper'  => array( 'width' => '40' ),
                    ),
                    array(
                        'key'     => 'field_credential_issuer',
                        'label'   => __( '発行機関', 'corporate-seo-pro' ),
                        'name'    => 'issuer',
                        'type'    => 'text',
                        'wrapper' => array( 'width' => '30' ),
                    ),
                    array(
                        'key'           => 'field_credential_valid_from',
                        'label'         => __( '取得日', 'corporate-seo-pro' ),
                        'name'          => 'valid_from',
                        'type'          => 'date_picker',
                        'return_format' => 'Y-m-d',
                        'wrapper'       => array( 'width' => '15' ),
                    ),
                    array(
                        'key'           => 'field_credential_valid_until',
                        'label'         => __( '有効期限', 'corporate-seo-pro' ),
                        'name'          => 'valid_until',
                        'type'          => 'date_picker',
                        'return_format' => 'Y-m-d',
                        'wrapper'       => array( 'width' => '15' ),
                    ),
                    array(
                        'key'          => 'field_credential_url',
                        'label'        => __( '証明URL', 'corporate-seo-pro' ),
                        'name'         => 'url',
                        'type'         => 'url',
                        'instructions' => __( '認証機関のページや証明書のURL', 'corporate-seo-pro' ),
                    ),
                ),
            ),

            // ========== 所属団体・加盟組織 ==========
            array(
                'key'          => 'field_company_memberships',
                'label'        => __( '所属団体・加盟組織', 'corporate-seo-pro' ),
                'name'         => 'company_memberships',
                'type'         => 'repeater',
                'instructions' => __( '業界団体、商工会議所、協会など。Schema.orgのmemberOfプロパティに使用されます。', 'corporate-seo-pro' ),
                'min'          => 0,
                'max'          => 20,
                'layout'       => 'block',
                'button_label' => __( '所属団体を追加', 'corporate-seo-pro' ),
                'sub_fields'   => array(
                    array(
                        'key'      => 'field_membership_org',
                        'label'    => __( '団体名', 'corporate-seo-pro' ),
                        'name'     => 'organization',
                        'type'     => 'text',
                        'required' => 1,
                        'wrapper'  => array( 'width' => '40' ),
                    ),
                    array(
                        'key'     => 'field_membership_url',
                        'label'   => __( '団体URL', 'corporate-seo-pro' ),
                        'name'    => 'url',
                        'type'    => 'url',
                        'wrapper' => array( 'width' => '40' ),
                    ),
                    array(
                        'key'         => 'field_membership_role',
                        'label'       => __( '会員種別・役割', 'corporate-seo-pro' ),
                        'name'        => 'role',
                        'type'        => 'text',
                        'placeholder' => __( '例：正会員、理事', 'corporate-seo-pro' ),
                        'wrapper'     => array( 'width' => '20' ),
                    ),
                ),
            ),

            // ========== 主要メンバー ==========
            array(
                'key'          => 'field_company_key_people',
                'label'        => __( '主要メンバー', 'corporate-seo-pro' ),
                'name'         => 'company_key_people',
                'type'         => 'repeater',
                'instructions' => __( '役員・キーパーソンの情報。Person Schemaとして出力されます（創業者情報はカスタマイザーで設定）。', 'corporate-seo-pro' ),
                'min'          => 0,
                'max'          => 10,
                'layout'       => 'block',
                'button_label' => __( 'メンバーを追加', 'corporate-seo-pro' ),
                'sub_fields'   => array(
                    array(
                        'key'      => 'field_person_name',
                        'label'    => __( '氏名', 'corporate-seo-pro' ),
                        'name'     => 'name',
                        'type'     => 'text',
                        'required' => 1,
                        'wrapper'  => array( 'width' => '30' ),
                    ),
                    array(
                        'key'      => 'field_person_title',
                        'label'    => __( '役職', 'corporate-seo-pro' ),
                        'name'     => 'title',
                        'type'     => 'text',
                        'required' => 1,
                        'wrapper'  => array( 'width' => '30' ),
                    ),
                    array(
                        'key'          => 'field_person_image',
                        'label'        => __( '写真', 'corporate-seo-pro' ),
                        'name'         => 'image',
                        'type'         => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'thumbnail',
                        'wrapper'      => array( 'width' => '40' ),
                    ),
                    array(
                        'key'   => 'field_person_description',
                        'label' => __( '経歴・プロフィール', 'corporate-seo-pro' ),
                        'name'  => 'description',
                        'type'  => 'textarea',
                        'rows'  => 3,
                    ),
                    array(
                        'key'          => 'field_person_sameAs',
                        'label'        => __( 'SNS・プロフィールURL', 'corporate-seo-pro' ),
                        'name'         => 'sameAs',
                        'type'         => 'repeater',
                        'min'          => 0,
                        'max'          => 5,
                        'layout'       => 'table',
                        'button_label' => __( 'URLを追加', 'corporate-seo-pro' ),
                        'sub_fields'   => array(
                            array(
                                'key'   => 'field_person_sameAs_url',
                                'label' => 'URL',
                                'name'  => 'url',
                                'type'  => 'url',
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'company-entity-settings',
                ),
            ),
        ),
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'active'                => true,
        'description'           => __( 'GoogleナレッジパネルやAI Overview、Schema.org構造化データに使用される企業情報', 'corporate-seo-pro' ),
    ) );
}
add_action( 'acf/init', 'corporate_seo_pro_register_entity_fields' );

/**
 * FAQ/HowTo構造化データ用ACFフィールドグループの登録
 */
function corporate_seo_pro_register_schema_fields() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    // FAQスキーマフィールドグループ
    acf_add_local_field_group( array(
        'key'    => 'group_faq_schema',
        'title'  => __( 'FAQ構造化データ', 'corporate-seo-pro' ),
        'fields' => array(
            array(
                'key'          => 'field_enable_faq_schema',
                'label'        => __( 'FAQスキーマを有効化', 'corporate-seo-pro' ),
                'name'         => 'enable_faq_schema',
                'type'         => 'true_false',
                'instructions' => __( 'この投稿にFAQ構造化データを出力します', 'corporate-seo-pro' ),
                'default_value' => 0,
                'ui'           => 1,
            ),
            array(
                'key'          => 'field_faq_items',
                'label'        => __( 'FAQ項目', 'corporate-seo-pro' ),
                'name'         => 'faq_items',
                'type'         => 'repeater',
                'instructions' => __( 'よくある質問と回答を入力してください。Schema.org FAQPageとして出力されます。', 'corporate-seo-pro' ),
                'min'          => 0,
                'max'          => 20,
                'layout'       => 'block',
                'button_label' => __( 'FAQ項目を追加', 'corporate-seo-pro' ),
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_enable_faq_schema',
                            'operator' => '==',
                            'value'    => '1',
                        ),
                    ),
                ),
                'sub_fields'   => array(
                    array(
                        'key'      => 'field_faq_question',
                        'label'    => __( '質問', 'corporate-seo-pro' ),
                        'name'     => 'question',
                        'type'     => 'text',
                        'required' => 1,
                        'placeholder' => __( '例：サービスの料金はいくらですか？', 'corporate-seo-pro' ),
                    ),
                    array(
                        'key'      => 'field_faq_answer',
                        'label'    => __( '回答', 'corporate-seo-pro' ),
                        'name'     => 'answer',
                        'type'     => 'wysiwyg',
                        'required' => 1,
                        'tabs'     => 'all',
                        'toolbar'  => 'basic',
                        'media_upload' => 0,
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'post',
                ),
            ),
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'page',
                ),
            ),
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'service',
                ),
            ),
        ),
        'menu_order'            => 20,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'active'                => true,
        'description'           => __( 'FAQスキーマ（FAQPage）構造化データを出力するためのフィールド', 'corporate-seo-pro' ),
    ) );

    // HowToスキーマフィールドグループ
    acf_add_local_field_group( array(
        'key'    => 'group_howto_schema',
        'title'  => __( 'HowTo構造化データ', 'corporate-seo-pro' ),
        'fields' => array(
            array(
                'key'          => 'field_enable_howto_schema',
                'label'        => __( 'HowToスキーマを有効化', 'corporate-seo-pro' ),
                'name'         => 'enable_howto_schema',
                'type'         => 'true_false',
                'instructions' => __( 'この投稿にHowTo構造化データを出力します', 'corporate-seo-pro' ),
                'default_value' => 0,
                'ui'           => 1,
            ),
            array(
                'key'          => 'field_howto_name',
                'label'        => __( '手順タイトル', 'corporate-seo-pro' ),
                'name'         => 'howto_name',
                'type'         => 'text',
                'instructions' => __( '手順全体のタイトルを入力してください', 'corporate-seo-pro' ),
                'placeholder'  => __( '例：WordPressでSEO対策を行う方法', 'corporate-seo-pro' ),
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_enable_howto_schema',
                            'operator' => '==',
                            'value'    => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key'          => 'field_howto_description',
                'label'        => __( '手順の説明', 'corporate-seo-pro' ),
                'name'         => 'howto_description',
                'type'         => 'textarea',
                'instructions' => __( '手順全体の説明を入力してください', 'corporate-seo-pro' ),
                'rows'         => 3,
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_enable_howto_schema',
                            'operator' => '==',
                            'value'    => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key'          => 'field_howto_total_time',
                'label'        => __( '所要時間', 'corporate-seo-pro' ),
                'name'         => 'howto_total_time',
                'type'         => 'text',
                'instructions' => __( '手順全体の所要時間をISO 8601形式で入力（例：PT30M = 30分、PT1H = 1時間）', 'corporate-seo-pro' ),
                'placeholder'  => 'PT30M',
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_enable_howto_schema',
                            'operator' => '==',
                            'value'    => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key'          => 'field_howto_steps',
                'label'        => __( '手順', 'corporate-seo-pro' ),
                'name'         => 'howto_steps',
                'type'         => 'repeater',
                'instructions' => __( '各手順を順番に入力してください。Schema.org HowToとして出力されます。', 'corporate-seo-pro' ),
                'min'          => 1,
                'max'          => 30,
                'layout'       => 'block',
                'button_label' => __( '手順を追加', 'corporate-seo-pro' ),
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_enable_howto_schema',
                            'operator' => '==',
                            'value'    => '1',
                        ),
                    ),
                ),
                'sub_fields'   => array(
                    array(
                        'key'      => 'field_howto_step_name',
                        'label'    => __( '手順名', 'corporate-seo-pro' ),
                        'name'     => 'name',
                        'type'     => 'text',
                        'required' => 1,
                        'placeholder' => __( '例：キーワードリサーチを行う', 'corporate-seo-pro' ),
                    ),
                    array(
                        'key'      => 'field_howto_step_text',
                        'label'    => __( '手順の詳細', 'corporate-seo-pro' ),
                        'name'     => 'text',
                        'type'     => 'textarea',
                        'required' => 1,
                        'rows'     => 3,
                    ),
                    array(
                        'key'      => 'field_howto_step_url',
                        'label'    => __( '手順の詳細URL（オプション）', 'corporate-seo-pro' ),
                        'name'     => 'url',
                        'type'     => 'url',
                        'required' => 0,
                    ),
                    array(
                        'key'          => 'field_howto_step_image',
                        'label'        => __( '手順の画像（オプション）', 'corporate-seo-pro' ),
                        'name'         => 'image',
                        'type'         => 'image',
                        'return_format' => 'url',
                        'preview_size' => 'medium',
                    ),
                ),
            ),
            // 必要なツール
            array(
                'key'          => 'field_howto_tools',
                'label'        => __( '必要なツール（オプション）', 'corporate-seo-pro' ),
                'name'         => 'howto_tools',
                'type'         => 'repeater',
                'instructions' => __( '手順に必要なツールを入力してください', 'corporate-seo-pro' ),
                'min'          => 0,
                'max'          => 10,
                'layout'       => 'table',
                'button_label' => __( 'ツールを追加', 'corporate-seo-pro' ),
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_enable_howto_schema',
                            'operator' => '==',
                            'value'    => '1',
                        ),
                    ),
                ),
                'sub_fields'   => array(
                    array(
                        'key'   => 'field_howto_tool_name',
                        'label' => __( 'ツール名', 'corporate-seo-pro' ),
                        'name'  => 'name',
                        'type'  => 'text',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'post',
                ),
            ),
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'page',
                ),
            ),
        ),
        'menu_order'            => 21,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'active'                => true,
        'description'           => __( 'HowToスキーマ構造化データを出力するためのフィールド', 'corporate-seo-pro' ),
    ) );
}
add_action( 'acf/init', 'corporate_seo_pro_register_schema_fields' );