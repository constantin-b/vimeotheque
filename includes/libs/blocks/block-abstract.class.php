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
	 * Stylesheet editor handle
	 *
	 * @var string
	 */
	private $editor_style_handle;
	/**
	 * Front-end styling handle
	 *
	 * @var string
	 */
	private $style_handle;

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
	 * @param bool $editor_style
	 *
	 * @return mixed
	 */
	protected function register_style( $handle, $block, $editor_style = false ){
		$file = $editor_style ? 'editor.css' : 'style.css';
		wp_register_style(
			$handle,
			VIMEOTHEQUE_URL . 'assets/front-end/js/blocks/' . $block . '/' . $file
		);

		if( $editor_style ){
			$this->editor_style_handle = $handle;
		}else{
			$this->style_handle = $handle;
		}

		return $handle;
	}

	/**
	 * @param $name
	 * @param array $args
	 *
	 * @return mixed|\WP_Block_Type
	 */
	protected function register_block_type(  $name, $args = array() ){
		$this->wp_block_type = register_block_type( $name, $args );
		return $this->wp_block_type;
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

	/**
	 * @return string
	 */
	public function get_editor_style_handle() {
		return $this->editor_style_handle;
	}

	/**
	 * @return string
	 */
	public function get_style_handle() {
		return $this->style_handle;
	}
}