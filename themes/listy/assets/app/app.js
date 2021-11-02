import $ from 'jQuery'

/** @namespace vimeotheque */
window.vimeotheque = window.vimeotheque || {};
vimeotheque.themes = vimeotheque.themes || {};

( function( exports ){
    const themeListy = () => {

        const items = $('.vimeotheque-playlist.listy .entry-content')

        const isEmpty = element => {
            return !$.trim( element.html() )
        }

        items.addClass('closed').each(
            ( i, item ) => {

                if( isEmpty( $(item) ) ){
                    $(item).remove()
                }else{
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
    }

    exports.themeListy = themeListy
}( vimeotheque.themes ) )

$(document).ready( vimeotheque.themes.themeListy )