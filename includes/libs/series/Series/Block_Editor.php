<?php
/**
 * @author CodeFlavors
 * @project vimeotheque-series
 */

namespace Vimeotheque_Series\Series;

use Vimeotheque_Series\Helper;
use Vimeotheque_Series\Plugin;

if (!defined('ABSPATH')) {
    die();
}

class Block_Editor {

    /**
     * Constructor.
     *
     * Initialize the blocks for the Block Editor.
     */
    public function __construct(){

        add_action(
            'init',
            function(){
                register_block_type(
                    Helper::get_path() . 'assets/series/block/block.json',
                    [
                        'render_callback' => [$this, 'the_content']
                    ]
                );
            }
        );

        add_action(
            'enqueue_block_editor_assets',
            [
                $this,
                'editor_assets'
            ]
        );

    }

    /**
     * Get the playlist content.
     *
     * @param array $attr
     * @return string
     */
    public function the_content( $attr ): string {
        $result = '';

        if( isset( $attr['playlist'] ) && $attr['playlist'] ) {
            $playlist = new Playlist(get_post( (int) $attr['playlist'] ) );
            // Get the content
            $result = $playlist->get_content();
        }

        return $result;
    }

    /**
     * Load the editor assets.
     *
     * Loads the additional editor assets from the playlist themes
     * required when displaying the series playlists.
     *
     * @return void
     */
    public function editor_assets() {

        $themes = Plugin::instance()->get_themes_manager()->get_themes();
        foreach ($themes as $theme) {
            $theme->enqueue_style();
            $theme->enqueue_script(['vimeotheque-series-block-editor-script']);
        }

        \Vimeotheque\Helper::enqueue_player(['vimeotheque-series-block-editor-script']);
    }

}