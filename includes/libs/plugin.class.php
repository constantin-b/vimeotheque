<?php

namespace Vimeotheque;

use Vimeotheque\Admin\Admin;
use Vimeotheque\Admin\WP_Customizer;
use Vimeotheque\Blocks\Blocks_Factory;
use Vimeotheque\Options\Options;
use Vimeotheque\Options\Options_Factory;
use Vimeotheque\Playlist\Theme\Theme;
use Vimeotheque\Playlist\Theme\Themes;
use Vimeotheque\Shortcode\Shortcode_Factory;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Plugin
 * @package Vimeotheque
 */
class Plugin{

	/**
	* Holds the plugin instance.
	*
	* @var Plugin
	*/
	public static $instance = null;

	/**
	 * Stores plugin options
	 *
	 * @var Options
	 */
	private $options;

	/**
	 * Stores player options
	 *
	 * @var Options
	 */
	private $player_options;

	/**
	 * @var Post_Type
	 */
	private $cpt;

	/**
	 * Store admin instance
	 * @var Admin
	 */
	private $admin;

	/**
	 * @var WP_Customizer
	 */
	private $wp_customizer;

	/**
	 * @var Posts_Import
	 */
	private $posts_import;
	/**
	 * @var Front_End
	 */
	private $front_end;
	/**
	 * @var Blocks_Factory
	 */
	private $blocks_factory;
	/**
	 * @var Themes
	 */
	private $playlist_themes;

	/**
	 * Clone.
	 *
	 * Disable class cloning and throw an error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object. Therefore, we don't want the object to be cloned.
	 *
	 * @access public
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'cvm_video' ), '2.0' );
	}

	/**
	 * Wakeup.
	 *
	 * Disable unserializing of the class.
	 *
	 * @access public
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'cvm_video' ), '2.0' );
	}

	/**
	 * Instance.
	 *
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @access public
	 * @static
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			/**
			 * Vimeotheque loaded.
			 *
			 * Fires when Vimeotheque was fully loaded and instantiated.
			 *
			 * @since 2.0
			 */
			do_action( 'vimeotheque_loaded' );
		}

		return self::$instance;
	}

	/**
	 * Class constructors - sets all filters and hooks
	 */
	private function __construct(){
		// start the autoloader
		$this->register_autoloader();
		// load dependency files
		$this->load();

		// activation hook to add the rewrite rules for the custom post type
		register_activation_hook( VIMEOTHEQUE_FILE, [
			$this,
			'activation_hook'
		] );

		add_action( 'plugins_loaded', [
			$this,
			'init'
		], 1 );

		// run this admin init on init to have access to earlier hooks
		// priority must be set to run very early so that init hooks set
		// in admin page can also run
		add_action( 'init', [
			$this,
			'admin_init'
		], -9999999 );
	}

	public function init(){
		// register the post type
		$this->set_post_type();
		// set the importer
		$this->load_importer();
		// start the front-end
		$this->load_front_end();

		new Shortcode_Factory( $this );
		$this->blocks_factory = new Blocks_Factory( $this );

		$this->playlist_themes = new Themes( new Theme( VIMEOTHEQUE_PATH . 'themes/default/player.php', __( 'Default', 'cvm_video' ) ) );
	}

	/**
	 * Register the autoloader
	 */
	private function register_autoloader(){
		require VIMEOTHEQUE_PATH . 'includes/libs/autoload.class.php';
		Autoload::run();
	}

	/**
	 * Register the post type
	 */
	private function set_post_type(){
		$this->cpt = new Post_Type( $this );
	}

	/**
	 * Loads the automatic importer
	 */
	private function load_importer(){
		$this->posts_import = new Posts_Import( $this->get_cpt() );
	}

	/**
	 * Loads the front-end
	 */
	private function load_front_end(){
		// start the REST API compatibility
		new Rest_Api( $this->get_cpt() );
		// start the front-end functionality
		$this->front_end = new Front_End( $this );
	}

	/**
	 * Set plugin options
	 */
	private function set_plugin_options(){
		$defaults = [
			'public' => true, // post type is public or not
			'archives' => false, // display video embed on archive pages
			'post_slug'	=> 'vimeo-video',
			'taxonomy_slug' => 'vimeo-videos',
			'tag_slug' => 'vimeo-tag',
			'import_tags' => false, // import tags retrieved from Vimeo
			'max_tags' => 3, // how many tags to import
			'import_title' => true, // import titles on custom posts
			'import_description' => 'post_content', // import descriptions on custom posts
			'import_date' => false, // import video date as post date
			'featured_image' => false, // set thumbnail as featured image; default import on video feed import (takes more time)
			'image_on_demand' => false, // when true, thumbnails will get imported only when viewing the video post as oposed to being imported on feed importing
			'import_status' => 'draft', // default import status of videos
			// Vimeo oAuth
			'vimeo_consumer_key' => '',
			'vimeo_secret_key' => '',
			'oauth_token' => '' // retrieved from Vimeo; gets set after entering valid client ID and client secret
		];

		/**
		 * Options filter
		 * @param array $defaults
		 */
		$defaults = apply_filters( 'vimeotheque\options_default', $defaults );

		$this->options = Options_Factory::get( '_cvm_plugin_settings', $defaults );
	}

	/**
	 * Set video player options
	 */
	private function set_player_options(){
		$defaults = [
			'title'	=> 1, 	// show video title
			'byline' => 1, 	// show player controls. Values: 0 or 1
			'portrait' => 1, 	// show author image
			'loop' => 0,
			// Autoplay may be blocked in some environments, such as IOS, Chrome 66+, and Safari 11+. In these cases, we’ll revert to standard playback requiring viewers to initiate playback.
			'autoplay' => 0, 	// 0 - on load, player won't play video; 1 - on load player plays video automatically
			'color'		=> '', 	// no color set by default; will use Vimeo's settings
			// extra settings
			'aspect_ratio' => '16x9',
			'width'	=> 640,
			'video_position' => 'below-content', // in front-end custom post, where to display the video: above or below post content
			'volume' => 25, // video default volume
			// extra player settings controllable by widgets/shortcodes
			'playlist_loop' => 0,
			'js_embed' => true, // if true, embedding is done by JavaScript. If false, embedding is done by PHP by simply placing the iframe code into the page
		];

		/**
		 * Filter for player options
		 * @param array $defaults
		 */
		$defaults = apply_filters( 'vimeotheque\player_options_default', $defaults );

		// get Plugin option
		$this->player_options = Options_Factory::get( '_cvm_player_settings', $defaults );
	}

	/**
	 * Runs on plugin activation and registers rewrite rules
	 * for video custom post type
	 *
	 * @return void
	 */
	public function activation_hook(){
		$this->set_post_type();
		// register custom post
		$this->get_cpt()->register_post();
		// create rewrite ( soft )
		flush_rewrite_rules( false );

		$this->add_admin();

		if( $this->admin ){
			$this->admin->plugin_activation();
		}
	}

	/**
	 * Returns plugin options array
	 *
	 * @return array
	 */
	public function get_options( $defaults = null ){
		if( !$this->options ){
			$this->set_plugin_options();
		}

		if( $this->wp_customizer && is_customize_preview() ){
			$defaults = null === $defaults ? $this->options->get_options() : $defaults;
			$options = $this->wp_customizer->get_changeset_data( $defaults );
		}else{
			if( null === $defaults ){
				$options = $this->options->get_options();
			}else {
				$options = $defaults;
			}
		}

		return $options;
	}

	/**
	 * Returns options object
	 *
	 * @return Options
	 */
	public function get_options_obj(){
		if( !$this->options ){
			$this->set_plugin_options();
		}

		return $this->options;
	}

	/**
	 * Returns player options object
	 *
	 * @return Options
	 */
	public function get_player_options(){
		if( !$this->player_options ){
			$this->set_player_options();
		}

		return $this->player_options;
	}

	/**
	 * Callback function for hook "admin_init"
	 */
	public function admin_init(){
		$this->add_customizer();
		$this->add_admin();
	}

	/**
	 * Implements WP customizer functionality
	 */
	private function add_customizer(){
		if( is_admin() || is_customize_preview() ) {
			// start the WP Customizer compatibility class
			$this->wp_customizer = new WP_Customizer( $this->get_cpt() );
		}
	}

	/**
	 * Adds plugin administration functionality
	 */
	private function add_admin(){
		if( is_admin() ) {
			$this->admin = new Admin( $this->get_cpt() );
		}
	}

	/**
	 * @param bool $cap
	 *
	 * @return array|mixed
	 * @throws \Exception
	 */
	public function get_capability( $cap = false ){
		return $this->admin->get_capability( $cap );
	}

	/**
	 * @return array
	 */
	public function get_roles(){
		return $this->admin->get_roles();
	}

	/**
	 * @return Post_Type
	 */
	public function get_cpt(){
		return $this->cpt;
	}

	/**
	 * @return Posts_Import
	 */
	public function get_posts_importer(){
		return $this->posts_import;
	}

	/**
	 * @return Admin
	 */
	public function get_admin(){
		return $this->admin;
	}

	/**
	 * @return Front_End
	 */
	public function get_front_end(){
		return $this->front_end;
	}

	/**
	 * @return Themes
	 */
	public function get_playlist_themes() {
		return $this->playlist_themes;
	}

	/**
	 *
	 *
	 * @param string $key - string key for the block
	 *
	 * @return \WP_Block_Type
	 * @see Blocks_Factory::register_blocks() for all keys
	 */
	public function get_block( $key ) {
		return $this->blocks_factory->get_block( $key )->get_wp_block_type();
	}

	/**
	 * Load dependencies
	 * @return void
	 */
	private function load(){
		include_once VIMEOTHEQUE_PATH . 'includes/functions.php';
		include_once VIMEOTHEQUE_PATH . 'includes/deprecated.php';
	}
}

Plugin::instance();