<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Video_Resource
 * @package Vimeotheque
 */
class Video_Resource extends Resource_Abstract implements Resource_Interface {

	/**
	 * Video_Resource constructor.
	 *
	 * @param $resource_id
	 * @param array $params
	 */
	public function __construct( $resource_id ) {
		parent::__construct( $resource_id, false, false );
		parent::set_action(
			sprintf( 'videos/%s', $resource_id )
		);

		parent::set_name( __( 'Video', 'cvm_video' ) );
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