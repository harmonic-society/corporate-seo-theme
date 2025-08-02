# Contact Form 7 エラーの最終解決策

## 問題の経緯
1. 「その他の設定」から `skip_mail: on_sent_ok` を削除して保存
2. その際にContact Form 7のデータ構造が壊れた
3. `messages`プロパティが配列ではなく文字列として保存されている

## 解決方法（優先順）

### 方法1: 新しいフォームを作成（最も簡単・確実）
1. WordPress管理画面で「お問い合わせ」→「新規追加」
2. 以下の内容をコピーして貼り付け：

```
<label> お名前 (必須)
    [text* your-name] </label>

<label> メールアドレス (必須)
    [email* your-email] </label>

<label> 題名
    [text your-subject] </label>

<label> メッセージ本文
    [textarea your-message] </label>

[submit "送信"]
```

3. メール設定：
   - 送信先: koushiki@harmonic-society.co.jp
   - 送信元: [your-name] <wordpress@example.com>
   - 題名: お問い合わせ: [your-subject]

4. 新しいショートコードを取得
5. page-contact.phpの85行目を新しいショートコードに更新

### 方法2: データベースで直接修正
1. phpMyAdminにアクセス
2. `cf7-diagnose-sql.txt`のSQLを実行して問題を特定
3. `cf7-repair-sql.txt`のSQLでデータを修復

### 方法3: Contact Form 7を再インストール
1. プラグインを無効化（データは残る）
2. プラグインを削除
3. 再度インストール
4. 有効化

### 方法4: プラグインの代替案
Contact Form 7の代わりに以下のプラグインを検討：
- WPForms Lite
- Ninja Forms
- Formidable Forms

## 緊急対応済み

テーマファイルに以下の修正を実装済み：
- `cf7-emergency-fix.php` - エラーハンドリング
- `cf7-direct-fix.php` - データベース自動修正
- `cf7-force-fix.php` - 管理画面の強制修正

これらにより、最悪でも管理画面にはアクセスできるはずです。

## 推奨アクション

**新しいフォームを作成する（方法1）が最も確実で簡単です。**

既存のフォームデータにこだわる必要がなければ、新規作成をお勧めします。