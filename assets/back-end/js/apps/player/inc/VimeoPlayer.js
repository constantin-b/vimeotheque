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
          player = new Vimeo.Player( $(this).find('iframe') ),
          data = $(this).data()

    player.on( 'loaded', options.onLoad )
    player.on( 'play', options.onPlay )
    player.on( 'timeupdate', options.onPlayback )
    player.on( 'pause', options.onPause )
    player.on( 'ended', options.onFinish )
    player.on( 'error', options.onError )

    /**
     * Load a new video into the player
     * @param id
     * @return {$.fn.VimeoPlayer}
     */
    this.loadVideo = id => {
        player.loadVideo( id ).then( id => {

        } ).catch( error => {
            console.log(error)
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
            console.log(error)
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
            console.log(error)
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
    this.setVolume = ( volume ) => {
        player.setVolume(volume).then( ( _volume )=>{

        } ).catch( error => {
            console.log(error)
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
            console.log(error)
        } )

        return self
    }

    /**
     *
     * @returns {Vimeo.Player}
     */
    this.getPlayer = () => {
        return player;
    }

    $(this).data( 'ref', this )
    return self
}

$.fn.VimeoPlayer.defaults = {
    onLoad: ( data ) => {},
    onPlay: ( data ) => {},
    onPlayback: (data) => {},
    onPause: ( data ) => {},
    onFinish: ( data ) => {},
    onError: ( data ) => {},
}