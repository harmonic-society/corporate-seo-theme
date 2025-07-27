<?php
/**
 * セキュリティヘッダーの実装
 *
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * セキュリティヘッダーの送信
 */
function corporate_seo_pro_security_headers() {
    if ( is_admin() ) {
        return;
    }
    
    // X-Frame-Options: クリックジャッキング対策
    header( 'X-Frame-Options: SAMEORIGIN' );
    
    // X-Content-Type-Options: MIMEタイプスニッフィング防止
    header( 'X-Content-Type-Options: nosniff' );
    
    // X-XSS-Protection: XSS攻撃対策（古いブラウザ向け）
    header( 'X-XSS-Protection: 1; mode=block' );
    
    // Referrer-Policy: リファラー情報の制御
    header( 'Referrer-Policy: strict-origin-when-cross-origin' );
    
    // Permissions-Policy: 機能の制限
    $permissions = array(
        'geolocation' => '(self)',
        'microphone' => '()',
        'camera' => '()',
        'payment' => '(self)',
        'usb' => '()',
        'magnetometer' => '()',
        'accelerometer' => '(self)',
        'gyroscope' => '(self)',
    );
    
    $permissions_header = array();
    foreach ( $permissions as $feature => $policy ) {
        $permissions_header[] = $feature . '=' . $policy;
    }
    
    header( 'Permissions-Policy: ' . implode( ', ', $permissions_header ) );
    
    // Content-Security-Policy: コンテンツセキュリティポリシー
    $csp_directives = corporate_seo_pro_get_csp_directives();
    header( 'Content-Security-Policy: ' . $csp_directives );
    
    // Strict-Transport-Security: HTTPS強制（HTTPSサイトのみ）
    if ( is_ssl() ) {
        header( 'Strict-Transport-Security: max-age=31536000; includeSubDomains; preload' );
    }
}
add_action( 'send_headers', 'corporate_seo_pro_security_headers' );

/**
 * CSPディレクティブの生成
 */
function corporate_seo_pro_get_csp_directives() {
    $directives = array(
        "default-src 'self'",
        "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google-analytics.com https://www.googletagmanager.com https://ajax.googleapis.com https://cdnjs.cloudflare.com",
        "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com",
        "img-src 'self' data: https: http: blob:",
        "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com data:",
        "connect-src 'self' https://www.google-analytics.com https://www.googletagmanager.com",
        "media-src 'self' https: blob:",
        "object-src 'none'",
        "frame-src 'self' https://www.youtube.com https://www.google.com",
        "base-uri 'self'",
        "form-action 'self'",
        "frame-ancestors 'self'",
        "upgrade-insecure-requests",
    );
    
    // 開発環境では緩いポリシー
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        $directives = array(
            "default-src *",
            "script-src * 'unsafe-inline' 'unsafe-eval'",
            "style-src * 'unsafe-inline'",
        );
    }
    
    return implode( '; ', apply_filters( 'corporate_seo_pro_csp_directives', $directives ) );
}

/**
 * セキュリティ関連のメタタグ
 */
function corporate_seo_pro_security_meta_tags() {
    // IE互換モード無効化
    echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">' . "\n";
    
    // DNS Prefetch Control
    echo '<meta http-equiv="x-dns-prefetch-control" content="on">' . "\n";
    
    // Format Detection無効化（電話番号の自動リンク化防止）
    echo '<meta name="format-detection" content="telephone=no">' . "\n";
}
add_action( 'wp_head', 'corporate_seo_pro_security_meta_tags', 0 );

/**
 * 不要な情報の削除
 */
function corporate_seo_pro_remove_sensitive_info() {
    // WordPressバージョン情報の削除
    remove_action( 'wp_head', 'wp_generator' );
    
    // WLW Manifest
    remove_action( 'wp_head', 'wlwmanifest_link' );
    
    // RSD Link
    remove_action( 'wp_head', 'rsd_link' );
    
    // Shortlink
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );
    
    // REST API
    remove_action( 'wp_head', 'rest_output_link_wp_head' );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    
    // フィードリンク
    remove_action( 'wp_head', 'feed_links', 2 );
    remove_action( 'wp_head', 'feed_links_extra', 3 );
    
    // ログインエラーメッセージの曖昧化
    add_filter( 'login_errors', function() {
        return 'ログイン情報が正しくありません。';
    } );
}
add_action( 'init', 'corporate_seo_pro_remove_sensitive_info' );

/**
 * ファイルアップロードの制限
 */
function corporate_seo_pro_upload_mimes( $mimes ) {
    // 危険なファイルタイプを削除
    unset( $mimes['exe'] );
    unset( $mimes['bat'] );
    unset( $mimes['cmd'] );
    unset( $mimes['sh'] );
    unset( $mimes['php'] );
    unset( $mimes['pl'] );
    unset( $mimes['cgi'] );
    unset( $mimes['386'] );
    unset( $mimes['dll'] );
    unset( $mimes['com'] );
    unset( $mimes['torrent'] );
    unset( $mimes['js'] );
    unset( $mimes['app'] );
    unset( $mimes['jar'] );
    unset( $mimes['pif'] );
    unset( $mimes['vb'] );
    unset( $mimes['vbscript'] );
    unset( $mimes['wsf'] );
    unset( $mimes['asp'] );
    unset( $mimes['cer'] );
    unset( $mimes['csr'] );
    unset( $mimes['jsp'] );
    unset( $mimes['drv'] );
    unset( $mimes['sys'] );
    unset( $mimes['ade'] );
    unset( $mimes['adp'] );
    unset( $mimes['bas'] );
    unset( $mimes['chm'] );
    unset( $mimes['cpl'] );
    unset( $mimes['crt'] );
    unset( $mimes['csh'] );
    unset( $mimes['fxp'] );
    unset( $mimes['hlp'] );
    unset( $mimes['hta'] );
    unset( $mimes['inf'] );
    unset( $mimes['ins'] );
    unset( $mimes['isp'] );
    unset( $mimes['jse'] );
    unset( $mimes['htaccess'] );
    unset( $mimes['htpasswd'] );
    
    return $mimes;
}
add_filter( 'upload_mimes', 'corporate_seo_pro_upload_mimes' );

/**
 * XML-RPC無効化
 */
function corporate_seo_pro_disable_xmlrpc() {
    add_filter( 'xmlrpc_enabled', '__return_false' );
    
    // XML-RPCへのアクセスをブロック
    if ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) {
        die( 'XML-RPC is disabled on this site.' );
    }
}
add_action( 'init', 'corporate_seo_pro_disable_xmlrpc' );

/**
 * REST APIの制限
 */
function corporate_seo_pro_restrict_rest_api( $result ) {
    if ( ! is_user_logged_in() ) {
        // 一部のエンドポイントは許可
        $allowed_routes = array(
            '/wp/v2/posts',
            '/wp/v2/pages',
            '/wp/v2/categories',
            '/wp/v2/tags',
            '/wp/v2/media',
        );
        
        $current_route = $_SERVER['REQUEST_URI'];
        $is_allowed = false;
        
        foreach ( $allowed_routes as $route ) {
            if ( strpos( $current_route, $route ) !== false ) {
                $is_allowed = true;
                break;
            }
        }
        
        if ( ! $is_allowed ) {
            return new WP_Error( 'rest_disabled', __( 'REST APIは無効化されています。', 'corporate-seo-pro' ), array( 'status' => 401 ) );
        }
    }
    
    return $result;
}
add_filter( 'rest_authentication_errors', 'corporate_seo_pro_restrict_rest_api' );

/**
 * ユーザー列挙の防止
 */
function corporate_seo_pro_prevent_user_enumeration() {
    if ( isset( $_GET['author'] ) && ! is_admin() ) {
        wp_redirect( home_url(), 301 );
        exit;
    }
}
add_action( 'init', 'corporate_seo_pro_prevent_user_enumeration' );

/**
 * コメントスパム対策
 */
function corporate_seo_pro_comment_spam_protection( $commentdata ) {
    // URLが多すぎるコメントを拒否
    $url_count = preg_match_all( '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/', $commentdata['comment_content'], $matches );
    
    if ( $url_count > 2 ) {
        wp_die( 'コメントにURLが多すぎます。' );
    }
    
    // 日本語が含まれないコメントを拒否（日本語サイトの場合）
    if ( ! preg_match( '/[ぁ-んァ-ヶー一-龠]+/u', $commentdata['comment_content'] ) ) {
        wp_die( 'コメントには日本語を含めてください。' );
    }
    
    return $commentdata;
}
add_filter( 'preprocess_comment', 'corporate_seo_pro_comment_spam_protection' );

/**
 * ログインページのセキュリティ
 */
function corporate_seo_pro_login_security() {
    // ログインページへの直接アクセスを制限
    if ( ! defined( 'CORPORATE_SEO_ALLOW_LOGIN' ) || ! CORPORATE_SEO_ALLOW_LOGIN ) {
        $ip_whitelist = array(
            // 許可するIPアドレスをここに追加
            // '123.456.789.0',
        );
        
        $user_ip = $_SERVER['REMOTE_ADDR'];
        
        if ( ! in_array( $user_ip, $ip_whitelist ) && strpos( $_SERVER['REQUEST_URI'], 'wp-login.php' ) !== false ) {
            // カスタムログインURLを使用する場合はリダイレクト
            // wp_redirect( home_url( '/secure-login/' ) );
            // exit;
        }
    }
}
add_action( 'init', 'corporate_seo_pro_login_security' );

/**
 * ファイル編集の無効化
 */
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
    define( 'DISALLOW_FILE_EDIT', true );
}

/**
 * セキュリティ監査ログ
 */
function corporate_seo_pro_security_log( $action, $details = array() ) {
    if ( ! defined( 'CORPORATE_SEO_SECURITY_LOG' ) || ! CORPORATE_SEO_SECURITY_LOG ) {
        return;
    }
    
    $log_data = array(
        'timestamp' => current_time( 'mysql' ),
        'action' => $action,
        'user_id' => get_current_user_id(),
        'ip_address' => $_SERVER['REMOTE_ADDR'],
        'user_agent' => $_SERVER['HTTP_USER_AGENT'],
        'details' => $details,
    );
    
    // ログをデータベースまたはファイルに保存
    $log_file = WP_CONTENT_DIR . '/security-logs/security-' . date( 'Y-m-d' ) . '.log';
    $log_dir = dirname( $log_file );
    
    if ( ! file_exists( $log_dir ) ) {
        wp_mkdir_p( $log_dir );
        
        // .htaccessでログディレクトリを保護
        $htaccess_content = "Order deny,allow\nDeny from all";
        file_put_contents( $log_dir . '/.htaccess', $htaccess_content );
    }
    
    $log_entry = date( 'Y-m-d H:i:s' ) . ' - ' . json_encode( $log_data ) . PHP_EOL;
    file_put_contents( $log_file, $log_entry, FILE_APPEND | LOCK_EX );
}

/**
 * 不審なアクティビティの検出
 */
function corporate_seo_pro_detect_suspicious_activity() {
    // SQLインジェクション検出
    $suspicious_patterns = array(
        '/union.*select/i',
        '/select.*from.*information_schema/i',
        '/\' or \'1\'=\'1/i',
        '/\"; drop table/i',
        '/script.*alert/i',
        '/\<script\>/i',
    );
    
    $request_uri = $_SERVER['REQUEST_URI'];
    $query_string = $_SERVER['QUERY_STRING'];
    
    foreach ( $suspicious_patterns as $pattern ) {
        if ( preg_match( $pattern, $request_uri ) || preg_match( $pattern, $query_string ) ) {
            corporate_seo_pro_security_log( 'suspicious_activity', array(
                'pattern' => $pattern,
                'request_uri' => $request_uri,
                'query_string' => $query_string,
            ) );
            
            wp_die( 'Forbidden', 'Forbidden', array( 'response' => 403 ) );
        }
    }
}
add_action( 'init', 'corporate_seo_pro_detect_suspicious_activity', 1 );