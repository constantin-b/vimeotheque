import VideoPostsList from './components/VideoPostsList'
import ServerSideEmbed from './components/ServerSideEmbed'
import SearchForm from "./components/SearchForm";
import CategoryList from "./components/CategoryList";
import ListMenu from "./components/ListMenu";
import { size, keys, map, merge, forEach } from 'lodash'

const {
        blocks: {
            registerBlockType
        },
        i18n: {
            __
        },
        blockEditor: {
            InspectorControls,
            BlockControls
        },
        components: {
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
        },
        element: {
            useState,
            useEffect
        },
        data: {
            select
        },
        hooks: {
            applyFilters,
            doAction
        }
    } = wp,
    {
        themes,
        order: videosOrder
    } = vmtq

registerBlockType( 'vimeotheque/video-playlist', {
    title: __( 'Video playlist', 'codeflavors-vimeo-video-post-lite' ),
    description: __( 'Display a playlist of Vimeo videos', 'codeflavors-vimeo-video-post-lite' ),
    icon: 'playlist-video',
    category: 'widgets',

    example: {

    },

    supports: {
        align: false,
        anchor: false,
        html: false,
        customClassName: false
    },

    edit: ( props ) => {
        const
            {
                attributes,
                setAttributes,
                className
            } = props,

            // modal window state
            [isOpen, setOpen] = useState( false ),
            // used to load the initial videos returned from DB
            [search, setSearch] = useState( { query: '', category: false } ),
            [showSearch, setShowSearch] = useState( true ),
            [taxonomy, setTaxonomy] = useState( 'vimeo-videos' ),
            [postType, setPostType] = useState( 'vimeo-video' ),
            [isRequestLoading, setRequestLoading] = useState( false ),

            openModal = e => {
                e.stopPropagation()
                setOpen( true )
            },

            closeModal = () => setOpen( false ),

            // posts selection
            selectPost = post => {
                let vids = [...attributes.videos, post ]
                setAttributes({
                    videos: vids
                })
                setPostsAttr( vids )
            },

            unselectPost = (post) => {
                for( var i = attributes.videos.length-1; i >= 0; i--){
                    if( attributes.videos[i].id == post.id ){
                        attributes.videos.splice( i, 1 )
                        setAttributes({
                            videos: [...attributes.videos]
                        })
                        setPostsAttr( attributes.videos )
                        break
                    }
                }
            },

            updateCategories = (categories) => {
                setAttributes({
                    categories: categories
                })
                setCatAttr( categories )
            },

            // update processed video IDs
            setPostsAttr = (vids) => {
                let _posts = []
                for( var i = 0; i <= vids.length-1; i++ ){
                    _posts.push( vids[i].id )
                }
                setAttributes({
                    post_ids: _posts
                })
            },

            setCatAttr = (categories) => {
                let _categories = []
                categories.forEach( (item) => {
                    _categories.push( item.id )
                })

                setAttributes({
                    cat_ids: _categories
                })
            },

            // create categories lists for all registered post types
            categoriesList = map( vmtq.postTypes, postType => {
                if( postType.taxonomy ){
                    return <CategoryList
                        key={postType.taxonomy.name}
                        taxonomy={postType.taxonomy.name}
                        title={ postType.taxonomy.labels.name }
                        categories={attributes.categories}
                        onChange={
                            categories => updateCategories( categories )
                        }
                        messageEmpty = { __( 'None available.', 'codeflavors-vimeo-video-post-lite' ) }
                    />
                }
            })

        useEffect(
            () => {
                setPostsAttr( attributes.videos )
                setCatAttr( attributes.categories )
            }, []
        )

        const serverSideEmbedAttributes = () => {
            const attrs = {
                theme: attributes.theme,
                layout: attributes.layout,
                show_excerpts: attributes.show_excerpts,
                use_original_thumbnails: attributes.use_original_thumbnails,
                order: attributes.order,
                aspect_ratio: attributes.aspect_ratio,
                align: attributes.align,
                width: attributes.width,
                volume: attributes.volume,
                title: attributes.title,
                byline: attributes.byline,
                portrait: attributes.portrait,
                playlist_loop: attributes.playlist_loop,
                post_ids: attributes.post_ids,
                cat_ids: attributes.cat_ids
            }

            return applyFilters(
                'vimeotheque.playlist.server-site-embed-params',
                attrs
            )
        }

        return [
            <div key='vimeotheque-playlist-block'>
                {
                    ( attributes.videos.length > 0 || attributes.categories.length > 0 ) &&
                    <>
                        <BlockControls>
                            <div
                                className='components-toolbar'
                            >
                                <Tooltip
                                    text={__('Edit playlist', 'codeflavors-vimeo-video-post-lite')}
                                    position="top"
                                    placement="top"
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
                            block="vimeotheque/video-playlist"
                            attributes={ serverSideEmbedAttributes() }
                            onComplete = { () =>{
                                setTimeout( ()=>{
                                    /**
                                     * All themes are stored under variable vimeotheque.themes
                                     * This simply iterates all registered functions and runs
                                     * each one
                                     */
                                    forEach( window.vimeotheque.themes, func => {
                                        func()
                                    } )

                                    doAction( 'vimeotheque.playlist.server-side-embed-loaded' )

                                }, 200 )
                            } }
                            isSelected={props.isSelected}
                        />
                    </>
                }

                { ( attributes.videos.length == 0 && attributes.categories.length == 0  ) &&
                    <Placeholder
                        icon="playlist-video"
                        label={ __('Video playlist', 'codeflavors-vimeo-video-post-lite') }
                    >
                        <Button
                            isPrimary
                            onClick={ openModal }>
                            { __('Choose posts', 'codeflavors-vimeo-video-post-lite') }
                        </Button>
                    </Placeholder>
                }

                {
                    isOpen && (
                        <Modal
                            title={ __( 'Choose posts', 'codeflavors-vimeo-video-post-lite' ) }
                            onRequestClose = { closeModal }
                            className = 'vimeotheque-posts-list-modal'
                        >
                            <div className="wrapper">
                                <div className="vimeotheque-post-list-container">
                                    <ListMenu
                                        postType={postType}
                                        disabled={isRequestLoading}
                                        textSelected={ `${__( 'Selected', 'codeflavors-vimeo-video-post-lite' )} ${attributes.videos.length}\\${attributes.cat_ids.length}` }
                                        onPostTypeChange={
                                            postType => {
                                                setShowSearch( 'selected' != postType )

                                                let tax = 'selected' != postType
                                                if( tax ){
                                                    tax = vmtq.postTypes[ postType ].taxonomy ? vmtq.postTypes[ postType ].taxonomy.name : false
                                                }
                                                setTaxonomy( tax )

                                                setSearch({ query:'', category:false })
                                                setPostType( postType )
                                            }
                                        }
                                    />
                                    <VideoPostsList
                                        onSelect = { selectPost }
                                        onRemove = { unselectPost }
                                        filteredPosts = { attributes.videos }
                                        filteredCategories={ attributes.cat_ids }
                                        search={ search }
                                        postType={ postType }
                                        taxonomy={ taxonomy }
                                        onRequestBegin = {
                                            () => setRequestLoading(true)
                                        }
                                        onRequestFinish = {
                                            () => setRequestLoading(false)
                                        }
                                        onRequestError = {
                                            () => setRequestLoading(false)
                                        }
                                    />
                                </div>
                                <nav className="sidebar">
                                    {
                                        showSearch ?
                                        // Show search form if not displaying selected posts/categories
                                            <SearchForm
                                                blocked = { isRequestLoading }
                                                taxonomy={ taxonomy }
                                                selectedCategories={attributes.categories}
                                                values={ search }
                                                onSubmit = {
                                                    value => setSearch( value )
                                                }
                                                onCategorySelect = {
                                                    // array of selected category ID's
                                                    categories => updateCategories( categories )
                                                }
                                                onCategoriesUpdate = {
                                                    categories => {
                                                        //console.log(categories)
                                                    }
                                                }
                                            />
                                        :
                                        // Show categories management in sidebar
                                        <>
                                            {categoriesList}
                                        </>
                                    }
                                </nav>
                            </div>
                        </Modal>
                    )
                }

            </div>,

            <InspectorControls key='vimeotheque-playlist-controls'>
                <PanelBody
                    title={ __( 'Theme', 'codeflavors-vimeo-video-post-lite' ) }
                >
                    <PanelRow>
                        <SelectControl
                            label = { __( 'Theme', 'codeflavors-vimeo-video-post-lite' ) }
                            value = {attributes.theme}
                            options = { themes }
                            onChange = {
                                value => {
                                    setAttributes({
                                        theme: value
                                    })
                                }
                            }
                        />
                    </PanelRow>
                    <PanelRow>
                        <TextControl
                            label = { __( 'Width', 'codeflavors-vimeo-video-post-lite' ) }
                            type = "number"
                            step = "5"
                            value = { attributes.width }
                            min = "200"
                            onChange = {
                                value => {
                                    setAttributes({
                                        width: value
                                    })
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
                                value => {
                                    setAttributes({
                                        aspect_ratio: value
                                    })
                                }
                            }
                        />
                    </PanelRow>
                    <PanelRow>
                        <SelectControl
                            label = { __( 'Align', 'codeflavors-vimeo-video-post-lite' ) }
                            value = { attributes.align }
                            options = {[
                                { label: 'left', value: 'align-left' },
                                { label: 'center', value: 'align-center' },
                                { label: 'right', value: 'align-right' },
                            ]}
                            onChange = {
                                value => {
                                    setAttributes({
                                        align: value
                                    })
                                }
                            }
                        />
                    </PanelRow>
                </PanelBody>

                <PanelBody
                    title={ __( 'Video posts', 'codeflavors-vimeo-video-post-lite' ) }
                >
                    <PanelRow>
                        <SelectControl
                            label = { __( 'Posts order', 'codeflavors-vimeo-video-post-lite' ) }
                            value = {attributes.order}
                            options = { videosOrder }
                            onChange = {
                                value => {
                                    setAttributes({
                                        order: value
                                    })
                                }
                            }
                        />
                    </PanelRow>
                </PanelBody>

                <PanelBody
                    title = { __('Embed options', 'codeflavors-vimeo-video-post-lite') }
                    initialOpen = {true}
                >
                    <PanelRow>
                        <ToggleControl
                            label = { __( 'Show title', 'codeflavors-vimeo-video-post-lite' ) }
                            checked = {attributes.title}
                            onChange = {
                                () => {
                                    setAttributes({
                                        title: !attributes.title
                                    })
                                }
                            }
                        />
                    </PanelRow>
                    <PanelRow>
                        <ToggleControl
                            label = { __( 'Show byline', 'codeflavors-vimeo-video-post-lite' ) }
                            checked = {attributes.byline}
                            onChange = {
                                () => {
                                    setAttributes({
                                        byline: !attributes.byline
                                    })
                                }
                            }
                        />
                    </PanelRow>
                    <PanelRow>
                        <ToggleControl
                            label = { __( 'Show portrait', 'codeflavors-vimeo-video-post-lite' ) }
                            checked = {attributes.portrait}
                            onChange = {
                                () => {
                                    setAttributes({
                                        portrait: !attributes.portrait
                                    })
                                }
                            }
                        />
                    </PanelRow>
                    <PanelRow>
                        <ToggleControl
                            label = { __( 'Loop playlist', 'codeflavors-vimeo-video-post-lite' ) }
                            checked = {attributes.playlist_loop}
                            onChange =  {
                                () => {
                                    setAttributes({
                                        playlist_loop: !attributes.playlist_loop
                                    })
                                }
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
                                value => {
                                    let vol = ( value >= 0 && value <= 100 ) ? value : attributes.volume;
                                    setAttributes({
                                        volume: vol
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
} );
