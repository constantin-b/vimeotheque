<?php
/**
 * @author CodeFlavors
 */

namespace Vimeotheque_Series;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class Helper
 *
 * @package Vimeotheque_Series
 */
class Helper {
	/**
	 * @return mixed
	 */
	public static function get_path(){
		return VIMEOTHEQUE_PATH;
	}

	/**
	 * @return mixed
	 */
	public static function get_url(){
		return VIMEOTHEQUE_URL;
	}

    /**
     * Get the plugin version.
     *
     * @return string
     */
    public static function get_version(): string {
        return VIMEOTHEQUE_VERSION;
    }

    /**
     * @param string $name
     * @param array  $depths
     */
    public static function enqueue_style( string $name, $depths = [] ){
        $url = self::get_url() . 'assets/series/css/' . $name . '.css';
        $path = self::get_path() . '/assets/series/css/' . $name . '.css';

        if( !file_exists( $path ) ){
            trigger_error(
                sprintf(
                    'Could not locate asset %s.',
                    esc_url( $url )
                ),
                E_USER_ERROR
            );
        }

        wp_enqueue_style(
            'vimeotheque-series-' . $name,
            $url,
            $depths,
            self::get_version()
        );
    }

    /**
     * @param string  $name
     * @param array   $depths
     * @param boolean $in_footer
     *
     * @return string
     */
    public static function enqueue_script( string $name, array $depths = [], bool $in_footer = false ): string {
        $url = self::get_url() . 'assets/series/js/' . $name . '.js';
        $path = self::get_path() . 'assets/series/js/' . $name . '.js';

        if( !file_exists( $path ) ){
            trigger_error(
                sprintf(
                    'Could not locate asset %s.',
                    esc_url( $url )
                ),
                E_USER_ERROR
            );
        }

        $handle = 'vimeotheque-series-' . $name;
        wp_enqueue_script(
            $handle,
            $url,
            $depths,
            self::get_version(),
            $in_footer
        );

        return $handle;
    }
}