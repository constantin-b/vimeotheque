import '../inc/registerEntities'
import ThemesList from "./components/ThemesList"

import {getEditedPostId} from "../inc/functions"
import Settings from "./components/Settings";

const {
    components: {
        Button,
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

const ThemeApp = props => {

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
            dispatch( 'vimeotheque-series/playlist-options' ).updateOption( 'theme', post.theme )

            doAction(
                'vimeotheque-series-themes-init',
                post
            )
        }
    }, [post])

    return (
        <div
            className="vimeotheque-series-themes"
        >
            {
                isLoading &&
                <>
                    <Spinner /> {__( 'Loading the themes, please wait...' )}
                </>
            }

            {
                !isLoading &&
                    <Settings />
            }

            {
                !isLoading &&
                    <ThemesList />
            }
        </div>
    )
}

const root = createRoot( document.getElementById( 'vimeotheque-series-theme' ) )
root.render( <ThemeApp /> )