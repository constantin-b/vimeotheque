/** @namespace vimeotheque */
window.vimeotheque = window.vimeotheque || {}

import $ from 'jQuery'

(function( exports ){

    /**
     * Last known width of each observed player, used to skip
     * observer callbacks triggered only by height changes.
     */
    const widths = new WeakMap()

    /**
     * Resize players when their width changes for any reason (theme layout,
     * late loading CSS, sidebars, etc.), not only on window resize.
     */
    const observer = 'undefined' !== typeof window.ResizeObserver ?
        new ResizeObserver( entries => {
            entries.forEach( entry => {
                if( widths.get( entry.target ) !== $(entry.target).width() ){
                    resize( entry.target )
                }
            })
        }) : false

    const
    /**
     * Resize all player in page
     */
    resizeAll = () => {
        $('div.vimeotheque-player').each((i, el) => {
            vimeotheque.resize(el)
        })
    },

    /**
     * Resize given element
     * @param element
     */
    resize = element => {
        // allow jQuery objects to be passed
        element = $(element).get(0)
        if( !element ){
            return
        }

        const size_ratio = parseFloat( $(element).attr('data-size_ratio') || 0 ),
            aspect_ratio = $(element).attr('data-aspect_ratio'),
            width = $(element).width()

        let height

        if( size_ratio > 0 ){
            height = Math.floor( width / size_ratio )
        }else{
            switch(  aspect_ratio ){
                case '16x9':
                default:
                    height = Math.floor( (width *  9) / 16 );
                    break;
                case '4x3':
                    height = Math.floor( (width *  3) / 4 );
                    break;
                case '2.35x1':
                    height = Math.floor( width / 2.35 );
                    break;
            }
        }

        $(element).css({ height: height })

        if( observer ){
            if( !widths.has( element ) ){
                observer.observe( element )
            }
            widths.set( element, width )
        }
    }

    exports.resizeAll = resizeAll
    exports.resize = resize

}(vimeotheque))

$(document).ready( vimeotheque.resizeAll )
$(window).on( 'resize', vimeotheque.resizeAll )
