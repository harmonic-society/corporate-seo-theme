<?php
/**
 * llms.txt 生成機能
 *
 * AIエージェント（ChatGPT、Claude、Perplexity等）向けの
 * サイト情報提供ファイルを動的に生成
 *
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * llms.txt リクエストの処理（リライトルール登録）
 */
function corporate_seo_pro_llms_txt_init() {
    add_rewrite_rule( '^llms\.txt$', 'index.php?llms_txt=1', 'top' );
    add_rewrite_rule( '^llms-full\.txt$', 'index.php?llms_full_txt=1', 'top' );
}
add_action( 'init', 'corporate_seo_pro_llms_txt_init' );

/**
 * クエリ変数の登録
 */
function corporate_seo_pro_llms_txt_query_vars( $vars ) {
    $vars[] = 'llms_txt';
    $vars[] = 'llms_full_txt';
    return $vars;
}
add_filter( 'query_vars', 'corporate_seo_pro_llms_txt_query_vars' );

/**
 * llms.txt の出力
 */
function corporate_seo_pro_render_llms_txt() {
    if ( get_query_var( 'llms_txt' ) ) {
        header( 'Content-Type: text/plain; charset=utf-8' );
        header( 'X-Robots-Tag: noindex' );
        echo corporate_seo_pro_generate_llms_txt();
        exit;
    }

    if ( get_query_var( 'llms_full_txt' ) ) {
        header( 'Content-Type: text/plain; charset=utf-8' );
        header( 'X-Robots-Tag: noindex' );
        echo corporate_seo_pro_generate_llms_full_txt();
        exit;
    }
}
add_action( 'template_redirect', 'corporate_seo_pro_render_llms_txt' );

/**
 * llms.txt コンテンツの生成（基本版）
 *
 * @return string llms.txt の内容
 */
function corporate_seo_pro_generate_llms_txt() {
    $site_name = get_bloginfo( 'name' );
    $site_url = home_url( '/' );
    $site_description = get_bloginfo( 'description' );

    // ヘッダー情報
    $output = "# {$site_name}\n\n";

    if ( ! empty( $site_description ) ) {
        $output .= "> {$site_description}\n\n";
    }

    // 会社概要セクション
    $output .= "## About\n\n";
    $output .= "Harmonic Society株式会社は、千葉県千葉市を拠点とするWebソリューション企業です。\n";
    $output .= "ホームページ制作、SEO対策、Webマーケティング、AI活用支援を専門としています。\n";
    $output .= "「ビジネスと社会の調和を創造する」をミッションに、中小企業のDX推進を支援しています。\n\n";

    // 専門分野
    $output .= "## Expertise\n\n";
    $output .= "- ホームページ制作・Webデザイン\n";
    $output .= "- WordPress開発・カスタマイズ\n";
    $output .= "- SEO対策・検索エンジン最適化\n";
    $output .= "- Webマーケティング・コンテンツマーケティング\n";
    $output .= "- AI活用支援・DX推進コンサルティング\n";
    $output .= "- レスポンシブデザイン・モバイル最適化\n\n";

    // 主要ページセクション
    $output .= "## Main Pages\n\n";
    $output .= "- [トップページ]({$site_url}): 会社概要とサービス一覧\n";

    // 会社概要ページ
    $about_page = get_page_by_path( 'about' );
    if ( ! $about_page ) {
        $about_page = get_page_by_path( 'company' );
    }
    if ( $about_page && $about_page->post_status === 'publish' ) {
        $output .= "- [会社概要](" . get_permalink( $about_page->ID ) . "): 企業理念、代表メッセージ、会社情報\n";
    }

    // サービスアーカイブ
    $service_archive_link = get_post_type_archive_link( 'service' );
    if ( $service_archive_link ) {
        $output .= "- [サービス一覧]({$service_archive_link}): 提供サービスの詳細\n";
    }

    // 制作実績アーカイブ
    $work_archive_link = get_post_type_archive_link( 'work' );
    if ( $work_archive_link ) {
        $output .= "- [制作実績]({$work_archive_link}): プロジェクト事例・ポートフォリオ\n";
    }

    // ブログ
    $blog_page_id = get_option( 'page_for_posts' );
    if ( $blog_page_id ) {
        $output .= "- [ブログ](" . get_permalink( $blog_page_id ) . "): Web制作・マーケティングの最新情報\n";
    } else {
        $output .= "- [ブログ](" . home_url( '/blog/' ) . "): Web制作・マーケティングの最新情報\n";
    }

    // お問い合わせページ
    $contact_page = get_page_by_path( 'contact' );
    if ( $contact_page && $contact_page->post_status === 'publish' ) {
        $output .= "- [お問い合わせ](" . get_permalink( $contact_page->ID ) . "): 相談・見積もり依頼\n";
    }

    // サービス一覧セクション
    $output .= "\n## Services\n\n";

    $services = get_posts( array(
        'post_type'      => 'service',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'post_status'    => 'publish',
    ) );

    if ( ! empty( $services ) ) {
        foreach ( $services as $service ) {
            $service_url = get_permalink( $service->ID );
            $service_excerpt = '';

            // ACFからサービスの説明を取得
            if ( function_exists( 'get_field' ) ) {
                $service_subtitle = get_field( 'service_subtitle', $service->ID );
                if ( ! empty( $service_subtitle ) ) {
                    $service_excerpt = ': ' . wp_strip_all_tags( $service_subtitle );
                }
            }

            // ACFがない場合は抜粋を使用
            if ( empty( $service_excerpt ) && ! empty( $service->post_excerpt ) ) {
                $service_excerpt = ': ' . wp_trim_words( wp_strip_all_tags( $service->post_excerpt ), 15, '...' );
            }

            $output .= "- [{$service->post_title}]({$service_url}){$service_excerpt}\n";
        }
    } else {
        $output .= "現在、サービス情報を準備中です。\n";
    }

    // 詳細版へのリンク
    $output .= "\n## Optional\n\n";
    $output .= "- [llms-full.txt](" . home_url( '/llms-full.txt' ) . "): 詳細情報版（最新記事・連絡先情報を含む）\n";

    return $output;
}

/**
 * llms-full.txt コンテンツの生成（詳細版）
 *
 * @return string llms-full.txt の内容
 */
function corporate_seo_pro_generate_llms_full_txt() {
    // 基本版の内容を取得
    $output = corporate_seo_pro_generate_llms_txt();

    // 最新記事セクション
    $output .= "\n## Recent Articles\n\n";

    $recent_posts = get_posts( array(
        'post_type'      => 'post',
        'posts_per_page' => 10,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    ) );

    if ( ! empty( $recent_posts ) ) {
        foreach ( $recent_posts as $post ) {
            $excerpt = wp_trim_words( wp_strip_all_tags( $post->post_content ), 30, '...' );
            $post_url = get_permalink( $post->ID );
            $post_date = get_the_date( 'Y-m-d', $post );

            $output .= "### {$post->post_title}\n";
            $output .= "- URL: {$post_url}\n";
            $output .= "- Date: {$post_date}\n";
            $output .= "- Summary: {$excerpt}\n\n";
        }
    } else {
        $output .= "現在、記事を準備中です。\n\n";
    }

    // 制作実績セクション
    $output .= "## Recent Works\n\n";

    $recent_works = get_posts( array(
        'post_type'      => 'work',
        'posts_per_page' => 5,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    ) );

    if ( ! empty( $recent_works ) ) {
        foreach ( $recent_works as $work ) {
            $work_url = get_permalink( $work->ID );

            // ACFからクライアント情報を取得
            $client_industry = '';
            if ( function_exists( 'get_field' ) ) {
                $industry = get_field( 'work_client_industry', $work->ID );
                if ( ! empty( $industry ) ) {
                    $client_industry = " ({$industry})";
                }
            }

            $output .= "- [{$work->post_title}]({$work_url}){$client_industry}\n";
        }
    } else {
        $output .= "現在、制作実績を準備中です。\n";
    }

    // 連絡先情報セクション
    $output .= "\n## Contact Information\n\n";
    $output .= "- 会社名: Harmonic Society株式会社\n";
    $output .= "- 所在地: 〒262-0033 千葉県千葉市花見川区幕張本郷3-31-8\n";

    // カスタマイザーから連絡先情報を取得
    $company_email = get_theme_mod( 'company_email' );
    if ( ! empty( $company_email ) ) {
        $output .= "- Email: {$company_email}\n";
    }

    $company_phone = get_theme_mod( 'company_phone' );
    if ( ! empty( $company_phone ) ) {
        $output .= "- 電話: {$company_phone}\n";
    }

    // お問い合わせページへのリンク
    $contact_page = get_page_by_path( 'contact' );
    if ( $contact_page && $contact_page->post_status === 'publish' ) {
        $output .= "- お問い合わせフォーム: " . get_permalink( $contact_page->ID ) . "\n";
    }

    // 対応エリア
    $output .= "\n## Service Area\n\n";
    $output .= "主な対応エリア: 千葉県（千葉市、船橋市、市川市、松戸市、柏市等）、東京都、神奈川県、埼玉県\n";
    $output .= "※オンライン対応により全国からのご依頼に対応可能です。\n";

    // 更新日時
    $output .= "\n---\n";
    $output .= "Last Updated: " . current_time( 'Y-m-d H:i:s' ) . " JST\n";

    return $output;
}

/**
 * リライトルールのフラッシュ（テーマ有効化時）
 */
function corporate_seo_pro_llms_txt_flush_rules() {
    corporate_seo_pro_llms_txt_init();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'corporate_seo_pro_llms_txt_flush_rules' );

/**
 * テーマ切り替え時にリライトルールをフラッシュ
 */
function corporate_seo_pro_llms_txt_theme_switch() {
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'corporate_seo_pro_llms_txt_theme_switch' );
