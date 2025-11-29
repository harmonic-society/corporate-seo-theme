<?php
/**
 * Custom Contact Form
 * 本格的なコンタクトフォーム機能
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * コンタクトフォームクラス
 */
class Corporate_SEO_Pro_Contact_Form {
    
    /**
     * コンストラクタ
     */
    public function __construct() {
        // ショートコード登録
        add_shortcode( 'custom_contact_form', array( $this, 'render_form' ) );
        
        // Ajax ハンドラー
        add_action( 'wp_ajax_submit_contact_form', array( $this, 'handle_ajax_submit' ) );
        add_action( 'wp_ajax_nopriv_submit_contact_form', array( $this, 'handle_ajax_submit' ) );
        
        // フォールバック（JavaScript無効時）のハンドラー
        add_action( 'init', array( $this, 'handle_fallback_submit' ) );
    }
    
    /**
     * フォームのレンダリング
     */
    public function render_form() {
        ob_start();
        ?>
        <form id="custom-contact-form" class="contact-form" method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" novalidate>
            <?php wp_nonce_field( 'contact_form_submit', 'contact_nonce' ); ?>
            <input type="hidden" name="action" value="submit_contact_form">
            
            <!-- ハニーポット（スパム対策） -->
            <div class="form-field-hp" aria-hidden="true">
                <label for="website">Website</label>
                <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
            </div>
            
            <!-- お名前 -->
            <div class="form-group">
                <label for="your-name" class="form-label">
                    お名前 <span class="required">*</span>
                </label>
                <input
                    type="text"
                    id="your-name"
                    name="your_name"
                    class="form-control"
                    required
                    autocomplete="name"
                    placeholder="例：山田 太郎"
                    data-validation="required|min:2|max:50"
                >
                <span class="error-message" role="alert"></span>
            </div>

            <!-- メールアドレス -->
            <div class="form-group">
                <label for="your-email" class="form-label">
                    メールアドレス <span class="required">*</span>
                </label>
                <input
                    type="email"
                    id="your-email"
                    name="your_email"
                    class="form-control"
                    required
                    autocomplete="email"
                    placeholder="例：info@example.com"
                    data-validation="required|email"
                >
                <span class="error-message" role="alert"></span>
            </div>
            
            <!-- 電話番号 -->
            <div class="form-group">
                <label for="your-phone" class="form-label">
                    電話番号 <span class="required">*</span>
                </label>
                <input
                    type="tel"
                    id="your-phone"
                    name="your_phone"
                    class="form-control"
                    required
                    autocomplete="tel"
                    placeholder="090-1234-5678"
                    data-validation="required|phone"
                >
                <span class="error-message" role="alert"></span>
            </div>

            <!-- 会社名 -->
            <div class="form-group">
                <label for="your-company" class="form-label">
                    会社名 <span class="required">*</span>
                </label>
                <input
                    type="text"
                    id="your-company"
                    name="your_company"
                    class="form-control"
                    required
                    autocomplete="organization"
                    placeholder="例：株式会社〇〇"
                    data-validation="required|max:100"
                >
                <span class="error-message" role="alert"></span>
            </div>

            <!-- ホームページURL -->
            <div class="form-group">
                <label for="your-url" class="form-label">
                    ホームページURL <span class="required">*</span>
                </label>
                <input
                    type="url"
                    id="your-url"
                    name="your_url"
                    class="form-control"
                    required
                    autocomplete="url"
                    placeholder="https://example.com"
                    data-validation="required|url"
                >
                <span class="error-message" role="alert"></span>
            </div>
            
            <!-- お問い合わせ種別 -->
            <div class="form-group">
                <label for="inquiry-type" class="form-label">
                    お問い合わせ種別 <span class="required">*</span>
                </label>
                <select 
                    id="inquiry-type" 
                    name="inquiry_type" 
                    class="form-control" 
                    required
                    data-validation="required"
                >
                    <option value="">選択してください</option>
                    <option value="サービスについて">サービスについて</option>
                    <option value="料金について">料金について</option>
                    <option value="お見積もり依頼">お見積もり依頼</option>
                    <option value="その他">その他</option>
                </select>
                <span class="error-message" role="alert"></span>
            </div>
            
            <!-- 題名 -->
            <div class="form-group">
                <label for="your-subject" class="form-label">
                    題名
                </label>
                <input
                    type="text"
                    id="your-subject"
                    name="your_subject"
                    class="form-control"
                    placeholder="例：サービスについてのご相談"
                    data-validation="max:100"
                >
                <span class="error-message" role="alert"></span>
            </div>
            
            <!-- メッセージ本文 -->
            <div class="form-group">
                <label for="your-message" class="form-label">
                    お問い合わせ内容 <span class="required">*</span>
                </label>
                <textarea
                    id="your-message"
                    name="your_message"
                    class="form-control"
                    rows="5"
                    required
                    placeholder="お問い合わせ内容をご記入ください"
                    data-validation="required|min:10|max:2000"
                ></textarea>
                <span class="error-message" role="alert"></span>
                <div class="character-count">
                    <span class="current">0</span> / <span class="max">2000</span> 文字
                </div>
            </div>
            
            <!-- プライバシーポリシー同意 -->
            <div class="form-group form-checkbox">
                <label class="checkbox-label">
                    <input 
                        type="checkbox" 
                        id="privacy-policy" 
                        name="privacy_policy" 
                        required
                        data-validation="required"
                    >
                    <span class="checkbox-text">
                        <a href="/privacy-policy/" target="_blank" rel="noopener">プライバシーポリシー</a>に同意する
                        <span class="required">*</span>
                    </span>
                </label>
                <span class="error-message" role="alert"></span>
            </div>
            
            <!-- 送信ボタン -->
            <div class="form-group form-submit">
                <button type="submit" class="btn btn-primary btn-submit" id="submit-button">
                    <span class="btn-text">送信する</span>
                    <span class="btn-loading" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> 送信中...
                    </span>
                </button>
            </div>
            
            <!-- メッセージエリア -->
            <div class="form-messages" role="alert" aria-live="polite"></div>
        </form>
        
        <style>
        /* フォームのスタイル */
        .contact-form {
            max-width: 100%;
        }
        
        /* ハニーポット非表示 */
        .form-field-hp {
            position: absolute;
            left: -9999px;
            height: 0;
            width: 0;
            overflow: hidden;
        }
        
        .form-group {
            margin-bottom: 0.875rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.25rem;
            font-weight: 600;
            font-size: 0.875rem;
            color: #1f2937;
        }

        .form-control {
            width: 100%;
            padding: 0.625rem 0.875rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #1f2937;
            background-color: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #00867b;
            box-shadow: 0 0 0 3px rgba(0, 134, 123, 0.1);
        }
        
        .form-control.error {
            border-color: #ef4444;
        }
        
        .form-control.valid {
            border-color: #10b981;
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }
        
        .required {
            color: #ef4444;
            font-weight: normal;
        }
        
        .error-message {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #ef4444;
            min-height: 1.25rem;
        }
        
        .character-count {
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #6b7280;
            text-align: right;
        }
        
        .character-count.warning {
            color: #f59e0b;
        }
        
        .character-count.error {
            color: #ef4444;
        }
        
        /* チェックボックス */
        .form-checkbox {
            margin-bottom: 1rem;
            background-color: #f9fafb;
            padding: 0.75rem;
            border-radius: 0.375rem;
            border: 1px solid #e5e7eb;
        }
        
        .checkbox-label {
            display: flex;
            align-items: flex-start;
            cursor: pointer;
        }
        
        .checkbox-label input[type="checkbox"] {
            flex-shrink: 0;
            width: 1.25rem;
            height: 1.25rem;
            margin-top: 0.125rem;
            margin-right: 0.5rem;
            cursor: pointer;
            -webkit-appearance: checkbox;
            -moz-appearance: checkbox;
            appearance: checkbox;
            opacity: 1 !important;
            position: relative !important;
            visibility: visible !important;
        }
        
        .checkbox-text {
            flex: 1;
            line-height: 1.5;
        }
        
        .checkbox-text a {
            color: #00867b;
            text-decoration: underline;
        }
        
        .checkbox-text a:hover {
            color: #006b61;
        }
        
        /* 送信ボタン */
        .btn-submit {
            min-width: 200px;
            padding: 0.875rem 2rem;
            font-size: 1.125rem;
            font-weight: 600;
            text-transform: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-submit:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 134, 123, 0.2);
        }
        
        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .btn-submit:active:not(:disabled) {
            transform: translateY(0);
        }
        
        /* メッセージエリア */
        .form-messages {
            margin-top: 1.5rem;
            padding: 1rem 1.5rem;
            border-radius: 0.375rem;
            display: none;
        }
        
        .form-messages.show {
            display: block;
        }
        
        .form-messages.success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }
        
        .form-messages.error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        
        /* レスポンシブ */
        @media (max-width: 768px) {
            .form-group {
                margin-bottom: 1.5rem;
            }
            
            .btn-submit {
                width: 100%;
            }
        }
        </style>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Ajax送信の処理
     */
    public function handle_ajax_submit() {
        // Nonceチェック
        if ( ! isset( $_POST['contact_nonce'] ) || ! wp_verify_nonce( $_POST['contact_nonce'], 'contact_form_submit' ) ) {
            wp_send_json_error( array( 'message' => 'セキュリティエラーが発生しました。' ) );
        }
        
        // フォームデータの処理
        $result = $this->process_form_data( $_POST );
        
        if ( $result['success'] ) {
            wp_send_json_success( $result );
        } else {
            wp_send_json_error( $result );
        }
    }
    
    /**
     * フォールバック送信の処理
     */
    public function handle_fallback_submit() {
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['action'] ) && $_POST['action'] === 'submit_contact_form' && ! wp_doing_ajax() ) {
            // Nonceチェック
            if ( ! isset( $_POST['contact_nonce'] ) || ! wp_verify_nonce( $_POST['contact_nonce'], 'contact_form_submit' ) ) {
                wp_die( 'セキュリティエラーが発生しました。' );
            }
            
            $result = $this->process_form_data( $_POST );
            
            if ( $result['success'] ) {
                wp_redirect( home_url( '/thanks/' ) );
                exit;
            } else {
                // エラーメッセージをセッションに保存
                set_transient( 'contact_form_errors', $result['errors'], 60 );
                set_transient( 'contact_form_data', $_POST, 60 );
                wp_redirect( wp_get_referer() );
                exit;
            }
        }
    }
    
    /**
     * フォームデータの処理
     */
    private function process_form_data( $data ) {
        // ハニーポットチェック
        if ( ! empty( $data['website'] ) ) {
            return array(
                'success' => false,
                'message' => 'スパムとして検出されました。'
            );
        }
        
        // データのサニタイズ
        $name = sanitize_text_field( $data['your_name'] ?? '' );
        $email = sanitize_email( $data['your_email'] ?? '' );
        $phone = sanitize_text_field( $data['your_phone'] ?? '' );
        $company = sanitize_text_field( $data['your_company'] ?? '' );
        $url = isset( $data['your_url'] ) ? esc_url_raw( $data['your_url'] ) : '';
        $inquiry_type = sanitize_text_field( $data['inquiry_type'] ?? '' );
        $subject = sanitize_text_field( $data['your_subject'] ?? '' );
        $message = sanitize_textarea_field( $data['your_message'] ?? '' );
        $privacy_policy = isset( $data['privacy_policy'] ) ? true : false;
        
        // バリデーション
        $errors = array();
        
        if ( empty( $name ) || strlen( $name ) < 2 ) {
            $errors['your_name'] = 'お名前を正しく入力してください。';
        }
        
        if ( empty( $email ) || ! is_email( $email ) ) {
            $errors['your_email'] = '有効なメールアドレスを入力してください。';
        }

        if ( empty( $phone ) ) {
            $errors['your_phone'] = '電話番号を入力してください。';
        } elseif ( ! preg_match( '/^[0-9\-\+\(\)\s]+$/', $phone ) ) {
            $errors['your_phone'] = '有効な電話番号を入力してください。';
        }

        if ( empty( $company ) ) {
            $errors['your_company'] = '会社名を入力してください。';
        }

        if ( empty( $url ) ) {
            $errors['your_url'] = 'ホームページURLを入力してください。';
        } elseif ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
            $errors['your_url'] = '有効なURLを入力してください。';
        }

        if ( empty( $inquiry_type ) ) {
            $errors['inquiry_type'] = 'お問い合わせ種別を選択してください。';
        }
        
        if ( empty( $message ) || strlen( $message ) < 10 ) {
            $errors['your_message'] = 'お問い合わせ内容を10文字以上で入力してください。';
        }
        
        if ( strlen( $message ) > 2000 ) {
            $errors['your_message'] = 'お問い合わせ内容は2000文字以内で入力してください。';
        }
        
        if ( ! $privacy_policy ) {
            $errors['privacy_policy'] = 'プライバシーポリシーに同意してください。';
        }
        
        // エラーがある場合
        if ( ! empty( $errors ) ) {
            return array(
                'success' => false,
                'errors' => $errors,
                'message' => '入力内容に誤りがあります。'
            );
        }
        
        // データの準備
        $contact_data = array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'company' => $company,
            'url' => $url,
            'inquiry_type' => $inquiry_type,
            'subject' => $subject,
            'message' => $message,
        );
        
        // お問い合わせ履歴を保存（メール送信前に保存）
        if ( class_exists( 'Corporate_SEO_Pro_Contact_History' ) ) {
            $history_id = Corporate_SEO_Pro_Contact_History::save_contact( $contact_data );
        }
        
        // メール送信
        $mail_sent = $this->send_emails( $contact_data );
        
        if ( $mail_sent ) {
            return array(
                'success' => true,
                'message' => 'お問い合わせを受け付けました。',
                'redirect' => home_url( '/thanks/' )
            );
        } else {
            // メール送信に失敗してもデータは保存されているので、thanksページへ遷移
            return array(
                'success' => true,
                'message' => 'お問い合わせを受け付けました。',
                'redirect' => home_url( '/thanks/' ),
                'warning' => 'メール送信に失敗した可能性があります。'
            );
        }
    }
    
    /**
     * メール送信
     */
    private function send_emails( $data ) {
        // 管理者向けメール
        $admin_email = 'koushiki@harmonic-society.co.jp';
        $admin_subject = '[お問い合わせ] ' . $data['inquiry_type'];
        if ( ! empty( $data['subject'] ) ) {
            $admin_subject .= ' - ' . $data['subject'];
        }
        
        $admin_message = $this->get_admin_email_template( $data );
        $admin_headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'From: ' . $data['name'] . ' <wordpress@harmonic-society.co.jp>',
            'Reply-To: ' . $data['email'],
        );
        
        $admin_sent = wp_mail( $admin_email, $admin_subject, $admin_message, $admin_headers );
        
        // 自動返信メール
        if ( $admin_sent ) {
            $user_subject = 'お問い合わせありがとうございます';
            $user_message = $this->get_user_email_template( $data );
            $user_headers = array(
                'Content-Type: text/plain; charset=UTF-8',
                'From: Harmonic Society <noreply@harmonic-society.co.jp>',
            );
            
            wp_mail( $data['email'], $user_subject, $user_message, $user_headers );
        }
        
        return $admin_sent;
    }
    
    /**
     * 管理者向けメールテンプレート
     */
    private function get_admin_email_template( $data ) {
        $message = "お問い合わせを受け付けました。\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "■ お客様情報\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "お名前: " . $data['name'] . "\n";
        $message .= "メールアドレス: " . $data['email'] . "\n";
        if ( ! empty( $data['phone'] ) ) {
            $message .= "電話番号: " . $data['phone'] . "\n";
        }
        if ( ! empty( $data['company'] ) ) {
            $message .= "会社名: " . $data['company'] . "\n";
        }
        if ( ! empty( $data['url'] ) ) {
            $message .= "ホームページURL: " . $data['url'] . "\n";
        }
        $message .= "\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "■ お問い合わせ内容\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "種別: " . $data['inquiry_type'] . "\n";
        if ( ! empty( $data['subject'] ) ) {
            $message .= "題名: " . $data['subject'] . "\n";
        }
        $message .= "\n" . $data['message'] . "\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "送信日時: " . date_i18n( 'Y年n月j日 G:i' ) . "\n";
        $message .= "送信元IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
        $message .= "ユーザーエージェント: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
        
        return $message;
    }
    
    /**
     * ユーザー向けメールテンプレート
     */
    private function get_user_email_template( $data ) {
        $message = $data['name'] . " 様\n\n";
        $message .= "この度は、Harmonic Societyへお問い合わせいただき、\n";
        $message .= "誠にありがとうございます。\n\n";
        $message .= "以下の内容でお問い合わせを受け付けました。\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "【お問い合わせ内容】\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "種別: " . $data['inquiry_type'] . "\n";
        if ( ! empty( $data['subject'] ) ) {
            $message .= "題名: " . $data['subject'] . "\n";
        }
        $message .= "\n" . $data['message'] . "\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "担当者より2営業日以内にご連絡させていただきます。\n";
        $message .= "今しばらくお待ちください。\n\n";
        $message .= "※このメールは自動送信されています。\n";
        $message .= "※このメールアドレスは送信専用のため、返信はできません。\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "Harmonic Society（ハーモニックソサエティー）\n";
        $message .= "〒262-0033 千葉県千葉市花見川区幕張本郷3-31-8\n";
        $message .= "TEL: 080-6946-4006\n";
        $message .= "Email: koushiki@harmonic-society.co.jp\n";
        $message .= "URL: https://harmonic-society.co.jp\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
        return $message;
    }
}

// インスタンス化
new Corporate_SEO_Pro_Contact_Form();