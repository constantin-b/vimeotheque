<?php

use Vimeotheque\Helper;
use Vimeotheque\Video_Post;

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