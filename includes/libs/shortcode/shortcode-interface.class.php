<?php

namespace Vimeotheque\Shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Interface Shortcode_Interface
 * @package Vimeotheque\Shortcode
 */
interface Shortcode_Interface {

	/**
	 * The shortcode output
	 *
	 * @return string
	 */
	public function get_output();
}