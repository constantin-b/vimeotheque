<?php

namespace Vimeotheque;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Helper
 * @package Vimeotheque
 */
class Helper{
	/**
	 * @param $action
	 * @param $callback
	 * @param int $priority
	 * @param int $args
	 */
	static public function add_action( $action, $callback, $priority = 10, $args = 1 ){
		add_action( $action, $callback, $priority, $args );
	}

	/**
	 * @param $action
	 * @param $callback
	 * @param int $priority
	 * @param int $args
	 */
	static public function add_filter( $action, $callback, $priority = 10, $args = 1 ){
		add_filter( $action, $callback, $priority, $args );
	}

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
		return cvm_load_player_settings();
	}

}

