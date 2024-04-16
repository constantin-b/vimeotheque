/**
 * Hack to replace missing functionality in WP that will be implemented sometime in the future.
 *
 * Docs currently specify that getEntityRecordsTotalItems() and getEntityRecordsTotalPages()
 * can be used, but they are currently unavailable.
 *
 * Current implementation will create a store that saves the pagination details for a given path.
 *
 * Usage:
 *
 *    import {omit} from 'lodash'
 *
 *    const {
 *        data: {
 *            useSelect
 *        },
 *        url: {
 *            addQueryArgs
 *        }
 *    } = wp
 *
 *    const {
 *        pages: totalPages,
 *        total: totalResults
 *    } = useSelect(
 *        select => {
 *            return select('vimeotheque-series/pagination-store')
 *                .getPagination( addQueryArgs( 'wp/v2/vimeo-video', omit( props.queryArgs, ['page'] ) ) )
 *        }
 *    )
 *
 */

import {find} from 'lodash'

const {
    apiFetch,
    data: {
        registerStore
    },
    url: {
        addQueryArgs
    }
} = wp

const DEFAULT_STATE = [
    {
        path: '',
        pages: 0,
        total: 0
    }
]

const actions = {
    setPagination( path, pages, total ){
        return {
            type: 'SET_PAGINATION',
            payload: {
                path,
                pages,
                total
            }
        }
    },
    //*
    fetchFromApi( path ){
        return {
            type: 'FETCH_FROM_API',
            path
        }
    }
    //*/
}

const reducer = ( state = DEFAULT_STATE, action ) => {

    switch( action.type ){
        case 'SET_PAGINATION':

            if( !find( state, {path: action.payload.path} ) ) {
                return [
                    ...state,
                    action.payload
                ]
            }

        break
    }

    return state

}

registerStore(
    'vimeotheque-series/pagination-store',
    {
        reducer: reducer,
        selectors: {
            getPagination( state, path ){
                return find( state, {path: path} ) || DEFAULT_STATE[0]
            }
        },
        actions: actions,
        //*
        controls: {
            async FETCH_FROM_API( action ){

                let pages = 0,
                    total = 0

                await apiFetch({
                    path: action.path,
                    method: 'HEAD',
                    parse: false
                }).then(
                    result => {
                        pages = parseInt(result.headers.get('X-WP-TotalPages'))
                        total = parseInt(result.headers.get('X-Wp-Total'))
                    }
                )

                return {
                    path: action.path,
                    pages: pages,
                    total: total
                }
            }
        },
        resolvers: {
            *getPagination( path ){
                const result = yield actions.fetchFromApi( path )
                return actions.setPagination( result.path, result.pages, result.total )
            }
        }
       //*/
    }
)