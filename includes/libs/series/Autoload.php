<?php
namespace Vimeotheque_Series;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Vimeotheque PRO autoloader.
 *
 * @ignore
 * @since  2.0
 */
class Autoload {

	/**
	 * Run autoloader.
	 *
	 * Register a function as `__autoload()` implementation.
	 *
	 * @since  1.6.0
	 * @access public
	 * @static
	 */
	public static function run() {
		spl_autoload_register( [ __CLASS__, 'autoload' ] );
	}

	/**
	 * Load class.
	 *
	 * For a given class name, require the class file.
	 *
	 * @since  1.6.0
	 * @access private
	 * @static
	 *
	 * @param string $relative_class_name Class name.
	 */
	private static function load_class( $relative_class_name ) {

        $file = str_replace( '\\', DIRECTORY_SEPARATOR, $relative_class_name ) . '.php';
        $filename = VIMEOTHEQUE_PATH . 'includes/libs/series/' . $file;

		if ( is_readable( $filename ) ) {
			require_once $filename;
		}
	}

	/**
	 * Autoload.
	 *
	 * For a given class, check if it exists and load it.
	 *
	 * @since  1.6.0
	 * @access private
	 * @static
	 *
	 * @param string $class Class name.
	 */
	private static function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ . '\\' ) ) {
			return;
		}

		$relative_class_name = preg_replace( '/^' . __NAMESPACE__ . '\\\/', '', $class );
		$final_class_name = __NAMESPACE__ . '\\' . $relative_class_name;

		if ( ! class_exists( $final_class_name ) ) {
			self::load_class( $relative_class_name );
		}
	}
}
