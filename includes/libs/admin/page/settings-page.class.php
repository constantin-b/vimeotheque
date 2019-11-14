<?php

namespace Vimeotheque\Admin\Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Vimeotheque\Admin\Notice\Admin_Notices;
use Vimeotheque\Admin\Notice\Notice;
use Vimeotheque\Options\Options;
use Vimeotheque\Plugin;
use Vimeotheque\Post_Type;
use Vimeotheque\Vimeo_Api\Vimeo_Oauth;
use WP_Error;

/**
 * Class Settings_Page
 * @package Vimeotheque\Admin
 */
class Settings_Page extends Page_Init_Abstract implements Page_Interface{
	/**
	 * @var Vimeo_Oauth
	 */
	private $vimeo_oauth;

	/**
	 * @var WP_Error
	 */
	private $error;

	public function __construct( Post_Type $object ) {
		parent::__construct( $object );
		Settings_Helper::init();
	}

	/**
	 * Generates page HTML
	 */
	public function get_html(){
		$options = $this->options_obj()->get_options();
		$player_opt = $this->player_options_obj()->get_options();

		/**
		 * Filter that allows addition of extra tabs to plugin Settings page.
		 * To pass a new tab to this filter, give it an array having this format:
		 *
		 * $tabs['my-tab-id'] = array(
		 *		'title' => __('My tab title'),
		 *      'callback' => callback function that will create the tab output (ie. array( $this, 'method_name' ))
		 * )
		 *
		 * @param array $tabs
		 */
		$extra_tabs = apply_filters( 'cvm_register_plugin_settings_tab', [] );

		include VIMEOTHEQUE_PATH . 'views/plugin_settings.php';
	}

	/**
	 * Page on_load callback
	 */
	public function on_load() {
		$options = $this->options_obj()->get_options();

		$this->vimeo_oauth = new Vimeo_Oauth(
			$options['vimeo_consumer_key'],
			$options['vimeo_secret_key'],
			$options['oauth_secret'],
			// you must use this instead of menu_page_url() to avoid API error
			admin_url( 'edit.php?post_type=' . $this->cpt->get_post_type() . '&page=cvm_settings' )
		);

		if( $this->set_unauth_token() ) {
			$options = $this->options_obj()->get_options( true );
		}

		/**
		 * Action triggered on settings page load event
		 */
		do_action( 'cvm_settings_on_load', $this->options_obj() );

		if( isset( $_POST['cvm_wp_nonce'] ) ){
			if( check_admin_referer('cvm-save-plugin-settings', 'cvm_wp_nonce') ){

				do_action( 'vimeotheque\admin\before_settings_save' );

				$updated_settings = $this->update_settings();

				do_action( 'vimeotheque\admin\after_settings_save', $updated_settings );

				do_action( 'vimeotheque\admin\before_player_settings_save' );

				$this->update_player_settings();

				do_action( 'vimeotheque\admin\after_player_settings_save' );
			}
			wp_redirect( 'edit.php?post_type=' . $this->cpt->get_post_type() . '&page=cvm_settings', false );
			die();
		}

		if( isset( $_GET[ 'clear_oauth' ] ) && 'true' == $_GET[ 'clear_oauth' ] ){
			if( check_admin_referer( 'cvm-clear-oauth-token', 'cvm_nonce' ) ){
				$options['vimeo_consumer_key'] = '';
				$options['vimeo_secret_key'] = '';
				$options['oauth_token']	= '';
				$options['oauth_secret'] = '';
				$options['vimeo_access_granted'] = false;
				$this->options_obj()->update_options( $options );
			}
			wp_redirect( 'edit.php?post_type=' . $this->cpt->get_post_type() . '&page=cvm_settings', false );
			die();
		}

		$this->_enqueue_assets();
	}

	/**
	 * Utility function, updates plugin settings
	 */
	private function update_settings(){
		$defaults = $this->options_obj()->get_defaults();

		foreach( $defaults as $key => $val ){
			if( is_numeric( $val ) ){
				if( isset( $_POST[ $key ] ) ){
					$defaults[ $key ] = (int)$_POST[ $key ];
				}
				continue;
			}
			if( is_bool( $val ) ){
				$defaults[ $key ] = isset( $_POST[ $key ] );
				continue;
			}

			// trim strings
			if( isset( $_POST[ $key ] ) ){
				$defaults[ $key ] = trim( $_POST[ $key ] );
			}
		}

		// rewrite
		$plugin_settings = $this->options_obj()->get_options();
		$flush_rules = false;
		if( isset( $_POST['post_slug'] ) ){
			$post_slug = sanitize_title( $_POST['post_slug'] );
			if( !empty( $_POST['post_slug'] ) && $plugin_settings['post_slug'] !== $post_slug ){
				$defaults['post_slug'] = $post_slug;
				$flush_rules = true;
			}else{
				$defaults['post_slug'] = $plugin_settings['post_slug'];
			}
		}

		if( isset( $_POST['taxonomy_slug'] ) ){
			$tax_slug = sanitize_title( $_POST['taxonomy_slug'] );
			/**
			 * Check that taxonomy slug is not empty, is updated and is not the same as the post slug
			 */
			if( !empty( $_POST['taxonomy_slug'] ) && $plugin_settings['taxonomy_slug'] !== $tax_slug && $_POST['taxonomy_slug'] != $defaults['post_slug'] ){
				$defaults['taxonomy_slug'] = $tax_slug;
				$flush_rules = true;
			}else{
				$defaults['taxonomy_slug'] = $plugin_settings['taxonomy_slug'];
			}
		}

		if( isset( $_POST['tag_slug'] ) ){
			$tag_slug = sanitize_title( $_POST['tag_slug'] );
			if( !empty( $_POST['tag_slug'] ) && $plugin_settings['tag_slug'] !== $tag_slug && $_POST['tag_slug'] != $defaults['post_slug'] && $_POST['tag_slug'] != $defaults['taxonomy_slug'] ){
				$defaults['tag_slug'] = $tag_slug;
				$flush_rules = true;
			}else{
				$defaults['tag_slug'] = $plugin_settings['tag_slug'];
			}
		}

		// reset OAuth if user changes the keys
		if( isset( $_POST['vimeo_consumer_key'] ) && isset( $_POST['vimeo_secret_key'] ) ){
			if(
				($_POST['vimeo_consumer_key'] != $plugin_settings['vimeo_consumer_key']) ||
				($_POST['vimeo_secret_key'] != $plugin_settings['vimeo_secret_key'] )
			){
				$defaults['oauth_token'] = '';
				$defaults['oauth_secret'] = '';
			}
		}else{
			// if the consumer keys are not sent by POST, set the old values
			$defaults['vimeo_consumer_key'] = $plugin_settings['vimeo_consumer_key'];
			$defaults['vimeo_secret_key'] = $plugin_settings['vimeo_secret_key'];
			$defaults['oauth_token'] = $plugin_settings['oauth_token'];
		}

		$this->options_obj()->update_options( $defaults );

		if( $flush_rules ){
			// create rewrite ( soft )
			// register custom post
			Plugin::instance()->get_cpt()->register_post();
			flush_rewrite_rules();
		}

		return $defaults;
	}

	/**
	 * Update general player settings
	 */
	private function update_player_settings(){
		$defaults = $this->player_options_obj()->get_defaults();
		foreach( $defaults as $key => $val ){
			if( is_numeric( $val ) ){
				if( isset( $_POST[ $key ] ) ){
					$defaults[ $key ] = (int)$_POST[ $key ];
				}else{
					$defaults[ $key ] = 0;
				}
				continue;
			}
			if( is_bool( $val ) ){
				$defaults[ $key ] = isset( $_POST[ $key ] );
				continue;
			}

			if( isset( $_POST[ $key ] ) ){
				$defaults[ $key ] = $_POST[ $key ];
			}
		}

		$this->player_options_obj()->update_options( $defaults );
	}

	/**
	 * Set token for unauthenticated API requests
	 */
	private function set_unauth_token(){
		$options = $this->options_obj()->get_options();
		if( !empty( $options['vimeo_consumer_key'] ) && !empty( $options['vimeo_secret_key'] ) ){
			if( '' == $options['oauth_token'] || '' == $options['oauth_secret'] ){
				// account token
				$token = $this->vimeo_oauth->get_unauth_token();
				if( !is_wp_error( $token ) ){
					// set the token
					$options['oauth_token'] 	= $token;
				}else{
					// reset everything in case of error
					$options['vimeo_consumer_key'] = '';
					$options['vimeo_secret_key'] = '';
					$options['oauth_token'] = '';
					// set a notice for the error
					Admin_Notices::instance()
					             ->register(
					             	new Notice( $token->get_error_message() )
					             );
				}

				$this->options_obj()->update_options( $options );
				return true;
			}
		}
	}

	/**
	 * Outputs a link that allows users to clear OAuth credentials
	 *
	 * @param string $text
	 * @param string $echo
	 * @return void|string
	 */
	private function clear_oauth_credentials_link( $text = '', $class='', $echo = true ){
		if( empty( $text ) ){
			$text = __( 'Clear OAuth credentials', 'cvm_video' );
		}

		$options = $this->options_obj()->get_options();
		if( empty( $options[ 'vimeo_consumer_key' ] ) || empty( $options[ 'vimeo_secret_key' ] ) ){
			return;
		}

		$nonce = wp_create_nonce( 'cvm-clear-oauth-token' );
		$url = menu_page_url( 'cvm_settings', false ) . '&clear_oauth=true&cvm_nonce=' . $nonce . '#cvm-settings-auth-options';
		$output = sprintf( '<a href="%s" class="%s">%s</a>', $url, esc_attr( $class ), $text );

		if( $echo ){
			echo $output;
		}

		return $output;
	}

	/**
	 * Enqueue scripts and CSS for this page
	 */
	private function _enqueue_assets(){
		wp_enqueue_style(
			'cvm-plugin-settings',
			VIMEOTHEQUE_URL . 'assets/back-end/css/plugin-settings.css',
			false
		);

		wp_enqueue_script(
			'cvm-tabs',
			VIMEOTHEQUE_URL . 'assets/back-end/js/tabs.js',
			[ 'jquery', 'jquery-ui-tabs' ]
		);

		wp_enqueue_script(
			'cvm-settings',
			VIMEOTHEQUE_URL . 'assets/back-end/js/plugin-settings.js',
			[ 'jquery' ]
		);

		wp_enqueue_script(
			'cvm-video-edit',
			VIMEOTHEQUE_URL . 'assets/back-end/js/video-edit.js',
			[ 'jquery' ],
			'1.0'
		);
	}

	/**
	 * Get plugin options object
	 * @return \Vimeotheque\Options\Options
	 */
	private function options_obj(){
		return \Vimeotheque\Plugin::instance()->get_options_obj();
	}

	/**
	 * Get player options object
	 * @return \Vimeotheque\Options\Options
	 */
	private function player_options_obj(){
		return \Vimeotheque\Plugin::instance()->get_player_options();
	}
}