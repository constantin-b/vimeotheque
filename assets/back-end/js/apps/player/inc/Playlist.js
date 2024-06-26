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
            $(item).VimeoPlaylist( params )
        });
    }

    let hasInteraction = false

    const options = $.extend({}, $.fn.VimeoPlaylist.defaults, params),
          self = this,
          player = $(this)
                      .find( options.player )
                      .VimeoPlayer({
                          // when the iframe URL is reloaded, re-initialize the playlist
                          onIframeReload: () => {
                             self.VimeoPlaylist( options )
                          },
                          onFinish: ()=>{
                              loadNext()
                          },
                          onPlay: () => {
                              hasInteraction = true
                          },
                          onLoad: () => {
                              if( 1 == playlistLoop && hasInteraction ){
                                  player.play()
                              }
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

    let items = $(this).find( options.items )

    const {
              playlist_loop,
              volume,
              shuffle,
          } = $(player).data()

    if( shuffle ){
        if ( items.length > 2 ) {
            // Note the -2 (instead of -1) and the i > 1 (instead of i > 0):
            for (let i = items.length - 1; i > 1; --i) {
                const j = 1 + Math.floor(Math.random() * i);
                [items[i], items[j]] = [items[j], items[i]];
            }
        }
    }

    let currentItem	= 0,
        playlistLoop = parseInt( playlist_loop )

    /**
     * Start the plugin
     *
     * @returns {$.fn.VimeoPlaylist}
     */
    const initialize = () => {
        // prepare the player
        vimeotheque.resize( player )
        player.setVolume( volume/100 )

        $.each( items, (i, item) => {
            if( 0 == i ){
                loadItem( item, i )
                $(item).addClass('active-video')
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
        // Prevent reloads if the current item is clicked again
        if( index === currentItem ){
            return
        }

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
            $( items[currentItem+1] ).trigger('click')
        }
    }

    return initialize()
}

$.fn.VimeoPlaylist.defaults = {
    'player' 	: '.vimeotheque-player',
    'items'	 	: '.cvm-playlist-item a[data-video_id]',
    'loadVideo'	: function(){},
}