import Settings from "./Settings";

const {
    components: {
        Button,
    },
    data: {
        dispatch,
        useSelect,
    },
    hooks: {
        addAction,
        addFilter,
    },
    i18n: {
        __,
    }
} = wp

addFilter(
    'vimeotheque-series-theme-settings',
    'vimeotheque-series-theme-list-settings',
    (content, theme) => {

        if( 'list' == theme ) {
            content = <Settings />
        }

        return content
    }
)

addFilter(
    'vimeotheque-series-playlist-options-defaults',
    'vimeotheque-series-theme-list-options-defaults',
    options => {

        options.columns = 3
        options.playback = 'modal'

        return options
    }
)

addAction(
    'vimeotheque-series-items-init',
    'vimeotheque-theme-list-items-init',
    post => {
        dispatch( 'vimeotheque-series/playlist-options' ).updateOption( 'columns', post.columns )
        dispatch( 'vimeotheque-series/playlist-options' ).updateOption( 'playback', post.playback )
    }
)
