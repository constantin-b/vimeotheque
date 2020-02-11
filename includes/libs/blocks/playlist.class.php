<?php
namespace Vimeotheque\Blocks;
use Vimeotheque\Helper;
use Vimeotheque\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Video
 * @package Vimeotheque\Blocks
 */
class Playlist extends Block_Abstract {
	/**
	 * Video constructor.
	 *
	 * @param Plugin $plugin
	 */
	public function __construct( Plugin $plugin ) {
		parent::__construct( $plugin );

		$handle = parent::register_script(
			'vimeotheque-playlist-block',
			'playlist'
		);

		$block_type = register_block_type(
			'vimeotheque/video-playlist',
			[
				'editor_script' => $handle,
				'editor_style' => [
					'vimeotheque-playlist-block',
					'bootstrap-grid2'
				]
			]
		);
		parent::register_block_type( $block_type );

		$r = wp_localize_script(
			$handle,
			'vmtq',
			[
				'noImageUrl' => VIMEOTHEQUE_URL . 'assets/back-end/images/no-image.jpg'
			]
		);

		parent::register_style( 'vimeotheque-playlist-block', 'playlist' );
		wp_register_style(
			'bootstrap-grid2',
			VIMEOTHEQUE_URL . 'assets/back-end/css/vendor/bootstrap.min.css',
			['vimeotheque-playlist-block']
		);
		//parent::register_style( 'vimeotheque-front-video-block', 'video', 'frontend' );

		//add_action( 'admin_enqueue_scripts', [ $this, 'init' ] );

		$this->set_rest_meta_queries();
	}

	/**
	 *
	 */
	public function init(){
		global $post;
		$_post = Helper::get_video_post( $post );
		if( $_post->is_video() ){
			unregister_block_type( parent::get_wp_block_type() );
			//wp_deregister_script( 'vimeotheque-video-block' );
			wp_deregister_style( 'vimeotheque-playlist-block' );
			//wp_deregister_style( 'vimeotheque-front-video-block' );
		}
	}

	/**
	 * By default, REST api doesn't allow queries from React to be made for meta keys.
	 * Register meta query queries for post types.
	 * @see \WP_REST_Posts_Controller::get_items() line 269
	 */
	private function set_rest_meta_queries(){
		$post_types = [ 'post', Plugin::instance()->get_cpt()->get_post_type() ];

		foreach( $post_types as $post_type ){
			add_filter( 'rest_' . $post_type . '_query', [ $this, 'meta_queries' ], 10, 2 );
		}
	}

	/**
	 * @see Playlist::set_rest_meta_queries()
	 *
	 * @param array $args
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed
	 */
	public function meta_queries( $args, $request ){
		if( $request->get_param( 'vimeothequeMetaKey' ) ){
			$args['meta_query'] = [
				[
					'key'     => Plugin::instance()->get_cpt()->get_post_settings()->get_meta_video_data(),
					'compare' => 'EXISTS'
				]
			];
		}

		return $args;
	}
}