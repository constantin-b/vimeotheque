import $ from 'jQuery'

/** @namespace vimeotheque */
window.vimeotheque = window.vimeotheque || {};
vimeotheque.series = vimeotheque.series || {};

vimeotheque.series.themeDefault = () => {

    const lists = $('.vimeotheque-series.playlist.default:not(.loaded)').VimeoPlaylist({
        'player': '.vimeotheque-player',
        'items': '.video-item',
        /**
         * Returns the current element from playlist that was loaded.
         *
         * @param item
         */
        'loadVideo': (item, index, player) => {
            player[0].scrollIntoView({behavior: 'smooth'})
        }
    })

}

$(document).ready( () => vimeotheque.series.themeDefault() )