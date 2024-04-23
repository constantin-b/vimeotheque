import {map, filter} from 'lodash'
import {getEditedPostId} from "../../inc/functions";

const {
    data: {
        dispatch,
        registerStore
    }
} = wp

const DEFAULT_STATE = []

registerStore(
    'vimeotheque-series/items-store',
    {
        actions: {
            updateOption( videos ){
                return {
                    type: 'UPDATE_OPTION',
                    payload: {
                        value: videos
                    }
                }
            },
            addItem( item ){
                return {
                    type: 'ADD_ITEM',
                    payload: {
                        value: item
                    }
                }
            },
            removeItem( item ) {
                return {
                    type: 'REMOVE_ITEM',
                    payload: {
                        value: item
                    }
                }
            }
        },

        selectors: {
            getItems( state ){
                return state
            }
        },

        reducer: ( state = DEFAULT_STATE, action ) => {

            switch( action.type ){
                case 'UPDATE_OPTION':
                    state = action.payload.value
                break
                case 'ADD_ITEM':
                    state = [ ...state, action.payload.value ]
                break
                case 'REMOVE_ITEM':
                    state = filter(
                        state,
                        _item => {
                            return _item.id !== action.payload.value.id
                        }
                    )
                break
            }

            if( 'payload' in action ) {
                dispatch('core').editEntityRecord('postType', 'series', getEditedPostId(), {'items': map(state, 'id')})
            }

            return state
        }
    }
)