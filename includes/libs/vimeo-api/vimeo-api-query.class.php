<?php

namespace Vimeotheque\Vimeo_Api;

use Vimeotheque\Helper;
use function Vimeotheque\_cvm_debug_message;

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
			_cvm_debug_message( sprintf( __( 'Endpoint API returned an error: %s.' ), $endpoint->get_error_message() ) );
			return $endpoint;
		}

		// send a debug message for any client listening to plugin messages
		_cvm_debug_message( sprintf( __( 'Making remote request to: %s.' ), $endpoint ) );
		
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
			_cvm_debug_message( 
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

			_cvm_debug_message( 'Vimeo API query returned error:' . $data['error'] );
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
		
		switch( $this->resource_type ){
			case 'album':
				$this->api_resource = new Album_Resource(
					$this->resource_id,
					$this->api_user_id,
					$this->get_api_request_params()
				);
			break;
			case 'search':
				$params = $this->get_api_request_params();
				// search query comes in resource ID
				$params['query'] = $this->resource_id;
				$this->api_resource = new Search_Resource(
					$params
				);
			break;
			case 'user':
				$this->api_resource = new User_Resource(
					$this->resource_id,
					$this->get_api_request_params()
				);
			break;
			case 'category':
				$this->api_resource = new Category_Resource(
					$this->resource_id,
					$this->get_api_request_params()
				);
			break;
			case 'channel':
				$this->api_resource = new Channel_Resource(
					$this->resource_id,
					$this->get_api_request_params()
				);
			break;
			case 'portfolio':
				$this->api_resource = new Portfolio_Resource(
					$this->resource_id,
					$this->api_user_id,
					$this->get_api_request_params()
				);
			break;
			case 'ondemand_videos':
				$this->api_resource = new Ondemand_Resource(
					$this->resource_id,
					$this->get_api_request_params()
				);
			break;
			case 'group':
				$this->api_resource = new Group_Resource(
					$this->resource_id,
					$this->get_api_request_params()
				);
			break;
			case 'video':
				$this->api_resource = new Video_Resource(
					$this->resource_id
				);
			break;
			case 'thumbnails':
				$this->api_resource = new Thumbnails_Resource(
					$this->resource_id
				);
			break;
		}

		$endpoint = $this->api_resource->get_endpoint();

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
		$params = $this->params;

		// Older implementation had a different ordering that seems more useful than current sorting.
		// It will be kept until enough requests from users are received to be updated.
		$map = [
			'alphabetical' => [ 'sort' => 'alphabetical', 'direction' => 'desc' ],
			'duration' => [ 'sort' => 'duration', 'direction' => 'desc' ],

			'new' 		=> [ 'sort' => 'date', 'direction' => 'desc' ],
			'old' 		=> [ 'sort' => 'date', 'direction' => 'asc' ],
			'played' 	=> [ 'sort' => 'plays', 'direction' => 'desc' ],
			'likes' 	=> [ 'sort' => 'likes', 'direction' => 'desc' ],
			'comments' 	=> [ 'sort' => 'comments', 'direction' => 'desc' ],
			'relevant' 	=> [ 'sort' => 'relevant', 'direction' => 'desc' ]
		];

		if( isset( $params['order'] ) && array_key_exists( $params['order'] , $map ) ){
			$params = array_merge( $params, $map[ $params['order'] ] );
		}

		return $params;
	}
}