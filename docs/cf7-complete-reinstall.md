# Contact Form 7 完全再インストール手順

## 状況
Contact Form 7が深刻なレベルで壊れており、新規追加も機能しません。
完全な再インストールが必要です。

## 手順

### 1. Contact Form 7を完全に削除

#### 方法A: FTP/ファイルマネージャー経由（推奨）
1. FTPまたはファイルマネージャーでサーバーにアクセス
2. `/wp-content/plugins/contact-form-7/` フォルダを**完全に削除**
3. WordPress管理画面の「プラグイン」ページをリロード

#### 方法B: 管理画面から（エラーが出る場合は不可）
1. プラグイン一覧で「Contact Form 7」を無効化
2. 「削除」をクリック

### 2. データベースのクリーンアップ

phpMyAdminで以下のSQLを実行：

```sql
-- Contact Form 7のデータを完全に削除
-- 注意: すべてのフォームデータが削除されます！

-- 1. フォームデータを削除
DELETE FROM wp_posts WHERE post_type = 'wpcf7_contact_form';

-- 2. メタデータを削除
DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT ID FROM wp_posts);

-- 3. オプションを削除
DELETE FROM wp_options WHERE option_name LIKE '%wpcf7%';
DELETE FROM wp_options WHERE option_name LIKE '%cf7%';

-- 4. ゴミ箱のデータも削除
DELETE FROM wp_posts WHERE post_type = 'wpcf7_contact_form' AND post_status = 'trash';
```

### 3. キャッシュのクリア
1. ブラウザのキャッシュをクリア
2. WordPressのキャッシュプラグインがある場合はクリア
3. サーバーのキャッシュもクリア（可能であれば）

### 4. Contact Form 7の新規インストール
1. WordPress管理画面「プラグイン」→「新規追加」
2. 「Contact Form 7」を検索
3. 「今すぐインストール」→「有効化」

### 5. 新しいフォームの作成
1. 「お問い合わせ」→「新規追加」
2. 以下の最小構成で作成：

```
[text* your-name]
[email* your-email]
[textarea* your-message]
[submit "送信"]
```

3. メール設定は最小限に：
   - 送信先: admin@example.com（実際のメールアドレス）
   - その他はデフォルトのまま

### 6. 代替案：別のフォームプラグイン

Contact Form 7の代わりに以下を検討：

#### WPForms Lite（推奨）
- 初心者に優しい
- ドラッグ&ドロップ
- 基本機能は無料

#### Ninja Forms
- 高機能
- 拡張性が高い
- 基本機能は無料

#### シンプルなPHPフォーム
テーマに直接実装する方法（プラグイン不要）：

```php
// functions.phpに追加
function handle_custom_contact_form() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['custom_contact_submit'])) {
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $message = sanitize_textarea_field($_POST['message']);
        
        $to = get_option('admin_email');
        $subject = 'お問い合わせ: ' . $name;
        $body = "名前: $name\nメール: $email\n\nメッセージ:\n$message";
        
        if (wp_mail($to, $subject, $body)) {
            wp_redirect(home_url('/thanks/'));
            exit;
        }
    }
}
add_action('init', 'handle_custom_contact_form');
```

### 7. 緊急対応

今すぐフォームが必要な場合：

1. **Googleフォームを埋め込む**
   - Googleフォームを作成
   - 埋め込みコードを取得
   - page-contact.phpに貼り付け

2. **メールリンクのみ設置**
   ```html
   <a href="mailto:koushiki@harmonic-society.co.jp" class="btn btn-primary">
       メールでお問い合わせ
   </a>
   ```

## 推奨アクション

1. **Contact Form 7を完全削除**（FTP経由）
2. **WPForms Liteをインストール**
3. 新しいフォームを作成
4. 動作確認

これで確実に動作するフォームが設置できます。