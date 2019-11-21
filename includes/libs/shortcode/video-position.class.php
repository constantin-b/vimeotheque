<?php

namespace Vimeotheque\Shortcode;

use Vimeotheque\Helper;
use function Vimeotheque\get_video_embed_html;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Video_Position
 * @package Vimeotheque\Shortcode
 */
class Video_Position extends Shortcode_Abstract implements Shortcode_Interface {

	/**
	 * @return string|void
	 */
	public function get_output(){
		if( !is_singular() ){
			return;
		}
		global $post;
		$_post = Helper::get_video_post( $post );

		if( !$_post->is_video() ){
			return;
		}

		return get_video_embed_html( $post, false );

	}

}