import '../video-list/store/appOptionsStore'
import '../video-list/store/itemsLoaded'

const {
    components: {
        SearchControl
    },
    data: {
        dispatch,
        useSelect,
    },
    element: {
        useEffect,
        useState
    },
    i18n: {
        __
    }
} = wp

const Search = props => {

    const
        [search, setSearch] = useState( '' ),
        savedSearch = useSelect( select => select('vimeotheque-series/app-options').getOption('search') )

    useEffect(() => {
        if( savedSearch !== '' && search === '' ){
            doSearch( '' )
        }

    }, [search])

    const doSearch = src => {
        dispatch('vimeotheque-series/app-options').updateOption( 'search', src )
        // Reset the current page
        dispatch('vimeotheque-series/app-options').updateOption( 'currentPage', 1 )
        // Reset the loaded items
        dispatch('vimeotheque-series/items-loaded').reset()
    }

    return (
        <SearchControl
            value={search}
            onChange={setSearch}
            placeholder={__('Type and hit Enter', 'vimeotheque-series')}
            onKeyPress={
                event => {
                    if( event.key === 'Enter' ){
                        doSearch( search )
                    }
                }
            }
        />
    )
}

Search.defaultProps = {}

export default Search