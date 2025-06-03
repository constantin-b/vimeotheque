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

	public function __construct() {

		parent::__construct( 'series' );

		add_action( 'init', [ $this, 'register_post_type' ] );
	}

	public function register_post_type() {

		$slug = \Vimeotheque\Plugin::instance()->get_options_obj()->get_option( 'series_slug' );

		register_post_type(
			parent::get_post_name(),
			[
				'labels'               => $this->get_labels(),
				'public'               => true,
				'exclude_from_search'  => false,
				'publicly_queryable'   => true,
				'show_in_nav_menus'    => true,

				'show_ui'              => true,
				'show_in_menu'         => true,
				'menu_position'        => 6,
				'menu_icon'            => 'dashicons-playlist-video',

				'query_var'            => false,
				'capability_type'      => 'post',
				'has_archive'          => false,
				'hierarchical'         => false,

				'show_in_rest'         => true,

				'rewrite'              => [
					'slug' => $slug,
				],

				'supports'             => [
					'series',
				],

				'register_meta_box_cb' => function () {

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
	private function get_labels() {
		return [
			'name'               => _x( 'Video Series', 'Video Series', 'codeflavors-vimeo-video-post-lite' ),
			'singular_name'      => _x( 'Video Series', 'Video Series', 'codeflavors-vimeo-video-post-lite' ),
			'add_new'            => _x( 'Add New', 'Series', 'codeflavors-vimeo-video-post-lite' ),
			'add_new_item'       => __( 'Add New Series', 'codeflavors-vimeo-video-post-lite' ),
			'edit_item'          => __( 'Edit Series', 'codeflavors-vimeo-video-post-lite' ),
			'new_item'           => __( 'New Series', 'codeflavors-vimeo-video-post-lite' ),
			'all_items'          => __( 'All Series', 'codeflavors-vimeo-video-post-lite' ),
			'view_item'          => __( 'View', 'codeflavors-vimeo-video-post-lite' ),
			'search_items'       => __( 'Search', 'codeflavors-vimeo-video-post-lite' ),
			'not_found'          => __( 'No Series found', 'codeflavors-vimeo-video-post-lite' ),
			'not_found_in_trash' => __( 'No Series in trash', 'codeflavors-vimeo-video-post-lite' ),
			'parent_item_colon'  => '',
			'menu_name'          => __( 'Series', 'codeflavors-vimeo-video-post-lite' ),
		];
	}
}
