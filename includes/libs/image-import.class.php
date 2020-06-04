<?php

namespace Vimeotheque;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Image_Import
 * @package Vimeotheque
 */
class Image_Import {
	/**
	 * @var Video_Post
	 */
	private $video_post;

	/**
	 * Image_Import constructor.
	 *
	 * @param Video_Post $video_post
	 */
	public function __construct( Video_Post $video_post ) {
		$this->video_post = $video_post;
	}

	/**
	 * @param bool $refresh
	 *
	 * @return array|bool|void
	 */
	public function set_featured_image( $refresh = false ){
		if( !$this->video_post->video_id ){
			return;
		}

		if( $refresh ){
			return $this->import_from_api();
		}else{
			// check if thumbnail was already imported
			$attachment = get_posts( [
				'post_type' 	=> 'attachment',
				'meta_key'  	=> 'video_thumbnail',
				'meta_value'	=> $this->video_post->video_id
			] );
			// if thumbnail exists, return it
			if( $attachment ){
				// set image as featured for current post
				set_post_thumbnail( $this->video_post->get_post()->ID, $attachment[0]->ID );
				return [
					'post_id' 		=> $this->video_post->get_post()->ID,
					'attachment_id' => $attachment[0]->ID
				];
			}else{
				return $this->import_from_api();
			}
		}
	}

	/**
	 * @return array|bool
	 */
	private function import_from_api(){
		$q = new Video_Import( 'thumbnails', $this->video_post->video_id );
		$thumbnails = $q->get_feed();
		if( $thumbnails ){
			$img = end( $thumbnails );
			return $this->import_to_media( $img );
		}
	}

	/**
	 * @param $image_url
	 *
	 * @return array|bool
	 */
	private function import_to_media( $image_url ){
		// get the thumbnail
		$request = wp_remote_get(
			$image_url,
			[
				'user-agent' => Helper::request_user_agent(),
				'sslverify' => false,
				/**
				 * Request timeout filter
				 * @var int
				 */
				'timeout' => apply_filters( 'vimeotheque\image_request_timeout', 30 )
			]
		);

		if( is_wp_error( $request ) || 200 != wp_remote_retrieve_response_code( $request ) ) {
			return false;
		}

		$image_contents = $request['body'];
		$image_type = wp_remote_retrieve_header( $request, 'content-type' );
		// Translate MIME type into an extension
		if ( $image_type == 'image/jpeg' ){
			$image_extension = '.jpg';
		}elseif ( $image_type == 'image/png' ){
			$image_extension = '.png';
		}

		// Construct a file name using post slug and extension
		$fname = urldecode( basename( get_permalink( $this->video_post->get_post()->ID ) ) ) ;
		$new_filename = preg_replace( '/[^A-Za-z0-9\-]/', '', $fname ) .
		                '-vimeo-thumbnail' .
		                $image_extension;

		// Save the image bits using the new filename
		$upload = wp_upload_bits( $new_filename, null, $image_contents );
		if ( $upload['error'] ) {
			return false;
		}

		$image_url = $upload['url'];
		$filename = $upload['file'];

		/**
		 * Action that allows modification of image that will be attached to video post
		 *
		 * @param $filename - complete path to original video image within WP gallery
		 * @param $post_id - the post ID that the image will be attached to as featured image
		 * @param $video_id - the video ID
		 */
		do_action(
			'vimeotheque\image_file_raw',
			$filename,
			$this->video_post->get_post()->ID,
			$this->video_post->video_id
		);

		$wp_filetype = wp_check_filetype( basename( $filename ), null );
		$attachment = [
			'post_mime_type'	=> $wp_filetype['type'],
			'post_title'		=> get_the_title( $this->video_post->get_post()->ID ).' - Vimeo thumbnail',
			'post_content'		=> '',
			'post_status'		=> 'inherit',
			'guid'				=> $image_url
		];
		$attach_id = wp_insert_attachment( $attachment, $filename, $this->video_post->get_post()->ID );
		// you must first include the image.php file
		// for the function wp_generate_attachment_metadata() to work
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		// Add field to mark image as a video thumbnail
		update_post_meta(
			$attach_id,
			'video_thumbnail',
			$this->video_post->video_id
		);

		// set image as featured for current post
		update_post_meta(
			$this->video_post->get_post()->ID,
			'_thumbnail_id',
			$attach_id
		);

		/**
		 * Trigger action on plugin import
		 */
		do_action(
			'vimeotheque\image_imported',
			$attach_id,
			$this->video_post->video_id,
			$this->video_post->get_post()->ID
		);

		return [
			'post_id' 		=> $this->video_post->get_post()->ID,
			'attachment_id' => $attach_id
		];
	}

}