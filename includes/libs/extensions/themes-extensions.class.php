<?php
/**
 * @author CodeFlavors
 * @project codeflavors-vimeo-video-post-lite
 */

namespace Vimeotheque\Extensions;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Themes_Extensions extends Extension_Abstract implements Extension_Interface {
	public function __construct(){
		parent::set_slug( 'vimeotheque-themes-compatibility/index.php' );
		parent::set_name( __('Vimeotheque Themes Compatibility', 'codeflavors-vimeo-video-post-lite') );
		parent::set_description( __('Implements compatibility with various WordPress Video Themes.', 'codeflavors-vimeo-video-post-lite') );
	}

	/**
	 * @return bool
	 */
	public function is_pro_addon() {
		return true;
	}

	public function get_file_id(){
		return 4;
	}
}