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
		return apply_filters( 'vimeotheque\vimeo_api\access_token', $token );
	}

	/**
	 * Returns user agent for remote requests
	 *
	 * @return string
	 */
	static public function request_user_agent(){
		return 'WordPress/' . get_bloginfo( 'version' ) . '; ' . get_bloginfo( 'url' );
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
			wp_register_script(
				'vimeo-video-player-sdk',
				'https://player.vimeo.com/api/player.js',
				false,
				'2.11'
			);

			$js_dependency = $js_dependency ? ['jquery', 'vimeo-video-player-sdk', $js_dependency] : ['jquery', 'vimeo-video-player-sdk'];

			wp_enqueue_script(
				'cvm-video-player',
				VIMEOTHEQUE_URL . 'assets/back-end/js/apps/player/app.build.js',
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

		$override = Plugin::instance()->get_embed_options_obj()
		                              ->get_option('aspect_override');

		if( !is_wp_error( $override ) && $override && is_numeric( $ratio ) && $ratio > 0 ){
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
	 * Return plugin embedding options
	 *
	 * @param array $_options
	 *
	 * @return array
	 */
	static public function get_embed_options( array $_options = [] ){
		$embed_options	= Plugin::instance()->get_embed_options();

		if( $_options ){
			foreach( $_options as $k => $v ){
				if( isset( $_options[ $k ] ) ){
					$embed_options[ $k ] = $_options[ $k ];
				}
			}
		}

		return $embed_options;
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

	/**
	 * @param $post
	 * @param array $options
	 * @param bool $echo
	 *
	 * @return string|void
	 */
	public static function embed_video( $post, $options = [], $echo = true ){
		$_post = self::get_video_post( $post );
		if( !$_post->is_video() ){
			return;
		}

		$player = new Player\Player( $_post, $options );
		return $player->get_output( $echo );
	}

	/**
	 * Determine if a potential video attached to current global post
	 * can be displayed in page
	 *
	 * !!! NOTE !!!
	 * Will always return false for pages and attachments unless display in archives
	 * option is enabled.
	 *
	 * @return bool
	 */
	public static function video_is_visible(){
		$options = Plugin::instance()->get_options();
		$is_visible = $options[ 'archives' ] ? true : is_single();
		if( is_admin() || ! $is_visible || !self::get_video_post()->is_video() ){
			return false;
		}
		return true;
	}

	/**
	 * Query Vimeo for single video details
	 *
	 * @param string $video_id
	 *
	 * @return array|\WP_Error
	 */
	public static function query_video( $video_id ){
		$vimeo = new Video_Import( 'video', $video_id );
		$result = $vimeo->get_feed();
		if( !$result ){
			$error = $vimeo->get_errors();
			if( is_wp_error( $error ) ){
				return $error;
			}
		}
		return $result;
	}

	/**
	 * A debug function that sets an action to allow third party scripts to hook into
	 *
	 * @param $message
	 * @param string $separator
	 * @param bool $data
	 */
	public static function debug_message( $message, $separator = "\n", $data = false ){
		/**
		 * Fires a debug message action
		 */
		do_action( 'vimeotheque\debug', $message, $separator, $data );
	}

	/**
	 * Retrieves default metadata value for the specified meta key and object.
	 *
	 * By default, an empty string is returned if `$single` is true, or an empty array
	 * if it's false.
	 *
	 * @since 5.5.0
	 *
	 * @param string $meta_type Type of object metadata is for. Accepts 'post', 'comment', 'term', 'user',
	 *                          or any other object type with an associated meta table.
	 * @param int    $object_id ID of the object metadata is for.
	 * @param string $meta_key  Metadata key.
	 * @param bool   $single    Optional. If true, return only the first value of the specified meta_key.
	 *                          This parameter has no effect if meta_key is not specified. Default false.
	 * @return mixed Single metadata value, or array of values.
	 */
	public static function get_metadata_default( $meta_type, $object_id, $meta_key, $single = true ){
		if( function_exists( 'get_metadata_default' ) ){
			return \get_metadata_default( $meta_type, $object_id, $meta_key, $single );
		}else{
			if( $single ) {
				return '';
			}else{
				return [];
			}
		}
	}

	/**
	 * Determines if an object of WP_Error type is an error returned by Vimeo API
	 *
	 * @param $wp_error
	 *
	 * @return bool
	 */
	public static function is_vimeo_api_error( $wp_error ){
		if( !is_wp_error( $wp_error ) ){
			return false;
		}

		$error_data = $wp_error->get_error_data();
		/**
		 * Key 'vimeo_api_error' is set in \Vimeotheque\Vimeo_Api\Vimeo::api_error()
		 * @see \Vimeotheque\Vimeo_Api\Vimeo::api_error()
		 * @since 2.0
		 */
		return ( isset( $error_data['vimeo_api_error'] ) && $error_data['vimeo_api_error'] );
	}
}

