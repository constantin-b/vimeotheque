import PlaybackTime from "./PlaybackTime";
import Reset from "../content-select/Reset";
import {getEditedPostId} from "../../../inc/functions";
import ButtonSave from "../../../components/ButtonSave";

const {
    components: {
        Button,
        Flex,
        FlexItem,
        Spinner,
    },
    data:{
        dispatch,
        useSelect,
    },
    element: {
        useEffect,
        useState,
    },
    hooks: {
        applyFilters
    },
    i18n: {
        __
    }
} = wp

const ListControls = props => {

    const
        shuffle = useSelect( select => select('vimeotheque-series/playlist-options').getOption( 'shuffle' ) ),
        displayContent = useSelect( select => select('vimeotheque-series/playlist-options').getOption( 'contentType' ) )

    const shuffleLabel = () => {
        return shuffle ?
            __('Disable shuffle', 'codeflavors-vimeo-video-post-lite'):
            __('Enable shuffle for the playlist', 'codeflavors-vimeo-video-post-lite')
    }

    return (
        <div
            className="playlist-controls"
        >
			<Flex>
                <FlexItem
                	isBlock={true}
                >
                    {
                        props.canAdd &&
                        	<Button
								icon='plus'
								onClick={ props.onAdd }
                                isSecondary={true}
                            >
                                {
                                    applyFilters(
                                        'vimeotheque-series-button-add-content-text',
                                        __( 'Add videos', 'codeflavors-vimeo-video-post-lite' ),
                                        displayContent
                                    )
                                }
                            </Button>
                    }

                    {
                        displayContent &&
                            <Reset />
                    }
                </FlexItem>
                <FlexItem>
                    {
                        props.totalTime && 'posts' == displayContent &&
                            <PlaybackTime />
                    }
                </FlexItem>
                <FlexItem>
                    {
                        props.canShuffle && 'posts' == displayContent &&
                            <Button
                                onClick={
                                    () => {
                                        dispatch('core').editEntityRecord( 'postType', 'series', getEditedPostId(), { 'shuffle': !shuffle })

                                        dispatch('vimeotheque-series/playlist-options').updateOption('shuffle', !shuffle)
                                    }
                                }
                                icon='randomize'
                                isSecondary={true}
                                className={`action-shuffle ${shuffle ? 'active' : 'inactive'}`}
                                showTooltip={ true }
                                tooltipPosition='top'
                                label={ shuffleLabel() }
                            />
                    }
                </FlexItem>
            </Flex>
        </div>
    )
}

ListControls.defaultProps = {
    canAdd: true,
    canShuffle: true,
    totalTime: false,
    onAdd: () => {}
}

export default ListControls