<?php
/**
 * @author CodeFlavors
 * @project Vimeotheque 2.0 Lite
 */

namespace Vimeotheque\Themes;

use Vimeotheque\Video_Post;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Helper {

	/**
	 * Returns or outputs the thumbnail of current video in loop
	 *
	 * @param string $size
	 * @param string $before
	 * @param string $after
	 * @param bool $echo
	 *
	 * @return string|void
	 */
	public static function get_thumbnail( $size = 'small', $before = '', $after = '', $echo = true ){
		$video = self::current_video_post();
		if( !$video ){
			return;
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

		$thumbnails = array_values( $video->thumbnails );

		if( isset( $thumbnails[ $sizes[ $size ] ] ) ){
			$img_url = $thumbnails[ $sizes[ $size ] ];
			if( is_ssl() ){
				$img_url = str_replace( 'http://' , 'https://', $img_url );
			}
			$output = sprintf( '<img src="%s" alt="" />', $img_url );
		}
		if( $echo ){
			echo $before . $output . $after;
		}

		return $before . $output . $after;
	}

	/**
	 * Output an image preloader
	 *
	 * @param string $size
	 * @param string $class
	 * @param bool $echo
	 *
	 * @return string|void
	 */
	public static function image_preloader( $size = 'small', $class="cvm-preload", $echo = true ){
		$video = self::current_video_post();
		if( !$video ){
			return;
		}

		$output = '';

		$sizes = [
			'small' 	=> 0, // 100px width
			'medium' 	=> 1, // 200px width
			'large' 	=> 2, // 640px width
		];

		if( !array_key_exists( $size, $sizes ) ){
			$size = 'small';
		}

		$blank = VIMEOTHEQUE_URL . '/assets/front-end/images/blank.png';

		$thumbnails = array_values( $video->thumbnails );

		if( isset( $thumbnails[ $sizes[ $size ] ] ) ){
			$output = sprintf(
				'<img data-src="%s" alt="" src="%s" class="%s" />',
				$thumbnails[ $sizes[ $size ] ],
				$blank,
				$class
			);
		}

		if( $echo ){
			echo $output;
		}

		return $output;
	}

	/**
	 * @param bool $include_duration
	 * @param string $before
	 * @param string $after
	 * @param bool $echo
	 *
	 * @return string|void
	 */
	public static function get_title( $include_duration = true,  $before = '', $after = '', $echo = true ){
		$video = self::current_video_post();
		if( !$video ){
			return;
		}

		$output = $video->get_post()->post_title;

		if( $include_duration ){
			$output .= ' <span class="duration">[' . \Vimeotheque\Helper::human_time( $video->duration ) . ']</span>';
		}

		if( $echo ){
			echo $before.$output.$after;
		}

		return $before.$output.$after;
	}

	/**
	 * @param string $before
	 * @param string $after
	 * @param bool   $echo
	 *
	 * @return string|void
	 */
	public static function get_excerpt( $before = '<div>', $after = '</div>', $echo  = true ){
		$video = self::current_video_post();
		if( !$video ){
			return;
		}

		$options = self::get_player_options();
		if( !isset( $options['show_excerpts'] ) || !$options['show_excerpts']  ){
			return;
		}

		/**
		 * Filters the displayed post excerpt.
		 *
		 * @since 0.71
		 *
		 * @see get_the_excerpt()
		 *
		 * @param string $post_excerpt The post excerpt.
		 */
		$excerpt = apply_filters( 'the_excerpt', get_the_excerpt( $video->get_post() ) );

		if( empty( $excerpt ) ){
			return;
		}

		if( $echo ){
			echo $before . $excerpt . $after;
		}

		return $before . $excerpt . $after;
	}

	/**
	 * @param string $before
	 * @param string $after
	 * @param bool $echo
	 *
	 * @return string|void
	 */
	public static function get_video_data_attributes( $before = " ", $after="", $echo = true ){
		$video = self::current_video_post();
		if( !$video ){
			return;
		}

		$options = $video->get_embed_options();

		$data = [
			'video_id' 	 => $video->video_id,
			'autoplay' 	 => $options['autoplay'],
			'volume'  	 => $options['volume'],
			'size_ratio' => $video->size['ratio'],
			'aspect_ratio'=> $options['aspect_ratio']
		];

		$output = \Vimeotheque\Helper::data_attributes( $data, false );

		if( $echo ){
			echo $before . $output . $after;
		}

		return $before . $output . $after;
	}

	/**
	 * @param bool $echo
	 *
	 * @return bool|false|string|void|\WP_Error
	 */
	public static function get_post_permalink( $echo  = true ){
		$video = self::current_video_post();
		if( !$video ){
			return;
		}

		$pl = get_permalink( $video->get_post()->ID );
		if( $echo ){
			echo $pl;
		}
		return $pl;
	}

	/**
	 * @param string $before
	 * @param string $after
	 * @param bool $echo
	 *
	 * @return string
	 */
	public static function get_width( $before = ' style="', $after='"', $echo = true ){
		$player = \Vimeotheque\Helper::get_embed_options( self::get_player_options() );
		if( $echo ){
			echo $before . 'width: ' . $player['width'].'px; ' . $after;
		}
		return $before . 'width: ' . $player['width'].'px; ' . $after;
	}

	/**
	 * @param string $before
	 * @param string $after
	 * @param bool $echo
	 *
	 * @return string
	 */
	public static function get_player_size( $before = ' style="', $after='"', $echo = true ){
		$player = \Vimeotheque\Helper::get_embed_options( self::get_player_options() );
		$height = \Vimeotheque\Helper::calculate_player_height( $player['aspect_ratio'], $player['width'] );

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

	/**
	 * Get the current video in loop
	 *
	 * @return Video_Post
	 */
	public static function current_video_post(){
		global $cvm_video;

		if( !$cvm_video ){
			_doing_it_wrong(__METHOD__, 'You should use this into a foreach() loop. Correct usage is: <br />foreach( $videos as $cvm_video ){ '.__METHOD__.'(); } ', '3.0');
			return false;
		}

		return $cvm_video;
	}

	/**
	 * @param bool $echo
	 *
	 * @return string
	 */
	public static function get_player_data_attributes( $echo = true ){
		$player = \Vimeotheque\Helper::get_embed_options( self::get_player_options() );
		$attributes = \Vimeotheque\Helper::data_attributes( $player, $echo );

		if( $echo ){
			echo $attributes;
		}

		return $attributes;
	}

	public static function get_player_options(){
		global $CVM_PLAYER_SETTINGS;
		return $CVM_PLAYER_SETTINGS;
	}
}