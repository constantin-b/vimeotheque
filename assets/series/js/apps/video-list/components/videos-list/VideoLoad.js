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

const VideoLoad = ({
   item = false
}) => {

    const {
        posts,
        isLoading
    } = useSelect(
        select => ({
            posts: select('core').getEntityRecords( 'postType', 'vimeo-video', {include: item.items, orderby: 'include'} ),
            isLoading: select('core/data').isResolving( 'core', 'getEntityRecords', ['postType', 'vimeo-video', {include: item.items, orderby: 'include'}] )
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
                    <Spinner /> {__('Loading the videos...', 'codeflavors-vimeo-video-post-lite')}
                </>
            }
        </>
    )
}

export default VideoLoad