import {assign} from 'lodash'
const {addFilter} = wp.hooks,
    { __ } = wp.i18n,
    { createHigherOrderComponent } = wp.compose,
    { InspectorControls } = wp.blockEditor || wp.editor,
    { PanelBody, SelectControl, ToggleControl } = wp.components

const enableOnBlocks = [
    'vimeotheque/video-playlist'
]

const layoutOptions = [
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
            );
        }

        const { layout, show_excerpts } = props.attributes;

        return (
            <>
                {
                    'default' == props.attributes.theme &&
                    <InspectorControls>
                        <PanelBody
                            title={ __( 'Layout', 'codeflavors-vimeo-video-post-lite' ) }
                            initialOpen={ true }
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
                        </PanelBody>
                    </InspectorControls>
                }

                <BlockEdit { ...props } />
            </>
        );
    };
}, 'withLayoutControl' );

addFilter( 'editor.BlockEdit', 'playlist-theme-default/with-layout-controls', withLayoutControls );