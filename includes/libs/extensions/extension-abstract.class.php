<?php
/**
 * @author CodeFlavors
 * @project codeflavors-vimeo-video-post-lite
 */

namespace Vimeotheque\Extensions;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class Extension_Abstract
 * @package Vimeotheque\Extensions
 * @since 2.1
 */
class Extension_Abstract {
	/**
	 * @var string
	 */
	private $dirname;

	/**
	 * @var string
	 */
	private $filename;

	/**
	 * The download URL
	 *
	 * @var string
	 */
	private $download_link;
	/**
	 * The plugin name
	 *
	 * @var string
	 */
	private $name;
	/**
	 * @var string
	 */
	private $description;

	/**
	 * Set the plugin slug
	 *
	 * @param string $slug
	 */
	protected function set_slug( $slug ){
		$path_parts = pathinfo( $slug );
		$this->dirname = $path_parts['dirname'];
		$this->filename = $path_parts['basename'];
	}

	/**
	 * @param string $name
	 */
	protected function set_name( $name ){
		$this->name = $name;
	}

	/**
	 * @param string $description
	 */
	protected function set_description( $description ) {
		$this->description = $description;
	}

	/**
	 * Set the URL from where the plugin archive can be downloaded
	 *
	 * @param string $url
	 */
	protected function set_download_link( $url ){
		$this->download_link = $url;
	}

	/**
	 * Returns the plugin slug
	 *
	 * @return string
	 */
	public function get_slug() {
		return $this->dirname . '/' . $this->filename;
	}

	/**
	 * Returns the download link for the plugin
	 *
	 * @return false
	 */
	public function get_download_link() {
		return $this->download_link;
	}

	/**
	 * Method must be overriden for extensions that are not published on WP repository
	 * and must return false.
	 *
	 * @return bool
	 */
	public function is_wp_hosted(){
		return true;
	}


	public function activation_url( $redirect_to = false ){
		$action = 'activate';
		$nonce_action = $action . '-plugin_' . $this->get_slug();

		return wp_nonce_url(
			add_query_arg(
				[
					'action' => $action,
					'plugin' => $this->get_slug()
				],
				admin_url( 'plugins.php' )
			),
			$nonce_action
		);
	}

	public function deactivation_url( $redirect_to = false ){
		$action = 'deactivate';
		$nonce_action = $action . '-plugin_' . $this->get_slug();

		return wp_nonce_url(
			add_query_arg(
				[
					'action' => $action,
					'plugin' => $this->get_slug()
				],
				admin_url( 'plugins.php' )
			),
			$nonce_action
		);
	}

	public function install_url(){
		$action = 'install-plugin';
		$nonce_action = $action . '_' . $this->dirname;

		return wp_nonce_url(
			add_query_arg(
				[
					'action' => $action,
					'plugin' => $this->dirname
				],
				admin_url( 'update.php' )
			),
			$nonce_action
		);
	}

	public function upgrade_url(){
		$action = 'upgrade-plugin';
		$nonce_action = $action . '_' . $this->get_slug();

		return wp_nonce_url(
			add_query_arg(
				[
					'action' => $action,
					'plugin' => $this->get_slug()
				],
				admin_url( 'update.php' )
			),
			$nonce_action
		);
	}

	/**
	 * Method must be overriden for PRO extensions
	 *
	 * @return false
	 */
	public function is_pro_addon(){
		return false;
	}

	/**
	 * Returns the plugin name
	 *
	 * @return string
	 */
	public function get_name(){
		return $this->name;
	}

	/**
	 * Returns the plugin data, if installed. If plugin is not installed
	 * returns false
	 *
	 * @uses get_plugin_data()
	 * @uses trailingslashit()
	 *
	 * @return array|false
	 */
	protected function get_plugin_data(){
		$plugin_file = trailingslashit( WP_PLUGIN_DIR ) . $this->get_slug();
		if( file_exists( $plugin_file ) ){
			return get_plugin_data( $plugin_file );
		}

		return false;
	}

	/**
	 * Returns the plugin data if the plugin is installed or false if not installed
	 *
	 * @return bool
	 */
	public function is_installed(){
		return $this->get_plugin_data() ? true : false;
	}

	/**
	 * Get plugin description
	 *
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Returns whether the plugin is active or not.
	 *
	 * @uses is_plugin_active()
	 *
	 * @return bool
	 */
	public function is_activated(){
		return is_plugin_active( $this->get_slug() );
	}
}