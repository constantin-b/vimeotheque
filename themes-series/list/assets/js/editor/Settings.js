const {
    components: {
        Button,
        ButtonGroup,
        Flex,
        FlexBlock,
        FlexItem,
        RadioControl,
    },
    data: {
        useSelect,
        dispatch,
    },
    element: {
        useEffect,
        useState
    },
    i18n: {
        __
    }
} = wp

const Settings = props => {

    const
        columns = useSelect( select => select('vimeotheque-series/playlist-options').getOption('columns') ),
        playback = useSelect( select => select('vimeotheque-series/playlist-options').getOption('playback') )



    const setColumns = cols => {
        dispatch( 'vimeotheque-series/playlist-options' ).updateOption( 'columns', cols )
        dispatch('core').editEntityRecord( 'postType', 'series', VSE.postId, { 'columns': cols })
    }

    const setOpener = opener => {
        dispatch( 'vimeotheque-series/playlist-options' ).updateOption( 'playback', opener )
        dispatch('core').editEntityRecord( 'postType', 'series', VSE.postId, { 'playback': opener })
    }

    return (
        <>
            <Flex>
                <FlexItem>
                    <label>{__('Columns', 'vimeotheque-series')} : </label>
                </FlexItem>
                <FlexBlock>
                    <ButtonGroup>
                        <Button
                            variant={1 == columns ? 'primary' : ''}
                            onClick={() => setColumns(1)}
                        >
                            1
                        </Button>
                        <Button
                            variant={2 == columns ? 'primary' : ''}
                            onClick={() => setColumns(2)}
                        >
                            2
                        </Button>
                        <Button
                            variant={3 == columns ? 'primary' : ''}
                            onClick={() => setColumns(3)}
                        >
                            3
                        </Button>
                        <Button
                            variant={4 == columns ? 'primary' : ''}
                            onClick={() => setColumns(4)}
                        >
                            4
                        </Button>
                        <Button
                            variant={5 == columns ? 'primary' : ''}
                            onClick={() => setColumns(5)}
                        >
                            5
                        </Button>
                    </ButtonGroup>
                </FlexBlock>
            </Flex>

            <Flex
                style={{marginTop: '15px'}}
                align='flex-start'
            >
                <FlexItem>
                    <label>{__('Playback', 'vimeotheque-series')} : </label>
                </FlexItem>
                <FlexBlock>
                    <RadioControl
                        selected={playback}
                        help={__('Clicking on the image will play the video in modal window or will open the post page.', 'codeflavors-vimeo-video-post-lite')}
                        options={[
                            {label: __('Modal', 'codeflavors-vimeo-video-post-lite'), value: 'modal'},
                            {label: __('Single Post', 'codeflavors-vimeo-video-post-lite'), value: 'post'},
                        ]}
                        onChange={ setOpener }
                    />
                </FlexBlock>
            </Flex>

        </>
    )
}

export default Settings