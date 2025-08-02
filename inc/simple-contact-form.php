<?php
/**
 * Simple Contact Form
 * Contact Form 7の代替として使用できるシンプルなフォーム
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * フォーム送信の処理
 */
add_action('init', 'corporate_seo_pro_handle_simple_contact_form');
function corporate_seo_pro_handle_simple_contact_form() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simple_contact_submit'])) {
        // Nonceチェック
        if (!isset($_POST['contact_nonce']) || !wp_verify_nonce($_POST['contact_nonce'], 'simple_contact_form')) {
            wp_die('セキュリティチェックに失敗しました。');
        }
        
        // データの取得とサニタイズ
        $name = sanitize_text_field($_POST['your_name']);
        $email = sanitize_email($_POST['your_email']);
        $phone = sanitize_text_field($_POST['your_phone']);
        $subject = sanitize_text_field($_POST['your_subject']);
        $message = sanitize_textarea_field($_POST['your_message']);
        
        // バリデーション
        $errors = array();
        if (empty($name)) $errors[] = 'お名前は必須です。';
        if (empty($email) || !is_email($email)) $errors[] = '有効なメールアドレスを入力してください。';
        if (empty($message)) $errors[] = 'メッセージは必須です。';
        
        if (empty($errors)) {
            // メール送信
            $to = 'koushiki@harmonic-society.co.jp';
            $mail_subject = '[お問い合わせ] ' . ($subject ?: '件名なし');
            $headers = array(
                'From: ' . $name . ' <wordpress@harmonic-society.co.jp>',
                'Reply-To: ' . $email,
                'Content-Type: text/plain; charset=UTF-8'
            );
            
            $body = "お問い合わせを受け付けました。\n\n";
            $body .= "差出人: $name\n";
            $body .= "メールアドレス: $email\n";
            if ($phone) $body .= "電話番号: $phone\n";
            $body .= "題名: " . ($subject ?: '件名なし') . "\n\n";
            $body .= "お問い合わせ内容:\n";
            $body .= $message . "\n\n";
            $body .= "--\n";
            $body .= "送信日時: " . date_i18n('Y年n月j日 G:i') . "\n";
            $body .= "送信元IP: " . $_SERVER['REMOTE_ADDR'];
            
            $mail_sent = wp_mail($to, $mail_subject, $body, $headers);
            
            if ($mail_sent) {
                // 自動返信メール
                $reply_subject = 'お問い合わせありがとうございます';
                $reply_headers = array(
                    'From: Harmonic Society <noreply@harmonic-society.co.jp>',
                    'Content-Type: text/plain; charset=UTF-8'
                );
                
                $reply_body = $name . " 様\n\n";
                $reply_body .= "この度は、Harmonic Societyへお問い合わせいただき、誠にありがとうございます。\n";
                $reply_body .= "以下の内容でお問い合わせを受け付けました。\n\n";
                $reply_body .= "-----------------------------------------\n";
                $reply_body .= "【お問い合わせ内容】\n";
                $reply_body .= "題名: " . ($subject ?: '件名なし') . "\n\n";
                $reply_body .= $message . "\n";
                $reply_body .= "-----------------------------------------\n\n";
                $reply_body .= "担当者より2営業日以内にご連絡させていただきます。\n";
                $reply_body .= "今しばらくお待ちください。\n\n";
                $reply_body .= "※このメールは自動送信されています。\n";
                $reply_body .= "※このメールアドレスは送信専用のため、返信はできません。\n\n";
                $reply_body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                $reply_body .= "Harmonic Society（ハーモニックソサエティー）\n";
                $reply_body .= "〒262-0033 千葉県千葉市花見川区幕張本郷3-31-8\n";
                $reply_body .= "TEL: 080-6946-4006\n";
                $reply_body .= "Email: koushiki@harmonic-society.co.jp\n";
                $reply_body .= "URL: https://harmonic-society.co.jp\n";
                $reply_body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                
                wp_mail($email, $reply_subject, $reply_body, $reply_headers);
                
                // thanksページへリダイレクト
                wp_redirect(home_url('/thanks/'));
                exit;
            } else {
                $errors[] = '送信に失敗しました。時間をおいて再度お試しください。';
            }
        }
        
        // エラーがある場合はセッションに保存
        if (!empty($errors)) {
            set_transient('contact_form_errors', $errors, 60);
            set_transient('contact_form_data', $_POST, 60);
            wp_redirect(wp_get_referer());
            exit;
        }
    }
}

/**
 * シンプルなコンタクトフォームを表示
 */
function corporate_seo_pro_simple_contact_form() {
    // エラーとフォームデータを取得
    $errors = get_transient('contact_form_errors');
    $form_data = get_transient('contact_form_data');
    
    // トランジェントを削除
    delete_transient('contact_form_errors');
    delete_transient('contact_form_data');
    
    ob_start();
    ?>
    <form method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" class="simple-contact-form">
        <?php wp_nonce_field('simple_contact_form', 'contact_nonce'); ?>
        
        <?php if ($errors): ?>
            <div class="form-errors">
                <?php foreach ($errors as $error): ?>
                    <p class="error-message"><?php echo esc_html($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div class="form-group">
            <label for="your_name">お名前 <span class="required">*</span></label>
            <input type="text" 
                   id="your_name" 
                   name="your_name" 
                   value="<?php echo isset($form_data['your_name']) ? esc_attr($form_data['your_name']) : ''; ?>" 
                   required>
        </div>
        
        <div class="form-group">
            <label for="your_email">メールアドレス <span class="required">*</span></label>
            <input type="email" 
                   id="your_email" 
                   name="your_email" 
                   value="<?php echo isset($form_data['your_email']) ? esc_attr($form_data['your_email']) : ''; ?>" 
                   required>
        </div>
        
        <div class="form-group">
            <label for="your_phone">電話番号</label>
            <input type="tel" 
                   id="your_phone" 
                   name="your_phone" 
                   value="<?php echo isset($form_data['your_phone']) ? esc_attr($form_data['your_phone']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="your_subject">題名</label>
            <input type="text" 
                   id="your_subject" 
                   name="your_subject" 
                   value="<?php echo isset($form_data['your_subject']) ? esc_attr($form_data['your_subject']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="your_message">メッセージ本文 <span class="required">*</span></label>
            <textarea id="your_message" 
                      name="your_message" 
                      rows="10" 
                      required><?php echo isset($form_data['your_message']) ? esc_textarea($form_data['your_message']) : ''; ?></textarea>
        </div>
        
        <div class="form-group">
            <button type="submit" name="simple_contact_submit" class="btn btn-primary">送信する</button>
        </div>
    </form>
    
    <style>
    .simple-contact-form {
        max-width: 600px;
        margin: 0 auto;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: bold;
    }
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }
    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #00867b;
    }
    .required {
        color: #dc3545;
    }
    .form-errors {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 4px;
    }
    .error-message {
        margin: 0.25rem 0;
    }
    .btn-primary {
        background-color: #00867b;
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        font-size: 1rem;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .btn-primary:hover {
        background-color: #006b61;
    }
    </style>
    <?php
    return ob_get_clean();
}

// ショートコード登録
add_shortcode('simple_contact_form', 'corporate_seo_pro_simple_contact_form');