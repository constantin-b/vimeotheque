<?php
/**
 * This file contains classes and functions that are deprecated
 * and can be removed in future updates.
 * They are still maintained for backwards compatibility. We
 * recommend updating your code to use the new classes and functions
 * as soon as possible.
 */

/**
 * Class CVM_Options_Factory
 * @deprecated - Use Vimeotheque\Options\Options_Factory instead
 */
final class CVM_Options_Factory extends \Vimeotheque\Options\Options_Factory{}

/**
 * Class CVM_Vimeo
 * @deprecated - Use Vimeotheque\Vimeo_Api\Vimeo_Api_Query instead
 */
final class CVM_Vimeo extends \Vimeotheque\Vimeo_Api\Vimeo_Oauth{

	const ENDPOINT = parent::API_ENDPOINT;

	/**
	 * CVM_Vimeo constructor.
	 */
	public function __construct() {
		$options  = \Vimeotheque\Plugin::instance()->get_options();

		$token = $options['oauth_token'];
		if( !empty( $options['oauth_secret'] ) ){
			$token = $options['oauth_secret'];
		}

		parent::__construct(
			$options['vimeo_consumer_key'],
			$options['vimeo_secret_key'],
			$token,
			// you must use this instead of menu_page_url() to avoid API error
			admin_url( 'edit.php?post_type=' . Vimeotheque\Plugin::instance()->get_cpt()->get_post_type() . '&page=cvm_settings' )
		);
	}
}

/**
 * Class CVM_Post_Type
 * @deprecated
 */
final class CVM_Post_Type extends \Vimeotheque\Post\Post_Type{

	public function __construct() {}

	/**
	 * @return array
	 * @deprecated
	 */
	public function get_roles(){
		return Vimeotheque\Plugin::instance()->get_roles();
	}

	/**
	 * @return array|mixed
	 * @deprecated
	 */
	public function get_capability(){
		return Vimeotheque\Plugin::instance()->get_capability();
	}

	/**
	 * @param $post
	 *
	 * @return mixed|void
	 * @deprecated
	 */
	public function get_video_data( $post ){
		return \Vimeotheque\Helper::get_video_post( $post )->get_video_data();
	}

	/**
	 * @return bool
	 * @deprecated
	 */
	public function is_video(){
		return \Vimeotheque\Helper::get_video_post()->is_video();
	}

	/**
	 * @param int|WP_Post $post
	 *
	 * @return bool|mixed|void
	 * @deprecated
	 */
	public function get_video_id( $post ) {
		$data = \Vimeotheque\Helper::get_video_post( $post )->video_id;
	}

	/**
	 * @return string
	 * @deprecated
	 */
	public function get_meta_key_is_video() {
		return parent::get_post_settings()->get_meta_is_video();
	}

	/**
	 * @return string
	 * @deprecated
	 */
	public function get_meta_key_video_data() {
		return parent::get_post_settings()->get_meta_video_data();
	}

	/**
	 * @return string
	 * @deprecated
	 */
	public function get_meta_key_video_url() {
		return parent::get_post_settings()->get_meta_video_url();
	}

	/**
	 * @return string
	 * @deprecated
	 */
	public function get_meta_key_video_id() {
		return parent::get_post_settings()->get_meta_video_id();
	}

	/**
	 * @param string $situation
	 *
	 * @return bool
	 * @deprecated
	 */
	public function import_image_on( $situation = 'post_create' ) {
		return parent::get_post_settings()->image_import( $situation );
	}

	/**
	 * @param bool $status
	 *
	 * @return bool|string
	 * @deprecated
	 */
	public function post_status( $status = false ) {
		return parent::get_post_settings()->post_status( $status );
	}

	/**
	 * @return string
	 * @deprecated
	 */
	public function get_post_type() {
		return parent::get_post_type();
	}

	/**
	 * @return string
	 * @deprecated
	 */
	public function get_post_tax() {
		return parent::get_post_tax();
	}

	/**
	 * @return string
	 * @deprecated
	 */
	public function get_tag_tax() {
		return parent::get_tag_tax();
	}

	/**
	 * @param $post_id
	 * @param array $fields
	 *
	 * @return bool|int
	 * @deprecated
	 */
	public function update_video_data( $post_id, $fields = [] ) {
		\Vimeotheque\Helper::get_video_post( $post_id )->set_video_data( $fields );
	}
}

/**
 * Class CVM_Vimeo_Videos
 * @deprecated
 */
final class CVM_Vimeo_Videos{}

/**
 * @var $CVM_POST_TYPE CVM_Post_Type
 * @deprecated
 */
global $CVM_POST_TYPE;
$CVM_POST_TYPE = new CVM_Post_Type();

/**
 * @deprecated
 * @param $val
 * @param bool $echo
 *
 * @return string
 */
function cvm_check( $val, $echo = true ){
	return Vimeotheque\Admin\Helper_Admin::check( $val, $echo );
}

/**
 * @deprecated
 * @param array $args
 * @param bool $echo
 *
 * @return string
 */
function cvm_select( $args = [], $echo = true ){
	return Vimeotheque\Admin\Helper_Admin::select( $args, $echo );
}

/**
 * @deprecated
 * @param $seconds
 *
 * @return string
 */
function cvm_human_time( $seconds ){
	return \Vimeotheque\Helper::human_time( $seconds );
}

function cvm_class_method( $method, $args = [] ){
	global $CVM_POST_TYPE;

	if( !$args ){
		$result = call_user_func( [ $CVM_POST_TYPE, $method ] );
	}else{
		$result = call_user_func_array( [ $CVM_POST_TYPE, $method ], $args);
	}

	return $result;
}

/**
 * @deprecated
 * @return string
 */
function cvm_get_post_type(){
	return Vimeotheque\Plugin::instance()->get_cpt()->get_post_type();
}

/**
 * @deprecated
 * @param $post_id
 *
 * @return bool|mixed|void
 */
function cvm_get_post_video_data( $post_id ){
	$_post = \Vimeotheque\Helper::get_video_post( $post_id );
	return $_post->get_video_data();
}

/**
 * @deprecated
 * @param bool $echo
 *
 * @return string
 */
function cvm_video_embed_html( $echo = true ){
	return Vimeotheque\embed_html( $echo );
}

/**
 * @deprecated
 * @param bool $post
 *
 * @return bool
 */
function cvm_is_video( $post = false ){
	return Vimeotheque\is_video( $post );
}

/**
 * @deprecated Use cvm_is_video
 * @return bool
 */
function cvm_is_video_post(){
	return cvm_is_video();
}

/**
 * @deprecated
 * @return string
 */
function cvm_get_category(){
	return Vimeotheque\Plugin::instance()->get_cpt()->get_post_tax();
}
/**
 * @deprecated
 * @return string
 */
function cvm_get_tag(){
	return Vimeotheque\Plugin::instance()->get_cpt()->get_tag_tax();
}

/**
 * @deprecated
 * @return array
 */
function cvm_get_settings(){
	return \Vimeotheque\Plugin::instance()->get_options();
}

/**
 * @deprecated
 * @param $post
 * @param bool $echo
 *
 * @return string|void
 */
function cvm_get_video_embed_html( $post, $echo = true ){
	return Vimeotheque\get_video_embed_html( $post, $echo );
}

/**
 * @deprecated
 * @since 1.3
 *
 * Import featured image from Vimeo
 *
 * @param int $post_id
 * @param string $post_type
 * @param bool $refresh
 * @return
 */
function cvm_set_featured_image($post_id, $post_type, $refresh = false){
	return \Vimeotheque\Helper::get_video_post( $post_id )->set_featured_image( $refresh );
}

/**
 * Returns the meta key name where video details are stored on post
 *
 * @return string
 * @deprecated
 */
function cvm_get_video_data_meta_name(){
	return Vimeotheque\Plugin::instance()->get_cpt()->get_post_settings()->get_meta_video_data();
}

/**
 * General method to access public methods of post type class
 *
 * @param string $method - method name
 * @param array $args
 *
 * @return string - result returned by class method
 */
function cvm_get_method( $method, $args = [] ){
	$obj = \Vimeotheque\Plugin::instance()->get_cpt();

	if( !$args ){
		$result = call_user_func( [ $obj, $method ] );
	}else{
		$result = call_user_func_array( [ $obj, $method ], $args);
	}
	return $result;
}

/**
 * Deprecated hook
 *
 * @param \Vimeotheque\Options\Options $options
 */
function _deprecatead_settings_page_load_event( $options ){
	/**
	 * Action triggered on settings page load event
	 * @deprecated
	 */
	do_action( 'cvm_settings_on_load', $options );
}

add_action( 'vimeotheque\admin\page\settings_load', '_deprecatead_settings_page_load_event', 10, 1 );

/**
 * Deprecated filter
 *
 * @param $tabs
 *
 * @return mixed|void
 */
function _deprecated_settings_tabs( $tabs ){
	return apply_filters( 'cvm_register_plugin_settings_tab', $tabs );
}
add_filter( 'vimeotheque\admin\page\settings_tabs', '_deprecated_settings_tabs', 10, 1 );