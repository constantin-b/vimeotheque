<?php

namespace Vimeotheque\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use stdClass;
use Vimeotheque\Post_Type;

/**
 * Meta panels callbacks
 * @author CodeFlavors
 *
 */
class Post_Edit_Meta_Panels{
	/**
	 * Stores the Post_Type object
	 * @var Post_Type
	 */
	private $cpt;

	/**
	 * Constructor. Used by other classes to initialize meta panels
	 *
	 * @param Post_Type $object
	 */
	public function __construct( Post_Type $object ){
		$this->cpt = $object;
	}

	/**
	 * Manual import side panel output
	 */
	public function import_entries_meta(){
		// plugin options
		$options = \Vimeotheque\get_settings();
		// embed options
		$player_opt = \Vimeotheque\get_player_settings();
		// merge the two together
		$options = array_merge( $options, $player_opt );

		if( isset( $_GET['id'] ) ){
			$playlist_id = absint($_GET['id']);
			$options = \Vimeotheque\cvm_get_playlist_settings( $playlist_id );

		}
		?>
		<label for="import_description"><?php _e('Set description as', 'cvm_video')?>:</label>
		<?php 
			$args = [
				'options' => [
					'content' 			=> __('content', 'cvm_video'),
					'excerpt' 			=> __('excerpt', 'cvm_video'),
					'content_excerpt' 	=> __('both', 'cvm_video'),
					'none'				=> __('none', 'cvm_video')
				],
				'name' => 'import_description',
				'selected' => $options['import_description']
			];
			Helper_Admin::select( $args );
		?><br />

		<label for="import_status"><?php _e('Import status', 'cvm_video')?>:</label>
		<?php 
			$args = [
				'options' => [
					'publish' 	=> __('Published', 'cvm_video'),
					'draft' 	=> __('Draft', 'cvm_video'),
					'pending'	=> __('Pending', 'cvm_video')
				],
				'name' 		=> 'import_status',
				'selected' 	=> $options['import_status']
			];
			Helper_Admin::select( $args );
		?><br />
		
		<?php			
			$dropdown = wp_dropdown_users( [
				'name' 	=> 'import_user',
				'id'	=> 'import_user',
				'hide_if_only_one_author' => true,
				'who' => 'authors',
				'selected' => isset( $options['import_user'] ) ? $options['import_user'] : false,
				'echo' => false
			] );
			if( $dropdown ):
		?>
		<label for="import_user"><?php _e('Import as user', 'cvm_video')?></label>
		<?php echo $dropdown;?>
		<br />
		<?php endif;?>
		
		<label for="import_title"><?php _e('Import titles', 'cvm_video')?> :</label>
		<input type="checkbox" value="1" id="import_title" name="import_title"<?php Helper_Admin::check( $options['import_title'] );?> /><br />
		
		<label for="import_date"><?php _e('Import date', 'cvm_video')?> :</label>
		<input type="checkbox" value="1" id="import_date" name="import_date"<?php Helper_Admin::check( $options['import_date'] );?> />
		
		<div id="cvm-import-embed-settings" style="clear:both; padding-top:5px;">
			<h4><?php _e('Video embed options', 'cvm_video');?></h4>
			<label for="cvm_aspect_ratio"><?php _e('Aspect ratio');?> :</label>
			<?php 
				$args = [
					'name' 		=> 'aspect_ratio',
					'id'		=> 'aspect_ratio',
					'class'		=> 'aspect_ratio',
					'selected' 	=> $options['aspect_ratio']
				];
				Helper_Admin::aspect_ratio_select( $args );
			?><br />
			
			<label for="cvm_video_position"><?php _e('Display video','cvm_video');?>:</label>
			<?php 
				$args = [
					'options' => [
						'above-content' => __('Above content', 'cvm_video'),
						'below-content' => __('Below content', 'cvm_video')
					],
					'name' 		=> 'video_position',
					'id'		=> 'video_position',
					'selected' 	=> $options['video_position']
				];
				Helper_Admin::select( $args );
			?><br />
			
			<label for="loop"><?php _e('Loop video', 'cvm_video')?> :</label>
			<input type="checkbox" value="1" id="loop" name="loop"<?php Helper_Admin::check( (bool) $options['loop'] );?> /><br />
			
			<label for="autoplay"><?php _e('Autoplay video', 'cvm_video')?> :</label>
			<input type="checkbox" value="1" id="autoplay" name="autoplay"<?php Helper_Admin::check( (bool) $options['autoplay'] );?> /><br />
		</div>
			
		<div id="cvm-import-videos-submit-c">
		    <?php submit_button(
		            apply_filters('cvm-playlist-meta-submit-text', __('Import videos', 'cvm_video') ),
                    'primary',
                    'cvm-import-button',
                    true
            );?>
		
		<span class="cvm-ajax-response"></span>
		</div>
		<input type="hidden" name="action_top" id="action_top" value="import" />  
		<?php 
	}
		
	/**
	 * Import categories meta box
	 */
	public function import_categories_meta(){
		
		// include file responsible for meta boxes
		include_once ABSPATH . 'wp-admin/includes/meta-boxes.php';
		
		$post = new stdClass();
		$post->ID = -1;
		
		$taxonomy = $this->cpt->get_post_tax();
		/**
		 * Filter that allows manipulation of category taxonomy to import as any publicly registered post type.
		 * @param string - category taxonomy
		 */
		$taxonomy = apply_filters( 'cvm_import_category', $taxonomy );		
		
		post_categories_meta_box(
			$post,
			[
				'title' => __('Categories', 'cvm_video'),
				'args' => [
					'taxonomy' => $taxonomy
				]
			]
		);		
	}
		
	/**
	 * Import tags meta box
	 */
	public function import_tags_meta(){
		// include file responsible for meta boxes
		include_once ABSPATH . 'wp-admin/includes/meta-boxes.php';
		
		$post = new stdClass();
		$post->ID = -1;
		$post->post_type = $this->cpt->get_post_type();
		
		$taxonomy = $this->cpt->get_tag_tax();
		/**
		 * Filter that allows manipulation of tag taxonomy to import as any publicly registered post type.
		 * @param string - tag taxonomy
		 */
		$taxonomy = apply_filters( 'cvm_import_tag', $taxonomy );
		
		$options = \Vimeotheque\get_settings();
		if( isset( $options['import_tags'] ) && $options['import_tags'] ){
			_e('Please note that any tags retrieved from Vimeo will also be imported and set as post tags.', 'cvm_video');
		}
		
		post_tags_meta_box(
			$post,
			[
				'title' => __('Tags', 'cvm_video'),
				'args' => [
					'taxonomy' => $taxonomy
				]
			]
		);
	}
		
	/**
	 * Adds the meta boxes into the current screen
	 */
	public function add_metaboxes(){
		$screen = get_current_screen();
		$page_hook = $screen->id;

		$taxonomy = $this->cpt->get_post_tax();
		/**
		 * Filter that allows manipulation of category taxonomy to import as any publicly registered post type.
		 * @param string - category taxonomy
		 */
		$taxonomy = apply_filters( 'cvm_import_category', $taxonomy );
		$category = get_taxonomy( $taxonomy );

		$tag_tax = $this->cpt->get_tag_tax();
		/**
		 * Filter that allows manipulation of tag taxonomy to import as any publicly registered post type.
		 * @param string - tag taxonomy
		 */
		$tag_tax = apply_filters( 'cvm_import_tag', $tag_tax );
		$tag = get_taxonomy( $tag_tax );

		// meta boxes
		add_meta_box(
		    'cvm-import-feed-entries',
            __('Import', 'cvm_video'),
            [ $this, 'import_entries_meta' ],
            $page_hook,
            'side'
        );

		if( $category ){
			add_meta_box(
			    'tagsdiv-plugin-cat',
                $category->labels->name,
                [ $this, 'import_categories_meta' ],
                $page_hook,
                'side'
            );
		}

		if( $tag ){
			add_meta_box(
			    'tagsdiv-plugin-tag',
                $tag->labels->name,
                [ $this, 'import_tags_meta' ],
                $page_hook,
                'side'
            );
		}
    }
}