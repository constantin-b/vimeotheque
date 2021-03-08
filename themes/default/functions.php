<?php
namespace Themes\DefaultTheme;

use Vimeotheque\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Add block editor Playlist Block attributes extension
 */
function block_editor(){
	wp_enqueue_script(
		'vimeotheque-theme-default-attributes',
		plugin_dir_url( __FILE__ ) . 'assets/js/block/app.build.js',
		[ Plugin::instance()->get_block('playlist')->get_script_handle() ],
		'1.0.0',
		true
	);
}

add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\block_editor' );

/**
 * Register additional image sizes
 *
 * @param $sizes
 * @param $video
 *
 * @return mixed
 */
function thumbnail_sizes( $sizes, $video ){
	$sizes['default_small_unique_size'] = 1;
	$sizes['default_small_original_size'] = 3;

	return $sizes;
}
add_filter( 'vimeotheque\themes\thumbnail_image_sizes', __NAMESPACE__ . '\thumbnail_sizes', 10, 2 );