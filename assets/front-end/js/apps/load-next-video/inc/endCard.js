import $ from 'jQuery'

$.fn.VimeothequeEndCard = function(params ){

    if( 0 == this.length ){
        return
    }

    const
        defaults = {
            'title' : '',
            'permalink' : '',
            'featured_image': '',
            'duration': ''
        },
        self = this,
        options = $.extend( {}, defaults, params )


    const init = () => {

        const
            container = $('<div />',{
                class: 'vimeotheque-end-card-container'
            }),
            wrap = $( '<div />',{
                class: 'inside-wrapper'
            }),
            imageContainer = $( '<div />', {
                class: 'image'
            }),
            duration = $('<div />', {
                class: 'duration',
                text: options.duration
            }),
            image = $('<img />',{
                src: options.featured_image
            }),
            title = $('<h2/>',{
                html: options.title
            }),
            cancel = $('<a />', {
                class: 'cancel',
                html: 'Cancel',
                href: '#',
                on: {
                    click:  e => {
                        e.preventDefault()
                        self.trigger( 'cancel' )
                    }
                }
            }),
            go = $('<a />', {
                class: 'load-now',
                html: 'Play now',
                href: options.permalink
            })


        container.append(
            wrap.append(
                imageContainer
                    .append( image )
                    .append( duration )
            )
                .append( title )
                .append(
                    $( '<div />', { class: 'controls' } )
                        .append(cancel)
                        .append( go )
                )
        )

        self.append( container )

        return self
    }

    return init()
}