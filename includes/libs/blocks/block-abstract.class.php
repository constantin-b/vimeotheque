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
	private $styles = [];

	/**
	 * @var \WP_Block_Type
	 */
	private $wp_block_type;

	/**
	 * Stores the handle for the block main script file
	 *
	 * @var string
	 */
	private $block_script_handle;

	/**
	 * Block_Abstract constructor.
	 *
	 * @param Plugin $plugin
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Registers block script
	 *
	 * @param $handle
	 * @param $block
	 * @param string $type
	 *
	 * @return mixed
	 */
	protected function register_script( $handle, $block ){
		wp_register_script(
			$handle,
			VIMEOTHEQUE_URL . 'assets/front-end/js/blocks/' . $block . '/block.build.js',
			['wp-blocks', 'wp-element', 'wp-editor', 'wp-i18n', 'wp-components']
		);

		$this->block_script_handle = $handle;

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
		wp_register_style(
			$handle,
			VIMEOTHEQUE_URL . 'assets/front-end/js/blocks/' . $block . '/editor.css'
		);

		return $handle;
	}

	/**
	 * @param \WP_Block_Type $block
	 */
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

	/**
	 * Returns block main script handle
	 *
	 * @return string
	 */
	public function get_script_handle(){
		return $this->block_script_handle;
	}
}