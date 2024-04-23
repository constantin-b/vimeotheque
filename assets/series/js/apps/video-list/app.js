import './store/itemsStore'
import './store/optionsStore'
import './store/appOptionsStore'
import '../inc/functions'
import {getEditedPostId} from "../inc/functions"
import Initialize from "./components/content-select/Initialize"

const{
    components: {
        Spinner,
    },
    data: {
        useSelect,
        dispatch,
    },
    element: {
        createRoot,
        useEffect,
        useState,
    },
    hooks: {
        applyFilters,
        doAction,
    },
    i18n: {
        __
    }
} = wp



const VideoListApp = props => {

    const {
        post,
        isLoading
    } = useSelect(
        select => ({
            post: select( 'core' ).getEntityRecord( 'postType', 'series', getEditedPostId() ),
            isLoading: select('core/data').isResolving( 'core', 'getEntityRecord', ['postType', 'series', getEditedPostId()] )
        })
    )

    useEffect(() => {
        if( post ){
            dispatch( 'vimeotheque-series/playlist-options' ).updateOption( 'contentType', post.content_type )
            dispatch( 'vimeotheque-series/playlist-options' ).updateOption( 'shuffle', post.shuffle )

            doAction(
                'vimeotheque-series-items-init',
                post
            )
        }
    }, [post])

    return (
        <>
            {
                isLoading &&
                    <>
                        <Spinner /> {__( 'Loading the settings, please wait...', 'codeflavors-vimeo-video-post-lite' )}
                    </>
            }

            {
                !isLoading && post &&
                    <Initialize
                        item={post}
                    />
            }
        </>
    )
}

const root = createRoot( document.getElementById( 'vimeotheque-series-videos' ) )
root.render( <VideoListApp /> )