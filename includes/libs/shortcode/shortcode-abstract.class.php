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
	 * Shortcode_Abstract constructor.
	 *
	 * @param $atts
	 * @param $content
	 */
	public function __construct( $atts, $content ) {
		$this->atts = $atts;
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
	}

	/**
	 * @return mixed
	 */
	public function get_content() {
		return $this->content;
	}

	/**
	 * Generate the shortcode output
	 */
	public function get_output() {
		// TODO: Implement get_output() method.
	}
}