const {
    data: {
        dispatch
    }
} = wp

dispatch('core').addEntities(
    [
        /**
         * Themes entity endpoint.
         * Can be accessed with: wp.data.select('core').getEntityRecords('vimeotheque-series', 'themes')
         */
        {
            name: 'themes',
            kind: 'vimeotheque-series',
            baseURL: 'vimeotheque-series/v1/themes',
            key: 'folder'
        }
    ]
)