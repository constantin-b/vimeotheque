import $ from 'jQuery'

/**
 * Load playlists
 */
$.fn.VimeoPlaylist = function( params ){

    if( 0 == this.length ){
        return false;
    }

    // support multiple elements
    if (this.length > 1){
        return this.each( ( index, item ) => {
            $(item).VimeoPlaylist( options );
        });
    }

    const options = $.extend({}, $.fn.VimeoPlaylist.defaults, params),
          self = this,
          player = $(this)
                      .find( options.player )
                      .VimeoPlayer({
                          // when the iframe URL is reloaded, re-initialize the playlist
                          onIframeReload: () => {
                             self.VimeoPlaylist()
                          },
                          onFinish: ()=>{
                              loadNext()
                          }
                      });

    /**
     * If VimeoPlayer returned an error set the playlist to be loaded again after a short delay.
     * Most likely this is caused by the Complianz plugin which doesn't allow VimeoPlayer to load
     * the video.
     */
    if( player.isError ){
        return;
    }

    const
          items = $(this).find( options.items ),
          {
              playlist_loop: playlistLoop,
              volume
          } = $(player).data()

    let currentItem	= 0

    /**
     * Start the plugin
     *
     * @returns {$.fn.VimeoPlaylist}
     */
    const initialize = () => {
        // prepare the player
        vimeotheque.resize( player )
        player.setVolume( volume/100 )

        $.each( items, function(i, item){
            if( 0 == i ){
                loadItem( item, i )
            }

            $(item).on( 'click', e => {
                e.preventDefault()

                player.getVolume().then( volume => {
                    loadItem( item, i, volume )
                } )

            })
        });

        return self;
    },

    /**
     * Load an item from items list
     *
     * @param item - HTML element
     * @param int index - element index
     */
    loadItem = (item, index, volume = false ) => {

        let {
                autoplay,
                video_id,
                size_ratio,
                aspect_ratio
            } = $(item).data()

        $(items[currentItem]).removeClass('active-video');
        $(item).addClass('active-video');

        player
            .loadVideo( video_id )
            .attr({
                'data-size_ratio': size_ratio,
                'data-aspect_ratio': aspect_ratio
            })

        if( volume )
            player.setVolume( volume )

        vimeotheque.resize(player)

        if( ( 1 == autoplay || 1 == playlistLoop ) && !is_apple() ){
            player.play()
        }

        currentItem = index

        /**
         * Trigger loadVideo event
         */
        options.loadVideo.call( self, item, index, player )
    },

    /**
     * Load next video in line. Triggered after a video ends
     */
    loadNext = () => {
        if( 1 != playlistLoop ){
            return;
        }

        if( currentItem < items.length -1 ){
            currentItem++
            var item = items[currentItem]
            $(item).trigger('click')
        }
    },

    /**
     * Check browser
     *
     * @returns {boolean}
     */
    is_apple = () => {
        return /webOS|iPhone|iPad|iPod/i.test(navigator.userAgent)
    }

    return initialize()
}

$.fn.VimeoPlaylist.defaults = {
    'player' 	: '.vimeotheque-player',
    'items'	 	: '.cvm-playlist-item a',
    'loadVideo'	: function(){}
}