<?php
/**
 * Output video thumbnail
 * @param string $before
 * @param string $after
 * @param bool $echo
 */
function cvm_output_thumbnail( $size = 'small', $before = '', $after = '', $echo = true ){
	global $cvm_video;
	if( !$cvm_video ){
		_doing_it_wrong(__METHOD__, __('You should use this into a foreach() loop. Correct usage is: <br />foreach( $videos as $cvm_video ){ '.__METHOD__.'(); } '), '3.0');
		return false;
	}
	$output = '';

	$sizes = [
		'small' => 0, // 100px width
		'medium' => 1, // 200px width
		'large' => 2, // 640px width
	];

	if( !array_key_exists($size, $sizes) ){
		$size = 'small';
	}

	$thumbnails = array_values( $cvm_video['video_data']['thumbnails'] );

	if( isset( $thumbnails[ $sizes[ $size ] ] ) ){
		$img_url = $thumbnails[ $sizes[ $size ] ];
		if( is_ssl() ){
			$img_url = str_replace( 'http://' , 'https://', $img_url );
		}
		$output = sprintf( '<img src="%s" alt="" />', $img_url );
	}
	if( $echo ){
		echo $before.$output.$after;
	}

	return $before.$output.$after;
}

/**
 * Returns video image prepared to be preloaded
 * @param string $size - small (100px width), medium (200px width) or large (640px width)
 * @param bool $echo
 */
function cvm_image_preload_output( $size = 'small', $class="cvm-preload", $echo = true ){
	global $cvm_video;
	if( !$cvm_video ){
		_doing_it_wrong(__METHOD__, __('You should use this into a foreach() loop. Correct usage is: <br />foreach( $videos as $cvm_video ){ '.__METHOD__.'(); } '), '3.0');
		return false;
	}
	$output = '';

	$sizes = [
		'small' 	=> 0, // 100px width
		'medium' 	=> 1, // 200px width
		'large' 	=> 2, // 640px width
	];

	if( !array_key_exists($size, $sizes) ){
		$size = 'small';
	}

	$blank = VIMEOTHEQUE_URL . '/assets/front-end/images/blank.png';

	$thumbnails = array_values( $cvm_video['video_data']['thumbnails'] );

	if( isset( $thumbnails[ $sizes[ $size ] ] ) ){
		$output = sprintf('<img data-src="%s" alt="" src="%s" class="%s" />', $thumbnails[ $sizes[ $size ] ], $blank, $class);
	}
	if( $echo ){
		echo $output;
	}

	return $output;
}

/**
 * Output video title
 * @param string $before
 * @param string $after
 * @param bool $echo
 */
function cvm_output_title( $include_duration = true,  $before = '', $after = '', $echo = true  ){
	global $cvm_video;
	if( !$cvm_video ){
		_doing_it_wrong(__METHOD__, __('You should use this into a foreach() loop. Correct usage is: <br />foreach( $videos as $cvm_video ){ '.__METHOD__.'(); } '), '3.0');
		return false;
	}
	$output = '';
	if( isset( $cvm_video['title'] ) ){
		$output = $cvm_video['title'];
	}

	if( $include_duration ){
		$output .= ' <span class="duration">[' . \Vimeotheque\Helper::human_time( $cvm_video['video_data']['duration'] ) . ']</span>';
	}

	if( $echo ){
		echo $before.$output.$after;
	}
	return $before.$output.$after;
}

/**
 * Outputs video data
 * @param string $before
 * @param string $after
 * @param bool $echo
 */
function cvm_output_video_data( $before = " ", $after="", $echo = true ){
	global $cvm_video;
	if( !$cvm_video ){
		_doing_it_wrong(__METHOD__, __('You should use this into a foreach() loop. Correct usage is: <br />foreach( $videos as $cvm_video ){ '.__METHOD__.'(); } '), '3.0');
		return false;
	}
	$video_settings = Vimeotheque\get_video_settings( $cvm_video['ID'] );

	$video_id 		= $cvm_video['video_data']['video_id'];
	$data = [
		'video_id' 	 => $video_id,
		'autoplay' 	 => $video_settings['autoplay'],
		'volume'  	 => $video_settings['volume'],
		'size_ratio' => $video_settings['size_ratio'],
		'aspect_ratio'=> $video_settings['aspect_ratio']
	];

	$output = Vimeotheque\cvm_data_attributes($data);
	if( $echo ){
		echo $before.$output.$after;
	}

	return $before.$output.$after;
}

/**
 * Returns the permalink to custom post type video
 * @param bool $echo
 */
function cvm_video_post_permalink( $echo  = true ){
	global $cvm_video;
	if( !$cvm_video ){
		_doing_it_wrong(__METHOD__, __('You should use this into a foreach() loop. Correct usage is: <br />foreach( $videos as $cvm_video ){ '.__METHOD__.'(); } '), '3.0');
		return false;
	}
	$pl = get_permalink( $cvm_video['ID'] );
	if( $echo ){
		echo $pl;
	}
	return $pl;
}

function cvm_output_width( $before = ' style="', $after='"', $echo = true ){
	return Vimeotheque\cvm_output_width( $before, $after, $echo );
}

function cvm_output_player_size( $before = ' style="', $after='"', $echo = true ){
	return Vimeotheque\cvm_output_player_size( $before, $after, $echo );
}

function cvm_output_player_data( $echo = true ){
	return Vimeotheque\cvm_output_player_data( $echo );
}