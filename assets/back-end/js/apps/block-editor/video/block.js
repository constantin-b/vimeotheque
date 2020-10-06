import VideoPostsList from "../playlist/components/VideoPostsList";
import SearchForm from "../playlist/components/SearchForm";
import ListMenu from "../playlist/components/ListMenu";
import ServerSideEmbed from "../playlist/components/ServerSideEmbed";

const { registerBlockType } = wp.blocks,
    { __ } = wp.i18n,
    {
        Button,
        Placeholder,
        Modal,
        Icon,
        Tooltip,
        Panel,
        PanelBody,
        PanelRow,
        Dropdown,
        TextControl,
        SelectControl,
        ToggleControl
    } = wp.components,
    {
        InspectorControls,
        BlockControls
    } = wp.blockEditor,
    { useState } = wp.element

registerBlockType( 'vimeotheque/video', {
    title: __( 'Vimeotheque video', 'codeflavors-vimeo-video-post-lite' ),
    description: __( 'Display embed of a video post', 'codeflavors-vimeo-video-post-lite' ),
    icon: 'media-video',
    category: 'embed',
    example: {

    },

    supports: {
        align: false,
        anchor: false,
        html: false
    },

    edit: props => {
        const {
                attributes,
                setAttributes,
                className
            } = props,
            // modal window state
            [isLoaded, setLoaded] = useState( false ),
            [isOpen, setOpen] = useState( false ),
            [isRequestLoading, setRequestLoading] = useState( false ),
            [search, setSearch] = useState( { query: '', category: false } ),
            [taxonomy, setTaxonomy] = useState( 'vimeo-videos' ),
            [postType, setPostType] = useState( 'vimeo-video' ),
            [videos, setVideos] = useState( [] ),
            openModal = e => {
                e.stopPropagation()
                setOpen( true )
            },
            closeModal = () => setOpen( false ),
            // posts selection
            selectPost = post => {
                setAttributes({
                    id: post.id,
                    post: [post]
                })
                setVideos( [post] )
            },
            unselectPost = post => {
                setAttributes({
                    id: 0,
                    post: []
                })
                setVideos([])
            }

        if( !isLoaded ){
            if( attributes.id ){
                setVideos( attributes.post )
            }
            setLoaded( true );
        }

        return [
            <div key='vimeotheque-video-embed-block'>
                {
                    attributes.id ?
                        <>
                            <BlockControls>
                                <div
                                    className='components-toolbar'
                                >
                                    <Tooltip
                                        text={__('Change video', 'codeflavors-vimeo-video-post-lite')}
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
                            <ServerSideEmbed
                                block="vimeotheque/video"
                                attributes={
                                    {
                                        id: attributes.id,
                                        volume: attributes.volume,
                                        width: attributes.width,
                                        aspect_ratio: attributes.aspect_ratio,
                                        loop: attributes.loop,
                                        autoplay: attributes.autoplay,
                                        video_align: attributes.video_align
                                    }
                                }
                                onComplete = { ()=>{
                                    setTimeout( () => {
                                        jQuery('div.vimeotheque-player').VimeoPlayer()
                                    }, 200 )

                                }}
                                isSelected={props.isSelected}
                            />
                        </>
                        :
                        <Placeholder
                            icon="media-video"
                            label={__('Embed a video post', 'codeflavors-vimeo-video-post-lite')}
                        >
                            <Button
                                isPrimary
                                onClick={ openModal }
                            >
                                {__('Choose video post', 'codeflavors-vimeo-video-post-lite')}
                            </Button>
                        </Placeholder>
                }

                {
                    isOpen && (
                        <Modal
                            title={ __( 'Choose post', 'codeflavors-vimeo-video-post-lite' ) }
                            onRequestClose = { closeModal }
                            className = 'vimeotheque-posts-list-modal'
                        >
                            <div className="wrapper">
                                <div className="vimeotheque-post-list-container">
                                    <ListMenu
                                        postType={postType}
                                        disabled={isRequestLoading}
                                        hideSelected={ 0 == attributes.id }
                                        onPostTypeChange={
                                            ( postType ) => {
                                                setSearch({ query:'', category:false })
                                                setPostType( postType )

                                                let tax = 'selected' != postType
                                                if( tax ){
                                                    tax = vmtq.postTypes[ postType ].taxonomy ? vmtq.postTypes[ postType ].taxonomy.name : false
                                                }
                                                setTaxonomy( tax )
                                            }
                                        }
                                    />
                                    <VideoPostsList
                                        onSelect = { selectPost }
                                        onRemove = { unselectPost }
                                        filteredPosts = { videos }
                                        search={ search }
                                        postType={postType}
                                        taxonomy={taxonomy}
                                        onRequestBegin = {
                                            () => {
                                                setRequestLoading(true)
                                            }
                                        }
                                        onRequestFinish = {
                                            () => {
                                                setRequestLoading(false)
                                            }
                                        }
                                        onRequestError = {
                                            () => {
                                                setRequestLoading(false)
                                            }
                                        }
                                    />
                                </div>
                                {
                                    'selected' != postType &&
                                    <nav className="sidebar">
                                        <SearchForm
                                            blocked = { isRequestLoading }
                                            taxonomy={ taxonomy }
                                            values={ search }
                                            onSubmit = {
                                                value => setSearch( value )
                                            }
                                        />
                                    </nav>
                                }
                            </div>
                        </Modal>
                    )
                }
            </div>,

            /*
			 * InspectorControls
			 */
            <InspectorControls key="vimeotheque-video-embed-controls">

                <PanelBody
                    title = { __('Embed options', 'codeflavors-vimeo-video-post-lite') }
                    initialOpen = {true}
                >
                    <PanelRow>
                        <ToggleControl
                            label = { __( 'Loop video', 'codeflavors-vimeo-video-post-lite' ) }
                            checked = {attributes.loop}
                            onChange =  {
                                () => { () => {
                                    setAttributes({
                                        loop: !attributes.loop
                                    })
                                } }
                            }
                        />
                    </PanelRow>
                    <PanelRow>
                        <ToggleControl
                            label = { __( 'Autoplay video', 'codeflavors-vimeo-video-post-lite' ) }
                            help = { __( "This feature won't work on all browsers.", 'codeflavors-vimeo-video-post-lite' ) }
                            checked = {attributes.autoplay}
                            onChange =  {
                                () => { ()=>{
                                    setAttributes({
                                        autoplay: !attributes.autoplay
                                    })
                                } }
                            }
                        />
                    </PanelRow>
                    <PanelRow>
                        <TextControl
                            label = { __( 'Volume', 'codeflavors-vimeo-video-post-lite' ) }
                            help = { __( 'Will work only for JS embeds', 'codeflavors-vimeo-video-post-lite' ) }
                            type = "number"
                            step = "1"
                            value = { attributes.volume }
                            min = "0"
                            max = "100"
                            onChange = {
                                ( value ) => {
                                    setAttributes({
                                        volume: ( value >= 0 && value <= 100 ) ? value : attributes.volume
                                    })
                                }
                            }
                        />
                    </PanelRow>
                </PanelBody>

                <PanelBody
                    title = { __('Embed size', 'codeflavors-vimeo-video-post-lite') }
                    initialOpen = {false}
                >
                    <PanelRow>
                        <TextControl
                            label = { __( 'Width', 'codeflavors-vimeo-video-post-lite' ) }
                            type = "number"
                            step = "5"
                            value = { attributes.width }
                            min = "200"
                            onChange = {
                                ( value ) => {
                                    setAttributes({
                                        width: ( !value || value < 200 ) ? 200 : value
                                    })
                                    vimeotheque.resizeAll()
                                }
                            }
                        />
                    </PanelRow>

                    <PanelRow>
                        <SelectControl
                            label = { __( 'Aspect ratio', 'codeflavors-vimeo-video-post-lite' ) }
                            value = { attributes.aspect_ratio }
                            options = {[
                                { label: '4x3', value: '4x3' },
                                { label: '16x9', value: '16x9' },
                                { label: '2.35x1', value: '2.35x1' },
                            ]}
                            onChange = {
                                ( value ) => {
                                    setAttributes({
                                        aspect_ratio: value
                                    })
                                    setTimeout(  vimeotheque.resizeAll, 200 );
                                }
                            }
                        />
                    </PanelRow>

                    <PanelRow>
                        <SelectControl
                            label = { __( 'Align', 'codeflavors-vimeo-video-post-lite' ) }
                            value = { attributes.video_align }
                            options = {[
                                { label: 'left', value: 'align-left' },
                                { label: 'center', value: 'align-center' },
                                { label: 'right', value: 'align-right' },
                            ]}
                            onChange = {
                                value => {
                                    setAttributes({
                                        video_align: value
                                    })
                                }
                            }
                        />
                    </PanelRow>
                </PanelBody>

            </InspectorControls>
        ]
    },

    save: props => null
})
