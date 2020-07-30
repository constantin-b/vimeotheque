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