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
		add_action( 'vimeotheque\admin\import_options_section', [ 'Vimeotheque\Admin\Page\Settings_Helper', 'pro_import_settings' ] );
		add_action( 'vimeotheque\admin\embed_options_section', [ 'Vimeotheque\Admin\Page\Settings_Helper', 'pro_embed_settings' ] );
		add_action( 'vimeotheque\admin\api_oauth_section', [ 'Vimeotheque\Admin\Page\Settings_Helper', 'oauth_settings' ] );

	}

	public static function oauth_settings(){
		_e( 'With Vimeotheque PRO you can also query and import your private videos.', 'cvm_video' );
	}

	/**
	 * PRO options under general settings
	 */
	public static function pro_general_settings(){
		Settings_Helper::row_checkbox(
			__('Import as regular post type (aka post)', 'cvm_video'),
			sprintf(
				__( 'Videos will be imported as %s instead of custom post type video. Posts having attached videos will display having the same player options as video post types.', 'cvm_video'),
				sprintf( '<strong>%s</strong>', __( 'regular post type', 'cvm_video' ) )
			)
		);

		Settings_Helper::row_checkbox(
			__('Include microdata on video pages', 'cvm_video'),
			sprintf(
				__( 'When checked, all page displaying videos will also include microdata for SEO purposes ( more on %s ).', 'cvm_video'),
				'<a href="http://schema.org" target="_blank">http://schema.org</a>'
			)
		);

		Settings_Helper::row_checkbox(
			__('Check video status after import', 'cvm_video'),
			__('When checked, will verify on Vimeo every 24H if the video still exists or is embeddable and if not, it will automatically set the post status to pending. This action is triggered by your website visitors.', 'cvm_video')
		);

		self::row_anchor();
	}

	/**
	 * Pro options under post type options
	 */
	public static function pro_post_type_settings(){
		Settings_Helper::row_checkbox(
			__( 'Include videos post type on homepage', 'cvm_video' ),
			__( 'When checked, if your homepage displays a list of regular posts, videos will be included among them.', 'cvm_video')
		);

		Settings_Helper::row_checkbox(
			__('Include videos post type in main RSS feed', 'cvm_video'),
			__( 'When checked, custom post type will be included in your main RSS feed.', 'cvm_video')
		);

		self::row_anchor();
	}

	public static function pro_content_settings(){
		Settings_Helper::row_checkbox(
			__( 'Prevent auto embed on video content', 'cvm_video' ),
			__( 'If content retrieved from Vimeo has links to other videos, checking this option will prevent auto embedding of videos in your post content.', 'cvm_video')
		);

		Settings_Helper::row_checkbox(
			__( "Make URL's in video content clickable", 'cvm_video' ),
			__( 'Automatically make all valid URL\'s from content retrieved from Vimeo clickable.', 'cvm_video')
		);

		self::row_anchor();
	}

	public static function pro_import_settings(){
		self::row_select(
			__( 'Videos not public will be', 'cvm_video' ),
			__( 'skipped from importing', 'cvm_video' ),
			__( 'If a video is not set as public by its owner (password protected videos for example), it will obey this rule.', 'cvm_video' )
		);

		self::row_select(
			__( 'Automatic import', 'cvm_video' ),
			__( '15 minutes', 'cvm_video' ),
			__( 'How often should Vimeo be queried for playlist updates.', 'cvm_video' ),
			__( 'Import 20 videos every', 'cvm_video' )
		);

		self::row_checkbox(
			__( 'Legacy automatic import', 'cvm_video' ),
			__( 'Trigger automatic video imports on page load (will increase page load time when doing automatic imports)', 'cvm_video' )
		);

		self::row_anchor();
	}

	public static function pro_embed_settings(){
		self::row_checkbox(
			__('Override individual posts options', 'cvm_video'),
			__('When checked, individual post options for embedding videos will not be taken into account. Instead, the option set in this page will be used to embed videos on your website.', 'cvm_video')
		);

		self::row_checkbox(
			__('Allow videos to override player size', 'cvm_video'),
			sprintf(
				'%s<br />%s',
				__( 'When checked (recommended), player will have the exact aspect ratio as retrieved from Vimeo. Player size option will be ignored.', 'cvm_video' ),
				__( 'Applies only to videos imported starting with plugin version 1.3.', 'cvm_video' )
			)
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
			esc_attr__( 'Hide PRO options', 'cvm_video' ),
			esc_attr__( 'View PRO options', 'cvm_video' ),
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