<?php

namespace Vimeotheque\Admin\Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Vimeotheque\Admin\Admin;
use Vimeotheque\Admin\Editor\Classic_Editor;
use Vimeotheque\Helper;
use Vimeotheque\Plugin;
use Vimeotheque\Post\Post_Type;
use Vimeotheque\Video_Post;

/**
 * Class Post_Edit_Page
 * @package Vimeotheque\Admin
 */
class Post_Edit_Page{
	/**
	 * @var Video_Post
	 */
	private $video;
	/**
	 * @var Post_Type
	 */
	private $cpt;

	/**
	 * Post_Edit_Page constructor.
	 *
	 * @param Admin $admin
	 */
	public function __construct( Admin $admin ){
		$this->cpt = $admin->get_post_type();

		add_action( 'admin_enqueue_scripts', [
			$this,
			'current_screen'
		], -999999999 );

		// action on loading post-new page for custom post type. Manages single video import
		//*
        add_action( 'load-post-new.php', [
			$this,
			'post_new_onload'
		] );
		//*/

		// Gutenberg action
		add_action( 'enqueue_block_editor_assets', [
            $this,
			'block_editor_assets'
        ] );

		// save data from meta boxes
		add_action( 'save_post', [
			$this,
			'save_post'
		], 10, 2 );
	}

	/**
	 * Save post data from meta boxes.
	 * Hooked to save_post
	 *
	 * @param int $post_id
	 * @param WP_Post $post
	 */
	public function save_post( $post_id, $post ){
		if( ! isset( $_POST[ 'cvm-video-nonce' ] ) ){
			return;
		}

		$_post = Helper::get_video_post( $post );
		// check if post is the correct type
		if( !$_post->is_video() ){
			return;
		}
		// check if user can edit
		if( ! current_user_can( 'edit_post', $post_id ) ){
			return;
		}
		// check nonce
		check_admin_referer( 'cvm-save-video-settings', 'cvm-video-nonce' );
		// update post
		\Vimeotheque\cvm_update_video_settings( $post_id );
	}

	/**
	 * Add functionality for the classic editor
	 */
	public function current_screen(){
		new Classic_Editor( get_current_screen() );
	}

	/**
	 * New post load action for videos.
	 * Will first display a form to query for the video.
	 */
	public function post_new_onload(){
		if( ! isset( $_REQUEST[ 'post_type' ] ) || $this->cpt->get_post_type() !== $_REQUEST[ 'post_type' ] ){
			return;
		}

		// blocks are not needed here
        Plugin::instance()->get_blocks()->unregister_blocks();

		global $post;
		$post = get_default_post_to_edit( $this->cpt->get_post_type(), true );

		include ABSPATH . 'wp-admin/admin-header.php';

		$options = \Vimeotheque\Plugin::instance()->get_options();
		if( empty( $options[ 'vimeo_consumer_key' ] ) || empty( $options[ 'vimeo_secret_key' ] ) ){
			?>
            <p>
				<?php _e( 'Please register on <a href="https://developer.vimeo.com/apps/new">Vimeo App page</a> in order to be able to import videos.', 'cvm_video' );?><br />
				<?php printf( __( 'A step by step tutorial on how to create an app on Vimeo can be found %shere%s.', 'cvm_video' ), '<a href="'. cvm_docs_link( 'getting-started/vimeo-oauth-new-interface/' ) .'" target="_blank">', '</a>');?>
            </p>
			<?php
		}else{
            wp_enqueue_script(
                'vimeotheque-import-video-react-app',
                VIMEOTHEQUE_URL . 'assets/back-end/js/apps/add_video/app.build.js',
                ['wp-element', 'wp-editor'],
                '1.0'
            );
			wp_localize_script(
			    'vimeotheque-import-video-react-app',
                'wpApiSettings',
                [
				    'root' => esc_url_raw( rest_url() ),
				    'nonce' => wp_create_nonce( 'wp_rest' ),
                    'postId' => $post->ID
			    ]
            );

			wp_enqueue_style(
				'vimeotheque-import-video-react-app',
                VIMEOTHEQUE_URL . 'assets/back-end/js/apps/add_video/style.css',
                ['wp-editor']
            );

			wp_enqueue_style( 'wp-editor' );

?>
<div class="wrap">
    <h1><?php _e( 'Import video' );?></h1>
    <div id="poststuff">
        <div class="notice error hide-if-js">
            <p><?php _e( 'JavaScript is disabled! You must enable JavaScript in order to be able to import videos.', 'cvm_video' );?></p>
        </div>
        <div id="vimeotheque-import-video"><!-- React App root --></div>
    </div>

</div>
<?php
		}

		include ABSPATH . 'wp-admin/admin-footer.php';
		die();
    }

	/**
	 * Callback on Gutenberg's script enqueue action
     * Enqueues all necessary files for Gutenberg compatibility
	 */
	public function block_editor_assets(){
	    global $post;
	    // do not enqueue script if post isn't a video post imported by the plugin
        if( is_a( $post, 'WP_Post' ) ){
            if( !\Vimeotheque\is_video( $post ) ){
                return;
            }
        }

		wp_enqueue_script(
		    'cvm-gutenberg',
			VIMEOTHEQUE_URL . 'assets/back-end/js/gutenberg/video-thumbnail.js',
            [ 'jquery' ],
            '1.0'
        );
    }
}