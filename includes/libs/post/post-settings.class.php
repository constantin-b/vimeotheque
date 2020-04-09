<?php

namespace Vimeotheque\Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Post_Settings
 * @package Vimeotheque
 */
class Post_Settings{
	/**
	 * Video ID meta name
	 *
	 * @var string
	 */
	private $meta_video_id = '__cvm_video_id';
	/**
	 * Video URL meta name
	 *
	 * @var string
	 */
	private $meta_video_url = '__cvm_video_url';
	/**
	 * Video data meta name
	 *
	 * @var string
	 */
	private $meta_video_data = '__cvm_video_data';
	/**
	 * Is video meta flag
	 *
	 * @var string
	 */
	private $meta_is_video = '__cvm_is_video';
	/**
	 * Embed options meta name
	 *
	 * @var string
	 */
	private $meta_embed_settings = '__cvm_playback_settings';

	/**
	 * @var Post_Type
	 */
	private $post_type;

	/**
	 * @var array
	 */
	private $options;

	/**
	 * Post_Settings constructor.
	 *
	 * @param Post_Type $post_type
	 */
	public function __construct( Post_Type $post_type ) {
		$this->post_type = $post_type;
		$this->options = $post_type->get_plugin()->get_options();
	}

	/**
	 * Returns if condition for importing images is on post display
	 * or post create.
	 *
	 * @param string $situation - "post_create" or "post_display"
	 *
	 * @return bool
	 */
	public function image_import( $situation = 'post_create' ){

		if( !isset( $this->options['featured_image'] ) || !$this->options['featured_image'] ){
			return false;
		}

		switch( $situation ){
			case 'post_create':
				return !(bool) $this->options['image_on_demand'];
				break;
			case 'post_display':
				return (bool) $this->options['image_on_demand'];
				break;
			default:
				trigger_error(
					sprintf(
						'Image import condition %s not found. Use either "post_create" or "post_display".',
						$situation
					) ,
					E_USER_WARNING
				);
				break;
		}

		return false;
	}

	/**
	 * Get import post status from plugin options
	 *
	 * @param bool $status
	 *
	 * @return bool|string
	 */
	public function post_status( $status = false ){
		if( !$status ){
			$status = $this->options['import_status'];
		}

		$status	= in_array( $status, [ 'publish', 'draft', 'pending' ] ) ? $status : 'draft';

		return $status;
	}

	/**
	 * Check to see if option to import as post is on
	 *
	 * @return bool
	 */
	public function import_as_post(){
		if( isset( $this->options['post_type_post'] ) && $this->options['post_type_post'] ){
			return (bool) $this->options['post_type_post'];
		}

		return false;
	}

	/**
	 * @return string
	 */
	public function get_meta_video_id(){
		return $this->meta_video_id;
	}

	/**
	 * @return string
	 */
	public function get_meta_video_url(){
		return $this->meta_video_url;
	}

	/**
	 * @return string
	 */
	public function get_meta_video_data(){
		return $this->meta_video_data;
	}

	/**
	 * @return string
	 */
	public function get_meta_is_video(){
		return $this->meta_is_video;
	}

	/**
	 * @return string
	 */
	public function get_meta_embed_settings(){
		return $this->meta_embed_settings;
	}
}