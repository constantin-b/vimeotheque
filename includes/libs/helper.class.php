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
	 * @param $post
	 *
	 * @return array|bool
	 */
	static public function get_post_player_size( $post ){
		$_post = self::get_video_post( $post );
		if( $_post->is_video() ){
			$options = $_post->get_embed_options();
			$height = self::calculate_player_height( $options['aspect_ratio'], $options['width'] );
			return [
				'width' => $options['width'],
				'height' => $height
			];
		}

		return false;
	}

	/**
	 * Output video parameters as data-* attributes
	 *
	 * @param $attributes
	 * @param bool $echo
	 *
	 * @return string
	 */
	public static function data_attributes( $attributes, $echo = false ){
		$result = [];
		// these variables are not needed by js and will be skipped
		$exclude = [ 'video_position', 'aspect_override' ];
		// loop attributes
		foreach( $attributes as $key=>$value ){
			// skip values from $exclude
			if( in_array( $key, $exclude ) ){
				continue;
			}
			$result[] = sprintf( 'data-%s="%s"', $key, $value );
		}
		if( $echo ){
			echo implode(' ', $result);
		}else{
			return implode(' ', $result);
		}
	}

	/**
	 * Adds video player script to page
	 *
	 * @param bool $include_js
	 * @param bool $js_dependency
	 * @param bool $css_dependency
	 *
	 * @return array
	 */
	public static function enqueue_player( $include_js = true,  $js_dependency = false, $css_dependency = false  ){
		$handles = [
			'js' => false,
			'css' => 'cvm-video-player'
		];

		if( $include_js ) {
			$js_dependency = $js_dependency ? ['jquery', $js_dependency] : ['jquery'];
			wp_enqueue_script(
				'cvm-video-player',
				VIMEOTHEQUE_URL . 'assets/front-end/js/video-player' . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.dev' : '' ) . '.js',
				$js_dependency,
				'1.0'
			);
			$handles['js'] = 'cvm-video-player';
		}

		$css_dependency = $css_dependency ? [ $css_dependency ] : false;
		wp_enqueue_style(
			'cvm-video-player',
			VIMEOTHEQUE_URL . 'assets/front-end/css/video-player.css',
			$css_dependency
		);

		return $handles;
	}

	/**
	 * Calculate player height from given aspect ratio and width
	 *
	 * @param string $aspect_ratio
	 * @param int $width
	 * @param bool $ratio - a given ratio; will override aspect ratio if set
	 *
	 * @return float|int
	 */
	public static function calculate_player_height( $aspect_ratio, $width, $ratio =  false ){
		$width = absint($width);

		if( is_numeric( $ratio ) && $ratio > 0 ){
			return floor( $width / $ratio );
		}

		$height = 0;
		switch( $aspect_ratio ){
			case '4x3':
				$height = floor( ($width * 3) / 4 );
				break;
			case '16x9':
			default:
				$height = floor( ($width * 9) / 16 );
				break;
			case '2.35x1':
				$height = floor( $width / 2.35 );
				break;
		}

		return $height;
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

	/**
	 * @param $name
	 * @param bool $type
	 * @param bool $sanitize
	 *
	 * @return bool|mixed
	 */
	static public function get_var( $name, $type = false, $sanitize = false ) {
		$result = false;

		if( !$type ){
			$result = isset( $_GET[ $name ] ) || isset( $_POST[ $name ] ) ? $_REQUEST[ $name ] : false;
		}

		$isset = 'POST' == $type ? isset( $_POST[ $name ] ) : isset( $_GET[ $name ] );
		if( $isset ){
			$result = $_REQUEST[ $name ];
		}

		if( $sanitize ){
			$result = call_user_func( $sanitize, $result );
		}

		return $result;
	}

}

