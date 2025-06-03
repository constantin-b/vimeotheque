<?php
/**
 * @author  CodeFlavors
 * @project vimeotheque-series
 */

namespace Vimeotheque_Series\Admin\Metabox;

interface Metabox_Interface {

	/**
	 * Initiate meta box.
	 *
	 * @param string $id
	 * @param string $name
	 * @param string $screen
	 * @param string $context
	 */
	public function __construct( string $id, string $name, string $screen, string $context = 'normal' );

	/**
	 * The metabox content.
	 *
	 * @return mixed
	 */
	public function content();

	/**
	 * Initiates the meta box.
	 *
	 * @return mixed
	 */
	public function initiate_metabox();

	/**
	 * Get the meta box ID.
	 *
	 * @return string
	 */
	public function get_id(): string;

	/**
	 * Get the meta box name.
	 *
	 * @return string
	 */
	public function get_name(): string;

	/**
	 * Get the meta box screen.
	 *
	 * @return string
	 */
	public function get_screen(): string;

	/**
	 * Get the meta box position.
	 *
	 * @return string
	 */
	public function get_context(): string;
}
