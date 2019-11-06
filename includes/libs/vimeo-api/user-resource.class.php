<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class User_Resource
 * @package Vimeotheque
 */
class User_Resource extends Resource_Abstract implements Resource_Interface {

	/**
	 * User_Resource constructor.
	 *
	 * @param bool $user_id
	 * @param array $params
	 */
	public function __construct( $user_id = false, $params = [] ) {
		parent::__construct( false, $user_id, $params );
		parent::set_action(
			sprintf( 'users/%s/videos', $user_id )
		);
		parent::set_default_params([
			'direction' => 'desc',
			'filter' => '',
			'filter_embeddable' => false,
			'filter_playable' => false,
			'page' => 1,
			'per_page' => 20,
			'query' => '',
			'sort' => 'default'
		]);

		parent::set_sort_options(
			[
				'alphabetical',
				'comments',
				'date',
				'default',
				'duration',
				'last_user_action_event_date',
				'likes',
				'modified_time',
				'plays'
			]
		);

		parent::set_filtering_options([
			'app_only',
			'embeddable',
			'featured',
			'playable',
		]);

		parent::set_name( __( 'User uploads', 'cvm_video' ) );
	}

	/**
	 * Can import newly added videos after importing the entire feed
	 *
	 * @return bool
	 */
	public function can_import_new_videos() {
		return true;
	}

	/**
	 * Feed can use date limit
	 *
	 * @return bool
	 */
	public function has_date_limit(){
		return true;
	}

}