const {
    components: {
        Icon,
        Button,
    },
    hooks: {
        applyFilters,
    },
    i18n: {
        __,
    }
} = wp

const Video = ({
    data = {},
    onClick = ()=>{},
    onCancel = ()=>{}
}) => {
    /**
     * Filter that allows extra parameters to be set.
     * Used in conjunction with filter "vimeotheque.single-import.import-actions"
     */
    let params = applyFilters( 'vimeotheque.single-import.params', {} )

    return (
        <div className='vimeotheque-video'>
            <div className='image'>
                <img
                    src={ data.thumbnails[2] }
                    alt={ data.title }
                />
                <span className='duration'>
                    {data._duration}
                </span>
                {
                    ( 'public' != data.privacy ) &&
                    <Icon
                        icon='lock'
                    />
                }
            </div>
            <div className='video'>
                <h1>{ data.title }</h1>
                <div className='meta'>
                    <span className='published'>
                        {data._published}
                    </span>
                    <span className='by'>
                        {`by ${data.uploader}`}
                    </span>
                </div>
                <Button
                    isPrimary
                    onClick={
                        ()=>{
                            onClick( data, params )
                        }
                    }
                >
                    { __( 'Import video', 'codeflavors-vimeo-video-post-lite' ) }
                </Button>
                <Button
                    isSecondary
                    onClick={onCancel}
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

export default Video