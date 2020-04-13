<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Group_Resource
 * @package Vimeotheque
 */
class Group_Resource extends Resource_Abstract implements Resource_Interface {

	/**
	 * Group_Resource constructor.
	 *
	 * @param $resource_id
	 * @param array $params
	 */
	public function __construct( $resource_id, $params = [] ) {
		parent::__construct( $resource_id, false, $params );

		parent::set_default_params([
			'direction' => 'desc',
			'filter' => '',
			'filter_embeddable' => false,
			'page' => 1,
			'per_page' => 20,
			'query' => '',
			'sort' => 'date'
		]);

		parent::set_sort_options(
			[
				'alphabetical',
				'comments',
				'date',
				'duration',
				'likes',
				'plays'
			]
		);

		parent::set_filtering_options([
			'embeddable'
		]);

		parent::set_name( 'group', __( 'Group', 'cvm_video' ) );

	}

	/**
	 * Feed can use date limit
	 *
	 * @return bool
	 */
	public function has_date_limit(){
		return true;
	}

	public function get_api_endpoint() {
		return sprintf( 'groups/%s/videos', $this->resource_id );
	}


}