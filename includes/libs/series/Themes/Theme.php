<?php
/**
 * @author  CodeFlavors
 * @project vimeotheque-series
 */

namespace Vimeotheque_Series\Themes;

use Vimeotheque_Series\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

final class Theme {

	/**
	 * The absolute path to the main file in the theme.
	 *
	 * @var string
	 */
	private $file = '';

	/**
	 * The theme name.
	 *
	 * @var string
	 */
	public $name = '';

	/**
	 * The theme Javascript file.
	 *
	 * @var string
	 */
	private $js_file = null;

	/**
	 * The theme stylesheet file.
	 *
	 * @var string
	 */
	private $css_file = null;

	/**
	 * URL path to the theme folder.
	 *
	 * @var string
	 */
	private $url = '';

	/**
	 * Absolute path to theme folder.
	 *
	 * @var string
	 */
	private $path = '';

	/**
	 * The folder name.
	 *
	 * @var string
	 */
	public $folder_name = '';

	/**
	 * Stores a md5 hash of the theme root, to function as the cache key.
	 *
	 * @var string
	 */
	private $cache_hash;

	/**
	 * Cache expiration.
	 *
	 * @var int
	 */
	private $cache_expiration = 1800;

	/**
	 * Initialize the class.
	 *
	 * @param string $file Absolute path to theme display file.
	 * @param string $name The theme name.
	 */
	public function __construct( string $file, string $name = '', string $js_file = '', string $css_file = '' ) {

		if ( ! is_file( $file ) ) {
			trigger_error(
				'Theme file not found.',
				E_USER_WARNING
			);
		}

		$this->file     = $file;
		$this->name     = $name;
		$this->js_file  = $js_file;
		$this->css_file = $css_file;

		$this->url         = plugin_dir_url( $file );
		$this->path        = plugin_dir_path( $file );
		$this->folder_name = basename( dirname( $file ) );

		$this->cache_hash = md5( $this->folder_name . '/' . $this->file );
	}

	/**
	 * Load the theme functions.
	 *
	 * @return void
	 */
	public function load_functions() {
		$path = $this->get_path( 'functions.php' );
		if ( file_exists( $path ) ) {
			include_once $path;
		}
	}

	/**
	 * Enqueue the theme script.
	 *
	 * @param  [] $dependency Script dependencies.
	 * @return false|string
	 */
	public function enqueue_script( $dependency = [] ) {
		return $this->enqueue_asset( $this->js_file, $dependency, 'wp_enqueue_script' );
	}

	/**
	 * Enqueue the theme styles.
	 *
	 * @param  [] $dependency Style dependencies.
	 * @return false|string
	 */
	public function enqueue_style( $dependency = [] ) {
		return $this->enqueue_asset( $this->css_file, $dependency, 'wp_enqueue_style' );
	}

	/**
	 * Enqueues a theme asset file.
	 *
	 * @param  string $file         The relative path of the file that needs to be enqueued.
	 * @param  []     $dependency   The file dependencies.
	 * @param  string $use_function The function to be used for the enqueue.
	 * @return false|string
	 */
	private function enqueue_asset( string $file, $dependency = [], $use_function = '' ) {
		$handle = false;

		if ( ! empty( $file ) && file_exists( $this->get_path( $file ) ) ) {
			$handle = 'vimeotheque-series-playlist-' . $this->get_folder_name();

			if ( function_exists( $use_function ) ) {
				call_user_func(
					$use_function,
					$handle,
					$this->get_url( $file ),
					$dependency,
					Helper::get_version()
				);
			}
		}

		return $handle;
	}

	/**
	 * Returns the main screenshot file for the theme.
	 *
	 * The main screenshot is called screenshot.png. gif and jpg extensions are also allowed.
	 *
	 * Screenshots for a theme must be in the theme directory.
	 *
	 * @param  string $uri Type of URL to return, either 'relative' or an absolute URI. Defaults to absolute URI.
	 * @return string|false Screenshot file. False if the theme does not have a screenshot.
	 */
	public function get_screenshot( string $uri = 'uri' ) {
		$screenshot = $this->cache_get( 'screenshot' );
		if ( $screenshot ) {
			if ( 'relative' === $uri ) {
				return $screenshot;
			}
			return $this->get_url( $screenshot );
		} elseif ( 0 === $screenshot ) {
			return false;
		}

		foreach ( [ 'png', 'gif', 'jpg', 'jpeg', 'webp' ] as $ext ) {
			if ( file_exists( $this->get_path( 'screenshot.' . $ext ) ) ) {
				$this->cache_add( 'screenshot', 'screenshot.' . $ext );
				if ( 'relative' === $uri ) {
					return 'screenshot.' . $ext;
				}
				return $this->get_url( 'screenshot.' . $ext );
			}
		}

		$this->cache_add( 'screenshot', 0 );
		return false;
	}

	/**
	 * Get theme name.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return $this->name;
	}

	/**
	 * Get theme folder URL.
	 *
	 * @param  string $rel_path A relative path that gets appended to the URL.
	 * @return string
	 */
	public function get_url( string $rel_path = '' ): string {
		return $this->url . $rel_path;
	}

	/**
	 * Get theme folder path.
	 *
	 * @param  string $rel_path A relative path that gets appended to the absolute path.
	 * @return string
	 */
	public function get_path( string $rel_path = '' ): string {
		return $this->path . $rel_path;
	}

	/**
	 * Get theme folder name.
	 *
	 * @return string
	 */
	public function get_folder_name(): string {
		return $this->folder_name;
	}

	/**
	 * The theme main file.
	 *
	 * @return string
	 */
	public function get_file(): string {
		return $this->file;
	}

	/**
	 * Adds theme data to cache.
	 *
	 * Cache entries keyed by the theme and the type of data.
	 *
	 * @param  string       $key  Type of data to store (theme, screenshot, headers, post_templates)
	 * @param  array|string $data Data to store
	 * @return void Return value from wp_cache_add()
	 *@since  3.4.0
	 */
	private function cache_add( string $key, $data ) {
		wp_cache_add( $key . '-' . $this->cache_hash, $data, 'vimeotheque-series-themes', $this->cache_expiration );
	}

	/**
	 * Gets theme data from cache.
	 *
	 * Cache entries are keyed by the theme and the type of data.
	 *
	 * @param  string $key Type of data to retrieve (theme, screenshot, headers, post_templates)
	 * @return mixed Retrieved data
	 */
	private function cache_get( string $key ) {
		return wp_cache_get( $key . '-' . $this->cache_hash, 'vimeotheque-series-themes' );
	}
}
