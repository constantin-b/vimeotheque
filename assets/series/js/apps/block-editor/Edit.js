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
        SearchControl,
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

    const {
        loadMore,
        open,
    } = useSelect(
        select => ({
            loadMore: select('vimeotheque-series/app-options').getOption( 'loadMore' ),
            open: select('vimeotheque-series/app-options').getOption( 'openContentModal' ),
        })
    )

    const EmptyResponse = () => {
        return (
            <>{__('No content found.', 'vimeotheque-series')}</>
        )
    }

    const toggleModal = () => {
        dispatch('vimeotheque-series/app-options').updateOption( 'openContentModal', !open )
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
                        instructions={__('Display Vimeotheque Series into the post.', 'vimeotheque-series')}
                        label={__('Vimeotheque Series', 'vimeotheque-series')}
                    >
                        <Button
                            isPrimary={true}
                            onClick={ toggleModal }
                        >
                            {__('Choose Series Playlist', 'vimeotheque-series')}
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
                        title={__('Vimeotheque Series', 'vimeotheque-series')}
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
                                }
                            }
                        />
                    </Modal>
            }

        </div>
    )
}

export default Edit