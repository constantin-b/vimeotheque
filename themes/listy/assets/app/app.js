import $ from 'jQuery'

$(document).ready(
    () => {

        const items = $('.vimeotheque-playlist.listy .entry-content')

        items.addClass('closed').each(
            ( i, item ) => {

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
        )
    }
)