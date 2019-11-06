<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Resource_Abstract
 * @package Vimeotheque\Vimeo_Api
 */
class Resource_Abstract implements Resource_Interface {
	/**
	 * The resource ID (album ID, channel ID, ...)
	 *
	 * @var string
	 */
	protected $resource_id;

	/**
	 * Vimeo user ID
	 *
	 * @var bool|string
	 */
	protected $user_id;

	/**
	 * Request parameters
	 *
	 * @var array
	 */
	protected $params;

	/**
	 * The action URI
	 *
	 * @var string
	 */
	protected $action_uri;

	/**
	 * Default params
	 *
	 * @var array
	 */
	protected $default_params = [];

	/**
	 * Stores extra fields required by concrete implementations
	 *
	 * @var array
	 */
	protected $request_fields = [];

	/**
	 * Set sorting options
	 *
	 * @var array
	 */
	private $sort_options = [];

	/**
	 * Results filtering options
	 *
	 * @var array
	 */
	private $filtering_options = [];

	/**
	 * Output name for the resource
	 *
	 * @var string
	 */
	private $output_name = '';

	/**
	 * Resource_Abstract constructor.
	 *
	 * @param $resource_id
	 * @param bool $user_id
	 * @param array $params
	 */
	public function __construct( $resource_id, $user_id = false, $params = [] ) {
		$this->resource_id = $resource_id;
		$this->user_id = $user_id;
		$this->params = $params;
	}

	/**
	 * Set the action URI
	 *
	 * @param $action_uri
	 */
	protected function set_action( $action_uri ){
		$this->action_uri = $action_uri;
	}

	/**
	 * Set default params.
	 *
	 * @param $params
	 */
	protected function set_default_params( $params ){
		$this->default_params = $params;
	}

	/**
	 * Set results sorting options
	 *
	 * @param $sort_options
	 */
	protected function set_sort_options( $sort_options ){
		$this->sort_options = $sort_options;
	}

	/**
	 * @param $filtering_options
	 */
	protected function set_filtering_options( $filtering_options ){
		$this->filtering_options = $filtering_options;
	}

	/**
	 * Return endpoint URI
	 *
	 * @return string|\WP_Error
	 */
	public function get_endpoint(){
		$_params = $this->default_params;
		foreach ( $_params as $k => $v ){
			if( isset( $this->params[ $k ] ) ){
				$_params[ $k ] = $this->params[ $k ];
			}

			if( is_string( $_params[ $k ] ) && empty( $_params[ $k ] ) ){
				unset( $_params[ $k ] );
			}
		}

		$_params['fields'] = implode( ',', $this->get_fields() );

		if( isset( $_params['sort'] ) && !in_array( $_params['sort'], $this->sort_options ) ){
			return new \WP_Error(
				'cvm-unknown-sort-options',
				sprintf(
					__('Sort option "%s" is not available in feed resource.'),
					$this->params['sort']
				)
			);
		}

		// unset query parameter to avoid empty answers from the API
		if( isset( $_params['query'] ) && empty( $_params['query'] ) ){
			unset( $_params['query'] );
		}

		/**
		 * Filter API query params
		 *
		 * @param array $_params - request parameters
		 */
		$_params = apply_filters( 'cvm_vimeo_api_query_params', $_params );


		return $this->action_uri . '?' . http_build_query( $_params );
	}

	/**
	 *
	 * For all available fields see: https://developer.vimeo.com/api/reference/responses/video
	 *
	 * @return array
	 */
	protected function get_fields(){

		$fields = [
			'categories',
			'content_rating',
			'created_time',
			'description',
			'duration',
			'height',
			'link',
			'modified_time',
			'name',
			'pictures',
			'privacy',
			'release_time',
			'stats',
			'tags',
			'type',
			'uri',
			'user',
			'width',
			'metadata.connections.comments.total',
			'metadata.connections.likes.total'
		];

		$optional = $this->get_optional_fields();
		$f = array_unique(
				array_merge(
					$fields,
					$this->request_fields,
					$optional
				),
				SORT_STRING );

		return $f;
	}

	/**
	 * Set resource output name
	 *
	 * @param $name
	 */
	protected function set_name( $name ){
		$this->output_name = $name;
	}

	/**
	 * Allows concrete implementations to add extra fields
	 *
	 * @param array $fields
	 *
	 * @return array
	 */
	protected function set_fields( $fields = [] ){
		return $this->request_fields = $fields;
	}

	/**
	 * Optionl additional fields that can be set by third party scripts
	 *
	 * @return array
	 */
	public function get_optional_fields(){
		/**
		 * Filter that allows setup of additional JSON fields in Vimeo API requests
		 *
		 * @see https://developer.vimeo.com/api/reference/responses/video
		 * @param array $fields
		 */
		return apply_filters( 'cvm_vimeo_api_request_extra_json_fields', [] );
	}

	/**
	 * @param $raw_entry
	 *
	 * @return array
	 */
	public function get_formatted_entry( $raw_entry ){
		$format = new Entry_Format( $raw_entry, $this );
		$formatted = $format->get_entry();

		return $formatted;
	}

	/**
	 * Can be overridden in concrete classes
	 *
	 * @return bool
	 */
	public function is_single_entry(){
		return false;
	}

	/**
	 * Can be overridden in concrete class
	 *
	 * @return bool
	 */
	public function has_automatic_import() {
		return true;
	}

	/**
	 * @return bool
	 */
	public function can_import_new_videos() {
		return false;
	}

	/**
	 * Feed can have a date limit
	 *
	 * @return bool
	 */
	public function has_date_limit(){
		return false;
	}

	/**
	 * @return array
	 */
	public function get_default_params() {
		return $this->default_params;
	}

	public function get_name(){
		return $this->output_name;
	}

	/**
	 * @return array
	 */
	public function get_sort_options() {
		return $this->sort_options;
	}

	/**
	 * @return array
	 */
	public function get_filtering_options() {
		return $this->filtering_options;
	}
}