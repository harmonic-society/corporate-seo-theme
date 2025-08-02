<?php
/**
 * Checkbox Debug Helper
 * 
 * チェックボックスの表示問題をデバッグするためのヘルパー
 * 
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * ContactページでチェックボックスのデバッグCSSを追加
 */
function corporate_seo_pro_checkbox_debug_css() {
    if ( is_page_template( 'page-contact.php' ) ) {
        ?>
        <style id="checkbox-debug-css">
            /* チェックボックスデバッグ用CSS */
            .form-checkbox {
                border: 2px dashed red !important;
                position: relative !important;
            }
            
            .form-checkbox::before {
                content: "Privacy Policy Checkbox Area" !important;
                position: absolute !important;
                top: -20px !important;
                left: 0 !important;
                font-size: 12px !important;
                color: red !important;
                background: yellow !important;
                padding: 2px 5px !important;
            }
            
            /* すべてのチェックボックスを強制表示 */
            input[type="checkbox"] {
                opacity: 1 !important;
                visibility: visible !important;
                width: 20px !important;
                height: 20px !important;
                position: relative !important;
                z-index: 9999 !important;
                display: inline-block !important;
                -webkit-appearance: checkbox !important;
                -moz-appearance: checkbox !important;
                appearance: checkbox !important;
            }
            
            /* デバッグ情報を表示 */
            .checkbox-label::after {
                content: " (Checkbox should be visible here)" !important;
                color: blue !important;
                font-size: 11px !important;
            }
        </style>
        
        <script>
        // チェックボックスの存在確認
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== Checkbox Debug ===');
            const checkbox = document.querySelector('input[name="privacy_policy"]');
            if (checkbox) {
                console.log('Checkbox found:', checkbox);
                console.log('Checkbox type:', checkbox.type);
                console.log('Checkbox checked:', checkbox.checked);
                console.log('Checkbox required:', checkbox.required);
                console.log('Checkbox computed style:', window.getComputedStyle(checkbox));
                
                // チェックボックスが見えない場合の修正
                const styles = window.getComputedStyle(checkbox);
                if (styles.display === 'none' || styles.visibility === 'hidden' || styles.opacity === '0') {
                    console.warn('Checkbox is hidden! Forcing visibility...');
                    checkbox.style.cssText = 'display: inline-block !important; visibility: visible !important; opacity: 1 !important; width: 20px !important; height: 20px !important;';
                }
            } else {
                console.error('Privacy policy checkbox not found!');
            }
            
            // すべてのフォーム要素を確認
            const formElements = document.querySelectorAll('#custom-contact-form input, #custom-contact-form select, #custom-contact-form textarea');
            console.log('All form elements:', formElements.length);
            formElements.forEach((el, index) => {
                console.log(`Element ${index}:`, el.name, el.type, el.required);
            });
        });
        </script>
        <?php
    }
}
add_action( 'wp_head', 'corporate_seo_pro_checkbox_debug_css', 999 );

/**
 * フォームデータのデバッグ情報を追加
 */
function corporate_seo_pro_form_debug_info() {
    if ( is_page_template( 'page-contact.php' ) && current_user_can( 'manage_options' ) ) {
        ?>
        <div style="background: #ffffcc; border: 2px solid #ff0000; padding: 20px; margin: 20px 0;">
            <h3 style="color: #ff0000;">デバッグ情報（管理者のみ表示）</h3>
            <p>このセクションは管理者にのみ表示されます。</p>
            <ul>
                <li>チェックボックスが表示されない場合は、CSSの競合を確認してください。</li>
                <li>ブラウザのコンソールでJavaScriptエラーを確認してください。</li>
                <li>フォーム送信時にプライバシーポリシーのチェックが必須です。</li>
            </ul>
        </div>
        <?php
    }
}
add_action( 'corporate_seo_pro_after_contact_form', 'corporate_seo_pro_form_debug_info' );