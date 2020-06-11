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
                label= { `${__( 'Insert Vimeo video URL or video ID', 'codeflavors-vimeo-video-post-lite' )} : ` }
                help = { __( '', 'codeflavors-vimeo-video-post-lite' ) }
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
                { __( 'Search', 'codeflavors-vimeo-video-post-lite' ) }
            </Button>
        </div>
    )
}

SearchForm.defaultProps = {
    onSubmit: () => {}
}

export default SearchForm