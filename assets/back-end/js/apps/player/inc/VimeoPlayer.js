import $ from 'jQuery'

$.fn.VimeoPlayer = function( params ){

    if( 0 == this.length ){
        return false;
    }

    // support multiple elements
    if ( this.length > 1 ){
        return this.each( ( index, item ) => {
            $(item).VimeoPlayer()
        });
    }

    const self = this,
          options = $.extend( {}, $.fn.VimeoPlayer.defaults, params ),
          data = $(this).data()

    /**
     * Initialize player variable
     */
    let player,
        initialVolumeSet = false

    /**
     * Used to check if the player issued any errors
     * @type {boolean}
     */
    this.isError = false

    /**
     * Plugin Complianz (https://wordpress.org/plugins/complianz-gdpr/) will replace the iframe Vimeo URL with a custom video
     * file that requires user consent to load the video.
     * To avoid errors, check if the player is issuing any errors.
     */
    try{
        player = new Vimeo.Player( $(this).find('iframe') )
    }catch( e ){
        self.isError = e;
    }

    if( this.isError ){
        try{
            console.log( '%cCould not load Vimeotheque player for video ' + data.video_id + ' due to Vimeo.Player error.', "color: #FF0000" );
        }catch(e){}

        /**
         * In case of player error, set a load event on the iframe to trigger reload events
         * on any any clients that initialized the player
         */
        $(this)
            .find('iframe')
            .on(
                'load',
                options.onIframeReload
            )

        return self
    }

    player.on( 'loaded', () => {
        /**
         * @since 2.0.14    Add CSS class loaded on video container after the video has loaded
         */
        self.addClass('loaded')
        options.onLoad( self )

        /**
         * Custom event triggered after the player loads.
         *
         * @param object Element    The video container element that holds the video.
         */
        $(document).trigger(
            'Vimeotheque_PlayerReady',
            [self]
        )
    })
    player.on( 'play', _data => {
        if( !initialVolumeSet ) {
            self.setVolume( parseInt( data.volume ) / 100 )
            initialVolumeSet = true
        }
        options.onPlay( _data, self )

        /**
         * Trigger event on playing
         */
        self.trigger(
            'onPlay',
            data
        )
    })
    player.on( 'timeupdate', data => {
        options.onPlayback( data, self )
    } )
    player.on( 'pause', data => {
        options.onPause( data, self )

        /**
         * Trigger event on playing
         */
        self.trigger(
            'onPause',
            data
        )
    } )
    player.on( 'ended', data => {
        options.onFinish( data, self )

        /**
         * Trigger event on playing
         */
        self.trigger(
            'onEnd',
            data
        )
    } )
    player.on( 'error', data => {
        options.onError( data, self )
    } )
    // If user changes volume manually, before the playback is initiated, don't reset the volume on playback
    player.on( 'volumechange', data => {
        initialVolumeSet = true
    } )


    /**
     * Load a new video into the player
     * @param id
     * @return {$.fn.VimeoPlayer}
     */
    this.loadVideo = id => {
        player.loadVideo( id ).then( id => {

        } ).catch( error => {
            //console.log(error)
        } )

        return self
    }

    /**
     * Pause video method
     * @return {$.fn.VimeoPlayer}
     */
    this.pause = () => {
        player.pause().then( ()=>{

        } ).catch( error => {
            //console.log(error)
        } )

        return self
    }

    /**
     * Play video method
     * @return {$.fn.VimeoPlayer}
     */
    this.play = () => {
        player.play().then( ()=>{

        } ).catch( error => {
            //console.log(error)
        } )

        return self
    }

    /**
     * This method sets the volume level of the player on a scale from 0 to 1.
     * When you set the volume through the API, the specified value isn't synchronized to other players or
     * stored as the viewer's preference.
     *
     * Set volume (between 0 and 1)
     *
     * @param volume
     * @return {$.fn.VimeoPlayer}
     */
    this.setVolume = volume => {

        if( data.background || data.muted ){
            return self
        }

        player.setVolume(volume).then( _volume => {

        } ).catch( error => {
            //console.log(error)
        } )

        return self
    }

    /**
     * Get current volume of player
     *
     * @return Promise
     */
    this.getVolume = () => {
        return player.getVolume()
    }

    /**
     * This method sets the current playback position in seconds.
     * The player attempts to seek to as close to the specified time as possible.
     * The exact time comes back as the fulfilled value of the promise.
     *
     * @param float seconds - where to start playback, in seconds.milliseconds (ie. 3.543)
     * @return {$.fn.VimeoPlayer}
     */
    this.setPlaybackPosition = ( seconds ) => {
        player.setCurrentTime( seconds ).then( ( _seconds ) => {

        } ).catch( (error) => {
            //console.log(error)
        } )

        return self
    }

    /**
     *
     * @returns {Vimeo.Player}
     */
    this.getPlayer = () => {
        return player
    }

    $(this).data( 'ref', this )
    return self
}

$.fn.VimeoPlayer.defaults = {
    onIframeReload: () => {},
    /**
     * Action triggered after the video has loaded into the iframe player.
     * @param data
     */
    onLoad: ( data ) => {},
    /**
     * Action triggered when the user is played.
     * @param data
     */
    onPlay: ( data ) => {},
    /**
     * Action triggered on video playback.
     * @param data
     */
    onPlayback: (data) => {},
    /**
     * Action triggered when the video is paused.
     * @param data
     */
    onPause: ( data ) => {},
    /**
     * Action triggered when the video finishes' playback.
     * @param data
     */
    onFinish: ( data ) => {},
    /**
     * Action triggered on player error.
     * @param data
     */
    onError: ( data ) => {},
}