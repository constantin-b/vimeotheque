import {getEditedPostId} from "../inc/functions";
import Title from "./components/Title";

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

const PostTitleApp = props => {

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
            dispatch( 'vimeotheque-series/playlist-options' ).updateOption( 'postTitle', post.title.raw )

            doAction(
                'vimeotheque-series-post-title-init',
                post
            )
        }
    }, [post])

    return (
        <div
            className="vimeotheque-series-post-title"
        >
            {
                isLoading &&
                    <>
                        <Spinner /> {__('Loading the post, please wait...', 'codeflavors-vimeo-video-post-lite')}
                    </>
            }
            {
                post &&
                    <Title />
            }
        </div>
    )
}

const root = createRoot( document.getElementById( 'vimeotheque-series-post-title' ) )
root.render( <PostTitleApp /> )