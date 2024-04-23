import Loader from "./Loader"
import ContentSelect from "./ContentSelect"
import Search from '../components/Search'
import '../video-list/store/appOptionsStore'

import {forEach} from 'lodash'

const {
    serverSideRender: ServerSideRender,
    blockEditor: {
        BlockControls,
        useBlockProps,
    },
    components: {
        Button,
        Modal,
        Placeholder,
        Tooltip,
        ToolbarGroup,
        ToolbarItem,
    },
    data: {
        dispatch,
        useSelect,
    },
    element: {
        useEffect,
        useState,
    },
    i18n:{
        __
    }
} = wp


const Edit = ( props ) => {

    const {
        attributes: {
            playlist
        },
        setAttributes,
        className
    } = props

    const [open, setOpen] = useState(false)

    const EmptyResponse = () => {
        return (
            <>{__('No content found.', 'codeflavors-vimeo-video-post-lite')}</>
        )
    }

    const toggleModal = () => {
        setOpen(!open)
    }

    return (
        <div
            key='vimeotheque-series-playlist-block'
            { ...useBlockProps() }
        >
            <BlockControls>
                <ToolbarGroup>
                    <Tooltip
                        text={__('Edit playlist', 'codeflavors-vimeo-video-post-lite')}
                        placement="top"
                    >
                        <ToolbarItem
                            as={ Button }
                            onClick={ toggleModal }
                            icon='edit'
                        />
                    </Tooltip>
                </ToolbarGroup>
            </BlockControls>

            {
                playlist > 0 &&
                    <ServerSideRender
                        block="vimeotheque-series/series"
                        attributes={
                            {
                                playlist: playlist
                            }
                        }
                        LoadingResponsePlaceholder={
                            () => {
                                return (
                                    <Loader
                                        onComplete={
                                            () => {
                                                forEach( window.vimeotheque.series, fn => fn() )
                                            }
                                        }
                                    />
                                )
                            }
                        }
                        EmptyResponsePlaceholder={EmptyResponse}
                    />
            }

            {
                0 == playlist &&
                    <Placeholder
                        instructions={__('Display Vimeotheque Series into the post.', 'codeflavors-vimeo-video-post-lite')}
                        label={__('Vimeotheque Series', 'codeflavors-vimeo-video-post-lite')}
                    >
                        <Button
                            isPrimary={true}
                            onClick={ toggleModal }
                        >
                            {__('Choose Series Playlist', 'codeflavors-vimeo-video-post-lite')}
                        </Button>
                    </Placeholder>
            }

            {
                open &&
                    <Modal
                        className='vimeotheque-posts-list-modal'
                        isFullScreen={true}
                        onRequestClose={ toggleModal }
                        shouldCloseOnClickOutside={false}
                        shouldCloseOnEsc={true}
                        title={__('Vimeotheque Series', 'codeflavors-vimeo-video-post-lite')}
                        headerActions={
                            (
                                <Search />
                            )
                        }
                    >
                        <ContentSelect
                            onSelect={
                                item => {
                                    setAttributes({playlist: item.id})
                                    toggleModal()
                                }
                            }
                        />
                    </Modal>
            }

        </div>
    )
}

export default Edit