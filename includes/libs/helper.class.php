<?php

namespace Vimeotheque;

use Vimeotheque\Options\Options;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Helper
 * @package Vimeotheque
 */
class Helper{
	/**
	 * Return the access token to Vimeo API
	 *
	 * @return bool|string
	 */
	static public function get_access_token(){
		$options = Plugin::instance()->get_options();
		$token = false;
		if( !empty( $options['oauth_secret'] ) ){
			$token = $options['oauth_secret'];
		}else if( !empty( $options['oauth_token'] ) ){
			$token = $options['oauth_token'];
		}

		/**
		 * Filter on Vimeo access token. Useful if trying to provide a default access token
		 * @var string
		 */
		return apply_filters( 'cvm_vimeo_access_token', $token );
	}

	/**
	 * Get a Video_Post object
	 *
	 * @param bool $post
	 *
	 * @return bool|int|Video_Post
	 */
	static public function get_video_post( $post = false ){
		if( $post instanceof Video_Post ){
			return $post;
		}

		return new Video_Post( $post );
	}

	/**
	 * @return Options
	 */
	static public function get_embed_options(){
		return \Vimeotheque\Plugin::instance()->get_player_options();
	}

	/**
	 * Creates from a number of given seconds a readable duration ( HH:MM:SS )
	 * @param int $seconds
	 * @return string - formatted time
	 */
	public static function human_time( $seconds ){

		$seconds = absint( $seconds );

		if( $seconds < 0 ){
			return;
		}

		$h = floor( $seconds / 3600 );
		$m = floor( $seconds % 3600 / 60 );
		$s = floor( $seconds %3600 % 60 );

		return ( ($h > 0 ? $h . ":" : "") . ( ($m < 10 ? "0" : "") . $m . ":" ) . ($s < 10 ? "0" : "") . $s);
	}

}

