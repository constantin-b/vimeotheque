<?php
/**
 * @author CodeFlavors
 * @project Vimeotheque 2.0 Lite
 */

namespace Vimeotheque\Admin\Page;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Status_Page extends Page_Abstract implements Page_Interface {

	/**
	 * @inheritDoc
	 */
	public function get_html() {
	    $wp_info = $this->get_wordpress_info();
	    $server_info = $this->get_server_info();
	    $theme_info = $this->get_theme_info();
?>
		<div class="wrap">
			<h1><?php _e( 'System status', 'codeflavors-vimeo-video-post-lite' );?></h1>
			<h2>WordPress</h2>
            <table class="form-table">
				<tbody>
				<tr>
					<th scope="row">WP URL</th>
					<td><?php echo get_bloginfo('wpurl');?></td>
				</tr>
                <tr>
					<th scope="row">Site address</th>
					<td><?php echo get_bloginfo('url');?></td>
				</tr>
                <tr>
					<th scope="row">Vimeotheque version</th>
					<td><?php echo VIMEOTHEQUE_VERSION;?></td>
				</tr>
                <tr>
					<th scope="row">WordPress version</th>
					<td><?php echo $wp_info['version'];?></td>
				</tr>
                <tr>
					<th scope="row">WordPress memory limit</th>
					<td><?php echo $wp_info['memory_limit'];?></td>
				</tr>
                <tr>
                    <th scope="row">WordPress debug mode</th>
                    <td><?php echo $wp_info['debug_mode'];?></td>
                </tr>
                <tr>
                    <th scope="row">Language</th>
                    <td><?php echo $wp_info['locale'];?></td>
                </tr>
                <tr>
					<th scope="row">WordPress multisite</th>
					<td><?php echo $wp_info['multisite'];?></td>
				</tr>
				</tbody>
			</table>

            <h2>Server</h2>
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row">Server info</th>
                    <td><?php echo $server_info['software'];?></td>
                </tr>
                <tr>
                    <th scope="row">PHP version</th>
                    <td><?php echo $server_info['php_version'];?></td>
                </tr>
                <tr>
                    <th scope="row">PHP post max size</th>
                    <td><?php echo $server_info['php_post_max_size'];?></td>
                </tr>
                <tr>
                    <th scope="row">PHP time limit</th>
                    <td><?php echo $server_info['php_time_limit'];?></td>
                </tr>
                <tr>
                    <th scope="row">PHP max input vars</th>
                    <td><?php echo $server_info['php_max_input_vars'];?></td>
                </tr>
                <tr>
                    <th scope="row">PHP default timezone</th>
                    <td><?php echo $server_info['php_default_timezone'];?></td>
                </tr>
                <tr>
                    <th scope="row">PHP cURL</th>
                    <td><?php echo $server_info['php_curl'];?></td>
                </tr>
                <tr>
                    <th scope="row">cURL version</th>
                    <td><?php echo $server_info['curl_version'];?></td>
                </tr>
                </tbody>
            </table>
            <h2>Theme</h2>
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row">Theme</th>
                    <td><?php echo $theme_info['name'];?></td>
                </tr>
                <tr>
                    <th scope="row">Version</th>
                    <td><?php echo $theme_info['version'];?></td>
                </tr>
                <tr>
                    <th scope="row">Child theme</th>
                    <td><?php echo $theme_info['child_theme'];?></td>
                </tr>

                </tbody>
            </table>
		</div>
<?php
	}

	/**
	 * @inheritDoc
	 */
	public function on_load() {
		// TODO: Implement on_load() method.
	}

	/**
	 * Get server related info.
	 *
	 * @return array
	 */
	private function get_server_info() {
		$server_data = [];

		if ( ! empty( $_SERVER['SERVER_SOFTWARE'] ) ) {
			$server_data['software'] = $_SERVER['SERVER_SOFTWARE'];
		}else{
		    $server_data['software'] = 'unknown';
		}

		if ( function_exists( 'phpversion' ) ) {
			$server_data['php_version'] = phpversion();
		}else{
		    $server_data['php_version'] = 'unknown';
		}

		if ( function_exists( 'ini_get' ) ) {
			$server_data['php_post_max_size'] = ini_get( 'post_max_size' );
			$server_data['php_time_limit']  = ini_get( 'max_execution_time' );
			$server_data['php_max_input_vars'] = ini_get( 'max_input_vars' );
		}else{
			$server_data['php_post_max_size'] = 'unknown';
			$server_data['php_time_limit'] = 'unknown';
			$server_data['php_max_input_vars'] = 'unknown';
		}

		$server_data['php_default_timezone'] = date_default_timezone_get();
		$server_data['php_curl'] = function_exists( 'curl_init' ) ? 'Yes' : 'No';

		// Figure out cURL version, if installed.
		$curl_version = '';
		if ( function_exists( 'curl_version' ) ) {
			$curl_version = curl_version();
			$curl_version = $curl_version['version'] . ', ' . $curl_version['ssl_version'];
		} elseif ( extension_loaded( 'curl' ) ) {
			$curl_version = __( 'cURL installed but unable to retrieve version.', 'codeflavors-vimeo-video-post-lite' );
		}

		$server_data['curl_version'] = $curl_version;

		return $server_data;
	}

	/**
	 * Get the current theme info, theme name and version.
	 *
	 * @return array
	 */
	private function get_theme_info() {
		$theme_data        = wp_get_theme();
		$theme_child_theme = is_child_theme();

		return [
			'name'        => $theme_data->Name, // @phpcs:ignore
			'version'     => $theme_data->Version, // @phpcs:ignore
			'child_theme' => $theme_child_theme ? 'Yes' : 'No',
		];
	}

	/**
	 * Get WordPress related data.
	 *
	 * @return array
	 */
	private function get_wordpress_info() {
		$wp_data = [];

		$wp_data['memory_limit'] = WP_MEMORY_LIMIT;
		$wp_data['debug_mode']   = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? 'Yes' : 'No';
		$wp_data['locale']       = get_locale();
		$wp_data['version']      = get_bloginfo( 'version' );
		$wp_data['multisite']    = is_multisite() ? 'Yes' : 'No';

		return $wp_data;
	}
}