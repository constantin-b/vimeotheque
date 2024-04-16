<?php
/**
 * @author  CodeFlavors
 * @project vimeotheque-series
 */

namespace Vimeotheque_Series\Post_Type;

use Vimeotheque_Series\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Series extends Abstract_Post_Type implements Interface_Post_Type {

	public function __construct(){

		parent::__construct( 'series' );

		add_action( 'init', [$this, 'register_post_type'] );

	}

	public function register_post_type(){

		register_post_type(
			parent::get_post_name(),
			[
				'labels' => $this->get_labels(),
				'public' => true,
				'exclude_from_search' => false,
				'publicly_queryable' => true,
				'show_in_nav_menus' => true,

				'show_ui' => true,
				'show_in_menu' => true,
				'menu_position' => 6,
				'menu_icon' => 'dashicons-playlist-video',

				'query_var' => false,
				'capability_type' => 'post',
				'has_archive' => false,
				'hierarchical' => false,

				'show_in_rest' => true,

				'supports' => [
                    'series'
                ],

                'register_meta_box_cb' => function(){

                    Plugin::instance()->get_metaboxes()->initiate_metaboxes();

                },
			]
		);
	}

	/**
	 * Post type labels
	 *
	 * @return array
	 */
	private function get_labels(){
		return [
			'name' 					=> _x('Video Series', 'Video Series', 'vimeotheque-series'),
			'singular_name' 		=> _x('Video Series', 'Video Series', 'vimeotheque-series'),
			'add_new' 				=> _x('Add New', 'Series', 'vimeotheque-series'),
			'add_new_item' 			=> __('Add New Series', 'vimeotheque-series'),
			'edit_item' 			=> __('Edit Series', 'vimeotheque-series'),
			'new_item'				=> __('New Series', 'vimeotheque-series'),
			'all_items' 			=> __('All Series', 'vimeotheque-series'),
			'view_item' 			=> __('View', 'vimeotheque-series'),
			'search_items' 			=> __('Search', 'vimeotheque-series'),
			'not_found' 			=> __('No Series found', 'vimeotheque-series'),
			'not_found_in_trash' 	=> __('No Series in trash', 'vimeotheque-series'),
			'parent_item_colon' 	=> '',
			'menu_name' 			=> __('Series', 'vimeotheque-series')
		];
	}
	
}