<?php
/**
 * @author  CodeFlavors
 * @project vimeotheque-automatic-import
 */

namespace Vimeotheque_Series\Admin;

use Vimeotheque_Series\Helper;
use Vimeotheque_Series\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class Admin
 *
 * @package Vimeotheque_Series\Admin
 */
class Admin {

	/**
	 * Admin constructor.
	 */
	public function __construct(){
		add_action( 'wp_loaded', [
			$this,
			'init'
		], 0 );
	}

	/**
	 * Initialize admin
	 */
	public function init(){

        add_action(
            'current_screen',
            function( $screen ){
                if( $screen->id === 'series' ){
                    /**
                     * Disable Classic editor scripts from Vimeotheque.
                     */
                    add_filter(
                        'vimeotheque-lite/enable-classic-editor-files',
                        '__return_false'
                    );

                }
            }
        );

        /**
         * Add the post title section.
         */
        add_action(
            'edit_form_after_title',
            function( $post ){
                if( Plugin::instance()->get_post_type()->get_post_name() == $post->post_type ){
                    $script_handle = Helper::enqueue_script(
                        'post-title',
                        ['wp-element', 'wp-editor', 'lodash'],
                        true
                    );

                    wp_localize_script(
                        $script_handle,
                        'VSE',
                        [
                            'postId' => $post->ID
                        ]
                    );
?>
<div id="vimeotheque-series-post-title"><!-- The React App for the post title section --></div>

<?php
                }
            }
        );

	}
}