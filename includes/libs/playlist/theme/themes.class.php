<?php

namespace Vimeotheque\Playlist\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Themes {

	/**
	 * @var Theme[]
	 */
	private $themes = [];

	/**
	 * Themes constructor.
	 *
	 * @param Theme $theme
	 */
	public function __construct( Theme $theme ) {
		$this->register_theme( $theme );
	}

	/**
	 * Register a new theme
	 *
	 * @param Theme $theme
	 */
	public function register_theme( Theme $theme ){
		$this->themes[ $theme->get_theme_name() ] = $theme;
	}

	/**
	 * Return all registered themes
	 *
	 * @return Theme[]
	 */
	public function get_themes(){
		return $this->themes;
	}

	/**
	 * @param $theme
	 *
	 * @return Theme
	 */
	public function get_theme( $theme ){
		if( isset( $this->themes[ $theme ] ) ){
			return $this->themes[ $theme ];
		}
	}

}