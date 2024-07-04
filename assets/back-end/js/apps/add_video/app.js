import SearchForm from "./components/SearchForm"
import VideoQuery from "./components/VideoQuery"
import VideoImporter from "./components/VideoImporter";

const {
    element: {
        createRoot,
        useState,
    }
} = wp

const VideoImportApp  = () => {

    const [query, setQuery] = useState( '' )
    const [video, setVideo] = useState( false )
    const [params, setParams] = useState( {} )


    return (
        <div className='vimeotheque-video-import-app' key='vimeotheque-single-video-import-app'>
            {
                video &&
                    <VideoImporter
                        video={video}
                        params={params}
                        onMessageClose={
                            () => {
                                setVideo(false)
                                setParams({})
                                setQuery('')
                            }
                        }
                    />
            }
            {
                !video && query &&
                <VideoQuery
                    query={query}
                    onSubmit={
                        ( video, params ) => {
                            setVideo( video )
                            setParams( params )
                        }
                    }
                    onCancel={
                        () => {
                            setQuery('')
                        }
                    }
                />
            }
            {
                 !video && !query &&
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

const root = createRoot( document.getElementById( 'vimeotheque-import-video' ) )
root.render( <VideoImportApp /> )