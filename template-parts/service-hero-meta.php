<?php
/**
 * Service Hero Meta Information
 * 
 * サービスヒーローセクションのメタ情報表示
 * 
 * @package Corporate_SEO_Pro
 */

// ACFが有効かチェック
if ( ! function_exists('get_field') ) {
    return;
}

// ACFフィールドの取得
$duration = get_field('service_duration');
$price = get_field('service_price');

// 両方とも空の場合は何も表示しない
if ( empty($duration) && empty($price) ) {
    return;
}
?>

<div class="service-hero-meta">
    <?php if ( $duration ) : ?>
        <div class="hero-meta-item">
            <i class="far fa-clock"></i>
            <span class="meta-label">所要時間:</span>
            <span class="meta-value"><?php echo esc_html( $duration ); ?></span>
        </div>
    <?php endif; ?>
    
    <?php if ( $price ) : ?>
        <div class="hero-meta-item">
            <i class="fas fa-yen-sign"></i>
            <span class="meta-label">料金:</span>
            <span class="meta-value"><?php echo esc_html( $price ); ?></span>
        </div>
    <?php endif; ?>
</div>