import VideoPostsList from './components/VideoPostsList';

const 	{ registerBlockType } = wp.blocks,
    { __ } = wp.i18n,
    {
        Placeholder,
        Button,
        ButtonGroup,
        Modal
    } = wp.components,
    {
        useState
    } = wp.element;

registerBlockType( 'vimeotheque/video-playlist', {
    title: __( 'Vimeotheque playlist', 'cvm_video' ),
    description: __( 'Video playlist block', 'cvm_video' ),
    icon: 'playlist-video',
    category: 'widgets',

    attributes: {

    },

    example: {

    },

    edit: ( props ) => {

        const [isOpen, setOpen] = useState( false ),
            openModal = (e) => {
                e.stopPropagation()
                setOpen( true )
            },
            closeModal = () => { setOpen( false ) };


        const [posts, setPosts] = useState( [] ),
            selectPost = ( post ) => {
                setPosts( [...posts, post ] )
            },
            unselectPost = ( post ) => {
                for( var i = posts.length-1; i >= 0; i--){
                    if( posts[i].id == post.id ){
                        posts.splice( i, 1 )
                        setPosts( [...posts] )
                        break
                    }
                }
            }

        return [
            <Placeholder
                icon = "playlist-video"
                label = { __( 'Video playlist', 'cvm_video' ) }
            >
                <Button
                    isPrimary
                    onClick={ openModal }>
                    { __(' Choose posts', 'cvm_video' ) }
                </Button>
                {
                    isOpen && (
                        <Modal
                            title={ __( 'Choose posts', 'cvm_video' ) }
                            onRequestClose = { closeModal }
                            className = 'vimeotheque-posts-list-modal'
                        >
                            <div className="wrapper">
                                <VideoPostsList
                                    onSelect = { selectPost }
                                    onRemove = { unselectPost }
                                    filteredPosts = { posts }
                                />
                                <nav className="sidebar">
                                    <Button
                                        isPrimary
                                        onClick={
                                            ()=>{

                                            }
                                        }
                                    >
                                        {__('Insert playlist', 'cvm_video')}
                                    </Button>
                                </nav>
                            </div>
                        </Modal>
                    )
                }
            </Placeholder>
        ]
    },

    save: ( props ) => {
        return null;
    },

} );

