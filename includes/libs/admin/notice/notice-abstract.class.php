<?php

namespace Vimeotheque\Admin\Notice;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Vimeotheque\Plugin;
use Vimeotheque\Post_Type;

/**
 * Class Notice_Abstract
 * @package Vimeotheque
 */
abstract class Notice_Abstract{
	/**
	 * @var Post_Type
	 */
	private $cpt;

	/**
	 * Notice_Abstract constructor.
	 */
	public function __construct() {
		$this->cpt = Plugin::instance()->get_cpt();
	}

	/**
	 * Dismiss the notice
	 */
	public function dismiss_notice(){
		// Override in concrete implementation
	}

	/**
	 * @return Post_Type
	 */
	public function get_cpt(){
		return $this->cpt;
	}
}