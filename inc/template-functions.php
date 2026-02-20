<?php
/**
 * Template Functions and Helpers
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 読了時間を計算する関数
 */
function corporate_seo_get_reading_time() {
    $content = get_post_field( 'post_content', get_the_ID() );
    $content = strip_tags( $content );
    
    // 日本語の文字数をカウント
    // 日本語は1分あたり400文字で計算（一般的な読書速度）
    $japanese_count = mb_strlen( preg_replace( '/[a-zA-Z0-9\s]/', '', $content ), 'UTF-8' );
    
    // 英数字の単語数をカウント
    $english_words = str_word_count( preg_replace( '/[^a-zA-Z0-9\s]/', ' ', $content ) );
    
    // 読了時間を計算
    // 日本語: 400文字/分、英語: 200単語/分
    $japanese_time = $japanese_count / 400;
    $english_time = $english_words / 200;
    
    $reading_time = ceil( $japanese_time + $english_time );
    
    // 最小値を1分に設定
    if ( $reading_time < 1 ) {
        $reading_time = 1;
    }
    
    if ( $reading_time == 1 ) {
        return '1分で読める';
    } else {
        return $reading_time . '分で読める';
    }
}


/**
 * 関連記事の取得
 * カテゴリベースで取得し、足りない場合は新しい順で補完
 */
function corporate_seo_pro_get_related_posts( $post_id, $number_posts = 6 ) {
    $related_post_ids = array();
    $exclude_ids = array( $post_id );

    // 1. まずカテゴリーベースで関連記事を取得
    $categories = wp_get_post_categories( $post_id );
    if ( $categories ) {
        $category_args = array(
            'post_type'      => 'post',
            'post__not_in'   => $exclude_ids,
            'posts_per_page' => $number_posts,
            'category__in'   => $categories,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'fields'         => 'ids',
        );

        $category_posts = get_posts( $category_args );
        if ( $category_posts ) {
            $related_post_ids = array_merge( $related_post_ids, $category_posts );
        }
    }

    // 2. カテゴリベースで足りない場合は新しい順で補完
    $current_count = count( $related_post_ids );
    if ( $current_count < $number_posts ) {
        $needed = $number_posts - $current_count;
        $exclude_ids = array_merge( $exclude_ids, $related_post_ids );

        $recent_args = array(
            'post_type'      => 'post',
            'post__not_in'   => $exclude_ids,
            'posts_per_page' => $needed,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'fields'         => 'ids',
        );

        $recent_posts = get_posts( $recent_args );
        if ( $recent_posts ) {
            $related_post_ids = array_merge( $related_post_ids, $recent_posts );
        }
    }

    // 3. 取得した投稿IDでWP_Queryを実行
    if ( empty( $related_post_ids ) ) {
        // 何もない場合は空のクエリを返す
        return new WP_Query();
    }

    $final_args = array(
        'post_type'      => 'post',
        'post__in'       => $related_post_ids,
        'posts_per_page' => $number_posts,
        'orderby'        => 'post__in',
    );

    return new WP_Query( $final_args );
}

/**
 * パンくずリスト
 */
function corporate_seo_pro_breadcrumb() {
    if ( is_front_page() ) {
        return;
    }

    echo '<nav class="breadcrumb" aria-label="' . esc_attr__( 'パンくずリスト', 'corporate-seo-pro' ) . '">';
    echo '<ol itemscope itemtype="https://schema.org/BreadcrumbList">';

    // ホーム
    echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
    echo '<a itemprop="item" href="' . esc_url( home_url( '/' ) ) . '">';
    echo '<span itemprop="name">' . esc_html__( 'ホーム', 'corporate-seo-pro' ) . '</span>';
    echo '</a>';
    echo '<meta itemprop="position" content="1" />';
    echo '</li>';

    $position = 2;

    // 投稿ページ
    if ( is_single() ) {
        $categories = get_the_category();
        if ( $categories ) {
            $category = $categories[0];
            echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            echo '<a itemprop="item" href="' . esc_url( get_category_link( $category->term_id ) ) . '">';
            echo '<span itemprop="name">' . esc_html( $category->name ) . '</span>';
            echo '</a>';
            echo '<meta itemprop="position" content="' . $position . '" />';
            echo '</li>';
            $position++;
        }

        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . get_the_title() . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';
    }

    // 固定ページ
    elseif ( is_page() ) {
        global $post;
        if ( $post && $post->post_parent ) {
            $ancestors = array_reverse( get_post_ancestors( $post->ID ) );
            foreach ( $ancestors as $ancestor ) {
                echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                echo '<a itemprop="item" href="' . esc_url( get_permalink( $ancestor ) ) . '">';
                echo '<span itemprop="name">' . get_the_title( $ancestor ) . '</span>';
                echo '</a>';
                echo '<meta itemprop="position" content="' . $position . '" />';
                echo '</li>';
                $position++;
            }
        }

        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . get_the_title() . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';
    }

    // カテゴリーアーカイブ
    elseif ( is_category() ) {
        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . single_cat_title( '', false ) . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';
    }

    // 検索結果
    elseif ( is_search() ) {
        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . esc_html__( '検索結果', 'corporate-seo-pro' ) . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';
    }

    echo '</ol>';
    echo '</nav>';
}

/**
 * お問い合わせページのURLを取得
 */
function get_contact_page_url() {
    // まず、IDでページを検索（最も確実）
    $page_id = get_option('page_on_front');
    
    // Template Name: Contact を使用しているページを検索
    $args = array(
        'post_type' => 'page',
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => '_wp_page_template',
                'value' => 'page-contact.php',
                'compare' => '='
            )
        ),
        'posts_per_page' => 1
    );
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        $query->the_post();
        $url = get_permalink();
        wp_reset_postdata();
        return $url;
    }
    
    // テンプレートが見つからない場合は、スラッグで検索
    $contact_page = get_page_by_path('contact');
    if ($contact_page && $contact_page->post_status == 'publish') {
        return get_permalink($contact_page->ID);
    }
    
    // それでも見つからない場合は、タイトルで検索
    $pages = get_pages(array(
        'post_type' => 'page',
        'post_status' => 'publish'
    ));
    
    foreach ($pages as $page) {
        if (strpos(strtolower($page->post_title), 'contact') !== false || 
            strpos($page->post_title, 'お問い合わせ') !== false ||
            strpos($page->post_title, '問い合わせ') !== false) {
            return get_permalink($page->ID);
        }
    }
    
    // デフォルトとしてホームURLを返す
    return home_url('/contact/');
}

/**
 * フォールバック用のお問い合わせフォーム
 * Contact Form 7が利用できない場合に使用
 */
function corporate_seo_pro_fallback_contact_form() {
    ?>
    <form class="contact-form fallback-form" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
        <input type="hidden" name="action" value="corporate_seo_pro_contact_form">
        <?php wp_nonce_field( 'corporate_seo_pro_contact_form', 'contact_form_nonce' ); ?>
        
        <div class="form-group">
            <label for="contact-name">お名前 <span class="required">*</span></label>
            <input type="text" id="contact-name" name="contact_name" required>
        </div>
        
        <div class="form-group">
            <label for="contact-email">メールアドレス <span class="required">*</span></label>
            <input type="email" id="contact-email" name="contact_email" required>
        </div>
        
        <div class="form-group">
            <label for="contact-phone">電話番号</label>
            <input type="tel" id="contact-phone" name="contact_phone">
        </div>
        
        <div class="form-group">
            <label for="contact-subject">件名 <span class="required">*</span></label>
            <input type="text" id="contact-subject" name="contact_subject" required>
        </div>
        
        <div class="form-group">
            <label for="contact-message">お問い合わせ内容 <span class="required">*</span></label>
            <textarea id="contact-message" name="contact_message" rows="8" required></textarea>
        </div>
        
        <div class="form-submit">
            <button type="submit" class="wpcf7-submit">送信する</button>
        </div>
    </form>
    <?php
}