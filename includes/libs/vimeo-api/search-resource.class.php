<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Search_Resource
 *
 * @package Vimeotheque
 */
class Search_Resource extends Resource_Abstract implements Resource_Interface {
	/**
	 * Search_Resource constructor.
	 *
	 * @param array $params
	 */
	public function __construct( $params = [] ) {
		parent::__construct( false, false, $params );
		parent::set_action( 'videos' );
		parent::set_default_params([
			'direction' => 'desc',
			'filter' => '',
			'links' => '',
			'page' => 1,
			'per_page' => 20,
			'query' => '',
			'sort' => 'date',
			'uris' => ''
		]);

		parent::set_sort_options(
			[
				'alphabetical',
				'comments',
				'date',
				'duration',
				'likes',
				'plays',
				'relevant'
			]
		);

		parent::set_filtering_options([
			'CC',
			'CC-BY',
			'CC-BY-NC',
			'CC-BY-NC-ND',
			'CC-BY-NC-SA',
			'CC-BY-ND',
			'CC-BY-SA',
			'CC0',
			'categories',
			'duration',
			'in-progress',
			'minimum_likes',
			'trending',
			'upload_date'
		]);

		parent::set_name( __( 'Search', 'cvm_video' ) );

	}

	/**
	 * Does not have automatic import
	 *
	 * @return boolean
	 */
	public function has_automatic_import() {
		return false;
	}

}