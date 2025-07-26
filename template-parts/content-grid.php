<?php
/**
 * Template part for displaying content in grid layout
 *
 * @package Corporate_SEO_Pro
 * 
 * Usage:
 * get_template_part( 'template-parts/content', 'grid', array(
 *     'post_type' => 'post', // post, service, work
 *     'posts_per_page' => 6,
 *     'columns' => 3, // 2, 3, 4
 *     'show_pagination' => true,
 *     'container_class' => 'custom-grid-class',
 * ) );
 */

// デフォルト設定
$post_type = isset( $args['post_type'] ) ? $args['post_type'] : 'post';
$posts_per_page = isset( $args['posts_per_page'] ) ? $args['posts_per_page'] : get_option( 'posts_per_page' );
$columns = isset( $args['columns'] ) ? $args['columns'] : 3;
$show_pagination = isset( $args['show_pagination'] ) ? $args['show_pagination'] : true;
$container_class = isset( $args['container_class'] ) ? $args['container_class'] : '';
$category = isset( $args['category'] ) ? $args['category'] : '';
$meta_key = isset( $args['meta_key'] ) ? $args['meta_key'] : '';
$meta_value = isset( $args['meta_value'] ) ? $args['meta_value'] : '';

// グリッドクラスの設定
$grid_class = 'content-grid';
$grid_class .= ' grid-cols-' . $columns;
if ( $container_class ) {
    $grid_class .= ' ' . $container_class;
}

// WP_Queryの引数を設定
$query_args = array(
    'post_type'      => $post_type,
    'posts_per_page' => $posts_per_page,
    'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
);

// カテゴリーフィルター
if ( $category ) {
    if ( $post_type === 'post' ) {
        $query_args['category_name'] = $category;
    } else {
        $query_args['tax_query'] = array(
            array(
                'taxonomy' => $post_type . '_category',
                'field'    => 'slug',
                'terms'    => $category,
            ),
        );
    }
}

// メタフィルター
if ( $meta_key && $meta_value ) {
    $query_args['meta_key'] = $meta_key;
    $query_args['meta_value'] = $meta_value;
}

// クエリ実行
// Check if a query was passed via set_query_var
$passed_query = get_query_var( 'grid_query', false );
if ( $passed_query && is_object( $passed_query ) && $passed_query instanceof WP_Query ) {
    $grid_query = $passed_query;
} else {
    $grid_query = new WP_Query( $query_args );
}

if ( $grid_query->have_posts() ) : ?>
    
    <div class="<?php echo esc_attr( $grid_class ); ?>">
        <?php while ( $grid_query->have_posts() ) : $grid_query->the_post(); ?>
            
            <?php if ( $post_type === 'post' ) : ?>
                
                <?php get_template_part( 'template-parts/content', 'blog-card', array( 'layout' => 'grid' ) ); ?>
                
            <?php elseif ( $post_type === 'service' ) : ?>
                
                <article class="service-card">
                    <a href="<?php the_permalink(); ?>" class="service-card-link">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="service-card-image">
                                <?php the_post_thumbnail( 'medium_large' ); ?>
                                <div class="service-card-overlay">
                                    <span class="view-details">詳細を見る</span>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="service-card-content">
                            <h3 class="service-card-title"><?php the_title(); ?></h3>
                            <p class="service-card-excerpt">
                                <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
                            </p>
                            
                            <?php 
                            if ( function_exists('get_field') ) {
                                $price = get_field('service_price');
                                if ( $price ) : ?>
                                    <div class="service-card-price">
                                        <span class="price-label">料金:</span>
                                        <span class="price-value"><?php echo esc_html( $price ); ?></span>
                                    </div>
                                <?php endif;
                            } ?>
                        </div>
                    </a>
                </article>
                
            <?php elseif ( $post_type === 'work' ) : ?>
                
                <article class="work-card">
                    <a href="<?php the_permalink(); ?>" class="work-card-link">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="work-card-image">
                                <?php the_post_thumbnail( 'medium_large' ); ?>
                                <div class="work-card-overlay">
                                    <div class="overlay-content">
                                        <h3 class="work-title"><?php the_title(); ?></h3>
                                        <?php 
                                        $client = get_post_meta( get_the_ID(), '_work_client', true );
                                        if ( $client ) : ?>
                                            <p class="work-client"><?php echo esc_html( $client ); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="work-card-image no-image">
                                <div class="placeholder-content">
                                    <h3 class="work-title"><?php the_title(); ?></h3>
                                </div>
                            </div>
                        <?php endif; ?>
                    </a>
                </article>
                
            <?php endif; ?>
            
        <?php endwhile; ?>
    </div>
    
    <?php if ( $show_pagination && $grid_query->max_num_pages > 1 ) : ?>
        <div class="pagination-wrapper">
            <?php
            echo paginate_links( array(
                'total'        => $grid_query->max_num_pages,
                'current'      => max( 1, get_query_var( 'paged' ) ),
                'format'       => '?paged=%#%',
                'show_all'     => false,
                'type'         => 'list',
                'end_size'     => 2,
                'mid_size'     => 1,
                'prev_next'    => true,
                'prev_text'    => '<i class="fas fa-chevron-left"></i>',
                'next_text'    => '<i class="fas fa-chevron-right"></i>',
                'add_args'     => false,
                'add_fragment' => '',
            ) );
            ?>
        </div>
    <?php endif; ?>
    
<?php else : ?>
    
    <div class="no-content-message">
        <p>
            <?php
            if ( $post_type === 'post' ) {
                echo '投稿が見つかりませんでした。';
            } elseif ( $post_type === 'service' ) {
                echo 'サービスが見つかりませんでした。';
            } elseif ( $post_type === 'work' ) {
                echo '実績が見つかりませんでした。';
            }
            ?>
        </p>
    </div>
    
<?php endif;

// Only reset postdata if we created a new query
if ( ! $passed_query ) {
    wp_reset_postdata();
}
?>