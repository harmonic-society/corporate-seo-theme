# Claude プロジェクトガイドライン

## プロジェクト概要

WordPress テーマ「Corporate SEO Pro」 - SEO最適化、パフォーマンス、日本市場サポートに特化した企業向けWordPressテーマ

## ブランドアイデンティティ

### ブランドカラー
- **プライマリカラー**: `#00867b` - すべてのプロジェクトでこの色を基調として使用すること
- **セカンダリカラー**: `#10b981` - アクセントとして使用
- ブランドカラーの派生色は、プライマリカラーの明度・彩度を調整して作成する
- 例：
  - ライト: `rgba(0, 134, 123, 0.1)` (10% opacity)
  - ミディアム: `rgba(0, 134, 123, 0.5)` (50% opacity)
  - ダーク: `#006b61` (darker shade)

## コーディング規約

### WordPress テーマ開発規約

1. **命名規則**
   - PHPファイル: kebab-case (例: `theme-setup.php`)
   - PHP関数: snake_case with prefix (例: `corporate_seo_pro_setup()`)
   - CSS クラス: kebab-case (例: `service-grid-item`)
   - JavaScript関数: camelCase (例: `handleMobileMenu()`)
   - 定数: UPPER_SNAKE_CASE (例: `CORPORATE_SEO_PRO_VERSION`)

2. **WordPress コーディング標準**
   - WordPress Coding Standards に準拠
   - プレフィックスの使用: `corporate_seo_pro_`
   - テキストドメイン: `corporate-seo-pro`

3. **エスケープとサニタイズ**
   ```php
   // 出力時は必ずエスケープ
   echo esc_html( $title );
   echo esc_url( $link );
   echo esc_attr( $attribute );
   
   // 入力値のサニタイズ
   $clean_data = sanitize_text_field( $_POST['data'] );
   ```

### ファイル構造の整理

```
corporate-seo-theme/
├── assets/
│   ├── css/
│   │   ├── base/              # 基本スタイル
│   │   │   ├── base.css       # リセット・基本設定
│   │   │   ├── typography.css # タイポグラフィ
│   │   │   └── responsive.css # レスポンシブ設定
│   │   ├── components/        # コンポーネント別CSS
│   │   │   ├── navigation.css
│   │   │   ├── buttons.css
│   │   │   ├── forms.css
│   │   │   ├── hero.css
│   │   │   └── mobile-menu.css
│   │   ├── layouts/          # レイアウト関連
│   │   ├── pages/            # ページ固有のスタイル
│   │   │   ├── pages.css
│   │   │   └── single-service.css
│   │   ├── utilities/        # ユーティリティクラス
│   │   │   └── utilities.css
│   │   └── color-scheme-teal.css  # カラースキーム
│   ├── js/
│   │   ├── utils/            # ユーティリティ関数
│   │   │   ├── animation-utils.js
│   │   │   └── tablet-detection.js
│   │   ├── navigation.js     # ナビゲーション
│   │   ├── mobile-menu.js    # モバイルメニュー
│   │   └── theme.js          # メインJavaScript
│   └── images/              # 画像ファイル
├── inc/                     # PHP機能ファイル
│   ├── theme-setup.php      # テーマ初期設定
│   ├── assets.php           # CSS/JS読み込み
│   ├── post-types.php       # カスタム投稿タイプ
│   ├── customizer.php       # カスタマイザー設定
│   ├── seo-functions.php    # SEO関連機能
│   ├── structured-data.php  # 構造化データ
│   └── template-functions.php # テンプレート関数
├── template-parts/          # 再利用可能なテンプレート
│   ├── content-*.php        # コンテンツ表示
│   ├── hero-*.php           # ヒーローセクション
│   └── service-*.php        # サービス関連
├── languages/               # 翻訳ファイル
├── acf-json/               # ACF設定ファイル
├── functions.php           # メイン機能ファイル
├── style.css              # テーマ情報・基本スタイル
└── テンプレートファイル各種
    ├── index.php
    ├── front-page.php
    ├── page.php
    ├── single.php
    ├── archive.php
    └── 404.php
```

### CSSの記述ルール

```css
/* WordPressテーマ用CSS記述例 */
.service-grid-item {
  /* レイアウト */
  display: flex;
  flex-direction: column;
  
  /* サイズ */
  width: 100%;
  padding: 2rem;
  margin-bottom: 2rem;
  
  /* 視覚的スタイル */
  background-color: #ffffff;
  border: 1px solid rgba(0, 134, 123, 0.1);
  border-radius: 8px;
  
  /* 効果 */
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.service-grid-item:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 16px rgba(0, 134, 123, 0.15);
}
```

### PHPテンプレートの記述例

```php
<?php
/**
 * サービス一覧テンプレート
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <div class="service-grid">
                <?php
                while ( have_posts() ) :
                    the_post();
                    get_template_part( 'template-parts/content', 'service' );
                endwhile;
                ?>
            </div>
            
            <?php the_posts_navigation(); ?>
        <?php else : ?>
            <?php get_template_part( 'template-parts/content', 'none' ); ?>
        <?php endif; ?>
    </div>
</main>

<?php
get_sidebar();
get_footer();
```

## スタイリングのベストプラクティス

### CSS変数の活用

```css
/* style.css または base.css */
:root {
  /* ブランドカラー */
  --primary-color: #00867b;
  --primary-light: rgba(0, 134, 123, 0.1);
  --primary-dark: #006b61;
  --secondary-color: #10b981;
  
  /* テキストカラー */
  --text-color: #1f2937;
  --text-light: #6b7280;
  --text-white: #ffffff;
  
  /* 背景色 */
  --bg-white: #ffffff;
  --bg-light: #f9fafb;
  --bg-dark: #1f2937;
  
  /* スペーシング */
  --spacing-xs: 0.5rem;
  --spacing-sm: 1rem;
  --spacing-md: 1.5rem;
  --spacing-lg: 2rem;
  --spacing-xl: 3rem;
  
  /* フォント */
  --font-primary: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans JP", sans-serif;
  --font-heading: "Noto Sans JP", sans-serif;
  
  /* その他 */
  --border-radius: 8px;
  --transition: all 0.3s ease;
}
```

### レスポンシブデザイン

```css
/* モバイルファースト approach */
.service-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 2rem;
}

/* タブレット */
@media (min-width: 768px) {
  .service-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

/* デスクトップ */
@media (min-width: 1024px) {
  .service-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}
```

## ACF (Advanced Custom Fields) の使用

```php
// フィールドの存在確認と出力
<?php if( get_field('service_features') ): ?>
    <div class="service-features">
        <?php the_field('service_features'); ?>
    </div>
<?php endif; ?>

// リピーターフィールド
<?php if( have_rows('pricing_plans') ): ?>
    <div class="pricing-plans">
        <?php while( have_rows('pricing_plans') ): the_row(); ?>
            <div class="pricing-plan">
                <h3><?php the_sub_field('plan_name'); ?></h3>
                <p class="price"><?php the_sub_field('plan_price'); ?></p>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif; ?>
```

## パフォーマンス最適化

1. **アセットの最適化**
   - CSS/JSの適切な読み込み順序
   - 不要なファイルの除外
   - 条件付き読み込みの実装

2. **画像の最適化**
   ```php
   // 適切な画像サイズの登録
   add_image_size( 'service-thumbnail', 400, 300, true );
   add_image_size( 'service-featured', 800, 600, true );
   
   // 遅延読み込み
   <img src="<?php echo esc_url( $image_url ); ?>" 
        loading="lazy" 
        alt="<?php echo esc_attr( $image_alt ); ?>">
   ```

3. **キャッシュの活用**
   - トランジェントAPIの使用
   - オブジェクトキャッシュの実装

## SEO最適化

1. **構造化データ**
   - Schema.org マークアップの実装
   - パンくずリストの構造化データ
   - 記事・サービスの構造化データ

2. **メタタグ管理**
   ```php
   // OGP・Twitterカード
   <meta property="og:title" content="<?php echo esc_attr( $title ); ?>">
   <meta property="og:description" content="<?php echo esc_attr( $description ); ?>">
   <meta property="og:image" content="<?php echo esc_url( $image ); ?>">
   ```

3. **適切な見出し構造**
   - h1は1ページに1つ
   - 階層的な見出し構造
   - キーワードの適切な配置

## チェックリスト

WordPress テーマ開発時は以下の項目を確認してください：

- [ ] ブランドカラー #00867b が適切に使用されているか
- [ ] WordPress Coding Standards に準拠しているか
- [ ] すべての出力がエスケープされているか
- [ ] 翻訳関数が適切に使用されているか
- [ ] レスポンシブデザインが実装されているか
- [ ] アクセシビリティが考慮されているか
- [ ] パフォーマンスが最適化されているか
- [ ] SEO対策が実装されているか
- [ ] セキュリティが考慮されているか

## Git 操作ルール

### 作業終了時の必須手順

**すべての作業セッションの終了時には、必ず以下のGit操作を実行すること：**

```bash
# 1. 変更内容の確認
git status

# 2. すべての変更をステージング
git add .

# 3. 意味のあるコミットメッセージで記録
git commit -m "feat: サービス一覧ページのレイアウト改善"

# 4. リモートリポジトリへプッシュ
git push origin main
```

### コミットメッセージの規約

```
<type>: <subject>

<body>（任意）

<footer>（任意）
```

**タイプの種類：**
- `feat:` 新機能の追加
- `fix:` バグ修正
- `style:` CSSやデザインの変更
- `refactor:` リファクタリング
- `perf:` パフォーマンス改善
- `docs:` ドキュメントの変更
- `chore:` ビルドプロセスやツールの変更
- `test:` テストの追加・修正

**良い例：**
```bash
git commit -m "feat: モバイルメニューのアニメーション追加"
git commit -m "fix: タブレット表示時のレイアウト崩れを修正"
git commit -m "style: ブランドカラー#00867bへの統一"
```

## 開発ツール

### 推奨開発環境
- Local by Flywheel
- MAMP/XAMPP
- Docker + WordPress

### 推奨エディタ拡張
- PHP Intelephense
- WordPress Snippets
- ACF Snippets
- phpcs (WordPress Coding Standards)

### デバッグツール
```php
// wp-config.php での設定
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'SCRIPT_DEBUG', true );
```

これらのガイドラインに従うことで、保守性が高く、パフォーマンスに優れたWordPressテーマを開発できます。