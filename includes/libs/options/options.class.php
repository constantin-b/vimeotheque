<?php

namespace Vimeotheque\Options;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Plugin options management class
 * All plugin options should be retrieved by using this class
 * Implements Singleton pattern
 */
class Options{
	
	/**
	 * Option defaults, stored as array
	 * @var array
	 */
	private $defaults;
	/**
	 * Database option name
	 * @var string
	 */
	private $option_name;
	/**
	 * Stores options retrieved from plugin options for the first time.
	 * Every subsequent request will return this value instead of making a new query.
	 * @var array
	 */
	private $options;

	/**
	 * Private constructor. Use CVM_Plugin_Options::get_instance()
	 * @param string $option_name
	 * @param array $defaults
	 */
	public function __construct( $option_name, $defaults = [] ){
		$this->defaults = $defaults;
		$this->option_name = $option_name;
	}
	
	/**
	 * @return array $defaults
	 */
	public function get_defaults(){
		return $this->defaults;
	}

	/**
	 * @param bool $refresh
	 *
	 * @return array
	 */
	public function get_options( $refresh = false ){
		if( !$refresh && $this->options ){
			return $this->options;
		}
		
		$this->options = $this->_get_wp_option();
		foreach ( $this->defaults as $k => $v ){
			if( !isset( $this->options[ $k ] ) ){
				$this->options[ $k ] = $v;
			}
		}
		
		return $this->options;
	}

	/**
	 * Get an option
	 *
	 * @param string $name
	 *
	 * @return mixed|null
	 */
	public function get_option( $name ){
		$options = $this->get_options();
		if( isset( $options[ $name ] ) ){
			return $this->options[ $name ];
		}

		trigger_error( sprintf( 'Options name "%s" is not set.', $name ), E_USER_ERROR );

		return null;
	}

	/**
	 * Allows updating of options. 
	 * @param array $values
	 */
	public function update_options( $values ){
		$this->_update_wp_option( $values );
	}
	
	/**
	 * Wrapper for WP function that retrieves option
	 * @return array|bool
	 */
	private function _get_wp_option(){
		return get_option( $this->option_name, $this->defaults );
	}
	
	/**
	 * Wrapper for WP function that updates option
	 * @param array $values - new values to be set up in option
	 * @return boolean
	 */
	private function _update_wp_option( $values ){
		return update_option( $this->option_name , $values );
	}

	/**
	 * Returns option name
	 * @return string
	 */
	public function get_option_name(){
		return $this->option_name;
	}
}