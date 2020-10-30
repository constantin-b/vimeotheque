<?php
namespace Vimeotheque;

use Vimeotheque\Admin\Helper_Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Front_End
 * @package Vimeotheque
 */
class Front_End{
	/**
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * The content embed filter priority
	 *
	 * @var int
	 */
	private $embed_filter_priority = 999;

	/**
	 * Store a list of video post ID's to skip automatic embedding for.
	 * Used for video posts that have the block editor video position block
	 * or for posts that use the video position shortcode.
	 *
	 * @var array
	 */
	private $skip_autoembed = [];

	/**
	 * Front_End constructor.
	 *
	 * @param Plugin $plugin
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		add_action( 'init', [ $this, 'init' ], 9999 );
	}

	/**
	 * Init action callback
	 * Will set all filters and actions needed by the plugin to do embeds
	 */
	public function init(){
		/**
		 * Allow automatic embedding of videos callback to be prioritized preferentially.
		 * This can be used in case a membership plugin is used and the priority needs to
		 * be customized in order to protect the content.
		 *
		 * @param int $priority The priority set to do the embedding into the post content
		 */
		$this->embed_filter_priority = intval(
			apply_filters(
				'vimeotheque\embed_filter_priority',
				$this->embed_filter_priority
			)
		);

		// filter content to embed video
		add_filter( 'the_content', [
			$this,
			'embed_video'
		], $this->embed_filter_priority, 1 );

		// add player script
		add_action( 'wp_print_scripts', [
			$this,
			'add_player_script'
		] );

		/**
		 * Template function the_term() works by default only for post_tag taxonomy.
		 * This filter will add the plugin taxonomy for plugin custom post type
		 */
		// add this filter only in front-end
		if( ! is_admin() ){
			add_filter( 'get_the_terms', [
				$this,
				'filter_video_terms'
			], 10, 3 );
		}
	}

	/**
	 * Second filter on content - embeds video in post content
	 *
	 * @param string $content
	 * @return string
	 */
	public function embed_video( $content ){
		if( ! Helper::video_is_visible() ){
			return $content;
		}

		global $post;

		// check if post is password protected
		if( post_password_required( $post ) ){
			return $content;
		}

		// check if filters prevent auto embedding
		if( !Helper::is_autoembed_allowed() ){
			return $content;
		}

		// if video is in skipped auto embed list (has block or the video position shortcode in content), don't embed
		if( $this->skipped_autoembed( $post ) ){
			return $content;
		}

		$video_container = Helper::embed_video( $post, [], false );

		// put the filter back for other posts; remove in method 'prevent_autoembeds'
		add_filter( 'the_content', [
			$GLOBALS[ 'wp_embed' ],
			'autoembed'
		], 8 );

		$_post = Helper::get_video_post( $post );
		$settings = $_post->get_embed_options();

		if( 'below-content' == $settings[ 'video_position' ] ){
			return $content . $video_container;
		}else{
			return $video_container . $content;
		}
	}

	/**
	 * Check if post should be skipped from autoembedding
	 *
	 * @param \WP_Post $post
	 *
	 * @return bool
	 */
	private function skipped_autoembed( \WP_Post $post ){
		return in_array( $post->ID, $this->skip_autoembed );
	}

	/**
	 * Embed player script on video pages
	 *
	 * @return void
	 */
	public function add_player_script(){
		if( ! Helper::video_is_visible() ){
			return;
		}
		Helper::enqueue_player();
	}

	/**
	 * Filter the tags for the custom post type implemented by this plugin.
	 * Useful in template pages using function the_tags() - this function works
	 * only for the default post_tag taxonomy; the filter adds functionality
	 * for plugin post type tag taxonomy
	 *
	 * @param array $terms - the terms found
	 * @param int $post_id - the id of the post
	 * @param string $taxonomy - the taxonomy searched for
	 * @return
	 *
	 */
	public function filter_video_terms( $terms, $post_id, $taxonomy ){
		// get the current post
		$post = get_post( $post_id );
		if( ! $post ){
			return $terms;
		}
		// check only for the custom post type of the plugin
		if( $this->plugin->get_cpt()->get_post_type() == $post->post_type ){
			// the_tags() will check only for taxonomy post_tag. Check if this is what it's looking for and replace if true
			if( $taxonomy != $this->plugin->get_cpt()->get_tag_tax() && 'post_tag' == $taxonomy ){
				return get_the_terms( $post_id, $this->plugin->get_cpt()->get_tag_tax() );
			}
		}
		return $terms;
	}

	/**
	 * @return int
	 */
	public function get_embed_filter_priority(){
		return $this->embed_filter_priority;
	}

	/**
	 * Remove filter set on post content to embed the video;
	 * prevents automatic video embed above or below content when called.
	 *
	 * @param int|false $post_id    The post ID registered to skip the auto embedding for
	 */
	public function prevent_post_autoembed( $post_id = false ){
		if( !$post_id ){
			/**
			 * @var \WP_Post
			 */
			global $post;
			$post_id = $post->ID;
		}

		$this->skip_autoembed[ $post_id ] = $post_id;
	}
}