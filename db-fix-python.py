#!/usr/bin/env python3
"""
Contact Form 7 Database Fix Script (Python version)
データベースを直接修正するPythonスクリプト

使用方法:
1. 必要なパッケージをインストール: pip install mysql-connector-python python-dotenv
2. 実行: python3 db-fix-python.py
"""

import os
import sys
import json
import mysql.connector
from mysql.connector import Error
from dotenv import load_dotenv
import re

# .envファイルを読み込み
load_dotenv()

# データベース接続情報
DB_CONFIG = {
    'host': os.getenv('DB_HOST', 'localhost'),
    'database': os.getenv('DB_NAME'),
    'user': os.getenv('DB_USER'),
    'password': os.getenv('DB_PASSWORD'),
    'port': int(os.getenv('DB_PORT', '3306')),
    'charset': os.getenv('DB_CHARSET', 'utf8mb4'),
    'use_unicode': True,
    'raise_on_warnings': True
}

# テーブルプレフィックス
PREFIX = os.getenv('DB_PREFIX', 'wp_')

def serialize_php(data):
    """PHPのserialize形式に変換（簡易版）"""
    if isinstance(data, dict):
        items = []
        for k, v in data.items():
            key_ser = serialize_php(k)
            val_ser = serialize_php(v)
            items.append(key_ser + val_ser)
        return f'a:{len(data)}:{{{"".join(items)}}}'
    elif isinstance(data, str):
        return f's:{len(data.encode("utf-8"))}:"{data}";'
    elif isinstance(data, int):
        return f'i:{data};'
    elif isinstance(data, bool):
        return f'b:{1 if data else 0};'
    elif data is None:
        return 'N;'
    else:
        return f's:{len(str(data))}:"{data}";'

def unserialize_php(data):
    """PHPのunserialize（簡易版）"""
    # 非常に基本的な実装のため、複雑なデータ構造には対応していません
    # 実際のデータ修正はSQL直接実行で行います
    return None

def get_default_messages():
    """デフォルトのメッセージを返す"""
    return {
        'mail_sent_ok': 'ありがとうございます。メッセージは送信されました。',
        'mail_sent_ng': 'メッセージの送信に失敗しました。後でまたお試しください。',
        'validation_error': '入力内容に問題があります。確認して再度お試しください。',
        'spam': 'メッセージの送信に失敗しました。後でまたお試しください。',
        'accept_terms': '承諾が必要です。',
        'invalid_required': '必須項目です。',
        'invalid_too_long': '入力された文字列が長すぎます。',
        'invalid_too_short': '入力された文字列が短すぎます。',
        'invalid_email': '正しいメールアドレスの形式で入力してください。',
        'invalid_url': '正しいURLの形式で入力してください。',
        'invalid_tel': '正しい電話番号の形式で入力してください。',
        'quiz_answer_not_correct': 'クイズの答えが正しくありません。',
        'invalid_date': '日付の形式が正しくありません。',
        'date_too_early': '選択された日付が早すぎます。',
        'date_too_late': '選択された日付が遅すぎます。',
        'upload_failed': 'ファイルのアップロードに失敗しました。',
        'upload_file_type_invalid': '許可されていないファイル形式です。',
        'upload_file_too_large': 'アップロードされたファイルが大きすぎます。',
        'upload_failed_php_error': 'ファイルのアップロード中にエラーが発生しました。',
        'invalid_number': '数値の形式が正しくありません。',
        'number_too_small': '入力された数値が小さすぎます。',
        'number_too_large': '入力された数値が大きすぎます。',
    }

def main():
    print("Contact Form 7 Database Fix Script (Python)")
    print("=" * 50)
    
    # 接続情報を表示
    print(f"Host: {DB_CONFIG['host']}:{DB_CONFIG['port']}")
    print(f"Database: {DB_CONFIG['database']}")
    print(f"User: {DB_CONFIG['user']}")
    print(f"Table prefix: {PREFIX}")
    
    if not DB_CONFIG['database'] or not DB_CONFIG['user']:
        print("\n❌ Error: Database name and user are required in .env file")
        sys.exit(1)
    
    try:
        # データベースに接続
        print("\nConnecting to database...")
        connection = mysql.connector.connect(**DB_CONFIG)
        
        if connection.is_connected():
            print("✅ Connected successfully!")
            cursor = connection.cursor(dictionary=True)
            
            # 1. Contact Form 7のフォームを確認
            print("\n1. Checking Contact Form 7 forms...")
            query = f"""
                SELECT ID, post_title, post_content 
                FROM {PREFIX}posts 
                WHERE post_type = 'wpcf7_contact_form' 
                AND post_status = 'publish'
            """
            cursor.execute(query)
            forms = cursor.fetchall()
            
            print(f"Found {len(forms)} forms")
            
            for form in forms:
                print(f"- Form ID: {form['ID']} - {form['post_title']}")
                
                # additional_settingsをチェック
                content = form['post_content']
                if content and 'skip_mail' in str(content):
                    print(f"  ⚠️  WARNING: contains skip_mail setting!")
                if content and 'messages";s:' in str(content):
                    print(f"  ⚠️  WARNING: messages might be stored as string!")
            
            # 2. postmetaから_messagesを削除
            print("\n2. Removing _messages from postmeta...")
            query = f"""
                DELETE FROM {PREFIX}postmeta 
                WHERE meta_key = '_messages' 
                AND post_id IN (
                    SELECT ID FROM {PREFIX}posts 
                    WHERE post_type = 'wpcf7_contact_form'
                )
            """
            cursor.execute(query)
            affected = cursor.rowcount
            connection.commit()
            print(f"✅ Removed {affected} _messages entries")
            
            # 3. additional_settingsから skip_mail を削除
            print("\n3. Removing skip_mail from additional_settings...")
            for form in forms:
                content = form['post_content']
                if content and 'skip_mail' in str(content):
                    # PHPシリアライズデータの場合、正規表現で修正
                    updated_content = re.sub(
                        r'skip_mail:\s*on_sent_ok\s*\n?',
                        '',
                        str(content),
                        flags=re.IGNORECASE
                    )
                    
                    if updated_content != content:
                        # 更新クエリ
                        update_query = f"""
                            UPDATE {PREFIX}posts 
                            SET post_content = %s 
                            WHERE ID = %s
                        """
                        cursor.execute(update_query, (updated_content, form['ID']))
                        connection.commit()
                        print(f"✅ Updated form ID: {form['ID']}")
            
            # 4. オプションをクリア
            print("\n4. Clearing fix flags...")
            query = f"DELETE FROM {PREFIX}options WHERE option_name LIKE 'cf7_messages_fix_applied%'"
            cursor.execute(query)
            connection.commit()
            
            # 5. 特定のフォームの詳細を表示（デバッグ用）
            print("\n5. Form details (for debugging):")
            cursor.execute(f"""
                SELECT ID, post_title, 
                       SUBSTRING(post_content, 1, 200) as content_preview
                FROM {PREFIX}posts 
                WHERE post_type = 'wpcf7_contact_form' 
                AND post_status = 'publish'
            """)
            for form in cursor.fetchall():
                print(f"\nForm ID {form['ID']}: {form['post_title']}")
                print(f"Content preview: {form['content_preview']}...")
            
            print("\n✅ Database fix completed successfully!")
            print("Please clear your WordPress cache and try accessing Contact Form 7 admin page.")
            
    except Error as e:
        print(f"\n❌ Database error: {e}")
        sys.exit(1)
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()
            print("\nDatabase connection closed.")

if __name__ == "__main__":
    main()