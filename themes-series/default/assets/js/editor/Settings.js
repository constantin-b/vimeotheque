const {
    components: {
        Button,
        ButtonGroup
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

    const columns = useSelect( select => select('vimeotheque-series/playlist-options').getOption('columns') )

    const setColumns = cols => {
        dispatch( 'vimeotheque-series/playlist-options' ).updateOption( 'columns', cols )
        dispatch('core').editEntityRecord( 'postType', 'series', VSE.postId, { 'columns': cols })
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
        </>
    )
}

export default Settings