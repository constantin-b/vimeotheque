<?php

namespace Vimeotheque\Shortcode;

use Vimeotheque\Helper;
use Vimeotheque\Playlist\Theme\Theme;
use Vimeotheque\Plugin;
use Vimeotheque\Video_Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Playlist
 * @package Vimeotheque\Shortcode
 */
class Playlist extends Shortcode_Abstract implements Shortcode_Interface {
	/**
	 * @var null
	 */
	private $options = null;
	/**
	 * @var \WP_Post
	 */
	private $posts = [];

	/**
	 * Playlist constructor.
	 *
	 * @param array $atts - Shortcode attributes
	 * @param string $content - Shortcode content
	 */
	public function __construct( $atts, $content ) {
		parent::__construct( $atts, $content );
	}

	/**
	 * @param \WP_Post $posts
	 */
	public function set_posts( $posts ){
		foreach ( $posts as $post ) {
			if( $post instanceof \WP_Post ){
				$_post = Helper::get_video_post( $post );
				if( $_post->is_video() ){
					$this->posts[] = $_post;
				}
			}
		}
	}

	/**
	 * @return string|void
	 */
	public function get_output(){
		$videos = $this->posts ? $this->posts : $this->get_video_posts();
		if( !$videos ){
			return;
		}

		ob_start();

		global $CVM_PLAYER_SETTINGS;
		$embed_options = $this->get_embed_options();
		$CVM_PLAYER_SETTINGS = $embed_options;

		/**
		 * @var Video_Post
		 */
		global $cvm_video;

		// include theme functions
		include_once( VIMEOTHEQUE_PATH . '/includes/theme-functions.php' );

		Helper::enqueue_player();

		$theme = $this->get_theme();
		if( !$theme ){
			$theme = Plugin::$instance->get_playlist_themes()->get_theme('default');
		}

		// include theme file
		include $theme->get_file();

		wp_enqueue_script(
			'cvm-vim-player-' . strtolower( $theme->get_folder_name() ) ,
			$theme->get_js_url(),
			[ 'cvm-video-player' ],
			'1.0'
		);

		wp_enqueue_style(
			'cvm-vim-player-' . strtolower( $theme->get_folder_name() ) ,
			$theme->get_style_url(),
			false,
			'1.0'
		);

		$content = ob_get_contents();
		ob_end_clean();

		// remove custom player settings
		$CVM_PLAYER_SETTINGS = false;

		return $content;
	}

	/**
	 * Shortcode defaults
	 *
	 * @return array
	 */
	private function get_embed_options(){
		if( $this->options ){
			return $this->options;
		}

		$this->options = Plugin::$instance->get_player_options()->get_options();
		foreach( $this->options as $key => $value ){
			$attr = parent::get_attr( $key );
			if( !is_wp_error( $attr ) ){
				// some options have value 0 or 1 and need to be processed this way
				if( in_array( $value, [0, 1] ) ){
					$attr = absint( $attr );
				}

				$this->options[ $key ] = $attr;
			}
		}

		return $this->options;
	}

	/**
	 * @return mixed|Theme
	 */
	private function get_theme(){
		$theme = parent::get_attr('theme');
		if( !$theme instanceof Theme ){
			$theme = Plugin::$instance->get_playlist_themes()->get_theme( $theme );
		}
		return $theme;
	}

	/**
	 * Get videos IDs from attributes
	 *
	 * @return array|mixed
	 */
	private function get_video_ids(){
		$video_ids = parent::get_attr('post_ids');
		if( is_wp_error( $video_ids ) ){
			$videos = parent::get_attr('videos');
			if( !is_wp_error( $videos ) ) {
				$video_ids = explode( ',', $videos );
			}
		}
		return $video_ids;
	}

	/**
	 * Get categories IDs from attributes
	 *
	 * @return array|mixed
	 */
	private function get_categories_ids(){
		$cat_ids = parent::get_attr( 'cat_ids' );
		if( is_wp_error( $cat_ids ) ){
			$categories = parent::get_attr( 'categories' );
			if( !is_wp_error( $categories ) ){
				$cat_ids = explode( ',', $categories );
			}
		}
		return $cat_ids;
	}

	/**
	 * @return array
	 */
	private function get_video_posts(){
		$posts = [];
		$videos = $this->get_video_ids();
		if( $videos && !is_wp_error( $videos ) ){
			$_posts = get_posts( [
				'post_type' => 'any',
				'include' => $videos,
				'posts_per_page' => count( $videos ),
				'numberposts' => count( $videos ),
				'post_status' => 'publish',
				'suppress_filters' => true
			] );

			if( $_posts && !is_wp_error( $_posts ) ){
				foreach( $_posts as $post ){
					$_post = Helper::get_video_post( $post );
					if( $_post->is_video() ){
						$posts[ $post->ID ] = $_post;
					}
				}
			}
		}

		$categories = $this->get_categories_ids();
		if( !is_wp_error( $categories ) ){
			$post_type = parent::get_attr( 'post_type' );
			if( $post_type && !is_wp_error( $post_type ) ){
				$_post_type = explode( ',', $post_type );
			}else{
				$_post_type = false;
			}

			$_posts = $this->get_category_post_ids( $categories, $_post_type );
			if( $_posts ){
				$_posts = array_diff_key( $_posts, $posts );
				$posts = array_merge( $posts, $_posts );
			}

		}

		return $posts;
	}

	/**
	 * Returns all post ids for the given categories
	 *
	 * @param array $categories - array of terms IDs
	 * @param $post_type
	 *
	 * @return array|void
	 */
	function get_category_post_ids( /*array*/ $categories, /*array*/ $post_type ){
		if( !is_array( $categories ) || !$categories ){
			return;
		}

		$posts = [];

		// if newest videos should be returned, return them
		if( in_array( '0', $categories ) ){
			$args = [
				'post_type' => $post_type,
				'numberposts' => apply_filters( 'cvm_shortcode_new_videos_max_posts', 10 ),
				'order' => 'DESC',
				'orderby' => 'post_date'
			];
			$p = get_posts( $args );
			if( $p && !is_wp_error( $p ) ){
				foreach( $p as $post ){
					$_post = Helper::get_video_post( $post );
					if( $_post->is_video() ) {
						$posts[ $post->ID ] = $_post;
					}
				}
			}
			return $posts;
		}

		$terms = [];
		foreach( $categories as $term_id ){
			$term = get_term( $term_id );
			if( $term && !is_wp_error( $term ) ){
				$terms[ $term->taxonomy ][] = $term->term_id;
			}
		}

		if( $terms ){
			$args = [
				'post_type' => $this->get_post_types_by_taxonomy( array_keys( $terms ) ),
				'numberposts' => -1,
				'order' => 'DESC',
				'orderby' => 'post_date',
				'tax_query' => [
					'relation' => 'OR',
				]
			];

			foreach( $terms as $taxonomy => $term_ids ){
				$args['tax_query'][] = [
					'taxonomy' => $taxonomy,
					'field' => 'term_id',
					'terms' => $term_ids
				];
			}

			$p = get_posts( $args );
			if( $p && !is_wp_error( $p ) ){
				foreach( $p as $post ){
					$_post = Helper::get_video_post( $post );
					if( $_post->is_video() ) {
						$posts[ $post->ID ] = $_post;
					}
				}
			}
		}

		return $posts;
	}

	/**
	 * @param array $taxonomies
	 *
	 * @return array
	 */
	function get_post_types_by_taxonomy( $taxonomies ){
		$out = [];

		foreach( $taxonomies as $tax ){
			$taxonomy = get_taxonomy( $tax );
			if( $taxonomy ){
				$out = array_merge( $out, $taxonomy->object_type );
			}
		}

		return $out;
	}

}