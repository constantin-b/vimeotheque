const {
    hooks: {
        addFilter,
    },
    i18n: {
        __,
    }
} = wp

addFilter(
    'vimeotheque-series-theme-settings',
    'vimeotheque-series-theme-carousel-settings',
    (content, theme) => {

        if( 'carousel' == theme ) {
            content = <p>{__( 'No available settings.', 'codeflavors-vimeo-video-post-lite' )}</p>
        }

        return content
    }
)