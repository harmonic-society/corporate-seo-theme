# Contact Form 7 トラブルシューティングガイド

## フォーム送信ができない場合のチェックリスト

### 1. ブラウザのコンソールでエラーを確認

1. F12キーを押して開発者ツールを開く
2. 「Console」タブを選択
3. 赤いエラーメッセージがないか確認
4. フォーム送信を試みて、新しいエラーが表示されるか確認

### 2. Contact Form 7の正しいフォーム設定

フォームタブに以下の形式で入力されていることを確認：

```html
<div class="form-group">
<label for="inquiry-type">お問い合わせの種類 <span class="required">*</span></label>
[select* inquiry-type id:inquiry-type class:form-control "選択してください" "無料相談のお申込み" "サービスに関するお問い合わせ"]
</div>
```

**重要**: 
- JavaScriptコードをフォームタブに入れない
- フィールド名に日本語を使わない
- 各フィールドにIDとクラスを設定

### 3. プラグインの競合確認

1. 他のプラグインを一時的に無効化
2. Contact Form 7のみ有効な状態でテスト
3. 問題が解決したら、プラグインを1つずつ有効化して原因を特定

### 4. REST APIの確認

WordPressのREST APIが有効になっているか確認：

```javascript
// ブラウザのコンソールで実行
fetch('/wp-json/contact-form-7/v1/')
  .then(response => response.json())
  .then(data => console.log(data))
  .catch(error => console.error('REST API Error:', error));
```

### 5. メール設定の確認

#### SMTP設定
- サーバーのメール送信機能が有効か確認
- WP Mail SMTPなどのプラグインを使用することを推奨

#### テストメール送信
```php
// functions.phpに一時的に追加してテスト
add_action('init', function() {
    if (isset($_GET['test_mail'])) {
        $result = wp_mail('your-email@example.com', 'Test', 'Test message');
        var_dump($result);
        exit;
    }
});
```

### 6. パーミッションの確認

- wp-content/uploadsディレクトリが書き込み可能か確認（755または775）
- .htaccessファイルの設定を確認

### 7. デバッグモードの有効化

wp-config.phpに以下を追加：

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', true);
```

### 8. Contact Form 7のバージョン確認

- 最新バージョンにアップデート
- WordPressのバージョンとの互換性を確認

### 9. フォームIDの確認

```php
// ショートコードのIDが正しいか確認
[contact-form-7 id="4f2cf0f" title="お問い合わせフォーム"]
```

### 10. JavaScriptの競合確認

```javascript
// コンソールで実行
console.log(typeof wpcf7);  // 'object'が返されるべき
console.log(wpcf7.apiSettings);  // API設定が表示されるべき
```

## よくある問題と解決方法

### 問題: 「メッセージの送信に失敗しました」エラー

**解決方法**:
1. メールアドレスが正しいか確認
2. サーバーのメール送信機能を確認
3. WP Mail SMTPプラグインをインストール

### 問題: フォームが表示されない

**解決方法**:
1. ショートコードが正しく配置されているか確認
2. Contact Form 7が有効化されているか確認
3. テーマのpage-contact.phpファイルを確認

### 問題: 送信ボタンを押しても何も起こらない

**解決方法**:
1. JavaScriptエラーがないか確認
2. フォームのバリデーションエラーを確認
3. 必須フィールドがすべて入力されているか確認

### 問題: リダイレクトが機能しない

**解決方法**:
1. functions.phpのリダイレクトコードを確認
2. thanksページが存在するか確認
3. JavaScriptエラーがないか確認

## デバッグ用コード

ブラウザのコンソールで以下を実行してフォームの状態を確認：

```javascript
// フォームデータの確認
window.testCF7Form();

// イベントリスナーの確認
document.addEventListener('wpcf7mailsent', function(e) {
    console.log('Mail sent event fired!', e);
});

// フォームの送信状態を確認
const form = document.querySelector('.wpcf7-form');
console.log('Form classes:', form.className);
console.log('Form data-status:', form.getAttribute('data-status'));
```

## サポート

問題が解決しない場合は、以下の情報を準備してサポートに連絡：

1. WordPressのバージョン
2. Contact Form 7のバージョン
3. PHPのバージョン
4. エラーメッセージの全文
5. ブラウザのコンソールログ
6. デバッグログ（wp-content/debug.log）