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
 * Class Extensions
 * @package Vimeotheque\Extensions
 * @since 2.1
 */
class Extensions {
	/**
	 * Holds all available extensions
	 *
	 * @var Extension_Interface[]
	 */
	private $extensions = [];

	/**
	 * Extensions constructor. Loads all available plugin extensions.
	 */
	public function __construct(){

		$this->register_extension( new Debug_Extension() );
		$this->register_extension( new Vimeotheque_Pro_Extension() );
		$this->register_extension( new Themes_Extensions() );
		$this->register_extension( new Push_Changes() );
		$this->register_extension( new User_Permissions() );

	}

	/**
	 * Registers and stores an extension.
	 *
	 * @param Extension_Interface $extension
	 */
	public function register_extension( Extension_Interface $extension ){
		$this->extensions[ $extension->get_slug() ] = $extension;
	}

	/**
	 * @return Extension_Interface[]
	 */
	public function get_registered_extensions() {
		return $this->extensions;
	}

	/**
	 * @param $slug
	 *
	 * @return false|Extension_Interface
	 */
	public function get_extension( $slug ){
		if( isset( $this->extensions[ $slug ] ) ){
			return $this->extensions[ $slug ];
		}

		return false;
	}
}