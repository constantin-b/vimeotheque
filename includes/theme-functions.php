<?php

use Vimeotheque\Helper;
use Vimeotheque\Video_Post;

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