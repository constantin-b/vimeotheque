<?php

namespace Vimeotheque\Admin\Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Admin page Interface
 * @author CodeFlavors
 *
 */
interface Page_Interface{
	/**
	 * Page output callback
	 */
	public function get_html();
	/**
	 * Page on_load event callback
	 */
	public function on_load();

}
