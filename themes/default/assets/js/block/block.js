import { assign } from 'lodash'

const {
        hooks: {
            addFilter
        },
        i18n: {
            __
        },
        compose: {
            createHigherOrderComponent
        },
        components: {
            PanelBody,
            SelectControl,
            ToggleControl
        },
        blockEditor: {
            InspectorControls
        }
    } = wp

// local
const enableOnBlocks = [
    'vimeotheque/video-playlist'
],
layoutOptions = [
    {
        label: __( 'Default', 'codeflavors-vimeo-video-post-lite' ),
        value: ''
    },
    {
        label: __( 'Right side', 'codeflavors-vimeo-video-post-lite' ),
        value: 'right'
    },
    {
        label: __( 'Left side', 'codeflavors-vimeo-video-post-lite' ),
        value: 'left'
    }
]

/**
 * Create HOC to add spacing control to inspector controls of block.
 */
const withLayoutControls = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
        // Do nothing if it's another block than our defined ones.
        if ( ! enableOnBlocks.includes( props.name ) ) {
            return (
                <BlockEdit { ...props } />
            )
        }

        const { layout, show_excerpts, use_original_thumbnails } = props.attributes

        return (
            <>
                <InspectorControls>
                    <PanelBody
                        title={ __( 'Layout', 'codeflavors-vimeo-video-post-lite' ) }
                        initialOpen={ true }
                        className={'default' == props.attributes.theme ? '' : 'hide-if-js'}
                    >
                        <SelectControl
                            label={ __( 'Navigation position', 'codeflavors-vimeo-video-post-lite' ) }
                            value={ layout }
                            options={ layoutOptions }
                            onChange={ ( value ) => {
                                props.setAttributes( {
                                    layout: value,
                                } );
                            } }
                        />

                        <ToggleControl
                            label = { __( 'Show excerpts', 'codeflavors-vimeo-video-post-lite' ) }
                            checked = {show_excerpts}
                            onChange = {
                                () => {
                                    props.setAttributes({
                                        show_excerpts: !show_excerpts
                                    })
                                }
                            }
                        />

                        <ToggleControl
                            label = { __( 'Use original thumbnail size ratio', 'codeflavors-vimeo-video-post-lite' ) }
                            help={ use_original_thumbnails ? __( 'Thumbnails might have different sizes depending on the size ratio.', 'codeflavors-vimeo-video-post-lite' ) : __('Thumbnails will have the same size and might display black bars.', 'codeflavors-vimeo-video-post-lite') }
                            checked = {use_original_thumbnails}
                            onChange = {
                                () => {
                                    props.setAttributes({
                                        use_original_thumbnails: !use_original_thumbnails
                                    })
                                }
                            }
                        />

                    </PanelBody>
                </InspectorControls>
                <BlockEdit { ...props } />
            </>
        )
    }
}, 'withLayoutControl' )

addFilter(
    'editor.BlockEdit',
    'playlist-theme-default/with-layout-controls',
    withLayoutControls
)