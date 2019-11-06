<?php

namespace Vimeotheque\Admin\Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class About_Page
 * @package Vimeotheque\Admin
 */
class About_Page extends Page_Init_Abstract implements Page_Interface{

	/**
	 * Page output callback
	 */
	public function get_html() {
		\Vimeotheque\cvm_enqueue_player();
		include VIMEOTHEQUE_PATH . 'views/about.php';
	}

	/**
	 * Page on_load event callback
	 */
	public function on_load() {
		// Empty method
	}
}