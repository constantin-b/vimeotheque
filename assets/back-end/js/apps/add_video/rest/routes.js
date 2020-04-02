const dispatch = wp.data.dispatch

dispatch( 'core' ).addEntities([
    {
        name: 'pictures',
        kind: 'vimeotheque/v1',
        baseURL: 'vimeotheque/v1/api-query/pictures/'
    },
    {
        name: 'search',
        kind: 'vimeotheque/v1',
        baseURL: 'vimeotheque/v1/api-query/search/'
    },
    {
        name: 'video',
        kind: 'vimeotheque/v1',
        baseURL: 'vimeotheque/v1/api-query/video/'
    },
])