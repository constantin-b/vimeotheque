<?php

use Vimeotheque\Helper;
use Vimeotheque\Video_Post;





/**
 * Outputs video data
 *
 * @param string $before
 * @param string $after
 * @param bool $echo
 *
 * @return bool|string
 */
function cvm_output_video_data( $before = " ", $after="", $echo = true ){
	/**
	 * @var Video_Post
	 */
	global $cvm_video;
	if( !$cvm_video ){
		_doing_it_wrong(__METHOD__, __('You should use this into a foreach() loop. Correct usage is: <br />foreach( $videos as $cvm_video ){ '.__METHOD__.'(); } '), '3.0');
		return false;
	}

	$options = $cvm_video->get_embed_options();

	$data = [
		'video_id' 	 => $cvm_video->video_id,
		'autoplay' 	 => $options['autoplay'],
		'volume'  	 => $options['volume'],
		'size_ratio' => $cvm_video->size['ratio'],
		'aspect_ratio'=> $options['aspect_ratio']
	];

	$output = Helper::data_attributes( $data, false );

	if( $echo ){
		echo $before.$output.$after;
	}

	return $before.$output.$after;
}

/**
 * Returns the permalink to custom post type video
 *
 * @param bool $echo
 *
 * @return bool|false|string
 */
function cvm_video_post_permalink( $echo  = true ){
	/**
	 * @var Video_Post
	 */
	global $cvm_video;
	if( !$cvm_video ){
		_doing_it_wrong(__METHOD__, __('You should use this into a foreach() loop. Correct usage is: <br />foreach( $videos as $cvm_video ){ '.__METHOD__.'(); } '), '3.0');
		return false;
	}

	$pl = get_permalink( $cvm_video->get_post()->ID );
	if( $echo ){
		echo $pl;
	}
	return $pl;
}

function cvm_output_width( $before = ' style="', $after='"', $echo = true ){
	$player = Helper::get_embed_options( cvm_get_player_options() );
	if( $echo ){
		echo $before . 'width: ' . $player['width'].'px; ' . $after;
	}
	return $before . 'width: ' . $player['width'].'px; ' . $after;
}

function cvm_output_player_size( $before = ' style="', $after='"', $echo = true ){
	$player = Helper::get_embed_options( cvm_get_player_options() );
	$height = Helper::calculate_player_height( $player['aspect_ratio'], $player['width'] );
	$output = sprintf(
		'width: %dpx; height: %dpx;',
		$player['width'],
		$height
	);

	if( $echo ){
		echo $before . $output . $after;
	}

	return $before . $output . $after;
}

function cvm_output_player_data( $echo = true ){
	$player = Helper::get_embed_options( cvm_get_player_options() );
	$attributes = Helper::data_attributes( $player, $echo );

	if( $echo ){
		echo $attributes;
	}

	return $attributes;
}

function cvm_get_player_options(){
	global $CVM_PLAYER_SETTINGS;
	return $CVM_PLAYER_SETTINGS;
}