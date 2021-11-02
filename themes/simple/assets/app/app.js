import $ from 'jQuery'

/** @namespace vimeotheque */
window.vimeotheque = window.vimeotheque || {};
vimeotheque.themes = vimeotheque.themes || {};

( function( exports ){
    const themeSimple = () => {
        $('.vimeotheque-playlist.simple').css( 'display', 'block' )
    }

    exports.themeSimple = themeSimple

}( vimeotheque.themes ) )

$(document).ready( vimeotheque.themes.themeSimple )