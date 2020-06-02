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
	 * @var string
	 */
	private $name;

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
	 * @param string $resource_id
	 */
	public function set_resource_id( $resource_id ) {
		$this->resource_id = $resource_id;
	}

	/**
	 * @param bool|string $user_id
	 */
	public function set_user_id( $user_id ) {
		$this->user_id = $user_id;
	}

	/**
	 * @param array $params
	 */
	public function set_params( $params ) {
		$this->params = $params;
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

		if( !$this->get_api_endpoint() ){
			return new \WP_Error(
				'vimeotheque-vimeo-api-resource-endpoint-error',
				'Plugin error occured! Method ' . get_class( $this ) . '::get_api_endpoint() returned an empty response.'
			);
		}

		return $this->get_api_endpoint() . ( $_params['fields'] ? '?' . http_build_query( $_params ) : '' );
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
	 * @param string $name
	 * @param string $output_name
	 */
	protected function set_name( $name, $output_name ){
		$this->name = $name;
		$this->output_name = $output_name;
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
	 * Feed can be proccessed by automatic import.
	 * Return true in concrete implementation if it can be processed.
	 *
	 * Can be overridden in concrete class
	 *
	 * @return bool
	 */
	public function has_automatic_import() {
		return true;
	}

	/**
	 * After processing the entire feed, only new videos can be imported.
	 * Feed will be parsed once and all future queries will only check for new videos.
	 * Return true in concrete implementation if this applies to feed.
	 *
	 * @return bool
	 */
	public function can_import_new_videos() {
		return false;
	}

	/**
	 * Feed can have a date limit when processing imports.
	 * When true, this signals that the feed can be imported
	 * up to a certain given date in past beyond which videos
	 * will be ignored from importing
	 *
	 * @return bool
	 */
	public function has_date_limit(){
		return false;
	}

	/**
	 * Return true in concrete implementation if feed requires authorization to work (ie. folders feed type).
	 *
	 * @return bool
	 */
	public function requires_authorization(){
		return false;
	}

	/**
	 * @return array
	 */
	public function get_default_params() {
		return $this->default_params;
	}

	/**
	 * @return string
	 */
	public function get_output_name(){
		return $this->output_name;
	}

	/**
	 * Return ID name
	 *
	 * @return string
	 */
	public function get_name(){
		return $this->name;
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

	/**
	 * Return resource relative API endpoint
	 *
	 * @return string
	 */
	public function get_api_endpoint() {
		_doing_it_wrong( __FUNCTION__, 'Method must be implemented in child class' );
	}

	/**
	 * Used to retrieve whether feed needs Vimeo user ID to make queries
	 *
	 * @return bool
	 */
	public function requires_user_id() {
		return false;
	}

	/**
	 * Get field label for Vimeo user ID
	 *
	 * @return bool|string
	 */
	public function label_user_id() {
		return false;
	}

	/**
	 * Get placeholder for field Vimeo user ID
	 *
	 * @return bool|string
	 */
	public function placeholder_user_id() {
		return false;
	}

	/**
	 * Most resources allow search within the returned results.
	 * By default, abstract class will assume this is allowed.
	 * Override in child implementation for feeds that do not support results searching
	 *
	 * @return bool
	 */
	public function can_search_results() {
		return true;
	}
}