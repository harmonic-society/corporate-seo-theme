<?php
/**
 * 著者プロフィールカスタムフィールド（E-E-A-T対応）
 *
 * ユーザープロフィールに専門性・資格・経験などのフィールドを追加し、
 * E-E-A-T（経験、専門性、権威性、信頼性）を構造化データとして出力
 *
 * @package Corporate_SEO_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * ユーザープロフィールにE-E-A-T関連フィールドを追加
 *
 * @param WP_User $user ユーザーオブジェクト
 */
function corporate_seo_pro_add_author_eeat_fields( $user ) {
    ?>
    <h3><?php esc_html_e( 'E-E-A-T プロフィール情報', 'corporate-seo-pro' ); ?></h3>
    <p class="description"><?php esc_html_e( '検索エンジンとAIが著者の専門性を理解するための情報です。Person Schema（構造化データ）として出力されます。', 'corporate-seo-pro' ); ?></p>

    <table class="form-table" role="presentation">
        <tr>
            <th><label for="author_job_title"><?php esc_html_e( '役職・職種', 'corporate-seo-pro' ); ?></label></th>
            <td>
                <input type="text" name="author_job_title" id="author_job_title"
                       value="<?php echo esc_attr( get_user_meta( $user->ID, 'author_job_title', true ) ); ?>"
                       class="regular-text" placeholder="例：Webディレクター、SEOコンサルタント" />
                <p class="description"><?php esc_html_e( '現在の役職や職種を入力してください。', 'corporate-seo-pro' ); ?></p>
            </td>
        </tr>

        <tr>
            <th><label for="author_organization"><?php esc_html_e( '所属組織', 'corporate-seo-pro' ); ?></label></th>
            <td>
                <input type="text" name="author_organization" id="author_organization"
                       value="<?php echo esc_attr( get_user_meta( $user->ID, 'author_organization', true ) ); ?>"
                       class="regular-text" placeholder="例：Harmonic Society株式会社" />
                <p class="description"><?php esc_html_e( '空白の場合は会社名が自動で設定されます。', 'corporate-seo-pro' ); ?></p>
            </td>
        </tr>

        <tr>
            <th><label for="author_expertise"><?php esc_html_e( '専門分野', 'corporate-seo-pro' ); ?></label></th>
            <td>
                <?php
                $expertise = get_user_meta( $user->ID, 'author_expertise', true );
                $expertise_text = '';
                if ( is_array( $expertise ) ) {
                    $expertise_text = implode( "\n", $expertise );
                } elseif ( is_string( $expertise ) ) {
                    $expertise_text = $expertise;
                }
                ?>
                <textarea name="author_expertise" id="author_expertise" rows="4" class="regular-text"
                          placeholder="例：&#10;SEO対策&#10;Webマーケティング&#10;WordPress開発"><?php echo esc_textarea( $expertise_text ); ?></textarea>
                <p class="description"><?php esc_html_e( '1行に1つずつ入力してください。Schema.orgのknowsAboutプロパティに使用されます。', 'corporate-seo-pro' ); ?></p>
            </td>
        </tr>

        <tr>
            <th><label for="author_credentials"><?php esc_html_e( '資格・認定', 'corporate-seo-pro' ); ?></label></th>
            <td>
                <?php
                $credentials = get_user_meta( $user->ID, 'author_credentials', true );
                $credentials_text = '';
                if ( is_array( $credentials ) ) {
                    $credentials_text = implode( "\n", $credentials );
                } elseif ( is_string( $credentials ) ) {
                    $credentials_text = $credentials;
                }
                ?>
                <textarea name="author_credentials" id="author_credentials" rows="3" class="regular-text"
                          placeholder="例：&#10;Google Analytics認定資格&#10;ウェブ解析士"><?php echo esc_textarea( $credentials_text ); ?></textarea>
                <p class="description"><?php esc_html_e( '1行に1つずつ入力してください。保有資格や認定を記載します。', 'corporate-seo-pro' ); ?></p>
            </td>
        </tr>

        <tr>
            <th><label for="author_alumni"><?php esc_html_e( '出身校', 'corporate-seo-pro' ); ?></label></th>
            <td>
                <input type="text" name="author_alumni" id="author_alumni"
                       value="<?php echo esc_attr( get_user_meta( $user->ID, 'author_alumni', true ) ); ?>"
                       class="regular-text" placeholder="例：○○大学 情報工学部" />
                <p class="description"><?php esc_html_e( '最終学歴を入力してください。', 'corporate-seo-pro' ); ?></p>
            </td>
        </tr>

        <tr>
            <th><label for="author_experience_years"><?php esc_html_e( '経験年数', 'corporate-seo-pro' ); ?></label></th>
            <td>
                <input type="number" name="author_experience_years" id="author_experience_years"
                       value="<?php echo esc_attr( get_user_meta( $user->ID, 'author_experience_years', true ) ); ?>"
                       class="small-text" min="0" max="50" />
                <span><?php esc_html_e( '年', 'corporate-seo-pro' ); ?></span>
                <p class="description"><?php esc_html_e( '業界での経験年数を入力してください。', 'corporate-seo-pro' ); ?></p>
            </td>
        </tr>

        <tr>
            <th><label for="author_linkedin_url"><?php esc_html_e( 'LinkedIn URL', 'corporate-seo-pro' ); ?></label></th>
            <td>
                <input type="url" name="author_linkedin_url" id="author_linkedin_url"
                       value="<?php echo esc_url( get_user_meta( $user->ID, 'author_linkedin_url', true ) ); ?>"
                       class="regular-text" placeholder="https://www.linkedin.com/in/username" />
            </td>
        </tr>

        <tr>
            <th><label for="author_twitter_url"><?php esc_html_e( 'X (Twitter) URL', 'corporate-seo-pro' ); ?></label></th>
            <td>
                <input type="url" name="author_twitter_url" id="author_twitter_url"
                       value="<?php echo esc_url( get_user_meta( $user->ID, 'author_twitter_url', true ) ); ?>"
                       class="regular-text" placeholder="https://twitter.com/username" />
            </td>
        </tr>
    </table>
    <?php
}
add_action( 'show_user_profile', 'corporate_seo_pro_add_author_eeat_fields' );
add_action( 'edit_user_profile', 'corporate_seo_pro_add_author_eeat_fields' );

/**
 * E-E-A-Tフィールドの保存
 *
 * @param int $user_id ユーザーID
 */
function corporate_seo_pro_save_author_eeat_fields( $user_id ) {
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    // セキュリティチェック
    if ( ! isset( $_POST['_wpnonce'] ) ) {
        return false;
    }

    // 役職
    if ( isset( $_POST['author_job_title'] ) ) {
        update_user_meta( $user_id, 'author_job_title', sanitize_text_field( $_POST['author_job_title'] ) );
    }

    // 所属組織
    if ( isset( $_POST['author_organization'] ) ) {
        update_user_meta( $user_id, 'author_organization', sanitize_text_field( $_POST['author_organization'] ) );
    }

    // 専門分野（改行区切りで配列に変換）
    if ( isset( $_POST['author_expertise'] ) ) {
        $expertise = array_filter(
            array_map( 'sanitize_text_field', explode( "\n", $_POST['author_expertise'] ) )
        );
        $expertise = array_map( 'trim', $expertise );
        update_user_meta( $user_id, 'author_expertise', $expertise );
    }

    // 資格・認定（改行区切りで配列に変換）
    if ( isset( $_POST['author_credentials'] ) ) {
        $credentials = array_filter(
            array_map( 'sanitize_text_field', explode( "\n", $_POST['author_credentials'] ) )
        );
        $credentials = array_map( 'trim', $credentials );
        update_user_meta( $user_id, 'author_credentials', $credentials );
    }

    // 出身校
    if ( isset( $_POST['author_alumni'] ) ) {
        update_user_meta( $user_id, 'author_alumni', sanitize_text_field( $_POST['author_alumni'] ) );
    }

    // 経験年数
    if ( isset( $_POST['author_experience_years'] ) ) {
        update_user_meta( $user_id, 'author_experience_years', absint( $_POST['author_experience_years'] ) );
    }

    // LinkedIn URL
    if ( isset( $_POST['author_linkedin_url'] ) ) {
        update_user_meta( $user_id, 'author_linkedin_url', esc_url_raw( $_POST['author_linkedin_url'] ) );
    }

    // Twitter URL
    if ( isset( $_POST['author_twitter_url'] ) ) {
        update_user_meta( $user_id, 'author_twitter_url', esc_url_raw( $_POST['author_twitter_url'] ) );
    }
}
add_action( 'personal_options_update', 'corporate_seo_pro_save_author_eeat_fields' );
add_action( 'edit_user_profile_update', 'corporate_seo_pro_save_author_eeat_fields' );

/**
 * 拡張著者Person Schemaを取得
 *
 * @param int $author_id 著者ID
 * @return array|null Person Schemaの配列またはnull
 */
function corporate_seo_pro_get_enhanced_author_schema( $author_id ) {
    $author = get_userdata( $author_id );

    if ( ! $author ) {
        return null;
    }

    $schema = array(
        '@type' => 'Person',
        '@id'   => get_author_posts_url( $author_id ) . '#author',
        'name'  => $author->display_name,
        'url'   => get_author_posts_url( $author_id ),
    );

    // 著者画像
    $avatar_url = get_avatar_url( $author_id, array( 'size' => 200 ) );
    if ( $avatar_url ) {
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url'   => $avatar_url,
        );
    }

    // プロフィール説明
    $bio = get_the_author_meta( 'description', $author_id );
    if ( ! empty( $bio ) ) {
        $schema['description'] = $bio;
    }

    // 役職情報
    $job_title = get_user_meta( $author_id, 'author_job_title', true );
    if ( ! empty( $job_title ) ) {
        $schema['jobTitle'] = $job_title;
    }

    // 所属組織
    $organization = get_user_meta( $author_id, 'author_organization', true );
    if ( ! empty( $organization ) ) {
        $schema['worksFor'] = array(
            '@type' => 'Organization',
            'name'  => $organization,
            '@id'   => home_url( '/#organization' ),
        );
    } else {
        // デフォルトで会社情報を設定
        $schema['worksFor'] = array(
            '@type' => 'Organization',
            'name'  => 'Harmonic Society株式会社',
            '@id'   => home_url( '/#organization' ),
        );
    }

    // 専門分野（knowsAbout）
    $knows_about = get_user_meta( $author_id, 'author_expertise', true );
    if ( ! empty( $knows_about ) && is_array( $knows_about ) ) {
        $schema['knowsAbout'] = $knows_about;
    }

    // 資格・認定（hasCredential）
    $credentials = get_user_meta( $author_id, 'author_credentials', true );
    if ( ! empty( $credentials ) && is_array( $credentials ) ) {
        $schema['hasCredential'] = array();
        foreach ( $credentials as $credential ) {
            if ( ! empty( $credential ) ) {
                $schema['hasCredential'][] = array(
                    '@type' => 'EducationalOccupationalCredential',
                    'name'  => $credential,
                );
            }
        }
    }

    // 学歴（alumniOf）
    $alumni = get_user_meta( $author_id, 'author_alumni', true );
    if ( ! empty( $alumni ) ) {
        $schema['alumniOf'] = array(
            '@type' => 'EducationalOrganization',
            'name'  => $alumni,
        );
    }

    // ソーシャルメディアリンク（sameAs）
    $social_links = array();

    $linkedin = get_user_meta( $author_id, 'author_linkedin_url', true );
    if ( ! empty( $linkedin ) ) {
        $social_links[] = $linkedin;
    }

    $twitter = get_user_meta( $author_id, 'author_twitter_url', true );
    if ( ! empty( $twitter ) ) {
        $social_links[] = $twitter;
    }

    // 標準のWordPressユーザーメタからも取得
    $user_url = $author->user_url;
    if ( ! empty( $user_url ) ) {
        $social_links[] = $user_url;
    }

    if ( ! empty( $social_links ) ) {
        $schema['sameAs'] = $social_links;
    }

    return $schema;
}
