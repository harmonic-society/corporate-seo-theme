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
                    <svg viewBox="0 0 240 240" xmlns="http://www.w3.org/2000/svg">
                        <path d="M120 0C53.7 0 0 44.4 0 99.2c0 44.8 35.9 82.6 85 95.2-3.3 11.1-11.9 40.1-12.3 41.7-.6 2.5 0 4.1 1.7 5.1 1.4.8 3 .6 4.3-.4 1.8-1.4 28.6-30.7 39.4-42.4 53.8-2.5 102-40.1 102-96.2C240 44.4 186.3 0 120 0zm-47.5 133.5c0 2.1-1.7 3.8-3.8 3.8s-3.8-1.7-3.8-3.8V85.7c0-2.1 1.7-3.8 3.8-3.8s3.8 1.7 3.8 3.8v47.8zm25.5 0c0 2.1-1.7 3.8-3.8 3.8s-3.8-1.7-3.8-3.8V85.7c0-2.1 1.7-3.8 3.8-3.8s3.8 1.7 3.8 3.8v47.8zm51.4 0c0 2.1-1.7 3.8-3.8 3.8-1.4 0-2.6-.7-3.3-1.9l-20.5-35.5v33.6c0 2.1-1.7 3.8-3.8 3.8s-3.8-1.7-3.8-3.8V85.7c0-2.1 1.7-3.8 3.8-3.8 1.4 0 2.6.7 3.3 1.9l20.5 35.5V85.7c0-2.1 1.7-3.8 3.8-3.8s3.8 1.7 3.8 3.8v47.8zm25.5-21.9c2.1 0 3.8 1.7 3.8 3.8s-1.7 3.8-3.8 3.8h-13.7v10.3h13.7c2.1 0 3.8 1.7 3.8 3.8s-1.7 3.8-3.8 3.8h-17.5c-2.1 0-3.8-1.7-3.8-3.8V85.7c0-2.1 1.7-3.8 3.8-3.8h17.5c2.1 0 3.8 1.7 3.8 3.8s-1.7 3.8-3.8 3.8h-13.7V104h13.7z"/>
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