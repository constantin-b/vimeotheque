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
		plugin_dir_url( __FILE__ ) . 'assets/js/block/script.build.js',
		[ Plugin::instance()->get_block('playlist')->get_script_handle() ],
		'1.0.0',
		true
	);
}

add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\block_editor' );