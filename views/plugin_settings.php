<?php
namespace Vimeotheque\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Vimeotheque\Admin\Page\Settings_Page;
use function Vimeotheque\cvm_automatic_update_timing;
use function Vimeotheque\cvm_link;
use function Vimeotheque\cvm_player_height;

/**
 * @var $this Settings_Page
 * @var $extra_tabs array
 * @var $options array
 * @var $player_opt array
 * @var $authorize_url string
 * @var $unauthorize_url string
 */
?>
<div class="wrap">
	<div class="icon32" id="icon-options-general"><br></div>
	<h2><?php _e('Videos - Plugin settings', 'cvm_video');?></h2>
	<div id="cvm_tabs">
		<form method="post" action="">
			<?php wp_nonce_field('cvm-save-plugin-settings', 'cvm_wp_nonce');?>
			<ul class="cvm-tab-labels">
				<li><a href="#cvm-settings-post-options"><i class="dashicons dashicons-arrow-right"></i> <?php _e('Post options', 'cvm_video')?></a></li>
				<li><a href="#cvm-settings-content-options"><i class="dashicons dashicons-arrow-right"></i> <?php _e('Content options', 'cvm_video')?></a></li>
				<li><a href="#cvm-settings-image-options"><i class="dashicons dashicons-arrow-right"></i> <?php _e('Image options', 'cvm_video')?></a></li>
				<li><a href="#cvm-settings-import-options"><i class="dashicons dashicons-arrow-right"></i> <?php _e('Import options', 'cvm_video')?></a></li>
				<li><a href="#cvm-settings-embed-options"><i class="dashicons dashicons-arrow-right"></i> <?php _e('Embed options', 'cvm_video')?></a></li>
				<li><a href="#cvm-settings-auth-options"><i class="dashicons dashicons-arrow-right"></i> <?php _e('API & License', 'cvm_video')?></a></li>
				<?php foreach( $extra_tabs as $tab_id => $hook ):?>
                    <li><a href="#<?php esc_attr_e( $tab_id );?>"><i class="dashicons dashicons-arrow-right"></i> <?php esc_attr_e( $hook['title'] );?></a></li>
				<?php endforeach;?>
			</ul>
			
			<!-- Tab post options -->
			<div id="cvm-settings-post-options">
				<table class="form-table">
					<tbody>
						<!-- Import type -->
						<tr><th colspan="2"><h4><i class="dashicons dashicons-admin-tools"></i> <?php _e('General settings', 'cvm_video');?></h4></th></tr>
						<tr valign="top">
							<th scope="row"><label for="post_type_post"><?php _e('Import as regular post type (aka post)', 'cvm_video')?>:</label></th>
							<td>
								<input type="checkbox" name="post_type_post" value="1" id="post_type_post"<?php Helper_Admin::check( $options['post_type_post'] );?> />
								<span class="description">
								<?php _e('Videos will be imported as <strong>regular posts</strong> instead of custom post type video. Posts having attached videos will display having the same player options as video post types.', 'cvm_video');?>
								</span>
							</td>
						</tr>				
						<tr valign="top">
							<th scope="row"><label for="archives"><?php _e('Embed videos in archive pages', 'cvm_video')?>:</label></th>
							<td>
								<input type="checkbox" name="archives" value="1" id="archives"<?php Helper_Admin::check( $options['archives'] );?> />
								<span class="description">
									<?php _e('When checked, videos will be visible on all pages displaying lists of video posts.', 'cvm_video');?>
								</span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="use_microdata"><?php _e('Include microdata on video pages', 'cvm_video')?>:</label></th>
							<td>
								<input type="checkbox" name="use_microdata" value="1" id="use_microdata"<?php Helper_Admin::check( $options['use_microdata'] );?> />
								<span class="description">
									<?php _e('When checked, all page displaying videos will also include microdata for SEO purposes ( more on <a href="http://schema.org" target="_blank">http://schema.org</a> ).', 'cvm_video');?>
								</span>
							</td>
						</tr>
                        <tr valign="top">
                            <th scope="row"><label for="check_video_status"><?php _e('Check video status after import', 'cvm_video')?>:</label></th>
                            <td>
                                <input type="checkbox" name="check_video_status" value="1" id="check_video_status" <?php Helper_Admin::check( $options['check_video_status'] );?> />
                                <span class="description">
									<?php _e('When checked, will verify on Vimeo every 24H if the video still exists or is embeddable and if not, it will automatically set the post status to pending. This action is triggered by your website visitors.', 'cvm_video');?>
								</span>
                            </td>
                        </tr>
						
						<!-- Visibility -->
						<tr><th colspan="2"><h4><i class="dashicons dashicons-video-alt3"></i> <?php _e('Video post type options', 'cvm_video');?></h4></th></tr>
						<tr valign="top">
							<th scope="row"><label for="public"><?php _e('Video post type is public', 'cvm_video')?>:</label></th>
							<td>
								<input type="checkbox" name="public" value="1" id="public"<?php Helper_Admin::check( $options['public'] );?> />
								<span class="description">
								<?php if( !$options['public'] ):?>
									<span style="color:red;"><?php _e('Videos cannot be displayed in front-end. You can only incorporate them in playlists or display them in regular posts using shortcodes.', 'cvm_video');?></span>
								<?php else:?>
								<?php _e('Videos will display in front-end as post type video are and can also be incorporated in playlists or displayed in regular posts.', 'cvm_video');?>
								<?php endif;?>
								</span>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><label for="homepage"><?php _e('Include videos post type on homepage', 'cvm_video')?>:</label></th>
							<td>
								<input type="checkbox" name="homepage" value="1" id="homepage"<?php Helper_Admin::check( $options['homepage'] );?> />
								<span class="description">
									<?php _e('When checked, if your homepage displays a list of regular posts, videos will be included among them.', 'cvm_video');?>
								</span>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><label for="main_rss"><?php _e('Include videos post type in main RSS feed', 'cvm_video')?>:</label></th>
							<td>
								<input type="checkbox" name="main_rss" value="1" id="main_rss"<?php Helper_Admin::check( $options['main_rss'] );?> />
								<span class="description">
									<?php _e('When checked, custom post type will be included in your main RSS feed.', 'cvm_video');?>
								</span>
							</td>
						</tr>				
						
						
						<!-- Rewrite settings -->
						<tr>
							<th colspan="2">
								<h4><i class="dashicons dashicons-admin-links"></i> <?php _e('Video post type rewrite (pretty links)', 'cvm_video');?></h4>
								<p class="description">
									<?php _e( "Please make sure that you don't insert the same slug twice.", 'cvm_video' );?>
								</p>
							</th>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="post_slug"><?php _e('Post slug', 'cvm_video')?>:</label></th>
							<td>
								<input type="text" id="post_slug" name="post_slug" value="<?php echo $options['post_slug'];?>" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="taxonomy_slug"><?php _e('Taxonomy slug', 'cvm_video')?> :</label></th>
							<td>
								<input type="text" id="taxonomy_slug" name="taxonomy_slug" value="<?php echo $options['taxonomy_slug'];?>" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="tag_slug"><?php _e('Tags slug', 'cvm_video')?> :</label></th>
							<td>
								<input type="text" id="tag_slug" name="tag_slug" value="<?php echo $options['tag_slug'];?>" />
							</td>
						</tr>
					</tbody>
				</table>
				<?php submit_button(__('Save settings', 'cvm_video'));?>	
			</div>
			<!-- /Tab post options -->	
			
			<!-- Tab content options -->
			<div id="cvm-settings-content-options">
				<table class="form-table">
					<tbody>
						<!-- Content settings -->
						<tr><th colspan="2"><h4><i class="dashicons dashicons-admin-post"></i> <?php _e('Post content settings', 'cvm_video');?></h4></th></tr>
						<tr valign="top">
							<th scope="row"><label for="import_date"><?php _e('Import date', 'cvm_video')?>:</label></th>
							<td>
								<input type="checkbox" value="1" name="import_date" id="import_date"<?php Helper_Admin::check($options['import_date']);?> />
								<span class="description"><?php _e("Imports will have Vimeo's publishing date.", 'cvm_video');?></span>
							</td>
						</tr>	
						
						<tr valign="top">
							<th scope="row"><label for="import_title"><?php _e('Import titles', 'cvm_video')?>:</label></th>
							<td>
								<input type="checkbox" value="1" id="import_title" name="import_title"<?php Helper_Admin::check($options['import_title']);?> />
								<span class="description"><?php _e('Automatically import video titles from feeds as post title.', 'cvm_video');?></span>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><label for="import_tags"><?php _e('Import tags', 'cvm_video')?>:</label></th>
							<td>
								<input type="checkbox" value="1" id="import_tags" name="import_tags"<?php Helper_Admin::check($options['import_tags']);?> />
								<span class="description"><?php _e('Automatically import video tags as post tags from feeds.', 'cvm_video');?></span>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><label for="max_tags"><?php _e('Number of tags', 'cvm_video')?>:</label></th>
							<td>
								<input type="text" value="<?php echo $options['max_tags'];?>" id="max_tags" name="max_tags" size="1" />
								<span class="description"><?php _e('Maximum number of tags that will be imported.', 'cvm_video');?></span>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><label for="import_description"><?php _e('Import descriptions as', 'cvm_video')?>:</label></th>
							<td>
								<?php 
									$args = [
										'options' => [
											'content' 			=> __('post content', 'cvm_video'),
											'excerpt' 			=> __('post excerpt', 'cvm_video'),
											'content_excerpt' 	=> __('post content and excerpt', 'cvm_video'),
											'none'				=> __('do not import', 'cvm_video')
										],
										'name' => 'import_description',
										'selected' => $options['import_description']
									];
									Helper_Admin::select( $args );
								?>
								<p class="description"><?php _e('Import video description from feeds as post description, excerpt or none.', 'cvm_video')?></p>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><label for="prevent_autoembed"><?php _e('Prevent auto embed on video content', 'cvm_video')?>:</label></th>
							<td>
								<input type="checkbox" value="1" name="prevent_autoembed" id="prevent_autoembed"<?php Helper_Admin::check($options['prevent_autoembed']);?> />
								<span class="description">
									<?php _e('If content retrieved from Vimeo has links to other videos, checking this option will prevent auto embedding of videos in your post content.', 'cvm_video');?>
								</span>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><label for="make_clickable"><?php _e("Make URL's in video content clickable", 'cvm_video')?>:</label></th>
							<td>
								<input type="checkbox" value="1" name="make_clickable" id="make_clickable"<?php Helper_Admin::check($options['make_clickable']);?> />
								<span class="description">
									<?php _e("Automatically make all valid URL's from content retrieved from Vimeo clickable.", 'cvm_video');?>
								</span>
							</td>
						</tr>															
						
					</tbody>
				</table>
				<?php submit_button(__('Save settings', 'cvm_video'));?>	
			</div>
			<!-- /Tab content options -->
			
			<!-- Tab image options -->
			<div id="cvm-settings-image-options">
				<table class="form-table">
					<tbody>
						<tr><th colspan="2"><h4><i class="dashicons dashicons-format-image"></i> <?php _e('Image settings', 'cvm_video');?></h4></th></tr>
						<tr valign="top">
							<th scope="row"><label for="featured_image"><?php _e('Import images', 'cvm_video')?>:</label></th>
							<td>
								<input type="checkbox" value="1" name="featured_image" id="featured_image"<?php Helper_Admin::check($options['featured_image']);?> />
								<span class="description"><?php _e("Vimeo video thumbnail will be set as post featured image.", 'cvm_video');?></span>						
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><label for="image_on_demand"><?php _e('Import featured image on request', 'cvm_video')?>:</label></th>
							<td>
								<input type="checkbox" value="1" name="image_on_demand" id="image_on_demand"<?php Helper_Admin::check($options['image_on_demand']);?> />
								<span class="description"><?php _e("Vimeo video thumbnail will be imported only when featured images needs to be displayed (ie. a post created by the plugin is displayed).", 'cvm_video');?></span>
							</td>
						</tr>					
					</tbody>
				</table>
				<?php submit_button(__('Save settings', 'cvm_video'));?>
			</div>
			<!-- /Tab image options -->
			
			<!-- Tab import options -->
			<div id="cvm-settings-import-options">
				<table class="form-table">
					<tbody>
						<!-- Manual Import settings -->
						<tr><th colspan="2"><h4><i class="dashicons dashicons-download"></i> <?php _e('Bulk Import settings', 'cvm_video');?></h4></th></tr>
						<tr valign="top">
							<th scope="row"><label for="import_status"><?php _e('Import status', 'cvm_video')?>:</label></th>
							<td>
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
								?>
								<p class="description"><?php _e('Imported videos will have this status.', 'cvm_video');?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="import_privacy"><?php _e('Videos not public will be', 'cvm_video')?>:</label></th>
							<td>
								<?php 
									$args = [
										'options' => [
											'import' 	=> __('imported following the import rules', 'cvm_video'),
											'pending' 	=> __('imported as posts pending review', 'cvm_video'),
											'skip'	=> __('skipped from importing', 'cvm_video')
										],
										'name' 		=> 'import_privacy',
										'selected' 	=> $options['import_privacy']
									];
									Helper_Admin::select( $args );
								?>
								<p class="description"><?php _e('If a video is not set as public by its owner (password protected videos for example), it will obey this rule.', 'cvm_video');?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="import_frequency"><?php _e('Automatic import', 'cvm_video')?> :</label></th>
							<td>
								<?php _e('Import ', 'cvm_video');?>
								<?php printf( __('%d videos', 'cvm_video'), $options['import_quantity'] );?>
								<?php _e('every', 'cvm_video');?>
								<?php 
									$args = [
										'options' => cvm_automatic_update_timing(),
										'name' 		=> 'import_frequency',
										'selected' 	=> $options['import_frequency']
									];
									Helper_Admin::select( $args );
								?>
								<p class="description"><?php _e('How often should Vimeo be queried for playlist updates.', 'cvm_video');?></p>
							</td>
						</tr>

                        <tr valign="top">
                            <th scope="row"><label for="cvm_conditional_import"><?php _e( 'Enable conditional automatic imports', 'cvm_video' );?>:</label>
                            </th>
                            <td>
                                <input type="checkbox" value="1" id="cvm_conditional_import" name="conditional_import" <?php checked( (bool) $options['conditional_import'] );?> />
                                <span class="description"><?php _e( 'When enabled, automatic imports will run only when a custom URL is opened on your website.', 'cvm_video' );?></span>
								<?php if( $options['conditional_import'] ) :?>
                                    <p>
										<?php printf( __( 'Important! Please make sure that you access URL %s by either setting a server cron job or using an alternative method that will generate a hit on this URL at least equal to automatic import setting from above.', 'cvm_video' ), '<code>' . \Vimeotheque\autoimport_uri( false ) . '</code>' );?>
                                    </p>
								<?php endif;?>
								<?php if( empty( $options['autoimport_param'] ) ):?>
                                    <input type="hidden" name="autoimport_param" value="<?php echo wp_generate_password( 16, false );?>" />
								<?php else:?>
                                    <input type="hidden" name="autoimport_param" value="<?php echo $options['autoimport_param'];?>" />
								<?php endif;?>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row"><label for="page_load_autoimport"><?php _e('Legacy automatic import', 'cvm_video')?>:</label></th>
                            <td>
                                <input type="checkbox" name="page_load_autoimport" id="page_load_autoimport" value="1" <?php Helper_Admin::check( (bool) $options['page_load_autoimport'] )?> />
                                <span class="description"><?php _e( 'Trigger automatic video imports on page load (will increase page load time when doing automatic imports)', 'cvm_video' );?></span>
                                <p>
									<?php _e( 'Starting with version 2.0, automatic imports are triggered by making a remote call to your website that triggers the imports. This decreases page loading time and improves the import process.', 'cvm_video' );?><br />
									<?php _e( 'Some systems may not allow this functionality. If you notice that your automatic import playlists aren\'t importing, enable this option.', 'cvm_video' );?>
                                </p></td>
                        </tr>
					</tbody>
				</table>
				<?php submit_button(__('Save settings', 'cvm_video'));?>
			</div>
			<!-- /Tab import options -->
			
			<!-- Tab embed options -->
			<div id="cvm-settings-embed-options">
				<table class="form-table cvm-player-settings-options">
					<tbody>
						<tr>
							<th colspan="2">
								<h4><i class="dashicons dashicons-leftright"></i> <?php _e('Player size and position', 'cvm_video');?></h4>
								<p class="description"><?php _e('General player size settings. These settings will be applied to any new video by default and can be changed individually for every imported video.', 'cvm_video');?></p>
							</th>
						</tr>

						<tr>
							<th><label for="cvm_js_embed"><?php _e('Embed by JavaScript', 'cvm_video');?>:</label></th>
							<td>
								<input type="checkbox" value="1" id="cvm_js_embed" name="js_embed"<?php Helper_Admin::check( (bool )$player_opt['js_embed'] );?> />
								<span class="description"><?php _e('When checked, single video embedding will be handled by plugin JavaScript. <br />If unchecked, the video iframe will be placed directly into the page.', 'cvm_video');?></span>
							</td>
						</tr>

                        <tr>
                            <th><label for="cvm_allow_override"><?php _e('Override individual posts options', 'cvm_video');?>:</label></th>
                            <td>
                                <input type="checkbox" value="1" id="cvm_allow_override" name="allow_override"<?php Helper_Admin::check( (bool )$player_opt['allow_override'] );?> />
                                <span class="description"><?php _e('When checked, individual post options for embedding videos will not be taken into account. Instead, the option set in this page will be used to embed videos on your website.', 'cvm_video');?></span>
                            </td>
                        </tr>

						<tr>
							<th><label for="cvm_aspect_ratio"><?php _e('Player size', 'cvm_video');?>:</label></th>
							<td>
								<label for="cvm_aspect_ratio"><?php _e('Aspect ratio');?> :</label>
								<?php 
									$args = [
										'name' 		=> 'aspect_ratio',
										'id'		=> 'cvm_aspect_ratio',
										'class'		=> 'cvm_aspect_ratio',
										'selected' 	=> $player_opt['aspect_ratio']
									];
									Helper_Admin::aspect_ratio_select( $args );
								?>
								<label for="cvm_width"><?php _e('Width', 'cvm_video');?> :</label>
								<input type="text" name="width" id="cvm_width" class="cvm_width" value="<?php echo $player_opt['width'];?>" size="2" />px
								| <?php _e('Height', 'cvm_video');?> : <span class="cvm_height" id="cvm_calc_height"><?php echo cvm_player_height( $player_opt['aspect_ratio'], $player_opt['width'] );?></span>px
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><label for="aspect_override"><?php _e('Allow videos to override player size', 'cvm_video')?>:</label></th>
							<td>
								<input type="checkbox" value="1" id="aspect_override" name="aspect_override"<?php Helper_Admin::check( (bool )$player_opt['aspect_override'] );?> />
								<span class="description"><?php _e('When checked (recommended), player will have the exact aspect ratio as retrieved from Vimeo. Player size option will be ignored.<br />Applies only to videos imported starting with plugin version 1.3.', 'cvm_video');?></span>
							</td>
						</tr>
						
						<tr>
							<th><label for="cvm_video_position"><?php _e('Display video','cvm_video');?>:</label></th>
							<td>
								<?php 
									$args = [
										'options' => [
											'above-content' => __('Above post content', 'cvm_video'),
											'below-content' => __('Below post content', 'cvm_video')
										],
										'name' 		=> 'video_position',
										'id'		=> 'cvm_video_position',
										'selected' 	=> $player_opt['video_position']
									];
									Helper_Admin::select( $args );
								?>
							</td>
						</tr>
						
						<tr>
							<th colspan="2">
								<h4><i class="dashicons dashicons-video-alt3"></i> <?php _e('Embed settings', 'cvm_video');?></h4>
								<p class="description"><?php _e('General Vimeo player settings. These settings will be applied to any new video by default and can be changed individually for every imported video.', 'cvm_video');?></p>
							</th>
						</tr>
						
						<tr>
							<th><label for="cvm_volume"><?php _e('Volume', 'cvm_video');?></label>:</th>
							<td>
								<input type="number" step="1" min="0" max="100" name="volume" id="cvm_volume" value="<?php echo $player_opt['volume'];?>" />
								<label for="cvm_volume"><span class="description">( <?php _e('number between 0 (mute) and 100 (max)', 'cvm_video');?> )</span></label>
							</td>
						</tr>

                        <tr valign="top">
                            <th scope="row"><label for="autoplay"><?php _e('Autoplay', 'cvm_video')?>:</label></th>
                            <td>
                                <input type="checkbox" value="1" id="autoplay" name="autoplay"<?php Helper_Admin::check( (bool )$player_opt['autoplay'] );?> />
                                <span class="description"><?php _e( 'Autoplay may be blocked in some environments, such as IOS, Chrome 66+, and Safari 11+. In these cases, Vimeo player revert to standard playback requiring viewers to initiate playback.', 'cvm_video' );?></span>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row"><label for="loop"><?php _e('Loop video', 'cvm_video')?>:</label></th>
                            <td><input type="checkbox" value="1" id="loop" name="loop"<?php Helper_Admin::check( (bool )$player_opt['loop'] );?> /></td>
                        </tr>

						<tr valign="top">
							<th scope="row"><label for="title"><?php _e('Show video title', 'cvm_video')?>:</label></th>
							<td><input type="checkbox" value="1" id="title" name="title"<?php Helper_Admin::check( (bool )$player_opt['title'] );?> /></td>
						</tr>
                        <?php /* //fullscreen option is deprecated and no longer supported by Vimeo player API
                        <tr valign="top">
                            <th scope="row"><label for="fullscreen"><?php _e('Allow fullscreen', 'cvm_video')?>:</label></th>
                            <td><input type="checkbox" name="fullscreen" id="fullscreen" value="1"<?php check( (bool)$player_opt['fullscreen'] );?> /></td>
                        </tr>
                        */?>
                        <tr valign="top">
                            <th scope="row"><label for="color"><?php _e('Player color', 'cvm_video')?>:</label></th>
                            <td>
                                #<input type="text" name="color" id="color" value="<?php echo $player_opt['color'];?>" />
                            </td>
                        </tr>

						<tr valign="top">
							<th scope="row"><label for="byline"><?php _e('Show video author', 'cvm_video')?>:</label></th>
							<td><input type="checkbox" value="1" id="byline" name="byline"<?php Helper_Admin::check( (bool )$player_opt['byline'] );?> /></td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><label for="portrait"><?php _e('Show author portrait', 'cvm_video')?>:</label></th>
							<td><input type="checkbox" value="1" id="portrait" name="portrait"<?php Helper_Admin::check( (bool )$player_opt['portrait'] );?> /></td>
						</tr>

					</tbody>
				</table>
				<?php submit_button(__('Save settings', 'cvm_video'));?>
			</div>	
			<!-- Tab embed options -->	
			
			<!-- Tab auth options -->
			<div id="cvm-settings-auth-options">
				<table class="form-table">
					<tbody>
						<tr>
							<th colspan="2">
								<h4><i class="dashicons dashicons-admin-network"></i> <?php _e('Vimeo oAuth keys', 'cvm_video');?></h4>
								<p class="description">
									<?php _e( 'In order to be able to make requests to Vimeo API, you must first have a Vimeo account and create the credentials.', 'cvm_video');?><br />
									<?php _e( 'To register your App, please visit <a target="_blank" href="https://developer.vimeo.com/apps/new">Vimeo App registration page</a> (requires login to Vimeo).', 'cvm_video')?><br />
									<?php printf( __( 'A step by step tutorial on how to create an app on Vimeo can be found %shere%s.', 'cvm_video' ), '<a href="' . Helper_Admin::docs_link( 'getting-started/vimeo-oauth-new-interface/' ) . '" target="_blank">', '</a>');?>
								</p>
								<p class="description">
									<?php printf( __( 'Make sure that you have set up the APP Callback URL to: <br /><span class="emphasize">%s</span><br /> in your APP settings on Vimeo.' , 'cvm_video' ), $this->vimeo_oauth->get_redirect_url() );?>
								</p>
							</th>
						</tr>
                        <?php if(empty( $options['vimeo_consumer_key'] ) || empty( $options['vimeo_secret_key'] )):?>
						<tr valign="top">
							<th scope="row"><label for="vimeo_consumer_key"><?php _e('Enter Vimeo Client Identifier', 'cvm_video')?>:</label></th>
							<td>
								<input type="text" name="vimeo_consumer_key" id="vimeo_consumer_key" value="<?php echo $options['vimeo_consumer_key'];?>" size="60" />
								<p class="description"><?php _e('You first need to create a Vimeo Account.', 'cvm_video');?></p>						
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="vimeo_secret_key"><?php _e('Enter Vimeo Client Secrets', 'cvm_video')?>:</label></th>
							<td>
								<input type="text" name="vimeo_secret_key" id="vimeo_secret_key" value="<?php echo $options['vimeo_secret_key'];?>" size="60" />
								<p class="description"><?php _e('You first need to create a Vimeo Account.', 'cvm_video');?></p>
							</td>
						</tr>
						<?php else:?>
						<tr valign="top">
                            <th scope="row"><label><?php _e('Plugin access to Vimeo account', 'cvm_video');?>:</label></th>
							<td>
                                <p>
                                    <?php _e( 'Your Vimeo keys are successfully installed.', 'cvm_video' );?>
                                    <?php $this->clear_oauth_credentials_link( __( 'Reset credentials', 'cvm_video' ), 'button cvm-danger' );?>
                                </p>
                                <p class="description">
                                    <?php _e( 'You can now query public videos on Vimeo and import them as WordPress posts.', 'cvm_video' );?>
                                </p>
                                <hr />

								<?php if( $authorize_url && !$options['vimeo_access_granted'] ):?>
								<p class="description">
									<?php _e('If you want to be able to import your private videos the plugin needs access to your Vimeo Account.', 'cvm_video');?><br />
									<?php _e('Access is limited only to reading your video feeds and details.', 'cvm_video');?>
								</p>
								<p><a class="cvm-ok button" href="<?php echo $authorize_url;?>"><?php _e('Authorize the plugin on Vimeo', 'cvm_video');?></a></p>
								<?php elseif( $unauthorize_url && $options['vimeo_access_granted']  ):?>
								<p>
									<?php _e('The plugin has access to your private videos.', 'cvm_video');?>									
								    <a class="cvm-danger button" href="<?php echo $unauthorize_url;?>"><?php _e('Remove authorization credentials', 'cvm_video');?></a>
                                </p>
                                    <p class="description">
                                        <?php _e( "By removing the authorization credentials you won't be able to query your private videos anymore.", 'cvm_video' );?>
                                    </p>
								<?php endif;?>
							</td>
						</tr>
                        <?php
	                        /**
	                         * Action that allows display of additional OAuth settings
	                         */
                            do_action( 'cvm_additional_oauth_settings_display' );
                            ;?>
                        <?php endif;?>
						<tr>
							<td colspan="2">
								<h4 id="cvm_license"><i class="dashicons dashicons-admin-network"></i> <?php _e('CodeFlavors License code', 'cvm_video');?></h4>
								<p class="description">
									<?php _e('All license keys purchased for this plugin can be found in your <a href="' . cvm_link( 'welcome-to-your-account/downloads-and-licenses/' ) . '">CodeFlavors account</a>.', 'cvm_video');?><br />
								</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="license_key"><?php _e('CodeFlavors license key', 'cvm_video')?>:</label></th>
							<td>
								<input type="text" name="license_key" id="license_key" value="<?php echo $options['license_key'];?>" size="60" />					
							</td>
						</tr>					
					</tbody>
				</table>
				<?php submit_button(__('Save settings', 'cvm_video'));?>
			</div>			
			<!-- /Tab auth options -->

			<?php foreach( $extra_tabs as $tab_id => $hook ):?>
                <div id="<?php esc_attr_e( $tab_id );?>" class="cvwp-panel hide-if-js">
					<?php call_user_func( $hook['callback'] );?>
                </div>
			<?php endforeach;?>

		</form>
	</div>
</div>