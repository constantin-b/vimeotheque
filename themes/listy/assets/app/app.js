import $ from 'jQuery'

/** @namespace vimeotheque */
window.vimeotheque = window.vimeotheque || {};
vimeotheque.themes = vimeotheque.themes || {};

( function( exports ){
    const themeListy = () => {

        const items = $('.vimeotheque-playlist.listy .entry-content')

        items.each(
            ( i, item ) => {
                if( $(item).height() > 200 ){
                    $(item).addClass('closed')

                    $('<a />', {
                        class: 'read-more',
                        href: '#',
                        text: $(item).data('open_text'),
                        click: e => {
                            e.preventDefault()
                            $(item).toggleClass('closed open')
                        }
                    }).appendTo( item )
                }
            }
        )

        vimeotheque.resizeAll()
    }

    exports.themeListy = themeListy
}( vimeotheque.themes ) )

$(document).ready( vimeotheque.themes.themeListy )