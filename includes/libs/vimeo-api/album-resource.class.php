<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Album_Resource
 * @package Vimeotheque
 */
class Album_Resource extends Resource_Abstract implements Resource_Interface {

	/**
	 * Album_Resource constructor.
	 *
	 * @param $resource_id
	 * @param bool $user_id
	 * @param array $params
	 */
	public function __construct( $resource_id, $user_id = false, $params = [] ) {
		// built without direction
		$default_params = [
			'filter' => '',
			'filter_embeddable' => false,
			'page' => 1,
			'password' => '',
			'per_page' => 20,
			'query' => '',
			'sort' => 'manual' // "manual" sorts by the date the video was added to album
		];

		// when sort is default, direction must be eliminated
		if( 'default' == $params['sort'] ){
			unset( $params['direction'] );
		}else{
			$default_params['direction'] = 'desc';
		}

		parent::__construct( $resource_id, $user_id, $params );

		parent::set_action(
			sprintf(
				'users/%s/albums/%s/videos',
				$user_id,
				$resource_id
			)
		);

		parent::set_default_params( $default_params );

		parent::set_sort_options(
			[
				'alphabetical',
				'comments',
				'date',
				'default',
				'duration',
				'likes',
				'manual',
				'modified_time',
				'plays'
			]
		);

		parent::set_filtering_options([
			'embeddable'
		]);

		parent::set_name( __( 'Showcase/Album', 'cvm_video' ) );

	}

	/**
	 * Can import newly added videos after importing the entire feed
	 *
	 * @return bool
	 */
	public function can_import_new_videos() {
		return true;
	}

}