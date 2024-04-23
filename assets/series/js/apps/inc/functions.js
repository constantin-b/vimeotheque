const {
    data: {
        dispatch
    }
} = wp

/**
 * Get the currently edited post ID.
 *
 * @returns {*}
 */
export const getEditedPostId = () => {
    const {
        postId
    } = VSE

    return postId
}

/**
 * Handles element scroll.
 *
 * @param e
 */
export const handleScroll = e => {
    const bottom = e.target.scrollHeight - e.target.scrollTop === e.target.clientHeight
    dispatch( 'vimeotheque-series/app-options' ).updateOption( 'loadMore', bottom )
}