<?php

namespace Vimeotheque\Post;

use Vimeotheque\Helper;
use Vimeotheque\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Custom post type class. Manages post registering, taxonomies, data saving
 */
class Post_Type{

	/**
	 * Video post type
	 *
	 * @var string
	 */
	private $post_type = 'vimeo-video';

	/**
	 * Video cateogry taxonomy
	 *
	 * @var string
	 */
	private $taxonomy = 'vimeo-videos';

	/**
	 * Video custom post type tag taxonomy
	 *
	 * @var string
	 */
	private $tag = 'vimeo-tag';

	/**
	 * @var Post_Settings
	 */
	private $post_settings;

	/**
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Constructor, initiates post type registering
	 *
	 * @param Plugin $plugin
	 */
	public function __construct( Plugin $plugin ){
		// custom post type registration and messages
		add_action( 'init', [ $this, 'register_post' ], 1 );
		// custom post type messages
		add_filter('post_updated_messages', [ $this, 'updated_messages' ] );

		$this->plugin = $plugin;
		$this->post_settings = new Post_Settings( $this );
	}
	
	/**
	 * Register video post type and taxonomies
	 */
	public function register_post(){
		$labels = [
			'name' 					=> _x('Vimeo Videos', 'Vimeo Videos', 'cvm_video'),
	    	'singular_name' 		=> _x('Vimeo Video', 'Vimeo Video', 'cvm_video'),
	    	'add_new' 				=> _x('Add new', 'video', 'cvm_video'),
	    	'add_new_item' 			=> __('Add new video', 'cvm_video'),
	    	'edit_item' 			=> __('Edit video', 'cvm_video'),
	    	'new_item'				=> __('New video', 'cvm_video'),
	    	'all_items' 			=> __('All videos', 'cvm_video'),
	    	'view_item' 			=> __('View', 'cvm_video'),
	    	'search_items' 			=> __('Search', 'cvm_video'),
	    	'not_found' 			=> __('No videos found', 'cvm_video'),
	    	'not_found_in_trash' 	=> __('No videos in trash', 'cvm_video'), 
	    	'parent_item_colon' 	=> '',
	    	'menu_name' 			=> __('Videos', 'cvm_video')
		];
		
		$options 	= \Vimeotheque\Plugin::instance()->get_options();
		$is_public 	= $options['public'];
		
		$args = [
    		'labels' 				=> $labels,
    		'public' 				=> $is_public,
			'exclude_from_search'	=> !$is_public,
    		'publicly_queryable' 	=> $is_public,
			'show_in_nav_menus'		=> $is_public,
		
    		'show_ui' 				=> true,
			'show_in_menu' 			=> true,
			'menu_position' 		=> 5,
			'menu_icon'				=> VIMEOTHEQUE_URL . 'assets/back-end/images/video.png',
		
    		'query_var' 			=> true,
    		'capability_type' 		=> 'post',
    		'has_archive' 			=> $is_public, 
    		'hierarchical' 			=> false,
    		
			// REST support
			'show_in_rest' 			=> true,
							
			'rewrite'				=> [
				'slug' => $options['post_slug']
			],
		
    		'supports' 				=> [
				'title', 
    			'editor', 
    			'author', 
    			'thumbnail', 
    			'excerpt', 
    			'trackbacks',
				'custom-fields',
    			'comments',  
    			'revisions',
    			'post-formats'
		    ],
		];
 		
 		register_post_type( $this->post_type, $args );
  
  		// Add new taxonomy, make it hierarchical (like categories)
  		$cat_labels = [
	    	'name' 					=> _x( 'Vimeo Video categories', 'Vimeo Video categories', 'cvm_video' ),
	    	'singular_name' 		=> _x( 'Vimeo Video category', 'Vimeo Video category', 'cvm_video' ),
	    	'search_items' 			=>  __( 'Search video category' ),
	    	'all_items' 			=> __( 'All video categories' ),
	    	'parent_item' 			=> __( 'Video category parent' ),
	    	'parent_item_colon'		=> __( 'Video category parent:' ),
	    	'edit_item' 			=> __( 'Edit video category' ), 
	    	'update_item' 			=> __( 'Update video category' ),
	    	'add_new_item' 			=> __( 'Add new video category' ),
	    	'new_item_name' 		=> __( 'Video category name' ),
	    	'menu_name' 			=> __( 'Categories' ),
	    ];

		register_taxonomy($this->taxonomy, [ $this->post_type ], [
			'public'			=> $is_public,
    		'show_ui' 			=> true,
			'show_in_nav_menus' => $is_public,
			'show_admin_column' => true,		
			'hierarchical' 		=> true,
			// REST support
			'show_in_rest' 		=> true,
			'rewrite' 			=> [
				'slug' => $options['taxonomy_slug']
			],
			'capabilities'		=> [ 'edit_posts' ],
    		'labels' 			=> $cat_labels,    		
    		'query_var' 		=> true
		] );
  		
  		// tags
  		$tag_labels = [
	    	'name' 					=> _x( 'Vimeo Video tags', 'Vimeo Video tags', 'cvm_video' ),
	    	'singular_name' 		=> _x( 'Vimeo Video tag', 'Vimeo Video tag', 'cvm_video' ),
	    	'search_items' 			=>  __( 'Search video tag' ),
	    	'all_items' 			=> __( 'All video tags' ),
	    	'parent_item' 			=> __( 'Video tag parent' ),
	    	'parent_item_colon'		=> __( 'Video tag parent:' ),
	    	'edit_item' 			=> __( 'Edit video tag' ), 
	    	'update_item' 			=> __( 'Update video tag' ),
	    	'add_new_item' 			=> __( 'Add new video tag' ),
	    	'new_item_name' 		=> __( 'Video tag name' ),
	    	'menu_name' 			=> __( 'Tags' ),
	    ];

  		register_taxonomy( $this->tag, [ $this->post_type ], [
			'public'			=> $is_public,
    		'show_ui' 			=> true,
			'show_in_nav_menus' => $is_public,
			'show_admin_column' => true,		
			'hierarchical' 		=> false,
  			// REST support
  			'show_in_rest' 		=> true,
			'rewrite' 			=> [
				'slug' => $options['tag_slug']
			],
			'capabilities'		=> [ 'edit_posts' ],
    		'labels' 			=> $tag_labels,    		
    		'query_var' 		=> true
	    ] );
	}

	/**
	 * Custom post type messages on edit, update, create, etc.
	 *
	 * @param array $messages
	 *
	 * @return array|void
	 */
	public function updated_messages( $messages ){
		global $post, $post_ID;

		$_post = Helper::get_video_post( $post );
		if( !$_post->get_post() || !$_post->is_video() ){
			return;
		}
		
		$vid_id = isset( $_GET['video_id'] ) ? $_GET['video_id'] : '';
		
		$messages[ $this->post_type ] = [
			0 => '', // Unused. Messages start at index 1.
	    	1 => sprintf( __('Video updated <a href="%s">See video</a>', 'cvm_video'), esc_url( get_permalink($post_ID) ) ),
	    	2 => __('Custom field updated.', 'cvm_video'),
	    	3 => __('Custom field deleted.', 'cvm_video'),
	    	4 => __('Video updated.', 'cvm_video'),
	   		/* translators: %s: date and time of the revision */
	    	5 => isset($_GET['revision']) ? sprintf( __('Video restored to version %s', 'cvm_video'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
	    	6 => sprintf( __('Video published. <a href="%s">See video</a>', 'cvm_video'), esc_url( get_permalink($post_ID) ) ),
	    	7 => __('Video saved.', 'cvm_video'),
	    	8 => sprintf( __('Video saved. <a target="_blank" href="%s">See video</a>', 'cvm_video'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	    	9 => sprintf( __('Video will be published at: <strong>%1$s</strong>. <a target="_blank" href="%2$s">See video</a>', 'cvm_video'),
	      	// translators: Publish box date format, see http://php.net/date
	      	date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
	    	10 => sprintf( __('Video draft saved. <a target="_blank" href="%s">See video</a>', 'cvm_video'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	    
	    	101 => __('Please select a source', 'cvm_video'),
	    	102 => sprintf( __('Vimeo video with ID <strong><em>%s</em></strong> is already imported. You are now editing the existing video.', 'cvm_video'), $vid_id)
		];
	    
		return $messages;
	}

	/**
	 * Return post type
	 */
	public function get_post_type(){
		return $this->post_type;
	}

	/**
	 * Return taxonomy
	 */
	public function get_post_tax(){
		return $this->taxonomy;
	}
	
	/**
	 * Returns tags taxonomy
	 */
	public function get_tag_tax(){
		return $this->tag;
	}

	/**
	 * @return Post_Settings
	 */
	public function get_post_settings(){
		return $this->post_settings;
	}

	/**
	 * @return Plugin
	 */
	public function get_plugin(){
		return $this->plugin;
	}
}