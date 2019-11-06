<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Ondemand_Resource
 * @package Vimeotheque
 */
class Ondemand_Resource extends Resource_Abstract implements Resource_Interface {

	/**
	 * Ondemand_Resource constructor.
	 *
	 * @param $resource_id
	 * @param array $params
	 */
	public function __construct( $resource_id, $params = [] ) {
		parent::__construct( $resource_id, false, $params );
		parent::set_action(
			sprintf( 'ondemand/pages/%s/videos', $resource_id )
		);
		parent::set_default_params([
			'direction' => 'desc',
			'filter' => '',
			'page' => 1,
			'per_page' => 20,
			'sort' => 'date'
		]);

		parent::set_sort_options(
			[
				'date',
				'default',
				'episode',
				'manual',
				'name',
				'purchase_time',
				'release_date'
			]
		);

		parent::set_filtering_options([
			'all',
			'buy',
			'expiring_soon',
			'extra',
			'main',
			'main.viewable',
			'rent',
			'trailer',
			'unwatched',
			'viewable',
			'watched'
		]);

		parent::set_name( __( 'Vimeo On Demand', 'cvm_video' ) );

	}

	/**
	 * @return bool
	 */
	public function is_single_entry(){
		return true;
	}

	/**
	 * No automatic import
	 *
	 * @return bool
	 */
	public function has_automatic_import() {
		return false;
	}

}