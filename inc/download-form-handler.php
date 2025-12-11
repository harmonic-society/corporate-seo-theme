<?php
/**
 * Download Form Handler
 *
 * 資料ダウンロードフォームの処理
 * リード情報をカスタム投稿タイプに保存
 *
 * @package Corporate_SEO_Pro
 */

// 直接アクセス禁止
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * ダウンロードリード用のカスタム投稿タイプを登録
 */
function corporate_seo_pro_register_download_lead_post_type() {
    $labels = array(
        'name'               => __( 'ダウンロードリード', 'corporate-seo-pro' ),
        'singular_name'      => __( 'ダウンロードリード', 'corporate-seo-pro' ),
        'menu_name'          => __( '資料DLリード', 'corporate-seo-pro' ),
        'all_items'          => __( 'すべてのリード', 'corporate-seo-pro' ),
        'view_item'          => __( 'リードを表示', 'corporate-seo-pro' ),
        'search_items'       => __( 'リードを検索', 'corporate-seo-pro' ),
        'not_found'          => __( 'リードが見つかりません', 'corporate-seo-pro' ),
        'not_found_in_trash' => __( 'ゴミ箱にリードがありません', 'corporate-seo-pro' ),
    );

    $args = array(
        'labels'              => $labels,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 25,
        'menu_icon'           => 'dashicons-download',
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'supports'            => array( 'title' ),
        'has_archive'         => false,
        'rewrite'             => false,
        'query_var'           => false,
        'exclude_from_search' => true,
    );

    register_post_type( 'download_lead', $args );
}
add_action( 'init', 'corporate_seo_pro_register_download_lead_post_type' );

/**
 * ダウンロードリードの管理画面カラムを追加
 */
function corporate_seo_pro_download_lead_columns( $columns ) {
    $new_columns = array(
        'cb'            => $columns['cb'],
        'title'         => __( 'メールアドレス', 'corporate-seo-pro' ),
        'download_date' => __( 'ダウンロード日時', 'corporate-seo-pro' ),
        'source'        => __( 'ソース', 'corporate-seo-pro' ),
        'source_post'   => __( '関連記事', 'corporate-seo-pro' ),
    );
    return $new_columns;
}
add_filter( 'manage_download_lead_posts_columns', 'corporate_seo_pro_download_lead_columns' );

/**
 * ダウンロードリードの管理画面カラム内容
 */
function corporate_seo_pro_download_lead_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'download_date':
            $date = get_post_meta( $post_id, '_download_date', true );
            if ( $date ) {
                echo esc_html( date_i18n( 'Y/m/d H:i', strtotime( $date ) ) );
            }
            break;

        case 'source':
            $source = get_post_meta( $post_id, '_download_source', true );
            $source_labels = array(
                'nav_button'     => __( 'ナビボタン', 'corporate-seo-pro' ),
                'mobile_menu'    => __( 'モバイルメニュー', 'corporate-seo-pro' ),
                'footer'         => __( 'フッター', 'corporate-seo-pro' ),
                'page'           => __( 'ページ内', 'corporate-seo-pro' ),
                'blog_article'   => __( 'ブログ記事', 'corporate-seo-pro' ),
            );
            echo isset( $source_labels[ $source ] ) ? esc_html( $source_labels[ $source ] ) : esc_html( $source );
            break;

        case 'source_post':
            $source_post_id = get_post_meta( $post_id, '_download_source_post_id', true );
            if ( $source_post_id ) {
                $post_title = get_post_meta( $post_id, '_download_source_post_title', true );
                $edit_link = get_edit_post_link( $source_post_id );
                if ( $edit_link ) {
                    echo '<a href="' . esc_url( $edit_link ) . '">' . esc_html( $post_title ) . '</a>';
                } else {
                    echo esc_html( $post_title );
                }
            } else {
                echo '—';
            }
            break;
    }
}
add_action( 'manage_download_lead_posts_custom_column', 'corporate_seo_pro_download_lead_column_content', 10, 2 );

/**
 * ダウンロードリードの管理画面カラムをソート可能に
 */
function corporate_seo_pro_download_lead_sortable_columns( $columns ) {
    $columns['download_date'] = 'download_date';
    return $columns;
}
add_filter( 'manage_edit-download_lead_sortable_columns', 'corporate_seo_pro_download_lead_sortable_columns' );

/**
 * AJAXハンドラー：ダウンロードフォーム処理
 */
function corporate_seo_pro_process_download_form() {
    // Nonce検証
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'download_form_nonce' ) ) {
        wp_send_json_error( array( 'message' => __( 'セキュリティエラーが発生しました。', 'corporate-seo-pro' ) ) );
    }

    // メールアドレス取得・サニタイズ
    $email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

    // メールアドレスバリデーション
    if ( empty( $email ) || ! is_email( $email ) ) {
        wp_send_json_error( array( 'message' => __( '有効なメールアドレスを入力してください。', 'corporate-seo-pro' ) ) );
    }

    // リードを投稿として保存
    $post_data = array(
        'post_type'   => 'download_lead',
        'post_title'  => $email,
        'post_status' => 'publish',
    );

    $post_id = wp_insert_post( $post_data );

    if ( is_wp_error( $post_id ) ) {
        wp_send_json_error( array( 'message' => __( 'データの保存に失敗しました。', 'corporate-seo-pro' ) ) );
    }

    // ソース情報を取得（拡張）
    $source = isset( $_POST['source'] )
        ? sanitize_text_field( wp_unslash( $_POST['source'] ) )
        : 'nav_button';

    // 記事IDを取得（新規）
    $source_post_id = isset( $_POST['source_post_id'] )
        ? absint( wp_unslash( $_POST['source_post_id'] ) )
        : 0;

    // メタデータを保存
    update_post_meta( $post_id, '_download_email', $email );
    update_post_meta( $post_id, '_download_date', current_time( 'mysql' ) );
    update_post_meta( $post_id, '_download_source', $source );
    update_post_meta( $post_id, '_user_ip', corporate_seo_pro_get_user_ip() );
    update_post_meta( $post_id, '_user_agent', isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '' );

    // 記事IDが指定されている場合は保存
    if ( $source_post_id > 0 ) {
        update_post_meta( $post_id, '_download_source_post_id', $source_post_id );
        update_post_meta( $post_id, '_download_source_post_title', get_the_title( $source_post_id ) );
    }

    // ダウンロードURL
    $download_url = isset( $_POST['download_url'] ) ? esc_url_raw( wp_unslash( $_POST['download_url'] ) ) : '';

    // 管理者にメール通知を送信
    corporate_seo_pro_send_download_notification( $email, $download_url );

    // メルマガ購読者にも自動登録
    corporate_seo_pro_auto_subscribe_newsletter( $email, $source );

    // 成功レスポンス
    wp_send_json_success( array(
        'message'      => __( 'ダウンロードの準備ができました。', 'corporate-seo-pro' ),
        'download_url' => $download_url,
    ) );
}
add_action( 'wp_ajax_process_download_form', 'corporate_seo_pro_process_download_form' );
add_action( 'wp_ajax_nopriv_process_download_form', 'corporate_seo_pro_process_download_form' );

/**
 * 資料ダウンロード通知メールを管理者に送信
 *
 * @param string $email        ダウンロードしたユーザーのメールアドレス
 * @param string $download_url ダウンロードURL
 * @return bool メール送信の成否
 */
function corporate_seo_pro_send_download_notification( $email, $download_url = '' ) {
    // 送信先（管理者メール）
    $to = 'morota@harmonic-society.co.jp';

    // サイト名
    $site_name = get_bloginfo( 'name' );

    // 件名
    $subject = sprintf(
        /* translators: %s: site name */
        __( '[%s] 資料ダウンロードがありました', 'corporate-seo-pro' ),
        $site_name
    );

    // 日時
    $datetime = current_time( 'Y年m月d日 H:i:s' );

    // IPアドレス
    $ip_address = corporate_seo_pro_get_user_ip();

    // メール本文
    $message = sprintf(
        __( '資料ダウンロードの通知

以下の情報で資料がダウンロードされました。

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

■ メールアドレス
%1$s

■ ダウンロード日時
%2$s

■ IPアドレス
%3$s

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

このメールは %4$s からの自動送信です。
管理画面でリード一覧を確認できます: %5$s

', 'corporate-seo-pro' ),
        $email,
        $datetime,
        $ip_address,
        $site_name,
        admin_url( 'edit.php?post_type=download_lead' )
    );

    // メールヘッダー
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $site_name . ' <' . $to . '>',
    );

    // メール送信
    return wp_mail( $to, $subject, $message, $headers );
}

/**
 * ユーザーIPアドレスを取得
 *
 * @return string
 */
function corporate_seo_pro_get_user_ip() {
    $ip = '';

    if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
        $ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
    } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
        $ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) );
    } elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
        $ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
    }

    // 複数IPがある場合は最初のものを使用
    if ( strpos( $ip, ',' ) !== false ) {
        $ip = explode( ',', $ip )[0];
    }

    return trim( $ip );
}

/**
 * リード一覧にエクスポートボタンを追加
 */
function corporate_seo_pro_add_export_button() {
    $screen = get_current_screen();
    if ( $screen && 'edit-download_lead' === $screen->id ) {
        ?>
        <script>
        jQuery(document).ready(function($) {
            var exportUrl = '<?php echo esc_url( admin_url( 'admin-ajax.php?action=export_download_leads&nonce=' . wp_create_nonce( 'export_leads_nonce' ) ) ); ?>';
            $('.wrap .wp-header-end').before('<a href="' + exportUrl + '" class="page-title-action">CSVエクスポート</a>');
        });
        </script>
        <?php
    }
}
add_action( 'admin_footer', 'corporate_seo_pro_add_export_button' );

/**
 * リードをCSVエクスポート
 */
function corporate_seo_pro_export_download_leads() {
    // 権限チェック
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( '権限がありません。', 'corporate-seo-pro' ) );
    }

    // Nonce検証
    if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['nonce'] ) ), 'export_leads_nonce' ) ) {
        wp_die( esc_html__( 'セキュリティエラー', 'corporate-seo-pro' ) );
    }

    // リードを取得
    $leads = get_posts( array(
        'post_type'      => 'download_lead',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ) );

    // CSVヘッダー
    header( 'Content-Type: text/csv; charset=UTF-8' );
    header( 'Content-Disposition: attachment; filename="download-leads-' . gmdate( 'Y-m-d' ) . '.csv"' );

    $output = fopen( 'php://output', 'w' );

    // BOM for UTF-8
    fwrite( $output, "\xEF\xBB\xBF" );

    // ヘッダー行
    fputcsv( $output, array(
        'メールアドレス',
        'ダウンロード日時',
        'ソース',
        '関連記事',
        'IPアドレス',
    ) );

    // データ行
    foreach ( $leads as $lead ) {
        fputcsv( $output, array(
            get_post_meta( $lead->ID, '_download_email', true ),
            get_post_meta( $lead->ID, '_download_date', true ),
            get_post_meta( $lead->ID, '_download_source', true ),
            get_post_meta( $lead->ID, '_download_source_post_title', true ),
            get_post_meta( $lead->ID, '_user_ip', true ),
        ) );
    }

    fclose( $output );
    exit;
}
add_action( 'wp_ajax_export_download_leads', 'corporate_seo_pro_export_download_leads' );

/**
 * 資料ダウンロード時にメルマガ購読者として自動登録
 *
 * @param string $email  メールアドレス
 * @param string $source ダウンロード元（nav_button, blog_article等）
 */
function corporate_seo_pro_auto_subscribe_newsletter( $email, $source = 'download' ) {
    // メールアドレスのバリデーション
    if ( ! is_email( $email ) ) {
        return;
    }

    // 既存の購読者をチェック
    $existing = get_posts( array(
        'post_type'      => 'nl_subscriber',
        'meta_key'       => 'subscriber_email',
        'meta_value'     => $email,
        'posts_per_page' => 1,
        'post_status'    => 'any',
    ) );

    // 既に登録済みの場合
    if ( ! empty( $existing ) ) {
        $existing_id = $existing[0]->ID;
        $current_status = get_post_meta( $existing_id, 'subscriber_status', true );

        // inactiveの場合はactiveに戻す
        if ( $current_status !== 'active' ) {
            update_post_meta( $existing_id, 'subscriber_status', 'active' );
        }
        return;
    }

    // 新規購読者として登録
    $subscriber_id = wp_insert_post( array(
        'post_type'   => 'nl_subscriber',
        'post_title'  => $email,
        'post_status' => 'publish',
    ) );

    if ( is_wp_error( $subscriber_id ) ) {
        return;
    }

    // ソース名を日本語に変換
    $source_labels = array(
        'nav_button'   => '資料DL（ナビボタン）',
        'mobile_menu'  => '資料DL（モバイルメニュー）',
        'footer'       => '資料DL（フッター）',
        'page'         => '資料DL（ページ内）',
        'blog_article' => '資料DL（ブログ記事）',
    );
    $source_label = isset( $source_labels[ $source ] ) ? $source_labels[ $source ] : '資料DL';

    // メタデータを保存
    update_post_meta( $subscriber_id, 'subscriber_email', $email );
    update_post_meta( $subscriber_id, 'subscribed_at', current_time( 'mysql' ) );
    update_post_meta( $subscriber_id, 'subscriber_status', 'active' );
    update_post_meta( $subscriber_id, 'source', $source_label );
    update_post_meta( $subscriber_id, 'ip_address', corporate_seo_pro_get_user_ip() );
    update_post_meta( $subscriber_id, 'user_agent', isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '' );
}
