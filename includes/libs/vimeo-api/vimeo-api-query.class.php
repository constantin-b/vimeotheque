<?php

namespace Vimeotheque\Vimeo_Api;

use Vimeotheque\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Vimeo_Api_Query
 * @package Vimeotheque
 */
class Vimeo_Api_Query extends Vimeo {

	/**
	 * Store params
	 * @var array
	 */
	private $params;

	/**
	 * The type of resource that should be queried: album, channel, etc.
	 *
	 * @var string
	 */
	private $resource_type;

	/**
	 * The resource ID: album id, channel id, etc.
	 *
	 * @var bool|string
	 */
	private $resource_id;

	/**
	 * Vimeo user ID
	 *
	 * @var bool|string
	 */
	private $api_user_id;

	/**
	 * Stores the resource object that is currently being queried
	 *
	 * @var Resource_Interface
	 */
	private $api_resource;

	/**
	 * Vimeo_Api_Query constructor.
	 *
	 * @param $resource_type - the type of resource that should be queried (ie. album, channel, etc)
	 * @param bool $resource_id - the API resource ID (ie. channel ID, album ID, user ID, etc)
	 * @param bool $api_user_id - the Vimeo user ID that should be used when making queries for albums or portfolios
	 * @param array $args - request args
	 */
	public function __construct( $resource_type, $resource_id = false, $api_user_id = false, $args = [] ){

		$this->resource_type = $resource_type;
		$this->resource_id = $resource_id;
		$this->api_user_id = $api_user_id;
		/**
		 * Defaults must not include parameters "sort" and "direction". If not specified by
		 * the concrete implementation, the resource default will be used. This is useful when
		 * performing automatic imports which implement ordering by default and allows different
		 * order parameters to be used.
		 */
		$default = [
			'page' => 1,
			'per_page' => 20,
			'query' => '',
			'filter' => '',
			'filter_embeddable' => false,
			'filter_playable' => false,
			'links' => '',
			'password' => ''
		];
		
		$this->params = wp_parse_args( $args, $default );		
	}
	
	/**
	 * Makes a request based on the params passed on constructor
	 */
	public function request_feed(){

		$endpoint = $this->_get_endpoint();

		if( is_wp_error( $endpoint ) ){
			// send a debug message for any client listening to plugin messages
			Helper::debug_message( sprintf( __( 'Endpoint API returned an error: %s.' ), $endpoint->get_error_message() ) );
			return $endpoint;
		}

		// send a debug message for any client listening to plugin messages
		Helper::debug_message( sprintf( __( 'Making remote request to: %s.' ), $endpoint ) );
		
		$request = wp_remote_get( $endpoint, [
		    /**
		     * Request timeout filter
		     * @var int
		     */
		    'timeout' => apply_filters( 'cvm_feed_request_timeout' , 10 ),
		    'sslverify' => false,
		    'headers' => [
				'authorization' => 'bearer ' . Helper::get_access_token(),
				'accept' => parent::VERSION_STRING
		    ]
		] );
		
		$rate_limit = wp_remote_retrieve_header( $request, 'x-ratelimit-limit' );
		if( $rate_limit ){
			// send a debug message for any client listening to plugin messages
			Helper::debug_message(
				sprintf( 
					__( 'Current rate limit: %s (%s remaining). Limit reset time set at %s.' ), 
					$rate_limit, 
					wp_remote_retrieve_header( $request , 'x-ratelimit-remaining' ),
					wp_remote_retrieve_header( $request , 'x-ratelimit-reset' )
				) 
			);
		}
		
		// if Vimeo returned error, return the error
		if( 200 != wp_remote_retrieve_response_code( $request ) ){
			// get request data
			$data = json_decode( wp_remote_retrieve_body( $request ), true );

			Helper::debug_message( 'Vimeo API query returned error:' . $data['error'] );
			return parent::api_error( $data );
		}	
		
		return $request;
	}

	/**
	 * Returns endpoint URL complete with params for a given existing action.
	 *
	 * @return string|\WP_Error
	 */
	private function _get_endpoint(){

		$this->api_resource = Resource_Objects::instance()->get_resource( $this->resource_type );
		if( is_wp_error( $this->api_resource ) ){
			return $this->api_resource;
		}

		$this->api_resource->set_resource_id( $this->resource_id );
		$this->api_resource->set_user_id( $this->api_user_id );
		$this->api_resource->set_params( $this->get_api_request_params() );
		$endpoint = $this->api_resource->get_endpoint();

		if( is_wp_error( $endpoint ) ){
			return $endpoint;
		}

		return parent::API_ENDPOINT . $endpoint;
	}

	/**
	 * Returns reference of CVM_JSON_Fields
	 * @return Resource_Interface
	 */
	public function get_api_resource() {
		return $this->api_resource;
	}

	/**
	 * Returns request parameters
	 *
	 * @return array
	 */
	public function get_api_request_params(){
		$sort = isset( $this->params['order'] ) ? $this->params['order'] : '';
		$sort_option = Resource_Objects::instance()->get_sort_option( $sort );
		return array_merge( $this->params, $sort_option );
	}
}