const {
    components: {
        Spinner
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

const VideoLoad = props => {

    const {
        posts,
        isLoading
    } = useSelect(
        select => ({
            posts: select('core').getEntityRecords( 'postType', 'vimeo-video', {include: props.item.items, orderby: 'include'} ),
            isLoading: select('core/data').isResolving( 'core', 'getEntityRecords', ['postType', 'vimeo-video', {include: props.item.items, orderby: 'include'}] )
        })
    )

    useEffect(
        () => {
            if( posts ){
                dispatch('vimeotheque-series/items-store').updateOption( posts )
                dispatch('vimeotheque-series/items-selection').updateOption( posts )
            }

        }, [posts]
    )

    return (
        <>
            {
                isLoading &&
                <>
                    <Spinner /> {__('Loading the videos...', 'vimeotheque-series')}
                </>
            }
        </>
    )
}

VideoLoad.defaultProps = {
    item: false
}

export default VideoLoad