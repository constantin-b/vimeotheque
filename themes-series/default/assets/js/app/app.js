import $ from 'jQuery'

/** @namespace vimeotheque */
window.vimeotheque = window.vimeotheque || {};
vimeotheque.series = vimeotheque.series || {};

/**
 * Shows or hides the "See more" toggle depending on whether the content overflows.
 */
function checkSeeMoreVisibility( $contentWrapper ) {
    const inner = $contentWrapper.find( '.content-inner' )[0]
    if ( ! inner ) return
    const $toggle = $contentWrapper.find( '.see-more-toggle' )
    if ( inner.scrollHeight <= inner.clientHeight + 1 ) {
        $toggle.hide()
    } else {
        $toggle.show()
    }
}

vimeotheque.series.themeDefault = () => {

    $( '.vimeotheque-series.playlist.default:not(.loaded)' ).each( function () {
        const $series = $( this )
        const $contentWrapper = $series.find( '.featured-video-content' )

        if ( $contentWrapper.length ) {
            checkSeeMoreVisibility( $contentWrapper )

            $contentWrapper.find( '.see-more-toggle' ).on( 'click', function () {
                const $toggle = $( this )
                const isExpanded = $contentWrapper.hasClass( 'expanded' )
                $contentWrapper.toggleClass( 'expanded' )
                $toggle.text( isExpanded ? $toggle.data( 'see-more' ) : $toggle.data( 'see-less' ) )
            } )
        }
    } )

    $( '.vimeotheque-series.playlist.default:not(.loaded)' ).VimeoPlaylist( {
        'player': '.vimeotheque-player',
        'items': '.video-item',
        /**
         * Returns the current element from playlist that was loaded.
         *
         * @param item   Raw DOM element of the clicked video item.
         * @param index  Index of the item in the playlist.
         * @param player jQuery object wrapping the player element.
         */
        'loadVideo': ( item, index, player ) => {
            player[0].scrollIntoView( { behavior: 'smooth' } )

            const $item   = $( item )
            const $series = player.closest( '.vimeotheque-series' )

            const title   = $item.data( 'featured_title' )
            const content = $item.data( 'featured_content' )

            if ( title !== undefined ) {
                $series.find( '.featured-video-title' ).text( title )
            }

            if ( content !== undefined ) {
                const $cw = $series.find( '.featured-video-content' )
                $cw.find( '.content-inner' ).text( content )
                $cw.removeClass( 'expanded' )
                const $toggle = $cw.find( '.see-more-toggle' )
                $toggle.text( $toggle.data( 'see-more' ) )
                checkSeeMoreVisibility( $cw )
            }
        }
    } )

}

$( document ).ready( () => vimeotheque.series.themeDefault() )
