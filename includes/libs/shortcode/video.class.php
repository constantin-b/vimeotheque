<?php

namespace Vimeotheque\Shortcode;

use Vimeotheque\Helper;
use Vimeotheque\Plugin;
use Vimeotheque\Video_Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Video
 * @package Vimeotheque\Shortcode
 */
class Video extends Shortcode_Abstract implements Shortcode_Interface {
	/**
	 * @var Video_Post|null
	 */
	private $post = null;

	/**
	 * Video constructor.
	 *
	 * @param $atts
	 * @param $content
	 */
	public function __construct( $atts, $content ) {
		parent::__construct( $atts, $content );
		if( parent::get_attr( 'id' ) ){
			$this->post = Helper::get_video_post( parent::get_attr('id') );
		}
	}

	/**
	 * @return bool|string|void
	 */
	public function get_output() {
		if( !$this->post ){
			return;
		}

		$options = $this->post->get_embed_options();

		$vars = shortcode_atts( [
			'volume' => $options['volume'],
			'width' => $options['width'],
			'aspect_ratio' => $options['aspect_ratio'],
			'loop' => $options['loop'],
			'autoplay' => $options['autoplay']
		], parent::get_atts() );

		if( !$vars['width'] ){
			trigger_error( 'No width specified for shortcode.', E_USER_NOTICE );
			return false;
		}

		$embed_settings = wp_parse_args( $vars, $options );
		$embed_settings['video_id'] = $this->post->video_id;

		$height = Helper::calculate_player_height(
			$embed_settings['aspect_ratio'],
			$embed_settings['width']
		);

		if( !$height ){
			trigger_error( 'Player height could not be calculated.', E_USER_NOTICE );
			return;
		}

		$class = [ 'cvm_single_video_player', 'cvm-shortcode' ];
		$embed_html = '<!-- Vimeotheque video player -->';

		$js_embed = Plugin::instance()->get_player_options()->get_option( 'js_embed' );

		if( !is_wp_error( $js_embed ) && !$js_embed ){
			$class[] = 'cvm_simple_embed';
			$embed_html = sprintf(
				'<iframe src="%s" width="100%%" height="100%%" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',
				sprintf(
					'https://player.vimeo.com/video/%s?%s',
					$this->post->video_id,
					http_build_query(
						[
							'title' => $embed_settings['title'],
							'byline' => $embed_settings['byline'],
							'portrait' => $embed_settings['portrait'],
							'loop' => $embed_settings['loop'],
							'color' => $embed_settings['color']
						], '', '&'
					)
				)
			);
		}

		$video_container = sprintf(
			'<div class="%s" %s style="width: %dpx; height: %dpx; max-width:100%%">%s</div>',
			implode( ' ', $class ),
			Helper::data_attributes( $embed_settings ),
			$embed_settings['width'],
			$height,
			$embed_html
		);

		Helper::enqueue_player( $js_embed );

		return $video_container;
	}
}