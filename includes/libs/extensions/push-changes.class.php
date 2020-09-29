<?php
/**
 * @author CodeFlavors
 * @project codeflavors-vimeo-video-post-lite
 */

namespace Vimeotheque\Extensions;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Push_Changes extends Extension_Abstract implements Extension_Interface {
	public function __construct(){
		parent::set_slug( 'vimeotheque-push-changes/index.php' );
		parent::set_name( __('Vimeotheque Push Changes', 'codeflavors-vimeo-video-post-lite') );
		parent::set_description( __('Edit your Vimeo videos directly from WordPress video edit screen.', 'codeflavors-vimeo-video-post-lite') );
	}

	/**
	 * @return bool
	 */
	public function is_pro_addon() {
		return true;
	}
}