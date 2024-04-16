import {getEditedPostId} from "../inc/functions"
import Initialize from "./components/Initialize"

const {
    components: {
        Spinner,
    },
    data: {
        dispatch,
        useSelect,
    },
    element: {
        createRoot,
        useEffect,
        useState
    },
    hooks: {
        doAction,
    },
    i18n: {
        __
    }
} = wp

const PlayerApp = props => {

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
            dispatch( 'vimeotheque-series/playlist-options' ).updateOption( 'width', post.width )
            dispatch( 'vimeotheque-series/playlist-options' ).updateOption( 'volume', post.volume )

            doAction(
                'vimeotheque-series-player-init',
                post
            )
        }
    }, [post])

    return (
        <div
            className="vimeotheque-series-post-actions"
        >
            {
                isLoading &&
                <>
                    <Spinner /> {__( 'Loading the settings, please wait...', 'vimeotheque-series' )}
                </>
            }

            {
                !isLoading && post &&
                    <Initialize
                        item={post}
                    />
            }
        </div>
    )
}

const root = createRoot( document.getElementById( 'vimeotheque-series-player' ) )
root.render( <PlayerApp /> )