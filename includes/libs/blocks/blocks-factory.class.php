<?php


namespace Vimeotheque\Blocks;

use Vimeotheque\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Blocks_Factory
 * @package Vimeotheque\Blocks
 */
class Blocks_Factory {

	/**
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Blocks_Factory constructor.
	 *
	 * @param Plugin $plugin
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		add_action( 'init', [ $this, 'register_blocks' ], -99999999999 );

	}

	/**
	 *
	 */
	public function register_blocks(){
		new Video( $this->plugin );
	}
}