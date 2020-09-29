<?php
/**
 * @author CodeFlavors
 * @project codeflavors-vimeo-video-post-lite
 */

namespace Vimeotheque\Extensions;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class Debug_Extension
 * @package Vimeotheque\Extensions
 * @since 2.1
 */
class Debug_Extension extends Extension_Abstract implements Extension_Interface {
	/**
	 * Debug_Extension constructor.
	 */
	public function __construct(){
		parent::set_slug( 'vimeotheque-debug/index.php' );
		parent::set_name( 'Vimeotheque Debug', 'codeflavors-vimeo-video-post-lite' );
		parent::set_description( __('Creates and outputs an activity log which stores the debug messages emitted by Vimeotheque when import actions are taken.' , 'codeflavors-vimeo-video-post-lite') );
	}
}