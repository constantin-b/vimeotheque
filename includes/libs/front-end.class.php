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

	private $embed_filter_priority = 999;

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
		 * @var integer
		 */
		$this->embed_filter_priority = intval( apply_filters( 'cvm_plugin_embed_content_filter_priority', $this->embed_filter_priority ) );

		// filter content to embed video
		//*
		add_filter( 'the_content', [
			$this,
			'embed_video'
		], $this->embed_filter_priority, 1 );
		//*/
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
	 * Callback function for filter 'get_the_categories' set up in function 'CBC_Video_Post_Type->add_to_main_feed'
	 * When custom post type is inserted into main feed for each post the correct categories based
	 * on post type taxonomy must be set.
	 * This does that otherwise all custom post type categories in
	 * feed will end up as Uncategorized.
	 *
	 * @param array $categories
	 * @return array|false|\WP_Error
	 */
	public function set_feed_video_categories( $categories ){
		global $post;

		if( ! $post || $this->plugin->get_cpt()->get_post_type() != $post->post_type ){
			return $categories;
		}

		$categories = get_the_terms( $post, $this->plugin->get_cpt()->get_post_tax() );
		if( ! $categories || is_wp_error( $categories ) )
			$categories = [];

		$categories = array_values( $categories );
		foreach( array_keys( $categories ) as $key ){
			_make_cat_compat( $categories[ $key ] );
		}

		return $categories;
	}

	/**
	 * Second filter on content - embeds video in post content
	 *
	 * @param string $content
	 * @return string
	 */
	public function embed_video( $content ){
		if( ! $this->is_visible() ){
			return $content;
		}

		global $post;

		// check if post is password protected
		if( post_password_required( $post ) ){
			return $content;
		}

		/**
		 * Filter that can prevent video embedding by the plugin.
		 * Useful if user wants to implement
		 * his own templates for video post type.
		 *
		 * @var bool
		 */
		$allow_embed = apply_filters( 'cvm_automatic_video_embed', true );
		if( ! $allow_embed ){
			return $content;
		}

		$video_container = get_video_embed_html( $post, false );

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
	 * Embed player script on video pages
	 *
	 * @return void
	 */
	public function add_player_script(){
		if( ! $this->is_visible() ){
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
	 * Helper function to determine if video embed is visible in
	 * post content based on a number of factors
	 *
	 * !!! NOTE !!!
	 * Will always return false for pages and attachments unless display in archives
	 * option is enabled.
	 *
	 * @return bool - true is visible; false if not visible
	 */
	private function is_visible(){
		$options = $this->plugin->get_options();
		$is_visible = $options[ 'archives' ] ? true : is_single();
		if( is_admin() || ! $is_visible || ! is_video() ){
			return false;
		}
		return true;
	}

	/**
	 * @return int
	 */
	public function get_embed_filter_priority(){
		return $this->embed_filter_priority;
	}
}