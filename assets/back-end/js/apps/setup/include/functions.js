import $ from 'jQuery'

/**
 * Set a parameter
 *
 * @param param
 * @param value
 */
export const setParam = ( param, value ) => {

    if( typeof window.vimeotheque === 'undefined' ){
        window.vimeotheque = {}
    }

    if( typeof param !== 'string' ){
        if( typeof console.error !== 'undefined' ){
            console.error( 'Parameter must be a string' )
        }
    }

    window.vimeotheque[ param ] = value
}

/**
 * Get a param
 *
 * @param param
 * @return {*}
 */
export const getParam = ( param, supress_warnings = false ) => {
    if( typeof window.vimeotheque[ param ] != 'undefined' ){
        return window.vimeotheque[ param ]
    }else{
        if( typeof console.error != 'undefined' && !supress_warnings ) {
            console.error(`Param ${param} does not exist.`)
        }
        return null
    }
}

/**
 * Add listener to be announced when step is changed
 *
 * @param func
 */
export const addStepListener = func => {
    window.vimeotheque.navigation.list.push( func )
}

/**
 * Trigger all step listeners
 *
 * @param postId
 * @param action
 */
export const triggerStepListeners = ( step ) => {

    $.each(
        window.vimeotheque.navigation.list,
        (i, func) => {
            func( step )
        }
    )
}