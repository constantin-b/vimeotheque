import {getEditedPostId} from "../../../inc/functions";

const {
    components: {
        Button
    },
    data: {
        useSelect,
        dispatch,
    },
    element: {
        useEffect,
        useState
    },
    hooks: {
        applyFilters,
    },
    i18n: {
        __
    },
} = wp

const ContentSelector = props => {

    const contentType = useSelect( select => select('vimeotheque-series/playlist-options').getOption( 'contentType' ) )

    const setContent = type => {
        dispatch('vimeotheque-series/playlist-options').updateOption( 'contentType', type )
        dispatch('vimeotheque-series/app-options').updateOption( 'openContentModal', true )

        dispatch('core').editEntityRecord( 'postType', 'series', getEditedPostId(), { 'content_type': type })
    }

    const _items = [
        <Button
            key='single-video'
            isLink={true}
            onClick={() => setContent('posts')}
        >
            {__('A playlist made of individual videos that I choose myself', 'codeflavors-vimeo-video-post-lite')}
        </Button>
    ]

    const items = applyFilters( 'vimeotheque-series-video-sources', _items )

    return (
        <div
            className='content-select container inline'
        >
            <div
                className="step-1">

                <h2>
                    {__('What will you build today?', 'codeflavors-vimeo-video-post-lite')}
                </h2>

                <p>
                    {items}
                </p>
            </div>
        </div>
    )
}

export default ContentSelector