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
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/LINE_Brand_icon.png' ); ?>" 
                         alt="LINE" 
                         width="80" 
                         height="80">
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