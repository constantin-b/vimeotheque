import ListControls from "../videos-list/ListControls";
import VideoList from "../videos-list/VideoList";
import ContentSelector from "./ContentSelector";
import ContentSelect from "./ContentSelect";
import Search from '../../../components/Search'

const {
    components: {
        Modal,
        SearchControl,
    },
    data: {
        useSelect,
        dispatch,
    },
    element: {
        useState,
        useEffect,
    },
    hooks: {
        applyFilters
    },
    i18n: {
        __
    }
} = wp

const Initialize = ({
    item = false
}) => {
    /**
     * Modal open.
     */
    const
        open = useSelect( select => select('vimeotheque-series/app-options').getOption( 'openContentModal' ) )

    const onCloseModal = () => {
        dispatch('vimeotheque-series/app-options').updateOption( 'openContentModal', !open )
    }

    const displayContent = useSelect( select => select('vimeotheque-series/playlist-options').getOption( 'contentType' ) )

    const selectedContent = applyFilters( 'vimeotheque-series-selected-content', '', displayContent )


    return (
        <>
            {
                displayContent &&
                    <ListControls
                        onAdd={ onCloseModal }
                        totalTime={true}
                    />
            }

            {
                'posts' == displayContent &&
                    <VideoList
                        item={item}
                    />
            }

            {
                'posts' != displayContent &&
                    selectedContent
            }

            {
                '' == displayContent &&
                    <ContentSelector />
            }

            {
                open &&
                    <Modal
                        className='vimeotheque-posts-list-modal'
                        title={ applyFilters( 'vimeotheque-series-content-modal-title', __( 'Add videos', 'codeflavors-vimeo-video-post-lite' ), displayContent ) }
                        onRequestClose={ onCloseModal }
                        isFullScreen={ applyFilters( 'vimeotheque-series-content-modal-fs', true, displayContent ) }
                        shouldCloseOnClickOutside={false}
                        shouldCloseOnEsc={true}
                        headerActions={
                            (
                                'posts' === displayContent &&
                                    <Search

                                    />
                            )
                        }
                    >
                        <ContentSelect />
                    </Modal>
            }
        </>
    )
}

export default Initialize