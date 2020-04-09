<?php

namespace Vimeotheque;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Vimeotheque\Post\Post_Type;
use WP_Error;

/**
 * Class Posts_Import
 * @package Vimeotheque
 */
class Posts_Import{
	/**
	 * @var Post_Type
	 */
	private $post_type;

	/**
	 * Posts_Import constructor.
	 *
	 * @param Post_Type $post_type
	 */
	public function __construct( Post_Type $post_type ) {
		$this->post_type = $post_type;
	}

	/**
	 * Imports videos from a given feed source.
	 * Used by automatic updates.
	 *
	 * @param $raw_feed
	 * @param Feed|array $import_options
	 *
	 * @return array|void
	 */
	public function run_import( $raw_feed, $import_options ){
		/**
		 * @var bool $theme_import
		 * @var array $native_tax
		 * @var array $theme_tax
		 * @var array $native_tag
		 * @var array $theme_tag
		 * @var bool $import_description
		 * @var string $import_status
		 * @var bool $import_title
		 * @var bool $import_date
		 * @var int $import_user
		 */
		extract( $import_options, EXTR_SKIP );

		// get import options
		$options = \Vimeotheque\Plugin::instance()->get_options();

		// overwrite plugin import settings with import settings
		$options['import_description'] = $import_description;
		$options['import_status'] = $import_status;
		$options['import_title'] = $import_title;
		//$options['import_date'] = $import_date;

		/**
		 * Filter the import options
		 *
		 * @param array $import_options
		 * @param array $source
		 */
		$options = apply_filters( 'vimeotheque\import\options', $options, $import_options );

		/**
		 * Post type filter
		 * @param string $post_type
		 */
		$post_type = apply_filters( 'vimeotheque\import\post_type',  $this->post_type->get_post_type(), $options );

		/**
		 * Category taxonomy name filter
		 * @param string $taxonomy
		 */
		$taxonomy = apply_filters( 'vimeotheque\import\post_taxonomy_name', $this->post_type->get_post_tax(), $options );

		/**
		 * Tag taxonomy name filter
		 *
		 * @param string $tag_taxonomy
		 */
		$tag_taxonomy = apply_filters( 'vimeotheque\import\tag_taxonomy_name', $this->post_type->get_tag_tax(), $options );

		/**
		 * Filter categories that are set on post
		 *
		 * @param array|bool $category
		 */
		$category = apply_filters( 'vimeotheque\import\post_taxonomies', $native_tax, $options );

		/**
		 * Filter tags that are set on post
		 *
		 * @param array|bool $tags
		 */
		$tags = apply_filters( 'vimeotheque\import\tag_taxonomies', $native_tag, $options );

		/**
		 * Filter the post format
		 *
		 * @param string $post_format
		 */
		$post_format = apply_filters( 'vimeotheque\import\post_format', 'video', $options );

		// post status
		$post_status	= $this->post_type->get_post_settings()->post_status( $import_status );

		// set user
		$user = isset( $import_user ) ? absint( $import_user ) : false;

		// store results
		$result = [
			'private' 	=> 0,
			'imported' 	=> 0,
			'skipped' 	=> 0,
			'total'		=> count( $raw_feed ),
			'ids'		=> [],
			'error'		=> []
		];

		$duplicates = $this->get_duplicate_posts( $raw_feed, $post_type );

		// parse feed
		foreach( $raw_feed as $video ){

			// video already exists, don't do anything
			if( array_key_exists( $video['video_id'], $duplicates ) ){

				/**
				 * Generate an error and pass it for debugging
				 * @var WP_Error
				 */
				$error = new WP_Error(
					'cvm_automatic_import_skip_existing_video',
					sprintf(
						__( 'Skipped video having ID %s because it already exists (post id ' . $duplicates[ $video['video_id'] ][0] . ').', 'cvm-video' ),
						$video['video_id']
					),
					[
						'video_data' => $video,
						'existing_posts' => $duplicates[ $video['video_id'] ]
					]
				);
				$result['error'][] = $error;

				/**
				 * Action that will pass the error to any third party code that
				 * can log the import process.
				 */
				do_action( 'cvm_debug_automatic_import' , $error );

				foreach( $duplicates[ $video['video_id'] ] as $_post_id ){
					// retrieve the post object for backwards compatibility
					$post = get_post( $_post_id );

					/**
					 * Action triggered when finding skipped posts.
					 * Can be used to set extra taxonomies for already existing posts.
					 */
					do_action( 'cvm_existing_video_posts_taxonomies',
						$post,
						$taxonomy,
						$category,
						$tag_taxonomy,
						$tags
					);
				}

				$result['skipped'] += 1;
				continue;
			}

			if( 'private' == $video['privacy'] ){
				$result['private'] += 1;
				if( 'skip' == $options['import_privacy'] ){
					$result['skipped'] += 1;

					/**
					 * Generate an error and pass it for debugging
					 * @var WP_Error
					 */
					$error = new WP_Error(
						'cvm_automatic_import_skip_private_video',
						sprintf(
							__( 'Skipped private video having ID %s because of plugin settings.', 'cvm-video' ),
							$video['video_id']
						),
						[
							'video_data' => $video
						]
					);
					$result['error'][] = $error;
					/**
					 * Action that will pass the error to any third party code that
					 * can log the import process.
					 */
					do_action( 'cvm_debug_automatic_import' , $error );

					continue;
				}
			}

			$post_id = $this->import_video( [
				'video' 		=> $video, // video details retrieved from Vimeo
				'post_type' 	=> $post_type, // what post type to import as
				'taxonomy' 		=> $taxonomy, // what taxonomy should be used
				'category' 		=> $category, // category name (if any) - will be created if category_id is false
				'tag_taxonomy'	=> $tag_taxonomy,
				'tags'			=> $tags,
				'user'			=> $user, // save as a given user if any
				'post_format'	=> $post_format, // post format will default to video
				'status'		=> $post_status, // post status
				'theme_import'	=> false,
				'options'		=> $options
			] );

			if( $post_id ){
				$result['imported'] += 1;
				$result['ids'][] = $post_id;
				cvm_update_video_settings( $post_id, $import_options, true );
			}
		}

		return $result;
	}

	/**
	 * @param $raw_feed
	 * @param $post_type
	 *
	 * @return array
	 */
	public function get_duplicate_posts( $raw_feed, $post_type ){

		$video_ids = [];
		foreach( $raw_feed as $video ){
			$video_ids[] = $video['video_id'];
		}
		/**
		 * @var \WP_Query
		 */
		global $wpdb;
		$query = $wpdb->prepare(
			"
			SELECT {$wpdb->postmeta}.post_id, {$wpdb->postmeta}.meta_value 
			FROM {$wpdb->postmeta}
			LEFT JOIN {$wpdb->posts}
			ON {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID
			WHERE
			{$wpdb->posts}.post_type LIKE '%s' 
			AND meta_value IN(" . implode( ',', $video_ids ) . ")
			",
			$post_type
		);

		$existing = $wpdb->get_results( $query );
		$result = [];

		if( $existing ){
			foreach( $existing as $r ){
				$result[ $r->meta_value ][] = $r->post_id;
			}
		}

		return $result;
	}

	/**
	 * Import a single video based on the passed data
	 *
	 * @param array $args
	 *
	 * @return bool|int
	 */
	public function import_video( $args = [] ){

		$defaults = [
			'video' 			=> [], // video details retrieved from Vimeo
			'post_id'           => false,
			'category' 			=> false, // category name (if any) - will be created if category_id is false
			'post_type' 		=> false, // what post type to import as
			'taxonomy' 			=> false, // what taxonomy should be used
			'tag_taxonomy'		=> false,
			'tags'				=> false,
			'user'				=> false, // save as a given user if any
			'post_format'		=> 'video', // post format will default to video
			'status'			=> 'draft', // post status
			'theme_import'		=> false,
			'options'			=> false,
		];
		/**
		 * @var array $video
		 * @var int $post_id
		 * @var string $category
		 * @var string $post_type
		 * @var string $taxonomy
		 * @var string $tag_taxonomy
		 * @var array $tags
		 * @var int $user
		 * @var string $post_format
		 * @var string $status
		 * @var array $theme_import
		 * @var array $options
		 */
		extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );

		// if no video details or post type, bail out
		if( !$video || !$post_type ){
			return false;
		}

		/**
		 * Filter that allows changing of post format when importing videos
		 * @var string - post format
		 */
		$post_format = apply_filters( 'cvm_import_post_format' , $post_format );

		/**
		 * Filter that allows video imports. Can be used to prevent importing of
		 * videos.
		 *
		 * @param $video - video details array
		 * @param $post_type - post type that should be created from the video details
		 * @param $theme_import - if video should be imported as theme compatible post, holds theme details array
		 */
		$allow_import = apply_filters('cvm_allow_video_import', true, $video, $post_type, $theme_import );
		if( !$allow_import ){
			/**
			 * Generate an error and pass it for debugging
			 * @var WP_Error
			 */
			$error = new WP_Error(
				'cvm_video_import_prevented_by_filter',
				sprintf(
					__( 'Video having ID %s could not be imported because filter "cvm_allow_video_import" was set to "false".', 'cvm-video' ),
					$video['video_id']
				),
				[ 'video_data' => $video ]
			);
			/**
			 * Action that will pass the error to any third party code that
			 * can log the import process.
			 */
			do_action( 'cvm_debug_bulk_import' , $error );

			return false;
		}

		// plugin settings; caller can pass their own import options
		if( !$options ){
			$options = \Vimeotheque\Plugin::instance()->get_options();
		}

		if( 'private' == $video['privacy'] && 'pending' == $options['import_privacy'] ){
			$status = 'pending';
		}

		// post content
		$post_content = '';
		if( 'content' == $options['import_description'] || 'content_excerpt' == $options['import_description'] ){
			$post_content = $video['description'];
		}
		// post excerpt
		$post_excerpt = '';
		if( 'excerpt' == $options['import_description'] || 'content_excerpt' == $options['import_description'] ){
			$post_excerpt = $video['description'];
		}

		// post title
		$post_title 	= $options['import_title'] ? $video['title'] : '';

		// action on post insert that allows setting of different meta on post
		do_action('cvm_before_post_insert', $video, $theme_import);

		// set post data
		$post_data = [
			/**
			 * Filter on post title
			 *
			 * @param string - the post title
			 * @param array - the video details
			 * @param bool/array - false if not imported as theme, array if imported as theme and theme is active
			 */
			'post_title' 	=> apply_filters('cvm_video_post_title', $post_title, $video, $theme_import),
			/**
			 * Filter on post content
			 *
			 * @param string - the post content
			 * @param array - the video details
			 * @param bool/array - false if not imported as theme, array if imported as theme and theme is active
			 */
			'post_content' 	=> apply_filters('cvm_video_post_content', $post_content, $video, $theme_import),
			/**
			 * Filter on post excerpt
			 *
			 * @param string - the post excerpt
			 * @param array - the video details
			 * @param bool/array - false if not imported as theme, array if imported as theme and theme is active
			 */
			'post_excerpt'	=> apply_filters('cvm_video_post_excerpt', $post_excerpt, $video, $theme_import),
			'post_type'		=> $post_type,
			/**
			 * Filter on post status
			 *
			 * @param string - the post status
			 * @param array - the video details
			 * @param bool/array - always false, implemented for PRO version reasons
			 */
			'post_status'	=> apply_filters('cvm_video_post_status', $status, $video, $theme_import )
		];

		$pd = $options['import_date'] ? date('Y-m-d H:i:s', strtotime( $video['published'] )) : current_time( 'mysql' );
		/**
		 * Filter on post date
		 *
		 * @param string - the post date
		 * @param array - the video details
		 * @param bool/array - false if not imported as theme, array if imported as theme and theme is active
		 */
		$post_date = apply_filters( 'cvm_video_post_date', $pd, $video, $theme_import );

		if( isset( $options['import_date'] ) && $options['import_date'] ){
			$post_data['post_date_gmt'] = $post_date;
			$post_data['edit_date']		= $post_date;
			$post_data['post_date']		= $post_date;
		}

		// set user
		if( $user ){
			$post_data['post_author'] = $user;
		}
		/**
		 * @var int|\WP_Error $post_id
		 */
		// single video import will pass post ID
		if( isset( $post_id ) && $post_id ){
			$post_data['ID'] = $post_id;
			$post_id = wp_update_post( $post_data, true );
		}else {
			// allow empty insert into post content
			apply_filters(
				'wp_insert_post_empty_content',
				'__return_false'
			);

			$post_id = wp_insert_post( $post_data, true );
		}

		if( is_wp_error( $post_id ) ){
			_cvm_debug_message(
				sprintf(
					'Post insert returned error %s. MySQL error is: %s',
					$post_id->get_error_message(),
					print_r( $post_id->get_error_data( 'db_insert_error' ), true )
				)
			);
		}

		// check if post was created
		if( !is_wp_error( $post_id ) ){

			// set post format
			if( $post_format  ){
				set_post_format( $post_id, $post_format );
			}

			/**
			 * Filter that can be used to manipulate the imported category taxonomy when enforcing a custom post type
			 * @var string
			 */
			$taxonomy = apply_filters( 'cvm_import_category', $taxonomy );
			/**
			 * Filter that can be used to manipulate the imported tag taxonomy when enforcing a custom post type
			 * @var string
			 */
			$tag_taxonomy = apply_filters( 'cvm_import_tag', $tag_taxonomy );

			// set post category
			if( $category ){
				$category = is_array( $category ) ? $category : [ $category ];
				wp_set_post_terms( $post_id, $category, $taxonomy );
			}

			if( $tags ){
				wp_set_post_terms( $post_id, $tags, $tag_taxonomy );
			}

			// insert tags
			if( ( isset( $options['import_tags'] ) && $options['import_tags'] ) && $tag_taxonomy ){
				if( isset( $video['tags'] ) && is_array( $video['tags'] ) ){
					$tags = [];
					$count = absint( $options['max_tags'] );
					$tags = array_slice($video['tags'], 0, $count);
					if( $tags ){
						wp_set_post_terms( $post_id, $tags, $tag_taxonomy, true );
					}
				}
			}

			/**
			 * Action on post insert that allows setting of different meta on post
			 *
			 * @param int $post_id
			 * @param array $video
			 * @param array|bool $theme_import
			 * @param string $post_type
			 */
			do_action('cvm_post_insert', $post_id, $video, $theme_import, $post_type);

			// if importing as theme post, there might be some meta fields to be set
			if( $theme_import && isset( $theme_import['post_meta'] ) && is_array( $theme_import['post_meta'] ) ){
				foreach( $theme_import['post_meta'] as $k => $meta_key ){
					switch( $k ){
						case 'url' :
							update_post_meta( $post_id, $meta_key, $video['link'] );
							break;
						case 'thumbnail':
							update_post_meta( $post_id, $meta_key, end( $video['thumbnails'] ) );
							break;
						case 'embed':
							update_post_meta( $post_id, $meta_key, cvm_video_embed( $video['video_id'] ) );
							break;
					}
				}
			}

			// set post meta
			$_post = Helper::get_video_post( $post_id );
			$_post->set_video_data( $video );
			$_post->set_video_id_meta();
			$_post->set_video_url_meta();
			// import image
			if( $options['featured_image'] && $this->post_type->get_post_settings()->image_import( 'post_create' ) ){
				$_post->set_featured_image();
			}

			/**
			 * Send a debug message
			 */
			_cvm_debug_message(  'Imported video ID ' . $video['video_id'] . ' into post #' . $post_id . ' having post type "' . $post_type . '".'  );

			return $post_id;

		}// end checking if not wp error on post insert

		return false;
	}
}