<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Resources_Objects
 * @package Vimeotheque\Admin
 */
class Resource_Objects{
	/**
	 * @var Resource_Abstract[]
	 */
	private $resources = [];

	/**
	 * Holds the plugin instance.
	 *
	 * @var Resource_Objects
	 */
	private static $instance = null;

	/**
	 * Clone.
	 *
	 * Disable class cloning and throw an error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object. Therefore, we don't want the object to be cloned.
	 *
	 * @access public
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'cvm_video' ), '2.0' );
	}

	/**
	 * Wakeup.
	 *
	 * Disable unserializing of the class.
	 *
	 * @access public
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'cvm_video' ), '2.0' );
	}

	/**
	 * Instance.
	 *
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @access public
	 * @static
	 *
	 * @return Resource_Objects
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Resources_Objects constructor.
	 */
	private function __construct() {
		$this->register_resource( new Album_Resource( false ) );
		$this->register_resource( new Category_Resource( false ) );
		$this->register_resource( new Channel_Resource( false ) );
		$this->register_resource( new Group_Resource( false ) );
		$this->register_resource( new Portfolio_Resource( false ) );
		$this->register_resource( new Search_Resource( false ) );
		$this->register_resource( new Thumbnails_Resource( false ) );
		$this->register_resource( new User_Resource( false ) );
		$this->register_resource( new Video_Resource( false ) );
	}

	/**
	 * @param Resource_Interface $resource
	 */
	public function register_resource( Resource_Interface $resource ){
		$this->resources[ $resource->get_name() ] = $resource;
	}

	/**
	 * @return Resource_Abstract[]
	 */
	public function get_resources(){
		return $this->resources;
	}

	/**
	 * @param $name
	 *
	 * @return Resource_Abstract|\WP_Error
	 */
	public function get_resource( $name ){
		if( !isset( $this->resources[ $name ] ) ){
			return new \WP_Error(
				'vimeotheque-api-query-resource-unknown',
				sprintf( __( 'Resource %s is not registered.', 'cvm_video' ), $name )
			);
		}

		return $this->resources[ $name ];
	}
}