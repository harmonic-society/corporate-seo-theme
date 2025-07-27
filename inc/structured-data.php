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

/**
 * パンくずリストの構造化データ
 */
function corporate_seo_pro_breadcrumb_schema() {
    if ( is_front_page() ) {
        return;
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => array(),
    );
    
    $position = 1;
    
    // ホーム
    $schema['itemListElement'][] = array(
        '@type' => 'ListItem',
        'position' => $position,
        'name' => get_bloginfo( 'name' ),
        'item' => home_url( '/' ),
    );
    $position++;
    
    // 投稿タイプアーカイブ
    if ( is_post_type_archive() ) {
        $post_type = get_queried_object();
        $schema['itemListElement'][] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => $post_type->label,
        );
    }
    
    // タクソノミーアーカイブ
    elseif ( is_tax() || is_category() || is_tag() ) {
        $term = get_queried_object();
        
        // 親タームがある場合
        if ( $term->parent ) {
            $ancestors = get_ancestors( $term->term_id, $term->taxonomy, 'taxonomy' );
            $ancestors = array_reverse( $ancestors );
            
            foreach ( $ancestors as $ancestor ) {
                $ancestor_term = get_term( $ancestor, $term->taxonomy );
                $schema['itemListElement'][] = array(
                    '@type' => 'ListItem',
                    'position' => $position,
                    'name' => $ancestor_term->name,
                    'item' => get_term_link( $ancestor_term ),
                );
                $position++;
            }
        }
        
        $schema['itemListElement'][] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => $term->name,
        );
    }
    
    // 個別投稿
    elseif ( is_singular() ) {
        global $post;
        
        // カスタム投稿タイプ
        if ( 'post' !== $post->post_type && 'page' !== $post->post_type ) {
            $post_type = get_post_type_object( $post->post_type );
            $schema['itemListElement'][] = array(
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $post_type->labels->name,
                'item' => get_post_type_archive_link( $post->post_type ),
            );
            $position++;
        }
        
        // 投稿の場合、カテゴリーを表示
        elseif ( 'post' === $post->post_type ) {
            $categories = get_the_category( $post->ID );
            if ( $categories ) {
                $primary_category = $categories[0];
                
                // カテゴリーの祖先を取得
                if ( $primary_category->parent ) {
                    $ancestors = get_ancestors( $primary_category->term_id, 'category' );
                    $ancestors = array_reverse( $ancestors );
                    
                    foreach ( $ancestors as $ancestor ) {
                        $ancestor_cat = get_category( $ancestor );
                        $schema['itemListElement'][] = array(
                            '@type' => 'ListItem',
                            'position' => $position,
                            'name' => $ancestor_cat->name,
                            'item' => get_category_link( $ancestor ),
                        );
                        $position++;
                    }
                }
                
                $schema['itemListElement'][] = array(
                    '@type' => 'ListItem',
                    'position' => $position,
                    'name' => $primary_category->name,
                    'item' => get_category_link( $primary_category ),
                );
                $position++;
            }
        }
        
        // 固定ページの場合、親ページを表示
        elseif ( 'page' === $post->post_type && $post->post_parent ) {
            $ancestors = get_ancestors( $post->ID, 'page' );
            $ancestors = array_reverse( $ancestors );
            
            foreach ( $ancestors as $ancestor ) {
                $schema['itemListElement'][] = array(
                    '@type' => 'ListItem',
                    'position' => $position,
                    'name' => get_the_title( $ancestor ),
                    'item' => get_permalink( $ancestor ),
                );
                $position++;
            }
        }
        
        // 現在のページ
        $schema['itemListElement'][] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => get_the_title(),
        );
    }
    
    // アーカイブページ
    elseif ( is_archive() ) {
        if ( is_date() ) {
            if ( is_year() ) {
                $schema['itemListElement'][] = array(
                    '@type' => 'ListItem',
                    'position' => $position,
                    'name' => get_the_date( 'Y年' ),
                );
            } elseif ( is_month() ) {
                $schema['itemListElement'][] = array(
                    '@type' => 'ListItem',
                    'position' => $position,
                    'name' => get_the_date( 'Y年' ),
                    'item' => get_year_link( get_the_date( 'Y' ) ),
                );
                $position++;
                
                $schema['itemListElement'][] = array(
                    '@type' => 'ListItem',
                    'position' => $position,
                    'name' => get_the_date( 'n月' ),
                );
            }
        } elseif ( is_author() ) {
            $schema['itemListElement'][] = array(
                '@type' => 'ListItem',
                'position' => $position,
                'name' => get_the_author(),
            );
        }
    }
    
    // 検索結果
    elseif ( is_search() ) {
        $schema['itemListElement'][] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => '検索結果: ' . get_search_query(),
        );
    }
    
    // 404ページ
    elseif ( is_404() ) {
        $schema['itemListElement'][] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => 'ページが見つかりません',
        );
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'corporate_seo_pro_breadcrumb_schema' );

/**
 * Product構造化データ（サービス用）
 */
function corporate_seo_pro_product_schema() {
    if ( ! is_singular( 'service' ) ) {
        return;
    }
    
    global $post;
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => get_the_title(),
        'description' => wp_trim_words( strip_tags( $post->post_content ), 50, '...' ),
        'url' => get_permalink(),
    );
    
    // アイキャッチ画像
    if ( has_post_thumbnail() ) {
        $thumbnail_id = get_post_thumbnail_id();
        $thumbnail_url = wp_get_attachment_image_src( $thumbnail_id, 'full' );
        $schema['image'] = $thumbnail_url[0];
    }
    
    // ブランド情報
    $schema['brand'] = array(
        '@type' => 'Brand',
        'name' => get_bloginfo( 'name' ),
    );
    
    // 価格情報（カスタムフィールドから取得）
    $price = get_post_meta( $post->ID, '_service_price', true );
    $price_type = get_post_meta( $post->ID, '_service_price_type', true );
    
    if ( $price ) {
        $schema['offers'] = array(
            '@type' => 'Offer',
            'price' => $price,
            'priceCurrency' => 'JPY',
            'availability' => 'https://schema.org/InStock',
            'priceValidUntil' => date( 'Y-m-d', strtotime( '+1 year' ) ),
        );
        
        if ( $price_type === 'starting_from' ) {
            $schema['offers']['priceSpecification'] = array(
                '@type' => 'PriceSpecification',
                'price' => $price,
                'priceCurrency' => 'JPY',
                'valueAddedTaxIncluded' => true,
            );
        }
    }
    
    // レビュー情報（カスタムフィールドから取得）
    $rating = get_post_meta( $post->ID, '_service_rating', true );
    $review_count = get_post_meta( $post->ID, '_service_review_count', true );
    
    if ( $rating && $review_count ) {
        $schema['aggregateRating'] = array(
            '@type' => 'AggregateRating',
            'ratingValue' => $rating,
            'reviewCount' => $review_count,
            'bestRating' => '5',
            'worstRating' => '1',
        );
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'corporate_seo_pro_product_schema' );

/**
 * Review構造化データ
 */
function corporate_seo_pro_review_schema() {
    if ( ! is_singular( 'work' ) ) {
        return;
    }
    
    global $post;
    
    // クライアントレビュー（カスタムフィールドから取得）
    $client_name = get_post_meta( $post->ID, '_work_client_name', true );
    $review_text = get_post_meta( $post->ID, '_work_review_text', true );
    $review_rating = get_post_meta( $post->ID, '_work_review_rating', true );
    $reviewer_name = get_post_meta( $post->ID, '_work_reviewer_name', true );
    $reviewer_title = get_post_meta( $post->ID, '_work_reviewer_title', true );
    
    if ( ! $client_name || ! $review_text || ! $review_rating ) {
        return;
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Review',
        'itemReviewed' => array(
            '@type' => 'CreativeWork',
            'name' => get_the_title(),
            'description' => wp_trim_words( strip_tags( $post->post_content ), 30, '...' ),
            'creator' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo( 'name' ),
            ),
        ),
        'reviewRating' => array(
            '@type' => 'Rating',
            'ratingValue' => $review_rating,
            'bestRating' => '5',
            'worstRating' => '1',
        ),
        'reviewBody' => $review_text,
        'author' => array(
            '@type' => 'Person',
            'name' => $reviewer_name ?: $client_name,
        ),
        'datePublished' => get_the_date( 'c' ),
    );
    
    if ( $reviewer_title ) {
        $schema['author']['jobTitle'] = $reviewer_title;
    }
    
    if ( $client_name && $reviewer_name ) {
        $schema['author']['worksFor'] = array(
            '@type' => 'Organization',
            'name' => $client_name,
        );
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'corporate_seo_pro_review_schema' );

/**
 * HowTo構造化データ（操作手順用）
 */
function corporate_seo_pro_howto_schema( $content ) {
    if ( ! is_singular() ) {
        return $content;
    }
    
    // HowToショートコードがある場合の処理
    if ( has_shortcode( $content, 'howto' ) ) {
        preg_match_all( '/\[howto name="([^"]+)" steps="([^"]+)"\]/i', $content, $matches );
        
        if ( ! empty( $matches[1] ) && ! empty( $matches[2] ) ) {
            foreach ( $matches[1] as $index => $howto_name ) {
                $steps_raw = $matches[2][$index];
                $steps = explode( '|', $steps_raw );
                
                $schema = array(
                    '@context' => 'https://schema.org',
                    '@type' => 'HowTo',
                    'name' => $howto_name,
                    'step' => array(),
                );
                
                foreach ( $steps as $step_index => $step_text ) {
                    $schema['step'][] = array(
                        '@type' => 'HowToStep',
                        'position' => $step_index + 1,
                        'name' => '手順' . ( $step_index + 1 ),
                        'text' => trim( $step_text ),
                    );
                }
                
                $howto_json = '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
                $content = $howto_json . $content;
            }
        }
    }
    
    return $content;
}
add_filter( 'the_content', 'corporate_seo_pro_howto_schema', 5 );

/**
 * Event構造化データ
 */
function corporate_seo_pro_event_schema() {
    if ( ! is_singular( 'post' ) ) {
        return;
    }
    
    global $post;
    
    // イベントカテゴリーの投稿のみ対象
    if ( ! has_category( 'event', $post ) && ! has_category( 'イベント', $post ) ) {
        return;
    }
    
    // カスタムフィールドから情報取得
    $event_name = get_post_meta( $post->ID, '_event_name', true ) ?: get_the_title();
    $event_start_date = get_post_meta( $post->ID, '_event_start_date', true );
    $event_end_date = get_post_meta( $post->ID, '_event_end_date', true );
    $event_location = get_post_meta( $post->ID, '_event_location', true );
    $event_address = get_post_meta( $post->ID, '_event_address', true );
    
    if ( ! $event_start_date ) {
        return;
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Event',
        'name' => $event_name,
        'startDate' => date( 'c', strtotime( $event_start_date ) ),
        'description' => wp_trim_words( strip_tags( $post->post_content ), 50, '...' ),
        'url' => get_permalink(),
        'organizer' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo( 'name' ),
            'url' => home_url( '/' ),
        ),
    );
    
    if ( $event_end_date ) {
        $schema['endDate'] = date( 'c', strtotime( $event_end_date ) );
    }
    
    if ( $event_location || $event_address ) {
        $schema['location'] = array(
            '@type' => 'Place',
            'name' => $event_location ?: $event_address,
        );
        
        if ( $event_address ) {
            $schema['location']['address'] = array(
                '@type' => 'PostalAddress',
                'streetAddress' => $event_address,
                'addressCountry' => 'JP',
            );
        }
    } else {
        // オンラインイベントの場合
        $schema['location'] = array(
            '@type' => 'VirtualLocation',
            'url' => get_permalink(),
        );
        $schema['eventAttendanceMode'] = 'https://schema.org/OnlineEventAttendanceMode';
    }
    
    // アイキャッチ画像
    if ( has_post_thumbnail() ) {
        $thumbnail_id = get_post_thumbnail_id();
        $thumbnail_url = wp_get_attachment_image_src( $thumbnail_id, 'full' );
        $schema['image'] = $thumbnail_url[0];
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'corporate_seo_pro_event_schema' );