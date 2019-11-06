<?php

namespace Vimeotheque;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Vimeotheque\Vimeo_Api\Vimeo_Api_Query;
use WP_Error;

/**
 * Class Video_Import
 * @package Vimeotheque
 */
class Video_Import{
	/**
	 * The results array containing all videos
	 *
	 * @var array
	 */
	private $results;

	/**
	 * Total number of entries returned by API query
	 *
	 * @var int
	 */
	private $total_items;

	/**
	 * Current page in API query
	 *
	 * @var int
	 */
	private $page;

	/**
	 * Reached the end of the feed
	 *
	 * @var bool
	 */
	private $end = false;

	/**
	 * Errors
	 *
	 * @var array|string|WP_Error
	 */
	private $errors;

	/**
	 * @var Vimeo_Api_Query
	 */
	private $api;

	/**
	 * Video_Import constructor.
	 *
	 * @param string $resource_type
	 * @param bool|string $resource_id
	 * @param bool|string $user_id
	 * @param array $args
	 */
	public function __construct( $resource_type, $resource_id = false, $user_id = false, $args = [] ){

		$this->api = new Vimeo_Api_Query( $resource_type, $resource_id, $user_id, $args );
		$request = $this->api->request_feed();
		// stop on error
		if( is_wp_error( $request ) ){
			$this->errors = $request;
			/**
			 * Action that will pass the error to any third party code that
			 * can log the import process.
			 */
			do_action( 'cvm_debug_request_error' ,  $this->errors );
			return;
		}
		
		$result = json_decode( $request['body'], true );
		
		/* single video entry */
		if( $this->api->get_api_resource()->is_single_entry() ){
			$this->results = $this->api->get_api_resource()->get_formatted_entry( $result );
			return;
		}

		$raw_entries = isset( $result['data'] ) ? $result['data'] : [];
		$entries =	[];
		foreach ( $raw_entries as $entry ){			
			$entries[] = $this->api->get_api_resource()->get_formatted_entry( $entry );
		}		
		
		$this->results = $entries;
		$this->end = ( !isset( $result['paging']['next'] ) || empty( $result['paging']['next'] ) );
		$this->total_items = isset( $result['total'] ) ? $result['total'] : 0;
		$this->page = isset( $result['page'] ) ? $result['page'] : 0;
	}

	/**
	 * @return array
	 */
	public function get_feed(){
		return $this->results;
	}

	/**
	 * @return int
	 */
	public function get_total_items(){
		return $this->total_items;
	}

	/**
	 * @return int
	 */
	public function get_page(){
		return $this->page;
	}

	/**
	 * @return bool
	 */
	public function has_ended(){
		return $this->end;
	}

	/**
	 * @return array|string|WP_Error
	 */
	public function get_errors(){
		return $this->errors;
	}
}