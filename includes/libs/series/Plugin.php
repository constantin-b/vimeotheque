<?php
namespace Vimeotheque_Series;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

use Vimeotheque_Series\Admin\Admin;
use Vimeotheque_Series\Admin\Metabox\Metabox_Factory;
use Vimeotheque_Series\Admin\Metabox\Player_Metabox;
use Vimeotheque_Series\Admin\Metabox\Post_Actions;
use Vimeotheque_Series\Admin\Metabox\Shortcode_Metabox;
use Vimeotheque_Series\Admin\Metabox\Theme_Metabox;
use Vimeotheque_Series\Admin\Metabox\Video_List_Metabox;
use Vimeotheque_Series\Post_Type\Series;
use Vimeotheque_Series\Rest_Api\Rest_Endpoint_Factory;
use Vimeotheque_Series\Rest_Api\Series_Rest_Fields;
use Vimeotheque_Series\Series\Block_Editor;
use Vimeotheque_Series\Series\Playlist;
use Vimeotheque_Series\Series\Single_Post;
use Vimeotheque_Series\Themes\Theme;

/**
 * Class Plugin
 */
class Plugin {

	/**
	 * Holds the plugin instance.
	 *
	 * @var Plugin
	 */
	private static $instance = null;
	/**
	 * @var Admin
	 */
	private $admin;
	/**
	 * @var Series
	 */
	private $post_type;
    /**
     * Theme manager.
     *
     * @var Themes\Themes
     */
    private $themes;
    /**
     * @var Metabox_Factory
     */
    private $metaboxes;

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
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'codeflavors-vimeo-video-post-lite' ), '2.0' );
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
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'codeflavors-vimeo-video-post-lite' ), '2.0' );
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
		}

		return self::$instance;
	}

	/**
	 * Class constructors - sets all filters and hooks
	 */
	private function __construct(){
		// start the autoloader
		$this->register_autoloader();
			
		$this->init();
		
		add_action( 'init', [
			$this,
			'init_admin'
		], -99 );

        add_action(
            'init',
            function(){
                add_shortcode(
                    'vimeotheque_series',
                    function( $atts ){

                        if( isset( $atts['id'] ) ){
                            $post = get_post($atts['id']);
                            if( $post && 'series' == $post->post_type && 'publish' == $post->post_status ){
                                $playlist = new Playlist($atts['id'] );
                                return $playlist->get_content();
                            }
                        }
                    }
                );
            }
        );

	}

	/**
	 * Register the autoloader
	 */
	private function register_autoloader(){
		require VIMEOTHEQUE_PATH . 'includes/libs/series/Autoload.php';
        Autoload::run();
	}

	private function init(){
		$this->post_type = new Series();
        // Initiate rest fields
        new Series_Rest_Fields();

        // Initiate the themes Rest Controller.
        new Rest_Endpoint_Factory();

        $this->themes = new Themes\Themes(
            new Theme(
                  Helper::get_path() . 'themes-series/default/theme.php',
                __( 'Default', 'codeflavors-vimeo-video-post-lite' ),
                'assets/js/script.js',
                'assets/css/style.css'
            )
        );

        $this->themes->register_theme(
            new Theme(
                Helper::get_path() . 'themes-series/list/theme.php',
                __( 'List', 'codeflavors-vimeo-video-post-lite' ),
                'assets/js/script.js',
                'assets/css/style.css'
            )
        );

        $this->themes->register_theme(
            new Theme(
                Helper::get_path() . 'themes-series/carousel/theme.php',
                __('Carousel', 'codeflavors-vimeo-video-post-lite'),
                'assets/js/script.js',
                'assets/css/style.css'
            )
        );

        // Meta boxes
        $this->metaboxes = new Metabox_Factory(
            new Video_List_Metabox(
                'vimeotheque-series-video-list-metabox',
                esc_html__( 'Videos', 'codeflavors-vimeo-video-post-lite' ),
                $this->post_type->get_post_name()
            )
        );

        $this->metaboxes->register_meta_box(
            new Theme_Metabox(
                'vimeotheque-series-theme-metabox',
                esc_html__( 'Theme', 'codeflavors-vimeo-video-post-lite' ),
                $this->post_type->get_post_name()
            )
        );

        $this->metaboxes->register_meta_box(
            new Post_Actions(
                'vimeotheque-series-post-actions-metabox',
                esc_html__( 'Actions', 'codeflavors-vimeo-video-post-lite' ),
                $this->post_type->get_post_name(),
                'side'
            )
        );

        $this->metaboxes->register_meta_box(
            new Player_Metabox(
                'vimeotheque-series-player-metabox',
                esc_html__('Video Player', 'codeflavors-vimeo-video-post-lite'),
                $this->post_type->get_post_name(),
                'side'
            )
        );

        $this->metaboxes->register_meta_box(
            new Shortcode_Metabox(
                'vimeotheque-series-shortcode-metabox',
                esc_html__( 'Shortcode', 'codeflavors-vimeo-video-post-lite' ),
                $this->post_type->get_post_name(),
                'side'
            )
        );

        if( !is_admin() ){
            new Single_Post();
        }

        new Block_Editor();
	}
	
	/**
	 * Initialize administration
	 */
	public function init_admin(){
		if( is_admin() ){
			$this->admin = new Admin();
		}
	}

	/**
	 * @return Series
	 */
	public function get_post_type(): Series {
		return $this->post_type;
	}

    /**
     * @return Themes\Themes
     */
    public function get_themes_manager (): Themes\Themes {
        return $this->themes;
    }

    /**
     * @return Metabox_Factory
     */
    public function get_metaboxes (): Metabox_Factory {
        return $this->metaboxes;
    }


}

Plugin::instance();