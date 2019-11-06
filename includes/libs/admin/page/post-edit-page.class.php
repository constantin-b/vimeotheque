<?php

namespace Vimeotheque\Admin\Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Vimeotheque\Admin\Helper_Admin;
use Vimeotheque\Helper;
use Vimeotheque\Post_Type;
use WP_Post;

/**
 * Class Post_Edit_Page
 * @package Vimeotheque\Admin
 */
class Post_Edit_Page extends Page_Init_Abstract{

	private $video;
	private $is_gutenberg = false;

	public function __construct( Post_Type $object ){
		parent::__construct( $object );
		
		// action on loading post-new page for custom post type. Manages single video import
		add_action( 'load-post-new.php', [
				$this, 
				'post_new_onload'
		] );
		// for empty imported posts, skip $maybe_empty verification
		add_filter( 'wp_insert_post_empty_content', [
				$this, 
				'force_empty_insert'
		], 999, 2 );
		// assets needed in various pages
		add_action( 'admin_enqueue_scripts', [
				$this, 
				'post_edit_assets'
		] );
		// create edit meta boxes
		add_action( 'admin_head', [
				$this, 
				'add_meta_boxes'
		] );
		// shortcode modal window output
		add_action( 'admin_footer', [
				$this, 
				'shortcode_modal'
		] );
		// add tinymce plugin
		add_action( 'admin_head', [
				$this, 
				'tinymce'
		] );
		// save data from meta boxes
		add_action( 'save_post', [
				$this, 
				'save_post'
		], 10, 2 );
		// post thumbnails
		add_filter( 'admin_post_thumbnail_html', [
				$this, 
				'post_thumbnail_meta_panel'
		], 10, 2 );
		// Gutenberg action
		add_action( 'enqueue_block_editor_assets', [
                $this,
                'gutenberg_editor'
        ] );
	}

	/**
	 * New post load action for videos.
	 * Will first display a form to query for the video.
	 */
	public function post_new_onload(){
		if( ! isset( $_REQUEST[ 'post_type' ] ) || $this->cpt->get_post_type() !== $_REQUEST[ 'post_type' ] ){
			return;
		}

		if( isset( $_POST[ 'wp_nonce' ] ) ){
			if( check_admin_referer( 'cvm_query_new_video', 'wp_nonce' ) ){
				
				if( empty( $_POST[ 'cvm_video_id' ] ) ){
					wp_redirect( 'post-new.php?post_type=' . $this->cpt->get_post_type() );
					die();
				}
				
				$post_type = $this->cpt->get_post_type();
				
				// search if video already exists
				$posts = get_posts( [
						'post_type' => $post_type, 
						'meta_key' => $this->cpt->get_post_settings()->get_meta_video_id(),
						'meta_value' => $_POST[ 'cvm_video_id' ], 
						'post_status' => [
                            'publish',
                            'pending',
                            'draft',
                            'future',
                            'private'
						]
				] );
				if( $posts ){
					wp_redirect( 'post.php?post=' . $posts[ 0 ]->ID . '&action=edit&message=102&video_id=' . $_POST[ 'cvm_video_id' ] );
					die();
				}
				
				$this->video = \Vimeotheque\cvm_query_video( $_POST[ 'cvm_video_id' ] );
				if( $this->video && !is_wp_error( $this->video ) ){
					$this->video[ 'description' ] = apply_filters( 'cvm_video_post_content', $this->video[ 'description' ], $this->video, [] );
					$this->video[ 'excerpt' ] = apply_filters( 'cvm_video_post_excerpt', $this->video[ 'description' ], $this->video, [] );
					$this->video[ 'title' ] = apply_filters( 'cvm_video_post_title', $this->video[ 'title' ], $this->video, [] );
					// single post import date
					$import_options = \Vimeotheque\get_settings();
					$post_date = $import_options[ 'import_date' ] ? date( 'Y-m-d H:i:s', strtotime( $this->video[ 'published' ] ) ) : current_time( 'mysql' );
					$this->video[ 'post_date' ] = apply_filters( 'cvm_video_post_date', $post_date, $this->video, [] );

					add_filter( 'default_content', [
							$this, 
							'default_content'
					], 999, 2 );
					add_filter( 'default_title', [
							$this, 
							'default_title'
					], 999, 2 );
					add_filter( 'default_excerpt', [
							$this, 
							'default_excerpt'
					], 999, 2 );
					
					// add video player for video preview on post
					\Vimeotheque\cvm_enqueue_player();
				}
			}else{
				wp_die( "Cheatin' uh?" );
			}
		}
		// if video query not started, display the form
		if( ! $this->video || is_wp_error( $this->video ) ){
			wp_enqueue_script( 'cvm-new-video-js', VIMEOTHEQUE_URL . 'assets/back-end/js/video-new.js', [
					'jquery'
			], '1.0' );
			
			$post_type_object = get_post_type_object( $this->cpt->get_post_type() );
			$title = $post_type_object->labels->add_new_item;
			
			include ABSPATH . 'wp-admin/admin-header.php';
			
			$options = \Vimeotheque\get_settings();
			if( empty( $options[ 'vimeo_consumer_key' ] ) || empty( $options[ 'vimeo_secret_key' ] ) ){
			?>
                <p>
					<?php _e( 'Please register on <a href="https://developer.vimeo.com/apps/new">Vimeo App page</a> in order to be able to import videos.', 'cvm_video' );?><br /> 
					<?php printf( __( 'A step by step tutorial on how to create an app on Vimeo can be found %shere%s.', 'cvm_video' ), '<a href="'. cvm_docs_link( 'getting-started/vimeo-oauth-new-interface/' ) .'" target="_blank">', '</a>');?>
				</p>
            <?php
			}else{
			    if( is_wp_error( $this->video ) ){
			        echo '<div class="error notice"><p>' . sprintf( __( 'Query for your video returned following error: "%s".', 'cvm_video' ), '<em>' . $this->video->get_error_message() . '</em>' ) . '</p></div>';
			        $this->video = false;
                }
				include VIMEOTHEQUE_PATH . 'views/new_video.php';
			}
			include ABSPATH . 'wp-admin/admin-footer.php';
			die();
		}
	}

	/**
	 * Set video description on new post
	 * 
	 * @param string $post_content
	 * @param object $post
	 */
	public function default_content( $post_content, $post ){
		if( ! $this->video ){
			return;
		}
		
		return $this->video[ 'description' ];
	}

	/**
	 * Set video title on new post
	 * 
	 * @param string $post_title
	 * @param object $post
	 */
	public function default_title( $post_title, $post ){
		if( ! $this->video ){
			return;
		}
		
		return $this->video[ 'title' ];
	}

	/**
	 * Set video excerpt on new post, add taxonomies and save meta
	 * 
	 * @param string $post_excerpt
	 * @param object $post
	 */
	public function default_excerpt( $post_excerpt, $post ){
		if( ! $this->video ){
			return;
		}

		$args = [
		    'video' => $this->video,
            'post_id' => $post->ID,
            'category' => false,
            'post_type' => $this->cpt->get_post_type(),
            'taxonomy' => $this->cpt->get_post_tax(),
            'tag_taxonomy' => $this->cpt->get_tag_tax(),
            'tags' => false,
            'user' => false,
            'post_format' => 'video',
            'status' => 'draft',
            'theme_import' => false,
            'options' => false
		];

		$this->cpt->get_plugin()->get_posts_importer()->import_video( $args );

		wp_redirect( add_query_arg( [
			'post' => $post->ID,
			'action' => 'edit'
		], 'post.php' ) );
	}

	/**
	 * When trying to insert an empty post, WP is running a filter.
	 * Given the fact that
	 * users are allowed to insert empty posts when importing, the filter will return
	 * false on maybe_empty to allow insertion of video.
	 *
	 * @param bool $maybe_empty
	 * @param array $postarr
	 *
	 * @return bool
	 */
	public function force_empty_insert( $maybe_empty, $postarr ){
		if( $this->cpt->get_post_type() == $postarr[ 'post_type' ] ){
			return false;
		}
	}

	/**
	 * Add scripts to custom post edit page
	 * 
	 * @param string $hook
	 */
	public function post_edit_assets( $hook ){
		if( 'post.php' !== $hook && 'post-new.php' !== $hook ){
			return;
		}

		global $post;
		if( ! $post ){
			return;
		}

		$_post = Helper::get_video_post( $post );

		// video/playlist shortcode functionality
		wp_enqueue_style(
		        'cvm-shortcode-modal',
			VIMEOTHEQUE_URL . 'assets/back-end/css/shortcode-modal.css',
                [ 'wp-jquery-ui-dialog' ],
                '1.0'
        );

		wp_enqueue_script(
		        'cvm-shortcode-modal',
			VIMEOTHEQUE_URL . 'assets/back-end/js/shortcode-modal.js',
                [ 'jquery-ui-dialog' ],
                '1.0'
        );
		
		wp_localize_script(
		    'cvm-shortcode-modal',
            'CVM_SHORTCODE_MODAL',
			[
				'playlist_title' => __( 'Videos in playlist', 'cvm_video' ),
				'no_videos' => __( 'No videos selected.<br />To create a playlist check some videos from the list on the right.', 'cvm_video' ),
				'deleteItem' => __( 'Delete from playlist', 'cvm_video' ),
				'insert_playlist' => __( 'Add shortcode into post', 'cvm_video' ),
				'deleteCategory' => __( 'Remove category', 'cvm_video' ),
				'no_categories' => __( 'Select some categories to display videos from. Categories can be added by clicking "Add category to shortcode" button above the posts table.', 'cvm_video' ),
				'is_gutenberg' => absint( $this->is_gutenberg_page() )
			]
        );
		
		// some files are needed only on custom post type edit page
		if( $_post->is_video() ){
			// add video player for video preview on post
			\Vimeotheque\cvm_enqueue_player();

			wp_enqueue_script(
			    'cvm-video-edit',
				VIMEOTHEQUE_URL . 'assets/back-end/js/video-edit.js',
                [ 'jquery' ],
                '1.0'
            );

			wp_enqueue_style(
			    'cvm-video-thumbnail',
				VIMEOTHEQUE_URL . 'assets/back-end/css/video-thumbnail.css',
                false,
                '1.0'
            );

			if( !$this->is_gutenberg ) {
				// video thumbnail functionality
				wp_enqueue_script(
					'cvm-video-thumbnail',
					VIMEOTHEQUE_URL . 'assets/back-end/js/video-thumbnail.js',
                    [ 'jquery' ],
                    '1.0'
                );

				wp_localize_script(
				    'cvm-video-thumbnail',
                    'cvm_thumb_message',
                    [
					    'loading'       => __( '... importing thumbnail', 'cvm_video' ),
					    'still_loading' => __( '... hold on, still loading' )
				    ]
                );
			}
		}

	}

	/**
	 * Add meta boxes on video posts
	 */
	public function add_meta_boxes(){
		global $post;
		if( ! $post ){
			return;
		}

		$_post = Helper::get_video_post( $post );
		// video post specific meta boxes
		if( $_post->is_video() ){
			add_meta_box(
			    'cvm-video-settings',
                __( 'Video settings', 'cvm_video' ),
                [ $this, 'post_video_settings_meta_box' ],
                $post->post_type,
                'normal',
                'high'
            );
			
			add_meta_box(
			    'cvm-show-video',
                __( 'Live video', 'cvm_video' ),
                [ $this, 'post_show_video_meta_box' ],
                $post->post_type,
                'normal',
                'high'
            );
		}
		
		// Shortcode meta box
		add_meta_box(
		    'cvm-add-video',
            __( 'Vimeotheque shortcode', 'cvm_video' ),
            [ $this, 'post_shortcode_meta_box' ],
            null,
            'side',
            'low'
        );
	}

	/**
	 * @param $input_name
	 * @param string $message
	 */
	private function option_override( $input_name, $message = '' ){
		global $post;
		$settings = \Vimeotheque\get_video_settings( $post->ID );
?>
    <input type="hidden" name="<?php echo esc_attr( $input_name );?>" value="<?php echo esc_attr( $settings[ $input_name ] );?>" />
    <em><?php echo $message ;?></em>
<?php
    }

	/**
	 * @return mixed
	 */
    private function is_option_override(  ){
	    $settings = \Vimeotheque\get_player_settings();
	    return $settings['allow_override'];
    }

	/**
	 * Check if current page uses Gutenberg or classic editor
	 * Adapted from https://github.com/Freemius/wordpress-sdk/commit/8a87d389c647b4588bfe96fc7d420d62a48cbac5
	 *
	 * @return bool
	 */
	private function is_gutenberg_page() {
		if
        (
		     function_exists( 'is_gutenberg_page' ) &&
		     is_gutenberg_page()
		) {
			// The Gutenberg plugin is on.
			return true;
		}

		$current_screen = get_current_screen();

		if
        (
		    method_exists( $current_screen, 'is_block_editor' ) &&
		    $current_screen->is_block_editor()
		) {
			// Gutenberg page on 5+.
			return true;
		}
		return false;
	}

	/**
	 * Video embed settings meta box displayed on custom post type post editing and
	 * regular post editing for videos imported as regular posts
	 * 
	 * @return null
	 */
	public function post_video_settings_meta_box(){
		global $post;
		$settings = \Vimeotheque\get_video_settings( $post->ID );
		$plugin_options = \Vimeotheque\get_player_settings();
?>
<?php wp_nonce_field('cvm-save-video-settings', 'cvm-video-nonce');?>
<?php if( $this->is_option_override() ): ?>
<div style="background-color: rgba(252,255,22,0.1); padding:.5em 1em;">
    <p>
        <h3><?php _e('Options override is ON', 'cvm_video');?></h3>
        <?php
            printf(
                __( 'Individual video post options are not editable; to change video options globally, go to plugin %sSettings%s, tab Embed options.', 'cvm_video' ),
                '<a href="' . menu_page_url( 'cvm_settings', false ) . '">',
                '</a>'
            );?>
    </p>
</div>
<?php endif;?>
<table class="form-table cvm-player-settings-options">
	<tbody>
		<tr>
			<th><label for="cvm_aspect_ratio"><?php _e('Player size', 'cvm_video');?>:</label></th>
			<td>
            <?php if( $this->is_option_override() ):?>
                <?php $this->option_override( 'width', sprintf( __( '%s X %s px' ), $plugin_options['width'], \Vimeotheque\cvm_player_height( $plugin_options['aspect_ratio'], $plugin_options['width'] ) ) );?>
	            <?php $this->option_override( 'aspect_ratio', sprintf( __( '/ Aspect ratio: %s', 'cvm_video' ), $plugin_options['aspect_ratio'] ) );?>
            <?php else: // is not option override?>
                <label for="cvm_aspect_ratio"><?php _e('Aspect ratio');?> :</label>
                <?php
                    $args = [
                            'name' => 'aspect_ratio',
                            'id' => 'cvm_aspect_ratio',
                            'class' => 'cvm_aspect_ratio',
                            'selected' => $settings[ 'aspect_ratio' ]
                    ];
                    Helper_Admin::aspect_ratio_select( $args );
                ?>
                <label for="cvm_width"><?php _e('Width', 'cvm_video');?> :</label>
				<input type="text" name="width" id="cvm_width" class="cvm_width" value="<?php echo $settings['width'];?>" size="2" />px
					| <?php _e('Height', 'cvm_video');?> : <span class="cvm_height"
				id="cvm_calc_height"><?php echo \Vimeotheque\cvm_player_height( $settings['aspect_ratio'], $settings['width'] );?></span>px
            <?php endif;// end option override?>
			</td>
		</tr>

		<tr>
			<th><label for="cvm_video_position"><?php _e('Display video','cvm_video');?>:</label></th>
			<td>
            <?php if( $this->is_option_override() ):
                    $video_positions = [
	                    'above-content' => __( 'Above post content', 'cvm_video' ),
	                    'below-content' => __( 'Below post content', 'cvm_video' )
                    ];
                ?>
                <?php $this->option_override( 'video_position', $video_positions[ $plugin_options['video_position'] ] );?>
		    <?php else: // is not option override?>
                <?php
                    $args = [
                        'options' => [
                            'above-content' => __( 'Above post content', 'cvm_video' ),
                            'below-content' => __( 'Below post content', 'cvm_video' )
                        ],
                        'name' => 'video_position',
                        'id' => 'cvm_video_position',
                        'selected' => $settings[ 'video_position' ]
                    ];
                    Helper_Admin::select( $args );
		        ?>
            <?php endif;// end option override?>
            </td>
		</tr>
		<tr>
			<th><label for="cvm_volume"><?php _e('Volume', 'cvm_video');?></label>:</th>
			<td>
            <?php if( $this->is_option_override() ):?>
	            <?php $this->option_override( 'volume', $plugin_options['volume'] );?>
		    <?php else: // is not option override?>
                <input type="text" name="volume" id="cvm_volume" value="<?php echo $settings['volume'];?>" size="1" maxlength="3" />
            <?php endif;// end option override?>
                <label for="cvm_volume"><span class="description">( <?php _e('number between 0 (mute) and 100 (max)', 'cvm_video');?> )</span></label>
			</td>
		</tr>
		<tr>
			<th><label for="cvm_autoplay"><?php _e('Autoplay', 'cvm_video');?></label>:</th>
			<td>
		    <?php if( $this->is_option_override() ):?>
			    <?php $this->option_override( 'autoplay', ( $plugin_options['autoplay'] ? __( 'On', 'cvm_video' ) : __( 'Off', 'cvm_video' ) ) );?>
		    <?php else: // is not option override?>
                <input name="autoplay" id="cvm_autoplay" type="checkbox" value="1" <?php Helper_Admin::check( (bool) $settings['autoplay'] );?> />
                <label for="cvm_autoplay"><span class="description">( <?php _e('when checked, video will start playing once page is loaded', 'cvm_video');?> )</span></label>
		    <?php endif;// end option override?>
                <p class="description"><?php _e( 'Autoplay may be blocked in some environments, such as IOS, Chrome 66+, and Safari 11+. In these cases, Vimeo player will revert to standard playback requiring viewers to initiate playback.', 'cvm_video' );?></p>
            </td>
		</tr>
		<tr>
			<th><label for="cvm_loop"><?php _e('Loop video', 'cvm_video');?></label>:</th>
			<td>
            <?php if( $this->is_option_override() ):?>
                <?php $this->option_override( 'loop', ( $plugin_options['loop'] ? __( 'On', 'cvm_video' ) : __( 'Off', 'cvm_video' ) ) );?>
            <?php else: // is not option override?>
                <input name="loop" id="cvm_loop" type="checkbox" value="1" <?php Helper_Admin::check( (bool) $settings['loop'] );?> /> <label for="cvm_loop">
                <span class="description">( <?php _e('when checked, the video will play again when it reaches the end', 'cvm_video');?> )</span></label>
            <?php endif;// end option override?>
			</td>
		</tr>
		<tr>
			<th><label for="title"><?php _e('Show video title', 'cvm_video');?></label>:</th>
			<td>
            <?php if( $this->is_option_override() ):?>
                <?php $this->option_override( 'title', ( $plugin_options['title'] ? __( 'On', 'cvm_video' ) : __( 'Off', 'cvm_video' ) ) );?>
            <?php else: // is not option override?>
                    <input name="title" id="cvm_title" class="cvm_title" type="checkbox" value="1" <?php Helper_Admin::check( (bool) $settings['title'] );?> />
                <label for="cvm_title"><span class="description">( <?php _e('when checked, player will display video title', 'cvm_video');?> )</span></label>
            <?php endif;// end option override?>
			</td>
		</tr>
        <?php /* // fullscreen option is deprecated and no longet supported by Vimeo player API
		<tr>
			<th><label for="cvm_fullscreen"><?php _e('Allow full screen', 'cvm_video');?></label>:</th>
			<td>
            <?php if( $this->is_option_override() ):?>
                <?php $this->option_override( 'fullscreen', ( $plugin_options['fullscreen'] ? __( 'On', 'cvm_video' ) : __( 'Off', 'cvm_video' ) ) );?>
            <?php else: // is not option override?>
                <input name="fullscreen" id="cvm_fullscreen" type="checkbox" value="1" <?php Helper_Admin::check( (bool) $settings['fullscreen'] );?> />
            <?php endif;// end option override?>
            </td>
		</tr>
        */ ?>
		<tr>
			<th><label for="cvm_color"><?php _e('Player color', 'cvm_video');?></label>:</th>
			<td>
            <?php if( $this->is_option_override() ):?>
                <?php $this->option_override( 'color', ( empty( $plugin_options['color'] ) ? __( 'Default', 'cvm_video' ) : '#' . $plugin_options['color'] ) );?>
                <?php if( !empty( $plugin_options['color'] ) ):?>
                    <div style="width: 20px; height: 20px; background-color: #<?php echo $plugin_options['color'];?>; float: left; margin-right:10px;">&nbsp;</div>
                <?php endif;?>
            <?php else: // is not option override?>
                #<input type="text" name="color" id="cvm_color" value="<?php echo $settings['color'];?>" />
            <?php endif;// end option override?>
            </td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="byline"><?php _e('Show video author', 'cvm_video')?>:</label></th>
			<td>
            <?php if( $this->is_option_override() ):?>
                <?php $this->option_override( 'byline', ( $plugin_options['byline'] ? __( 'On', 'cvm_video' ) : __( 'Off', 'cvm_video' ) ) );?>
            <?php else: // is not option override?>
                <input type="checkbox" value="1" id="byline" name="byline" <?php Helper_Admin::check( (bool) $settings['byline'] );?> />
                <span class="description"><?php _e('When checked, player will display video uploader.', 'cvm_video');?></span>
            <?php endif;// end option override?>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="portrait"><?php _e('Author portrait', 'cvm_video')?>:</label></th>
			<td>
            <?php if( $this->is_option_override() ):?>
                <?php $this->option_override( 'portrait', ( $plugin_options['portrait'] ? __( 'On', 'cvm_video' ) : __( 'Off', 'cvm_video' ) ) );?>
            <?php else: // is not option override?>
            <input type="checkbox" value="1" id="portrait" name="portrait" <?php Helper_Admin::check( (bool) $settings['portrait'] );?> />
                <span class="description"><?php _e('When checked, player will display uploader image.', 'cvm_video');?></span>
            <?php endif;// end option override?>
			</td>
		</tr>

	</tbody>
</table>
<?php
	}

	/**
	 * Display live video meta box on post editing
	 */
	public function post_show_video_meta_box(){
		global $post;
        $video_post = Helper::get_video_post( $post );
		$video_data = $video_post->get_video_data();
		?>
<script language="javascript">
;(function($){
	$(document).ready(function(){
		$('#cvm-video-preview').Vimeo_VideoPlayer({
			'video_id' 	: '<?php echo $video_data['video_id'];?>',
			'source'	: 'vimeo'
		});
	})
})(jQuery);
</script>
<div id="cvm-video-preview"
	style="height: 315px; width: 560px; max-width: 100%;"
	<?php \Vimeotheque\cvm_output_player_data();?>></div>
<?php
	}

	/**
	 * Post add shortcode meta box output
	 */
	public function post_shortcode_meta_box(){
		?>
<p><?php _e('Add video/playlist into post.', 'cvm_video');?>
<p>
	<a class="button" href="#" id="cvm-shortcode-2-post"
		title="<?php _e('Add shortcode');?>"><?php _e('Add video shortcode');?></a>
		<?php
	}

	/**
	 * Video/playlist embed shortcode modal window output
	 * 
	 * @return null
	 */
	public function shortcode_modal(){
		global $post;
		if( ! $post ){
			return;
		}
		
		$options = \Vimeotheque\get_player_settings();
		?>
	
<div id="CVMVideo_Modal_Window" style="display: none;">
	<div class="wrap">
		<div id="cvm-playlist-items">
			<div class="inside">
				<h3><?php _e('Playlist settings', 'cvm_video');?></h3>
				<div id="cvm-playlist-settings" class="cvm-player-settings-options">
					<table>
						<tr>
							<th valign="top"><label for="cvm_playlist_theme"><?php _e('Theme', 'cvm_video');?>:</label></th>
							<td>
								<?php
		$playlist_themes = \Vimeotheque\cvm_playlist_themes();
		Helper_Admin::select( [
				'name' => 'cvm_playlist_theme', 
				'id' => 'cvm_playlist_theme', 
				'options' => $playlist_themes
		] );
		?>
                                    <div class="cvm-theme-customize default">
                                        <?php _e( 'Playlist location', 'cvm_video' ) ;?> :
                                        <label for=""><input type="radio" name="cvm_theme_default_layout" value="" checked="checked" /> <?php _e( 'bottom', 'cvm_video' );?></label>
                                        <label for=""><input type="radio" name="cvm_theme_default_layout" value="right" /> <?php _e( 'right', 'cvm_video' );?></label>
                                        <label for=""><input type="radio" name="cvm_theme_default_layout" value="left" /> <?php _e( 'left', 'cvm_video' );?></label>
                                    </div>

								</td>
						</tr>
						<tr>
							<th><label for="cvm_aspect_ratio"><?php _e('Aspect', 'cvm_video');?>:</label></th>
							<td>
								<?php
		$args = [
				'name' => 'aspect_ratio', 
				'id' => 'aspect_ratio', 
				'class' => 'cvm_aspect_ratio'
		];
		Helper_Admin::aspect_ratio_select( $args );
		?>
								</td>
						</tr>

						<tr>
							<th><label for="width"><?php _e('Width', 'cvm_video');?>:</label></th>
							<td><input type="text" class="cvm_width" name="width" id="width"
								value="<?php echo $options['width'];?>" size="2" />px
									| <?php _e('Height', 'cvm_video');?> : <span class="cvm_height"
								id="cvm_calc_height"><?php echo \Vimeotheque\cvm_player_height( $options['aspect_ratio'], $options['width'] );?></span>px
							</td>
						</tr>

						<tr>
							<th><label for="volume"><?php _e('Volume', 'cvm_video');?></label>:</th>
							<td><input type="text" name="volume" id="volume"
								value="<?php echo $options['volume'];?>" size="1" maxlength="3" />
								<label for="volume"><span class="description"><?php _e('number between 0 (mute) and 100 (max)', 'cvm_video');?></span></label>
							</td>
						</tr>

						<tr>
							<th><label for="cvm_title"><?php _e('Title', 'cvm_video');?></label>:</th>
							<td><input type="checkbox" name="title" id="cvm_title" value="1"
								<?php if($options['title']) echo 'checked="checked"';?> /> <label
								for="cvm_title"><span class="description"><?php _e('will display title on video', 'cvm_video');?></span></label>
							</td>
						</tr>

						<tr>
							<th><label for="cvm_byline"><?php _e('Author', 'cvm_video');?></label>:</th>
							<td><input type="checkbox" name="byline" id="cvm_byline"
								value="1"
								<?php if($options['byline']) echo 'checked="checked"';?> /> <label
								for="cvm_byline"><span class="description"><?php _e('will display author name on video', 'cvm_video');?></span></label>
							</td>
						</tr>

						<tr>
							<th><label for="cvm_portrait"><?php _e('Image', 'cvm_video');?></label>:</th>
							<td><input type="checkbox" name="portrait" id="cvm_portrait"
								value="1"
								<?php if($options['portrait']) echo 'checked="checked"';?> /> <label
								for="cvm_portrait"><span class="description"><?php _e('will display author image on video', 'cvm_video');?></span></label>
							</td>
						</tr>

						<tr>
							<th><label for="playlist_loop"><?php _e('Loop', 'cvm_video');?></label>:</th>
							<td><input type="checkbox" name="playlist_loop"
								id="playlist_loop" value="1" /> <label for="playlist_loop"><span
									class="description"><?php _e('will automatically play next video when current playing video ends', 'cvm_video');?></span></label>
							</td>
						</tr>
					</table>
					<p>
						<input type="button" id="cvm-insert-playlist-shortcode"
							class="button primary"
							value="<?php _e('Insert playlist', 'cvm_video');?>" />
					</p>
				</div>

                <input type="hidden" name="cvm_selected_categories" value="" />
                <h3><?php _e( 'Create from categories', 'cvm_video' );?></h3>
                <div id="cvm-categories-list">
                    <em>
                        <?php _e( 'Select some categories to display videos from.', 'cvm_video' ) ;?><br />
                        <?php _e( 'Categories can be added by clicking "Add category to shortcode" button above the posts table.', 'cvm_video' );?>
                    </em>
                </div>

				<input type="hidden" name="cvm_selected_items" value="" />
				<h3><?php _e('Videos selected in playlist', 'cvm_video');?></h3>

				<div id="cvm-list-items">
					<em><?php _e('No videos selected', 'cvm_video');?><br /><?php _e('To create a playlist check some videos from the list on the right.', 'cvm_video');?></em>
				</div>
			</div>
		</div>
		<div id="cvm-display-videos">
			<iframe
				src="edit.php?post_type=<?php echo $this->cpt->get_post_type();?>&page=cvm_videos"
				frameborder="0" width="100%" height="100%"></iframe>
		</div>
	</div>
</div>
<?php
	}

	/**
	 * Add filters to put tinymce plugin buttons on editor rich edit mode
	 * 
	 * @return null
	 */
	public function tinymce(){
		// Don't bother doing this stuff if the current user lacks permissions
		if( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) )
			return;
			
			// Don't load unless is post editing (includes post, page and any custom posts set)
		$screen = get_current_screen();
		if( !$screen || 'post' != $screen->base || $this->cpt->get_post_type() == $screen->post_type ){
			return;
		}
		
		// Add only in Rich Editor mode
		if( get_user_option( 'rich_editing' ) == 'true' ){
			
			wp_enqueue_script( [
					'jquery-ui-dialog'
			] );
			
			wp_enqueue_style( [
					'wp-jquery-ui-dialog'
			] );

			add_filter( 'mce_external_plugins', [
					$this, 
					'tinymce_plugins'
			] );
			add_filter( 'mce_buttons', [
					$this, 
					'tinymce_buttons'
			] );
		}
	}

	/**
	 * Add tinymce plugin ( mce_external_plugins filter callback set in function $this->tinymce )
	 *
	 * @param $plugin_array
	 *
	 * @return array
	 */
	public function tinymce_plugins( $plugin_array ){
		$plugin_array[ 'cvm_shortcode' ] = VIMEOTHEQUE_URL . 'assets/back-end/js/tinymce/shortcode.js';
		return $plugin_array;
	}

	/**
	 * Add tinymce buttons for the mce plugin registered above
	 *
	 * @param $buttons
	 *
	 * @return array
	 */
	public function tinymce_buttons( $buttons ){
		array_push( $buttons, 'separator', 'cvm_shortcode' );
		return $buttons;
	}

	/**
	 * Save post data from meta boxes.
	 * Hooked to save_post
	 *
	 * @param int $post_id
	 * @param WP_Post $post
	 */
	public function save_post( $post_id, $post ){
		if( ! isset( $_POST[ 'cvm-video-nonce' ] ) ){
			return;
		}

		$_post = Helper::get_video_post( $post );
		// check if post is the correct type
		if( !$_post->is_video() ){
			return;
		}
		// check if user can edit
		if( ! current_user_can( 'edit_post', $post_id ) ){
			return;
		}
		// check nonce
		check_admin_referer( 'cvm-save-video-settings', 'cvm-video-nonce' );
		// update post
		\Vimeotheque\cvm_update_video_settings( $post_id );
	}

	/**
	 * Manipulate output for featured image on custom post to allow importing of thumbnail as featured image
	 *
	 * @param $content
	 * @param $post_id
	 *
	 * @return string
	 */
	public function post_thumbnail_meta_panel( $content, $post_id ){
		$_post = Helper::get_video_post( $post_id );
		
		if( !$_post || !$_post->video_id ){
			return $content;
		}

		$has_thumbnail = get_post_meta( $post_id, '_thumbnail_id', true );
		if( !$_post->thumbnails ){
			$has_thumbnail = true;
		}
		
		if( $has_thumbnail ){
			$content .= '<hr /><br />';
			$content .= sprintf( '<a href="#" id="cvm-import-video-thumbnail" data-refresh="1" data-post="%d"><i class="dashicons dashicons-update"></i> %s</a>', $post_id, __( 'Refresh Vimeo image', 'cvm_video' ) );
			$content .= '<p class="description" id="cvm-thumb-response">' . __( 'Will import a fresh image from Vimeo. If already existing, it will be duplicated.', 'cvm_video' ) . '</p>';
		}else{
			$content .= '<hr /><br />';
			$content .= sprintf( '<a href="#" id="cvm-import-video-thumbnail" data-refresh="0" data-post="%d"><i class="dashicons dashicons-download"></i >%s</a>', $post_id, __( 'Import Vimeo image', 'cvm_video' ) );
			$content .= '<p class="description" id="cvm-thumb-response">' . __( 'Will first search the Media Gallery for an already imported image and will import if none found.', 'cvm_video' ) . '</p>';
		}
		return $content;
	}

	/**
	 * Callback on Gutenberg's script enqueue action
     * Enqueues all necessary files for Gutenberg compatibility
	 */
	public function gutenberg_editor(){
	    global $post;
	    // do not enqueue script if post isn't a video post imported by the plugin
        if( is_a( $post, 'WP_Post' ) ){
            if( !\Vimeotheque\is_video( $post ) ){
                return;
            }
        }

		wp_enqueue_script(
		    'cvm-gutenberg',
			VIMEOTHEQUE_URL . 'assets/back-end/js/gutenberg/video-thumbnail.js',
            [ 'jquery' ],
            '1.0'
        );
		wp_dequeue_script( 'cvm-video-thumbnail' );
		$this->is_gutenberg = true;
    }
}