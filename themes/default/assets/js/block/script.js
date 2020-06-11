import {assign} from 'lodash'
const {addFilter} = wp.hooks,
    { __ } = wp.i18n,
    { createHigherOrderComponent } = wp.compose,
    { InspectorControls } = wp.editor,
    { PanelBody, SelectControl } = wp.components

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

const addLayoutAttribute = ( settings, name ) => {
    // Do nothing if it's another block than our defined ones.
    if ( ! enableOnBlocks.includes( name ) ) {
        return settings;
    }

    settings.attributes = assign( settings.attributes, {
        layout: {
            type: 'string',
            default: layoutOptions[0].value
        }
    })

    return settings
}
/**
 * @param string - hook name
 * @param string - namespace
 * @param string - callback
 */
addFilter( 'blocks.registerBlockType', 'playlist-theme-default/attribute/layout', addLayoutAttribute )

/**
 * Create HOC to add spacing control to inspector controls of block.
 */
const withLayoutControl = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
        // Do nothing if it's another block than our defined ones.
        if ( ! enableOnBlocks.includes( props.name ) ) {
            return (
                <BlockEdit { ...props } />
            );
        }

        const { layout } = props.attributes;

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
                        </PanelBody>
                    </InspectorControls>
                }

                <BlockEdit { ...props } />
            </>
        );
    };
}, 'withLayoutControl' );

addFilter( 'editor.BlockEdit', 'playlist-theme-default/with-layout-control', withLayoutControl );