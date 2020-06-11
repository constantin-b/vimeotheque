import {postCreateApplyWithSelect} from "../query/videoQueryApplyWithSelect";

const {
        Spinner,
        Notice,
        Placeholder,
        Button,
        ButtonGroup
    } = wp.components,
    { __ } = wp.i18n

const VideoImporterBase = props => {

    return (
        <>
            {
                props.loading ?
                    <Placeholder
                        className='loading'
                        label={__('Saving video', 'codeflavors-vimeo-video-post-lite')}
                    >
                        {__('Your video post is being created, please wait...', 'codeflavors-vimeo-video-post-lite')}
                        <Spinner/>
                    </Placeholder>
                    :
                    <div>
                        {
                            props.error ?
                                <Notice
                                    status='error'
                                    onRemove={
                                        () => {
                                            props.onMessageClose()
                                        }
                                    }
                                >
                                    {props.error.message}
                                </Notice>
                                :
                                <Placeholder
                                    label={ props.response.message }
                                >
                                    <Button
                                        isPrimary
                                        href={props.response.editLink}
                                    >
                                        { __( 'Edit post', 'codeflavors-vimeo-video-post-lite' ) }
                                    </Button>
                                    <Button
                                        isSecondary
                                        href={props.response.viewLink}
                                    >
                                        { __( 'View post', 'codeflavors-vimeo-video-post-lite' ) }
                                    </Button>
                                    <Button
                                        isTertiary
                                        onClick={
                                            () => {
                                                props.onMessageClose()
                                            }
                                        }
                                    >
                                        { __( 'Import another video', 'codeflavors-vimeo-video-post-lite' ) }
                                    </Button>
                                </Placeholder>
                        }
                    </div>
            }
        </>
    )
}

VideoImporterBase.defaultProps = {
    video: {},
    loading: false,
    response: false,
    error: false,
    onMessageClose: () => {}
}

const VideoImporter = postCreateApplyWithSelect( VideoImporterBase )

export default VideoImporter