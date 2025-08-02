<?php
/**
 * Contact Form History
 * お問い合わせ履歴管理機能
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * お問い合わせ履歴管理クラス
 */
class Corporate_SEO_Pro_Contact_History {
    
    /**
     * コンストラクタ
     */
    public function __construct() {
        // カスタム投稿タイプの登録
        add_action( 'init', array( $this, 'register_post_type' ) );
        
        // 管理画面のカスタマイズ
        add_filter( 'manage_contact_history_posts_columns', array( $this, 'set_custom_columns' ) );
        add_action( 'manage_contact_history_posts_custom_column', array( $this, 'custom_column_data' ), 10, 2 );
        add_filter( 'manage_edit-contact_history_sortable_columns', array( $this, 'sortable_columns' ) );
        
        // メタボックスの追加
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        
        // 検索機能の拡張
        add_filter( 'pre_get_posts', array( $this, 'extend_admin_search' ) );
        
        
        // CSVエクスポート
        add_action( 'admin_init', array( $this, 'handle_csv_export' ) );
        
        // 管理画面メニューのカスタマイズ
        add_action( 'admin_menu', array( $this, 'add_export_submenu' ) );
        
        // ステータスの変更を無効化
        add_filter( 'wp_insert_post_data', array( $this, 'force_private_status' ), 10, 2 );
    }
    
    /**
     * カスタム投稿タイプの登録
     */
    public function register_post_type() {
        $labels = array(
            'name'               => 'お問い合わせ履歴',
            'singular_name'      => 'お問い合わせ',
            'menu_name'          => 'お問い合わせ',
            'name_admin_bar'     => 'お問い合わせ',
            'add_new'            => '新規追加',
            'add_new_item'       => '新規お問い合わせを追加',
            'new_item'           => '新規お問い合わせ',
            'edit_item'          => 'お問い合わせを編集',
            'view_item'          => 'お問い合わせを表示',
            'all_items'          => '全てのお問い合わせ',
            'search_items'       => 'お問い合わせを検索',
            'not_found'          => 'お問い合わせが見つかりません',
            'not_found_in_trash' => 'ゴミ箱にお問い合わせが見つかりません'
        );
        
        $args = array(
            'labels'              => $labels,
            'public'              => false,
            'publicly_queryable'  => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => false,
            'rewrite'             => false,
            'capability_type'     => 'post',
            'capabilities'        => array(
                'create_posts' => 'do_not_allow', // 手動での新規作成を無効化
            ),
            'map_meta_cap'        => true,
            'has_archive'         => false,
            'hierarchical'        => false,
            'menu_position'       => 30,
            'menu_icon'           => 'dashicons-email-alt',
            'supports'            => array( 'title' ),
            'show_in_rest'        => false,
        );
        
        register_post_type( 'contact_history', $args );
    }
    
    /**
     * カスタムカラムの設定
     */
    public function set_custom_columns( $columns ) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = '件名';
        $new_columns['name'] = 'お名前';
        $new_columns['email'] = 'メールアドレス';
        $new_columns['company'] = '会社名';
        $new_columns['inquiry_type'] = '種別';
        $new_columns['status'] = 'ステータス';
        $new_columns['date'] = '受信日時';
        
        return $new_columns;
    }
    
    /**
     * カスタムカラムのデータ表示
     */
    public function custom_column_data( $column, $post_id ) {
        switch ( $column ) {
            case 'name':
                echo esc_html( get_post_meta( $post_id, '_contact_name', true ) );
                break;
                
            case 'email':
                $email = get_post_meta( $post_id, '_contact_email', true );
                echo '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
                break;
                
            case 'company':
                echo esc_html( get_post_meta( $post_id, '_contact_company', true ) ?: '-' );
                break;
                
            case 'inquiry_type':
                echo esc_html( get_post_meta( $post_id, '_contact_inquiry_type', true ) );
                break;
                
            case 'status':
                $status = get_post_meta( $post_id, '_contact_status', true ) ?: 'new';
                $status_labels = array(
                    'new' => '<span style="color: #0073aa;">新規</span>',
                    'read' => '<span style="color: #666;">既読</span>',
                    'replied' => '<span style="color: #46b450;">返信済</span>',
                    'closed' => '<span style="color: #999;">完了</span>',
                );
                echo $status_labels[$status] ?? $status;
                break;
        }
    }
    
    /**
     * ソート可能なカラムの設定
     */
    public function sortable_columns( $columns ) {
        $columns['name'] = 'name';
        $columns['email'] = 'email';
        $columns['inquiry_type'] = 'inquiry_type';
        $columns['status'] = 'status';
        return $columns;
    }
    
    /**
     * メタボックスの追加
     */
    public function add_meta_boxes() {
        // お問い合わせ詳細
        add_meta_box(
            'contact_details',
            'お問い合わせ詳細',
            array( $this, 'render_details_meta_box' ),
            'contact_history',
            'normal',
            'high'
        );
        
        // ステータス管理
        add_meta_box(
            'contact_status',
            'ステータス管理',
            array( $this, 'render_status_meta_box' ),
            'contact_history',
            'side',
            'high'
        );
        
        // 送信情報
        add_meta_box(
            'contact_info',
            '送信情報',
            array( $this, 'render_info_meta_box' ),
            'contact_history',
            'side',
            'default'
        );
    }
    
    /**
     * 詳細メタボックスの表示
     */
    public function render_details_meta_box( $post ) {
        $name = get_post_meta( $post->ID, '_contact_name', true );
        $email = get_post_meta( $post->ID, '_contact_email', true );
        $phone = get_post_meta( $post->ID, '_contact_phone', true );
        $company = get_post_meta( $post->ID, '_contact_company', true );
        $inquiry_type = get_post_meta( $post->ID, '_contact_inquiry_type', true );
        $subject = get_post_meta( $post->ID, '_contact_subject', true );
        $message = get_post_meta( $post->ID, '_contact_message', true );
        ?>
        <style>
            .contact-details-table {
                width: 100%;
                border-collapse: collapse;
            }
            .contact-details-table th {
                width: 150px;
                padding: 10px;
                background: #f5f5f5;
                border: 1px solid #ddd;
                text-align: left;
                font-weight: bold;
            }
            .contact-details-table td {
                padding: 10px;
                border: 1px solid #ddd;
            }
            .contact-message {
                background: #f9f9f9;
                padding: 15px;
                border: 1px solid #ddd;
                border-radius: 4px;
                white-space: pre-wrap;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                line-height: 1.6;
            }
        </style>
        
        <table class="contact-details-table">
            <tr>
                <th>お名前</th>
                <td><?php echo esc_html( $name ); ?></td>
            </tr>
            <tr>
                <th>メールアドレス</th>
                <td>
                    <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                    <a href="mailto:<?php echo esc_attr( $email ); ?>?subject=Re: <?php echo esc_attr( $subject ?: 'お問い合わせについて' ); ?>" class="button button-small" style="margin-left: 10px;">返信する</a>
                </td>
            </tr>
            <tr>
                <th>電話番号</th>
                <td><?php echo esc_html( $phone ?: '-' ); ?></td>
            </tr>
            <tr>
                <th>会社名</th>
                <td><?php echo esc_html( $company ?: '-' ); ?></td>
            </tr>
            <tr>
                <th>お問い合わせ種別</th>
                <td><?php echo esc_html( $inquiry_type ); ?></td>
            </tr>
            <tr>
                <th>件名</th>
                <td><?php echo esc_html( $subject ?: '-' ); ?></td>
            </tr>
        </table>
        
        <h4 style="margin-top: 20px;">お問い合わせ内容</h4>
        <div class="contact-message">
            <?php echo esc_html( $message ); ?>
        </div>
        <?php
    }
    
    /**
     * ステータスメタボックスの表示
     */
    public function render_status_meta_box( $post ) {
        $current_status = get_post_meta( $post->ID, '_contact_status', true ) ?: 'new';
        $notes = get_post_meta( $post->ID, '_contact_notes', true );
        
        wp_nonce_field( 'contact_status_nonce', 'contact_status_nonce' );
        ?>
        <p>
            <label for="contact_status">ステータス:</label>
            <select name="contact_status" id="contact_status" style="width: 100%;">
                <option value="new" <?php selected( $current_status, 'new' ); ?>>新規</option>
                <option value="read" <?php selected( $current_status, 'read' ); ?>>既読</option>
                <option value="replied" <?php selected( $current_status, 'replied' ); ?>>返信済</option>
                <option value="closed" <?php selected( $current_status, 'closed' ); ?>>完了</option>
            </select>
        </p>
        
        <p>
            <label for="contact_notes">メモ:</label>
            <textarea name="contact_notes" id="contact_notes" rows="4" style="width: 100%;"><?php echo esc_textarea( $notes ); ?></textarea>
        </p>
        
        <script>
        jQuery(document).ready(function($) {
            // ステータス変更時に自動保存
            $('#contact_status').on('change', function() {
                $('#publish').click();
            });
        });
        </script>
        <?php
    }
    
    /**
     * 送信情報メタボックスの表示
     */
    public function render_info_meta_box( $post ) {
        $ip = get_post_meta( $post->ID, '_contact_ip', true );
        $user_agent = get_post_meta( $post->ID, '_contact_user_agent', true );
        $referrer = get_post_meta( $post->ID, '_contact_referrer', true );
        ?>
        <p>
            <strong>IPアドレス:</strong><br>
            <?php echo esc_html( $ip ); ?>
        </p>
        
        <p>
            <strong>ユーザーエージェント:</strong><br>
            <small><?php echo esc_html( $user_agent ); ?></small>
        </p>
        
        <?php if ( $referrer ) : ?>
        <p>
            <strong>参照元:</strong><br>
            <small><?php echo esc_html( $referrer ); ?></small>
        </p>
        <?php endif; ?>
        <?php
    }
    
    /**
     * ステータスの保存
     */
    public function save_status( $post_id ) {
        if ( ! isset( $_POST['contact_status_nonce'] ) || ! wp_verify_nonce( $_POST['contact_status_nonce'], 'contact_status_nonce' ) ) {
            return;
        }
        
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
        
        if ( isset( $_POST['contact_status'] ) ) {
            update_post_meta( $post_id, '_contact_status', sanitize_text_field( $_POST['contact_status'] ) );
        }
        
        if ( isset( $_POST['contact_notes'] ) ) {
            update_post_meta( $post_id, '_contact_notes', sanitize_textarea_field( $_POST['contact_notes'] ) );
        }
    }
    
    /**
     * 検索機能の拡張
     */
    public function extend_admin_search( $query ) {
        if ( ! is_admin() || ! $query->is_main_query() ) {
            return;
        }
        
        if ( 'contact_history' !== $query->get( 'post_type' ) ) {
            return;
        }
        
        $search_term = $query->get( 's' );
        if ( $search_term ) {
            $query->set( 'meta_query', array(
                'relation' => 'OR',
                array(
                    'key'     => '_contact_name',
                    'value'   => $search_term,
                    'compare' => 'LIKE'
                ),
                array(
                    'key'     => '_contact_email',
                    'value'   => $search_term,
                    'compare' => 'LIKE'
                ),
                array(
                    'key'     => '_contact_company',
                    'value'   => $search_term,
                    'compare' => 'LIKE'
                ),
                array(
                    'key'     => '_contact_message',
                    'value'   => $search_term,
                    'compare' => 'LIKE'
                )
            ) );
        }
    }
    
    /**
     * CSVエクスポートサブメニューの追加
     */
    public function add_export_submenu() {
        add_submenu_page(
            'edit.php?post_type=contact_history',
            'エクスポート',
            'エクスポート',
            'export',
            'contact-history-export',
            array( $this, 'render_export_page' )
        );
    }
    
    /**
     * エクスポートページの表示
     */
    public function render_export_page() {
        ?>
        <div class="wrap">
            <h1>お問い合わせ履歴のエクスポート</h1>
            
            <form method="post" action="">
                <?php wp_nonce_field( 'export_contact_history', 'export_nonce' ); ?>
                <input type="hidden" name="action" value="export_contact_history">
                
                <table class="form-table">
                    <tr>
                        <th scope="row">期間</th>
                        <td>
                            <label>
                                開始日: <input type="date" name="start_date" value="<?php echo date( 'Y-m-01' ); ?>">
                            </label>
                            <label style="margin-left: 20px;">
                                終了日: <input type="date" name="end_date" value="<?php echo date( 'Y-m-d' ); ?>">
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">ステータス</th>
                        <td>
                            <label><input type="checkbox" name="status[]" value="new" checked> 新規</label>
                            <label style="margin-left: 20px;"><input type="checkbox" name="status[]" value="read" checked> 既読</label>
                            <label style="margin-left: 20px;"><input type="checkbox" name="status[]" value="replied" checked> 返信済</label>
                            <label style="margin-left: 20px;"><input type="checkbox" name="status[]" value="closed" checked> 完了</label>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <button type="submit" class="button button-primary">CSVエクスポート</button>
                </p>
            </form>
        </div>
        <?php
    }
    
    /**
     * CSVエクスポートの処理
     */
    public function handle_csv_export() {
        if ( ! isset( $_POST['action'] ) || $_POST['action'] !== 'export_contact_history' ) {
            return;
        }
        
        if ( ! isset( $_POST['export_nonce'] ) || ! wp_verify_nonce( $_POST['export_nonce'], 'export_contact_history' ) ) {
            return;
        }
        
        if ( ! current_user_can( 'export' ) ) {
            return;
        }
        
        // クエリパラメータの設定
        $args = array(
            'post_type'      => 'contact_history',
            'posts_per_page' => -1,
            'post_status'    => 'private',
        );
        
        // 期間フィルター
        if ( ! empty( $_POST['start_date'] ) && ! empty( $_POST['end_date'] ) ) {
            $args['date_query'] = array(
                array(
                    'after'     => $_POST['start_date'],
                    'before'    => $_POST['end_date'] . ' 23:59:59',
                    'inclusive' => true,
                ),
            );
        }
        
        // ステータスフィルター
        if ( ! empty( $_POST['status'] ) ) {
            $args['meta_query'] = array(
                array(
                    'key'     => '_contact_status',
                    'value'   => $_POST['status'],
                    'compare' => 'IN',
                ),
            );
        }
        
        $contacts = get_posts( $args );
        
        // CSVヘッダー
        header( 'Content-Type: text/csv; charset=UTF-8' );
        header( 'Content-Disposition: attachment; filename="contact-history-' . date( 'Y-m-d' ) . '.csv"' );
        header( 'Pragma: no-cache' );
        header( 'Expires: 0' );
        
        // BOMを出力（Excel対応）
        echo "\xEF\xBB\xBF";
        
        // ファイルポインタを開く
        $output = fopen( 'php://output', 'w' );
        
        // ヘッダー行
        fputcsv( $output, array(
            '受信日時',
            'お名前',
            'メールアドレス',
            '電話番号',
            '会社名',
            'お問い合わせ種別',
            '件名',
            'お問い合わせ内容',
            'ステータス',
            'メモ',
            'IPアドレス',
        ) );
        
        // データ行
        foreach ( $contacts as $contact ) {
            $status_labels = array(
                'new'     => '新規',
                'read'    => '既読',
                'replied' => '返信済',
                'closed'  => '完了',
            );
            
            $status = get_post_meta( $contact->ID, '_contact_status', true ) ?: 'new';
            
            fputcsv( $output, array(
                $contact->post_date,
                get_post_meta( $contact->ID, '_contact_name', true ),
                get_post_meta( $contact->ID, '_contact_email', true ),
                get_post_meta( $contact->ID, '_contact_phone', true ),
                get_post_meta( $contact->ID, '_contact_company', true ),
                get_post_meta( $contact->ID, '_contact_inquiry_type', true ),
                get_post_meta( $contact->ID, '_contact_subject', true ),
                get_post_meta( $contact->ID, '_contact_message', true ),
                $status_labels[$status] ?? $status,
                get_post_meta( $contact->ID, '_contact_notes', true ),
                get_post_meta( $contact->ID, '_contact_ip', true ),
            ) );
        }
        
        fclose( $output );
        exit;
    }
    
    /**
     * 投稿ステータスを強制的にprivateにする
     */
    public function force_private_status( $data, $postarr ) {
        if ( $data['post_type'] === 'contact_history' ) {
            $data['post_status'] = 'private';
        }
        return $data;
    }
    
    /**
     * お問い合わせを保存
     */
    public static function save_contact( $data ) {
        // タイトルの生成
        $title = sprintf(
            '[%s] %s - %s',
            date_i18n( 'Y-m-d H:i' ),
            $data['name'],
            $data['inquiry_type']
        );
        
        // 投稿データ
        $post_data = array(
            'post_title'   => $title,
            'post_type'    => 'contact_history',
            'post_status'  => 'private',
            'post_author'  => 1,
        );
        
        // 投稿を作成
        $post_id = wp_insert_post( $post_data );
        
        if ( ! is_wp_error( $post_id ) ) {
            // メタデータを保存
            update_post_meta( $post_id, '_contact_name', $data['name'] );
            update_post_meta( $post_id, '_contact_email', $data['email'] );
            update_post_meta( $post_id, '_contact_phone', $data['phone'] );
            update_post_meta( $post_id, '_contact_company', $data['company'] );
            update_post_meta( $post_id, '_contact_inquiry_type', $data['inquiry_type'] );
            update_post_meta( $post_id, '_contact_subject', $data['subject'] );
            update_post_meta( $post_id, '_contact_message', $data['message'] );
            update_post_meta( $post_id, '_contact_status', 'new' );
            update_post_meta( $post_id, '_contact_ip', $_SERVER['REMOTE_ADDR'] ?? '' );
            update_post_meta( $post_id, '_contact_user_agent', $_SERVER['HTTP_USER_AGENT'] ?? '' );
            update_post_meta( $post_id, '_contact_referrer', $_SERVER['HTTP_REFERER'] ?? '' );
            
            return $post_id;
        }
        
        return false;
    }
}

// インスタンス化
new Corporate_SEO_Pro_Contact_History();

// ステータス保存のフック
add_action( 'save_post_contact_history', array( new Corporate_SEO_Pro_Contact_History(), 'save_status' ) );