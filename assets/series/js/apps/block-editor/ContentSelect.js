import {handleScroll} from "../inc/functions"
import PostsList from "./PostsList";

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
    i18n: {
        __
    }
} = wp

const ContentSelect = props => {

    const loadMore = useSelect( select => select('vimeotheque-series/app-options').getOption( 'loadMore' ) )

    return (
        <div
            className="content-select container posts-select"
            onScroll={ handleScroll }
        >
            <PostsList
                loadMore={loadMore}
                onSelect={ props.onSelect }
            />
        </div>
    )
}

ContentSelect.defaultProps = {
    onSelect: () => {}
}

export default ContentSelect