/** @namespace vimeotheque */
window.vimeotheque = window.vimeotheque || {}

import $ from 'jQuery'

vimeotheque.resizeAll = () => {
    $( 'div.vimeotheque-player' ).each( (i, el) => {
        vimeotheque.resize(el)
    })
}

vimeotheque.resize = element => {
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
}

$(document).ready( vimeotheque.resizeAll )
$(window).resize( vimeotheque.resizeAll )