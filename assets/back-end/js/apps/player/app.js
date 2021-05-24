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

    // Embed any not lazy-laoded players
    vimeotheque.players = $('.vimeotheque-player:not(.lazy-load)').VimeoPlayer( playersData )
})