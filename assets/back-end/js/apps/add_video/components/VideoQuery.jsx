import {videoQueryApplyWithSelect} from "../query/videoQueryApplyWithSelect";
import Video from "./Video";

const
    {
        useState,
        useEffect
    } = wp.element,
    {
        Spinner,
        Notice,
        Placeholder
    } = wp.components,
    { __ } = wp.i18n

const VideoQueryBase = ( props ) => {

    const [showNotice, setShowNotice] = useState( true )

    useEffect( () => {
        if( !showNotice ){
            setShowNotice(true)
        }
    }, [props.loading] )

    return (
        <>
            {
                props.loading ?
                    <Placeholder className='loading'>
                        {__('Loading, please wait...', 'cvm_video')}
                        <Spinner/>
                    </Placeholder>
                    :
                    <div>
                        {
                            props.error ?
                                showNotice &&
                                    <Notice
                                        status='error'
                                        onRemove={
                                            () => {
                                                props.onCancel()
                                                setShowNotice( false )
                                            }
                                        }
                                    >
                                        {props.error.message}
                                    </Notice>
                                :
                                <Video
                                    data={props.video}
                                    onClick={props.onSubmit}
                                    onCancel={props.onCancel}
                                />
                        }
                    </div>
            }
        </>
    )
}

VideoQueryBase.defaultProps = {
    loading: false,
    video: false,
    error: false,
    query: '',
    onSubmit: () => {}
}

const VideoQuery = videoQueryApplyWithSelect( VideoQueryBase )

export default VideoQuery