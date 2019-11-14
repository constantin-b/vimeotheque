<?php

namespace Vimeotheque\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Exception;
use Vimeotheque\Admin\Notice\Admin_Notices;
use Vimeotheque\Admin\Notice\Plugin_Notice;
use Vimeotheque\Admin\Page\About_Page;
use Vimeotheque\Admin\Page\Automatic_Import_Page;
use Vimeotheque\Admin\Page\Go_Pro_Page;
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
	 * Pro menu priority
	 */
	const PRO_MENU_PRIORITY = 512;

	/**
	 *
	 * @param Post_Type $post_type
	 */
	public function __construct( Post_Type $post_type ){
		// store object reference
		$this->cpt = $post_type;

		add_action( 'wp_loaded', [ $this, 'init' ], 1 );

		// add extra menu pages
		add_action( 'admin_menu', [
				$this, 
				'menu_pages'
		], 1 );

		add_action( 'admin_menu', [
			$this,
			'pro_menu'
		], self::PRO_MENU_PRIORITY );

		// add admin capabilities
		add_action( 'init', [
			$this,
			'add_capabilities'
		], -999 );

		// add columns to posts table
		add_filter( 'manage_edit-' . $this->cpt->get_post_type() . '_columns', [
				$this, 
				'extra_columns'
		] );

		add_action( 'manage_' . $this->cpt->get_post_type() . '_posts_custom_column', [
				$this, 
				'output_extra_columns'
		], 10, 2 );

		// alert if setting to import as post type post by default is set on all plugin pages
		add_action( 'admin_notices', [
			$this,
			'admin_notices'
		], 10 );

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
	 * Initialize
	 */
	public function init(){
		// start AJAX actions
		$this->ajax = new Ajax_Actions( $this->cpt );
		// start post edit single video page
		new Post_Edit_Page( $this->cpt );
	}

	/**
	 * Add subpage for custom post type admin menu
	 */
	public function menu_pages(){

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
			'vimeotheque_about',
			[
				$page,
				'get_html'
			] );
		add_action( 'load-' . $about_page, [
			$page,
			'on_load'
		] );

		/**
		 * Shortcode videos list table
		 */
		$v_list = new List_Videos_Page( $this->cpt );
		$videos_list = add_submenu_page(
			null,
			__( 'Videos', 'cvm_video' ),
			__( 'Videos', 'cvm_video' ),
			'edit_posts',
			'cvm_videos',
			[
				$v_list, 
				'get_html'
			]
		);

		add_action( 'load-' . $videos_list, [
				$v_list, 
				'on_load'
		] );
	}

	/**
	 * @throws Exception
	 */
	public function pro_menu(){
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


		$gopro_page = new Go_Pro_Page( $this->cpt );
		$gopro = add_submenu_page(
			'edit.php?post_type=' . $this->cpt->get_post_type(),
			__( 'Go PRO!', 'cvm_video' ),
			__( 'Go PRO!', 'cvm_video' ),
			'manage_options',
			'cvm_go_pro',
			[
				$gopro_page,
				'get_html'
			]
		);
		add_action( 'load-' . $gopro,
			[
				$gopro_page,
				'on_load'
			]
		);
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
				echo \Vimeotheque\Helper::human_time( $meta[ 'duration' ] );
			break;
		}
	}

	/**
	 * Set admin notices
	 */
	public function admin_notices(){
		if( !isset( $_GET['post_type'] ) || $this->cpt->get_post_type() != $_GET['post_type'] ){
			return;
		}

		Admin_Notices::instance()->show_notices();
	}

	/**
	 * Triggered on plugin activation
	 */
	public function plugin_activation(){
		set_transient( 'vimeotheque_activation' , true, 30 );
	}

	/**
	 * Admin init callback, redirects to plugin Settings page after plugin activation.
	 */
	public function activation_redirect(){
		$t = get_transient( 'vimeotheque_activation' );
		if( $t ){
			delete_transient( 'vimeotheque_activation' );
			wp_redirect( str_replace( '#038;' , '&', menu_page_url( 'vimeotheque_about', false ) ) );
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
	 * @throws Exception
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
}