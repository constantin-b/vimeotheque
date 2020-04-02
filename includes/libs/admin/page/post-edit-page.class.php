<?php

namespace Vimeotheque\Admin\Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Vimeotheque\Admin\Admin;
use Vimeotheque\Admin\Editor\Classic_Editor;
use Vimeotheque\Helper;
use Vimeotheque\Post_Type;
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
		add_action( 'load-post-new.php', [
			$this,
			'post_new_onload'
		] );

		// for empty imported posts, skip $maybe_empty verification
		add_filter( 'wp_insert_post_empty_content', [
				$this, 
				'force_empty_insert'
		], 999, 2 );

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

		if( isset( $_POST[ 'wp_nonce' ] ) ){
			if( check_admin_referer( 'cvm_query_new_video', 'wp_nonce' ) ){
				
				if( empty( $_POST[ 'cvm_video_id' ] ) ){
					wp_redirect( 'post-new.php?post_type=' . $this->cpt->get_post_type() );
					die();
				}
				
				$post_type = $this->cpt->get_post_type();
				
				// search if video already exists
				$posts = get_posts( [
						'post_type' => $post_type, 
						'meta_key' => $this->cpt->get_post_settings()->get_meta_video_id(),
						'meta_value' => $_POST[ 'cvm_video_id' ], 
						'post_status' => [
                            'publish',
                            'pending',
                            'draft',
                            'future',
                            'private'
						]
				] );
				if( $posts ){
					wp_redirect( 'post.php?post=' . $posts[ 0 ]->ID . '&action=edit&message=102&video_id=' . $_POST[ 'cvm_video_id' ] );
					die();
				}
				
				$this->video = \Vimeotheque\cvm_query_video( $_POST[ 'cvm_video_id' ] );
				if( $this->video && !is_wp_error( $this->video ) ){
					$this->video[ 'description' ] = apply_filters( 'cvm_video_post_content', $this->video[ 'description' ], $this->video, [] );
					$this->video[ 'excerpt' ] = apply_filters( 'cvm_video_post_excerpt', $this->video[ 'description' ], $this->video, [] );
					$this->video[ 'title' ] = apply_filters( 'cvm_video_post_title', $this->video[ 'title' ], $this->video, [] );
					// single post import date
					$import_options = \Vimeotheque\Plugin::instance()->get_options();
					$post_date = $import_options[ 'import_date' ] ? date( 'Y-m-d H:i:s', strtotime( $this->video[ 'published' ] ) ) : current_time( 'mysql' );
					$this->video[ 'post_date' ] = apply_filters( 'cvm_video_post_date', $post_date, $this->video, [] );

					add_filter( 'default_content', [
							$this, 
							'default_content'
					], 999, 2 );
					add_filter( 'default_title', [
							$this, 
							'default_title'
					], 999, 2 );
					add_filter( 'default_excerpt', [
							$this, 
							'default_excerpt'
					], 999, 2 );
					
					// add video player for video preview on post
					\Vimeotheque\cvm_enqueue_player();
				}
			}else{
				wp_die( "Cheatin' uh?" );
			}
		}
		// if video query not started, display the form
		if( ! $this->video || is_wp_error( $this->video ) ){
		    $this->output_add_new();
		}
	}

	private function output_add_new(){
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
                ['wp-element'],
                '1.0'
            );
			wp_localize_script(
			    'vimeotheque-import-video-react-app',
                'wpApiSettings',
                [
				    'root' => esc_url_raw( rest_url() ),
				    'nonce' => wp_create_nonce( 'wp_rest' )
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
	 * Set video description on new post
	 * 
	 * @param string $post_content
	 * @param object $post
	 */
	public function default_content( $post_content, $post ){
		if( ! $this->video ){
			return;
		}
		
		return $this->video[ 'description' ];
	}

	/**
	 * Set video title on new post
	 * 
	 * @param string $post_title
	 * @param object $post
	 */
	public function default_title( $post_title, $post ){
		if( ! $this->video ){
			return;
		}
		
		return $this->video[ 'title' ];
	}

	/**
	 * Set video excerpt on new post, add taxonomies and save meta
	 * 
	 * @param string $post_excerpt
	 * @param object $post
	 */
	public function default_excerpt( $post_excerpt, $post ){
		if( ! $this->video ){
			return;
		}

		$args = [
		    'video' => $this->video,
            'post_id' => $post->ID,
            'category' => false,
            'post_type' => $this->cpt->get_post_type(),
            'taxonomy' => $this->cpt->get_post_tax(),
            'tag_taxonomy' => $this->cpt->get_tag_tax(),
            'tags' => false,
            'user' => false,
            'post_format' => 'video',
            'status' => 'draft',
            'theme_import' => false,
            'options' => false
		];

		$this->cpt->get_plugin()->get_posts_importer()->import_video( $args );

		wp_redirect( add_query_arg( [
			'post' => $post->ID,
			'action' => 'edit'
		], 'post.php' ) );
	}

	/**
	 * When trying to insert an empty post, WP is running a filter.
	 * Given the fact that users are allowed to insert empty posts when importing, the filter will return
	 * false on maybe_empty to allow insertion of video.
	 *
	 * @param bool $maybe_empty
	 * @param array $postarr
	 *
	 * @return bool
	 */
	public function force_empty_insert( $maybe_empty, $postarr ){
		if( $this->cpt->get_post_type() == $postarr[ 'post_type' ] ){
			return false;
		}
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
}