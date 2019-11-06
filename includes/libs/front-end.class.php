<?php
namespace Vimeotheque;

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
		$priority = intval( apply_filters( 'cvm_plugin_embed_content_filter_priority', 999 ) );

		// filter content to embed video
		add_filter( 'the_content', [
			$this,
			'embed_video'
		], $priority, 1 );

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

		$_post = Helper::get_video_post( $post );

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

		if( ! $_post->video_id ){
			return $content;
		}

		/**
		 * Allow embed settings filtering that can change the embedding options
		 * when the post is displayed.
		 *
		 * @var array
		 *
		 * @param array $embed_settings - the post video embed settings
		 * @param object $post - the current post being displayed
		 * @param array $video - the video details as retrieved from Vimeo
		 */
		$settings = apply_filters( 'cvm_video_embed_settings', $_post->get_embed_options(), $post, $_post->get_video_data() );

		$settings[ 'video_id' ] = $_post->video_id;

		/**
		 * Filter that can be used to modify the width of the embed
		 *
		 * @var int
		 */
		$width = apply_filters( 'cvm-embed-width', $settings[ 'width' ], $_post->get_video_data(), 'automatic_embed' );
		/**
		 * @deprecated
		 * Filter that can be used to modify the width of the embed
		 *
		 * @var int
		 */
		$width = apply_filters( 'cvm_embed_width', $width, $_post->get_video_data(), 'automatic_embed' );
		$height = cvm_player_height( $settings[ 'aspect_ratio' ], $width, $settings[ 'size_ratio' ] );

		/**
		 *
		 * @deprecated - Use cvm_video_embed_css_class
		 */
		$class = apply_filters( 'cvm_video_post_css_class', [], $post );

		/**
		 * Filter on video container CSS class to add extra CSS classes
		 * Name: cvm_video_post_css_class
		 * Params: - an empty array
		 * - the post object that will embed the video
		 *
		 * @var string
		 */
		$class = apply_filters( 'cvm_video_embed_css_class', $class, $post );
		$extra_css = implode( ' ', ( array ) $class );

		$video_data_atts = cvm_data_attributes( $settings );

		// if js embedding not allowed, embed by placing the iframe dirctly into the post content
		$embed_html = '<!--video container-->';
		if( isset( $settings[ 'js_embed' ] ) && !$settings[ 'js_embed' ] ){
			$params = [
				'title' => $settings[ 'title' ],
				'byline' => $settings[ 'byline' ],
				'portrait' => $settings[ 'portrait' ],
				'loop' => $settings[ 'loop' ],
				'color' => $settings[ 'color' ],
				//'fullscreen' => $settings[ 'fullscreen' ]
			];
			$embed_url = 'https://player.vimeo.com/video/' . $_post->video_id . '?' . http_build_query( $params, '', '&' );
			$extra_css .= ' cvm_simple_embed';
			$embed_html = '<iframe src="' . $embed_url . '" width="100%" height="100%" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
		}else{
			// add player script
			cvm_enqueue_player();
		}

		$video_container = '<div class="cvm_single_video_player ' . $extra_css . '" ' . $video_data_atts . ' style="width:' . $width . 'px; height:' . $height . 'px; max-width:100%;">' . $embed_html . '</div>';

		// put the filter back for other posts; remove in method 'prevent_autoembeds'
		add_filter( 'the_content', [
			$GLOBALS[ 'wp_embed' ],
			'autoembed'
		], 8 );

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
		cvm_enqueue_player();
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
}