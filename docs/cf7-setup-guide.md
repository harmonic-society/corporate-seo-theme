# Contact Form 7 設定ガイド

このガイドでは、Corporate SEO ProテーマでContact Form 7を使用して、thanksページへリダイレクトする方法を説明します。

## 1. Contact Form 7のインストール

1. WordPress管理画面にログイン
2. 「プラグイン」→「新規追加」へ移動
3. 「Contact Form 7」を検索
4. インストールして有効化

## 2. お問い合わせフォームの設定

### 基本設定

1. 「お問い合わせ」→「コンタクトフォーム」へ移動
2. 既存のフォームを編集するか、新規作成
3. フォームIDが `4f2cf0f` であることを確認（または任意のIDを使用）

### 推奨フォームテンプレート

```html
<div class="form-group">
    <label>お名前 (必須)</label>
    [text* your-name class:form-control placeholder "山田 太郎"]
</div>

<div class="form-group">
    <label>メールアドレス (必須)</label>
    [email* your-email class:form-control placeholder "example@example.com"]
</div>

<div class="form-group">
    <label>電話番号</label>
    [tel your-phone class:form-control placeholder "090-1234-5678"]
</div>

<div class="form-group">
    <label>会社名</label>
    [text company-name class:form-control placeholder "株式会社〇〇"]
</div>

<div class="form-group">
    <label>お問い合わせ種別 (必須)</label>
    [select* inquiry-type class:form-control "選択してください" "サービスについて" "お見積もり" "採用について" "その他"]
</div>

<div class="form-group">
    <label>お問い合わせ内容 (必須)</label>
    [textarea* your-message class:form-control rows:8 placeholder "お問い合わせ内容をご記入ください"]
</div>

<div class="form-group privacy-policy">
    [acceptance privacy-policy] <a href="/privacy-policy/" target="_blank">プライバシーポリシー</a>に同意する
</div>

[submit class:wpcf7-submit "送信する"]
```

## 3. メール設定

### 管理者宛メール

```
送信先: [admin_email]
送信元: [your-name] <wordpress@yourdomain.com>
題名: [お問い合わせ] [inquiry-type] - [your-name]様より
追加ヘッダー: Reply-To: [your-email]

メッセージ本文:
------------------------------
お問い合わせを受け付けました
------------------------------

■お名前
[your-name]

■メールアドレス
[your-email]

■電話番号
[your-phone]

■会社名
[company-name]

■お問い合わせ種別
[inquiry-type]

■お問い合わせ内容
[your-message]

------------------------------
送信日時: [_date] [_time]
送信元IP: [_remote_ip]
送信元URL: [_url]
------------------------------
```

### 自動返信メール

メール (2) を有効化して設定：

```
送信先: [your-email]
送信元: Harmonic Society <noreply@yourdomain.com>
題名: 【Harmonic Society】お問い合わせありがとうございます

メッセージ本文:
[your-name] 様

この度は、Harmonic Societyへお問い合わせいただき
誠にありがとうございます。

以下の内容でお問い合わせを受け付けました。
内容を確認の上、担当者より24時間以内にご連絡させていただきます。

------------------------------
■お問い合わせ内容
------------------------------

お名前: [your-name]
メールアドレス: [your-email]
電話番号: [your-phone]
会社名: [company-name]
お問い合わせ種別: [inquiry-type]

お問い合わせ内容:
[your-message]

------------------------------

なお、お急ぎの場合は下記までお電話ください。

TEL: 080-6946-4006
営業時間: 平日 9:00-18:00

━━━━━━━━━━━━━━━━━━━━
Harmonic Society
〒262-0033
千葉県千葉市花見川区幕張本郷3-31-8
TEL: 080-6946-4006
Email: koushiki@harmonic-society.co.jp
URL: https://harmonic-society.co.jp
━━━━━━━━━━━━━━━━━━━━
```

## 4. Thanksページへのリダイレクト設定

### 方法1: Contact Form 7の追加設定（推奨）

「その他の設定」タブに以下を追加：

```
on_sent_ok: "location = '/thanks/';"
```

### 方法2: フックを使用（functions.phpに追加）

```php
add_action( 'wpcf7_mail_sent', 'corporate_seo_pro_cf7_redirect' );
function corporate_seo_pro_cf7_redirect( $contact_form ) {
    $form_id = $contact_form->id();
    
    // 特定のフォームIDの場合のみリダイレクト
    if ( $form_id == '4f2cf0f' ) {
        $redirect_url = home_url( '/thanks/' );
        
        // JavaScriptでリダイレクト
        ?>
        <script type="text/javascript">
        window.location.href = '<?php echo esc_url( $redirect_url ); ?>';
        </script>
        <?php
    }
}
```

### 方法3: DOM Events（最新版推奨）

Contact Form 7の最新バージョンでは、DOM Eventsを使用：

```javascript
document.addEventListener( 'wpcf7mailsent', function( event ) {
    location = '/thanks/';
}, false );
```

## 5. Thanksページの作成

1. WordPress管理画面で「固定ページ」→「新規追加」
2. タイトル: 「お問い合わせありがとうございます」
3. スラッグ: `thanks`
4. ページテンプレート: 「Thanks」を選択
5. 内容は空のまま公開（テンプレートが自動的に内容を表示）

## 6. スタイルのカスタマイズ

必要に応じて、以下のCSSを追加してフォームの見た目を調整：

```css
/* Contact Form 7 カスタムスタイル */
.wpcf7-form .form-group {
    margin-bottom: 1.5rem;
}

.wpcf7-form label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #1f2937;
}

.wpcf7-form .form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.wpcf7-form .form-control:focus {
    outline: none;
    border-color: #00867b;
    box-shadow: 0 0 0 3px rgba(0, 134, 123, 0.1);
}

.wpcf7-form .wpcf7-submit {
    background: #00867b;
    color: white;
    padding: 0.875rem 2.5rem;
    border: none;
    border-radius: 50px;
    font-size: 1.125rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.wpcf7-form .wpcf7-submit:hover {
    background: #006b61;
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 134, 123, 0.3);
}

.wpcf7 .ajax-loader {
    margin-left: 1rem;
}

.privacy-policy {
    margin: 2rem 0;
}

.privacy-policy a {
    color: #00867b;
    text-decoration: underline;
}
```

## 7. トラブルシューティング

### リダイレクトが機能しない場合

1. プラグインのキャッシュをクリア
2. ブラウザのキャッシュをクリア
3. JavaScript エラーがないか確認（F12でコンソールを確認）
4. Contact Form 7のバージョンを確認

### メールが送信されない場合

1. SMTP設定を確認
2. WP Mail SMTP などのプラグインを使用
3. サーバーのメール送信制限を確認

### フォームの見た目が崩れる場合

1. テーマのCSSとの競合を確認
2. 上記のカスタムCSSを適用
3. ブラウザの開発者ツールでスタイルを確認

## 8. セキュリティ対策

1. **reCAPTCHA の設定**
   - Contact Form 7 → インテグレーション
   - Google reCAPTCHA v3 を設定

2. **Akismet の設定**
   - Akismet プラグインをインストール
   - API キーを設定

3. **送信制限**
   - 同一IPからの連続送信を制限
   - Honeypot フィールドを追加

## サポート

設定で問題が発生した場合は、以下をご確認ください：

- [Contact Form 7 公式ドキュメント](https://contactform7.com/docs/)
- [WordPress Codex](https://codex.wordpress.org/)
- テーマのサポートフォーラム