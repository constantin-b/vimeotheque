<?php

namespace Vimeotheque\Shortcode;

use Vimeotheque\Helper;
use Vimeotheque\Video_Post;
use function Vimeotheque\cvm_get_post_types_by_taxonomy;

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
	 * Playlist constructor.
	 *
	 * @param $atts
	 * @param $content
	 */
	public function __construct( $atts, $content ) {
		parent::__construct( $atts, $content );
	}

	/**
	 * @return string|void
	 */
	public function get_output(){
		$videos = $this->get_video_posts();
		if( !$videos ){
			return;
		}

		ob_start();

		// set custom player settings if any
		global $CVM_PLAYER_SETTINGS;
		$player_settings = $this->get_options();

		$defaults = \Vimeotheque\get_player_settings();
		foreach ( $defaults as $setting => $value ){
			if( isset( $player_settings[ $setting ] ) ){
				if( is_numeric( $defaults[ $setting ] ) && $defaults[ $setting ] <= 1 ){
					$defaults[ $setting ] = 1 == $player_settings[ $setting ] ? 1 : 0;
				}else{
					$defaults[ $setting ] = $player_settings[ $setting ];
				}
			}
		}

		$defaults['theme'] = $player_settings['theme'];
		$defaults['layout'] = $player_settings['layout'];

		$CVM_PLAYER_SETTINGS = $defaults;
		/**
		 * @var Video_Post
		 */
		global $cvm_video;

		if( !array_key_exists( $defaults['theme'], \Vimeotheque\cvm_playlist_themes() ) ){
			$theme = 'default';
		}else{
			$theme = $defaults['theme'];
		}
		// include theme functions
		include_once( VIMEOTHEQUE_PATH . '/includes/theme-functions.php' );
		// include the theme display file
		include( VIMEOTHEQUE_PATH . 'themes/' . $theme . '/player.php' );

		$content = ob_get_contents();
		ob_end_clean();

		Helper::enqueue_player();
		wp_enqueue_script(
			'cvm-vim-player-'.$theme,
			VIMEOTHEQUE_URL . 'themes/' . $theme . '/assets/script.js',
			[ 'cvm-video-player' ],
			'1.0'
		);
		wp_enqueue_style(
			'cvm-vim-player-'.$theme,
			VIMEOTHEQUE_URL . 'themes/' . $theme . '/assets/stylesheet.css',
			false,
			'1.0'
		);

		// remove custom player settings
		$CVM_PLAYER_SETTINGS = false;

		return $content;
	}

	/**
	 * Shortcode defaults
	 *
	 * @return array
	 */
	private function get_options(){
		if( $this->options ){
			return $this->options;
		}

		$defaults = [
			'theme' 		=> 'default',
			'aspect_ratio' 	=> '16x9',
			'width' 		=> 0,
			'volume' 		=> 20,
			'title'			=> 1,
			'byline'		=> 1,
			'portrait'		=> 1,
			'playlist_loop' => 0,
			'videos' 		=> '',
			'categories'    => '',
			'post_type'     => ''
		];

		$this->options = wp_parse_args( parent::get_atts(), $defaults );

		return $this->options;
	}

	/**
	 * @param $name
	 *
	 * @return mixed|\WP_Error
	 */
	private function get_option( $name ){
		$options = $this->get_options();
		if( array_key_exists( $name, $options ) ){
			return $options[ $name ];
		}

		return new \WP_Error(
			'shortcode_option_missing',
			sprintf(
				__( 'Option "%s" is missing from shortcode options.', 'cvm_video' ),
				$name
			)
		);
	}

	/**
	 * @return array
	 */
	private function get_video_posts(){
		$posts = [];
		$videos = $this->get_option( 'videos' );
		if( $videos && !is_wp_error( $videos ) ){
			$ids = explode( ',', $videos );
			$_posts = get_posts( [
				'post_type' => 'any',
				'include' => $ids,
				'posts_per_page' => count( $ids ),
				'numberposts' => count( $ids ),
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

		$categories = $this->get_option( 'categories' );
		if( $categories && !is_wp_error( $categories ) ){
			$_categories = explode( ',', $categories );
			$post_type = $this->get_option( 'post_type' );
			if( $post_type && !is_wp_error( $post_type ) ){
				$_post_type = explode( ',', $post_type );
			}else{
				$_post_type = false;
			}

			$_posts = $this->get_category_post_ids( $_categories, $_post_type );
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
	 * @param $tax
	 *
	 * @return array
	 */
	function get_post_types_by_taxonomy( $tax ){
		$out = [];
		$post_types = get_post_types();
		foreach( $post_types as $post_type ){
			$taxonomies = get_object_taxonomies( $post_type );
			if( array_intersect( $tax, $taxonomies ) ){
				$out[] = $post_type;
			}
		}
		return $out;
	}

}