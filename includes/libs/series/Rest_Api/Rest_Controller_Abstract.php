<?php
/**
 * @author  CodeFlavors
 * @project vimeotheque-series
 */

namespace Vimeotheque_Series\Rest_Api;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Rest_Controller_Abstract {
	/**
	 * The Rest API namespace.
	 *
	 * @var string
	 */
	private $namespace;

	/**
	 * The Rest API base.
	 *
	 * @var string
	 */
	private $rest_base;

	/**
	 * @return string
	 */
	public function get_namespace(): string {
		return $this->namespace;
	}

	/**
	 * @param string $namespace
	 */
	public function set_namespace( string $namespace ) {
		$this->namespace = $namespace;
	}

	/**
	 * @return string
	 */
	public function get_rest_base(): string {
		return $this->rest_base;
	}

	/**
	 * @param string $rest_base
	 */
	public function set_rest_base( string $rest_base ) {
		$this->rest_base = $rest_base;
	}

	/**
	 * Returns controller complete Rest API route.
	 *
	 * @return string
	 */
	public function get_route(): string {
		return $this->get_namespace() . $this->get_rest_base();
	}
}
