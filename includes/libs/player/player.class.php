<?php
/**
 * @author CodeFlavors
 * @project Vimeotheque 2.0 Lite
 */

namespace Vimeotheque\Player;

use Vimeotheque\Helper;
use Vimeotheque\Video_Post;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Player {
	/**
	 * @var Video_Post
	 */
	private $post;

	/**
	 * Initial options that will override any options retrieved from post
	 * options
	 *
	 * @var array
	 */
	private $manual_options;

	/**
	 * @var mixed|void
	 */
	private $options;

	/**
	 * Player constructor.
	 *
	 * @param Video_Post $post
	 * @param array $options
	 */
	public function __construct( Video_Post $post, $options = [] ) {
		$this->post           = $post;
		$this->manual_options = $options;
		$this->set_post_embed_options();
	}

	/**
	 * Embed output
	 *
	 * @param bool $echo
	 *
	 * @param bool $width
	 *
	 * @return string|void
	 */
	public function get_output( $echo = true, $width = false ){
		if( !$this->post->is_video() ){
			return;
		}

		$_width = $width ? absint( $width ) : $this->get_embed_width();
		$height = $this->get_embed_height( $_width );
		$css_class = $this->get_css_classes();
		$iframe = sprintf(
			'<iframe src="%s" width="100%%" height="100%%" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',
			$this->get_embed_url()
		);

		$video_container = sprintf(
			'<div class="vimeotheque-player %s" %s style="width:%spx; height:%spx; max-width:100%%;">%s</div>',
			$css_class,
			$this->get_data_attributes(),
			$_width,
			$height,
			$iframe
		);

		if( $echo ){
			echo $video_container;
		}

		return $video_container;
	}

	/**
	 * Get video embedding options
	 *
	 * @return mixed|void
	 */
	private function set_post_embed_options(){
		/**
		 * Allow embed settings filtering that can change the embedding options when the post is displayed.
		 *
		 * @param array $embed_settings     The post video embed settings
		 * @param object $post              The current post being displayed
		 * @param array $video              The video details as retrieved from Vimeo
		 */
		$this->options = apply_filters(
			'vimeotheque\player\embed_options',
			wp_parse_args(
				$this->manual_options,
				$this->post->get_embed_options( true )
			),
			$this->post->get_post(),
			$this->post->get_video_data()
		);
	}

	/**
	 * @return mixed|void
	 */
	private function get_embed_width(){
		/**
		 * Filter that can be used to modify the width of the embed
		 *
		 * @param int $width    Width in pixels
		 */
		return apply_filters(
			'vimeotheque\player\embed_width',
			$this->options['width'],
			$this->post->get_video_data(),
			$this->post->get_post()
		);
	}

	/**
	 * Calculate height based on width
	 *
	 * @param $width
	 *
	 * @return float|int
	 */
	private function get_embed_height( $width ){
		return Helper::calculate_player_height(
			$this->options['aspect_ratio'],
			$width,
			$this->options['size_ratio']
		);
	}

	/**
	 * Returns additional CSS classes
	 *
	 * @return string
	 */
	private function get_css_classes(){
		/**
		 * Generate additional CSS classes on Vimeotheque embed player container
		 *
		 * @param array $classes    CSS classes to be added
		 * @param \WP_Post $post    The post object reference
		 */
		$classes = apply_filters(
			'vimeotheque\player\css_class',
			[
				$this->options['video_align']
			],
			$this->post->get_post()
		);

		return implode( ' ', (array) $classes );
	}

	/**
	 * Generates options
	 *
	 * @since 2.0.14    Modified method visibility from private to public
	 *
	 * @return string
	 */
	public function get_embed_url(){
		$options = [
			'title' => $this->options['title'],
			'byline' => $this->options['byline'],
			'portrait' => $this->options['portrait'],
			'loop' => $this->options['loop'],
			'autoplay' => $this->options['autoplay'],
			'color' => str_replace( '#', '', $this->options['color'] ),
			'dnt' => $this->options['dnt'],
			'transparent' => true
		];

		/**
		 * Filter to allow extra parameters to be put on the embed URL in iframe
		 *
		 * @param array $options            The extra options
		 * @param \WP_Post|false|null $post The WordPress post object
		 * @param array $video_details      The video details array attached to the post
		 */
		$extra = apply_filters(
			'vimeotheque\player\embed-parameters',
			[],
			$this->post->get_post(),
			$this->post->get_video_data(),
			$this->manual_options
		);

		if( $extra && is_array( $extra ) ){
			$options = array_merge( $extra, $options );
		}

		return sprintf(
			'%s?%s',
			$this->post->get_embed_url(),
			http_build_query( $options, '', '&' )
		);
	}

	/**
	 * @return string[]
	 */
	private function get_data_attributes(){
		$result = [];
		// loop attributes
		foreach( $this->options as $key=>$value ){
			$result[] = sprintf(
				'data-%s="%s"',
				$key,
				$value
			);
		}

		$result[] = sprintf( 'data-video_id="%s"', $this->post->video_id );

		return implode(' ', $result);
	}
}