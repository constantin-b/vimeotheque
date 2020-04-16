<?php

namespace Vimeotheque;

use Vimeotheque\Options\Options;
use Vimeotheque\Post\Post_Type;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Post
 * @package Vimeotheque
 */
class Video_Post{

	/**
	 * Vimeo video ID
	 *
	 * @var string
	 */
	public $video_id = '';

	/**
	 * Vimeo uploader username
	 *
	 * @var string
	 */
	public $uploader = '';

	/**
	 * Vimeo uploader URL
	 *
	 * @var string
	 */
	public $uploader_uri = '';

	/**
	 * Vimeo publishing date
	 *
	 * @var string
	 */
	public $published = '';

	/**
	 * Vimeo publishing date formatted as "M dS, Y"
	 *
	 * @var string
	 */
	public $_published = '';

	/**
	 * Vimeo video update date
	 *
	 * @var string
	 */
	public $updated = '';

	/**
	 * Vimeo video title
	 *
	 * @var string
	 */
	public $title = '';

	/**
	 * Vimeo video description
	 *
	 * @var string
	 */
	public $description = '';

	/**
	 * @todo Determine how categories will be stored
	 *
	 * @var
	 */
	public $category;

	/**
	 * Vimeo video tags
	 *
	 * @var array
	 */
	public $tags = [];

	/**
	 * Vimeo video duration
	 *
	 * @var string
	 */
	public $duration = '';

	/**
	 * Vimeo video duration as HH:MM:SS
	 *
	 * @var string
	 */
	public $_duration = '';

	/**
	 * Vimeo video thumbnails
	 *
	 * @var array
	 */
	public $thumbnails = [];

	/**
	 * Video statistics (view count, comment count and like count)
	 *
	 * @var array
	 */
	public $stats = [];

	/**
	 * Video privacy
	 *
	 * @var string
	 */
	public $privacy = '';

	/**
	 * Vimeo view privacy
	 *
	 * @var string
	 */
	public $view_privacy = '';

	/**
	 * Vimeo embed privacy
	 *
	 * @var string
	 */
	public $embed_privacy = '';

	/**
	 * Video size (width, height and size ratio)
	 *
	 * @var array
	 */
	public $size = [];

	/**
	 * Vimeo video type (video, live)
	 *
	 * @var string
	 */
	public $type = '';

	/**
	 * Vimeo video URI (ie. videos/2312953)
	 *
	 * @var string
	 */
	public $uri = '';

	/**
	 * Vimeo video URL
	 *
	 * @var string
	 */
	public $link = '';

	/**
	 * The WP_Post object
	 *
	 * @var array|\WP_Post|null
	 */
	private $_post = null;

	/**
	 * @var Image_Import
	 */
	private $_image;

	/**
	 * Post constructor.
	 *
	 * @param \WP_Post|int $post
	 */
	public function __construct( $post ) {
		$this->_post  = get_post( $post, OBJECT, 'raw' );
		$this->_image = new Image_Import( $this );

		$meta = $this->get_video_data();
		if( $meta ){
			$this->_set_properties( $meta );
		}
	}

	/**
	 * Set class properties
	 *
	 * @param $data
	 */
	private function _set_properties( $data ){
		foreach( $data as $key => $value ){
			if( property_exists( $this, $key ) ){
				$this->$key = $value;
			}
		}
	}

	/**
	 * Check if it is a video post
	 *
	 * @return bool
	 */
	public function is_video(){
		if( !$this->get_post() ){
			return false;
		}

		$result = $this->cpt()->get_post_type() == $this->get_post()->post_type;

		return apply_filters( 'vimeotheque\video\is_video', $result, $this );
	}

	/**
	 * Get video dtaa stored on post
	 *
	 * @return mixed|void
	 */
	public function get_video_data(){
		if( !$this->get_post() ){
			return [];
		}

		$meta = $this->get_meta(
			$this->cpt()->get_post_settings()->get_meta_video_data()
		);

		if( isset( $meta['video_id'] ) && stristr( $meta['video_id'] , ':' ) ){
			$parts = explode( ':', $meta['video_id'] );
			$meta['video_id'] = $parts[0];
		}

		return $meta;
	}

	/**
	 * Set video data on post
	 *
	 * @param $data
	 *
	 * @return bool|int|void
	 */
	public function set_video_data( $data ){
		if( !$this->_post ){
			return;
		}

		$_data = $this->get_video_data();
		if( $_data ) {
			foreach ( $data as $key => $value ) {
				if ( isset( $_data[ $key ] ) ) {
					$_data[ $key ] = $value;
				}
			}
		}else{
			// if set for the first time, set the entire data as post meta
			$_data = $data;
			$this->_set_properties( $data );
		}

		return $this->update_meta(
			$this->cpt()->get_post_settings()->get_meta_video_data(),
			$_data
		);
	}

	/**
	 * Returns embed options for the post
	 *
	 * @param bool $output
	 *
	 * @return array|mixed|void
	 */
	public function get_embed_options( $output = false ){
		if( !$this->_post ){
			return;
		}
		/**
		 * @var Options
		 */
		$options_obj = Helper::get_embed_options();

		if( $options_obj->get_option( 'allow_override' ) ){
			$options = $options_obj->get_options();
		}else{
			$options = $this->get_meta( $this->cpt()->get_post_settings()->get_meta_embed_settings() );
			foreach ( $options_obj->get_options() as $key => $value ){
				if( !isset( $options[ $key ] ) ){
					$options[ $key ] = $value;
				}
				// when values are in output booleans needs to be 0 or 1
				if( $output && is_bool( $value ) ){
					$options[ $key ] = absint( $options[ $key ] );
				}
			}
		}

		$options['size_ratio'] = false;
		if( !is_wp_error( $options_obj->get_option('aspect_override') ) ){
			$options['size_ratio'] = isset( $this->size['ratio'] ) ? $this->size['ratio'] : false;
		}

		return $options;
	}

	/**
	 * Unpublish the post by changing its status
	 */
	public function unpublish(){
		$this->set_post_status( 'pending' );
	}

	/**
	 * @param string $post_status
	 *
	 * @return int|void|\WP_Error
	 */
	private function set_post_status( $post_status = 'publish' ){
		if( !$this->_post ){
			return;
		}

		$statuses = ['publish', 'pending', 'draft', 'private'];
		if( !in_array( $post_status, $statuses ) ){
			trigger_error(
				sprintf(
					'Post status cannot be changed to %s. Allowed values are: %s.',
					$post_status,
					implode( ', ', $statuses )
				),
				E_USER_WARNING
			);
			return;
		}

		return wp_update_post([
			'post_status' => $post_status,
			'ID' => $this->_post->ID
		]);
	}

	/**
	 * Set video ID meta
	 */
	public function set_video_id_meta(){
		if( $this->video_id ) {
			$this->update_meta(
				$this->cpt()->get_post_settings()->get_meta_video_id(),
				$this->video_id
			);
		}
	}

	/**
	 * Set video url meta
	 */
	public function set_video_url_meta(){
		if( $this->link ){
			$this->update_meta(
				$this->cpt()->get_post_settings()->get_meta_video_url(),
				$this->link
			);
		}
	}

	/**
	 * @param $key
	 * @param $value
	 *
	 * @return bool|int
	 */
	private function update_meta( $key, $value ){
		if( $this->_post ) {
			return update_post_meta(
				$this->_post->ID,
				$key,
				$value
			);
		}
	}

	/**
	 * @param $key
	 * @param bool $single
	 * @param array $default - a default value that should be returned in case the meta isn't found
	 *
	 * @return mixed
	 */
	private function get_meta( $key, $single = true, $default = [] ){
		if( $this->_post ){
			$meta = get_post_meta(
				$this->_post->ID,
				$key,
				$single
			);

			return $meta ? $meta : $default;
		}

		return $default;
	}

	/**
	 * Set featured image on post
	 *
	 * @param bool $refresh
	 *
	 * @return array|bool|void
	 */
	public function set_featured_image( $refresh = false ){
		return $this->_image->set_featured_image( $refresh );
	}

	/**
	 * Return duration in ISO format (ie. PT1H43M23S)
	 *
	 * @return string
	 */
	public function get_iso_duration(){
		$seconds = $this->duration;
		$iso_format = 'PT';

		if ( $seconds > 3600 ) {
			$hours = floor( $seconds / 3600 );
			$iso_format .= $hours . 'H';
			$seconds = $seconds - ( $hours * 3600 );
		}

		if ( $seconds > 60 ) {
			$minutes = floor( $seconds / 60 );
			$iso_format .= $minutes . 'M';
			$seconds = $seconds - ( $minutes * 60 );
		}

		if ( $seconds > 0 ) {
			$iso_format .= $seconds . 'S';
		}

		return $iso_format;
	}

	/**
	 * Returns embed URL
	 *
	 * @return string
	 */
	public function get_embed_url(){
		return sprintf(
			'https://player.vimeo.com/video/%s',
			$this->video_id
		);
	}

	/**
	 * @return Post_Type
	 */
	private function cpt(){
		return Plugin::instance()->get_cpt();
	}

	/**
	 * @return array|\WP_Post|null
	 */
	public function get_post(){
		return $this->_post;
	}

}