<?php

namespace Vimeotheque\Vimeo_Api;

use function Vimeotheque\human_time;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * All fields are detailed here: https://developer.vimeo.com/api/reference/responses/video
 *
 * Class Entry_Format
 * @package Vimeotheque
 */
class Entry_Format{

	/**
	 * The raw, unprocessed entry
	 *
	 * @var array
	 */
	private $_entry;

	/**
	 * The processed entry
	 *
	 * @var array
	 */
	private $formatted_entry;

	/**
	 * @var Resource_Interface
	 */
	private $api_resource;

	/**
	 * Entry_Format constructor.
	 *
	 * @param $entry
	 * @param Resource_Interface $api_resource
	 */
	public function __construct( $entry, Resource_Interface $api_resource ) {
		$this->_entry = $entry;
		$this->api_resource = $api_resource;
		$this->formatted_entry = $this->get_formatted_entry();
		$this->set_optional_fields();
	}

	/**
	 * @return array
	 */
	private function get_formatted_entry(){
		return [
			'video_id'      => $this->get_video_id(),
			'uploader'      => $this->_entry['user']['name'],
			'uploader_uri'  => $this->_entry['user']['uri'],
			'published'     => $this->get_publish_date(),
			'_published'    => date( 'M dS, Y', strtotime( $this->get_publish_date() ) ),
			'updated'       => $this->get_field( 'modified_time' ),
			'title'         => (string) $this->get_field( 'name' ),
			'description'   => (string) $this->get_field( 'description' ),
			'category'      => false, // @todo see where categories are stored and process them
			'tags'          => $this->get_tags(),
			'duration'      => (int) $this->get_field( 'duration' ),
			'_duration'     => human_time( (int) $this->get_field( 'duration' ) ),
			'thumbnails'    => $this->get_thumbnails(),
			'stats'         => $this->get_stats(),
			'privacy'       => $this->get_privacy(),
			'view_privacy'  => $this->get_field( 'privacy' ) ? $this->_entry['privacy']['view'] : false,
			'embed_privacy' => $this->get_field( 'privacy' ) ? $this->_entry['privacy']['embed'] : false,
			'size'          => $this->get_size(),
			'type'          => $this->get_field( 'type' ), // can be live, stock or video
			'uri'           => $this->get_field( 'uri' ),
			'link'          => $this->get_field( 'link' )
		];
	}

	/**
	 * Process the optional fields set by third party
	 */
	private function set_optional_fields(){
		$fields = (array) $this->api_resource->get_optional_fields();
		if( $fields ){
			foreach( $fields as $field ){
				$_field = $this->get_field( $field );
				if( !is_null( $_field ) ){
					$this->formatted_entry[ $field ] = $_field;
				}
			}
		}
	}

	/**
	 * @return mixed|null
	 */
	private function get_video_id(){
		/**
		 * Videos with private link have uri like: /videos/174336869:444d9d089b
		 * To match the ID, look for :
		 */
		preg_match( '#/videos/([^:]+)(.*)$#' , $this->get_field('uri'), $matches);
		return isset( $matches[1] ) ? $matches[1] : NULL;
	}

	/**
	 * @return bool|mixed
	 */
	private function get_publish_date(){
		$publish_date = false;
		if( $this->get_field( 'created_time' ) ){
			$publish_date = $this->_entry['created_time'];
		}else if( $this->get_field( 'release_date' ) ){
			$publish_date = $this->_entry['release_date'];
		}
		return $publish_date;
	}

	/**
	 * @return array
	 */
	private function get_tags(){
		$_tags = $this->get_field( 'tags' );
		$tags = [];
		if( $_tags && is_array( $_tags ) ){
			foreach( $_tags as $tag ){
				$tags[] = $tag['name'];
			}
		}
		return $tags;
	}

	/**
	 * @return array
	 */
	private function get_thumbnails(){
		$thumbnails = [];
		$_thumbnails = $this->get_field( 'pictures' );

		if( $_thumbnails ){
			foreach( $_thumbnails['sizes'] as $thumbnail ){
				$thumbnails[ $thumbnail['width'] ] = $thumbnail['link'];
			}
			ksort( $thumbnails, SORT_NUMERIC );
			$thumbnails = array_values( $thumbnails );
		}

		return $thumbnails;
	}

	/**
	 * @return array
	 */
	private function get_stats(){
		$stats = [
			'comments' 	=> 0,
			'likes' 	=> 0,
			'views' 	=> 0
		];

		if( isset( $this->_entry['metadata']['connections']['comments']['total'] ) ){
			$stats['comments'] = $this->_entry['metadata']['connections']['comments']['total'];
		}

		if( isset( $this->_entry['metadata']['connections']['likes']['total'] ) ){
			$stats['likes'] = $this->_entry['metadata']['connections']['likes']['total'];
		}

		if( isset( $this->_entry['stats']['plays'] ) ){
			$stats['views'] = $this->_entry['stats']['plays'];
		}

		return $stats;
	}

	/**
	 * @return bool|string
	 */
	private function get_privacy(){
		/**
		 * View privacy on Vimeo can have the following values:
		 *
		 * - anybody - video is visible for everyone
		 * - nobody - video can be viewed only by the owner
		 * - contacts - video can be viewed only by the uploader's followers
		 * - users - video can be viewed only by specified users
		 * - password - video is password protected
		 * - unlisted - video can be viewed using a special, private link
		 * - disable - video won't be displayed on vimeo.com
		 */
		$privacy = false;
		if( $this->get_field( 'privacy' ) ){
			if( in_array( $this->_entry['privacy']['view'], [ 'anybody', 'unlisted', 'disable' ] ) ){
				$privacy = 'public';
			}else{
				$privacy = 'private';
			}
		}

		return $privacy;
	}

	/**
	 * @return array
	 */
	private function get_size(){
		$size = [
			'width' => (int) $this->get_field( 'width' ),
			'height' => (int) $this->get_field( 'height' ),
			'ratio' => 0
		];

		if( $size['height'] ){
			$size['ratio'] = round( $size['width'] / $size['height'], 2 );
		}

		return $size;
	}

	/**
	 * @param $name
	 *
	 * @return mixed|null
	 */
	private function get_field( $name ){
		return isset( $this->_entry[ $name ] ) ? $this->_entry[ $name ] : null;
	}

	/**
	 * @return array
	 */
	public function get_entry(){
		return $this->formatted_entry;
	}

}