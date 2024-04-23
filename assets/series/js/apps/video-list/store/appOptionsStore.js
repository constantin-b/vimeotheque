/**
 * Needed by the App to function properly.
 */

import {mapValues} from 'lodash'

const {
    data: {
        registerStore
    },
    hooks: {
        applyFilters
    }
} = wp

/**
 * The default state of the store.
 *
 */
const DEFAULT_STATE = applyFilters(
    'vimeotheque-series-app-options-defaults',
    {
        /**
         * Is the modal content select window open.
         */
        openContentModal: false,

        /**
         * The current page that video posts are loaded from.
         */
        currentPage: 1,

        /**
         * Search term.
         */
        search: '',

        /**
         * Was load more triggered for the current screen?
         */
        loadMore: false,
    }
)

registerStore(
    'vimeotheque-series/app-options',
    {
        actions: {
            updateOption( option, value ){
                return {
                    type: 'UPDATE_OPTION',
                    payload: {
                        option: option,
                        value: value
                    }

                }
            },
            resetOption( option ) {
                return {
                    type: 'RESET_OPTION',
                    payload: {
                        option: option
                    }
                }
            }
        },
        selectors: {
            getOption( state, option ){
                return state[ option ]
            }
        },
        reducer: ( state = DEFAULT_STATE, action ) => {
            switch( action.type ){
                case 'UPDATE_OPTION':
                    return mapValues(
                        state,
                        (val, key) => {
                            return key == action.payload.option ? action.payload.value : val
                        }
                    )
                    break
                case 'RESET_OPTION':
                    return mapValues(
                        state,
                        (val, key) => {
                            return key == action.payload.option ? DEFAULT_STATE[action.payload.option] : val
                        }
                    )

                    break
            }
            return state
        }
    }
)