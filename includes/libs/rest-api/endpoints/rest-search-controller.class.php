<?php

namespace Vimeotheque\Rest_Api\Endpoints;

use Vimeotheque\Video_Import;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Rest_Search_Controller extends Rest_Controller_Abstract implements Rest_Controller_Interface {

	/**
	 * Rest_Pictures_Controller constructor.
	 */
	public function __construct() {
		parent::set_namespace( 'vimeotheque/v1' );
		parent::set_rest_base( '/api-query/search/' );
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
				'method' => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'get_response' ],
				'permission_callback' => function(){
					return current_user_can( 'edit_posts' );
				},
				'args' => [
					'query' => [
						'validate_callback' => function( $param ){
							if( empty( $param ) ){
								return new \WP_Error( 'vimeotheque-rest-empty-search', __( 'No search query detected.', 'cvm_video' ) );
							}

							return true;
						}
					]
				]
			]
		);
	}

	/**
	 * Returns the response for Rest API
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return array
	 */
	public function get_response( \WP_REST_Request $request ) {
		if( empty( $request->get_param( 'query' ) ) ){
			return new \WP_Error(
				'vimeotheque-rest-empty-search',
				__( 'No search query detected.', 'cvm_video' )
			);
		}

		$query = new Video_Import(
			'search',
			$request->get_param('query'),
			false,
			[
				'page' => 1,
				'per_page' => 10,
				'order' => 'relevant'
			]
		);
		return $query->get_feed();
	}
}