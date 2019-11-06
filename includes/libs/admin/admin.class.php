<?php

namespace Vimeotheque\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Exception;
use Vimeotheque\Admin\Notice\Plugin_Notice;
use Vimeotheque\Admin\Page\About_Page;
use Vimeotheque\Admin\Page\Automatic_Import_Page;
use Vimeotheque\Admin\Page\List_Videos_Page;
use Vimeotheque\Admin\Page\Post_Edit_Page;
use Vimeotheque\Admin\Page\Settings_Page;
use Vimeotheque\Admin\Page\Video_Import_Page;
use Vimeotheque\Helper;
use Vimeotheque\Post_Type;

/**
 * Admin pages
 * 
 * @author CodeFlavors
 */
class Admin{

	/**
	 * Store reference to Post_Type object
	 * 
	 * @var Post_Type
	 */
	private $cpt;

	/**
	 * Ajax Class reference
	 * 
	 * @var Ajax_Actions
	 */
	private $ajax;

	/**
	 * Store help screens references
	 */
	private $help_screens = [];
	
	/**
	 *
	 * @param Post_Type $post_type
	 */
	public function __construct( Post_Type $post_type ){
		// store object reference
		$this->cpt = $post_type;
		
		// start AJAX actions
		$this->ajax = new Ajax_Actions( $this->cpt );
		// start post edit single video page
		new Post_Edit_Page( $post_type );
		// add extra menu pages
		add_action( 'admin_menu', [
				$this, 
				'menu_pages'
		], 1 );
		// add admin capabilities
		add_action( 'init', [
			$this,
			'add_capabilities'
		], -999 );

		// help screens
		add_filter( 'contextual_help', [
				$this, 
				'contextual_help'
		], 10, 3 );
		// add columns to posts table
		add_filter( 'manage_edit-' . $this->cpt->get_post_type() . '_columns', [
				$this, 
				'extra_columns'
		] );
		add_action( 'manage_' . $this->cpt->get_post_type() . '_posts_custom_column', [
				$this, 
				'output_extra_columns'
		], 10, 2 );
		// video thumbnail bulk imports
		add_action( 'admin_print_scripts-edit.php', [
				$this, 
				'bulk_actions_js'
		] );

		add_action( 'admin_init', [
			$this,
			'activation_redirect'
		] );

		add_action( 'admin_init', [
			$this,
			'privacy_policy'
		] );
	}

	/**
	 * Add subpages for custom post type admin menu
	 */
	public function menu_pages(){
		$import_page = new Video_Import_Page( $this->cpt, $this->ajax );
		$video_import = add_submenu_page(
			'edit.php?post_type=' . $this->cpt->get_post_type(),
			__( 'Import videos', 'cvm_video' ),
			__( 'Import videos', 'cvm_video' ),
			$this->get_capability('manual_import'),
			'cvm_import',
			[
				$import_page, 
				'get_html'
			] );
		// page load
		add_action( 'load-' . $video_import, [
				$import_page, 
				'on_load'
		] );
		
		$automatic_page = new Automatic_Import_Page( $this->cpt );
		$automatic_import = add_submenu_page(
			'edit.php?post_type=' . $this->cpt->get_post_type(),
			__( 'Automatic Vimeo video import', 'cvm_video' ),
			__( 'Automatic import', 'cvm_video' ),
			$this->get_capability( 'automatic_import' ),
			'cvm_auto_import',
			[
				$automatic_page, 
				'get_html'
			] );
		add_action( 'load-' . $automatic_import, [
				$automatic_page, 
				'on_load'
		] );

		/**
		 * Plugin about page. Shown on plugin activation only
		 * @var \Vimeotheque\Admin\Page\About_Page
		 */
		$page = new About_Page( $this->cpt );
		$about_page = add_submenu_page(
			null ,
			__( 'About', 'cvm_video' ),
			__( 'About', 'cvm_video' ),
			'activate_plugins',
			'cvm_about',
			[
				$page,
				'get_html'
			] );
		add_action( 'load-' . $about_page, [
			$page,
			'on_load'
		] );

		// help screens
		$this->help_screens[ $automatic_import ] = [
				[
					'id' => 'cvm_automatic_import_overview',
					'title' => __( 'Overview', 'cvm_video' ),
					'content' => $this->get_contextual_help( 'automatic-import-overview' )
				],
				[
					'id' => 'cvm_automatic_import_frequency',
					'title' => __( 'Import frequency', 'cvm_video' ),
					'content' => $this->get_contextual_help( 'automatic-import-frequency' )
				],
				[
					'id' => 'cvm_automatic_import_as_post',
					'title' => __( 'Import videos as posts', 'cvm_video' ),
					'content' => $this->get_contextual_help( 'automatic-import-as-post' )
				]
		];
		
		$settings_page = new Settings_Page( $this->cpt );
		$settings = add_submenu_page(
			'edit.php?post_type=' . $this->cpt->get_post_type(),
			__( 'Settings', 'cvm_video' ),
			__( 'Settings', 'cvm_video' ),
			'manage_options',
			'cvm_settings',
			[
				$settings_page, 
				'get_html'
			] );
		add_action( 'load-' . $settings, [
				$settings_page, 
				'on_load'
		] );
		
		/**
		 * Shortcode videos list table
		 */
		$v_list = new List_Videos_Page( $this->cpt );
		$videos_list = add_submenu_page( null, __( 'Videos', 'cvm_video' ), __( 'Videos', 'cvm_video' ), 'edit_posts', 'cvm_videos', [
				$v_list, 
				'get_html'
		] );
		add_action( 'load-' . $videos_list, [
				$v_list, 
				'on_load'
		] );
	}

	/**
	 * Add admin capabilities
	 *
	 * @throws Exception
	 */
	public function add_capabilities(){
		if( !is_admin() ){
			return;
		}

		$capabilities = $this->get_capability();
		// admin always has access
		$admin = get_role('administrator');
		foreach ( $capabilities as $cap ) {
			$admin->add_cap( $cap['capability'] );
		}

		$roles = $this->get_roles();
		foreach( $roles as $role => $name ){
			$r = get_role( $role );
			if( is_a( $r, 'WP_Role' ) ) {
				foreach ( $capabilities as $cap ) {
					$r->add_cap( $cap['capability'] );
				}
			}
		}
	}

	/**
	 * @param $contextual_help
	 * @param $screen_id
	 * @param \WP_Screen $screen
	 *
	 * @return mixed
	 */
	public function contextual_help( $contextual_help, $screen_id, $screen ){
		// if not hooks page, return default contextual help
		if( ! is_array( $this->help_screens ) || ! array_key_exists( $screen_id, $this->help_screens ) ){
			return $contextual_help;
		}
		
		// current screen help screens
		$help_screens = $this->help_screens[ $screen_id ];
		
		// create help tabs
		foreach( $help_screens as $help_screen ){
			$screen->add_help_tab( $help_screen );
		}
	}

	/**
	 * Extra columns in list table
	 *
	 * @param array $columns
	 *
	 * @return array
	 */
	public function extra_columns( $columns ){
		$cols = [];
		foreach( $columns as $c => $t ){
			$cols[ $c ] = $t;
			if( 'title' == $c ){
				$cols[ 'video_id' ] = __( 'Video ID', 'cvm_video' );
				$cols[ 'duration' ] = __( 'Duration', 'cvm_video' );
			}
		}
		return $cols;
	}

	/**
	 * Extra columns in list table output
	 * 
	 * @param string $column_name
	 * @param int $post_id
	 */
	public function output_extra_columns( $column_name, $post_id ){
		$_post = Helper::get_video_post( $post_id );
		$meta = $_post->get_video_data();
		switch( $column_name ){
			case 'video_id':
				echo $meta['video_id'];
			break;
			case 'duration':
				echo \Vimeotheque\human_time( $meta[ 'duration' ] );
			break;
		}
	}

	/**
	 * Hackish method to inject new bulk actions to post table views
	 * 
	 * @return null
	 */
	public function bulk_actions_js(){
		$screen = get_current_screen();
		if( 'edit' != $screen->base ){
			return;
		}
		
		wp_enqueue_script( 'cvm-bulk-actions', VIMEOTHEQUE_URL . 'assets/back-end/js/bulk-actions.js', [
				'jquery'
		], '1.0' );
		
		wp_enqueue_style( 'cvm-bulk-actions-response', VIMEOTHEQUE_URL . 'assets/back-end/css/video-list.css', false, '1.0' );
		
		wp_localize_script( 'cvm-bulk-actions', 'cvm_bulk_actions', [
				'actions' => $this->bulk_actions(), 
				'wait' => __( 'Processing, please wait...', 'cvm_video' ), 
				'wait_longer' => __( 'Not done yet, please be patient...', 'cvm_video' ), 
				'maybe_error' => __( 'There was an error while importing your thumbnails. Please try again.', 'cvm_video' )
		] );
	}

	/**
	 * Some allowed bulk actions
	 *
	 * @return array
	 */
	private function bulk_actions(){
		$actions = [
				'cvm_thumbnail' => __( 'Import thumbnails', 'cvm_video' )
		];
		
		return $actions;
	}

	/**
	 * Triggered on plugin activation
	 */
	public function plugin_activation(){
		set_transient( 'cvm_plugin_activation' , true, 30 );
	}

	/**
	 * Admin init callback, redirects to plugin Settings page after plugin activation.
	 */
	public function activation_redirect(){
		$t = get_transient( 'cvm_plugin_activation' );
		if( $t ){
			delete_transient( 'cvm_plugin_activation' );
			wp_redirect( str_replace( '#038;' , '&', menu_page_url( 'cvm_about', false ) ) );
			die();
		}
	}

	/**
	 * Add to Privacy policy
	 */
	public function privacy_policy(){
		if( !function_exists( 'wp_add_privacy_policy_content' ) ){
			return;
		}

		$policy_content = sprintf(
			__( 'By using the embed feature of this plugin you will be agreeing to Vimeo\'s privacy policy. More details can be found here: %s', 'cvm-video' ),
			'https://vimeo.com/privacy'
		);

		wp_add_privacy_policy_content( 'Vimeotheque PRO', $policy_content );
	}

	/**
	 * @param bool $cap
	 *
	 * @return array|mixed
	 */
	public function get_capability( $cap = false ){
		$capabilities = [
			'manual_import' => [
				'capability' => 'cvm_manual_import',
				'description' => __( 'Manual bulk import', 'cvm_video' )
			],
			'automatic_import' => [
				'capability' => 'cvm_automatic_import',
				'description' => __( 'Automatic import', 'cvm_video' )
			]
		];

		if( !$cap ){
			return $capabilities;
		}

		if( isset( $capabilities[ $cap ] ) ){
			return $capabilities[ $cap ]['capability'];
		}else{
			throw new Exception( sprintf( 'Capability "%s" could not be found.', $cap ) );
		}
	}

	/**
	 * @return array
	 */
	public function get_roles(){
		$roles = [
			'editor' => __( 'Editor', 'cvm_video' ),
			'author' => __( 'Author', 'cvm_video' ),
			'contributor' => __( 'Contributor', 'cvm_video' ),
		];

		return $roles;
	}

	/**
	 * Gets contextual help content from external file
	 *
	 * @param $file
	 *
	 * @return bool|false|string
	 */
	private function get_contextual_help( $file ){
		if( !$file ){
			return false;
		}
		$file_path = VIMEOTHEQUE_PATH . 'views/help/' . $file . '.html.php';
		if( is_file($file_path) ){
			ob_start();
			include( $file_path );
			$help_contents = ob_get_contents();
			ob_end_clean();
			return $help_contents;
		}else{
			return false;
		}
	}
}