<?php
/**
 * LINE公式アカウント CTA テンプレート
 *
 * @package Corporate_SEO_Pro
 */
?>

<div class="line-cta-section">
    <div class="container-narrow">
        <div class="line-cta-wrapper">
            <!-- 背景装飾 -->
            <div class="line-cta-bg">
                <div class="line-pattern"></div>
                <div class="line-icon-bg"></div>
            </div>
            
            <!-- コンテンツ -->
            <div class="line-cta-content">
                <!-- LINEロゴ -->
                <div class="line-logo">
                    <svg viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg">
                        <rect width="1000" height="1000" rx="200" fill="#06C755"/>
                        <path d="M500 225c151.9 0 275 98.3 275 219.5 0 122.3-123.1 221.5-275 221.5-19.2 0-37.9-1.6-56-4.6-7.1-1.2-14.4-.4-20.8 2.5-15.4 6.9-64.1 29.1-92.4 44.4-7.5 4.1-16.9-.9-16.3-9.7.3-4.9 2.8-22.9 4-36.3.6-7-1.5-14-5.9-19.7C265.3 593.4 225 514.2 225 444.5 225 323.3 348.1 225 500 225z" fill="#fff"/>
                        <path d="M380.5 409.5h-42c-7.2 0-13 5.8-13 13s5.8 13 13 13h29v86h-29c-7.2 0-13 5.8-13 13s5.8 13 13 13h42c7.2 0 13-5.8 13-13v-112c0-7.2-5.8-13-13-13zm78 0c7.2 0 13 5.8 13 13v112c0 7.2-5.8 13-13 13s-13-5.8-13-13v-112c0-7.2 5.8-13 13-13zm132.2 0c5.2 0 9.9 3.1 12 7.8 2 4.8.9 10.3-2.9 13.9l-62.5 60.6v52.7c0 7.2-5.8 13-13 13s-13-5.8-13-13v-52.6c0-3.5 1.4-6.8 3.9-9.3l47.8-46.4v75c0 7.2 5.8 13 13 13h29c7.2 0 13-5.8 13-13s-5.8-13-13-13h-16v-24.7h16c7.2 0 13-5.8 13-13s-5.8-13-13-13h-16v-24h29c7.2 0 13-5.8 13-13s-5.8-13-13-13h-42.3z" fill="#06C755"/>
                    </svg>
                </div>
                
                <!-- キャッチコピー -->
                <div class="line-cta-catchphrase">
                    ＼AI活用で最短2週間Webリリース／
                </div>
                
                <!-- メインメッセージ -->
                <p class="line-cta-main-message">
                    LINEでは、AI駆動のWeb制作・開発の最新事例や、<br class="pc-only">
                    コスト削減ノウハウを配信中。
                </p>
                
                <!-- サブメッセージ -->
                <p class="line-cta-sub-message">
                    登録後は無料相談で、貴社に合う開発プランをご提案します。
                </p>
                
                <!-- CTAボタン -->
                <div class="line-cta-button-wrapper">
                    <a href="https://lin.ee/mQTrmxJ" 
                       class="line-add-friend-btn" 
                       target="_blank" 
                       rel="noopener noreferrer">
                        <span class="btn-emoji">👉</span>
                        <span>今すぐ登録して事例を見る</span>
                    </a>
                    
                    <button class="line-qr-button" onclick="openLineQRModal()">
                        <i class="fas fa-qrcode"></i>
                        <span>QRコード表示</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QRコードモーダル -->
<div class="line-qr-modal" id="lineQRModal">
    <div class="line-qr-modal-content">
        <button class="line-qr-close" onclick="closeLineQRModal()">
            <i class="fas fa-times"></i>
        </button>
        
        <h4 class="line-qr-modal-title">QRコードで友だち追加</h4>
        
        <div class="line-qr-code">
            <!-- QRコード画像 -->
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/line-qr-code.png' ); ?>" 
                 alt="LINE友だち追加QRコード" 
                 loading="lazy">
        </div>
        
        <p class="line-qr-instruction">
            LINEアプリの「友だち追加」から<br>
            「QRコード」を選択して読み取ってください
        </p>
    </div>
</div>

<script>
// QRコードモーダル制御
function openLineQRModal() {
    document.getElementById('lineQRModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeLineQRModal() {
    document.getElementById('lineQRModal').classList.remove('active');
    document.body.style.overflow = '';
}

// モーダル外クリックで閉じる
document.getElementById('lineQRModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLineQRModal();
    }
});

// ESCキーで閉じる
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('lineQRModal').classList.contains('active')) {
        closeLineQRModal();
    }
});
</script>