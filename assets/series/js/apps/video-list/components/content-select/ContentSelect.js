import PostsList from "./PostsList"
import {handleScroll} from "../../../inc/functions"

const {
    components: {
        Button
    },
	data: {
		dispatch,
		useSelect,
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
    }
} = wp

const ContentSelect = props => {

	const
        displayContent = useSelect( select => select('vimeotheque-series/playlist-options').getOption( 'contentType' ) ),
        loadMore = useSelect( select => select('vimeotheque-series/app-options').getOption( 'loadMore' ) )

    const screen = applyFilters( 'vimeotheque-series-display-content-selector', <></> )

    return (
        <div
            className={`content-select container ${ 'posts' == displayContent ? 'posts-select' : '' }`}
            onScroll={ handleScroll }
        >
			{
				'posts' == displayContent &&
					<PostsList
                        loadMore={loadMore}
                    />
			}

            {
                screen
            }
        </div>
    )
}

export default ContentSelect