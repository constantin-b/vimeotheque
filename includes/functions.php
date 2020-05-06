<?php

namespace Vimeotheque;

use Vimeotheque\Player\Player;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**************************************************************************************
 * TEMPLATING
 **************************************************************************************/

/**
 * Outputs default player data
 */
function cvm_output_player_data( $echo = true ){
	$player = Helper::get_embed_options();
	$attributes = cvm_data_attributes( $player, $echo );	
	return $attributes;
}

/**
 * Output video parameters as data-* attributes
 *
 * @param $attributes
 * @param bool $echo
 *
 * @return string
 */
function cvm_data_attributes( $attributes, $echo = false ){
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
 * Outputs the default player size
 *
 * @param string $before
 * @param string $after
 * @param bool $echo
 *
 * @return string
 */
function cvm_output_player_size( $before = ' style="', $after='"', $echo = true ){
	$player = Helper::get_embed_options();
	$height = Helper::calculate_player_height($player['aspect_ratio'], $player['width']);
	$output = 'width:'.$player['width'].'px; height:'.$height.'px;';
	if( $echo ){
		echo $before.$output.$after;
	}
	
	return $before.$output.$after;
}

/**
 * Output width according to player
 *
 * @param string $before
 * @param string $after
 * @param bool $echo
 *
 * @return string
 */
function cvm_output_width( $before = ' style="', $after='"', $echo = true ){
	$player = Helper::get_embed_options();
	if( $echo ){
		echo $before.'width: '.$player['width'].'px; '.$after;
	}
	return $before.'width: '.$player['width'].'px; '.$after;
}


/**
 * Displayes or returns the HTML needed to embed the video player
 *
 * @param int/WP_Post $post - the post ( ID or WP_Post object ) that needs to display the player
 * @param bool $echo - output the result
 *
 * @return string|void
 */
function get_video_embed_html( $post, $echo = true ){
	$_post = Helper::get_video_post( $post );

	if( !$_post->video_id ){
		return;
	}

	$player = new Player( $_post );
	return $player->get_output( $echo );
}

/********************************************************************************
 * Utils
 ********************************************************************************/

/**
 * Returns video URL for a given video ID
 *
 * @param string $video_id
 *
 * @return string
 */
function cvm_video_url( $video_id ){
	return sprintf('https://vimeo.com/%s', $video_id);
}

/**
 * Returns embed code for a given video ID
 *
 * @param string $video_id
 *
 * @return string
 */
function cvm_video_embed( $video_id ){
	$options = Helper::get_embed_options();
	return sprintf( '<iframe src="https://player.vimeo.com/video/%s?title=%d&amp;byline=%d&amp;portrait=%d&amp;color=%s" width="%d" height="%d" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',
		$video_id,
		$options['title'],
		$options['byline'],
		$options['portrait'],
		$options['color'],
		$options['width'],
		Helper::calculate_player_height($options['aspect_ratio'], $options['width'])
	);
}

/**
 * Query Vimeo for single video details
 *
 * @param string $video_id
 * @param bool $ondemand_id
 * @param string $source
 *
 * @return array|bool|string|\WP_Error
 */
function cvm_query_video( $video_id ){
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
 * Utility function. Checks if a given or current post is video created by the plugin
 *
 * @param bool|int|\WP_Post $post
 *
 * @return bool
 */
function is_video( $post = false ){
	return Helper::get_video_post( $post )->is_video();
}

/**
 * Returns post video data from meta
 *
 * @param int|\WP_Post $post
 *
 * @return bool/array
 */
function cvm_get_post_video_data( $post ){
	$_post = Helper::get_video_post( $post );
	if( $_post ){
		return $_post->get_video_data();
	}

	return false;
}

/***********************************************************************************
 * General player settings (from Settings page)
 **********************************************************************************/

/**
 * Get general player settings
 */
function get_player_settings(){
	$option	= Plugin::instance()->get_embed_options();

	// various player outputs may set their own player settings. Return those.
	global $CVM_PLAYER_SETTINGS;
	if( $CVM_PLAYER_SETTINGS ){
		foreach( $option as $k => $v ){
			if( isset( $CVM_PLAYER_SETTINGS[ $k ] ) ){
				$option[ $k ] = $CVM_PLAYER_SETTINGS[ $k ];
			}
		}
	}
	
	return $option;
}

/**
 * @param $path
 * @param string $medium
 *
 * @return string
 */
function cvm_link( $path, $medium = 'doc_link' ){
	$base = 'https://vimeotheque.com/';
	$vars = [
		'utm_source' => 'plugin',
		'utm_medium' => $medium,
		'utm_campaign' => 'vimeotheque-lite'
	];
	$q = http_build_query( $vars );
	return $base . trailingslashit( $path ) . '?' . $q;
}

/**
 * A simple debug function. Doesn't do anything special, only triggers an
 * action that passes the information along the way.
 * For actual debug messages, extra functions that process and hook to this action
 * are needed.
 */
function _cvm_debug_message( $message, $separator = "\n", $data = false ){
	/**
	 * Fires a debug message action
	 */
	do_action( 'cvm_debug_message', $message, $separator, $data );	
}