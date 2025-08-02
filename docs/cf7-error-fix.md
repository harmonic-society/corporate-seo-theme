# Contact Form 7 エラー修正手順

## エラーの原因
Contact Form 7の「その他の設定」に不正な形式の設定（`skip_mail: on_sent_ok`）があるため、管理画面でエラーが発生しています。

## 最新の修正内容
テーマに以下の修正を追加しました：
1. functions.phpにメッセージ配列の修正処理を追加
2. cf7-emergency-fix.phpで管理画面のエラーを回避
3. データベースの自動修正機能

## 緊急修正手順

### 方法1: データベースから直接修正（推奨）

1. phpMyAdminまたはデータベース管理ツールにアクセス
2. `wp_posts`テーブルを開く
3. 以下のSQLを実行してフォームの設定を確認：
   ```sql
   SELECT ID, post_title, post_content 
   FROM wp_posts 
   WHERE post_type = 'wpcf7_contact_form' 
   AND post_title LIKE '%お問い合わせ%';
   ```

4. 該当するフォーム（ID: 4f2cf0fに相当するもの）のpost_contentを編集
5. `additional_settings`セクションから`skip_mail: on_sent_ok`の行を削除
6. 保存

### 方法2: プラグインを一時的に無効化

1. FTPまたはファイルマネージャーで以下のディレクトリにアクセス：
   `/wp-content/plugins/`
2. `contact-form-7`フォルダを`contact-form-7-temp`にリネーム
3. WordPress管理画面にアクセス
4. プラグインを再度有効化
5. フォームの設定を修正

### 方法3: functions.phpで一時的に修正

functions.phpに以下のコードを追加（緊急対応のみ）：

```php
// Contact Form 7の不正な設定を修正
add_filter('wpcf7_contact_form_properties', function($properties, $contact_form) {
    if (isset($properties['additional_settings'])) {
        // skip_mail: on_sent_okの行を削除
        $properties['additional_settings'] = preg_replace(
            '/skip_mail:\s*on_sent_ok\s*\n?/i', 
            '', 
            $properties['additional_settings']
        );
    }
    return $properties;
}, 10, 2);
```

## 修正後の確認

1. Contact Form 7の管理画面にアクセスできることを確認
2. 「その他の設定」タブが空になっていることを確認
3. フォームの動作をテスト

## 今後の対策

1. Contact Form 7の最新バージョンを使用
2. 「その他の設定」には何も記入しない（必要な機能はfunctions.phpで実装）
3. 定期的なバックアップの実施