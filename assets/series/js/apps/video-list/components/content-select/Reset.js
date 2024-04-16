const {
    components: {
        Button,
        Flex,
        FlexItem,
        Modal,
    },
    data: {
        dispatch,
    },
    element: {
        useEffect,
        useState,
    },
    hooks: {
        doAction,
    },
    i18n: {
        __
    }
} = wp

const Reset = props => {

    const [showConfirm, setShowConfirm] = useState(false)

    return (
        <>
            <Button
                className='reset-playlist-content'
                icon='undo'
                onClick={ () =>  setShowConfirm( true ) }
                showTooltip={ true }
                tooltipPosition='top'
                label={ __('Reset content settings', 'vimeotheque-series') }
            />

            {
                showConfirm &&
                    <Modal
                        title={__( 'Reset playlist content', 'vimeotheque-series' )}
                        onRequestClose={ () => setShowConfirm(false) }
                        isFullScreen={false}
                        shouldCloseOnClickOutside={true}
                        shouldCloseOnEsc={true}
                    >
                        <p>{ __('This action will reset all content settings and will allow you to start over with new settings.', 'vimeotheque-series') }</p>
                        <Flex
                            justify='flex-end'
                        >
                            <FlexItem>
                                <Button
                                    isSecondary={true}
                                    onClick={ () => setShowConfirm(false) }
                                >
                                    {__('Cancel', 'vimeotheque-series')}
                                </Button>
                            </FlexItem>
                            <FlexItem>
                                <Button
                                    isPrimary={true}
                                    onClick={
                                        () => {
                                            dispatch('vimeotheque-series/app-options').resetOption( 'currentPage' )
                                            dispatch('vimeotheque-series/app-options').resetOption( 'loadMore' )
                                            dispatch('vimeotheque-series/items-store').updateOption([])
                                            dispatch('vimeotheque-series/items-selection').updateOption([])
                                            dispatch('vimeotheque-series/items-loaded').reset()
                                            dispatch('vimeotheque-series/playlist-options').reset()

                                            doAction('vimeotheque-series-reset-content-options')
                                        }
                                    }
                                >
                                    {__('Proceed', 'vimeotheque-series')}
                                </Button>
                            </FlexItem>
                        </Flex>
                    </Modal>
            }

        </>
    )
}

Reset.defaultProps = {}

export default Reset