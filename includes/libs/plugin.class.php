<?php

namespace Vimeotheque;

use Vimeotheque\Admin\Admin;
use Vimeotheque\Admin\WP_Customizer;

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
	 * @var array
	 */
	private $options;

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
		// register the post type
		$this->set_post_type();
		// set the importer
		$this->load_importer();
		// start the front-end
		$this->load_front_end();

		// activation hook to add the rewrite rules for the custom post type
		register_activation_hook( __FILE__, [
			$this,
			'activation_hook'
		] );

		// store plugin options for later use
		$this->options = get_settings();

		// run this admin init on init to have access to earlier hooks
		// priority must be set to run very early so that init hooks set
		// in admin page can also run
		add_action( 'init', [
			$this,
			'admin_init'
		], -999999999 );
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
		new Front_End( $this );
	}

	/**
	 * Runs on plugin activation and registeres rewrite rules
	 * for video custom post type
	 *
	 * @return void
	 */
	public function activation_hook(){
		// register custom post
		$this->get_cpt()->register_post();
		// create rewrite ( soft )
		flush_rewrite_rules( false );

		$this->admin_init();
		if( $this->admin ){
			$this->admin->plugin_activation();
		}
	}

	/**
	 * @return array
	 */
	public function get_options( $defaults = null ){
		$options = $this->options;
		if( $this->wp_customizer && is_customize_preview() ){
			$defaults = null === $defaults ? get_settings() : $defaults;
			$options = $this->wp_customizer->get_changeset_data( $defaults );
		}else{
			if( null === $defaults ){
				$options = get_settings();
			}else {
				$options = $defaults;
			}
		}

		return $options;
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
	 * Load dependencies
	 * @return void
	 */
	private function load(){
		include_once VIMEOTHEQUE_PATH . 'includes/functions.php';
		include_once VIMEOTHEQUE_PATH . 'includes/shortcodes.php';
		include_once VIMEOTHEQUE_PATH . 'includes/deprecated.php';
	}
}

Plugin::instance();