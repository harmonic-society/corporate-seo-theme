# Contact Form 7 新規フォーム作成手順書

## 1. 新規フォームの作成

### ステップ1: 管理画面にアクセス
1. WordPress管理画面にログイン
2. 左メニューから「お問い合わせ」→「新規追加」をクリック

### ステップ2: フォーム名の設定
- タイトル欄に「お問い合わせフォーム」と入力

### ステップ3: フォームの内容を設定
以下のコードを「フォーム」タブに貼り付けてください：

```html
<label> お名前 (必須)
    [text* your-name autocomplete:name] </label>

<label> メールアドレス (必須)
    [email* your-email autocomplete:email] </label>

<label> 電話番号
    [tel your-phone autocomplete:tel] </label>

<label> 題名
    [text your-subject] </label>

<label> メッセージ本文 (必須)
    [textarea* your-message] </label>

[submit "送信する"]
```

## 2. メール設定

「メール」タブをクリックして、以下の設定を行います：

### 送信先設定
- **送信先**: `koushiki@harmonic-society.co.jp`
- **送信元**: `[your-name] <wordpress@harmonic-society.co.jp>`
- **題名**: `[お問い合わせ] [your-subject]`
- **追加ヘッダー**: `Reply-To: [your-email]`

### メッセージ本文
以下をコピーして貼り付け：

```
お問い合わせを受け付けました。

差出人: [your-name]
メールアドレス: [your-email]
電話番号: [your-phone]
題名: [your-subject]

お問い合わせ内容:
[your-message]

--
このメールは https://harmonic-society.co.jp のお問い合わせフォームから送信されました。
送信日時: [_date] [_time]
送信元IP: [_remote_ip]
```

### メール（2）設定（自動返信メール）
「メール（2）を使用」にチェックを入れて、以下を設定：

- **送信先**: `[your-email]`
- **送信元**: `Harmonic Society <noreply@harmonic-society.co.jp>`
- **題名**: `お問い合わせありがとうございます`
- **メッセージ本文**:

```
[your-name] 様

この度は、Harmonic Societyへお問い合わせいただき、誠にありがとうございます。
以下の内容でお問い合わせを受け付けました。

-----------------------------------------
【お問い合わせ内容】
題名: [your-subject]

[your-message]
-----------------------------------------

担当者より2営業日以内にご連絡させていただきます。
今しばらくお待ちください。

※このメールは自動送信されています。
※このメールアドレスは送信専用のため、返信はできません。

━━━━━━━━━━━━━━━━━━━━━━━━━━━
Harmonic Society（ハーモニックソサエティー）
〒262-0033 千葉県千葉市花見川区幕張本郷3-31-8
TEL: 080-6946-4006
Email: koushiki@harmonic-society.co.jp
URL: https://harmonic-society.co.jp
━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

## 3. メッセージ設定

「メッセージ」タブは**何も変更しません**（デフォルトのまま）。

## 4. その他の設定

「その他の設定」タブは**空欄のまま**にしてください。
**何も入力しないでください。**

## 5. 保存とショートコードの取得

1. 画面右上の「保存」ボタンをクリック
2. 保存後、画面上部に表示される青いボックス内のショートコードをコピー
   - 例: `[contact-form-7 id="123" title="お問い合わせフォーム"]`

## 6. テーマファイルの更新

1. `page-contact.php`ファイルを開く
2. 85行目の以下の部分を探す：
   ```php
   echo do_shortcode('[contact-form-7 id="4f2cf0f" title="お問い合わせフォーム"]');
   ```
3. 新しいショートコードに置き換える：
   ```php
   echo do_shortcode('[contact-form-7 id="新しいID" title="お問い合わせフォーム"]');
   ```

## 7. 動作確認

1. お問い合わせページ（https://harmonic-society.co.jp/contact/）にアクセス
2. フォームが正しく表示されることを確認
3. テスト送信を行う：
   - お名前: テスト
   - メールアドレス: あなたのメールアドレス
   - 題名: テスト送信
   - メッセージ: これはテストです
4. 以下を確認：
   - 管理者メール（koushiki@harmonic-society.co.jp）に通知が届く
   - 入力したメールアドレスに自動返信が届く
   - thanksページにリダイレクトされる

## 注意事項

- **その他の設定には何も入力しない**（skip_mailなどは絶対に入力しない）
- メールアドレスのドメイン部分は実際のドメインと一致させる
- SPF/DKIM設定がある場合は、送信元メールアドレスに注意
- テスト送信は必ず実施する

## トラブルシューティング

### フォームが表示されない場合
- ショートコードが正しくコピーされているか確認
- Contact Form 7プラグインが有効になっているか確認

### メールが届かない場合
- 迷惑メールフォルダを確認
- WP Mail SMTPなどのSMTPプラグインの導入を検討
- サーバーのメール送信制限を確認

### リダイレクトされない場合
- ブラウザのコンソールでJavaScriptエラーを確認
- テーマのfunctions.phpでリダイレクト設定が有効か確認