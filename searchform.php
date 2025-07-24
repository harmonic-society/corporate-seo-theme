<?php
/**
 * 検索フォームテンプレート
 *
 * @package Corporate_SEO_Pro
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label>
        <span class="screen-reader-text"><?php echo _x( '検索:', 'label', 'corporate-seo-pro' ); ?></span>
        <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'キーワードを入力...', 'placeholder', 'corporate-seo-pro' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" />
    </label>
    <button type="submit" class="search-submit">
        <i class="fas fa-search"></i>
        <span class="screen-reader-text"><?php echo _x( '検索', 'submit button', 'corporate-seo-pro' ); ?></span>
    </button>
</form>