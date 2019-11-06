<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Interface Resource_Interface
 * @package Vimeotheque
 */
interface Resource_Interface{
	/**
	 * Must return the endpoint URI with all neccessary parameters
	 *
	 * @return string
	 */
	public function get_endpoint();

	/**
	 * Returns any additional optional fields that should be set from the
	 * raw video entry returned by Vimeo API
	 *
	 * @return array
	 */
	public function get_optional_fields();

	/**
	 * @param $raw_entry
	 *
	 * @return array
	 */
	public function get_formatted_entry( $raw_entry );

	/**
	 * When used for a single video or other type of single entry, should return true
	 *
	 * @return bool
	 */
	public function is_single_entry();

	/**
	 * Resource can be used in automatic import
	 *
	 * @return bool
	 */
	public function has_automatic_import();

	/**
	 * Resource can skip reiteration of feed and import only newly added videos
	 *
	 * @return bool
	 */
	public function can_import_new_videos();

	/**
	 * Feed can have a date limit set for automatic import that it can use
	 * to stop importing if videos are older than a given date
	 *
	 * @return boolean
	 */
	public function has_date_limit();

	/**
	 * Returns the resource name for the page output
	 *
	 * @return string
	 */
	public function get_name();
}