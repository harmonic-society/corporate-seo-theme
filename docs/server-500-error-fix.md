# 500エラー対処法

## 問題
テーマディレクトリ内の.htaccessファイルが原因で、すべてのCSS/JSファイルが500エラーになる問題が発生しました。

## 原因
WordPressのテーマディレクトリに.htaccessファイルを配置すると、サーバー設定によっては500エラーが発生する場合があります。特に：
- 共有ホスティング環境
- .htaccessの使用が制限されている環境
- PageSpeed Moduleの設定が競合する環境

## 解決方法

### 1. .htaccessファイルを削除
テーマディレクトリ内の.htaccessファイルを削除しました。

### 2. PageSpeed Module対策（別の方法）

もしPageSpeed Moduleが問題を引き起こしている場合は、WordPressの管理画面から以下を設定：

1. **プラグインで対処**
   - Cache系プラグインの設定でJavaScriptの結合・最適化を無効化
   - 特定のファイルを除外設定に追加

2. **functions.phpに追加**
```php
// PageSpeed Moduleへのヒント
add_action('wp_head', function() {
    echo '<!-- PageSpeed: Disable JavaScript optimization for contact form -->';
    echo '<!-- ModPagespeedDisallow: */contact-*.js -->';
});
```

3. **サーバー管理画面から設定**
   - cPanelやサーバー管理画面でPageSpeed Moduleの設定を調整
   - 特定のディレクトリを除外設定に追加

### 3. CSP（Content Security Policy）対策

CSPエラーが出ている場合は、functions.phpに以下を追加：

```php
// CSPヘッダーの調整
add_action('send_headers', function() {
    if (is_page_template('page-contact.php')) {
        header("Content-Security-Policy: default-src 'self' 'unsafe-inline' 'unsafe-eval' https: data: blob:;");
    }
});
```

## 今後の対策

1. **テーマディレクトリには.htaccessを置かない**
   - WordPressのルートディレクトリの.htaccessのみを使用
   - テーマ固有の設定はfunctions.phpで行う

2. **サーバー設定の確認**
   - エラーログを確認: `/wp-content/debug.log`
   - サーバーのerror_logを確認

3. **段階的なテスト**
   - 開発環境でテストしてから本番環境に適用
   - キャッシュをクリアしてから動作確認

## 確認方法

1. ブラウザのキャッシュをクリア
2. サイトを再読み込み
3. デベロッパーツールのNetworkタブで200 OKが返ることを確認