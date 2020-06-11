const {
        Icon,
        Button,
        ButtonGroup
    } = wp.components,
    {applyFilters} = wp.hooks,
    { __ } = wp.i18n

const Video = ( props ) => {
    /**
     * Filter that allows extra parameters to be set.
     * Used in conjunction with filter "vimeotheque.single-import.import-actions"
     */
    let params = applyFilters( 'vimeotheque.single-import.params', {} )

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
                <Button
                    isPrimary
                    onClick={
                        ()=>{
                            props.onClick( props.data, params )
                        }
                    }
                >
                    { __( 'Import video', 'codeflavors-vimeo-video-post-lite' ) }
                </Button>
                <Button
                    isSecondary
                    onClick={props.onCancel}
                >
                    { __( 'Cancel', 'codeflavors-vimeo-video-post-lite' ) }
                </Button>
                {
                    /**
                     * Filter that allow insert of additional edit elements.
                     * IE: it is used to inject the theme import checkbox
                     */
                    applyFilters('vimeotheque.single-import.import-actions')
                }
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