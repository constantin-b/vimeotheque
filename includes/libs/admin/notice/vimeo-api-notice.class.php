<?php
/**
 * @author CodeFlavors
 * @project Vimeotheque 2.0 Lite
 */

namespace Vimeotheque\Admin\Notice;

use Vimeotheque\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Vimeo_Api_Notice extends Notice_Abstract implements Notice_Interface {

	/**
	 * @inheritDoc
	 */
	public function get_notice() {
		$options = Plugin::instance()->get_options_obj();

		if(
			!empty( $options->get_option('vimeo_consumer_key') ) &&
			!empty( $options->get_option('vimeo_secret_key') )
		){
			return;
		}

		$message = [ sprintf(
           __('In order to be able to import videos using Vimeotheque, you must register on %s.', 'cvm_video'),
			sprintf(
				'<a href="https://developer.vimeo.com/apps/new">%s</a>',
				__( 'Vimeo App page', 'cvm_video' )
			)
       ) ];
		$message[] = __( 'Please note that you must have a valid Vimeo account and also you must be logged into Vimeo before being able to register your app.', 'cvm_video' );
		$message[] = sprintf(
			__('After you registered your app visit %s and enter your Vimeo consumer and secret keys.', 'cvm_video'),
			sprintf(
				'<a href="%s">%s</a>',
				menu_page_url('cvm_settings', false) . '#cvm-settings-auth-options',
				__( 'Settings page', 'cvm_video' )
			)
		);

		printf(
			'<div class="notice notice-error"><p>%s</p></div>',
			implode( '<br />', $message )
		);
	}
}