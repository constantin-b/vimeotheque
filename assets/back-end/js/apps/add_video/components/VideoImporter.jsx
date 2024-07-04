import {postCreateApplyWithSelect} from "../query/videoQueryApplyWithSelect";

const {
    components: {
        Spinner,
        Notice,
        Placeholder,
        Button,
    },
    i18n: {
        __,
    }
} = wp

const VideoImporterBase = ({
   video = {},
   loading = false,
   response = false,
   error = false,
   onMessageClose = () => {}
}) => {

    return (
        <>
            {
                loading ?
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
                            error ?
                                <Notice
                                    status='error'
                                    onRemove={onMessageClose}
                                >
                                    {error.message}
                                </Notice>
                                :
                                <Placeholder
                                    label={ response.message }
                                >
                                    <Button
                                        isPrimary
                                        href={response.editLink}
                                    >
                                        { __( 'Edit post', 'codeflavors-vimeo-video-post-lite' ) }
                                    </Button>
                                    <Button
                                        isSecondary
                                        href={response.viewLink}
                                    >
                                        { __( 'View post', 'codeflavors-vimeo-video-post-lite' ) }
                                    </Button>
                                    <Button
                                        isTertiary
                                        onClick={onMessageClose}
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

const VideoImporter = postCreateApplyWithSelect( VideoImporterBase )

export default VideoImporter