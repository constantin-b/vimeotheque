window.vimeotheque = window.vimeotheque || {}

import $ from 'jQuery'
import "./inc/VimeoPlayer"
import './inc/helper'
import './inc/Playlist'

$(document).ready( () => {

    const playersData = {
        /*
        onPlay: ( data )=>{
            console.log('started playing')
            console.log(data)
        },

        onPlayback: ( data )=>{
            console.log('playing')
            console.log(data)
        },
        onPause: ( data ) => {
            console.log( 'paused' )
            console.log( data )
        },
        onLoad: ( data ) => {
            console.log( 'loaded' )
            console.log( data )
        },
        onFinish: ( data ) => {
            console.log( 'finished' )
            console.log( data )
        },
        onError: ( data ) => {
            console.log( 'error' )
            console.log( data )
        }
        //*/
    }


    //*
    $('.vimeotheque-player.lazy-load .vimeotheque-load-video').
        on(
            'click',
            function(e){
                e.preventDefault()
                $(this)
                    .closest( '.vimeotheque-player.lazy-load' )
                    .html(
                        $( '<iframe />',{
                                src: $(this).data('url'),
                                width: '100%',
                                height: '100%',
                                frameborder: 0
                            }
                        )
                    )
                    .removeClass('lazy-load')
                    .VimeoPlayer( playersData )
            }
        )
    //*/

    $('.vimeotheque-player.lazy-load').each(
        (i, player) => {
            const img = $(player).find('img.video-thumbnail')
            if( 0 == img.length ){
                return
            }

            let width = false,
                height = false

            if( $( img[0] ).prop('complete') ){
                width = $(img[0]).width()
                height = $(img[0]).height()
            }

            $( img[0] ).on(
                'load',
                () => {
                    width = $(img[0]).width()
                    height = $(img[0]).height()
                }
            )

            const interval = setInterval(
                () => {
                    if( width && height ){

                        const
                            playerWidth = $(player).width(),
                            playerHeight = $(player).height()

                        if( height < playerHeight ){
                            $( img[0] ).addClass('center-horizontal')
                        }

                        clearInterval(interval)
                    }

                }, 300
            )
        }
    )

    // Embed any not lazy-laoded players
    vimeotheque.players = $('.vimeotheque-player:not(.lazy-load)').VimeoPlayer( playersData )
})