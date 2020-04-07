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
                        label={__('Saving video', 'cvm_video')}
                    >
                        {__('Your video post is being created, please wait...', 'cvm_video')}
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
                                        { __( 'Edit post', 'cvm_video' ) }
                                    </Button>
                                    <Button
                                        isSecondary
                                        href={props.response.viewLink}
                                    >
                                        { __( 'View post', 'cvm_video' ) }
                                    </Button>
                                    <Button
                                        isTertiary
                                        onClick={
                                            () => {
                                                props.onMessageClose()
                                            }
                                        }
                                    >
                                        { __( 'Import another video', 'cvm_video' ) }
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
    onMessageClose: () => {}
}

const VideoImporter = postCreateApplyWithSelect( VideoImporterBase )

export default VideoImporter