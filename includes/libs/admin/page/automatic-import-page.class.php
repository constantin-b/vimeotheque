<?php

namespace Vimeotheque\Admin\Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Vimeotheque\Post_Type;

/**
 * Class Automatic_Import_Page
 * @package Vimeotheque\Admin
 */
class Automatic_Import_Page extends Page_Init_Abstract implements Page_Interface{
	/**
	 * @param Post_Type $object
	 */
	public function __construct( Post_Type $object ){
		parent::__construct($object);
	}

	/**
	 * (non-PHPdoc)
	 * @see Page_Interface::get_html()
	 */
	public function get_html(){
?>
<div class="wrap">
    <h1><?php _e( 'Automatic import', 'cvm_video' );?></h1>
    <p><?php _e( 'Feature available in PRO version', 'cvm_video' );?></p>
</div>
<?php
	}

	/**
	 * (non-PHPdoc)
	 * @see Page_Interface::on_load()
	 */
	public function on_load(){

	}
}