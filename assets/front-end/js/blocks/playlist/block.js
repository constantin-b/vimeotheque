import VideoPostsList from './components/VideoPostsList';

const 	{ registerBlockType } = wp.blocks,
    { __ } = wp.i18n,
    {
        Placeholder,
        Button,
        ButtonGroup,
        Modal,
        // editor
        Panel,
        PanelBody,
        PanelRow,
        SelectControl,
        TextControl,
        ToggleControl,
        Icon,
        Tooltip
    } = wp.components,
    {
        InspectorControls,
        BlockControls
    } = wp.blockEditor,
    { useState } = wp.element;

registerBlockType( 'vimeotheque/video-playlist', {
    title: __( 'Vimeotheque playlist', 'cvm_video' ),
    description: __( 'Video playlist block', 'cvm_video' ),
    icon: 'playlist-video',
    category: 'widgets',

    attributes: {

    },

    example: {

    },

    supports: {
        align: false,
        anchor: false,
        html: false,
        customClassName: false
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

        let opt = {
            width: 900,
            aspect_ratio: '16x9',
            title: true,
            byline: true,
            portrait: true,
            loop: false,
            volume: 70
        }

        return [
            <>
                {
                    posts.length > 0 &&
                    <BlockControls>
                        <div
                            className='components-toolbar'
                        >
                            <Tooltip
                                text={__('Edit playlist', 'cvm_video')}
                                position="top"
                            >
                                <Button
                                    onClick={ openModal }
                                >
                                    <Icon
                                        icon="edit"
                                    />
                                </Button>
                            </Tooltip>
                        </div>
                    </BlockControls>
                }
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
            </>,

            <InspectorControls>
                <PanelBody
                    title={ __( 'Theme', 'cvm_video' ) }
                >
                    <PanelRow>
                        <SelectControl
                            label = { __( 'Theme', 'cvm_video' ) }
                            value = 'default'
                            options = {[
                                { label: 'Default', value: 'default' },
                                { label: 'Carousel', value: 'carousel' },
                                { label: 'Wall', value: 'wall' },
                            ]}
                            onChange = {
                                ( value ) => {

                                }
                            }
                        />
                    </PanelRow>
                    <PanelRow>
                        <TextControl
                            label = { __( 'Width', 'cvm_video' ) }
                            type = "number"
                            step = "5"
                            value = { opt.width }
                            min = "200"
                            onChange = {
                                ( value ) => {

                                }
                            }
                        />
                    </PanelRow>
                    <PanelRow>
                        <SelectControl
                            label = { __( 'Aspect ratio', 'cvm_video' ) }
                            value = { opt.aspect_ratio }
                            options = {[
                                { label: '4x3', value: '4x3' },
                                { label: '16x9', value: '16x9' },
                                { label: '2.35x1', value: '2.35x1' },
                            ]}
                            onChange = {
                                ( value ) => {

                                }
                            }
                        />
                    </PanelRow>
                </PanelBody>

                <PanelBody
                    title = { __('Embed options', 'cvm_video') }
                    initialOpen = {true}
                >
                    <PanelRow>
                        <ToggleControl
                            label = { __( 'Show title', 'cvm_video' ) }
                            checked = {opt.title}
                            onChange = {
                                () => {  }
                            }
                        />
                    </PanelRow>
                    <PanelRow>
                        <ToggleControl
                            label = { __( 'Show byline', 'cvm_video' ) }
                            checked = {opt.byline}
                            onChange = {
                                () => {  }
                            }
                        />
                    </PanelRow>
                    <PanelRow>
                        <ToggleControl
                            label = { __( 'Show portrait', 'cvm_video' ) }
                            checked = {opt.portrait}
                            onChange = {
                                () => {  }
                            }
                        />
                    </PanelRow>
                    <PanelRow>
                        <ToggleControl
                            label = { __( 'Loop playlis', 'cvm_video' ) }
                            checked = {opt.loop}
                            onChange =  {
                                () => { onFormToggleChange( 'loop' ) }
                            }
                        />
                    </PanelRow>
                    <PanelRow>
                        <TextControl
                            label = { __( 'Volume', 'cvm_video' ) }
                            help = { __( 'Will work only for JS embeds', 'cvm_video' ) }
                            type = "number"
                            step = "1"
                            value = { opt.volume }
                            min = "0"
                            max = "100"
                            onChange = {
                                ( value ) => {
                                    opt.volume = ( value >= 0 && value <= 100 ) ? value : opt.volume;
                                    setAttributes({
                                        embed_options: JSON.stringify( opt )
                                    })
                                }
                            }
                        />
                    </PanelRow>
                </PanelBody>

            </InspectorControls>
        ]
    },

    save: ( props ) => {
        return null;
    },

} );

