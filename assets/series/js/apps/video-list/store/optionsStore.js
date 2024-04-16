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
    'vimeotheque-series-playlist-options-defaults',
    {
        /**
         * The type of content to select from.
         *
         * Available options: posts, categories, api
         *
         * @var string
         */
        contentType: '',
        /**
         * Series is set to shuffle.
         *
         * @var bool
         */
        shuffle: false,
        /**
         * The selected theme.
         *
         * @var string
         */
        theme: '',
        /**
         * The post title.
         */
        postTitle: '',
        /**
         * Player volume
         */
        volume: 30,
        /**
         * Player maximum width
         */
        width: 1200,
    }
)

registerStore(
    'vimeotheque-series/playlist-options',
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
            reset(){
                return {
                    type: 'RESET'
                }
            }
        },
        selectors: {
            getOption( state, option ){
                return state[ option ]
            },
            getOptions( state ){
                return state
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
                case 'RESET':
                    return DEFAULT_STATE
                break
            }
            return state
        }
    }
)