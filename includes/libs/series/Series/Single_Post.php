<?php
/**
 * @author CodeFlavors
 * @project vimeotheque-series
 */

namespace Vimeotheque_Series\Series;

use Vimeotheque\Helper;
use Vimeotheque_Series\Plugin;

if (!defined('ABSPATH')) {
    die();
}

class Single_Post {

    /**
     * Constructor.
     *
     * Initialize filters.
     */
    public function __construct() {

        add_filter(
            'the_content',
            function( $content ){
                if( is_singular( Plugin::instance()->get_post_type()->get_post_name() ) ){
                    // Initialize the Series
                    return $this->the_content();
                }

                return $content;
            }
        );
    }

    /**
     * Get the playlist content.
     *
     * @return false|string
     */
    public function the_content() {

        $playlist = new Playlist(get_post() );
        return $playlist->get_content();
    }

}