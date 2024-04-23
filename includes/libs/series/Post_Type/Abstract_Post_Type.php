<?php
/**
 * @author  CodeFlavors
 * @project vimeotheque-series
 */

namespace Vimeotheque_Series\Post_Type;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

abstract class Abstract_Post_Type {
	/**
	 * The post name
	 *
	 * @var string
	 */
	private $post_name;

	/**
	 * The category taxonomy assigned to post type $post_name
	 *
	 * @var mixed|null
	 */
	private $category_taxonomy;

	/**
	 * The tag taxonomy assigned to post type $post_name
	 *
	 * @var mixed|null
	 */
	private $tag_taxonomy;

	/**
	 * Abstract_Post_Type constructor.
	 *
	 * @param $post_name
	 * @param null      $category_taxonomy
	 * @param null      $tag_taxonomy
	 */
	public function __construct( $post_name, $category_taxonomy = null, $tag_taxonomy = null ){
		$this->post_name = $post_name;
		$this->category_taxonomy = $category_taxonomy;
		$this->tag_taxonomy = $tag_taxonomy;
	}

	/**
	 * @return mixed
	 */
	public function get_post_name() {
		return $this->post_name;
	}

	/**
	 * @return mixed|null
	 */
	public function get_category_taxonomy() {
		return $this->category_taxonomy;
	}

	/**
	 * @return mixed|null
	 */
	public function get_tag_taxonomy() {
		return $this->tag_taxonomy;
	}
}