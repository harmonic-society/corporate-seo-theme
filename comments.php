<?php
/**
 * コメントテンプレート
 *
 * @package Corporate_SEO_Pro
 */

if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            if ( '1' === $comment_count ) {
                printf(
                    esc_html__( '「%1$s」への%2$s件のコメント', 'corporate-seo-pro' ),
                    '<span>' . get_the_title() . '</span>',
                    '<span class="comment-count-number">1</span>'
                );
            } else {
                printf(
                    esc_html( _nx( '「%1$s」への%2$s件のコメント', '「%1$s」への%2$s件のコメント', $comment_count, 'comments title', 'corporate-seo-pro' ) ),
                    '<span>' . get_the_title() . '</span>',
                    '<span class="comment-count-number">' . number_format_i18n( $comment_count ) . '</span>'
                );
            }
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments( array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 60,
                'callback'    => 'corporate_seo_pro_comment',
            ) );
            ?>
        </ol>

        <?php the_comments_navigation(); ?>

    <?php endif; ?>

    <?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
        <p class="no-comments"><?php esc_html_e( 'コメントは締め切られています。', 'corporate-seo-pro' ); ?></p>
    <?php endif; ?>

    <?php
    // コメントフォーム
    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );

    $fields = array(
        'author' => '<div class="comment-form-author form-group">
                        <label for="author">' . __( 'お名前', 'corporate-seo-pro' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label>
                        <input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />
                    </div>',
        'email'  => '<div class="comment-form-email form-group">
                        <label for="email">' . __( 'メールアドレス', 'corporate-seo-pro' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label>
                        <input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />
                    </div>',
        'url'    => '<div class="comment-form-url form-group">
                        <label for="url">' . __( 'ウェブサイト', 'corporate-seo-pro' ) . '</label>
                        <input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" />
                    </div>',
    );

    $args = array(
        'id_form'           => 'commentform',
        'class_form'        => 'comment-form',
        'id_submit'         => 'submit',
        'class_submit'      => 'submit btn btn-primary',
        'name_submit'       => 'submit',
        'title_reply'       => __( 'コメントを残す', 'corporate-seo-pro' ),
        'title_reply_to'    => __( '%sへ返信', 'corporate-seo-pro' ),
        'cancel_reply_link' => __( 'キャンセル', 'corporate-seo-pro' ),
        'label_submit'      => __( 'コメントを送信', 'corporate-seo-pro' ),
        'format'            => 'xhtml',
        'comment_field'     => '<div class="comment-form-comment form-group">
                                    <label for="comment">' . _x( 'コメント', 'noun', 'corporate-seo-pro' ) . ' <span class="required">*</span></label>
                                    <textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
                                </div>',
        'fields'            => apply_filters( 'comment_form_default_fields', $fields ),
    );

    comment_form( $args );
    ?>

</div>

<?php
/**
 * カスタムコメント表示
 */
function corporate_seo_pro_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    ?>
    <li <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <div class="comment-author vcard">
                <?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
                <div class="comment-author-info">
                    <?php printf( __( '<cite class="fn">%s</cite>', 'corporate-seo-pro' ), get_comment_author_link() ); ?>
                    <div class="comment-metadata">
                        <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
                            <time datetime="<?php comment_time( 'c' ); ?>">
                                <?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'corporate-seo-pro' ), get_comment_date(), get_comment_time() ); ?>
                            </time>
                        </a>
                        <?php edit_comment_link( __( '編集', 'corporate-seo-pro' ), '<span class="edit-link">', '</span>' ); ?>
                    </div>
                </div>
            </div>

            <?php if ( '0' == $comment->comment_approved ) : ?>
                <p class="comment-awaiting-moderation"><?php _e( 'あなたのコメントは承認待ちです。', 'corporate-seo-pro' ); ?></p>
            <?php endif; ?>

            <div class="comment-content">
                <?php comment_text(); ?>
            </div>

            <div class="reply">
                <?php comment_reply_link( array_merge( $args, array(
                    'add_below' => 'div-comment',
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth'],
                    'before'    => '<span class="reply-link">',
                    'after'     => '</span>',
                ) ) ); ?>
            </div>
        </article>
    <?php
}