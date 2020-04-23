window.vimeotheque = window.vimeotheque || {}

import $ from 'jQuery'
import "./inc/VimeoPlayer"
import './inc/helper'
import './inc/Playlist'

$(document).ready( () => {
    vimeotheque.players = $('.vimeotheque-player').VimeoPlayer(/*{
        onPlay: ( data )=>{
            console.log('started playing')
            console.log(data)
            console.log(this)
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
    }*/)
})