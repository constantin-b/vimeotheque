<?php
/**
 * @author CodeFlavors
 * @project codeflavors-vimeo-video-post-lite
 */

namespace Vimeotheque\Extensions;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class User_Permissions extends Extension_Abstract implements Extension_Interface {
	public function __construct(){
		parent::set_slug( 'vimeotheque-user-permissions/index.php' );
		parent::set_name( __('Vimeotheque User Permissions', 'codeflavors-vimeo-video-post-lite') );
		parent::set_description( __("Edit user permissions by selectively allowing user roles to have access to Vimeotheque's plugin screens.", 'codeflavors-vimeo-video-post-lite') );
	}

	/**
	 * @return bool
	 */
	public function is_pro_addon() {
		return true;
	}
}