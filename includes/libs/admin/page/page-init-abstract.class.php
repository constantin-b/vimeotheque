<?php

namespace Vimeotheque\Admin\Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Vimeotheque\Post_Type;

/**
 * Class Page_Init_Abstract
 * @package Vimeotheque
 */
abstract class Page_Init_Abstract{
	/**
	 * @var Post_Type
	 */
	protected $cpt;

	/**
	 * Page_Init_Abstract constructor.
	 *
	 * @param Post_Type $object
	 */
	public function __construct( Post_Type $object ){
		$this->cpt = $object;
	}
	
}