/**
 * カスタマイザープレビュー機能
 * 
 * @package Corporate_SEO_Pro
 */

( function( $ ) {
    'use strict';

    // ヒーローセクションの表示/非表示
    wp.customize( 'show_hero_section', function( value ) {
        value.bind( function( to ) {
            if ( to ) {
                $( '.hero-section' ).show();
            } else {
                $( '.hero-section' ).hide();
            }
        } );
    } );

    // ヒーロータイトル
    wp.customize( 'hero_title', function( value ) {
        value.bind( function( to ) {
            // タイトルを2行に分割する処理
            if ( to.indexOf( '調和' ) !== -1 ) {
                var parts = to.split( '調和' );
                var html = '<span class="title-line-1">' + parts[0] + '</span>';
                html += '<span class="title-line-2" data-text="調和' + parts[1] + '">調和' + parts[1] + '</span>';
                $( '.hero-title' ).html( html );
            } else {
                $( '.hero-title' ).html( '<span class="title-line-1">' + to + '</span>' );
            }
        } );
    } );

    // ヒーロー説明文
    wp.customize( 'hero_description', function( value ) {
        value.bind( function( to ) {
            $( '.hero-lead' ).text( to );
        } );
    } );

    // ヒーロー背景画像
    wp.customize( 'hero_background_image', function( value ) {
        value.bind( function( to ) {
            if ( to ) {
                $( '.hero-bg-image' ).css( 'background-image', 'url(' + to + ')' );
            } else {
                // デフォルト画像を設定
                $( '.hero-bg-image' ).css( 'background-image', 'url(https://harmonic-society.co.jp/wp-content/uploads/2024/10/GettyImages-981641584-scaled.jpg)' );
            }
        } );
    } );

    // ヒーロースタイル
    wp.customize( 'hero_style', function( value ) {
        value.bind( function( to ) {
            var $heroSection = $( '.hero-section' );
            
            // 全てのスタイルクラスを削除
            $heroSection.removeClass( 'hero-gradient hero-image hero-video hero-particles hero-geometric' );
            
            // 新しいスタイルクラスを追加
            $heroSection.addClass( 'hero-' + to );
            
            // ビデオスタイルの場合の処理
            if ( to === 'video' ) {
                // ビデオ要素がない場合は追加
                if ( ! $heroSection.find( '.hero-video-container' ).length ) {
                    var videoUrl = wp.customize( 'hero_background_video' ).get();
                    if ( videoUrl ) {
                        var videoHtml = '<div class="hero-video-container"><video autoplay muted loop playsinline><source src="' + videoUrl + '" type="video/mp4"></video></div>';
                        $heroSection.prepend( videoHtml );
                    }
                }
            } else {
                // ビデオ以外のスタイルの場合はビデオ要素を削除
                $heroSection.find( '.hero-video-container' ).remove();
            }
        } );
    } );

    // 背景動画URL
    wp.customize( 'hero_background_video', function( value ) {
        value.bind( function( to ) {
            var $video = $( '.hero-section .hero-video-container video source' );
            if ( $video.length && to ) {
                $video.attr( 'src', to );
                $video.parent()[0].load();
            }
        } );
    } );

    // オーバーレイの濃度
    wp.customize( 'hero_overlay_opacity', function( value ) {
        value.bind( function( to ) {
            var opacity = parseFloat( to );
            if ( opacity === 0 ) {
                $( '.hero-section::before' ).css( 'display', 'none' );
            } else {
                $( '.hero-section::before' ).css({
                    'display': 'block',
                    'opacity': opacity
                });
            }
        } );
    } );

    // パーティクルの色
    wp.customize( 'hero_particles_color', function( value ) {
        value.bind( function( to ) {
            // CSSカスタムプロパティを更新
            document.documentElement.style.setProperty( '--hero-particles-color', to );
        } );
    } );

    // プライマリボタンテキスト
    wp.customize( 'hero_button_text', function( value ) {
        value.bind( function( to ) {
            if ( to ) {
                $( '.hero-actions .btn-primary .btn-text' ).text( to );
                $( '.hero-actions .btn-primary' ).show();
            } else {
                $( '.hero-actions .btn-primary' ).hide();
            }
        } );
    } );

    // プライマリボタンURL
    wp.customize( 'hero_button_url', function( value ) {
        value.bind( function( to ) {
            $( '.hero-actions .btn-primary' ).attr( 'href', to );
        } );
    } );

    // セカンダリボタンテキスト
    wp.customize( 'hero_button2_text', function( value ) {
        value.bind( function( to ) {
            if ( to ) {
                $( '.hero-actions .btn-secondary .btn-text' ).text( to );
                $( '.hero-actions .btn-secondary' ).show();
            } else {
                $( '.hero-actions .btn-secondary' ).hide();
            }
        } );
    } );

    // セカンダリボタンURL
    wp.customize( 'hero_button2_url', function( value ) {
        value.bind( function( to ) {
            $( '.hero-actions .btn-secondary' ).attr( 'href', to );
        } );
    } );

    // ヒーロー特徴
    for ( var i = 1; i <= 3; i++ ) {
        ( function( index ) {
            wp.customize( 'hero_feature_' + index + '_title', function( value ) {
                value.bind( function( to ) {
                    $( '.hero-feature:nth-child(' + index + ') h3' ).text( to );
                } );
            } );

            wp.customize( 'hero_feature_' + index + '_desc', function( value ) {
                value.bind( function( to ) {
                    $( '.hero-feature:nth-child(' + index + ') p' ).text( to );
                } );
            } );

            wp.customize( 'hero_feature_' + index + '_icon', function( value ) {
                value.bind( function( to ) {
                    var $icon = $( '.hero-feature:nth-child(' + index + ') .feature-icon i' );
                    if ( to ) {
                        if ( $icon.length ) {
                            $icon.attr( 'class', to );
                        } else {
                            $( '.hero-feature:nth-child(' + index + ') .feature-icon' ).html( '<i class="' + to + '"></i>' );
                        }
                    }
                } );
            } );
        } )( i );
    }

    // グラデーション色
    wp.customize( 'hero_gradient_start', function( value ) {
        value.bind( function( to ) {
            updateHeroGradient();
        } );
    } );

    wp.customize( 'hero_gradient_end', function( value ) {
        value.bind( function( to ) {
            updateHeroGradient();
        } );
    } );

    function updateHeroGradient() {
        var startColor = wp.customize( 'hero_gradient_start' ).get();
        var endColor = wp.customize( 'hero_gradient_end' ).get();
        $( '.hero-gradient-overlay' ).css( 'background', 'linear-gradient(135deg, ' + startColor + ' 0%, ' + endColor + ' 100%)' );
    }

    // テキスト色
    wp.customize( 'hero_text_color', function( value ) {
        value.bind( function( to ) {
            $( '.hero-section' ).css( 'color', to );
        } );
    } );

    // ヒーローの高さ
    wp.customize( 'hero_height', function( value ) {
        value.bind( function( to ) {
            var $hero = $( '.hero-section' );
            $hero.removeClass( 'hero-height-small hero-height-default hero-height-large hero-height-full' );
            $hero.addClass( 'hero-height-' + to );
        } );
    } );

    // テキスト配置
    wp.customize( 'hero_text_align', function( value ) {
        value.bind( function( to ) {
            $( '.hero-content' ).css( 'text-align', to );
            if ( to === 'left' ) {
                $( '.hero-content' ).css( 'align-items', 'flex-start' );
            } else if ( to === 'right' ) {
                $( '.hero-content' ).css( 'align-items', 'flex-end' );
            } else {
                $( '.hero-content' ).css( 'align-items', 'center' );
            }
        } );
    } );

    // アニメーション有効/無効
    wp.customize( 'hero_animation', function( value ) {
        value.bind( function( to ) {
            if ( to ) {
                $( '.hero-section' ).addClass( 'animations-enabled' );
            } else {
                $( '.hero-section' ).removeClass( 'animations-enabled' );
            }
        } );
    } );

    // サービスセクション
    wp.customize( 'show_services_section', function( value ) {
        value.bind( function( to ) {
            if ( to ) {
                $( '.services-section' ).show();
            } else {
                $( '.services-section' ).hide();
            }
        } );
    } );

    wp.customize( 'services_title', function( value ) {
        value.bind( function( to ) {
            $( '.services-section .section-title' ).text( to );
        } );
    } );

    wp.customize( 'services_description', function( value ) {
        value.bind( function( to ) {
            $( '.services-section .section-description' ).text( to );
        } );
    } );

    // アバウトセクション
    wp.customize( 'show_about_section', function( value ) {
        value.bind( function( to ) {
            if ( to ) {
                $( '.about-section' ).show();
            } else {
                $( '.about-section' ).hide();
            }
        } );
    } );

    wp.customize( 'about_title', function( value ) {
        value.bind( function( to ) {
            $( '.about-section .brand-statement-title' ).text( to );
        } );
    } );

    wp.customize( 'about_content', function( value ) {
        value.bind( function( to ) {
            $( '.about-section .brand-statement-text' ).html( '<p>' + to.replace(/\n/g, '</p><p>') + '</p>' );
        } );
    } );

    wp.customize( 'about_signature', function( value ) {
        value.bind( function( to ) {
            if ( to ) {
                $( '.brand-statement-signature' ).text( to ).show();
            } else {
                $( '.brand-statement-signature' ).hide();
            }
        } );
    } );

    // 選ばれる理由セクション
    wp.customize( 'show_features_section', function( value ) {
        value.bind( function( to ) {
            if ( to ) {
                $( '.features-section' ).show();
            } else {
                $( '.features-section' ).hide();
            }
        } );
    } );

    wp.customize( 'features_title', function( value ) {
        value.bind( function( to ) {
            $( '.features-section .section-title' ).text( to );
        } );
    } );

    wp.customize( 'features_description', function( value ) {
        value.bind( function( to ) {
            if ( to ) {
                $( '.features-section .section-description' ).text( to ).show();
            } else {
                $( '.features-section .section-description' ).hide();
            }
        } );
    } );

    // 選ばれる理由の各項目
    for ( var i = 1; i <= 3; i++ ) {
        ( function( index ) {
            wp.customize( 'feature_' + index + '_title', function( value ) {
                value.bind( function( to ) {
                    $( '.feature-item[data-feature="' + index + '"] .feature-title' ).text( to );
                } );
            } );

            wp.customize( 'feature_' + index + '_description', function( value ) {
                value.bind( function( to ) {
                    $( '.feature-item[data-feature="' + index + '"] .feature-description' ).text( to );
                } );
            } );

            wp.customize( 'feature_' + index + '_icon', function( value ) {
                value.bind( function( to ) {
                    var $icon = $( '.feature-item[data-feature="' + index + '"] .feature-circle i' );
                    if ( to ) {
                        if ( $icon.length ) {
                            $icon.attr( 'class', to );
                        } else {
                            $( '.feature-item[data-feature="' + index + '"] .feature-circle' ).append( '<i class="' + to + '"></i>' );
                        }
                    } else {
                        $icon.remove();
                    }
                } );
            } );
        } )( i );
    }

    // ニュースセクション
    wp.customize( 'show_news_section', function( value ) {
        value.bind( function( to ) {
            if ( to ) {
                $( '.news-section' ).show();
            } else {
                $( '.news-section' ).hide();
            }
        } );
    } );

    wp.customize( 'news_title', function( value ) {
        value.bind( function( to ) {
            $( '.news-section .section-title' ).text( to );
        } );
    } );

    // CTAセクション
    wp.customize( 'show_cta_section', function( value ) {
        value.bind( function( to ) {
            if ( to ) {
                $( '.cta-section' ).show();
            } else {
                $( '.cta-section' ).hide();
            }
        } );
    } );

    wp.customize( 'cta_title', function( value ) {
        value.bind( function( to ) {
            $( '.cta-section .cta-title' ).text( to );
        } );
    } );

    wp.customize( 'cta_description', function( value ) {
        value.bind( function( to ) {
            $( '.cta-section .cta-description' ).text( to );
        } );
    } );

    wp.customize( 'cta_button_text', function( value ) {
        value.bind( function( to ) {
            $( '.cta-section .cta-button-text' ).text( to );
        } );
    } );

    wp.customize( 'cta_button_url', function( value ) {
        value.bind( function( to ) {
            $( '.cta-section .cta-button' ).attr( 'href', to );
        } );
    } );

    wp.customize( 'cta_phone', function( value ) {
        value.bind( function( to ) {
            if ( to ) {
                $( '.cta-section .cta-phone-number' ).attr( 'href', 'tel:' + to ).html( '<i class="fas fa-phone"></i> ' + to );
                $( '.cta-section .cta-phone' ).show();
            } else {
                $( '.cta-section .cta-phone' ).hide();
            }
        } );
    } );

} )( jQuery );