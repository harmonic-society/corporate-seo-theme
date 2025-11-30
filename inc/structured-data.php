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
 * 組織の構造化データ（拡張版 - ナレッジグラフ対応）
 *
 * GoogleナレッジパネルやAI Overviewでの企業情報表示を最適化
 */
function corporate_seo_pro_organization_schema() {
    if ( ! is_front_page() ) {
        return;
    }

    $schema = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'Organization',
        '@id'         => home_url( '/#organization' ),
        'name'        => get_bloginfo( 'name' ),
        'url'         => home_url( '/' ),
        'description' => get_bloginfo( 'description' ),
    );

    // 正式法人名
    $legal_name = get_theme_mod( 'company_legal_name', 'Harmonic Society株式会社' );
    if ( ! empty( $legal_name ) ) {
        $schema['legalName'] = $legal_name;
    }

    // 創業日
    $founding_date = get_theme_mod( 'company_founding_date', '2023-03-03' );
    if ( ! empty( $founding_date ) ) {
        $schema['foundingDate'] = $founding_date;
    }

    // 企業スローガン
    $slogan = get_theme_mod( 'company_slogan', '社会の調和' );
    if ( ! empty( $slogan ) ) {
        $schema['slogan'] = $slogan;
    }

    // ロゴ
    if ( has_custom_logo() ) {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $logo = wp_get_attachment_image_src( $custom_logo_id, 'full' );
        if ( $logo ) {
            $schema['logo'] = array(
                '@type'   => 'ImageObject',
                '@id'     => home_url( '/#logo' ),
                'url'     => $logo[0],
                'width'   => $logo[1],
                'height'  => $logo[2],
                'caption' => get_bloginfo( 'name' ) . ' ロゴ',
            );
            $schema['image'] = $schema['logo'];
        }
    }

    // 住所（詳細構造）
    $postal_code = get_theme_mod( 'company_postal_code', '262-0033' );
    $address_region = get_theme_mod( 'company_address_region', '千葉県' );
    $address_locality = get_theme_mod( 'company_address_locality', '千葉市花見川区' );
    $street_address = get_theme_mod( 'company_street_address', '幕張本郷3-31-8' );

    $schema['address'] = array(
        '@type'           => 'PostalAddress',
        'postalCode'      => $postal_code,
        'addressRegion'   => $address_region,
        'addressLocality' => $address_locality,
        'streetAddress'   => $street_address,
        'addressCountry'  => 'JP',
    );

    // 連絡先情報
    $contact_point = array(
        '@type'             => 'ContactPoint',
        'contactType'       => 'customer service',
        'availableLanguage' => 'Japanese',
        'areaServed'        => 'JP',
    );

    if ( get_theme_mod( 'company_phone' ) ) {
        $contact_point['telephone'] = get_theme_mod( 'company_phone' );
    }

    if ( get_theme_mod( 'company_email' ) ) {
        $contact_point['email'] = get_theme_mod( 'company_email' );
    }

    if ( isset( $contact_point['telephone'] ) || isset( $contact_point['email'] ) ) {
        $schema['contactPoint'] = $contact_point;
    }

    // 創業者情報
    $founder_name = get_theme_mod( 'company_founder_name', '師田 賢人' );
    if ( ! empty( $founder_name ) ) {
        $founder = array(
            '@type' => 'Person',
            '@id'   => home_url( '/#founder' ),
            'name'  => $founder_name,
        );

        $founder_title = get_theme_mod( 'company_founder_title', '代表取締役' );
        if ( ! empty( $founder_title ) ) {
            $founder['jobTitle'] = $founder_title;
        }

        $founder_image = get_theme_mod( 'company_founder_image' );
        if ( ! empty( $founder_image ) ) {
            $founder['image'] = $founder_image;
        }

        $founder_description = get_theme_mod( 'company_founder_description' );
        if ( ! empty( $founder_description ) ) {
            $founder['description'] = $founder_description;
        }

        $schema['founder'] = $founder;
    }

    // 従業員数
    $employee_count = get_theme_mod( 'company_employee_count' );
    if ( ! empty( $employee_count ) ) {
        $schema['numberOfEmployees'] = array(
            '@type' => 'QuantitativeValue',
            'value' => intval( $employee_count ),
        );
    }

    // 専門分野（knowsAbout）
    $schema['knowsAbout'] = array(
        'SEO対策',
        'Webマーケティング',
        'ホームページ制作',
        'WordPress開発',
        'AI活用支援',
        'DX推進支援',
        'レスポンシブデザイン',
        'Webシステム開発',
        'コンテンツマーケティング',
        '検索エンジン最適化',
    );

    // 対応エリア
    $schema['areaServed'] = array(
        array( '@type' => 'AdministrativeArea', 'name' => '千葉県' ),
        array( '@type' => 'AdministrativeArea', 'name' => '東京都' ),
        array( '@type' => 'Country', 'name' => '日本' ),
    );

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

    // ブランド情報
    $schema['brand'] = array(
        '@type'  => 'Brand',
        '@id'    => home_url( '/#brand' ),
        'name'   => get_bloginfo( 'name' ),
        'slogan' => $slogan,
    );

    // DUNS番号（設定されている場合）
    $duns_number = get_theme_mod( 'company_duns_number' );
    if ( ! empty( $duns_number ) ) {
        $schema['duns'] = $duns_number;
    }

    // ACFから受賞歴・認定資格・所属団体を取得
    if ( function_exists( 'get_field' ) ) {
        // 受賞歴
        $awards_data = get_field( 'company_awards', 'option' );
        if ( $awards_data && is_array( $awards_data ) ) {
            $awards = array();
            foreach ( $awards_data as $award ) {
                if ( ! empty( $award['name'] ) ) {
                    $awards[] = $award['name'];
                }
            }
            if ( ! empty( $awards ) ) {
                $schema['award'] = $awards;
            }
        }

        // 認定資格
        $credentials_data = get_field( 'company_credentials', 'option' );
        if ( $credentials_data && is_array( $credentials_data ) ) {
            $credentials = array();
            foreach ( $credentials_data as $credential ) {
                if ( ! empty( $credential['name'] ) ) {
                    $credential_item = array(
                        '@type'              => 'EducationalOccupationalCredential',
                        'credentialCategory' => 'certification',
                        'name'               => $credential['name'],
                    );

                    if ( ! empty( $credential['issuer'] ) ) {
                        $credential_item['recognizedBy'] = array(
                            '@type' => 'Organization',
                            'name'  => $credential['issuer'],
                        );
                    }

                    if ( ! empty( $credential['valid_from'] ) ) {
                        $credential_item['validFrom'] = $credential['valid_from'];
                    }

                    $credentials[] = $credential_item;
                }
            }
            if ( ! empty( $credentials ) ) {
                $schema['hasCredential'] = $credentials;
            }
        }

        // 所属団体
        $memberships_data = get_field( 'company_memberships', 'option' );
        if ( $memberships_data && is_array( $memberships_data ) ) {
            $memberships = array();
            foreach ( $memberships_data as $membership ) {
                if ( ! empty( $membership['organization'] ) ) {
                    $membership_item = array(
                        '@type' => 'Organization',
                        'name'  => $membership['organization'],
                    );

                    if ( ! empty( $membership['url'] ) ) {
                        $membership_item['url'] = $membership['url'];
                    }

                    $memberships[] = $membership_item;
                }
            }
            if ( ! empty( $memberships ) ) {
                $schema['memberOf'] = $memberships;
            }
        }
    }

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
}
add_action( 'wp_head', 'corporate_seo_pro_organization_schema' );

/**
 * 創業者・主要メンバーのPerson Schema
 */
function corporate_seo_pro_founder_person_schema() {
    // フロントページまたはAboutページでのみ出力
    if ( ! is_front_page() && ! is_page( 'about' ) && ! is_page( 'company' ) ) {
        return;
    }

    // 創業者のPerson Schema
    $founder_name = get_theme_mod( 'company_founder_name', '師田 賢人' );
    if ( ! empty( $founder_name ) ) {
        $founder_schema = array(
            '@context' => 'https://schema.org',
            '@type'    => 'Person',
            '@id'      => home_url( '/#founder' ),
            'name'     => $founder_name,
            'worksFor' => array(
                '@type' => 'Organization',
                '@id'   => home_url( '/#organization' ),
                'name'  => get_bloginfo( 'name' ),
            ),
        );

        $founder_title = get_theme_mod( 'company_founder_title', '代表取締役' );
        if ( ! empty( $founder_title ) ) {
            $founder_schema['jobTitle'] = $founder_title;
        }

        $founder_image = get_theme_mod( 'company_founder_image' );
        if ( ! empty( $founder_image ) ) {
            $founder_schema['image'] = $founder_image;
        }

        $founder_description = get_theme_mod( 'company_founder_description' );
        if ( ! empty( $founder_description ) ) {
            $founder_schema['description'] = $founder_description;
        }

        // 専門分野
        $founder_schema['knowsAbout'] = array(
            'Webマーケティング',
            'SEO対策',
            '経営戦略',
            'DX推進',
        );

        echo '<script type="application/ld+json">' . wp_json_encode( $founder_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
    }

    // ACFから主要メンバー情報を取得
    if ( function_exists( 'get_field' ) ) {
        $key_people = get_field( 'company_key_people', 'option' );
        if ( $key_people && is_array( $key_people ) ) {
            foreach ( $key_people as $index => $person ) {
                if ( empty( $person['name'] ) ) {
                    continue;
                }

                $person_schema = array(
                    '@context' => 'https://schema.org',
                    '@type'    => 'Person',
                    '@id'      => home_url( '/#person-' . ( $index + 1 ) ),
                    'name'     => $person['name'],
                    'worksFor' => array(
                        '@type' => 'Organization',
                        '@id'   => home_url( '/#organization' ),
                    ),
                );

                if ( ! empty( $person['title'] ) ) {
                    $person_schema['jobTitle'] = $person['title'];
                }

                if ( ! empty( $person['image'] ) && is_array( $person['image'] ) ) {
                    $person_schema['image'] = $person['image']['url'];
                }

                if ( ! empty( $person['description'] ) ) {
                    $person_schema['description'] = $person['description'];
                }

                if ( ! empty( $person['sameAs'] ) && is_array( $person['sameAs'] ) ) {
                    $sameAs_urls = array();
                    foreach ( $person['sameAs'] as $link ) {
                        if ( ! empty( $link['url'] ) ) {
                            $sameAs_urls[] = $link['url'];
                        }
                    }
                    if ( ! empty( $sameAs_urls ) ) {
                        $person_schema['sameAs'] = $sameAs_urls;
                    }
                }

                echo '<script type="application/ld+json">' . wp_json_encode( $person_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
            }
        }
    }
}
add_action( 'wp_head', 'corporate_seo_pro_founder_person_schema' );

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
 * 記事の構造化データ（E-E-A-T対応版）
 */
function corporate_seo_pro_article_schema() {
    if ( ! is_singular( 'post' ) ) {
        return;
    }

    global $post;

    // 著者情報を取得（拡張版があれば使用）
    $author_id = get_the_author_meta( 'ID' );
    if ( function_exists( 'corporate_seo_pro_get_enhanced_author_schema' ) ) {
        $author_schema = corporate_seo_pro_get_enhanced_author_schema( $author_id );
    }

    // 著者スキーマがない場合はデフォルト
    if ( empty( $author_schema ) ) {
        $author_schema = array(
            '@type' => 'Person',
            'name'  => get_the_author(),
            'url'   => get_author_posts_url( $author_id ),
        );
    }

    $schema = array(
        '@context'      => 'https://schema.org',
        '@type'         => 'BlogPosting',
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id'   => get_permalink(),
        ),
        'headline'      => get_the_title(),
        'url'           => get_permalink(),
        'datePublished' => get_the_date( 'c' ),
        'dateModified'  => get_the_modified_date( 'c' ),
        'author'        => $author_schema,
        'publisher'     => array(
            '@type' => 'Organization',
            '@id'   => home_url( '/#organization' ),
            'name'  => get_bloginfo( 'name' ),
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
    if ( ! is_front_page() ) {
        return;
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'ProfessionalService',
        'name' => get_bloginfo( 'name' ),
        'url' => home_url( '/' ),
        'description' => get_bloginfo( 'description' ),
        'areaServed' => array(
            array(
                '@type' => 'City',
                'name' => '千葉市',
                'containedInPlace' => array(
                    '@type' => 'AdministrativeArea',
                    'name' => '千葉県'
                )
            ),
            array(
                '@type' => 'AdministrativeArea',
                'name' => '千葉県'
            )
        ),
        'serviceType' => array(
            'ホームページ制作',
            'Webサイト制作',
            'SEO対策',
            'Webマーケティング'
        ),
        'knowsAbout' => array(
            'ホームページ制作',
            'Webデザイン',
            'WordPress開発',
            'SEO対策',
            'レスポンシブデザイン'
        ),
        'address' => array(
            '@type' => 'PostalAddress',
            'addressLocality' => '千葉市',
            'addressRegion' => '千葉県',
            'addressCountry' => 'JP'
        )
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
            'areaServed' => 'JP',
            'availableLanguage' => 'Japanese'
        );

        if ( get_theme_mod( 'company_phone' ) ) {
            $schema['contactPoint']['telephone'] = get_theme_mod( 'company_phone' );
        }

        if ( get_theme_mod( 'company_email' ) ) {
            $schema['contactPoint']['email'] = get_theme_mod( 'company_email' );
        }
    }

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
    
    // ホーム（地域情報を含む）
    $schema['itemListElement'][] = array(
        '@type' => 'ListItem',
        'position' => $position,
        'name' => get_bloginfo( 'name' ) . ' | 千葉県千葉市',
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
 * ACFフィールドからFAQスキーマを生成
 */
function corporate_seo_pro_acf_faq_schema() {
    if ( ! is_singular() ) {
        return;
    }

    // ACFが有効でない場合はスキップ
    if ( ! function_exists( 'get_field' ) ) {
        return;
    }

    // FAQスキーマが有効か確認
    $enable_faq = get_field( 'enable_faq_schema' );
    if ( ! $enable_faq ) {
        return;
    }

    // FAQ項目を取得
    $faq_items = get_field( 'faq_items' );
    if ( empty( $faq_items ) ) {
        return;
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type'    => 'FAQPage',
        'mainEntity' => array(),
    );

    foreach ( $faq_items as $item ) {
        if ( empty( $item['question'] ) || empty( $item['answer'] ) ) {
            continue;
        }

        $schema['mainEntity'][] = array(
            '@type' => 'Question',
            'name'  => wp_strip_all_tags( $item['question'] ),
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => wp_strip_all_tags( $item['answer'] ),
            ),
        );
    }

    if ( ! empty( $schema['mainEntity'] ) ) {
        echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
    }
}
add_action( 'wp_head', 'corporate_seo_pro_acf_faq_schema' );

/**
 * ACFフィールドからHowToスキーマを生成
 */
function corporate_seo_pro_acf_howto_schema() {
    if ( ! is_singular() ) {
        return;
    }

    // ACFが有効でない場合はスキップ
    if ( ! function_exists( 'get_field' ) ) {
        return;
    }

    // HowToスキーマが有効か確認
    $enable_howto = get_field( 'enable_howto_schema' );
    if ( ! $enable_howto ) {
        return;
    }

    // 手順を取得
    $howto_steps = get_field( 'howto_steps' );
    if ( empty( $howto_steps ) ) {
        return;
    }

    $howto_name = get_field( 'howto_name' ) ?: get_the_title();
    $howto_description = get_field( 'howto_description' );
    $howto_total_time = get_field( 'howto_total_time' );
    $howto_tools = get_field( 'howto_tools' );

    $schema = array(
        '@context' => 'https://schema.org',
        '@type'    => 'HowTo',
        'name'     => $howto_name,
    );

    // 説明
    if ( ! empty( $howto_description ) ) {
        $schema['description'] = wp_strip_all_tags( $howto_description );
    }

    // 所要時間
    if ( ! empty( $howto_total_time ) ) {
        $schema['totalTime'] = $howto_total_time;
    }

    // 必要なツール
    if ( ! empty( $howto_tools ) ) {
        $schema['tool'] = array();
        foreach ( $howto_tools as $tool ) {
            if ( ! empty( $tool['name'] ) ) {
                $schema['tool'][] = array(
                    '@type' => 'HowToTool',
                    'name'  => $tool['name'],
                );
            }
        }
    }

    // 手順
    $schema['step'] = array();
    $position = 1;
    foreach ( $howto_steps as $step ) {
        if ( empty( $step['name'] ) || empty( $step['text'] ) ) {
            continue;
        }

        $step_schema = array(
            '@type'    => 'HowToStep',
            'position' => $position,
            'name'     => wp_strip_all_tags( $step['name'] ),
            'text'     => wp_strip_all_tags( $step['text'] ),
        );

        // 詳細URL
        if ( ! empty( $step['url'] ) ) {
            $step_schema['url'] = esc_url( $step['url'] );
        }

        // 画像
        if ( ! empty( $step['image'] ) ) {
            $step_schema['image'] = array(
                '@type' => 'ImageObject',
                'url'   => esc_url( $step['image'] ),
            );
        }

        $schema['step'][] = $step_schema;
        $position++;
    }

    if ( ! empty( $schema['step'] ) ) {
        echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
    }
}
add_action( 'wp_head', 'corporate_seo_pro_acf_howto_schema' );

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