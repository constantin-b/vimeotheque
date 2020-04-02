const {
        Icon,
        Button,
        ButtonGroup
    } = wp.components,
    { __ } = wp.i18n

const Video = ( props ) => {

    return (
        <div className='vimeotheque-video'>
            <div className='image'>
                <img
                    src={ props.data.thumbnails[2] }
                    alt={ props.data.title }
                />
                <span className='duration'>
                    {props.data._duration}
                </span>
                {
                    ( 'public' != props.data.privacy ) &&
                    <Icon
                        icon='lock'
                    />
                }
            </div>
            <div className='video'>
                <h1>{ props.data.title }</h1>
                <div className='meta'>
                    <span className='published'>
                        {props.data._published}
                    </span>
                    <span className='by'>
                        {`by ${props.data.uploader}`}
                    </span>
                </div>
                <ButtonGroup>
                    <Button
                        isPrimary
                        onClick={
                            ()=>{
                                props.onClick( props.data )
                            }
                        }
                    >
                        { __( 'Import video', 'cvm_video' ) }
                    </Button>
                    <Button
                        onClick={props.onCancel}
                    >
                        { __( 'Cancel', 'cvm_video' ) }
                    </Button>
                </ButtonGroup>
            </div>
        </div>
    )
}

Video.defaultProps = {
    data: {},
    onClick: ()=>{},
    onCancel: ()=>{}
}

export default Video