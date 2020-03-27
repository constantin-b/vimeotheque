<?php
namespace Vimeotheque\Blocks;
use Vimeotheque\Helper;
use Vimeotheque\Plugin;
use function Vimeotheque\get_video_embed_html;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Video
 * @package Vimeotheque\Blocks
 */
class Video_Position extends Block_Abstract implements Block_Interface {
	/**
	 * Video constructor.
	 *
	 * @param Plugin $plugin
	 */
	public function __construct( Plugin $plugin ) {
		parent::__construct( $plugin );

		parent::register_script( 'vimeotheque-video-position-block', 'video_position' );
		parent::register_style( 'vimeotheque-video-block', 'video', true );
		parent::register_style( 'vimeotheque-front-video-block', 'video' );

		parent::register_block_type( 'vimeotheque/video-position', [
			'editor_script' => parent::get_script_handle(),
			'editor_style' => parent::get_editor_style_handle(),
			'style' => parent::get_style_handle(),
			'render_callback' => function(){
				/**
				 * Remove default action that embeds the video in front-end
				 * @see Front_End::embed_video()
				 */
				remove_action(
					'the_content',
					[ Plugin::$instance->get_front_end(), 'embed_video' ],
					Plugin::instance()->get_front_end()->get_embed_filter_priority()
				);

				global $post;
				return get_video_embed_html( $post, false );
			}
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
				'sanitize_callback' => function( $value ){
					if( is_array( $value ) ){
						return $value;
					}

					$options = json_decode( $value, true );
					foreach( $options as $key => $value ){
						if( is_bool( $value ) ){
							$options[ $key ] = (int) $value;
						}
					}

					return $options;
 				},
				'type' => 'string',
				'default' => false,
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
				'default' => false,
				'auth_callback' => function() {
					return current_user_can( 'edit_posts' );
				}
			]
		);

		add_action( 'admin_enqueue_scripts', [ $this, 'init' ] );
		add_action( 'the_post', [ $this, 'force_video_block' ], -99999, 2 );
	}

	/**
	 *
	 */
	public function init(){
		global $post;
		$_post = Helper::get_video_post( $post );
		if( !$_post->is_video() ){
			unregister_block_type( parent::get_wp_block_type()->name );
			wp_deregister_script( parent::get_script_handle() );
			wp_deregister_style( parent::get_editor_style_handle() );
			wp_deregister_style( parent::get_style_handle() );
		}
	}

	/**
	 * @param \WP_Post $post
	 * @param \WP_Query $query
	 */
	public function force_video_block( \WP_Post $post, \WP_Query $query ){
		if( !is_admin() ){
			return;
		}

		$_post = Helper::get_video_post( $post );
		if( $_post->is_video() && !has_block( parent::get_wp_block_type()->name, $post ) ) {
			$settings = $_post->get_embed_options();

			if( 'below-content' == $settings[ 'video_position' ] ){
				$post->post_content .= "\n" . '<!-- wp:' . parent::get_wp_block_type()->name . ' /-->';
			}else{
				$post->post_content = '<!-- wp:' . parent::get_wp_block_type()->name . ' /-->' . "\n" . $post->post_content ;
			}
		}
	}
}