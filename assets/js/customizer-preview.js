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
                $( '.hero-modern' ).show();
            } else {
                $( '.hero-modern' ).hide();
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
