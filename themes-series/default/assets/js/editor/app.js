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
    'vimeotheque-series-theme-default-settings',
    (content, theme) => {

        if( 'default' == theme ) {
            content = <Settings />
        }

        return content
    }
)

addFilter(
    'vimeotheque-series-playlist-options-defaults',
    'vimeotheque-series-theme-default-options-defaults',
    options => {

        options.columns      = 3
        options.show_title   = 'yes'
        options.show_content = 'yes'

        return options
    }
)

addAction(
    'vimeotheque-series-items-init',
    'vimeotheque-theme-default-items-init',
    post => {
        dispatch( 'vimeotheque-series/playlist-options' ).updateOption( 'columns', post.columns )
        dispatch( 'vimeotheque-series/playlist-options' ).updateOption( 'show_title', post.show_title )
        dispatch( 'vimeotheque-series/playlist-options' ).updateOption( 'show_content', post.show_content )
    }
)
