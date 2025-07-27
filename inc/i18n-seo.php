<?php
/**
 * 国際化とhreflangタグ実装
 *
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * hreflangタグの出力
 */
function corporate_seo_pro_hreflang_tags() {
    // 多言語サイトの設定
    $languages = array(
        'ja' => array(
            'locale' => 'ja_JP',
            'name' => '日本語',
            'url_prefix' => '',
            'region' => 'JP',
        ),
        'en' => array(
            'locale' => 'en_US',
            'name' => 'English',
            'url_prefix' => '/en',
            'region' => 'US',
        ),
        'zh' => array(
            'locale' => 'zh_CN',
            'name' => '中文',
            'url_prefix' => '/zh',
            'region' => 'CN',
        ),
    );
    
    // Polylangプラグインがある場合
    if ( function_exists( 'pll_languages_list' ) ) {
        corporate_seo_pro_polylang_hreflang();
        return;
    }
    
    // WPMLプラグインがある場合
    if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
        corporate_seo_pro_wpml_hreflang();
        return;
    }
    
    // カスタム実装
    $current_url = corporate_seo_pro_get_current_url();
    $current_lang = corporate_seo_pro_get_current_language();
    
    foreach ( $languages as $lang_code => $lang_data ) {
        $alt_url = corporate_seo_pro_get_alternate_url( $current_url, $lang_code );
        
        if ( $alt_url ) {
            $hreflang = $lang_code;
            if ( ! empty( $lang_data['region'] ) ) {
                $hreflang .= '-' . $lang_data['region'];
            }
            
            echo '<link rel="alternate" hreflang="' . esc_attr( $hreflang ) . '" href="' . esc_url( $alt_url ) . '" />' . "\n";
        }
    }
    
    // x-default タグ
    $default_url = corporate_seo_pro_get_default_language_url( $current_url );
    echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $default_url ) . '" />' . "\n";
}
add_action( 'wp_head', 'corporate_seo_pro_hreflang_tags', 1 );

/**
 * 現在のURLを取得
 */
function corporate_seo_pro_get_current_url() {
    global $wp;
    return home_url( add_query_arg( array(), $wp->request ) );
}

/**
 * 現在の言語を取得
 */
function corporate_seo_pro_get_current_language() {
    // URLから言語を判定
    $uri = $_SERVER['REQUEST_URI'];
    
    if ( strpos( $uri, '/en/' ) !== false || strpos( $uri, '/en' ) === 0 ) {
        return 'en';
    } elseif ( strpos( $uri, '/zh/' ) !== false || strpos( $uri, '/zh' ) === 0 ) {
        return 'zh';
    }
    
    // デフォルトは日本語
    return 'ja';
}

/**
 * 代替言語URLを取得
 */
function corporate_seo_pro_get_alternate_url( $url, $lang_code ) {
    $parsed_url = parse_url( $url );
    $path = isset( $parsed_url['path'] ) ? $parsed_url['path'] : '/';
    
    // 現在の言語プレフィックスを削除
    $path = preg_replace( '#^/(en|zh)(/|$)#', '/', $path );
    
    // 新しい言語プレフィックスを追加
    if ( $lang_code !== 'ja' ) {
        $path = '/' . $lang_code . $path;
    }
    
    $alternate_url = $parsed_url['scheme'] . '://' . $parsed_url['host'];
    if ( isset( $parsed_url['port'] ) ) {
        $alternate_url .= ':' . $parsed_url['port'];
    }
    $alternate_url .= $path;
    
    if ( isset( $parsed_url['query'] ) ) {
        $alternate_url .= '?' . $parsed_url['query'];
    }
    
    return $alternate_url;
}

/**
 * デフォルト言語URLを取得
 */
function corporate_seo_pro_get_default_language_url( $url ) {
    // 日本語をデフォルトとする
    return corporate_seo_pro_get_alternate_url( $url, 'ja' );
}

/**
 * Polylang用hreflang実装
 */
function corporate_seo_pro_polylang_hreflang() {
    if ( ! function_exists( 'pll_the_languages' ) ) {
        return;
    }
    
    $languages = pll_the_languages( array( 'raw' => 1 ) );
    
    foreach ( $languages as $language ) {
        $lang_code = $language['slug'];
        $locale = $language['locale'];
        $url = $language['url'];
        
        // locale形式をhreflang形式に変換
        $hreflang = str_replace( '_', '-', $locale );
        
        echo '<link rel="alternate" hreflang="' . esc_attr( $hreflang ) . '" href="' . esc_url( $url ) . '" />' . "\n";
    }
    
    // x-defaultタグ
    $default_lang = pll_default_language();
    $default_url = pll_home_url( $default_lang );
    echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $default_url ) . '" />' . "\n";
}

/**
 * WPML用hreflang実装
 */
function corporate_seo_pro_wpml_hreflang() {
    $languages = apply_filters( 'wpml_active_languages', NULL );
    
    if ( empty( $languages ) ) {
        return;
    }
    
    foreach ( $languages as $language ) {
        $lang_code = $language['language_code'];
        $url = $language['url'];
        
        // 地域コードを追加
        $hreflang = corporate_seo_pro_get_hreflang_with_region( $lang_code );
        
        echo '<link rel="alternate" hreflang="' . esc_attr( $hreflang ) . '" href="' . esc_url( $url ) . '" />' . "\n";
    }
    
    // x-defaultタグ
    $default_language = apply_filters( 'wpml_default_language', NULL );
    $default_url = apply_filters( 'wpml_permalink', home_url(), $default_language );
    echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $default_url ) . '" />' . "\n";
}

/**
 * 言語コードに地域コードを追加
 */
function corporate_seo_pro_get_hreflang_with_region( $lang_code ) {
    $language_regions = array(
        'ja' => 'ja-JP',
        'en' => 'en-US',
        'zh' => 'zh-CN',
        'ko' => 'ko-KR',
        'es' => 'es-ES',
        'fr' => 'fr-FR',
        'de' => 'de-DE',
        'it' => 'it-IT',
        'pt' => 'pt-BR',
        'ru' => 'ru-RU',
    );
    
    return isset( $language_regions[ $lang_code ] ) ? $language_regions[ $lang_code ] : $lang_code;
}

/**
 * 言語切り替えメニュー
 */
function corporate_seo_pro_language_switcher( $args = array() ) {
    $defaults = array(
        'show_names' => true,
        'show_flags' => true,
        'dropdown' => false,
        'echo' => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $output = '<div class="language-switcher">';
    
    if ( function_exists( 'pll_the_languages' ) ) {
        // Polylang
        $output .= pll_the_languages( array(
            'show_names' => $args['show_names'],
            'show_flags' => $args['show_flags'],
            'dropdown' => $args['dropdown'],
            'echo' => 0,
        ) );
    } elseif ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
        // WPML
        $languages = apply_filters( 'wpml_active_languages', NULL );
        
        if ( $args['dropdown'] ) {
            $output .= '<select class="language-selector" onchange="location.href=this.value;">';
            foreach ( $languages as $language ) {
                $selected = $language['active'] ? ' selected' : '';
                $output .= '<option value="' . esc_url( $language['url'] ) . '"' . $selected . '>';
                if ( $args['show_flags'] ) {
                    $output .= $language['language_code'] . ' - ';
                }
                if ( $args['show_names'] ) {
                    $output .= $language['native_name'];
                }
                $output .= '</option>';
            }
            $output .= '</select>';
        } else {
            $output .= '<ul class="language-list">';
            foreach ( $languages as $language ) {
                $active_class = $language['active'] ? ' class="active"' : '';
                $output .= '<li' . $active_class . '>';
                $output .= '<a href="' . esc_url( $language['url'] ) . '">';
                if ( $args['show_flags'] ) {
                    $output .= '<img src="' . $language['country_flag_url'] . '" alt="' . $language['language_code'] . '" /> ';
                }
                if ( $args['show_names'] ) {
                    $output .= $language['native_name'];
                }
                $output .= '</a></li>';
            }
            $output .= '</ul>';
        }
    } else {
        // カスタム実装
        $languages = array(
            'ja' => array( 'name' => '日本語', 'flag' => '🇯🇵' ),
            'en' => array( 'name' => 'English', 'flag' => '🇺🇸' ),
            'zh' => array( 'name' => '中文', 'flag' => '🇨🇳' ),
        );
        
        $current_lang = corporate_seo_pro_get_current_language();
        $current_url = corporate_seo_pro_get_current_url();
        
        if ( $args['dropdown'] ) {
            $output .= '<select class="language-selector" onchange="location.href=this.value;">';
            foreach ( $languages as $lang_code => $lang_data ) {
                $url = corporate_seo_pro_get_alternate_url( $current_url, $lang_code );
                $selected = ( $lang_code === $current_lang ) ? ' selected' : '';
                $output .= '<option value="' . esc_url( $url ) . '"' . $selected . '>';
                if ( $args['show_flags'] ) {
                    $output .= $lang_data['flag'] . ' ';
                }
                if ( $args['show_names'] ) {
                    $output .= $lang_data['name'];
                }
                $output .= '</option>';
            }
            $output .= '</select>';
        } else {
            $output .= '<ul class="language-list">';
            foreach ( $languages as $lang_code => $lang_data ) {
                $url = corporate_seo_pro_get_alternate_url( $current_url, $lang_code );
                $active_class = ( $lang_code === $current_lang ) ? ' class="active"' : '';
                $output .= '<li' . $active_class . '>';
                $output .= '<a href="' . esc_url( $url ) . '">';
                if ( $args['show_flags'] ) {
                    $output .= '<span class="flag">' . $lang_data['flag'] . '</span> ';
                }
                if ( $args['show_names'] ) {
                    $output .= $lang_data['name'];
                }
                $output .= '</a></li>';
            }
            $output .= '</ul>';
        }
    }
    
    $output .= '</div>';
    
    // スタイル
    $output .= '<style>
    .language-switcher {
        display: inline-block;
    }
    .language-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        gap: 1rem;
    }
    .language-list li {
        display: inline-block;
    }
    .language-list a {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.5rem;
        text-decoration: none;
        color: inherit;
        transition: all 0.2s ease;
    }
    .language-list a:hover {
        color: #3b82f6;
    }
    .language-list .active a {
        font-weight: bold;
        color: #1e3a8a;
    }
    .language-selector {
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        background-color: white;
        font-size: 1rem;
        cursor: pointer;
    }
    .language-selector:hover {
        border-color: #9ca3af;
    }
    .flag {
        font-size: 1.25rem;
    }
    </style>';
    
    if ( $args['echo'] ) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * 言語別のメタデータ
 */
function corporate_seo_pro_localized_meta_tags() {
    $current_lang = corporate_seo_pro_get_current_language();
    
    // 言語別のOG:locale
    $og_locales = array(
        'ja' => 'ja_JP',
        'en' => 'en_US',
        'zh' => 'zh_CN',
    );
    
    if ( isset( $og_locales[ $current_lang ] ) ) {
        echo '<meta property="og:locale" content="' . esc_attr( $og_locales[ $current_lang ] ) . '" />' . "\n";
    }
    
    // 言語宣言
    echo '<meta http-equiv="content-language" content="' . esc_attr( $current_lang ) . '" />' . "\n";
}
add_action( 'wp_head', 'corporate_seo_pro_localized_meta_tags', 2 );