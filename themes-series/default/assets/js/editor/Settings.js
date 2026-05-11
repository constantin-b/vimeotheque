const {
    components: {
        Button,
        ButtonGroup,
        Flex,
        FlexBlock,
        ToggleControl,
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
        columns     = useSelect( select => select('vimeotheque-series/playlist-options').getOption('columns') ),
        showTitle   = useSelect( select => select('vimeotheque-series/playlist-options').getOption('show_title') ),
        showContent = useSelect( select => select('vimeotheque-series/playlist-options').getOption('show_content') )

    const setColumns = cols => {
        dispatch( 'vimeotheque-series/playlist-options' ).updateOption( 'columns', cols )
        dispatch('core').editEntityRecord( 'postType', 'series', VSE.postId, { 'columns': cols })
    }

    const setShowTitle = value => {
        const val = value ? 'yes' : 'no'
        dispatch( 'vimeotheque-series/playlist-options' ).updateOption( 'show_title', val )
        dispatch('core').editEntityRecord( 'postType', 'series', VSE.postId, { 'show_title': val })
    }

    const setShowContent = value => {
        const val = value ? 'yes' : 'no'
        dispatch( 'vimeotheque-series/playlist-options' ).updateOption( 'show_content', val )
        dispatch('core').editEntityRecord( 'postType', 'series', VSE.postId, { 'show_content': val })
    }

    return (
        <>
            <label>{__('Columns', 'vimeotheque-series')} : </label>
        	<ButtonGroup>
                <Button
                    variant={ 1 == columns ? 'primary' : '' }
                    onClick={ () => setColumns(1) }
                >
                    1
                </Button>
                <Button
                    variant={ 2 == columns ? 'primary' : '' }
                    onClick={ () => setColumns(2) }
                >
                    2
                </Button>
                <Button
                    variant={ 3 == columns ? 'primary' : '' }
                    onClick={ () => setColumns(3) }
                >
                    3
                </Button>
                <Button
                    variant={ 4 == columns ? 'primary' : '' }
                    onClick={ () => setColumns(4) }
                >
                    4
                </Button>
                <Button
                    variant={ 5 == columns ? 'primary' : '' }
                    onClick={ () => setColumns(5) }
                >
                    5
                </Button>
            </ButtonGroup>

            <Flex style={{marginTop: '15px'}} align='flex-start'>
                <FlexBlock>
                    <ToggleControl
                        label={__('Show title', 'codeflavors-vimeo-video-post-lite')}
                        help={__('Display the post title below the featured video.', 'codeflavors-vimeo-video-post-lite')}
                        checked={ 'yes' === showTitle }
                        onChange={ setShowTitle }
                    />
                </FlexBlock>
            </Flex>

            <Flex style={{marginTop: '5px'}} align='flex-start'>
                <FlexBlock>
                    <ToggleControl
                        label={__('Show content', 'codeflavors-vimeo-video-post-lite')}
                        help={__('Display the post content below the featured video, collapsed with a "See more" button.', 'codeflavors-vimeo-video-post-lite')}
                        checked={ 'yes' === showContent }
                        onChange={ setShowContent }
                    />
                </FlexBlock>
            </Flex>
        </>
    )
}

export default Settings