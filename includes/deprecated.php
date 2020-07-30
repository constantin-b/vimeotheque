<?php
/**
 * This file contains classes and functions that are deprecated
 * and can be removed in future updates.
 * They are still maintained for backwards compatibility. We
 * recommend updating your code to use the new classes and functions
 * as soon as possible.
 */

use Vimeotheque\Helper;
use Vimeotheque\Player\Player;

/**
 * Class CVM_Options_Factory
 * @deprecated - Use Vimeotheque\Options\Options_Factory instead
 */
final class CVM_Options_Factory extends \Vimeotheque\Options\Options_Factory{}

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
	 * @throws Exception
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
 * @var CVM_Post_Type $CVM_POST_TYPE
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
	global $post;
	if( !$post ){
		return;
	}

	$e = cvm_get_video_embed_html( $post, $echo );
	return $e;
}

/**
 * @deprecated
 * @param bool $post
 *
 * @return bool
 */
function cvm_is_video( $post = false ){
	return Helper::get_video_post( $post )->is_video();
}

/**
 * @deprecated Use cvm_is_video
 * @return bool
 */
function cvm_is_video_post(){
	return Helper::get_video_post()->is_video();
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
	$_post = Helper::get_video_post( $post );

	if( !$_post->video_id ){
		return;
	}

	$player = new Player( $_post );
	return $player->get_output( $echo );
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
	do_action_deprecated(
		'cvm_settings_on_load',
		[$options],
		'2.0',
		'vimeotheque\admin\page\settings_load'
	);
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
	return apply_filters_deprecated(
		'cvm_register_plugin_settings_tab',
		[$tabs],
		'2.0',
		'vimeotheque\admin\page\settings_tabs'
	);
}
add_filter( 'vimeotheque\admin\page\settings_tabs', '_deprecated_settings_tabs', 10, 1 );


function _deprecated_embed_filter_priority( $priority ){
	return apply_filters_deprecated(
		'cvm_plugin_embed_content_filter_priority',
		[$priority],
		'2.0',
		'vimeotheque\embed_filter_priority'
	);
}
add_filter( 'vimeotheque\embed_filter_priority', '_deprecated_embed_filter_priority', 10, 1 );

function _deprecated_allow_video_embed($allow){
	return apply_filters_deprecated(
		'cvm_automatic_video_embed',
		[$allow],
		'2.0',
		'vimeotheque\post_content_embed'
	);
}
add_filter( 'vimeotheque\post_content_embed', '_deprecated_allow_video_embed', 10, 1 );

function _deprecated_vimeo_access_token( $token ){
	return apply_filters_deprecated(
		'cvm_vimeo_access_token',
		[$token],
		'2.0',
		'vimeotheque\vimeo_api\access_token'
	);
}
add_filter( 'vimeotheque\vimeo_api\access_token', '_deprecated_vimeo_access_token', 10, 1 );

function _deprecated_debug( ...$args ){
	do_action_deprecated(
		'cvm_debug_message',
		$args,
		'2.0',
		'vimeotheque\debug'
	);
}
add_action( 'vimeotheque\debug', '_deprecated_debug', 10, 3 );

function _deprecated_image_timeout( $timeout ){
	return apply_filters_deprecated(
		'cvm_image_request_timeout',
		[$timeout],
		'2.0',
		'vimeotheque\image_request_timeout'
	);
}
add_filter( 'vimeotheque\image_request_timeout', '_deprecated_image_timeout', 10, 1 );

function _deprecated_image_file_raw( ...$args ){
	do_action_deprecated(
		'cvm_import_video_image_raw_file',
		$args,
		'2.0',
		'vimeotheque\image_file_raw'
	);
}
add_action( 'vimeotheque\image_file_raw', '_deprecated_image_file_raw', 10, 3 );

function _deprecated_image_imported( ...$args ){
	do_action_deprecated(
		'cvm_import_video_thumbnail',
		$args,
		'2.0',
		'vimeotheque\image_imported'
	);
}
add_action( 'vimeotheque\image_imported', '_deprecated_image_imported', 10, 3 );

function _deprecated_post_reimport_tax( ...$args ){
	do_action_deprecated(
		'cvm_existing_video_posts_taxonomies',
		$args,
		'2.0',
		'vimeotheque\import_duplicate_taxonomies'
	);
}
add_action( 'vimeotheque\import_duplicate_taxonomies', '_deprecated_post_reimport_tax', 10, 5 );

function _deprecated_post_format( $format ){
	return apply_filters_deprecated(
		'cvm_import_post_format' ,
		[$format],
		'2.0',
		'vimeotheque\import_post_format'
	);
}
add_filter( 'vimeotheque\import_post_format', '_deprecated_post_format', 10, 1 );

function _deprecated_allow_import( ...$args ){
	return apply_filters_deprecated(
		'cvm_allow_video_import',
		$args,
		'2.0',
		'vimeotheque\allow_import'
	);
}
add_filter( 'vimeotheque\allow_import', '_deprecated_allow_import', 10, 4 );

function _deprecated_import_before( ...$args ){
	do_action_deprecated(
		'cvm_before_post_insert',
		$args,
		'2.0',
		'vimeotheque\import_before'
	);
}
add_action( 'vimeotheque\import_before', '_deprecated_import_before', 10, 2 );

function _deprecated_post_title_filter( ...$args ){
	return apply_filters_deprecated(
		'cvm_video_post_title',
		$args,
		'2.0',
		'vimeotheque\import_post_title'
	);
}
add_filter( 'vimeotheque\import_post_title', '_deprecated_post_title_filter', 10, 3 );

function _deprecated_post_content_filter( ...$args ){
	return apply_filters_deprecated(
		'cvm_video_post_content',
		$args,
		'2.0',
		'vimeotheque\import_post_content'
	);
}
add_filter( 'vimeotheque\import_post_content', '_deprecated_post_content_filter', 10, 3 );

function _deprecated_post_excerpt_filter( ...$args ){
	return apply_filters_deprecated(
		'cvm_video_post_excerpt',
		$args,
		'2.0',
		'vimeotheque\import_post_excerpt'
	);
}
add_filter( 'vimeotheque\import_post_excerpt', '_deprecated_post_excerpt_filter', 10, 3 );

function _deprecated_post_status_filter( ...$args ){
	return apply_filters_deprecated(
		'cvm_video_post_status',
		$args,
		'2.0',
		'vimeotheque\import_post_status'
	);
}
add_filter( 'vimeotheque\import_post_status', '_deprecated_post_status_filter', 10, 3 );

function _deprecated_post_date_filter( ...$args ){
	return apply_filters_deprecated(
		'cvm_video_post_date',
		$args,
		'2.0',
		'vimeotheque\import_post_date'
	);
}
add_filter( 'vimeotheque\import_post_date', '_deprecated_post_date_filter', 10, 3 );

function _deprecated_post_success( ...$args ){
	do_action_deprecated(
		'cvm_post_insert',
		$args,
		'2.0',
		'vimeotheque\import_success'
	);
}
add_action( 'vimeotheque\import_success', '_deprecated_post_success', 10, 4 );

function _deprecated_api_oauth_settings(){
	do_action_deprecated(
		'cvm_additional_oauth_settings_display',
		[],
		'2.0',
		'vimeotheque\admin\api_oauth_settings_extra'
	);
}
add_action( 'vimeotheque\admin\api_oauth_settings_extra', '_deprecated_api_oauth_settings', 10 );

function _deprecated_request_timeout( $timeout ){
	return apply_filters_deprecated(
		'cvm_feed_request_timeout' ,
		[$timeout],
		'2.0',
		'vimeotheque\vimeo_api\request_timeout'
	);
}
add_filter( 'vimeotheque\vimeo_api\request_timeout', '_deprecated_request_timeout', 10, 1 );

function _deprecated_api_json_fields( $fields ){
	return apply_filters_deprecated(
		'cvm_vimeo_api_request_extra_json_fields',
		[$fields],
		'2.0',
		'vimeotheque\vimeo_api\add_json_fields'
	);
}
add_filter( 'vimeotheque\vimeo_api\add_json_fields', '_deprecated_api_json_fields', 10, 1 );

function _deprecated_api_query_params( $params ){
	return apply_filters_deprecated(
		'cvm_vimeo_api_query_params',
		[$params],
		'2.0',
		'vimeotheque\vimeo_api\query_params'
	);
}
add_filter( 'vimeotheque\vimeo_api\query_params', '_deprecated_api_query_params', 10, 1 );

function _deprecated_shortcode_playlist_max_posts( $max ){
	return apply_filters_deprecated(
		'cvm_shortcode_new_videos_max_posts',
		[$max],
		'2.0',
		'vimeotheque\shortcode\playlist\newest_max_posts'
	);
}
add_filter( 'vimeotheque\shortcode\playlist\newest_max_posts', '_deprecated_shortcode_playlist_max_posts', 10, 1 );

function _deprecated_player_css( ...$args ){
	return apply_filters_deprecated(
		'cvm_video_embed_css_class',
		$args,
		'2.0',
		'vimeotheque\player\css_class'
	);
}
add_filter( 'vimeotheque\player\css_class', '_deprecated_player_css', 10, 2 );

function _deprecated_player_width( ...$args ){
	return apply_filters_deprecated(
		'cvm_embed_width',
		$args,
		'2.0',
		'vimeotheque\player\embed_width'
	);
}
add_filter( 'vimeotheque\player\embed_width', '_deprecated_player_width', 10, 3 );

function _deprecated_player_options( ...$args ){
	return apply_filters_deprecated(
		'cvm_video_embed_settings',
		$args,
		'2.0',
		'vimeotheque\player\embed_options'
	);
}
add_filter( 'vimeotheque\player\embed_options', '_deprecated_player_options', 10, 3 );

function _deprecated_import_button_text( $text ){
	return apply_filters_deprecated(
		'cvm-playlist-meta-submit-text',
		[$text],
		'2.0',
		'vimeotheque\admin\import_meta_panel\button_text'
	);
}
add_filter( 'vimeotheque\admin\import_meta_panel\button_text', '_deprecated_import_button_text', 10, 1 );

function _deprecated_video_list_scripts(){
	do_action_deprecated(
		'cvm_video_list_modal_print_scripts',
		[],
		'2.0',
		'vimeotheque\admin\video_list_modal_print_scripts'
	);
}
add_action( 'vimeotheque\admin\video_list_modal_print_scripts', '_deprecated_video_list_scripts', 10 );

// playlist theme functions

function cvm_output_thumbnail( $size = 'small', $before = '', $after = '', $echo = true ){
	return \Vimeotheque\Themes\Helper::get_thumbnail( $size, $before, $after, $echo );
}

function cvm_image_preload_output( $size = 'small', $class="cvm-preload", $echo = true ) {
	return \Vimeotheque\Themes\Helper::image_preloader( $size, $class, $echo );
}

function cvm_output_title( $include_duration = true,  $before = '', $after = '', $echo = true  ){
	return \Vimeotheque\Themes\Helper::get_title( $include_duration, $before, $after, $echo );
}

function cvm_output_video_data( $before = " ", $after="", $echo = true ){
	return \Vimeotheque\Themes\Helper::get_video_data_attributes( $before, $after, $echo );
}

function cvm_video_post_permalink( $echo  = true ){
	return \Vimeotheque\Themes\Helper::get_post_permalink( $echo );
}

function cvm_output_width( $before = ' style="', $after='"', $echo = true ){
	return \Vimeotheque\Themes\Helper::get_width( $before, $after, $echo );
}

