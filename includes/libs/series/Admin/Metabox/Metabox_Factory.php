<?php
/**
 * @author CodeFlavors
 * @project vimeotheque-series
 */

namespace Vimeotheque_Series\Admin\Metabox;

use Vimeotheque_Series\Plugin;

if (!defined('ABSPATH')) {
    die();
}

class Metabox_Factory {
    /**
     * @var Metabox_Interface[]
     */
    private $meta_boxes;

    /**
     * @param Metabox_Interface $metabox
     */
    public function __construct( Metabox_Interface $metabox ) {
        $this->register_meta_box( $metabox );

        /**
         * Allow only meta boxes that were registered by the plugin.
         */
        add_action(
            'add_meta_boxes',
            function( $post_type, $post ){

                global $wp_meta_boxes;

                if( isset( $wp_meta_boxes[ Plugin::instance()->get_post_type()->get_post_name() ] ) ){
                    unset( $wp_meta_boxes[ Plugin::instance()->get_post_type()->get_post_name() ] );
                }
                
            }, 999999, 2
        );
        
    }

    /**
     * Register a metabox
     *
     * @param Metabox_Interface $metabox
     * @return void
     */
    public function register_meta_box ( Metabox_Interface $metabox ) {
        $this->meta_boxes[ $metabox->get_id() ] = $metabox;
    }

    /**
     * Initiate all meta boxes.
     *
     * @return void
     */
    public function initiate_metaboxes() {
        foreach ($this->meta_boxes as $meta_box) {
            $meta_box->initiate_metabox();
        }
    }

}