<?php

namespace Vimeotheque;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Shortcode to display a single video in post/page
 * Usage:
 * 
 * [cvm_video id="video_id_from_wp"]
 * 
 * Complete params:
 * 
 * - id : video ID from WordPress import (post ID) - required
 * - volume : video volume (number between 1 and 100) - optional
 * - width : width of video (number) - optional; works in conjunction with aspect_ratio
 * - aspect_ratio : aspect ratio of video ( 16x9, 4x3, 2.35x1 ) - optional; needed to calculate height
 * - autoplay : play video on page load ( 0 or 1 ) - optional
 * - controls : display controls on video ( 0 or 1 ) - optional
 * 
 * @param array $atts
 * @param string $content
 */
function cvm_single_video( $atts = [], $content = '' ){
	// bail out from feeds
	if( is_feed() ){
		return false;
	}
	// check if atts is set
	if( !is_array( $atts ) ){
		return;
	}	
	// look for video ID
	if( !array_key_exists('id', $atts) ){
		return;
	}

	$_post = Helper::get_video_post( $atts['id'] );
	if( !$_post->video_id ){
		return;
	}

	// get video options attached to post
	$post_options = $_post->get_embed_options( true );

	// combine video vars with atts
	$vars = shortcode_atts( [
		'autoplay'		=> $post_options['autoplay'],
		'volume' 		=> $post_options['volume'],
		'width' 		=> $post_options['width'],
		'loop' 			=> $post_options['loop'],
		'aspect_ratio' 	=> $post_options['aspect_ratio']
	], $atts);
	
	if( !$atts['width'] ){
		return false;
	}
	
	$width	= absint( $vars['width'] );
	$height = cvm_player_height( $vars['aspect_ratio'] , $vars['width']);
	
	$settings = wp_parse_args( $vars, $post_options );
	$settings['video_id'] = $_post->video_id;
	
	$video_data_atts = cvm_data_attributes( $settings );
	$extra_css = ' cvm-shortcode';
	
	// if js embedding not allowed, embed by placing the iframe dirctly into the post content 
	$plugin_embed_opt = get_player_settings();
	$embed_html = '<!--video container-->';
	if( isset( $post_options['js_embed'] ) && !$plugin_embed_opt['js_embed'] ){
		$params = [
			'title'		=> $settings['title'],
			'byline'	=> $settings['byline'],
			'portrait'	=> $settings['portrait'],
			'loop'		=> $settings['loop'],
			'color'		=> $settings['color'],
			//'fullscreen'=> $settings['fullscreen']
		];
		$embed_url = 'https://player.vimeo.com/video/' . $_post->video_id . '?' . http_build_query( $params, '', '&' ) ;
		$extra_css .= ' cvm_simple_embed';
		$embed_html = '<iframe src="' . $embed_url . '" width="100%" height="100%" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
	}	
	
	$video_container = '<div class="cvm_single_video_player' . $extra_css . '" ' . $video_data_atts . ' style="width:' . $width.'px; height:' . $height . 'px; max-width:100%;">' . $embed_html . '</div>';
	// add JS file
	cvm_enqueue_player();
	
	return $video_container;
}
add_shortcode('cvm_video', __NAMESPACE__ . '\cvm_single_video');

if( !function_exists( 'cvm_embed_video_shortcode' ) ) {
	/**
	 * Shortcode callback that displays the video into a video post into
	 * the exact position where the shortode is.
	 * @return string|void
	 */
	function cvm_embed_video_shortcode() {
		if ( ! is_singular() || ! is_video() ) {
			return;
		}
		global $post;

		return get_video_embed_html( $post, false );
	}
	add_shortcode( 'cvm_video_embed', __NAMESPACE__ . '\cvm_embed_video_shortcode' );
}

/**
 * Shortcode to display a playlist of videos
 * @param array $atts
 * @param string $content
 */
function cvm_video_playlist( $atts = [], $content = '' ){

	$defaults = [
		'theme' 		=> 'default',
		'aspect_ratio' 	=> '16x9',
		'width' 		=> 0,
		'volume' 		=> 20,
		'title'			=> 1,
		'byline'		=> 1,
		'portrait'		=> 1,
		'playlist_loop' => 0,
		'videos' 		=> '',
		'categories'    => '',
		'post_type'     => ''
	];
	
	$atts = wp_parse_args($atts, $defaults);
	
	// check if atts is set
	if( !is_array( $atts ) ){
		return;
	}	
	// look for video ID's
	if( array_key_exists('videos', $atts) && $atts['videos'] ){
		// look for video ids
		$video_ids = explode(',', $atts['videos']);
	}

	if( array_key_exists( 'categories', $atts ) ){
		$categories = explode( ',', $atts['categories'] );
		if( $categories ) {
			$post_type = false;
			if( array_key_exists( 'post_type', $atts ) && !empty( $atts['post_type'] ) ){
				$post_type = explode( ',', $atts['post_type'] );
			}

			$video_ids = array_merge( ( isset( $video_ids ) ? $video_ids : [] ), get_category_post_ids( $categories, $post_type ) );
		}
	}

	if( !isset( $video_ids ) || !$video_ids ){
		return;
	}
	
	unset( $atts['videos'] );
	$defaults = get_player_settings();
	$player_settings = wp_parse_args($atts, $defaults);
	
	if( !isset( $atts['theme'] ) ){
		$atts['theme'] = 'default';
	}

	$extra = [ 'layout' => '' ];
	if( isset( $atts['layout'] ) ){
		$extra['layout'] = $atts['layout'];
	}
	
	$content = cvm_output_playlist( $video_ids, count($video_ids), $atts['theme'], $player_settings, 'both', false, $extra );
	return $content;
}
add_shortcode('cvm_playlist', __NAMESPACE__ . '\cvm_video_playlist');

/**
 * Returns all post ids for the given categories
 * @param array $categories - array of terms IDs
 */
function get_category_post_ids( /*array*/ $categories, /*array*/ $post_type ){
	if( !is_array( $categories ) || !$categories ){
		return;
	}

	$posts = [];

	// if newest videos should be returned, return them
	if( in_array( '0', $categories ) ){
		$args = [
			'post_type' => $post_type,
			'numberposts' => apply_filters( 'cvm_shortcode_new_videos_max_posts', 10 ),
			'order' => 'DESC',
			'orderby' => 'post_date'
		];
		$p = get_posts( $args );
		if( $p && !is_wp_error( $p ) ){
			foreach( $p as $post ){
				if( is_video( $post ) ) {
					$posts[] = $post->ID;
				}
			}
		}
		return $posts;
	}

	$terms = [];
	foreach( $categories as $term_id ){
		$term = get_term( $term_id );
		if( $term && !is_wp_error( $term ) ){
			$terms[ $term->taxonomy ][] = $term->term_id;
		}
	}

	if( $terms ){

		$args = [
			'post_type' => cvm_get_post_types_by_taxonomy( array_keys( $terms ) ),
			'numberposts' => -1,
			'order' => 'DESC',
			'orderby' => 'post_date',
			'tax_query' => [
				'relation' => 'OR',
			]
		];

		foreach( $terms as $taxonomy => $term_ids ){
			$args['tax_query'][] = [
				'taxonomy' => $taxonomy,
				'field' => 'term_id',
				'terms' => $term_ids
			];
		}

		$p = get_posts( $args );
		if( $p && !is_wp_error( $p ) ){
			foreach( $p as $post ){
				if( is_video( $post ) ) {
					$posts[] = $post->ID;
				}
			}
		}
	}

	return $posts;
}

/**
 * @param $tax
 *
 * @return array
 */
function cvm_get_post_types_by_taxonomy( $tax ){
	$out = [];
	$post_types = get_post_types();
	foreach( $post_types as $post_type ){
		$taxonomies = get_object_taxonomies( $post_type );
		if( array_intersect( $tax, $taxonomies ) ){
			$out[] = $post_type;
		}
	}
	return $out;
}


/**
 * Outputs a shortcode/widget
 * 
 * @param array/string $videos - an array of video ids or latest for latest videos
 * @param int $results - number of latest videos to retrieve
 * @param string $theme - theme name; must be valid plugin theme
 * @param array $player_settings - settings for player
 * @param string $post_type - retrieve posts or retrieve videos (or both); values: post, vimeo-video, both
 * @param int $taxonomy - the taxonomy to retrieve videos from
 * @return string - HTML playlist
 */
function cvm_output_playlist( $videos = 'latest', $results = 5, $theme = 'default', $player_settings = [], $post_type = false, $taxonomy = false, $theme_settings = [] ){
	
	switch( $post_type ){
		case 'post':
			$post_type = 'post';
			$tax_name = 'category';
		break;
		case 'both':
			$post_type = [ 'post', Plugin::instance()->get_cpt()->get_post_type() ];
		break;	
		default:
			$post_type = Plugin::instance()->get_cpt()->get_post_type();
			$tax_name = Plugin::instance()->get_cpt()->get_post_tax();
		break;	
	}
	
	$args = [
		'post_type' 		=> $post_type,
		'posts_per_page' 	=> absint( $results ),
		'numberposts'		=> absint( $results ),
		'post_status'		=> 'publish',
		'supress_filters'	=> true
	];
	
	if( !is_array( $post_type ) && 'post' === $post_type ){
		$args['meta_query'] = [
			[
			'key' => '__cvm_is_video',
			'value' => true,
			'compare' => '=='
			]
		];
	}
	
	
	// taxonomy query
	if( !is_array( $videos ) && isset( $taxonomy ) && !empty( $taxonomy ) && ((int)$taxonomy) > 0 ){
		$term = get_term( $taxonomy, $tax_name, ARRAY_A );
		if( !is_wp_error( $term ) ){			
			$args[ $tax_name ] = 'category' === $tax_name ? $term['term_id'] : $term['slug'];
		}	
	}
	
	// if $videos is array, the function was called with an array of video ids
	if( is_array( $videos ) ){
		
		$ids = [];
		foreach( $videos as $video_id ){
			$ids[] = absint( $video_id );
		}		
		$args['include'] 		= $ids;
		$args['posts_per_page'] = count($ids);
		$args['numberposts'] 	= count($ids);
		
	}elseif( is_string( $videos ) ){
		
		$found = false;
		switch( $videos ){
			case 'latest':
				$args['orderby']	= 'post_date';
				$args['order']		= 'DESC';
				$found 				= true;
			break;	
		}
		if( !$found ){
			return;
		}
				
	}else{ // if $videos is anything else other than array or string, bail out		
		return;		
	}
	
	// get video posts
	$posts = get_posts( $args );
	
	if( !$posts ){
		return;
	}
	
	$videos = [];
	foreach( $posts as $post_key => $post ){
		if( isset( $ids ) ){
			$key = array_search($post->ID, $ids);
		}else{
			$key = $post_key;
		}	
		
		if( is_numeric( $key ) ){
			$videos[ $key ] = [
				'ID'			=> $post->ID,
				'title' 		=> $post->post_title,
				'video_data' 	=> cvm_get_post_video_data( $post->ID )
			];
		}
	}
	ksort( $videos );
	
	ob_start();
	
	// set custom player settings if any
	global $CVM_PLAYER_SETTINGS;
	if( $player_settings && is_array( $player_settings ) ){
		$defaults = get_player_settings();
		foreach ( $defaults as $setting => $value ){
			if( isset( $player_settings[ $setting ] ) ){
				if( is_numeric( $defaults[ $setting ] ) && $defaults[ $setting ] <= 1 ){
					$defaults[ $setting ] = 1 == $player_settings[ $setting ] ? 1 : 0;	
				}else{				
					$defaults[ $setting ] = $player_settings[ $setting ];
				}
			}
		}
		
		$CVM_PLAYER_SETTINGS = $defaults;
	}
	
	global $cvm_video;
	
	if( !array_key_exists($theme, cvm_playlist_themes()) ){
		$theme = 'default';
	}
	// include theme functions
	include_once( VIMEOTHEQUE_PATH . '/includes/theme-functions.php' );
	// include the theme display file
	include( VIMEOTHEQUE_PATH . 'themes/' . $theme . '/player.php' );
	
	$content = ob_get_contents();
	ob_end_clean();
	
	cvm_enqueue_player();
	wp_enqueue_script(
		'cvm-vim-player-'.$theme,
		VIMEOTHEQUE_URL . 'themes/' . $theme . '/assets/script.js',
		[ 'cvm-video-player' ],
		'1.0'
	);
	wp_enqueue_style(
		'cvm-vim-player-'.$theme,
		VIMEOTHEQUE_URL . 'themes/' . $theme . '/assets/stylesheet.css',
		false, 
		'1.0'
	);
	
	// remove custom player settings
	$CVM_PLAYER_SETTINGS = false;
	
	return $content;
	
}
