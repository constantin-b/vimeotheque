<?php
/**
 * @author CodeFlavors
 * @project codeflavors-vimeo-video-post-lite
 */

namespace Vimeotheque\extensions;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Vimeotheque_Pro_Extension extends Extension_Abstract implements Extension_Interface {
	public function __construct(){
		parent::set_slug('vimeo-video-post/main.php');
		parent::set_name( 'Vimeotheque PRO' );
		parent::set_description( __( 'Vimeotheque PRO add-on, adds private videos imports, automatic imports and other extra functionality to better manage and automate the video import process.', 'codeflavors-vimeo-video-post-lite' ) );
	}

	public function is_pro_addon() {
		return true;
	}
}