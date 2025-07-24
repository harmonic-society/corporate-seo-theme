<?php
/**
 * サービスデータの編集例
 * 
 * このファイルは参考用です。実際の編集は single-service.php で行ってください。
 */

// サービスの特徴を編集する場合（single-service.php の 124行目付近）
$features = array(
    array(
        'icon' => 'fas fa-rocket',  // Font Awesomeのアイコンクラス
        'title' => '迅速な対応',
        'description' => 'お客様のご要望に素早くお応えし、スピーディーな課題解決を実現します。'
    ),
    array(
        'icon' => 'fas fa-shield-alt',
        'title' => '高品質保証',
        'description' => '業界最高水準の品質基準を設け、安心してご利用いただけるサービスを提供します。'
    ),
    array(
        'icon' => 'fas fa-users',
        'title' => '専門チーム',
        'description' => '経験豊富な専門家チームが、お客様のプロジェクトを成功へと導きます。'
    )
);

// サービスの流れを編集する場合（single-service.php の 170行目付近）
$processes = array(
    array(
        'title' => 'お問い合わせ',
        'description' => 'まずはお気軽にご相談ください'
    ),
    array(
        'title' => 'ヒアリング',
        'description' => 'お客様のご要望を詳しくお伺いします'
    ),
    array(
        'title' => 'ご提案',
        'description' => '最適なソリューションをご提案します'
    ),
    array(
        'title' => '実施',
        'description' => 'プロフェッショナルチームが実行します'
    ),
    array(
        'title' => 'フォローアップ',
        'description' => '継続的なサポートを提供します'
    )
);

// 料金プランを編集する場合（single-service.php の 207行目付近）
$plans = array(
    array(
        'name' => 'ベーシックプラン',
        'price' => '¥50,000〜',
        'features' => array(
            '基本機能',
            'メールサポート',
            '月1回のレポート'
        ),
        'recommended' => false
    ),
    array(
        'name' => 'スタンダードプラン',
        'price' => '¥100,000〜',
        'features' => array(
            '全機能利用可能',
            '優先サポート',
            '週次レポート',
            'カスタマイズ対応'
        ),
        'recommended' => true  // おすすめプラン
    ),
    array(
        'name' => 'プレミアムプラン',
        'price' => 'お見積もり',
        'features' => array(
            'フルカスタマイズ',
            '24時間サポート',
            'リアルタイムレポート',
            '専任担当者'
        ),
        'recommended' => false
    )
);

// FAQを編集する場合（single-service.php の 268行目付近）
$faqs = array(
    array(
        'question' => 'サービスの導入期間はどのくらいですか？',
        'answer' => 'プロジェクトの規模により異なりますが、通常2週間〜1ヶ月程度での導入が可能です。'
    ),
    array(
        'question' => 'サポート体制について教えてください。',
        'answer' => '専任の担当者がつき、メール・電話・チャットでのサポートを提供しています。'
    ),
    array(
        'question' => '他社からの乗り換えは可能ですか？',
        'answer' => 'はい、可能です。データ移行のサポートも含めて対応いたします。'
    )
);

/**
 * アイコンの選び方
 * 
 * Font Awesome 6.4.0 のアイコンが使用可能です。
 * https://fontawesome.com/icons
 * 
 * 例：
 * - fas fa-rocket（ロケット）
 * - fas fa-shield-alt（シールド）
 * - fas fa-users（ユーザーグループ）
 * - fas fa-chart-line（グラフ）
 * - fas fa-cog（歯車）
 * - fas fa-check-circle（チェックマーク）
 * - fas fa-star（星）
 * - fas fa-lightbulb（電球）
 * - fas fa-lock（鍵）
 * - fas fa-globe（地球）
 */
?>