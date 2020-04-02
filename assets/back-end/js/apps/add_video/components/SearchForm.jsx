const {
        useState
    } = wp.element,
    { __ } = wp.i18n,
    {
        TextControl,
        Button,
        Spinner
    } = wp.components

const SearchForm = ( props ) => {

    const [query, setQuery] = useState( '' )

    return (
        <div className='vimeotheque-search-form'>
            <TextControl
                label= { `${__( 'Insert video URL or search query', 'cvm_video' )} : ` }
                help = { __( '', 'cvm_video' ) }
                value = { query }
                onChange = {
                    value => {
                        setQuery( value )
                    }
                }
            />
            <Button
                isPrimary
                onClick={
                    () => {
                        props.onSubmit( query )
                    }
                }
            >
                { __( 'Search', 'cvm_video' ) }
            </Button>
        </div>
    )
}

SearchForm.defaultProps = {
    onSubmit: () => {}
}

export default SearchForm