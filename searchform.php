<?php
/**
 * 検索フォームテンプレート
 *
 * @package Corporate_SEO_Pro
 */
?>

<form role="search" method="get" class="search-form modern-search-form" action="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/' ) ); ?>">
    <div class="search-input-wrapper">
        <input type="search" 
               class="search-field" 
               placeholder="<?php echo esc_attr_x( 'キーワードで検索', 'placeholder', 'corporate-seo-pro' ); ?>" 
               value="<?php echo esc_attr( get_search_query() ); ?>" 
               name="s" 
               autocomplete="off"
               aria-label="<?php echo esc_attr_x( '検索', 'label', 'corporate-seo-pro' ); ?>" />
        <button type="submit" class="search-submit" aria-label="<?php echo esc_attr_x( '検索を実行', 'submit button', 'corporate-seo-pro' ); ?>">
            <i class="fas fa-search"></i>
        </button>
    </div>
</form>