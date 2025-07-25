<?php
/**
 * ニュースリリース 詳細ページテンプレート
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="page-header">
        <div class="container">
            <h1 class="page-title"><?php the_title(); ?></h1>
            <div class="breadcrumbs">
                <?php corporate_seo_pro_breadcrumb(); ?>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="content-wrapper">
            <div class="content-area">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'single-news' ); ?>>
                        <header class="entry-header">
                            <div class="entry-meta">
                                <time class="entry-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                    <?php echo esc_html( get_the_date( 'Y年n月j日' ) ); ?>
                                </time>
                            </div>
                        </header>

                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>

                        <?php if ( get_theme_mod( 'show_share_buttons', true ) ) : ?>
                            <div class="share-buttons">
                                <h3 class="share-title"><?php esc_html_e( 'この記事をシェア', 'corporate-seo-pro' ); ?></h3>
                                <div class="share-buttons-list">
                                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>&text=<?php echo urlencode( get_the_title() ); ?>" 
                                       target="_blank" 
                                       rel="noopener noreferrer" 
                                       class="share-button share-twitter">
                                        <i class="fab fa-twitter"></i>
                                        <span>Twitter</span>
                                    </a>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" 
                                       target="_blank" 
                                       rel="noopener noreferrer" 
                                       class="share-button share-facebook">
                                        <i class="fab fa-facebook-f"></i>
                                        <span>Facebook</span>
                                    </a>
                                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode( get_permalink() ); ?>&title=<?php echo urlencode( get_the_title() ); ?>" 
                                       target="_blank" 
                                       rel="noopener noreferrer" 
                                       class="share-button share-linkedin">
                                        <i class="fab fa-linkedin-in"></i>
                                        <span>LinkedIn</span>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <nav class="post-navigation">
                            <div class="nav-links">
                                <div class="nav-previous">
                                    <?php previous_post_link( '%link', '<i class="fas fa-chevron-left"></i> %title' ); ?>
                                </div>
                                <div class="nav-next">
                                    <?php next_post_link( '%link', '%title <i class="fas fa-chevron-right"></i>' ); ?>
                                </div>
                            </div>
                        </nav>
                    </article>
                <?php endwhile; ?>
            </div>

            <aside class="sidebar">
                <div class="widget">
                    <h3 class="widget-title"><?php esc_html_e( '最新のニュースリリース', 'corporate-seo-pro' ); ?></h3>
                    <?php
                    $recent_news = new WP_Query( array(
                        'post_type'      => 'news',
                        'posts_per_page' => 5,
                        'post__not_in'   => array( get_the_ID() ),
                    ) );

                    if ( $recent_news->have_posts() ) : ?>
                        <ul class="recent-news-list">
                            <?php while ( $recent_news->have_posts() ) : $recent_news->the_post(); ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>">
                                        <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                            <?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?>
                                        </time>
                                        <span><?php the_title(); ?></span>
                                    </a>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                        <?php wp_reset_postdata(); ?>
                    <?php endif; ?>
                </div>

                <div class="widget">
                    <h3 class="widget-title"><?php esc_html_e( 'アーカイブ', 'corporate-seo-pro' ); ?></h3>
                    <ul class="archive-list">
                        <?php wp_get_archives( array(
                            'type'      => 'monthly',
                            'post_type' => 'news',
                            'limit'     => 12,
                        ) ); ?>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</main>

<?php get_footer();