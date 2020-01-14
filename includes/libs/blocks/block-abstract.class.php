<?php


namespace Vimeotheque\Blocks;

use Vimeotheque\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Block_Abstract
 * @package Vimeotheque\Blocks
 */
class Block_Abstract {

	/**
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * @var array
	 */
	private $scripts = [];

	/**
	 * @var array
	 */
	private $styles = [];

	/**
	 * @var \WP_Block_Type
	 */
	private $wp_block_type;

	/**
	 * Block_Abstract constructor.
	 *
	 * @param Plugin $plugin
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_backend_assets' ] );
		add_action( 'enqueue_block_assets', [ $this, 'enqueue_frontend_assets' ] );
	}

	public function enqueue_backend_assets(){
		if( isset( $this->scripts['backend'] ) ){
			foreach ( $this->scripts['backend'] as $script ){
				wp_register_script(
					$script['handle'],
					VIMEOTHEQUE_URL . 'assets/front-end/js/blocks/' . $script['block'] . '/block.build.js',
					['wp-blocks', 'wp-element', 'wp-editor', 'wp-i18n', 'wp-components']
				);
			}
		}

		if( isset( $this->styles['backend'] ) ){
			foreach ( $this->styles['backend'] as $style ){
				wp_enqueue_style(
					$style['handle'],
					VIMEOTHEQUE_URL . 'assets/front-end/js/blocks/' . $style['block'] . '/editor.css'
				);
			}
		}
	}

	public function enqueue_frontend_assets(){
		if( isset( $this->scripts['frontend'] ) ){
			foreach ( $this->scripts['frontend'] as $script ){
				wp_register_script(
					$script['handle'],
					VIMEOTHEQUE_URL . 'assets/front-end/js/blocks/' . $script['block'] . '/block.build.js',
					['wp-blocks', 'wp-element']
				);
			}
		}

		if( isset( $this->styles['frontend'] ) ){
			foreach ( $this->styles['frontend'] as $style ){
				wp_enqueue_style(
					$style['handle'],
					VIMEOTHEQUE_URL . 'assets/front-end/js/blocks/' . $style['block'] . '/style.css'
				);
			}
		}
	}

	/**
	 * @param $handle
	 * @param $block
	 * @param string $type
	 *
	 * @return mixed
	 */
	protected function register_script( $handle, $block, $type = 'backend' ){
		if( $this->validate_type( $type ) ) {
			$this->scripts[ $type ][] = [
				'block'  => $block,
				'handle' => $handle
			];
		}
		/*
		wp_register_script(
			$handle,
			VIMEOTHEQUE_URL . 'assets/front-end/js/blocks/' . $block . '/block.build.js',
			['wp-blocks', 'wp-element']
		);
		*/
		return $handle;
	}

	/**
	 * @param $handle
	 * @param $block
	 * @param string $type
	 *
	 * @return mixed
	 */
	protected function register_style( $handle, $block, $type = 'backend' ){
		if( $this->validate_type( $type ) ) {
			$this->styles[ $type ][] = [
				'block' => $block,
				'handle' => $handle
			];
		}

		return $handle;
	}

	protected function remove_assets(){
		$this->scripts = [];
		$this->styles = [];
	}

	/**
	 * @param $type
	 *
	 * @return bool
	 */
	private function validate_type( $type ){
		if( !in_array( $type, ['frontend', 'backend'] ) ){
			trigger_error( 'Script/Style type not valid, please review. Type can be "frontend" or "backend".', E_USER_NOTICE );
			return false;
		}
		return true;
	}

	protected function register_block_type( \WP_Block_Type $block ){
		$this->wp_block_type = $block;
	}

	/**
	 * @return \WP_Block_Type
	 */
	public function get_wp_block_type(){
		return $this->wp_block_type;
	}

	/**
	 * @return Plugin
	 */
	public function get_plugin(){
		return $this->plugin;
	}
}