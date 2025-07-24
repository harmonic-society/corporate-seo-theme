<?php
/**
 * Template part for displaying breadcrumb navigation
 *
 * @package Corporate_SEO_Pro
 */

// 構造化データ対応のパンくずリスト
$breadcrumb_items = array();

// ホームを追加
$breadcrumb_items[] = array(
    'name' => 'ホーム',
    'url'  => home_url( '/' ),
);

// カスタム投稿タイプの場合
if ( is_singular() ) {
    $post_type = get_post_type();
    
    // サービスの場合
    if ( $post_type === 'service' ) {
        $breadcrumb_items[] = array(
            'name' => 'サービス',
            'url'  => get_post_type_archive_link( 'service' ),
        );
    }
    // 実績の場合
    elseif ( $post_type === 'work' ) {
        $breadcrumb_items[] = array(
            'name' => '実績',
            'url'  => get_post_type_archive_link( 'work' ),
        );
    }
    // ブログ投稿の場合
    elseif ( $post_type === 'post' ) {
        $breadcrumb_items[] = array(
            'name' => 'ブログ',
            'url'  => get_permalink( get_option( 'page_for_posts' ) ),
        );
        
        // カテゴリーを追加
        $categories = get_the_category();
        if ( ! empty( $categories ) ) {
            $breadcrumb_items[] = array(
                'name' => $categories[0]->name,
                'url'  => get_category_link( $categories[0]->term_id ),
            );
        }
    }
    
    // 現在のページを追加
    $breadcrumb_items[] = array(
        'name' => get_the_title(),
        'url'  => '',
    );
}
// アーカイブページの場合
elseif ( is_archive() ) {
    if ( is_post_type_archive( 'service' ) ) {
        $breadcrumb_items[] = array(
            'name' => 'サービス',
            'url'  => '',
        );
    } elseif ( is_post_type_archive( 'work' ) ) {
        $breadcrumb_items[] = array(
            'name' => '実績',
            'url'  => '',
        );
    } elseif ( is_category() ) {
        $breadcrumb_items[] = array(
            'name' => 'ブログ',
            'url'  => get_permalink( get_option( 'page_for_posts' ) ),
        );
        $breadcrumb_items[] = array(
            'name' => single_cat_title( '', false ),
            'url'  => '',
        );
    }
}
// 検索結果ページの場合
elseif ( is_search() ) {
    $breadcrumb_items[] = array(
        'name' => '検索結果',
        'url'  => '',
    );
}
// 404ページの場合
elseif ( is_404() ) {
    $breadcrumb_items[] = array(
        'name' => 'ページが見つかりません',
        'url'  => '',
    );
}

// 固定ページの場合
elseif ( is_page() ) {
    // 親ページがある場合は階層を追加
    $ancestors = get_post_ancestors( get_the_ID() );
    if ( $ancestors ) {
        $ancestors = array_reverse( $ancestors );
        foreach ( $ancestors as $ancestor ) {
            $breadcrumb_items[] = array(
                'name' => get_the_title( $ancestor ),
                'url'  => get_permalink( $ancestor ),
            );
        }
    }
    
    $breadcrumb_items[] = array(
        'name' => get_the_title(),
        'url'  => '',
    );
}

// パンくずリストを表示
if ( count( $breadcrumb_items ) > 1 ) : ?>
    <nav class="breadcrumb" aria-label="パンくずリスト">
        <ol class="breadcrumb-list" itemscope itemtype="https://schema.org/BreadcrumbList">
            <?php foreach ( $breadcrumb_items as $index => $item ) : ?>
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <?php if ( ! empty( $item['url'] ) ) : ?>
                        <a href="<?php echo esc_url( $item['url'] ); ?>" itemprop="item">
                            <span itemprop="name"><?php echo esc_html( $item['name'] ); ?></span>
                        </a>
                    <?php else : ?>
                        <span itemprop="name"><?php echo esc_html( $item['name'] ); ?></span>
                    <?php endif; ?>
                    <meta itemprop="position" content="<?php echo $index + 1; ?>" />
                </li>
            <?php endforeach; ?>
        </ol>
    </nav>
<?php endif; ?>