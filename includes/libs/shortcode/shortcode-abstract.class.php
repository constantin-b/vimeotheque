<?php

namespace Vimeotheque\Shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Shortcode_Abstract
 * @package Vimeotheque\Shortcode
 */
class Shortcode_Abstract implements Shortcode_Interface {
	/**
	 * @var aray
	 */
	private $atts;

	/**
	 * @var string
	 */
	private $content;

	/**
	 * Name of shortcode
	 *
	 * @var string
	 */
	private $shortcode_name;

	/**
	 * Shortcode_Abstract constructor.
	 *
	 * @param $name
	 */
	public function __construct( $name = false ) {
		$this->shortcode_name = $name;
		$this->add_shortcode();
	}

	protected function set_atts( $atts ){
		$this->atts = $atts;
	}

	protected function set_content( $content ){
		$this->content = $content;
	}

	/**
	 * @return mixed
	 */
	public function get_atts() {
		return $this->atts;
	}

	/**
	 * Returns value of an attribute
	 *
	 * @param $attr
	 *
	 * @return mixed
	 */
	public function get_attr( $attr ){
		if( isset( $this->atts[ $attr ] ) ){
			return $this->atts[ $attr ];
		}

		return new \WP_Error(
			'vimeotheque-shortcode-attribute-missing',
			sprintf(
				__( 'Shortcode attribute %s is missing.', 'cvm_video' ),
				$attr
			)
		);
	}

	/**
	 * @return string
	 */
	public function get_shortcode_name() {
		return $this->shortcode_name;
	}

	/**
	 * Add the shortcode
	 */
	public function add_shortcode(){
		if( !$this->shortcode_name ){
			return;
		}

		$names = !is_array( $this->shortcode_name ) ? [ $this->shortcode_name ] : $this->shortcode_name;
		foreach( $names as $tag ){
			add_shortcode(
				$tag,
				[
					$this,
					'get_output'
				]
			);
		}
	}

	/**
	 * Returns the shortcode content inserted by the user
	 *
	 * @return mixed
	 */
	public function get_content() {
		return $this->content;
	}

	/**
	 * Generate the shortcode output
	 *
	 * @param $atts
	 * @param $content
	 */
	public function get_output( $atts, $content ) {
		_doing_it_wrong(
			__FUNCTION__,
			'Vimeotheque error: Method "get_output()" must be implemented in child class.',
			'2.0'
		);
	}
}