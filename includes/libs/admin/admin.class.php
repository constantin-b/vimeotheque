<?php

namespace Vimeotheque\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Exception;
use Vimeotheque\Admin\Menu\Menu_Pages;
use Vimeotheque\Admin\Notice\Admin_Notices;
use Vimeotheque\Admin\Notice\Plugin_Notice;
use Vimeotheque\Admin\Notice\Review_Notice;
use Vimeotheque\Admin\Notice\User_Notice\Message;
use Vimeotheque\Admin\Notice\User_Notice\User;
use Vimeotheque\Admin\Notice\Vimeo_Api_Notice;
use Vimeotheque\Admin\Page\About_Page;
use Vimeotheque\Admin\Page\Automatic_Import_Page;
use Vimeotheque\Admin\Page\Go_Pro_Page;
use Vimeotheque\Admin\Page\List_Videos_Page;
use Vimeotheque\Admin\Page\Post_Edit_Page;
use Vimeotheque\Admin\Page\Settings_Page;
use Vimeotheque\Admin\Page\Status_Page;
use Vimeotheque\Admin\Page\Video_Import_Page;
use Vimeotheque\Helper;
use Vimeotheque\Post\Post_Type;

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
	private $post_type;

	/**
	 * Ajax Class reference
	 * 
	 * @var Ajax_Actions
	 */
	private $ajax;

	/**
	 * @var Menu_Pages
	 */
	private $admin_menu;

	/**
	 *
	 * @param \Vimeotheque\Post\Post_Type $post_type
	 */
	public function __construct( Post_Type $post_type ){
		// store object reference
		$this->post_type = $post_type;

		add_action( 'wp_loaded', [ $this, 'init' ], -20 );

		// add admin capabilities
		add_action( 'init', [
			$this,
			'add_capabilities'
		], -999 );

		// add columns to posts table
		add_filter( 'manage_edit-' . $this->post_type->get_post_type() . '_columns', [
				$this, 
				'extra_columns'
		] );

		add_action( 'manage_' . $this->post_type->get_post_type() . '_posts_custom_column', [
				$this, 
				'output_extra_columns'
		], 10, 2 );

		add_action( 'admin_init', [
			$this,
			'register_notices'
		] );

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

		add_filter( 'plugin_action_links_' . plugin_basename( VIMEOTHEQUE_FILE ), [
			$this,
			'action_links'
		] );
	}

	/**
	 * Initialize
	 */
	public function init(){
		// start AJAX actions
		$this->ajax = new Ajax_Actions( $this->post_type );
		// start post edit single video page

		new Post_Edit_Page( $this );

		$this->register_pages();
	}

	/**
	 * Add subpage for custom post type admin menu
	 */
	public function register_pages(){

		$this->admin_menu = new Menu_Pages(
			new Settings_Page(
				$this,
				__( 'Settings', 'cvm_video' ),
				__( 'Settings', 'cvm_video' ),
				'cvm_settings',
				'edit.php?post_type=' . $this->post_type->get_post_type(),
				'manage_options'
			)
		);

		$this->admin_menu->register_page(
			new Status_Page(
				$this,
				__('Status', 'cvm_video'),
				__('Status', 'cvm_video'),
				'vimeotheque_status',
				'edit.php?post_type=' . $this->post_type->get_post_type(),
				'manage_options'
			)
		);

		$this->admin_menu->register_page(
			new Video_Import_Page(
				$this,
				__( 'Import videos', 'cvm_video' ),
				__( 'Import videos', 'cvm_video' ),
				'cvm_import',
				'edit.php?post_type=' . $this->post_type->get_post_type(),
				$this->get_capability('manual_import')
			)
		);

		$this->admin_menu->register_page(
			new About_Page(
				$this,
				__( 'About', 'cvm_video' ),
				__( 'About', 'cvm_video' ),
				'vimeotheque_about',
				false,
				'activate_plugins'
			)
		);

		$this->admin_menu->register_page(
			new List_Videos_Page(
				$this,
				__( 'Videos', 'cvm_video' ),
				__( 'Videos', 'cvm_video' ),
				'cvm_videos',
				false,
				'edit_posts'
			)
		);

		$this->admin_menu->register_page(
			new Automatic_Import_Page(
				$this,
				__( 'Automatic Vimeo video import', 'cvm_video' ),
				__( 'Automatic import', 'cvm_video' ),
				'vimeotheque_auto_import',
				'edit.php?post_type=' . $this->post_type->get_post_type(),
				'edit_posts'
			)
		);

		$this->admin_menu->register_page(
			new Go_Pro_Page(
				$this,
				__( 'Go PRO!', 'cvm_video' ),
				__( 'Go PRO!', 'cvm_video' ),
				'vimeotheque_go_pro',
				'edit.php?post_type=' . $this->post_type->get_post_type(),
				'edit_posts'
			)
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
					if( !$r->has_cap( $cap['capability'] ) ) {
						$r->add_cap( $cap['capability'] );
					}
				}
			}
		}
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
	 * Register plugin notices
	 */
	public function register_notices(){
		Admin_Notices::instance()->register( new Vimeo_Api_Notice() );

		$message = new Message(
			__( "It's great to see that you've been using <strong>Vimeotheque</strong> plugin for a while now. Hopefully you're happy with it! <br>If so, would you consider leaving a positive review? It really helps to support the plugin and helps others to discover it too!", 'cvm_video' ),
			'https://wordpress.org/plugins/codeflavors-vimeo-video-post-lite/'
		);

		Admin_Notices::instance()->register(
			new Review_Notice(
				'vmtq_plugin_review_callout',
				$message,
				new User('vmtq_ignore_review_nag')
			)
		);
	}

	/**
	 * Set admin notices
	 */
	public function admin_notices(){
		if( !isset( $_GET['post_type'] ) || $this->post_type->get_post_type() != $_GET['post_type'] ){
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
			wp_redirect(
				str_replace(
					'#038;' ,
					'&',
					menu_page_url(
						'vimeotheque_about',
						false
					)
				)
			);

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
	 * Plugin action links
	 *
	 * @param $links
	 *
	 * @return mixed
	 */
	public function action_links( $links ){
		$anchor = '<a href="%s" target="%s">%s</a>';

		$links[] = sprintf(
			$anchor,
			Helper_Admin::docs_link( 'getting-started/vimeotheque-pro-installation/' ),
			'_blank',
			__( 'First time installation', 'cvm_video' )
		);

		$links[] = sprintf(
			$anchor,
			Helper_Admin::docs_link( 'getting-started' ),
			'_blank',
			__( 'Documentation', 'cvm_video' )
		);

		$links[] = sprintf(
			$anchor,
			$this->get_admin_menu()->get_page('cvm_settings')->get_menu_page(),
			'_self',
			$this->get_admin_menu()->get_page( 'cvm_settings' )->get_menu_title()
		);

		return $links;
	}

	/**
	 * @return \Vimeotheque\Post\Post_Type
	 */
	public function get_post_type(){
		return $this->post_type;
	}

	/**
	 * @return Ajax_Actions
	 */
	public function get_ajax(){
		return $this->ajax;
	}

	/**
	 * @return Menu_Pages
	 */
	public function get_admin_menu() {
		return $this->admin_menu;
	}
}