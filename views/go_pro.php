<?php

namespace Vimeotheque;

use Vimeotheque\Admin\Helper_Admin;

?>
<div class="wrap about-wrap">
	<h1>
		<?php _e('Vimeotheque PRO', 'codeflavors-vimeo-video-post-lite')?>
	</h1>
	<p class="about-text" style="margin-left: 0; margin-right: 0;">
		<?php _e('Created having Vimeo publishers in mind, PRO version offers more tools to make your life easier and to allow you to focus on creating more high quality content.', 'codeflavors-vimeo-video-post-lite');?>
	</p>

	<hr />

	<script language="javascript">
        ;(function($){
            $(document).ready(function(){
                $('#cvm-video-preview').Vimeo_VideoPlayer({
                    'video_id' 	: '223879840',
                    'source'	: 'vimeo',
                    'play'       : false,
                    'volume'    : 50
                }).pause();
            })
        })(jQuery);
	</script>

	<div class="class="feature-section one-col">
		<div class="col">
			<div id="cvm-video-preview" class="cvm-video-preview">%nbsp;</div>
			<p class="gopro-btn-holder">
				<a class="button try-pro-btn" href="https://vvp-demo.codeflavors.com/" target="_blank"><?php _e( 'Try PRO version', 'codeflavors-vimeo-video-post-lite' ) ;?></a>
				<a class="button gopro-btn" href="<?php echo Helper_Admin::publisher_link( '' ) ;?>" target="_blank"><?php _e( 'Get PRO version!', 'codeflavors-vimeo-video-post-lite' ) ;?></a>
			</p>
		</div>
	</div>
<hr />
	<div class="class="feature-section two-col">
		<h2><?php _e( 'Vimeotheque Lite vs. PRO ', 'codeflavors-vimeo-video-post-lite' ) ;?></h2>
		<div class="two-col">
			<div class="col">
				<p>
					<h3><?php _e( 'Import videos as plugin custom post type', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( 'Videos retrieved from Vimeo will be imported into WordPress as custom post type <em>vimeo-video</em>.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="yes">Lite</span>
				</p>

				<p>
					<h3><?php _e( 'Single video import', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( 'Create new WordPress post from a given Vimeo video ID.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="yes">Lite</span>
				</p>

				<p>
					<h3><?php _e( 'Bulk import videos', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php printf( __( 'Import Vimeo videos as WordPress posts by using the %smanual bulk import%s feature.', 'codeflavors-vimeo-video-post-lite' ), '<a href="' . Helper_Admin::publisher_link( 'documents/basic-tutorials/manual-bulk-import/' ) . '" target="_blank">', '</a>' );?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="yes">Lite</span>
				</p>

				<p>
					<h3><?php _e( 'Bulk import into WordPress categories', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( 'Import Vimeo videos into your existing post categories.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="yes">Lite</span>
				</p>

				<p>
					<h3><?php _e( 'General video embedding option', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php printf( __( 'Set global %svideo embed options%s for all your new videos from plugin settings.', 'codeflavors-vimeo-video-post-lite' ), '<a href="' . Helper_Admin::publisher_link( 'documents/plugin-options/embed-options/' ) . '" target="_blank">', '</a>' );?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="yes">Lite</span>
				</p>

				<p>
					<h3><?php _e( 'Post video embedding options', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( 'Customize video embed settings for each individual post created from a Vimeo video.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="yes">Lite</span>
				</p>

				<p>
					<h3><?php _e( 'Video title and description import', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( 'The post created from Vimeo video will automatically have the title and post content and/or excerpt filled with the details retrieved from Vimeo.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="yes">Lite</span>
				</p>

				<p>
					<h3><?php _e( 'Single video shortcode embed', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( 'A simple shortcode that embeds imported videos into any post or page.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="yes">Lite</span>
				</p>

				<p>
					<h3><?php _e( 'Video playlist shortcode embed', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( 'A playlist shortcode that embeds playlists made from existing posts into any post or page.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="yes">Lite</span>
				</p>
			</div>
			<div class="col">
				<p>
					<h3><?php _e( 'Private videos import', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( 'Allows you to create posts from your own private videos from Vimeo.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="no">Lite</span>
				</p>

				<p>
					<h3><?php _e( 'Automatic video import', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( 'Automatically create video posts from Vimeo channels, categories, albums, uploads or groups with embedded video and full details (title, description, featured image). Once set up, the plugin will run the import process automatically.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="no">Lite</span>
				</p>

				<p>
					<h3><?php _e( 'Import videos as regular posts', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( 'Choose to import videos as regular <strong>post</strong> type post instead of plugin’s post type <strong>vimeo-video</strong>.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="no">Lite</span>
				</p>

				<p>
					<h3><?php _e( 'Import videos as WordPress theme posts', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( 'For video websites running <strong>video WordPress themes</strong> the plugin can import Vimeo videos as any post type needed by your theme and will automatically fill all custom fields needed by the theme to embed and display the video and its information.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="no">Lite</span>
				</p>

				<p>
					<h3><?php _e( 'Import multiple video tags from Vimeo', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( 'When importing Vimeo videos the plugin can automatically create and assign the tags of the video on your WordPress website.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="no">Lite</span>
				</p>

				<p>
					<h3><?php _e( 'Bulk import video image as post featured image', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( 'Set up Vimeo video image as post featured image when importing videos as posts in WordPress.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="no">Lite</span>
				</p>

				<p>
					<h3><?php _e( 'Include video microdata in front-end', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( 'The plugin can optionally automatically create video microdata for SEO purposes directly in your pages.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="no">Lite</span>
				</p>

				<p>
					<h3><?php _e( 'WordPress video theme compatibility layer', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( 'By default, the plugin is compatible with several WordPress video themes and can also be extended to include your theme if not supported.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="no">Lite</span>
				</p>

				<p>
					<h3><?php _e( 'Full support', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( '<strong>Priority support</strong> and debugging directly on your website from the plugin developers.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="no">Lite</span>
				</p>
				<p class="gopro-btn-holder extra-space">
					<a class="button try-pro-btn" href="https://vvp-demo.codeflavors.com/" target="_blank"><?php _e( 'Try PRO version', 'codeflavors-vimeo-video-post-lite' ) ;?></a>
					<a class="button gopro-btn" href="<?php echo Helper_Admin::publisher_link( '' ) ;?>" target="_blank"><?php _e( 'Go PRO!', 'codeflavors-vimeo-video-post-lite' ) ;?></a>
				</p>
			</div>
		</div>
	</div>
	<hr />
	<div class="return-to-dashboard">
		<a href="<?php echo Helper_Admin::publisher_link(''); ?>">Vimeotheque PRO</a>
	</div>
</div>