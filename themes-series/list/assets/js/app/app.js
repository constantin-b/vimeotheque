import $ from 'jQuery'
import './jquery-modal'


/** @namespace vimeotheque */
window.vimeotheque = window.vimeotheque || {};
vimeotheque.series = vimeotheque.series || {};

vimeotheque.series.themeList = () => {

    if( $('.vimeotheque-series.playlist.list').length > 0 ){

        const
            elements = $('.vimeotheque-series.playlist.list .video-item'),
            // The video element
            videoEl = $('<div />', {
                class: 'video-embed vimeotheque-player'
            }),
            // The modal main container
            modalContainer = $('<div />', {
                class: 'vimeotheque-series-video-modal-display',
                css:{
                    display: 'none'
                },
                html: videoEl
            })
                .appendTo('body')
                .on( $.modal.CLOSE, ( event, modal ) => {
                    $(videoEl)
                        .empty()
                        .removeClass('loaded')
                        .removeAttr('data-embed_url data-aspect_ratio data-size_ratio data-volume data-autoplay data-video_id style');
                })

        let	current = 0,
            total = elements.length

        const
            // Modal window navigation container (prev/next video)
            navigation = $('<div />', {
                class: 'modal-nav'
            }).appendTo( modalContainer ),
            //
            navPrev = $('<a />', {
                class: 'prev',
                text: '',
                href: '#'
            }).on(
                'click',
                e => {
                    e.preventDefault()
                    goTo( -1 )
                }
            ).appendTo( navigation ),

            currentVideo = (
                $('<div />', {
                    class: 'current',
                    text: '',
                })
            ).appendTo( navigation ),

            navNext = $('<a />', {
                class: 'next',
                text: '',
                href: '#'
            }).on(
                'click',
                e => {
                    e.preventDefault()
                    goTo( +1 )
                }
            ).appendTo( navigation )

        const
            // Show the navigation controls
            showNavs = () => {
                if( current == ( total -1 ) ){
                    navNext.addClass('hide')
                    navPrev.removeClass('hide').text( $( elements[ current - 1 ] ).data( 'title' ) )
                }else if( current == 0 ){
                    navPrev.addClass('hide')
                    navNext.removeClass('hide').text( $( elements[ current + 1 ] ).data( 'title' ) )
                }else{
                    navNext.removeClass('hide').text( $( elements[ current + 1 ] ).data( 'title' ) )
                    navPrev.removeClass('hide').text( $( elements[ current - 1 ] ).data( 'title' ) )
                }

                currentVideo
                    .text( $( elements[ current ] ).data( 'title' ) )
                    .removeClass('playing')
            },
            // Navigate between items
            goTo = index => {
                current += index;
                if( current >= total ){
                    current = total - 1
                }else if( current < 0 ){
                    current = 0
                }

                showNavs();

                const
                    el = $( elements )[current],
                    data = $(el).data();

                $(videoEl)
                    .data('ref')
                    .loadVideo( data['video_id'] )

                $.each( data, (i, v) => {
                    $(videoEl).attr( 'data-' + i, v )
                })

                window.vimeotheque.resize( videoEl );
            },
            goToNext = () => {
                goTo( +1 )
            }

        $( elements ).on(
            'click',
            e => {
                e.preventDefault()
                const data = $( e.currentTarget ).data()

                current = $( elements ).index( $( e.currentTarget ) )
                showNavs()

                $(videoEl)
                    .empty()
                    .html(
                        $('<iframe />',  {
                            width: '100%',
                            height: '100%',
                            frameborder: 0,
                            allow: 'autoplay; fullscreen',
                            allowfullscreen: 'allowfullscreen',
                            src: data.embed_url + '&transparent=1'
                        })
                    )
                    .VimeoPlayer({
                        onFinish: data => {
                            //$.modal.close();
                            goToNext()
                        },
                        onLoad: player => {
                            player.play()
                        },
                        onPlay: () => currentVideo.addClass('playing'),
                        onPause: () => currentVideo.removeClass('playing'),
                    })

                $.each( data, ( i, v ) => {
                    $(videoEl).attr( 'data-' + i, v )
                })

                $(modalContainer).modal()

                if( data.size_ratio > 0 && data.size_ratio < 1 ) {
                    const
                        w = $(videoEl).width(),
                        height = w / data.size_ratio

                    if ( height > $(window).height() - 200 ) {
                        $(videoEl).css({width: Math.floor(($(window).height() - 200) * data.size_ratio)})
                    }
                }

                window.vimeotheque.resize( videoEl )
            }
        )

    }
}

$(document).ready( () => vimeotheque.series.themeList() )