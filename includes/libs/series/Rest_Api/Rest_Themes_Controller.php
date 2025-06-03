<?php
/**
 * @author  CodeFlavors
 * @project vimeotheque-series
 */

namespace Vimeotheque_Series\Rest_Api;

use Vimeotheque_Series\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Rest Controller for the Vimeotheque Series Themes.
 */
class Rest_Themes_Controller extends Rest_Controller_Abstract implements Rest_Controller_Interface {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::set_namespace( 'vimeotheque-series/v1' );
		parent::set_rest_base( '/themes' );
		$this->register_routes();
	}

	/**
	 * Get all the registered themes.
	 *
	 * @param  \WP_REST_Request $request
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function get_items( \WP_REST_Request $request ) {

		if ( ! current_user_can( 'edit_posts' ) ) {
			return new \WP_Error(
				'vimeotheque-series-rest-no-access',
				__( "You don't have access to this resource.", 'codeflavors-vimeo-video-post-lite' )
			);
		}

		$themes = [];

		$registered_themes = Plugin::instance()->get_themes_manager()->get_themes();
		foreach ( $registered_themes as $registered_theme ) {
			$themes[] = [
				'name'       => $registered_theme->get_name(),
				'screenshot' => $registered_theme->get_screenshot(),
				'folder'     => $registered_theme->get_folder_name(),
			];
		}

		$response = rest_ensure_response( $themes );
		$response->header( 'X-WP-Total', count( $themes ) );
		$response->header( 'X-WP-TotalPages', 1 );

		return $response;
	}

	/**
	 * Register the routes.
	 *
	 * @return void
	 */
	public function register_routes() {

		register_rest_route(
			parent::get_namespace(),
			parent::get_rest_base(),
			[
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => function () {
						return current_user_can( 'edit_posts' );
					},
				),
			]
		);
	}
}
