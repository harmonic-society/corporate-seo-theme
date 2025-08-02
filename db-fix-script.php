<?php
/**
 * Contact Form 7 Database Fix Script
 * このスクリプトを直接実行してデータベースを修正します
 * 
 * 使用方法:
 * 1. .envファイルに正しいデータベース情報を設定
 * 2. コマンドラインから実行: php db-fix-script.php
 */

// .envファイルを読み込む関数
function loadEnv($path) {
    if (!file_exists($path)) {
        die("Error: .env file not found at: $path\n");
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// .envファイルを読み込み
$envPath = __DIR__ . '/.env';
if (!file_exists($envPath)) {
    die("Error: .env file not found. Please copy .env.example to .env and update with your database credentials.\n");
}

loadEnv($envPath);

// データベース接続情報を取得
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');
$port = getenv('DB_PORT') ?: '3306';
$prefix = getenv('DB_PREFIX') ?: 'wp_';
$charset = getenv('DB_CHARSET') ?: 'utf8mb4';

if (!$dbname || !$user) {
    die("Error: Database name and user are required in .env file\n");
}

echo "Connecting to database...\n";
echo "Host: $host:$port\n";
echo "Database: $dbname\n";
echo "User: $user\n";
echo "Table prefix: $prefix\n";

try {
    // データベースに接続
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "\nConnected successfully!\n";
    
    // 1. Contact Form 7のフォームを確認
    echo "\n1. Checking Contact Form 7 forms...\n";
    $stmt = $pdo->prepare("
        SELECT ID, post_title, post_content 
        FROM {$prefix}posts 
        WHERE post_type = 'wpcf7_contact_form' 
        AND post_status = 'publish'
    ");
    $stmt->execute();
    $forms = $stmt->fetchAll();
    
    echo "Found " . count($forms) . " forms\n";
    
    foreach ($forms as $form) {
        echo "- Form ID: {$form['ID']} - {$form['post_title']}\n";
        
        // post_contentをチェック
        $content = $form['post_content'];
        if (is_serialized($content)) {
            $data = @unserialize($content);
            if ($data && isset($data['messages']) && !is_array($data['messages'])) {
                echo "  WARNING: messages is not an array!\n";
            }
            if ($data && isset($data['additional_settings']) && strpos($data['additional_settings'], 'skip_mail') !== false) {
                echo "  WARNING: contains skip_mail setting!\n";
            }
        }
    }
    
    // 2. postmetaから_messagesを削除
    echo "\n2. Removing _messages from postmeta...\n";
    $stmt = $pdo->prepare("
        DELETE FROM {$prefix}postmeta 
        WHERE meta_key = '_messages' 
        AND post_id IN (
            SELECT ID FROM {$prefix}posts 
            WHERE post_type = 'wpcf7_contact_form'
        )
    ");
    $affected = $stmt->execute();
    echo "Removed $affected _messages entries\n";
    
    // 3. 各フォームのデータを修正
    echo "\n3. Fixing form data...\n";
    foreach ($forms as $form) {
        $updated = false;
        $content = $form['post_content'];
        
        if (is_serialized($content)) {
            $data = @unserialize($content);
            
            if ($data && is_array($data)) {
                // messagesを修正
                if (isset($data['messages']) && !is_array($data['messages'])) {
                    echo "Fixing messages for form ID: {$form['ID']}\n";
                    $data['messages'] = getDefaultMessages();
                    $updated = true;
                }
                
                // additional_settingsを修正
                if (isset($data['additional_settings'])) {
                    $cleaned = preg_replace('/skip_mail:\s*on_sent_ok\s*\n?/i', '', $data['additional_settings']);
                    if ($cleaned !== $data['additional_settings']) {
                        echo "Removing skip_mail from form ID: {$form['ID']}\n";
                        $data['additional_settings'] = $cleaned;
                        $updated = true;
                    }
                }
                
                if ($updated) {
                    $newContent = serialize($data);
                    $stmt = $pdo->prepare("UPDATE {$prefix}posts SET post_content = :content WHERE ID = :id");
                    $stmt->execute([
                        ':content' => $newContent,
                        ':id' => $form['ID']
                    ]);
                    echo "Updated form ID: {$form['ID']}\n";
                }
            }
        }
    }
    
    // 4. オプションをクリア
    echo "\n4. Clearing fix flags...\n";
    $stmt = $pdo->prepare("DELETE FROM {$prefix}options WHERE option_name LIKE 'cf7_messages_fix_applied%'");
    $stmt->execute();
    
    echo "\n✅ Database fix completed successfully!\n";
    echo "Please clear your WordPress cache and try accessing Contact Form 7 admin page.\n";
    
} catch (PDOException $e) {
    die("\n❌ Database connection failed: " . $e->getMessage() . "\n");
}

function is_serialized($data) {
    if (!is_string($data)) {
        return false;
    }
    $data = trim($data);
    if ('N;' == $data) {
        return true;
    }
    if (strlen($data) < 4) {
        return false;
    }
    if (':' !== $data[1]) {
        return false;
    }
    $lastc = substr($data, -1);
    if (';' !== $lastc && '}' !== $lastc) {
        return false;
    }
    $token = $data[0];
    switch ($token) {
        case 's':
            if ('"' !== substr($data, -2, 1)) {
                return false;
            }
        case 'a':
        case 'O':
            return (bool)preg_match("/^{$token}:[0-9]+:/s", $data);
        case 'b':
        case 'i':
        case 'd':
            return (bool)preg_match("/^{$token}:[0-9.E-]+;$/", $data);
    }
    return false;
}

function getDefaultMessages() {
    return [
        'mail_sent_ok' => 'ありがとうございます。メッセージは送信されました。',
        'mail_sent_ng' => 'メッセージの送信に失敗しました。後でまたお試しください。',
        'validation_error' => '入力内容に問題があります。確認して再度お試しください。',
        'spam' => 'メッセージの送信に失敗しました。後でまたお試しください。',
        'accept_terms' => '承諾が必要です。',
        'invalid_required' => '必須項目です。',
        'invalid_too_long' => '入力された文字列が長すぎます。',
        'invalid_too_short' => '入力された文字列が短すぎます。',
        'invalid_email' => '正しいメールアドレスの形式で入力してください。',
        'invalid_url' => '正しいURLの形式で入力してください。',
        'invalid_tel' => '正しい電話番号の形式で入力してください。',
        'quiz_answer_not_correct' => 'クイズの答えが正しくありません。',
        'invalid_date' => '日付の形式が正しくありません。',
        'date_too_early' => '選択された日付が早すぎます。',
        'date_too_late' => '選択された日付が遅すぎます。',
        'upload_failed' => 'ファイルのアップロードに失敗しました。',
        'upload_file_type_invalid' => '許可されていないファイル形式です。',
        'upload_file_too_large' => 'アップロードされたファイルが大きすぎます。',
        'upload_failed_php_error' => 'ファイルのアップロード中にエラーが発生しました。',
        'invalid_number' => '数値の形式が正しくありません。',
        'number_too_small' => '入力された数値が小さすぎます。',
        'number_too_large' => '入力された数値が大きすぎます。',
    ];
}