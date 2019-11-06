<?php

namespace Vimeotheque;

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
	return apply_filters( 'cvm_the_tags', get_the_term_list( $id, cvm_get_tag(), $before, $sep, $after ), $before, $sep, $after, $id );
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
 * @param array $array - key=>value pairs
 * @param bool $echo	
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
 * @param string $before
 * @param string $after
 * @param bool $echo
 */
function cvm_output_player_size( $before = ' style="', $after='"', $echo = true ){
	$player = get_player_settings();
	$height = cvm_player_height($player['aspect_ratio'], $player['width']);
	$output = 'width:'.$player['width'].'px; height:'.$height.'px;';
	if( $echo ){
		echo $before.$output.$after;
	}
	
	return $before.$output.$after;
}

/**
 * Output width according to player
 * @param string $before
 * @param string $after
 * @param bool $echo
 */
function cvm_output_width( $before = ' style="', $after='"', $echo = true ){
	$player = get_player_settings();
	if( $echo ){
		echo $before.'width: '.$player['width'].'px; '.$after;
	}
	return $before.'width: '.$player['width'].'px; '.$after;
}


/**
 * Outputs the HTML for embedding videos on single posts.
 *
 * @param bool $echo
 *
 * @return string
 */
function embed_html( $echo = true ){
	
	global $post;
	if( !$post ){
		return;
	}

	$e = get_video_embed_html( $post, $echo );
	return $e;
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



	$settings = $_post->get_embed_options( true );
	$settings['video_id'] = $_post->video_id;

	/**
	 * @deprecated - Use cvm_embed_width
	 */
	$width 	= apply_filters( 'cvm-embed-width', $settings['width'], $_post->get_video_data(), 'manual_embed' );

	/**
	 * Filter that can be used to modify the width of the embed
	 * @var int
	 */
	$width 	= apply_filters( 'cvm_embed_width', $width, $_post->get_video_data(), 'manual_embed' );

	$height = cvm_player_height( $settings['aspect_ratio'] , $width, $settings['size_ratio'] );

	/**
	 * @deprecated - Use cvm_video_embed_css_class
	 */
	$class = apply_filters( 'cvm_video_post_css_class', [], $post );

	/**
	 * Filter on video container CSS class to add extra CSS classes
	 *
	 * Name: cvm_video_post_css_class
	 * Params: 	- an empty array
	 * 			- the post object that will embed the video
	 *
	 * @var string
	 */
	$class = apply_filters( 'cvm_video_embed_css_class', $class, $post );

	$extra_css = implode( ' ', (array) $class );

	$video_container = '<div class="cvm_single_video_player ' . $extra_css . '" ' . cvm_data_attributes( $settings ) . ' style="width:' . $width . 'px; height:' . $height.'px; max-width:100%;"><!--video container--></div>';

	// add player script
	cvm_enqueue_player();

	if( $echo ) {
		echo $video_container;
	}

	return $video_container;
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
 * @param string $video_id
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
		cvm_player_height($options['aspect_ratio'], $options['width'])
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
function cvm_query_video( $video_id, $ondemand_id = false ){
	$feed = $ondemand_id ? 'ondemand_video' : 'video';
	
	if( !ctype_digit( $video_id ) ){
		return cvm_query_ondemand_main_video( $video_id );	
	}
	
	$vimeo = new Video_Import( $feed, $video_id );
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
 * @param $ondemand_id
 *
 * @return array|bool|string|\WP_Error
 */
function cvm_query_ondemand_main_video( $ondemand_id ){

	$vimeo = new Video_Import( 'ondemand_videos', $ondemand_id );
	$result = $vimeo->get_feed();
	if( !$result ){
		$error = $vimeo->get_errors();
		if( is_wp_error( $error ) ){
			return $error;
		}
	}
	
	$video = false;
	foreach ( $result as $v ){
		if( 'main' == $v['type'] ){
			$video = $v;
			break;
		}
	}
	
	return $video;
}

/**
 * @deprecated 
 * @since 1.3
 * @uses is_video()
 * 
 * Checks that global $post is video post type
 */
function cvm_is_video_post(){
	return is_video();
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
 * Get user set post type to which all videos should be imported to
 *
 * @return false|string - returns the user post type set programatically or false if no post type is set using filter 'cvm_import_post_type'
 */
function cvm_user_post_type(){
	return get_method( 'get_user_post_type', [ false ] );
}

/**
 * More efficient strip tags
 *
 * @link  http://www.php.net/manual/en/function.strip-tags.php#110280
 * @param string $string string to strip tags from
 * @return string
 */
function cvm_strip_tags( $string ) {
   
    // ----- remove HTML TAGs -----
    $string = preg_replace ('/<[^>]*>/', ' ', $string);
   
    // ----- remove control characters -----
    $string = str_replace("\r", '', $string);    // --- replace with empty space
    $string = str_replace("\n", ' ', $string);   // --- replace with space
    $string = str_replace("\t", ' ', $string);   // --- replace with space
   
    // ----- remove multiple spaces -----
    $string = trim(preg_replace('/ {2,}/', ' ', $string));
   
    return $string;
}

/**
 * Adds video player script to page
 */
function cvm_enqueue_player(){
	wp_enqueue_script(
		'cvm-video-player',
		VIMEOTHEQUE_URL . 'assets/front-end/js/video-player' . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.dev' : '' ) . '.js',
		['jquery'],
		'1.0'
	);
	
	wp_enqueue_style(
		'cvm-video-player',
		VIMEOTHEQUE_URL . 'assets/front-end/css/video-player.css'
	);
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

/**
 * Calculate player height from given aspect ratio and width
 * @param string $aspect_ratio
 * @param int $width
 * @param float $ratio - a given ratio; will override aspect ratio if set
 */
function cvm_player_height( $aspect_ratio, $width, $ratio =  false ){
	$width = absint($width);
	
	if( is_numeric($ratio) && $ratio > 0 ){
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
 * Available player themes
 */
function cvm_playlist_themes(){
	return [
		'default' 	=> __('Default theme', 'cvm_video'),
		'carousel' 	=> __('Carousel navigation', 'cvm_video'),
		'wall'		=> __('Wall', 'cvm_video')
	];
}

/**
 * Check if theme is supported by the plugin.
 * Returns false or an array containing a mapping for custom post fields to store information on
 */
function has_theme_support(){
	return Theme_Compatibility::instance()->get_theme_compatibility();
}

/**
 * General method to access public methods of post type class
 *
 * @param string $method - method name
 * @param array $args
 *
 * @return string - result returned by class method
 */
function get_method( $method, $args = [] ){
	$obj = Plugin::instance()->get_cpt();

	if( !$args ){
		$result = call_user_func( [ $obj, $method ] );
	}else{
		$result = call_user_func_array( [ $obj, $method ], $args);
	}	
	return $result;
}

/**
 * Get plugin custom post type
 * 
 * @return string
 */

function cvm_get_post_type(){
	return get_method('get_post_type');
}

/**
 * Get plugin custom post type "category" taxonomy
 * 
 * @return string
 */

function cvm_get_category(){
	return get_method('get_post_tax');
}

/**
 * Get plugin custom post type tag taxonomy
 * 
 * @return string
 */

function cvm_get_tag(){
	return get_method('get_tag_tax');
}

/**
 * Get plugin custom post type for automatic import playlists
 * 
 * @return string
 */

function cvm_get_playlist_post_type(){
	return get_method('get_playlist_post_type');
}

/**
 * Get playlist meta name that stores playlist details
 * 
 * @return string
 */

function cvm_get_playlist_meta_name(){
	return get_method('get_playlist_meta_name');
}



/*********************************************************************************
 * Plugin settings management
 *********************************************************************************/

/**
 * Utility function, returns plugin default settings
 */
function cvm_load_plugin_settings(){
	$defaults = [
		'public' => true, // post type is public or not
		'archives' => false, // display video embed on archive pages
		'homepage' => false, // include custom post type on homepage
		'main_rss' => false, // include custom post type into the main RSS feed
		'use_microdata' => false, // put microdata on video pages ( more details on: http://schema.org )
		'post_type_post' => false, // when true all videos will be imported as post type post and will disregard the theme compatibility layer
		'check_video_status' => false, // when true, will check imported video posts if still published on Vimeo and embeddable
		'post_slug'	=> 'vimeo-video',
		'taxonomy_slug' => 'vimeo-videos',
		'tag_slug' => 'vimeo-tag',
		// 'import_categories' => false,
		'import_tags' => false, // import tags retrieved from Vimeo
		'max_tags' => 3, // how many tags to import
		'import_title' => true, // import titles on custom posts
		'import_description' => 'post_content', // import descriptions on custom posts
		// 'remove_after_text' => '',
		'prevent_autoembed' => false, // prevent autoembeds on video posts
		'make_clickable' => false, // make urls pasted in content clickable
		'import_date' => false, // import video date as post date
		'featured_image' => false, // set thumbnail as featured image; default import on video feed import (takes more time)
		'image_on_demand' => false, // when true, thumbnails will get imported only when viewing the video post as oposed to being imported on feed importing
		'import_status' => 'draft', // default import status of videos
		/**
		 * Videos having user privacy set not public will be:
		 * - import: imported  
		 * - pending: imported having status pending
		 * - skip: skipped from importing
		 */
		'import_privacy' => 'import',
		'import_frequency' => 5, // in minutes
		'import_quantity' => 20,
		//'feed_unpublish_on_error' => false, // remove automatic import from queue on feed error
		//'feed_fail_number_unpublish' => 3, // unpublish if feed query returned error for this many times
		'conditional_import' => false, // when true, automatic imports will be processed only when custom link is hit (used for CRON Jobs)
		'autoimport_param' => '', // the value of the variable that must be set when autoimport hits the website (unique for each installation)
		'page_load_autoimport' => false, // run imports on page load

		// Vimeo oAuth
		'vimeo_consumer_key' => '',
		'vimeo_secret_key' => '',
		'oauth_token' => '',// retrieved from Vimeo; gets set after entering valid client ID and client secret
		'oauth_secret' => '',// retrieved from Vimeo; gets set after uses authorizes the plugin to query the API on his behalf
		'vimeo_access_granted' => false,// set when oAuth is done to true
		'license_key' => ''
	];

	return Options_Factory::get( '_cvm_plugin_settings', $defaults );
}

/**
 * Utility function, returns plugin settings
 */
function get_settings(){
	$options = cvm_load_plugin_settings();
	return $options->get_options();
}

/**
 * Returns WP option name of plugin settings option
 * @return string
 */
function cvm_get_settings_option_name(){
	$option = cvm_load_plugin_settings();
	return $option->get_option_name();
}

/**
 * Outputs the autoimport URL for conditional importing
 *
 * @param boolean $include_field
 * @param boolean $echo
 * @return string
 */
function autoimport_uri( $echo = true ){
	$options = get_settings();
	$output = add_query_arg( array(
		'cvm_autoimport' => $options[ 'autoimport_param' ]
	), trailingslashit( get_home_url() ) );

	if( $echo ){
		echo $output;
	}

	return $output;
}

/***********************************************************************************
 * General player settings (from Settings page)
 **********************************************************************************/

/**
 * Global player settings defaults.
 */
function cvm_load_player_settings(){
	$defaults = [
		'title'		=> 1, 	// show video title
		'byline' 	=> 1, 	// show player controls. Values: 0 or 1
		'portrait' 	=> 1, 	// show author image
		'color'		=> '', 	// no color set by default; will use Vimeo's settings
		///'fullscreen'=> 1,	// deprecated option ont supported by Vimeo player API (0 - fullscreen button hidden; 1 - fullscreen button displayed)
		'loop'		=> 0,
		// Autoplay may be blocked in some environments, such as IOS, Chrome 66+, and Safari 11+. In these cases, we’ll revert to standard playback requiring viewers to initiate playback.
		'autoplay'	=> 0, 	// 0 - on load, player won't play video; 1 - on load player plays video automatically
		
		// extra settings
		'aspect_ratio'		=> '16x9',
		'aspect_override'	=> true,
		'width'				=> 640,
		'video_position' 	=> 'below-content', // in front-end custom post, where to display the video: above or below post content
		'volume'			=> 25, // video default volume	
		// extra player settings controllable by widgets/shortcodes
		'playlist_loop'		=> 0,
		'js_embed' 			=> true, // if true, embedding is done by JavaScript. If false, embedding is done by PHP by simply placing the iframe code into the page
		'allow_override'    => false
	];
	// get Plugin option
	return Options_Factory::get( '_cvm_player_settings', $defaults );
}

/**
 * Get general player settings
 */
function get_player_settings(){
	/**
	 * Options object
	 */
	$option 	= cvm_load_player_settings()->get_options();

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
 * @return string
 */
function cvm_get_player_settings_option_name(){
	$option = cvm_load_player_settings();
	$name = $option->get_option_name();
	return $name;
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
	$option = $settings['allow_override'] ? $settings : get_post_meta( $post_id, Plugin::instance()->get_cpt()->get_post_settings()->get_meta_embed_settings(), true );
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
 * @param string $values
 * @param string $_use_defaults
 * @return void
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
				// if flaged to use the default values, just skip the setting and allow the default
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
	
	update_post_meta($post_id, Plugin::instance()->get_cpt()->get_post_settings()->get_meta_embed_settings(), $defaults);
}

/***********************************************************************************
 * Automatic import playlists settings
 ***********************************************************************************/

/**
 * Global playlist settings defaults.
 */
function cvm_playlist_settings_defaults(){
	$settings = get_settings();
	$player_opt = get_player_settings();
	
	$defaults = [
		'type' => '',
		'id' => '',
		'album_user' => false,
		'theme_import' => false,
		'native_tax' => [],
		'theme_tax' => [],
		'native_tag' => [],
		'theme_tag' => [],
		'import_description' => $settings['import_description'],
		'import_status' => $settings['import_status'], // post status of imported videos
		'import_title' => $settings['import_title'],
		'import_date' => $settings['import_date'],
		'import_user' => false,
		// embed options
		'aspect_ratio' => $player_opt['aspect_ratio'],
		'video_position' => $player_opt['video_position'],
		'loop' => false,
		'autoplay' => $player_opt['autoplay'],
		// stats
		'updated' => false,
		'total' => 0,
		'imported' => 0,
		'errors' => false,
		'stats' => false,
		'page' => 1, // default page is 1 (can't be less than 1)
		'first_video' => false,
		'last_video' => false,
		// extra
		// @todo Implement options in Feed settings when editing
		'start_date' => false,
		'no_reiterate' => false,
		'repeat' => 1,
		'repeats_left' => 1,		
	];

	return $defaults;
}

/**
 * Get general playlist settings
 */
function cvm_get_playlist_settings( $post_id ){
	$defaults 	= cvm_playlist_settings_defaults();
	$option 	= get_post_meta($post_id, cvm_get_playlist_meta_name(), true);	
	$option = wp_parse_args($option, $defaults);	
	return $option;
}

/**
 * Creates from a number of given seconds a readable duration ( HH:MM:SS )
 * @param int $seconds
 * @return string - formatted time
 */
function human_time( $seconds ){
	
	$seconds = absint( $seconds );
	
	if( $seconds < 0 ){
		return;
	}
	
	$h = floor( $seconds / 3600 );
	$m = floor( $seconds % 3600 / 60 );
	$s = floor( $seconds %3600 % 60 );
	
	return ( ($h > 0 ? $h . ":" : "") . ( ($m < 10 ? "0" : "") . $m . ":" ) . ($s < 10 ? "0" : "") . $s);
}

/*************************************************************************************
 * Admin utils
 *************************************************************************************/












/******************************************************************************************
 * Automatic import utils
 ******************************************************************************************/







/**
 * Options on how often should automatic update be ran
 * 
 * @return array
 */
function cvm_automatic_update_timing(){	
	$values = [
		'1'		=> __('minute', 'cvm_video'),
		'15' 	=> __('15 minutes', 'cvm_video'),
		'30' 	=> __('30 minutes', 'cvm_video'),
		'60'	=> __('hour', 'cvm_video'),
		'120'	=> __('2 hours', 'cvm_video'),
		'180'	=> __('3 hours', 'cvm_video'),
		'360'	=> __('6 hours', 'cvm_video'),
		'720'	=> __('12 hours', 'cvm_video'),
		'1440'	=> __('day', 'cvm_video')
	];
	return $values;	
}











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