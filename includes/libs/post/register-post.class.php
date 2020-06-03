<?php
/**
 * @author CodeFlavors
 * @project Vimeotheque 2.0 Lite
 */

namespace Vimeotheque\Post;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Register_Post {

	/**
	 * @var \WP_Taxonomy
	 */
	private $taxonomy;
	/**
	 * @var \WP_Post_Type
	 */
	private $post_type;

	/**
	 * Register_Post constructor.
	 *
	 * @param \WP_Post_Type $post_type
	 * @param \WP_Taxonomy $taxonomy
	 */
	public function __construct( \WP_Post_Type $post_type, \WP_Taxonomy $taxonomy ) {
		$this->post_type = $post_type;
		$this->taxonomy  = $taxonomy;
	}

	/**
	 * @return \WP_Taxonomy
	 */
	public function get_taxonomy() {
		return $this->taxonomy;
	}

	/**
	 * @return \WP_Post_Type
	 */
	public function get_post_type() {
		return $this->post_type;
	}

	public function get_post_type_rest_endpoint(){
		return '/vimeotheque/v1/get_posts';
	}

	public function get_taxonomy_rest_endpoint(){
		$rest_base = ! empty( $this->taxonomy->rest_base ) ? $this->taxonomy->rest_base : $this->taxonomy->name;
		return '/wp/v2/' . $rest_base;
	}


}