<?php

namespace Vimeotheque\Shortcode;

use Vimeotheque\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Shortcode_Factory
 * @package Vimeotheque
 */
class Shortcode_Factory {
	/**
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Shortcode_Factory constructor.
	 */
	public function __construct( Plugin $plugin ) {

		$this->plugin = $plugin;
		// legacy shortcodes
		add_shortcode( 'cvm_video', [ $this, 'process_shortcode' ] );
		add_shortcode( 'cvm_playlist', [ $this, 'process_shortcode' ] );
		add_shortcode( 'cvm_video_embed', [ $this, 'process_shortcode' ] );
		// new style shortcodes
		add_shortcode( 'vimeotheque_video', [ $this, 'process_shortcode' ] );
		add_shortcode( 'vimeotheque_video_position', [ $this, 'process_shortcode' ] );
		add_shortcode( 'vimeotheque_playlist', [ $this, 'process_shortcode' ] );
	}

	/**
	 * @param $atts
	 * @param $content
	 * @param $shortcode_name
	 *
	 * @return string
	 */
	public function process_shortcode( $atts, $content, $shortcode_name ){
		if( 'cvm_video' == $shortcode_name ){
			$shortcode_name = 'vimeotheque_video';
		}
		if( 'cvm_playlist' == $shortcode_name ){
			$shortcode_name = 'vimeotheque_playlist';
		}
		if( 'cvm_video_embed' == $shortcode_name ){
			$shortcode_name = 'vimeotheque_video_position';
		}

		switch( $shortcode_name ){
			case 'vimeotheque_video':
				$shortcode_obj = new Video( $atts, $content );
			break;
			case 'vimeotheque_playlist':
				$shortcode_obj = new Playlist( $atts, $content );
			break;
			case 'vimeotheque_video_position':
				$shortcode_obj = new Video_Position( $atts, $content );
			break;
			default:
				$shortcode_obj = null;
				trigger_error( sprintf( "Shortcode '%s' doesn't exist.", $shortcode_name ), E_USER_NOTICE );
			break;
		}

		if( $shortcode_obj ) {
			return $shortcode_obj->get_output();
		}
	}


}