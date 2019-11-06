<?php

namespace Vimeotheque;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * REST API implementation
 * Add all meta fields to video post
 * @author CodeFlavors
 *
 */
class Rest_Api{
	/**
	 * Custom post type class reference
	 * @var Post_Type
	 */
	private $cpt;

	/**
	 * Constructor
	 *
	 * @param Post_Type $cpt
	 */
	public function __construct( Post_Type $cpt ){
		// store custom post type reference
		$this->cpt = $cpt;
		// add init action
		add_action( 'rest_api_init', [ $this, 'api_init' ] );
	}

	/**
	 * REST API init callback
	 */
	public function api_init(){
		register_rest_field(
				[ $this->cpt->get_post_type(), 'post' ],
				'vimeo_video',
				[
					'get_callback' => [ $this, 'register_field' ],
					//'update_callback' => NULL,
					//'schema' => array()
				]
		);
	}

	/**
	 * Post array returned by REST API
	 *
	 * @param array $object
	 *
	 * @return array|null
	 */
	public function register_field( $object ){
		$post_id = $object['id'];
		$meta = Helper::get_video_post( $post_id )->get_video_data();
		$response = NULL;

		if( is_array( $meta ) ){
			$response = [
				'video_id'		=> $meta['video_id'],
				'uploader'		=> $meta['uploader'],
				'uploader_uri'	=> $meta['uploader_uri'],
				'published' 	=> $meta['published'],
				'_published'	=> $meta['_published'],
				'updated'		=> $meta['updated'],
				'title'			=> $meta['title'],
				'description' 	=> $meta['description'],
				'tags'			=> ( isset( $meta['tags'] ) ? $meta['tags'] : [] ),
				'duration'		=> $meta['duration'],
				'_duration'		=> $meta['_duration'],
				'thumbnails'	=> $meta['thumbnails'],
				'stats'			=> $meta['stats'],
				'privacy'		=> $meta['privacy'], // set by the plugin
				'view_privacy'	=> $meta['view_privacy'], // the original Vimeo privacy setting
				'embed_privacy' => $meta['embed_privacy'], // the original Vimeo privacy embed setting
				'size'			=> $meta['size'],
				// Vimeo on Demand
				'type' 	=> $meta['type'],
				'uri'	=> $meta['uri'],
				'link'	=> $meta['link']
			];
		}

		return $response;
	}
}