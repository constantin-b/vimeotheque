import {unionBy} from 'lodash'

const {
    data: {
        registerStore
    }
} = wp

const DEFAULT_STATE = []

registerStore(
    'vimeotheque-series/items-loaded',
    {
        actions: {
            addItems( videos ){
                return {
                    type: 'ADD_ITEMS',
                    payload: {
                        value: videos
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
            getItems( state ){
                return state
            }
        },

        reducer: ( state = DEFAULT_STATE, action ) => {

            switch( action.type ){
                case 'ADD_ITEMS':
                    if( action.payload.value ) {
                        state = unionBy( state, action.payload.value, 'id' )
                    }
                break
                case 'RESET':
                    state = []
                break
            }

            return state
        }
    }
)