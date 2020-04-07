import SearchForm from "./components/SearchForm"
import VideoQuery from "./components/VideoQuery"
import VideoImporter from "./components/VideoImporter";

const {
        render,
        useState
    } = wp.element,
    { Placeholder, Spinner } = wp.components

const VideoImportApp  = ( props ) => {

    const [query, setQuery] = useState( '' )
    const [video, setVideo] = useState( false )

    return (
        <div className='vimeotheque-video-import-app'>
            {
                video &&
                    <VideoImporter
                        video={video}
                        onMessageClose={
                            () => {
                                setVideo(false)
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
                        video => {
                            setVideo(video)
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

render(
    <VideoImportApp />,
    document.getElementById('vimeotheque-import-video')
)