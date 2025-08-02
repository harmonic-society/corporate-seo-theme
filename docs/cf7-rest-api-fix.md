# Contact Form 7 REST APIエラーの解決方法

## エラーの原因

管理画面に表示される「REST APIへのアクセスに問題があります」というエラーは、以下の原因が考えられます：

1. パーマリンク設定が「基本」になっている
2. REST APIへのアクセスが制限されている
3. セキュリティプラグインによるブロック
4. .htaccessの設定問題

## 解決方法

### 1. パーマリンク設定の確認

1. WordPress管理画面 → 設定 → パーマリンク設定
2. 「基本」以外の設定を選択（推奨：投稿名）
3. 「変更を保存」をクリック

### 2. プラグインの競合確認

1. 他のセキュリティプラグインを一時的に無効化
2. Contact Form 7が正常に動作するか確認
3. 問題が解決したら、セキュリティプラグインの設定を調整

### 3. .htaccessファイルの確認

```apache
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress
```

### 4. REST APIのテスト

ブラウザで以下のURLにアクセスしてテスト：
```
https://your-domain.com/wp-json/wp/v2/
```

正常な場合はJSONデータが表示されます。

### 5. デバッグ方法

wp-config.phpに以下を追加：
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

### 6. サーバー設定の確認

- mod_rewriteが有効か確認
- PHPバージョンが7.0以上か確認
- メモリ制限が十分か確認（推奨：256M以上）

## それでも解決しない場合

1. サーバーのエラーログを確認
2. ホスティング会社に問い合わせ
3. Contact Form 7の再インストール

## 注意事項

- このテーマには自動的なREST API修正機能が含まれています
- 通常は追加の設定は不要です
- エラーが表示されてもフォーム送信は正常に動作する場合があります