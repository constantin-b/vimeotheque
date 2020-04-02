import SearchForm from "./components/SearchForm"
import VideoQuery from "./components/VideoQuery"

const {
        render,
        useState
    } = wp.element,
    { Placeholder } = wp.components

const VideoImportApp  = ( props ) => {

    const [query, setQuery] = useState( '' )

    return (
        <div className='vimeotheque-video-import-app'>
            {
                query ?
                    <VideoQuery
                        query={query}
                        onSubmit={
                            video => {

                            }
                        }
                        onCancel={
                            ()=>{
                                setQuery('')
                            }
                        }
                    />
                    :
                    <SearchForm
                        onSubmit={
                            ( query ) => {
                                setQuery( query )
                            }
                        }
                    />

            }
        </div>
    )
}

render(
    <VideoImportApp />,
    document.getElementById('vimeotheque-import-video')
)