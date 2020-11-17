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
class Video_Position extends Block_Abstract implements Block_Interface {
	/**
	 * Video constructor.
	 *
	 * @param Plugin $plugin
	 */
	public function __construct( Plugin $plugin ) {
		parent::__construct( $plugin );

		parent::register_script( 'vimeotheque-video-position-block', 'video_position' );

		parent::register_block_type( 'vimeotheque/video-position', [
			'editor_script' => parent::get_script_handle(),
			'render_callback' => function(){
				// check if filters or settings prevent auto embedding
				if( !Helper::is_autoembed_allowed() ){
					return;
				}
				/**
				 * Add current post to skipped videos from auto embedding
				 * @see Front_End::embed_video()
				 */
				parent::get_plugin()->get_front_end()->prevent_post_autoembed();

				// check if embedding in archives is allowed
				$options = Plugin::instance()->get_options();
				if( !is_singular() && !$options['archives'] ){
					return;
				}

				global $post;
				$video_post = Helper::get_video_post( $post );
				if( $video_post->is_video() ) {
					return Helper::embed_video( $post, [], false );
				}
			}
		] );

		register_post_meta(
			'',
			'__cvm_playback_settings',
			[
				'single' => true,
				'show_in_rest' => [
					'prepare_callback' => function( $value ){
						global $post;

						// @since WP 5.5
						$default = Helper::get_metadata_default(
							'post',
							$post->ID,
							self::get_plugin()->get_cpt()->get_post_settings()->get_meta_embed_settings(),
							true
						);

						$_default = parent::get_plugin()->get_embed_options_obj()->get_options();

						if( $default === $value ){
							$value = $_default;
						}

						// allow new values to be added to existing meta
						foreach ( $_default as $k => $v ){
							if( !isset( $value[ $k ] ) ){
								$value[ $k ] = $v;
							}
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
				'default' => '{}',
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
				'default' => '',
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
		if( !$_post->is_video() || !Helper::is_autoembed_allowed() ){
			$this->deactivate();
			wp_deregister_script( parent::get_script_handle() );
			//wp_deregister_style( parent::get_editor_style_handle() );
			//wp_deregister_style( parent::get_style_handle() );
		}
	}

	/**
	 * @param \WP_Post $post
	 * @param \WP_Query $query
	 */
	public function force_video_block( \WP_Post $post, \WP_Query $query ){
		if( !is_admin() || !Helper::is_autoembed_allowed() ){
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