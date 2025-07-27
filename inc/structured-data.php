<?php
/**
 * 構造化データの実装
 *
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 組織の構造化データ
 */
function corporate_seo_pro_organization_schema() {
    if ( ! is_front_page() ) {
        return;
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => get_bloginfo( 'name' ),
        'url' => home_url( '/' ),
        'description' => get_bloginfo( 'description' ),
    );
    
    // ロゴ
    if ( has_custom_logo() ) {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $logo = wp_get_attachment_image_src( $custom_logo_id, 'full' );
        $schema['logo'] = array(
            '@type' => 'ImageObject',
            'url' => $logo[0],
            'width' => $logo[1],
            'height' => $logo[2],
        );
    }
    
    // 連絡先情報
    if ( get_theme_mod( 'company_phone' ) || get_theme_mod( 'company_email' ) ) {
        $schema['contactPoint'] = array(
            '@type' => 'ContactPoint',
            'contactType' => 'customer service',
        );
        
        if ( get_theme_mod( 'company_phone' ) ) {
            $schema['contactPoint']['telephone'] = get_theme_mod( 'company_phone' );
        }
        
        if ( get_theme_mod( 'company_email' ) ) {
            $schema['contactPoint']['email'] = get_theme_mod( 'company_email' );
        }
    }
    
    // 住所
    if ( get_theme_mod( 'company_address' ) ) {
        $schema['address'] = array(
            '@type' => 'PostalAddress',
            'streetAddress' => get_theme_mod( 'company_address' ),
            'addressCountry' => 'JP',
        );
    }
    
    // ソーシャルメディア
    $social_profiles = array();
    if ( get_theme_mod( 'facebook_url' ) ) {
        $social_profiles[] = get_theme_mod( 'facebook_url' );
    }
    if ( get_theme_mod( 'twitter_url' ) ) {
        $social_profiles[] = get_theme_mod( 'twitter_url' );
    }
    if ( get_theme_mod( 'linkedin_url' ) ) {
        $social_profiles[] = get_theme_mod( 'linkedin_url' );
    }
    if ( get_theme_mod( 'youtube_url' ) ) {
        $social_profiles[] = get_theme_mod( 'youtube_url' );
    }
    
    if ( ! empty( $social_profiles ) ) {
        $schema['sameAs'] = $social_profiles;
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'corporate_seo_pro_organization_schema' );

/**
 * ウェブサイトの構造化データ
 */
function corporate_seo_pro_website_schema() {
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => get_bloginfo( 'name' ),
        'url' => home_url( '/' ),
        'potentialAction' => array(
            '@type' => 'SearchAction',
            'target' => array(
                '@type' => 'EntryPoint',
                'urlTemplate' => home_url( '/?s={search_term_string}' ),
            ),
            'query-input' => 'required name=search_term_string',
        ),
    );
    
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'corporate_seo_pro_website_schema' );

/**
 * 記事の構造化データ
 */
function corporate_seo_pro_article_schema() {
    if ( ! is_singular( 'post' ) ) {
        return;
    }
    
    global $post;
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => get_the_title(),
        'url' => get_permalink(),
        'datePublished' => get_the_date( 'c' ),
        'dateModified' => get_the_modified_date( 'c' ),
        'author' => array(
            '@type' => 'Person',
            'name' => get_the_author(),
        ),
        'publisher' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo( 'name' ),
        ),
    );
    
    // アイキャッチ画像
    if ( has_post_thumbnail() ) {
        $thumbnail_id = get_post_thumbnail_id();
        $thumbnail_url = wp_get_attachment_image_src( $thumbnail_id, 'full' );
        
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $thumbnail_url[0],
            'width' => $thumbnail_url[1],
            'height' => $thumbnail_url[2],
        );
    }
    
    // 説明文
    $description = get_post_meta( $post->ID, '_corporate_seo_meta_description', true );
    if ( empty( $description ) ) {
        $description = wp_trim_words( strip_tags( $post->post_content ), 30, '...' );
    }
    $schema['description'] = $description;
    
    // カテゴリー
    $categories = get_the_category();
    if ( $categories ) {
        $schema['articleSection'] = $categories[0]->name;
    }
    
    // タグ
    $tags = get_the_tags();
    if ( $tags ) {
        $keywords = array();
        foreach ( $tags as $tag ) {
            $keywords[] = $tag->name;
        }
        $schema['keywords'] = implode( ', ', $keywords );
    }
    
    // ロゴ
    if ( has_custom_logo() ) {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $logo = wp_get_attachment_image_src( $custom_logo_id, 'full' );
        $schema['publisher']['logo'] = array(
            '@type' => 'ImageObject',
            'url' => $logo[0],
            'width' => $logo[1],
            'height' => $logo[2],
        );
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'corporate_seo_pro_article_schema' );

/**
 * サービスの構造化データ
 */
function corporate_seo_pro_service_schema() {
    if ( ! is_singular( 'service' ) ) {
        return;
    }
    
    global $post;
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => get_the_title(),
        'description' => wp_trim_words( strip_tags( $post->post_content ), 30, '...' ),
        'provider' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo( 'name' ),
        ),
    );
    
    // カスタムフィールドから追加情報を取得
    $price = get_post_meta( $post->ID, '_service_price', true );
    if ( $price ) {
        $schema['offers'] = array(
            '@type' => 'Offer',
            'price' => $price,
            'priceCurrency' => 'JPY',
        );
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'corporate_seo_pro_service_schema' );

/**
 * FAQの構造化データ
 */
function corporate_seo_pro_faq_schema( $content ) {
    if ( ! is_singular() ) {
        return $content;
    }
    
    // FAQショートコードがある場合の処理
    if ( has_shortcode( $content, 'faq' ) ) {
        preg_match_all( '/\[faq question="([^"]+)" answer="([^"]+)"\]/i', $content, $matches );
        
        if ( ! empty( $matches[1] ) && ! empty( $matches[2] ) ) {
            $faq_schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => array(),
            );
            
            foreach ( $matches[1] as $index => $question ) {
                $faq_schema['mainEntity'][] = array(
                    '@type' => 'Question',
                    'name' => $question,
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text' => $matches[2][$index],
                    ),
                );
            }
            
            $faq_json = '<script type="application/ld+json">' . wp_json_encode( $faq_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
            $content = $faq_json . $content;
        }
    }
    
    return $content;
}
add_filter( 'the_content', 'corporate_seo_pro_faq_schema' );

/**
 * ローカルビジネスの構造化データ
 */
function corporate_seo_pro_local_business_schema() {
    if ( ! is_front_page() || ! get_theme_mod( 'enable_local_business_schema' ) ) {
        return;
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => get_theme_mod( 'business_type', 'LocalBusiness' ),
        'name' => get_bloginfo( 'name' ),
        'url' => home_url( '/' ),
    );
    
    // 営業時間
    if ( get_theme_mod( 'business_hours' ) ) {
        $schema['openingHours'] = explode( ',', get_theme_mod( 'business_hours' ) );
    }
    
    // 価格帯
    if ( get_theme_mod( 'price_range' ) ) {
        $schema['priceRange'] = get_theme_mod( 'price_range' );
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'corporate_seo_pro_local_business_schema' );