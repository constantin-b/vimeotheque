import {videoQueryApplyWithSelect} from "../query/videoQueryApplyWithSelect";
import Video from "./Video";

const {
    components: {
        Spinner,
        Notice,
        Placeholder,
    },
    element: {
        useState,
        useEffect,
    },
    i18n: {
        __
    }
} = wp

const VideoQueryBase = ({
    loading = false,
    response = false,
    error = false,
    query = '',
    onSubmit = () => {},
    onCancel = () => {},
}) => {

    const [showNotice, setShowNotice] = useState( true )

    useEffect( () => {
        if( !showNotice ){
            setShowNotice(true)
        }
    }, [loading] )

    return (
        <>
            {
                loading ?
                    <Placeholder
                        className='loading'
                        label={__('Making query to Vimeo', 'codeflavors-vimeo-video-post-lite')}
                    >
                        {__('Please wait...', 'codeflavors-vimeo-video-post-lite')}
                        <Spinner/>
                    </Placeholder>
                    :
                    <div>
                        {
                            error ?
                                showNotice &&
                                    <Notice
                                        status='error'
                                        onRemove={
                                            () => {
                                                onCancel()
                                                setShowNotice( false )
                                            }
                                        }
                                    >
                                        {error.message}
                                    </Notice>
                                :
                                <Video
                                    data={response}
                                    onClick={onSubmit}
                                    onCancel={onCancel}
                                />
                        }
                    </div>
            }
        </>
    )
}

const VideoQuery = videoQueryApplyWithSelect( VideoQueryBase )

export default VideoQuery