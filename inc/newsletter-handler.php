<?php
/**
 * メルマガ登録・配信ハンドラー
 *
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * カスタム投稿タイプ: メルマガ購読者
 */
function corporate_seo_pro_register_newsletter_subscriber_cpt() {
    $labels = array(
        'name'               => __( 'メルマガ購読者', 'corporate-seo-pro' ),
        'singular_name'      => __( 'メルマガ購読者', 'corporate-seo-pro' ),
        'menu_name'          => __( 'メルマガ購読者', 'corporate-seo-pro' ),
        'all_items'          => __( 'すべての購読者', 'corporate-seo-pro' ),
        'add_new'            => __( '新規追加', 'corporate-seo-pro' ),
        'add_new_item'       => __( '新規購読者を追加', 'corporate-seo-pro' ),
        'edit_item'          => __( '購読者を編集', 'corporate-seo-pro' ),
        'view_item'          => __( '購読者を表示', 'corporate-seo-pro' ),
        'search_items'       => __( '購読者を検索', 'corporate-seo-pro' ),
        'not_found'          => __( '購読者が見つかりません', 'corporate-seo-pro' ),
        'not_found_in_trash' => __( 'ゴミ箱に購読者がありません', 'corporate-seo-pro' ),
    );

    $args = array(
        'labels'              => $labels,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => 'edit.php?post_type=download_lead',
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'supports'            => array( 'title' ),
        'menu_icon'           => 'dashicons-email-alt',
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'show_in_nav_menus'   => false,
    );

    register_post_type( 'newsletter_subscriber', $args );
}
add_action( 'init', 'corporate_seo_pro_register_newsletter_subscriber_cpt' );

/**
 * メルマガ購読者一覧にカスタムカラムを追加
 */
function corporate_seo_pro_newsletter_subscriber_columns( $columns ) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = __( 'メールアドレス', 'corporate-seo-pro' );
    $new_columns['status'] = __( 'ステータス', 'corporate-seo-pro' );
    $new_columns['subscribed_at'] = __( '登録日時', 'corporate-seo-pro' );
    $new_columns['source'] = __( '登録元', 'corporate-seo-pro' );

    return $new_columns;
}
add_filter( 'manage_newsletter_subscriber_posts_columns', 'corporate_seo_pro_newsletter_subscriber_columns' );

/**
 * メルマガ購読者一覧のカスタムカラム内容
 */
function corporate_seo_pro_newsletter_subscriber_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'status':
            $status = get_post_meta( $post_id, 'subscriber_status', true );
            $status_label = $status === 'active' ? __( '有効', 'corporate-seo-pro' ) : __( '無効', 'corporate-seo-pro' );
            $status_class = $status === 'active' ? 'status-active' : 'status-inactive';
            echo '<span class="subscriber-status ' . esc_attr( $status_class ) . '">' . esc_html( $status_label ) . '</span>';
            break;

        case 'subscribed_at':
            $subscribed_at = get_post_meta( $post_id, 'subscribed_at', true );
            if ( $subscribed_at ) {
                echo esc_html( date_i18n( 'Y/m/d H:i', strtotime( $subscribed_at ) ) );
            }
            break;

        case 'source':
            $source = get_post_meta( $post_id, 'source', true );
            echo esc_html( $source ?: 'blog_cta' );
            break;
    }
}
add_action( 'manage_newsletter_subscriber_posts_custom_column', 'corporate_seo_pro_newsletter_subscriber_column_content', 10, 2 );

/**
 * メルマガ購読者一覧のステータスバッジスタイル
 */
function corporate_seo_pro_newsletter_admin_styles() {
    $screen = get_current_screen();
    if ( $screen && $screen->post_type === 'newsletter_subscriber' ) {
        echo '<style>
            .subscriber-status {
                display: inline-block;
                padding: 4px 10px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
            }
            .status-active {
                background: #d1fae5;
                color: #065f46;
            }
            .status-inactive {
                background: #fee2e2;
                color: #991b1b;
            }
        </style>';
    }
}
add_action( 'admin_head', 'corporate_seo_pro_newsletter_admin_styles' );

/**
 * AJAX: メルマガ登録
 */
function corporate_seo_pro_newsletter_subscribe() {
    // nonceチェック
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'newsletter_subscribe' ) ) {
        wp_send_json_error( array( 'message' => __( 'セキュリティエラーが発生しました', 'corporate-seo-pro' ) ) );
    }

    // メールアドレスの検証
    $email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';

    if ( ! is_email( $email ) ) {
        wp_send_json_error( array( 'message' => __( '有効なメールアドレスを入力してください', 'corporate-seo-pro' ) ) );
    }

    // 重複チェック
    $existing = get_posts( array(
        'post_type'      => 'newsletter_subscriber',
        'meta_key'       => 'subscriber_email',
        'meta_value'     => $email,
        'posts_per_page' => 1,
        'post_status'    => 'any',
    ) );

    if ( ! empty( $existing ) ) {
        // 既存の購読者がinactiveならactiveに戻す
        $existing_id = $existing[0]->ID;
        $current_status = get_post_meta( $existing_id, 'subscriber_status', true );

        if ( $current_status !== 'active' ) {
            update_post_meta( $existing_id, 'subscriber_status', 'active' );
            wp_send_json_success( array( 'message' => __( '登録が完了しました！', 'corporate-seo-pro' ) ) );
        }

        wp_send_json_error( array( 'message' => __( 'このメールアドレスは既に登録されています', 'corporate-seo-pro' ) ) );
    }

    // 新規購読者を保存
    $post_id = wp_insert_post( array(
        'post_type'   => 'newsletter_subscriber',
        'post_title'  => $email,
        'post_status' => 'publish',
    ) );

    if ( is_wp_error( $post_id ) ) {
        wp_send_json_error( array( 'message' => __( '登録に失敗しました。もう一度お試しください。', 'corporate-seo-pro' ) ) );
    }

    // メタデータを保存
    update_post_meta( $post_id, 'subscriber_email', $email );
    update_post_meta( $post_id, 'subscribed_at', current_time( 'mysql' ) );
    update_post_meta( $post_id, 'subscriber_status', 'active' );
    update_post_meta( $post_id, 'source', 'blog_cta' );
    update_post_meta( $post_id, 'ip_address', corporate_seo_pro_get_client_ip() );
    update_post_meta( $post_id, 'user_agent', isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ) : '' );

    // 管理者への通知メール
    corporate_seo_pro_notify_newsletter_subscription( $email );

    wp_send_json_success( array( 'message' => __( '登録が完了しました！', 'corporate-seo-pro' ) ) );
}
add_action( 'wp_ajax_newsletter_subscribe', 'corporate_seo_pro_newsletter_subscribe' );
add_action( 'wp_ajax_nopriv_newsletter_subscribe', 'corporate_seo_pro_newsletter_subscribe' );

/**
 * 管理者へのメルマガ登録通知
 */
function corporate_seo_pro_notify_newsletter_subscription( $email ) {
    $to = 'morota@harmonic-society.co.jp';
    $site_name = get_bloginfo( 'name' );
    $subject = sprintf( '【%s】新規メルマガ登録がありました', $site_name );

    $body = sprintf(
        "新しいメルマガ購読者が登録されました。\n\n" .
        "メールアドレス: %s\n" .
        "登録日時: %s\n\n" .
        "管理画面で確認: %s",
        $email,
        current_time( 'Y/m/d H:i:s' ),
        admin_url( 'edit.php?post_type=newsletter_subscriber' )
    );

    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $site_name . ' <wordpress@' . wp_parse_url( home_url(), PHP_URL_HOST ) . '>',
    );

    wp_mail( $to, $subject, $body, $headers );
}

/**
 * Cronスケジュールを登録
 */
function corporate_seo_pro_schedule_newsletter_cron() {
    if ( ! wp_next_scheduled( 'corporate_seo_pro_daily_newsletter' ) ) {
        // 日本時間8:00 = UTC 23:00（前日）
        // WordPressの内部タイムゾーン設定に基づいて計算
        $timezone = wp_timezone();
        $now = new DateTime( 'now', $timezone );
        $target = new DateTime( 'today 08:00:00', $timezone );

        // 既に8時を過ぎていたら明日の8時
        if ( $now > $target ) {
            $target->modify( '+1 day' );
        }

        wp_schedule_event( $target->getTimestamp(), 'daily', 'corporate_seo_pro_daily_newsletter' );
    }
}
add_action( 'wp', 'corporate_seo_pro_schedule_newsletter_cron' );

/**
 * テーマ有効化時にCronを登録
 */
function corporate_seo_pro_activate_newsletter_cron() {
    corporate_seo_pro_schedule_newsletter_cron();
}
add_action( 'after_switch_theme', 'corporate_seo_pro_activate_newsletter_cron' );

/**
 * テーマ無効化時にCronを解除
 */
function corporate_seo_pro_deactivate_newsletter_cron() {
    $timestamp = wp_next_scheduled( 'corporate_seo_pro_daily_newsletter' );
    if ( $timestamp ) {
        wp_unschedule_event( $timestamp, 'corporate_seo_pro_daily_newsletter' );
    }
}
add_action( 'switch_theme', 'corporate_seo_pro_deactivate_newsletter_cron' );

/**
 * 日次メルマガ配信
 */
function corporate_seo_pro_send_daily_newsletter() {
    // 過去24時間の新着記事を取得
    $yesterday = date( 'Y-m-d H:i:s', strtotime( '-24 hours' ) );

    $new_posts = get_posts( array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'date_query'     => array(
            array( 'after' => $yesterday ),
        ),
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ) );

    // 新着記事がなければ配信しない
    if ( empty( $new_posts ) ) {
        corporate_seo_pro_log_newsletter( 'No new posts. Newsletter skipped.' );
        return;
    }

    // アクティブな購読者を取得
    $subscribers = get_posts( array(
        'post_type'      => 'newsletter_subscriber',
        'meta_key'       => 'subscriber_status',
        'meta_value'     => 'active',
        'posts_per_page' => -1,
    ) );

    // 購読者がいなければ配信しない
    if ( empty( $subscribers ) ) {
        corporate_seo_pro_log_newsletter( 'No active subscribers. Newsletter skipped.' );
        return;
    }

    // メール本文を作成
    $site_name = get_bloginfo( 'name' );
    $site_url = home_url();
    $subject = sprintf( '【%s】本日の新着記事をお届けします', $site_name );

    $body = corporate_seo_pro_build_newsletter_html( $new_posts, $site_name, $site_url );

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $site_name . ' <morota@harmonic-society.co.jp>',
    );

    // 配信カウント
    $sent_count = 0;
    $error_count = 0;

    // 各購読者に送信
    foreach ( $subscribers as $subscriber ) {
        $email = get_post_meta( $subscriber->ID, 'subscriber_email', true );

        if ( ! is_email( $email ) ) {
            continue;
        }

        $result = wp_mail( $email, $subject, $body, $headers );

        if ( $result ) {
            $sent_count++;
        } else {
            $error_count++;
        }

        // サーバー負荷軽減のため少し待つ
        usleep( 100000 ); // 0.1秒
    }

    // ログに記録
    corporate_seo_pro_log_newsletter( sprintf(
        'Newsletter sent. Posts: %d, Subscribers: %d, Sent: %d, Errors: %d',
        count( $new_posts ),
        count( $subscribers ),
        $sent_count,
        $error_count
    ) );
}
add_action( 'corporate_seo_pro_daily_newsletter', 'corporate_seo_pro_send_daily_newsletter' );

/**
 * メルマガHTMLを生成
 */
function corporate_seo_pro_build_newsletter_html( $posts, $site_name, $site_url ) {
    $logo_url = get_theme_mod( 'custom_logo' ) ? wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'medium' ) : '';

    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo esc_html( $site_name ); ?> - 新着記事</title>
    </head>
    <body style="margin: 0; padding: 0; background-color: #f4f4f5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Noto Sans JP', sans-serif;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f5; padding: 40px 20px;">
            <tr>
                <td align="center">
                    <table width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                        <!-- ヘッダー -->
                        <tr>
                            <td style="background: linear-gradient(135deg, #00867b 0%, #10b981 100%); padding: 30px; text-align: center;">
                                <?php if ( $logo_url ) : ?>
                                    <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $site_name ); ?>" style="max-width: 180px; height: auto;">
                                <?php else : ?>
                                    <h1 style="color: #ffffff; margin: 0; font-size: 24px;"><?php echo esc_html( $site_name ); ?></h1>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <!-- メインコンテンツ -->
                        <tr>
                            <td style="padding: 40px 30px;">
                                <h2 style="color: #1f2937; margin: 0 0 10px; font-size: 22px;">本日の新着記事</h2>
                                <p style="color: #6b7280; margin: 0 0 30px; font-size: 14px;">
                                    <?php echo esc_html( date_i18n( 'Y年n月j日' ) ); ?>の更新情報をお届けします
                                </p>

                                <?php foreach ( $posts as $post ) :
                                    $permalink = get_permalink( $post->ID );
                                    $title = $post->post_title;
                                    $excerpt = wp_trim_words( $post->post_excerpt ?: wp_strip_all_tags( $post->post_content ), 60 );
                                    $thumbnail = get_the_post_thumbnail_url( $post->ID, 'medium' );
                                    $categories = get_the_category( $post->ID );
                                    $category_name = ! empty( $categories ) ? $categories[0]->name : '';
                                ?>
                                    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 25px; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden;">
                                        <?php if ( $thumbnail ) : ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo esc_url( $permalink ); ?>" style="display: block;">
                                                    <img src="<?php echo esc_url( $thumbnail ); ?>" alt="<?php echo esc_attr( $title ); ?>" style="width: 100%; height: auto; display: block;">
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <td style="padding: 20px;">
                                                <?php if ( $category_name ) : ?>
                                                    <span style="display: inline-block; background: #00867b; color: #ffffff; padding: 4px 12px; border-radius: 20px; font-size: 12px; margin-bottom: 10px;">
                                                        <?php echo esc_html( $category_name ); ?>
                                                    </span>
                                                <?php endif; ?>
                                                <h3 style="margin: 0 0 10px; font-size: 18px;">
                                                    <a href="<?php echo esc_url( $permalink ); ?>" style="color: #1f2937; text-decoration: none;">
                                                        <?php echo esc_html( $title ); ?>
                                                    </a>
                                                </h3>
                                                <p style="color: #6b7280; margin: 0 0 15px; font-size: 14px; line-height: 1.6;">
                                                    <?php echo esc_html( $excerpt ); ?>
                                                </p>
                                                <a href="<?php echo esc_url( $permalink ); ?>" style="display: inline-block; color: #00867b; font-size: 14px; font-weight: 600; text-decoration: none;">
                                                    続きを読む →
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                <?php endforeach; ?>
                            </td>
                        </tr>

                        <!-- フッター -->
                        <tr>
                            <td style="background: #f9fafb; padding: 30px; text-align: center; border-top: 1px solid #e5e7eb;">
                                <p style="color: #6b7280; margin: 0 0 15px; font-size: 13px;">
                                    このメールは<?php echo esc_html( $site_name ); ?>のメルマガに<br>ご登録いただいた方にお送りしています。
                                </p>
                                <p style="color: #9ca3af; margin: 0; font-size: 12px;">
                                    配信停止をご希望の場合はこのメールに返信してください。
                                </p>
                                <p style="color: #9ca3af; margin: 15px 0 0; font-size: 12px;">
                                    © <?php echo esc_html( date( 'Y' ) ); ?> <?php echo esc_html( $site_name ); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>
    <?php
    return ob_get_clean();
}

/**
 * メルマガ配信ログを記録
 */
function corporate_seo_pro_log_newsletter( $message ) {
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        error_log( '[Newsletter] ' . $message );
    }
}

/**
 * クライアントIPアドレスを取得
 */
if ( ! function_exists( 'corporate_seo_pro_get_client_ip' ) ) {
    function corporate_seo_pro_get_client_ip() {
        $ip_keys = array(
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
        );

        foreach ( $ip_keys as $key ) {
            if ( ! empty( $_SERVER[ $key ] ) ) {
                $ip = sanitize_text_field( $_SERVER[ $key ] );
                // カンマ区切りの場合は最初のIPを取得
                if ( strpos( $ip, ',' ) !== false ) {
                    $ip = trim( explode( ',', $ip )[0] );
                }
                if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
                    return $ip;
                }
            }
        }

        return '0.0.0.0';
    }
}

/**
 * 手動でメルマガを送信するための管理画面メニュー（オプション）
 */
function corporate_seo_pro_newsletter_admin_menu() {
    add_submenu_page(
        'edit.php?post_type=download_lead',
        __( 'メルマガ手動配信', 'corporate-seo-pro' ),
        __( 'メルマガ手動配信', 'corporate-seo-pro' ),
        'manage_options',
        'newsletter-manual-send',
        'corporate_seo_pro_newsletter_manual_send_page'
    );
}
add_action( 'admin_menu', 'corporate_seo_pro_newsletter_admin_menu' );

/**
 * メルマガ手動配信ページ
 */
function corporate_seo_pro_newsletter_manual_send_page() {
    // 送信処理
    if ( isset( $_POST['send_newsletter'] ) && check_admin_referer( 'newsletter_manual_send' ) ) {
        corporate_seo_pro_send_daily_newsletter();
        echo '<div class="notice notice-success"><p>' . esc_html__( 'メルマガを送信しました。', 'corporate-seo-pro' ) . '</p></div>';
    }

    // 次回配信予定時刻を取得
    $next_scheduled = wp_next_scheduled( 'corporate_seo_pro_daily_newsletter' );
    $next_time = $next_scheduled ? date_i18n( 'Y/m/d H:i:s', $next_scheduled ) : __( '未設定', 'corporate-seo-pro' );

    // 購読者数を取得
    $subscriber_count = wp_count_posts( 'newsletter_subscriber' );
    $active_count = get_posts( array(
        'post_type'      => 'newsletter_subscriber',
        'meta_key'       => 'subscriber_status',
        'meta_value'     => 'active',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ) );

    // 過去24時間の新着記事数を取得
    $yesterday = date( 'Y-m-d H:i:s', strtotime( '-24 hours' ) );
    $new_posts_count = count( get_posts( array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'date_query'     => array(
            array( 'after' => $yesterday ),
        ),
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ) ) );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'メルマガ手動配信', 'corporate-seo-pro' ); ?></h1>

        <div class="card" style="max-width: 600px; padding: 20px;">
            <h2><?php esc_html_e( '配信状況', 'corporate-seo-pro' ); ?></h2>

            <table class="form-table">
                <tr>
                    <th><?php esc_html_e( '次回自動配信', 'corporate-seo-pro' ); ?></th>
                    <td><?php echo esc_html( $next_time ); ?></td>
                </tr>
                <tr>
                    <th><?php esc_html_e( 'アクティブ購読者数', 'corporate-seo-pro' ); ?></th>
                    <td><?php echo esc_html( count( $active_count ) ); ?><?php esc_html_e( '人', 'corporate-seo-pro' ); ?></td>
                </tr>
                <tr>
                    <th><?php esc_html_e( '過去24時間の新着記事', 'corporate-seo-pro' ); ?></th>
                    <td><?php echo esc_html( $new_posts_count ); ?><?php esc_html_e( '件', 'corporate-seo-pro' ); ?></td>
                </tr>
            </table>

            <hr>

            <h3><?php esc_html_e( '手動配信', 'corporate-seo-pro' ); ?></h3>
            <p class="description">
                <?php esc_html_e( '過去24時間の新着記事を全アクティブ購読者に配信します。新着記事がない場合は配信されません。', 'corporate-seo-pro' ); ?>
            </p>

            <form method="post">
                <?php wp_nonce_field( 'newsletter_manual_send' ); ?>
                <p>
                    <button type="submit" name="send_newsletter" class="button button-primary" <?php echo $new_posts_count === 0 ? 'disabled' : ''; ?>>
                        <?php esc_html_e( '今すぐ配信する', 'corporate-seo-pro' ); ?>
                    </button>
                </p>
                <?php if ( $new_posts_count === 0 ) : ?>
                    <p class="description" style="color: #d63638;">
                        <?php esc_html_e( '※ 過去24時間の新着記事がないため配信できません。', 'corporate-seo-pro' ); ?>
                    </p>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <?php
}
