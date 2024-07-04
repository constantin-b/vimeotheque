const {
    components: {
        TextControl,
        Button,
    },
    element: {
        useState,
    },
    i18n: {
        __,
    }
} = wp

const SearchForm = ({
    onSubmit = () => {}
}) => {

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
                        onSubmit( query )
                    }
                }
            >
                { __( 'Search', 'codeflavors-vimeo-video-post-lite' ) }
            </Button>
        </div>
    )
}

export default SearchForm