# Contact Form 7 データベース破損の完全修復手順

## 問題の詳細
プラグインを再インストールしても解決しないということは、データベースに破損したデータが残っていることが原因です。

## 原因
- `wp_options`テーブルに破損したContact Form 7の設定が残っている
- 言語ファイルのキャッシュが破損している
- テンプレートデータが文字列として保存されている

## 完全修復手順

### ステップ1: Contact Form 7を無効化
FTPまたはファイルマネージャーで：
```
/wp-content/plugins/contact-form-7/
```
フォルダの名前を一時的に変更：
```
/wp-content/plugins/contact-form-7-disabled/
```

### ステップ2: データベースの完全クリーンアップ

phpMyAdminで以下のSQLを**順番に**実行：

```sql
-- 1. すべてのContact Form 7のデータを確認
SELECT option_name, option_value 
FROM wp_options 
WHERE option_name LIKE '%wpcf7%' 
   OR option_name LIKE '%cf7%'
   OR option_name LIKE '%contact-form-7%';

-- 2. Contact Form 7のすべてのオプションを削除
DELETE FROM wp_options 
WHERE option_name LIKE '%wpcf7%' 
   OR option_name LIKE '%cf7%'
   OR option_name LIKE '%contact-form-7%';

-- 3. フォームの投稿データを削除
DELETE FROM wp_posts WHERE post_type = 'wpcf7_contact_form';

-- 4. 孤立したpostmetaを削除
DELETE FROM wp_postmeta 
WHERE post_id NOT IN (SELECT ID FROM wp_posts);

-- 5. トランジェントを削除
DELETE FROM wp_options 
WHERE option_name LIKE '_transient_%wpcf7%' 
   OR option_name LIKE '_transient_timeout_%wpcf7%';

-- 6. サイトトランジェントを削除
DELETE FROM wp_options 
WHERE option_name LIKE '_site_transient_%wpcf7%' 
   OR option_name LIKE '_site_transient_timeout_%wpcf7%';

-- 7. ユーザーメタからCF7関連データを削除
DELETE FROM wp_usermeta 
WHERE meta_key LIKE '%wpcf7%' 
   OR meta_key LIKE '%contact-form-7%';
```

### ステップ3: WordPressのキャッシュをクリア

1. 以下のフォルダ内のファイルを削除（フォルダ自体は残す）：
   - `/wp-content/cache/`
   - `/wp-content/uploads/wpcf7_uploads/`（存在する場合）

2. `.htaccess`ファイルをバックアップして、デフォルトに戻す：
```apache
# BEGIN WordPress
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
# END WordPress
```

### ステップ4: 言語ファイルのリセット

FTPで以下のファイルを削除：
```
/wp-content/languages/plugins/contact-form-7-ja.mo
/wp-content/languages/plugins/contact-form-7-ja.po
```

### ステップ5: Contact Form 7の再インストール

1. プラグインフォルダ名を元に戻す：
   ```
   /wp-content/plugins/contact-form-7/
   ```

2. WordPress管理画面にアクセス

3. プラグインを有効化

### それでもダメな場合：シンプルフォームを使用

`page-contact.php`の85行目を以下に変更：

```php
// echo do_shortcode('[contact-form-7 id="4f2cf0f" title="お問い合わせフォーム"]');
echo do_shortcode('[simple_contact_form]');
```

`functions.php`に追加：
```php
// シンプルコンタクトフォームを読み込み
require_once get_template_directory() . '/inc/simple-contact-form.php';
```

## 最終手段：手動でテーブルを作成

もしContact Form 7がどうしても動かない場合、手動でフォームデータを作成：

```sql
-- 新しいフォームを手動で作成
INSERT INTO wp_posts (
    post_author, 
    post_date, 
    post_date_gmt, 
    post_content, 
    post_title, 
    post_status, 
    post_type, 
    post_name
) VALUES (
    1,
    NOW(),
    NOW(),
    'a:7:{s:7:"subject";s:25:"[your-subject]";s:6:"sender";s:34:"[your-name] <wordpress@example.com>";s:4:"body";s:235:"From: [your-name] <[your-email]>\nSubject: [your-subject]\n\nMessage Body:\n[your-message]\n\n--\nThis e-mail was sent from a contact form on Your Site";s:9:"recipient";s:29:"koushiki@harmonic-society.co.jp";s:18:"additional_headers";s:22:"Reply-To: [your-email]";s:11:"attachments";s:0:"";s:8:"use_html";i:0;}',
    'Contact form 1',
    'publish',
    'wpcf7_contact_form',
    'contact-form-1'
);
```

## 推奨事項

データベースが深刻に破損している場合は、**シンプルコンタクトフォーム**（すでに作成済み）を使用することを強く推奨します。