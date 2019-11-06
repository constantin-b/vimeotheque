<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Thumbnails_Resource
 * @package Vimeotheque
 */
class Thumbnails_Resource extends Resource_Abstract implements Resource_Interface {

	/**
	 * Thumbnails_Resource constructor.
	 *
	 * @param $resource_id
	 */
	public function __construct( $resource_id ) {
		parent::__construct( $resource_id, false, false );
		parent::set_action(
			sprintf(
				'videos/%s/pictures',
				$resource_id
			)
		);

		parent::set_name( __( 'Thumbnail', 'cvm_video' ) );
	}

	/**
	 * Format the API response
	 *
	 * @param $raw_entry
	 *
	 * @return array
	 */
	public function get_formatted_entry( $raw_entry ) {
		$thumbnails = [];
		if( !isset( $raw_entry['data'][0]['sizes'] ) ){
			return $thumbnails;
		}

		foreach( $raw_entry['data'][0]['sizes'] as $thumbnail ){
			$thumbnails[ $thumbnail['width'] ] = $thumbnail['link'];
		}
		ksort( $thumbnails, SORT_NUMERIC );
		$thumbnails = array_values( $thumbnails );

		return $thumbnails;
	}

	/**
	 * Set specific fields to be retrieved from Vimeo API according to this endpoint
	 *
	 * @return array
	 */
	protected function get_fields() {
		return ['uri', 'sizes'];
	}

	/**
	 * @return bool
	 */
	public function is_single_entry(){
		return true;
	}

	/**
	 * @return bool
	 */
	public function has_automatic_import() {
		return false;
	}

}