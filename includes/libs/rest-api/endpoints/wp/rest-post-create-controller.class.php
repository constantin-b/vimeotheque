<?php
namespace Vimeotheque\Rest_Api\Endpoints\Wp;

use Vimeotheque\Plugin;
use Vimeotheque\Rest_Api\Endpoints\Rest_Controller_Abstract;
use Vimeotheque\Rest_Api\Endpoints\Rest_Controller_Interface;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Rest_Post_Create_Controller extends Rest_Controller_Abstract implements Rest_Controller_Interface {

	public function __construct() {
		parent::set_namespace( 'vimeotheque/v1' );
		parent::set_rest_base( '/wp/post-create/' );
		$this->register_routes();
	}

	/**
	 * @inheritDoc
	 */
	public function register_routes() {
		register_rest_route(
			parent::get_namespace(),
			parent::get_rest_base(),
			[
				'methods' => \WP_REST_Server::CREATABLE,
				'callback' => [ $this, 'get_response' ],
				'permission_callback' => function(){
					return current_user_can( 'edit_posts' );
				},
				'args' => [
					'video' => [
						'validate_callback' => function( $param ){
							return is_array( $param );
						}
					],
					'postId' => [
						'validate_callback' => function( $param ){
							return is_numeric( $param );
						}
					]
				]

			]
		);
	}

	/**
	 * @inheritDoc
	 */
	public function get_response( \WP_REST_Request $request ) {
		$video = $request->get_param( 'video' );
		if( !isset( $video['video_id'] ) || empty( $video['video_id'] ) ){
			return new \WP_Error(
				'vimeotheque_no_video_id',
				__('Video ID could not be detected. Please try again.', 'cvm_video')
			);
		}

		$duplicates = Plugin::$instance
			->get_posts_importer()
			->get_duplicate_posts(
				[ $video ],
				Plugin::$instance->get_cpt()->get_post_type()
			);

		if( $duplicates ){
			$post_id = $duplicates[ $video['video_id'] ][0];
			return $this->response(
				$post_id,
				__( 'Video post was already imported.', 'cvm_video' )
			);
		}

		$post_id = $request->get_param( 'postId' );
		$post = get_post( $post_id );
		if( is_wp_error( $post ) ){
			return $post;
		}elseif( !$post || 'auto-draft' != $post->post_status ){
			return new \WP_Error(
				'vimeotheque_post_auto_draft_not_found',
				__( 'An unknown error has occured, please refresh the page and try again.', 'cvm_video' )
			);
		}

		$import_result = Plugin::$instance
			->get_posts_importer()
			->import_video(
				[
					'video' => $request->get_param( 'video' ),
					'post_id' => $post_id,
					'category' => false,
					'post_type' => Plugin::$instance->get_cpt()->get_post_type(),
					'taxonomy' => Plugin::$instance->get_cpt()->get_post_tax(),
					'tag_taxonomy' => Plugin::$instance->get_cpt()->get_tag_tax(),
					'tags' => false,
					'user' => false,
					'post_format' => 'video',
					'status' => 'draft',
					'theme_import' => false,
					'options' => false
				]
			);

		if( !$import_result ){
			return new \WP_Error(
				'vimeotheque_post_not_imported',
				__( 'Sorry, your video could not be imported, please try again.', 'cvm_video' )
			);
		}else{
			return $this->response(
				$post_id,
				__( 'Video post created!', 'cvm_video' )
			);
		}
	}

	/**
	 * @param $post_id
	 * @param $message
	 *
	 * @return array
	 */
	private function response( $post_id, $message ){
		return [
			'message' => $message,
			'postId' => $post_id,
			'editLink' => get_edit_post_link( $post_id, 'raw' ),
			'viewLink' => get_permalink( $post_id )
		];
	}
}