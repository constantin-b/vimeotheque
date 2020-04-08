import {find} from 'lodash'

// reducer
const addPostsResource = ( state = [], action ) => {
    if( action.type == 'ADD_POSTS' ) {
        return state.concat( [action.payload] )
    }

    return state
}

// selector
const getPosts = ( state, url ) => {
    let obj = find( state, { url: url } )
    return obj
}

// action for adding posts
const addPosts = ( url, posts, total, pages ) => {
    return {
        type: 'ADD_POSTS',
        payload: {
            url: url,
            posts: posts,
            total: total,
            pages: pages
        }
    }
}

wp.data.registerStore( 'vimeotheque-post-store', {
    reducer: addPostsResource,
    selectors: { getPosts: getPosts },
    actions: { addPosts: addPosts }
})