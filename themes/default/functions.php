<?php

namespace Themes\DefaultTheme;

function block_editor(){
	wp_enqueue_script(
		'vimeotheque-theme-default-attributes',
		plugin_dir_url( __FILE__ ) . 'assets/js/block/script.build.js',
		['vimeotheque-playlist-block'],
		'1.0.0',
		true
	);
}

add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\block_editor' );