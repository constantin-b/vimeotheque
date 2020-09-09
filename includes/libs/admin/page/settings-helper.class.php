<?php

namespace Vimeotheque\Admin\Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Settings_Helper
 * @package Vimeotheque\Admin\Page
 */
class Settings_Helper {

	public static function init(){
		$allow = apply_filters( 'vimeotheque\admin\page\settings_helper\show_pro_options', true );
		if( !$allow ){
			return;
		}

		add_action( 'vimeotheque\admin\general_settings_section', [ 'Vimeotheque\Admin\Page\Settings_Helper', 'pro_general_settings' ] );
		add_action( 'vimeotheque\admin\post_type_section', [ 'Vimeotheque\Admin\Page\Settings_Helper', 'pro_post_type_settings' ] );
		add_action( 'vimeotheque\admin\content_options_section', [ 'Vimeotheque\Admin\Page\Settings_Helper', 'pro_content_settings' ] );
		add_action( 'vimeotheque\admin\image_options_section', [ 'Vimeotheque\Admin\Page\Settings_Helper', 'pro_image_settings' ] );
		add_action( 'vimeotheque\admin\import_options_section', [ 'Vimeotheque\Admin\Page\Settings_Helper', 'pro_import_settings' ] );
		add_action( 'vimeotheque\admin\embed_options_section', [ 'Vimeotheque\Admin\Page\Settings_Helper', 'pro_embed_settings' ] );
		add_action( 'vimeotheque\admin\api_oauth_section', [ 'Vimeotheque\Admin\Page\Settings_Helper', 'oauth_settings' ] );

	}

	public static function oauth_settings(){
		_e( 'With Vimeotheque PRO you can also query and import your private videos.', 'codeflavors-vimeo-video-post-lite' );
	}

	/**
	 * PRO options under general settings
	 */
	public static function pro_general_settings(){
		Settings_Helper::row_checkbox(
			__('Import as regular post type (aka post)', 'codeflavors-vimeo-video-post-lite'),
			sprintf(
				__( 'Videos will be imported as %s instead of custom post type video. Posts having attached videos will display having the same player options as video post types.', 'codeflavors-vimeo-video-post-lite'),
				sprintf( '<strong>%s</strong>', __( 'regular post type', 'codeflavors-vimeo-video-post-lite' ) )
			)
		);

		Settings_Helper::row_checkbox(
			__('Include microdata on video pages', 'codeflavors-vimeo-video-post-lite'),
			sprintf(
				__( 'When checked, all page displaying videos will also include microdata for SEO purposes ( more on %s ).', 'codeflavors-vimeo-video-post-lite'),
				'<a href="http://schema.org" target="_blank">http://schema.org</a>'
			)
		);

		Settings_Helper::row_checkbox(
			__('Check video status after import', 'codeflavors-vimeo-video-post-lite'),
			__('When checked, will verify on Vimeo every 24H if the video still exists or is embeddable and if not, it will automatically set the post status to pending. This action is triggered by your website visitors.', 'codeflavors-vimeo-video-post-lite')
		);

		self::row_anchor();
	}

	/**
	 * Pro options under post type options
	 */
	public static function pro_post_type_settings(){
		Settings_Helper::row_checkbox(
			__( 'Include videos post type on homepage', 'codeflavors-vimeo-video-post-lite' ),
			__( 'When checked, if your homepage displays a list of regular posts, videos will be included among them.', 'codeflavors-vimeo-video-post-lite')
		);

		Settings_Helper::row_checkbox(
			__('Include videos post type in main RSS feed', 'codeflavors-vimeo-video-post-lite'),
			__( 'When checked, custom post type will be included in your main RSS feed.', 'codeflavors-vimeo-video-post-lite')
		);

		self::row_anchor();
	}

	public static function pro_content_settings(){
		Settings_Helper::row_checkbox(
			__( 'Prevent auto embed on video content', 'codeflavors-vimeo-video-post-lite' ),
			__( 'If content retrieved from Vimeo has links to other videos, checking this option will prevent auto embedding of videos in your post content.', 'codeflavors-vimeo-video-post-lite')
		);

		Settings_Helper::row_checkbox(
			__( "Make URL's in video content clickable", 'codeflavors-vimeo-video-post-lite' ),
			__( 'Automatically make all valid URL\'s from content retrieved from Vimeo clickable.', 'codeflavors-vimeo-video-post-lite')
		);

		self::row_anchor();
	}

	public static function pro_image_settings(){
		Settings_Helper::row_checkbox(
			__( 'Import featured image on request', 'codeflavors-vimeo-video-post-lite' ),
			__( 'Vimeo video thumbnail will be imported only when featured images needs to be displayed (ie. a post created by the plugin is displayed).', 'codeflavors-vimeo-video-post-lite')
		);

		self::row_anchor();
	}

	public static function pro_import_settings(){
		self::row_select(
			__( 'Videos not public will be', 'codeflavors-vimeo-video-post-lite' ),
			__( 'skipped from importing', 'codeflavors-vimeo-video-post-lite' ),
			__( 'If a video is not set as public by its owner (password protected videos for example), it will obey this rule.', 'codeflavors-vimeo-video-post-lite' )
		);

		self::row_select(
			__( 'Automatic import', 'codeflavors-vimeo-video-post-lite' ),
			__( '15 minutes', 'codeflavors-vimeo-video-post-lite' ),
			__( 'How often should Vimeo be queried for playlist updates.', 'codeflavors-vimeo-video-post-lite' ),
			__( 'Import 20 videos every', 'codeflavors-vimeo-video-post-lite' )
		);

		self::row_checkbox(
			__( 'Stop automatic feed import on error', 'codeflavors-vimeo-video-post-lite' ),
			__( 'When enabled, if Vimeo API returns an error on automatic import, the respective feed will be removed from the queue if the allowed number of fails is exceeded.', 'codeflavors-vimeo-video-post-lite' )
		);

		self::row_select(
			__( 'Number of fails until automatic import is stopped', 'codeflavors-vimeo-video-post-lite' ),
			3,
			__( 'For how many times an automatic update can fail before being removed from the import queue.', 'codeflavors-vimeo-video-post-lite' )
		);

		self::row_checkbox(
			__( 'Enable conditional automatic imports', 'codeflavors-vimeo-video-post-lite' ),
			__( 'When enabled, automatic imports will run only when a custom URL is opened on your website.', 'codeflavors-vimeo-video-post-lite' )
		);

		self::row_checkbox(
			__( 'Legacy automatic import', 'codeflavors-vimeo-video-post-lite' ),
			__( 'Trigger automatic video imports on page load (will increase page load time when doing automatic imports)', 'codeflavors-vimeo-video-post-lite' )
		);

		self::row_anchor();
	}

	public static function pro_embed_settings(){
		self::row_checkbox(
			__('Override individual posts options', 'codeflavors-vimeo-video-post-lite'),
			__('When checked, individual post options for embedding videos will not be taken into account. Instead, the option set in this page will be used to embed videos on your website.', 'codeflavors-vimeo-video-post-lite')
		);

		self::row_anchor();
	}

	/**
	 * Show anchor for viewing PRO options
	 */
	public static function row_anchor(){
		printf(
			'<tr>%s</tr>',
			sprintf( '<th colspan="2">%s</th>', self::anchor_show() )
		);
	}

	/**
	 * Anchor for showing options
	 */
	static public function anchor_show(){
		return sprintf(
			'<a class="cvm-pro-options-trigger" href="#" data-visible="0" data-text_on="%1$s" data-text_off="%2$s" data-selector="%3$s">%2$s</a>',
			esc_attr__( 'Hide PRO options', 'codeflavors-vimeo-video-post-lite' ),
			esc_attr__( 'View PRO options', 'codeflavors-vimeo-video-post-lite' ),
			'.cvm-pro-option'
		);
	}

	static public function row_select( $label, $select_text = '', $description = '', $before_select = '' ){
		self::row(
			self::label_cell( $label ),
			self::field_cell(
				sprintf(
					'%s <select><option>%s</option></select>',
					$before_select,
					$select_text
				),
				$description,
				'p'
			)
		);
	}

	/**
	 * @param $label
	 * @param string $description
	 */
	static public function row_checkbox( $label, $description = '' ){
		self::row(
			self::label_cell( $label ),
			self::field_cell(
				'<input type="checkbox" />',
				$description
			)
		);
	}

	/**
	 * @param $label
	 * @param $field
	 *
	 * @return string
	 */
	static public function row( $label, $field ){
		printf(
			'<tr class="cvm-pro-option hide-if-js">%s%s</tr>',
			$label,
			$field
		);
	}

	/**
	 * @param $label
	 *
	 * @return string
	 */
	static public function label_cell( $label ){
		return sprintf(
			'<th scope="row"><label>%s:</label></th>',
			$label
		);
	}

	/**
	 * @param $field
	 * @param string $description
	 *
	 * @param string $wrap
	 *
	 * @return string
	 */
	static public function field_cell( $field, $description = '', $wrap = 'span' ){
		$desc = empty( $description ) ? '' : sprintf( '<%1$s class="description">%2$s</%1%s>', $wrap, $description );
		return sprintf(
			'<td>%s %s</td>',
			$field,
			$desc
		);
	}

}