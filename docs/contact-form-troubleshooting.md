# Contact Form トラブルシューティングガイド

## 問題: フォームが送信できない

### 1. デバッグ情報の確認

ブラウザのコンソールを開いて（F12キー）、以下を確認してください：

1. **JavaScriptエラーの確認**
   - コンソールタブで赤いエラーメッセージがないか確認
   - 特に `contact_ajax is not defined` などのエラーに注意

2. **ネットワークタブでの確認**
   - フォーム送信時にNetworkタブを開く
   - `admin-ajax.php` へのリクエストを探す
   - ステータスコードとレスポンスを確認

### 2. PageSpeed Module の問題

サーバーでPageSpeed Moduleが有効な場合、JavaScriptファイル名が変更される可能性があります。

**対処法:**

1. `.htaccess` ファイルに以下を追加:
```apache
<IfModule pagespeed_module>
    ModPagespeedDisallow "*/contact-*.js"
    ModPagespeedDisallow "*/mobile-menu.js"
</IfModule>
```

2. キャッシュをクリア:
   - ブラウザキャッシュをクリア
   - WordPressのキャッシュプラグインがある場合はそれもクリア

### 3. デバッグモードの有効化

`wp-config.php` に以下を追加:

```php
// デバッグモードを有効化
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'SCRIPT_DEBUG', true );
```

### 4. デバッグページの使用

1. 新規ページを作成
2. ページテンプレートを「Contact Debug」に設定
3. ページを公開して表示
4. デバッグ情報とテストフォームを確認

### 5. エラーログの確認

WordPressのデバッグログを確認:
- ファイルパス: `/wp-content/debug.log`
- 「Contact form Ajax handler called」というメッセージを探す

### 6. プラグインの競合確認

1. 他のプラグインを一時的に無効化
2. 問題が解決するか確認
3. 一つずつプラグインを有効化して、競合するプラグインを特定

### 7. フォールバック版の使用

メインのJavaScriptが動作しない場合、フォールバック版が自動的に使用されます。
コンソールで以下のメッセージを確認:
- `Contact form script loaded` (メイン版)
- `Contact form fallback script loaded` (フォールバック版)

### 8. 手動テスト

以下のコードをブラウザのコンソールで実行して、Ajax通信をテスト:

```javascript
// Ajax URLの確認
console.log('Ajax URL:', contact_ajax.ajax_url);
console.log('Nonce:', contact_ajax.nonce);

// 手動でテスト送信
fetch(contact_ajax.ajax_url, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'action=submit_contact_form&contact_nonce=' + contact_ajax.nonce,
    credentials: 'same-origin'
})
.then(response => response.json())
.then(data => console.log('Response:', data))
.catch(error => console.error('Error:', error));
```

### 9. サーバー設定の確認

- PHPバージョン: 7.4以上推奨
- `max_input_vars`: 1000以上
- `post_max_size`: 8M以上
- `upload_max_filesize`: 2M以上

### 10. 一般的な解決策

1. **パーマリンクの更新**
   - 管理画面 > 設定 > パーマリンク
   - 「変更を保存」をクリック（設定は変更しなくてOK）

2. **テーマファイルの再アップロード**
   - FTPで以下のファイルを再アップロード:
     - `/assets/js/pages/contact-new.js`
     - `/assets/js/pages/contact-fallback.js`
     - `/inc/contact-form.php`
     - `/inc/contact-form-history.php`

3. **データベースの最適化**
   - phpMyAdminまたはデータベース管理ツールで最適化を実行

## それでも解決しない場合

上記の手順で解決しない場合は、以下の情報を収集してください:

1. ブラウザのコンソールログ（エラーメッセージ）
2. ネットワークタブのスクリーンショット
3. WordPressのデバッグログ（最新の20行）
4. 使用しているプラグインのリスト
5. サーバー環境（PHP版、WordPress版）

これらの情報があれば、問題の原因を特定しやすくなります。