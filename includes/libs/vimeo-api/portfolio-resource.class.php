<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Portfolio_Resource
 * @package Vimeotheque
 */
class Portfolio_Resource extends Resource_Abstract implements Resource_Interface {

	/**
	 * Portfolio_Resource constructor.
	 *
	 * @param $resource_id
	 * @param $user_id
	 * @param array $params
	 */
	public function __construct( $resource_id, $user_id = '', $params = [] ) {
		parent::__construct( $resource_id, $user_id, $params );

		parent::set_default_params([
			'filter' => '',
			'filter_embeddable' => false,
			'page' => 1,
			'per_page' => 20,
			'sort' => 'date',
			'direction' => 'desc'
		]);

		parent::set_sort_options(
			[
				'alphabetical',
				'comments',
				'date',
				'default', // the default sort set on the portfolio
				'likes',
				'manual',
				'plays'
			]
		);

		parent::set_filtering_options([
			'embeddable'
		]);

		parent::set_name( 'portfolio', __( 'Portfolio', 'cvm_video' ) );

	}

	/**
	 * Feed can use date limit
	 *
	 * @return bool
	 */
	public function has_date_limit(){
		return true;
	}

	/**
	 * Return resource relative API endpoint
	 *
	 * @return string
	 */
	public function get_api_endpoint() {
		sprintf(
			'users/%s/portfolios/%s/videos',
			$this->user_id,
			$this->resource_id
		);
	}


}