import $ from 'jQuery'
import './inc/endCard'
import './inc/counter'

const Init = ( event, player ) => {

    $(player).on(
        'onEnd',
        () => {
            if( 'undefined' !== typeof vimeotheque.next_post ) {

                let cancelled = false

                $(player).empty().addClass( 'show-end-card' )
                $(player)
                    .VimeothequeCounter({
                        textBefore: 'Up next in',
                        seconds: 7
                    })
                    .VimeothequeEndCard(
                        vimeotheque.next_post
                    )
                    .on(
                        'timerExpired',
                        () => {
                            if( !cancelled )
                                window.location = vimeotheque.next_post.permalink
                        }
                    ).on(
                        'cancel',
                        () => {

                            player
                                .html(
                                    $( '<iframe />',{
                                            src: vimeotheque.current_post.embed_url,
                                            width: '100%',
                                            height: '100%',
                                            frameborder: 0,
                                            allowFullScreen: '',
                                            mozallowfullscreen: '',
                                            webkitallowfullscreen: ''
                                        }
                                    )
                                )

                            player
                                .removeClass( 'show-end-card' )
                                .VimeoPlayer()

                            cancelled = true
                        }
                )
            }
        }
    )
}

$(document).on( 'Vimeotheque_PlayerReady', Init )
