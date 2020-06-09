<?php

namespace Vimeotheque\Admin\Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Automatic_Import_Page
 * @package Vimeotheque\Admin
 */
class Automatic_Import_Page extends Page_Abstract implements Page_Interface{

	/**
	 * (non-PHPdoc)
	 * @see Page_Interface::get_html()
	 */
	public function get_html(){
?>
<div class="wrap">
    <h1><?php _e( 'Automate your imports', 'cvm_video' );?></h1>
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