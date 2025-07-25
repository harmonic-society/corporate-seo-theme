<?php
/**
 * ニュースリリース アーカイブページテンプレート
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="page-header">
        <div class="container">
            <h1 class="page-title"><?php _e( 'ニュースリリース', 'corporate-seo-pro' ); ?></h1>
            <div class="breadcrumbs">
                <?php corporate_seo_pro_breadcrumbs(); ?>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="content-area">
            <?php if ( have_posts() ) : ?>
                <div class="news-archive-list">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class( 'news-archive-item' ); ?>>
                            <a href="<?php the_permalink(); ?>" class="news-archive-link">
                                <div class="news-archive-date">
                                    <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                        <?php echo esc_html( get_the_date( 'Y年n月j日' ) ); ?>
                                    </time>
                                </div>
                                <div class="news-archive-content">
                                    <h2 class="news-archive-title"><?php the_title(); ?></h2>
                                    <?php if ( has_excerpt() ) : ?>
                                        <div class="news-archive-excerpt">
                                            <?php the_excerpt(); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="news-archive-arrow">
                                    <span aria-hidden="true">→</span>
                                </div>
                            </a>
                        </article>
                    <?php endwhile; ?>
                </div>
                
                <?php corporate_seo_pro_pagination(); ?>
                
            <?php else : ?>
                <div class="no-results">
                    <h2><?php esc_html_e( 'ニュースリリースが見つかりません', 'corporate-seo-pro' ); ?></h2>
                    <p><?php esc_html_e( '申し訳ございません。現在、ニュースリリースはありません。', 'corporate-seo-pro' ); ?></p>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
                        <?php esc_html_e( 'トップページへ戻る', 'corporate-seo-pro' ); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php get_footer();