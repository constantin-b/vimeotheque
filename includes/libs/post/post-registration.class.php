<?php
/**
 * @author CodeFlavors
 * @project Vimeotheque 2.0 Lite
 */

namespace Vimeotheque\Post;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Post_Registration {
	/**
	 * @var Register_Post[]
	 */
	private $types;

	/**
	 * Post_Registration constructor.
	 *
	 * @param \WP_Post_Type $post_type
	 * @param \WP_Taxonomy|false $taxonomy
	 */
	public function __construct( \WP_Post_Type $post_type, $taxonomy ) {
		$this->register( $post_type, $taxonomy );
	}

	/**
	 * @param \WP_Post_Type $post_type
	 * @param \WP_Taxonomy|false $taxonomy
	 */
	public function register( \WP_Post_Type $post_type, $taxonomy ){
		if( !did_action( 'init' ) ){
			_doing_it_wrong( __FUNCTION__, 'Post types must be registered only after "init" hook is fired.' );
		}

		$this->types[ $post_type->name ] = new Register_Post(
			$post_type,
			$taxonomy
		);
	}

	/**
	 * @return Register_Post[]
	 */
	public function get_post_types(){
		return $this->types;
	}

	/**
	 * @param $post_type
	 *
	 * @return null|Register_Post
	 */
	public function get_post_type( $post_type ){
		if( $this->is_registered_post_type( $post_type ) ){
			return $this->types[ $post_type ];
		}

		return null;
	}

	/**
	 * @param $post_type
	 *
	 * @return bool
	 */
	public function is_registered_post_type( $post_type ){
		return isset( $this->types[ $post_type ] );
	}
}