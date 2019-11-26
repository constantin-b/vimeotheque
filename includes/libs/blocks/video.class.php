<?php


namespace Vimeotheque\Blocks;

use Vimeotheque\Helper;
use Vimeotheque\Plugin;
use function Vimeotheque\cvm_enqueue_player;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Video
 * @package Vimeotheque\Blocks
 */
class Video extends Block_Abstract {
	/**
	 * @var false|\WP_Block_Type
	 */
	private $block_type;

	/**
	 * Video constructor.
	 *
	 * @param Plugin $plugin
	 */
	public function __construct( Plugin $plugin ) {
		parent::__construct( $plugin );

		cvm_enqueue_player();
		$handle = parent::register_script( 'vimeotheque-video-block', 'video' );

		$this->block_type = register_block_type( 'vimeotheque/video', [
			'attributes' => [
				'cvm_css' => [
					'type' => 'string',
					'default' => 'cvm-video'
				]
			],
			'editor_script' => $handle,
		] );

		register_post_meta(
			'',
			'__cvm_playback_settings',
			[
				'single' => true,
				'show_in_rest' => [
					'prepare_callback' => function( $value ){
						if( !$value ){
							$value = parent::get_plugin()->get_player_options()->get_options();
						}

						return json_encode( $value );
					}
				],
				'type' => 'string',
				'auth_callback' => function() {
					return current_user_can( 'edit_posts' );
				}
			]
		);

		register_post_meta(
			'',
			'__cvm_video_id',
			[
				'single' => true,
				'show_in_rest' => true,
				'type' => 'string',
				'auth_callback' => function() {
					return current_user_can( 'edit_posts' );
				}
			]
		);

		parent::register_style( 'vimeotheque-video-block', 'video' );
		parent::register_style( 'vimeotheque-front-video-block', 'video', 'frontend' );

		add_action( 'admin_enqueue_scripts', [ $this, 'init' ] );
	}

	public function init(){

		global $post;
		$_post = Helper::get_video_post( $post );
		if( !$_post->is_video() ){
			unregister_block_type( $this->block_type );
			wp_deregister_script( 'vimeotheque-video-block' );
			wp_deregister_style( 'vimeotheque-video-block' );
			wp_deregister_style( 'vimeotheque-front-video-block' );
		}
	}

}