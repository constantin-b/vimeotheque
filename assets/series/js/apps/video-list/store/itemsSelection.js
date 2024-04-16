/**
 * Videos store for videos that were temporarily selected from the modal window
 * and were not set as playlist videos yet.
 */

import {includes, filter} from 'lodash'

const {
    data: {
        registerStore
    }
} = wp

const DEFAULT_STATE = []

registerStore(
    'vimeotheque-series/items-selection',
    {
        actions: {
            updateOption( items ){
                return {
                    type: 'UPDATE_OPTION',
                    payload: {
                        value: items
                    }
                }
            },
            manageItem( item ) {
                return {
                    type: 'MANAGE_ITEM',
                    payload:{
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
                case 'MANAGE_ITEM':
                    if( includes( state, action.payload.value ) ){
                        state = filter(
                            state,
                            _item => {
                                return _item.id !== action.payload.value.id
                            }
                        )
                    }else{
                        state = [ ...state, action.payload.value ]
                    }
                    break
            }

            return state
        }
    }
)