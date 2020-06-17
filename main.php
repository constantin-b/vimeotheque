<?php
/*
 * Plugin Name: Vimeotheque
 * Plugin URI: https://vimeotheque.com
 * Description: Formerly known as "Vimeo Videos", Vimeotheque imports public Vimeo videos as WordPress posts. It is a perfect fit for membership, portfolio, online courses or any type of video collection.
 * Author: CodeFlavors
 * Version: 2.0
 * Author URI: https://codeflavors.com
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'VIMEOTHEQUE_FILE', __FILE__ );
define( 'VIMEOTHEQUE_PATH', plugin_dir_path( __FILE__ ) );
define( 'VIMEOTHEQUE_URL', plugin_dir_url( __FILE__ ) );
define( 'VIMEOTHEQUE_VERSION', '2.0' );
/**
 * Minimum WP version.
 * Verifies against the current running WP version.
 * WP 5.2 required minimum version because it contains React 16.8+ which implements hooks
 */
define( 'VIMEOTHEQUE_WP_COMPAT', '5.2' );

if ( ! version_compare( PHP_VERSION, '5.6', '>=' ) ) {
	add_action( 'admin_notices', 'vimeotheque_fail_php_version' );
} elseif ( ! version_compare( get_bloginfo( 'version' ), VIMEOTHEQUE_WP_COMPAT, '>=' ) ) {
	add_action( 'admin_notices', 'vimeotheque_fail_wp_version' );
}else{
    require_once VIMEOTHEQUE_PATH . 'includes/libs/plugin.class.php';
}

/**
 * Vimeotheque admin notice for minimum PHP version.
 * @return void
 */
function vimeotheque_fail_php_version() {
	/* translators: %s: PHP version */
	$message = sprintf( esc_html__( 'Vimeotheque requires PHP version %s+, plugin is currently NOT RUNNING.', 'codeflavors-vimeo-video-post-lite' ), '5.4' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

/**
 * Vimeotheque admin notice for minimum WordPress version.
 * @return void
 */
function vimeotheque_fail_wp_version() {
	/* translators: %s: WordPress version */
	$message = sprintf( esc_html__( 'Vimeotheque requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT RUNNING.', 'codeflavors-vimeo-video-post-lite' ), VMTQ_PRO_WP_COMPAT );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}