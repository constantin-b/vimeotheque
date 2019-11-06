<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Channel_Resource
 * @package Vimeotheque
 */
class Channel_Resource extends Resource_Abstract implements Resource_Interface {

	/**
	 * Channel_Resource constructor.
	 *
	 * @param $resource_id
	 * @param array $params
	 */
	public function __construct( $resource_id, $params = [] ) {
		parent::__construct( $resource_id, false, $params );
		parent::set_action(
			sprintf( 'channels/%s/videos', $resource_id )
		);
		parent::set_default_params([
			'direction' => 'desc',
			'filter' => '',
			'filter_embeddable' => false,
			'page' => 1,
			'per_page' => 20,
			'query' => '',
			'sort' => 'added'
		]);

		parent::set_sort_options(
			[
				'added',
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

		parent::set_name( __( 'Channel', 'cvm_video' ) );

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