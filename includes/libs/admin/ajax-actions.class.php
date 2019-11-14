<?php

namespace Vimeotheque\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Vimeotheque\Helper;
use Vimeotheque\Post_Type;
use Vimeotheque\Video_Import;

/**
 * 
 * @author CodeFlavors
 *
 */
class Ajax_Actions{
	/**
	 * Store Post_Type object reference
	 * @var Post_Type
	 */
	private $cpt;
	
	/**
	 * 
	 * @param Post_Type $object
	 */
	public function __construct( Post_Type $object ){
		$this->cpt = $object;
		// get the actions
		$actions = $this->__actions();
		// add wp actions
		foreach( $actions as $action ){
			add_action( 'wp_ajax_' . $action['action'], $action['callback'] );
		}
	}
	
	/**
	 * Ajax query callback
	 */
	public function vimeo_api_query(){
		$this->__check_referer( 'api_query' );
		if( !current_user_can( 'edit_posts' ) ){
			header('HTTP/1.1 401 Unauthorized');
			_e( 'You do not have the neccessary permissions.', 'cvm_vimeo' );
			die();
		}
		
		$args = [
			'page' => $_POST['page'],
			'order' => $_POST['cvm_order'], // @todo implement ordering
			'query' => trim( $_POST['cvm_search_results'] ),
			'per_page' => 50
		];
		$query = new Video_Import( $_POST['cvm_feed'], $_POST['cvm_query'], $_POST['cvm_album_user'], $args);
		$videos = $query->get_feed();
		
		if( is_wp_error( $query->get_errors() ) ){
			header('HTTP/1.1 503 Service Unavailable');
			$error = $query->get_errors();
			if( is_wp_error( $error ) ){
				echo $error->get_error_message();
			}else{
				_e('An unknown error has occured.', 'cvm_video');	
			}			
		}else{
			$response = [
				'results' 	=> $query->get_total_items(),
				'page'		=> $query->get_page(),
				'end'		=> $query->has_ended(),
				'videos' 	=> $videos
			];
			wp_send_json_success( $response );
		}
		
		die();
	}
	
	/**
	 * Import thumbnail as featured image in single post page
	 */
	public function import_thumbnail(){		
		if( !current_user_can( 'upload_files' ) ){
			header('HTTP/1.1 401 Unauthorized');
			_e( 'You do not have the neccessary permissions.', 'cvm_vimeo' );
			die();
		}
		
		if( !isset( $_POST['post'] ) ){
			die();
		}
	
		$post_id = absint( $_POST['post'] );
		$refresh = (bool)$_POST['refresh'];
		$thumbnail = Helper::get_video_post( $post_id )->set_featured_image( $refresh );
	
		if( !$thumbnail ){
			wp_send_json_error( __('Image could not be retrieved.', 'cvm_video') );
		}

		// If request is sent by Gutenberg script, it will contain a POST variable called "gutenberg"
		if( isset( $_POST['gutenberg'] ) ){
			$response = $thumbnail;
		}else{
			$response = _wp_post_thumbnail_html( $thumbnail['attachment_id'], $thumbnail['post_id'] );
		}

		wp_send_json_success( $response );	
	}
	
	/**
	 * Bulk import videos when in List view
	 */
	public function bulk_import_videos(){
		$this->__check_referer( 'list_view_import_videos' );
		if( !current_user_can( 'edit_posts' ) ){
			header('HTTP/1.1 401 Unauthorized');
			_e( 'You do not have the neccessary permissions.', 'cvm_vimeo' );
			die();
		}

		$videos = (array) $_POST['cvm_import'];
		$response = [
			'total' => count( $videos ),
			'imported' => 0,
			'skipped' => 0,
			'private' => 0,
			'error' => false,
			'success' => false
		];

		if( 'import' == $_REQUEST['action_top'] || 'import' == $_REQUEST['action2'] ){
			$import_options = $this->get_import_options( $_POST );
			foreach( $videos as $video ){
				$result = $this->cpt->get_plugin()
				                    ->get_posts_importer()
				                     ->run_import(
					                     [ \Vimeotheque\cvm_query_video( $video ) ],
					                     $import_options
				                     );
				$response['imported'] += $result['imported'];
				$response['skipped'] += $result['skipped'];
				$response['private'] += $result['private'];
			}

			$response['success'] = sprintf(
				__('%d videos: %d imported, %d skipped, %d private, %d error.', 'cvm_video'),
				$response['total'],
				$response['imported'],
				$response['skipped'],
				$response['private'],
				$response['error']
			);
		}

		echo json_encode( $response );
		die();
	}
	
	/**
	 * Import a video
	 */
	public function import_video(){
		$this->__check_referer( 'api_query' );
		if( !current_user_can( 'edit_posts' ) ){
			header('HTTP/1.1 401 Unauthorized');
			_e( 'You do not have the neccessary permissions.', 'cvm_vimeo' );
			die();
		}

		// if importing a single video apply the settings from plugin
		if( !isset( $_POST['model']['import'] ) ){
			$import_options = \Vimeotheque\Plugin::instance()->get_options();
		}else {
			// get the import options
			$import_options = $this->get_import_options( $_POST['model']['import'] );
		}

		$results = $this->cpt->get_plugin()
		                     ->get_posts_importer()
		                     ->run_import(
		                     	[ $_POST['model'] ],
		                        $import_options
		                     );

		if( $results['imported'] ){
			// add edit link and permalink to results array
			$results['links'] = [];
			foreach( $results['ids'] as $post_id ){
				$results['links'][] = [
					'edit_link' => get_edit_post_link( $post_id ),
					'permalink' => get_permalink( $post_id )
				];
			}			
			wp_send_json_success( $results );
		}
		
		header('HTTP/1.1 409 Conflict');
		if( $results['error'] ){
			/**
			 * @var \WP_Error $error
			 */
			foreach( $results['error'] as $k => $error ){
				$results['error'][ $k ] = $error->get_error_message();
			}
		}

		wp_send_json_error( $results );
	}

	/**
	 * Process the import options
	 *
	 * @param array $source
	 *
	 * @return array
	 */
	private function get_import_options( $source = [] ){
		$taxonomy = $this->cpt->get_post_tax();
		$tag_tax = $this->cpt->get_tag_tax();
		$native_tax = isset( $source['tax_input'][ $taxonomy ] ) ? (array) $source['tax_input'][ $taxonomy ] : [];
		$native_tag = isset( $source['tax_input'][ $tag_tax ] ) ? (array) $source['tax_input'][ $tag_tax ] : [];

		$import_options = [
			//'theme_import'		=> false,
			'native_tax'		=> $native_tax,
			//'theme_tax'			=> false,
			'native_tag'		=> $native_tag,
			//'theme_tag'			=> false,
			'import_description' => $source['import_description'],
			'import_status' => $source['import_status'],
			'import_title' => isset( $source['import_title'] ),
			//'import_date' => $source['import_date'],
			//'import_user' => (isset( $source['import_user'] )) ? $source['import_user'] : false,
			// embed options
			//'aspect_ratio' => $source['aspect_ratio'],
			//'video_position' => $source['video_position'],
			//'loop' => isset( $source['loop'] ),
			//'autoplay' => isset( $source['autoplay'] )
		];

		/**
		 * Filter the options
		 *
		 * @param array $import_options
		 */
		return apply_filters( 'vimeotheque\admin\import\ajax_options', $import_options, $source );
	}

	/**
	 * Stores all ajax actions references.
	 * This is where all ajax actions are added.
	 */
	private function __actions(){
		
		$callbacks = [
			// get Vimeo videos from API when viewing in Grid view bulk import
			'api_query' => [
				'action' 	=> 'cvm_get_videos',
				'callback' 	=> [ $this, 'vimeo_api_query' ],
				'nonce' => [
					'name' 		=> 'nonce',
					'action' 	=> 'cvm_vimeo_videos_grid_nonce'
				]
			],
			// import single video from Grid View bulk import
			'save_video' => [
				'action' 	=> 'cvm_import_video',
				'callback'	=> [ $this, 'import_video' ],
				'nonce' => [
					'name' 		=> 'nonce',
					'action' 	=> 'cvm_vimeo_videos_grid_nonce'
				]
			],
			'import_thumbnail' => [
				'action' => 'cvm_import_video_thumbnail',
				'callback' => [ $this, 'import_thumbnail' ],
				'nonce' => [
					'name' => '',
					'action' => ''
				]
			],
			// list view bulk import
			'list_view_import_videos' => [
				'action' => 'cvm_import_videos',
				'callback' => [ $this, 'bulk_import_videos' ],
				'nonce' => [
					'name' => 'cvm_import_nonce',
					'action' => 'cvm-import-videos-to-wp'
				]
			]
		];

		$_callbacks = apply_filters( 'vimeotheque\admin\ajax_response', [], $this );

		return array_merge( $_callbacks, $callbacks );
	}
	
	/**
	 * For a given action key it will perform nonce checking and admin referer verification.
	 * @param string $key
	 */
	public function __check_referer( $key ){
		$action = $this->__get_action_data( $key );
		if( !$action ){
			wp_die( sprintf( __( 'Action %s not found. Please review!' ), $key ) );
		}
		
		check_admin_referer( $action['nonce']['action'], $action['nonce']['name'] );		
	}

	/**
	 * Gets all details of a given action from registered actions
	 *
	 * @param string $key
	 *
	 * @return
	 */
	protected function __get_action_data( $key ){
		$actions = $this->__actions();
		if( array_key_exists( $key, $actions ) ){
			return $actions[ $key ];
		}else{
			trigger_error( sprintf( __( 'Action %s not found.'), $key ), E_USER_WARNING);
		}
	}

	/**
	 * Returns the action name for a given key
	 *
	 * @param string $action
	 *
	 * @return
	 */
	public function get_action( $action ){
		$data = $this->__get_action_data( $action );
		return $data['action'];
	}

	/**
	 * Returns the nonce name for a given key
	 *
	 * @param string $action
	 *
	 * @return
	 */
	public function get_nonce_name( $action ){
		$data = $this->__get_action_data( $action );
		return $data['nonce']['name'];
	}

	/**
	 * Returns the nonce action for a given key
	 *
	 * @param string $action
	 *
	 * @return
	 */
	public function get_nonce_action( $action ){
		$data = $this->__get_action_data( $action );
		return $data['nonce']['action'];
	}
}