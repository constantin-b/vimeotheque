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
 * Outputs the tag list for custom post type video
 * 
 * @param string $before
 * @param string $sep
 * @param string $after
 * @return null
 */
function cvm_the_tags( $before = null, $sep, $after, $id = 0 ){
	if ( null === $before )
		$before = __('Tags: ', 'cvm_video');
	echo cvm_get_the_tag_list($before, $sep, $after, $id = 0);	
}

/**
 * Gets the tag list for custom post type video
 * 
 * @param string $before
 * @param string $sep
 * @param string $after
 * @param int $id
 * @return
 */
function cvm_get_the_tag_list($before, $sep, $after, $id = 0){
	return apply_filters(
		'cvm_the_tags',
		get_the_term_list(
			$id,
			Plugin::instance()->get_cpt()->get_tag_tax(),
			$before,
			$sep,
			$after
		),
		$before,
		$sep,
		$after,
		$id
	);
}

/**
 * Outputs default player data
 */
function cvm_output_player_data( $echo = true ){
	$player = get_player_settings();
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
	$player = get_player_settings();
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
	$player = get_player_settings();
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
	$options = get_player_settings();
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
	/**
	 * Options object
	 */
	$option 	= \Vimeotheque\Plugin::instance()->get_player_options()->get_options();

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

/**************************************************************************************************
 * Post specific player settings
 **************************************************************************************************/

/**
 * Single post default settings
 */
function cvm_post_settings_defaults(){
	// general player settings
	$plugin_defaults = get_player_settings();
	return $plugin_defaults;
}

/**
 * Returns playback settings set on a video post
 */
function get_video_settings( $post_id = false, $output = false ){
	if( !$post_id ){
		global $post;
		if( !$post || !is_video($post) ){
			return false;
		}
		$post_id = $post->ID;		
	}else{
		$post = get_post( $post_id );
		if( !$post || !is_video($post) ){
			return false;
		}
	}
	
	// stores default value as set in plugin settings
	$plugin_settings = cvm_post_settings_defaults();

	// if override is on, return global embed settings
	$settings = get_player_settings();

	// get values from post
	$option = isset( $settings['allow_override'] ) && $settings['allow_override'] ?
		$settings :
		get_post_meta( $post_id, Plugin::instance()->get_cpt()->get_post_settings()->get_meta_embed_settings(), true );

	// in some cases, this might not be an array and will issue errors; let's prevent this
	if( !is_array( $option ) ){
		$option = [];
	}

	// the options that should be preserved from main plugin settings
	$get_from_main_settings = [ 'aspect_override' ];
	
	// overwrite defaults with post options
	foreach( $plugin_settings as $k => $v ){
		if( in_array( $k, $get_from_main_settings ) || !isset( $option[ $k ] ) ){
			$option[ $k ] = $v;
		}
	}
	
	if( $output ){
		foreach( $option as $k => $v ){
			if( is_bool( $v ) ){
				$option[$k] = absint( $v );
			}
		}
	}
	
	if( isset( $option['aspect_override'] ) && $option['aspect_override'] ){
		$post_meta = cvm_get_post_video_data($post_id);
		if( $post_meta && isset( $post_meta['size']['ratio'] ) ){
			$option['size_ratio'] = $post_meta['size']['ratio'];
		}else{
			$option['size_ratio'] = false;
		}		
	}else{
		$option['size_ratio'] = false;
	}
	
	return $option;	
}

/**
 * Update video playback options
 *
 * @param int $post_id
 * @param bool $values
 * @param bool $_use_defaults
 *
 * @return bool
 */
function cvm_update_video_settings( $post_id, $values = false, $_use_defaults = false ){
	
	if( !$post_id ){
		return false;
	}
	
	$post = get_post( $post_id );
	if( !$post || !is_video( $post ) ){
		return false;
	}
	
	$defaults = cvm_post_settings_defaults();
	if( $values ){
		$source = (array)$values;
	}else{
		$source = $_POST;
	}
	
	
	foreach( $defaults as $key => $val ){
		if( is_numeric( $val ) ){
			if( isset( $source[ $key ] ) ){
				$defaults[ $key ] = (int)$source[ $key ];
			}else{
				// if flagged to use the default values, just skip the setting and allow the default
				if( $_use_defaults ){
					continue;
				}
				
				// some defaults are numeric but can only have value 1 or 0
				// if so, the option is a checkbox that is unchecked, set it to 0
				if( 0 == $defaults[$key] || 1 == $defaults[$key] ){				
					$defaults[ $key ] = 0;
				}
			}
			continue;
		}
		if( is_bool( $val ) ){
			$defaults[ $key ] = isset( $source[ $key ] );
			continue;
		}
		
		if( isset( $source[ $key ] ) ){
			$defaults[ $key ] = $source[ $key ];
		}
	}	
	
	update_post_meta(
		$post_id,
		Plugin::instance()->get_cpt()
		                  ->get_post_settings()
		                  ->get_meta_embed_settings(),
		$defaults
	);
}

/**
 * Creates from a number of given seconds a readable duration ( HH:MM:SS )
 * @param int $seconds
 * @return string - formatted time
 */
function human_time( $seconds ){
	return Helper::human_time( $seconds );
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
		'utm_campaign' => 'vimeo-pro-plugin'
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