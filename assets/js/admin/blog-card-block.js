/**
 * Blog Card Block for Gutenberg
 * 内部リンクをリッチカードで表示するブロック
 *
 * @package Corporate_SEO_Pro
 */

( function( wp ) {
    const { registerBlockType } = wp.blocks;
    const { useState, useEffect } = wp.element;
    const { TextControl, SelectControl, ToggleControl, Spinner, PanelBody } = wp.components;
    const { InspectorControls, useBlockProps } = wp.blockEditor;
    const { __ } = wp.i18n;

    // 記事検索コンポーネント
    const PostSearch = ( { onSelect, selectedPost } ) => {
        const [ searchTerm, setSearchTerm ] = useState( '' );
        const [ searchResults, setSearchResults ] = useState( [] );
        const [ isLoading, setIsLoading ] = useState( false );

        useEffect( () => {
            if ( searchTerm.length < 2 ) {
                setSearchResults( [] );
                return;
            }

            const timeoutId = setTimeout( () => {
                setIsLoading( true );

                wp.apiFetch( {
                    path: `/wp/v2/posts?search=${ encodeURIComponent( searchTerm ) }&per_page=10&_embed`,
                } ).then( ( posts ) => {
                    setSearchResults( posts );
                    setIsLoading( false );
                } ).catch( () => {
                    setSearchResults( [] );
                    setIsLoading( false );
                } );
            }, 300 );

            return () => clearTimeout( timeoutId );
        }, [ searchTerm ] );

        return wp.element.createElement(
            'div',
            { className: 'blog-card-search' },
            wp.element.createElement( TextControl, {
                label: __( '記事を検索', 'corporate-seo-pro' ),
                value: searchTerm,
                onChange: setSearchTerm,
                placeholder: __( 'タイトルで検索...', 'corporate-seo-pro' ),
            } ),
            isLoading && wp.element.createElement(
                'div',
                { className: 'blog-card-search-loading' },
                wp.element.createElement( Spinner ),
                __( '検索中...', 'corporate-seo-pro' )
            ),
            searchResults.length > 0 && wp.element.createElement(
                'ul',
                { className: 'blog-card-search-results' },
                searchResults.map( ( post ) => {
                    const thumbnail = post._embedded &&
                                     post._embedded['wp:featuredmedia'] &&
                                     post._embedded['wp:featuredmedia'][0] &&
                                     post._embedded['wp:featuredmedia'][0].media_details &&
                                     post._embedded['wp:featuredmedia'][0].media_details.sizes &&
                                     post._embedded['wp:featuredmedia'][0].media_details.sizes.thumbnail
                        ? post._embedded['wp:featuredmedia'][0].media_details.sizes.thumbnail.source_url
                        : null;

                    return wp.element.createElement(
                        'li',
                        {
                            key: post.id,
                            className: 'blog-card-search-result' + ( selectedPost === post.id ? ' is-selected' : '' ),
                            onClick: () => onSelect( post ),
                        },
                        thumbnail && wp.element.createElement(
                            'img',
                            {
                                src: thumbnail,
                                alt: '',
                                className: 'blog-card-search-result-thumbnail',
                            }
                        ),
                        wp.element.createElement(
                            'div',
                            { className: 'blog-card-search-result-content' },
                            wp.element.createElement(
                                'span',
                                { className: 'blog-card-search-result-title' },
                                post.title.rendered
                            ),
                            wp.element.createElement(
                                'span',
                                { className: 'blog-card-search-result-date' },
                                new Date( post.date ).toLocaleDateString( 'ja-JP' )
                            )
                        )
                    );
                } )
            )
        );
    };

    // ブロック登録
    registerBlockType( 'corporate-seo-pro/blog-card', {
        title: __( 'ブログカード', 'corporate-seo-pro' ),
        description: __( '内部リンクをリッチなカード形式で表示します', 'corporate-seo-pro' ),
        icon: 'admin-links',
        category: 'common',
        keywords: [
            __( '内部リンク', 'corporate-seo-pro' ),
            __( 'ブログカード', 'corporate-seo-pro' ),
            __( 'リンクカード', 'corporate-seo-pro' ),
            'blog card',
            'internal link'
        ],
        attributes: {
            postId: {
                type: 'number',
                default: 0,
            },
            postTitle: {
                type: 'string',
                default: '',
            },
            postUrl: {
                type: 'string',
                default: '',
            },
            postThumbnail: {
                type: 'string',
                default: '',
            },
            postExcerpt: {
                type: 'string',
                default: '',
            },
            postDate: {
                type: 'string',
                default: '',
            },
            postCategory: {
                type: 'string',
                default: '',
            },
            style: {
                type: 'string',
                default: 'default',
            },
            showExcerpt: {
                type: 'boolean',
                default: true,
            },
            showThumbnail: {
                type: 'boolean',
                default: true,
            },
            showCategory: {
                type: 'boolean',
                default: true,
            },
            showDate: {
                type: 'boolean',
                default: true,
            },
        },

        edit: function( props ) {
            const { attributes, setAttributes } = props;
            const { postId, postTitle, postUrl, postThumbnail, postExcerpt, postDate, postCategory, style, showExcerpt, showThumbnail, showCategory, showDate } = attributes;
            const blockProps = useBlockProps();

            const handlePostSelect = ( post ) => {
                const thumbnail = post._embedded &&
                                 post._embedded['wp:featuredmedia'] &&
                                 post._embedded['wp:featuredmedia'][0]
                    ? post._embedded['wp:featuredmedia'][0].source_url
                    : '';

                const categories = post._embedded &&
                                  post._embedded['wp:term'] &&
                                  post._embedded['wp:term'][0] &&
                                  post._embedded['wp:term'][0][0]
                    ? post._embedded['wp:term'][0][0].name
                    : '';

                const excerpt = post.excerpt && post.excerpt.rendered
                    ? post.excerpt.rendered.replace( /<[^>]+>/g, '' ).substring( 0, 100 ) + '...'
                    : '';

                setAttributes( {
                    postId: post.id,
                    postTitle: post.title.rendered,
                    postUrl: post.link,
                    postThumbnail: thumbnail,
                    postExcerpt: excerpt,
                    postDate: new Date( post.date ).toLocaleDateString( 'ja-JP' ),
                    postCategory: categories,
                } );
            };

            const clearSelection = () => {
                setAttributes( {
                    postId: 0,
                    postTitle: '',
                    postUrl: '',
                    postThumbnail: '',
                    postExcerpt: '',
                    postDate: '',
                    postCategory: '',
                } );
            };

            // サイドバー設定
            const inspectorControls = wp.element.createElement(
                InspectorControls,
                {},
                wp.element.createElement(
                    PanelBody,
                    { title: __( '表示設定', 'corporate-seo-pro' ) },
                    wp.element.createElement( SelectControl, {
                        label: __( 'スタイル', 'corporate-seo-pro' ),
                        value: style,
                        options: [
                            { label: __( 'デフォルト（横並び）', 'corporate-seo-pro' ), value: 'default' },
                            { label: __( 'コンパクト', 'corporate-seo-pro' ), value: 'compact' },
                            { label: __( 'フル（縦型）', 'corporate-seo-pro' ), value: 'full' },
                        ],
                        onChange: ( value ) => setAttributes( { style: value } ),
                    } ),
                    wp.element.createElement( ToggleControl, {
                        label: __( 'サムネイルを表示', 'corporate-seo-pro' ),
                        checked: showThumbnail,
                        onChange: ( value ) => setAttributes( { showThumbnail: value } ),
                    } ),
                    wp.element.createElement( ToggleControl, {
                        label: __( 'カテゴリーを表示', 'corporate-seo-pro' ),
                        checked: showCategory,
                        onChange: ( value ) => setAttributes( { showCategory: value } ),
                    } ),
                    wp.element.createElement( ToggleControl, {
                        label: __( '抜粋を表示', 'corporate-seo-pro' ),
                        checked: showExcerpt,
                        onChange: ( value ) => setAttributes( { showExcerpt: value } ),
                    } ),
                    wp.element.createElement( ToggleControl, {
                        label: __( '日付を表示', 'corporate-seo-pro' ),
                        checked: showDate,
                        onChange: ( value ) => setAttributes( { showDate: value } ),
                    } )
                )
            );

            // 記事未選択時
            if ( ! postId ) {
                return wp.element.createElement(
                    'div',
                    blockProps,
                    inspectorControls,
                    wp.element.createElement(
                        'div',
                        { className: 'blog-card-block-placeholder' },
                        wp.element.createElement(
                            'div',
                            { className: 'blog-card-block-icon' },
                            wp.element.createElement( 'span', { className: 'dashicons dashicons-admin-links' } )
                        ),
                        wp.element.createElement(
                            'h3',
                            {},
                            __( 'ブログカード', 'corporate-seo-pro' )
                        ),
                        wp.element.createElement(
                            'p',
                            {},
                            __( 'リンクする記事を検索してください', 'corporate-seo-pro' )
                        ),
                        wp.element.createElement( PostSearch, {
                            onSelect: handlePostSelect,
                            selectedPost: postId,
                        } )
                    )
                );
            }

            // 記事選択済み - プレビュー表示
            return wp.element.createElement(
                'div',
                blockProps,
                inspectorControls,
                wp.element.createElement(
                    'div',
                    { className: 'blog-card-block-preview blog-card-shortcode blog-card-shortcode--' + style },
                    showThumbnail && wp.element.createElement(
                        'div',
                        { className: 'blog-card-thumbnail' },
                        postThumbnail
                            ? wp.element.createElement( 'img', { src: postThumbnail, alt: postTitle } )
                            : wp.element.createElement(
                                'div',
                                { className: 'blog-card-thumbnail--placeholder' },
                                wp.element.createElement( 'span', { className: 'dashicons dashicons-format-image' } )
                            )
                    ),
                    wp.element.createElement(
                        'div',
                        { className: 'blog-card-content' },
                        ( showCategory || showDate ) && wp.element.createElement(
                            'div',
                            { className: 'blog-card-meta' },
                            showCategory && postCategory && wp.element.createElement(
                                'span',
                                { className: 'blog-card-category' },
                                postCategory
                            ),
                            showDate && style !== 'compact' && wp.element.createElement(
                                'span',
                                { className: 'blog-card-date' },
                                postDate
                            )
                        ),
                        wp.element.createElement(
                            'h4',
                            { className: 'blog-card-title' },
                            postTitle
                        ),
                        showExcerpt && style !== 'compact' && postExcerpt && wp.element.createElement(
                            'p',
                            { className: 'blog-card-excerpt' },
                            postExcerpt
                        )
                    ),
                    wp.element.createElement(
                        'button',
                        {
                            className: 'blog-card-block-change',
                            onClick: clearSelection,
                            type: 'button',
                        },
                        __( '記事を変更', 'corporate-seo-pro' )
                    )
                )
            );
        },

        save: function() {
            // サーバーサイドでレンダリング
            return null;
        },
    } );

} )( window.wp );
