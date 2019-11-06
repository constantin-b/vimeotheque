<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Category_Resource
 * @package Vimeotheque
 */
class Category_Resource extends Resource_Abstract implements Resource_Interface {

	/**
	 * Category_Resource constructor.
	 *
	 * @param $resource_id
	 * @param array $params
	 */
	public function __construct( $resource_id, $params = [] ) {
		parent::__construct( $resource_id, false, $params );
		parent::set_action(
			sprintf( 'categories/%s/videos', $resource_id )
		);
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
				'featured',
				'likes',
				'plays',
				'relevant'
			]
		);

		parent::set_filtering_options([
			'conditional_featured',
			'embeddable'
		]);

		parent::set_name( __( 'Category', 'cvm_video' ) );
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