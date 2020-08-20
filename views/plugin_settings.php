<?php
namespace Vimeotheque\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Vimeotheque\Admin\Page\Settings_Page;
use Vimeotheque\Helper;

/**
 * @var Settings_Page $this
 * @var array $extra_tabs
 * @var array $options
 * @var array $player_opt
 * @var string $authorize_url
 * @var string $unauthorize_url
 */
?>
<div class="wrap">
	<div class="icon32" id="icon-options-general"><br></div>
	<h2><?php _e('Videos - Plugin settings', 'codeflavors-vimeo-video-post-lite');?></h2>
	<div id="cvm_tabs">
		<form method="post" action="">
			<?php wp_nonce_field('cvm-save-plugin-settings', 'cvm_wp_nonce');?>
			<ul class="cvm-tab-labels">
				<li><a href="#cvm-settings-post-options"><i class="dashicons dashicons-arrow-right"></i> <?php _e('Post options', 'codeflavors-vimeo-video-post-lite')?></a></li>
				<li><a href="#cvm-settings-content-options"><i class="dashicons dashicons-arrow-right"></i> <?php _e('Content options', 'codeflavors-vimeo-video-post-lite')?></a></li>
				<li><a href="#cvm-settings-image-options"><i class="dashicons dashicons-arrow-right"></i> <?php _e('Image options', 'codeflavors-vimeo-video-post-lite')?></a></li>
				<li><a href="#cvm-settings-import-options"><i class="dashicons dashicons-arrow-right"></i> <?php _e('Import options', 'codeflavors-vimeo-video-post-lite')?></a></li>
				<li><a href="#cvm-settings-embed-options"><i class="dashicons dashicons-arrow-right"></i> <?php _e('Embed options', 'codeflavors-vimeo-video-post-lite')?></a></li>
				<li><a href="#cvm-settings-auth-options"><i class="dashicons dashicons-arrow-right"></i> <?php _e('API & License', 'codeflavors-vimeo-video-post-lite')?></a></li>
				<?php foreach( $extra_tabs as $tab_id => $hook ):?>
                    <li><a href="#<?php esc_attr_e( $tab_id );?>"><i class="dashicons dashicons-arrow-right"></i> <?php esc_attr_e( $hook['title'] );?></a></li>
				<?php endforeach;?>
			</ul>
			
			<!-- Tab post options -->
			<div id="cvm-settings-post-options">
				<table class="form-table">
					<tbody>
						<!-- Import type -->
						<tr>
                            <th colspan="2">
                                <h4><i class="dashicons dashicons-admin-tools"></i> <?php _e('General settings', 'codeflavors-vimeo-video-post-lite');?></h4>
                            </th>
                        </tr>

                        <tr valign="top">
                            <th scope="row"><label for="archives"><?php _e('Embed videos in archive pages', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
                            <td>
                                <input type="checkbox" name="archives" value="1" id="archives"<?php Helper_Admin::check( $options['archives'] );?> />
                                <span class="description">
									<?php _e('When checked, videos will be visible on all pages displaying lists of video posts.', 'codeflavors-vimeo-video-post-lite');?>
								</span>
                            </td>
                        </tr>

                        <?php
                            /**
                             * Action that allows other settings to be displayed in page
                             */
                            do_action( 'vimeotheque\admin\general_settings_section' );
                        ?>

						<!-- Visibility -->
						<tr><th colspan="2"><h4><i class="dashicons dashicons-video-alt3"></i> <?php _e('Video post type options', 'codeflavors-vimeo-video-post-lite');?></h4></th></tr>
						<tr valign="top">
							<th scope="row"><label for="public"><?php _e('Video post type is public', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
							<td>
								<input type="checkbox" name="public" value="1" id="public"<?php Helper_Admin::check( $options['public'] );?> />
								<span class="description">
								<?php if( !$options['public'] ):?>
									<span style="color:red;"><?php _e('Videos cannot be displayed in front-end. You can only incorporate them in playlists or display them in regular posts using shortcodes.', 'codeflavors-vimeo-video-post-lite');?></span>
								<?php else:?>
								<?php _e('Videos will display in front-end as post type video are and can also be incorporated in playlists or displayed in regular posts.', 'codeflavors-vimeo-video-post-lite');?>
								<?php endif;?>
								</span>
							</td>
						</tr>

						<?php
						/**
						 * Action that allows other settings to be displayed in page
						 */
						do_action( 'vimeotheque\admin\post_type_section' );
						?>

                        <!-- Rewrite settings -->
						<tr>
							<th colspan="2">
								<h4><i class="dashicons dashicons-admin-links"></i> <?php _e('Video post type rewrite (pretty links)', 'codeflavors-vimeo-video-post-lite');?></h4>
								<p class="description">
									<?php _e( "Please make sure that you don't insert the same slug twice.", 'codeflavors-vimeo-video-post-lite' );?>
								</p>
							</th>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="post_slug"><?php _e('Post slug', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
							<td>
								<input type="text" id="post_slug" name="post_slug" value="<?php echo $options['post_slug'];?>" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="taxonomy_slug"><?php _e('Taxonomy slug', 'codeflavors-vimeo-video-post-lite')?> :</label></th>
							<td>
								<input type="text" id="taxonomy_slug" name="taxonomy_slug" value="<?php echo $options['taxonomy_slug'];?>" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="tag_slug"><?php _e('Tags slug', 'codeflavors-vimeo-video-post-lite')?> :</label></th>
							<td>
								<input type="text" id="tag_slug" name="tag_slug" value="<?php echo $options['tag_slug'];?>" />
							</td>
						</tr>
					</tbody>
				</table>
				<?php submit_button(__('Save settings', 'codeflavors-vimeo-video-post-lite'));?>
			</div>
			<!-- /Tab post options -->	
			
			<!-- Tab content options -->
			<div id="cvm-settings-content-options">
				<table class="form-table">
					<tbody>
						<!-- Content settings -->
						<tr>
                            <th colspan="2">
                                <h4><i class="dashicons dashicons-admin-post"></i> <?php _e('Post content settings', 'codeflavors-vimeo-video-post-lite');?></h4>
                            </th>
                        </tr>
						<tr valign="top">
							<th scope="row"><label for="import_date"><?php _e('Import date', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
							<td>
								<input type="checkbox" value="1" name="import_date" id="import_date"<?php Helper_Admin::check($options['import_date']);?> />
								<span class="description"><?php _e("Imports will have Vimeo's publishing date.", 'codeflavors-vimeo-video-post-lite');?></span>
							</td>
						</tr>	
						
						<tr valign="top">
							<th scope="row"><label for="import_title"><?php _e('Import titles', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
							<td>
								<input type="checkbox" value="1" id="import_title" name="import_title"<?php Helper_Admin::check($options['import_title']);?> />
								<span class="description"><?php _e('Automatically import video titles from feeds as post title.', 'codeflavors-vimeo-video-post-lite');?></span>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><label for="import_tags"><?php _e('Import tags', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
							<td>
								<input type="checkbox" value="1" id="import_tags" name="import_tags"<?php Helper_Admin::check($options['import_tags']);?> />
								<span class="description"><?php _e('Automatically import video tags as post tags from feeds.', 'codeflavors-vimeo-video-post-lite');?></span>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><label for="max_tags"><?php _e('Number of tags', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
							<td>
								<input type="text" value="<?php echo $options['max_tags'];?>" id="max_tags" name="max_tags" size="1" />
								<span class="description"><?php _e('Maximum number of tags that will be imported.', 'codeflavors-vimeo-video-post-lite');?></span>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><label for="import_description"><?php _e('Import descriptions as', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
							<td>
								<?php 
									$args = [
										'options' => [
											'content' 			=> __('post content', 'codeflavors-vimeo-video-post-lite'),
											'excerpt' 			=> __('post excerpt', 'codeflavors-vimeo-video-post-lite'),
											'content_excerpt' 	=> __('post content and excerpt', 'codeflavors-vimeo-video-post-lite'),
											'none'				=> __('do not import', 'codeflavors-vimeo-video-post-lite')
										],
										'name' => 'import_description',
										'selected' => $options['import_description']
									];
									Helper_Admin::select( $args );
								?>
								<p class="description"><?php _e('Import video description from feeds as post description, excerpt or none.', 'codeflavors-vimeo-video-post-lite')?></p>
							</td>
						</tr>

						<?php
						/**
						 * Action that allows other settings to be displayed in page
						 */
						do_action( 'vimeotheque\admin\content_options_section' );
						?>

					</tbody>
				</table>
				<?php submit_button(__('Save settings', 'codeflavors-vimeo-video-post-lite'));?>
			</div>
			<!-- /Tab content options -->
			
			<!-- Tab image options -->
			<div id="cvm-settings-image-options">
				<table class="form-table">
					<tbody>
						<tr>
                            <th colspan="2">
                                <h4><i class="dashicons dashicons-format-image"></i> <?php _e('Image settings', 'codeflavors-vimeo-video-post-lite');?></h4>
                            </th>
                        </tr>
						<tr valign="top">
							<th scope="row"><label for="featured_image"><?php _e('Import images', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
							<td>
								<input type="checkbox" value="1" name="featured_image" id="featured_image"<?php Helper_Admin::check($options['featured_image']);?> />
								<span class="description"><?php _e("Vimeo video thumbnail will be set as post featured image.", 'codeflavors-vimeo-video-post-lite');?></span>
							</td>
						</tr>

						<?php
						/**
						 * Action that allows other settings to be displayed in page
						 */
						do_action( 'vimeotheque\admin\image_options_section' );
						?>
					</tbody>
				</table>
				<?php submit_button(__('Save settings', 'codeflavors-vimeo-video-post-lite'));?>
			</div>
			<!-- /Tab image options -->
			
			<!-- Tab import options -->
			<div id="cvm-settings-import-options">
				<table class="form-table">
					<tbody>
						<!-- Manual Import settings -->
						<tr>
                            <th colspan="2">
                                <h4><i class="dashicons dashicons-download"></i> <?php _e('Bulk Import settings', 'codeflavors-vimeo-video-post-lite');?></h4>
                            </th>
                        </tr>
						<tr valign="top">
							<th scope="row"><label for="import_status"><?php _e('Import status', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
							<td>
								<?php 
									$args = [
										'options' => [
											'publish' 	=> __('Published', 'codeflavors-vimeo-video-post-lite'),
											'draft' 	=> __('Draft', 'codeflavors-vimeo-video-post-lite'),
											'pending'	=> __('Pending', 'codeflavors-vimeo-video-post-lite')
										],
										'name' 		=> 'import_status',
										'selected' 	=> $options['import_status']
									];
									Helper_Admin::select( $args );
								?>
								<p class="description"><?php _e('Imported videos will have this status.', 'codeflavors-vimeo-video-post-lite');?></p>
							</td>
						</tr>

                        <?php
                            do_action( 'vimeotheque\admin\import_options_section' );
                        ?>

					</tbody>
				</table>
				<?php submit_button(__('Save settings', 'codeflavors-vimeo-video-post-lite'));?>
			</div>
			<!-- /Tab import options -->
			
			<!-- Tab embed options -->
			<div id="cvm-settings-embed-options">
				<table class="form-table cvm-player-settings-options">
					<tbody>
						<tr>
							<th colspan="2">
								<h4><i class="dashicons dashicons-leftright"></i> <?php _e('Player size and position', 'codeflavors-vimeo-video-post-lite');?></h4>
                                <p class="description"><?php _e('General player size settings. These settings will be applied to any new video by default and can be changed individually for every imported video.', 'codeflavors-vimeo-video-post-lite');?></p>
							</th>
						</tr>

						<tr>
							<th><label for="cvm_aspect_ratio"><?php _e('Player size', 'codeflavors-vimeo-video-post-lite');?>:</label></th>
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
								<label for="cvm_width"><?php _e('Width', 'codeflavors-vimeo-video-post-lite');?> :</label>
								<input type="text" name="width" id="cvm_width" class="cvm_width" value="<?php echo $player_opt['width'];?>" size="2" />px
								| <?php _e('Height', 'codeflavors-vimeo-video-post-lite');?> : <span class="cvm_height" id="cvm_calc_height"><?php echo Helper::calculate_player_height( $player_opt['aspect_ratio'], $player_opt['width'] );?></span>px
							</td>
						</tr>

                        <tr>
                            <th><label for="cvm_video_position"><?php _e('Display video','codeflavors-vimeo-video-post-lite');?>:</label></th>
                            <td>
								<?php
								$args = [
									'options' => [
										'above-content' => __('Above post content', 'codeflavors-vimeo-video-post-lite'),
										'below-content' => __('Below post content', 'codeflavors-vimeo-video-post-lite')
									],
									'name' 		=> 'video_position',
									'id'		=> 'cvm_video_position',
									'selected' 	=> $player_opt['video_position']
								];
								Helper_Admin::select( $args );
								?>
                            </td>
                        </tr>

                        <?php
                            do_action( 'vimeotheque\admin\embed_options_section' );
                        ?>

                        <tr>
							<th colspan="2">
								<h4><i class="dashicons dashicons-video-alt3"></i> <?php _e('Embed settings', 'codeflavors-vimeo-video-post-lite');?></h4>
								<p class="description"><?php _e('General Vimeo player settings. These settings will be applied to any new video by default and can be changed individually for every imported video.', 'codeflavors-vimeo-video-post-lite');?></p>
							</th>
						</tr>
						
						<tr>
							<th><label for="cvm_volume"><?php _e('Volume', 'codeflavors-vimeo-video-post-lite');?></label>:</th>
							<td>
								<input type="number" step="1" min="0" max="100" name="volume" id="cvm_volume" value="<?php echo $player_opt['volume'];?>" />
								<label for="cvm_volume"><span class="description">( <?php _e('number between 0 (mute) and 100 (max)', 'codeflavors-vimeo-video-post-lite');?> )</span></label>
							</td>
						</tr>

                        <tr valign="top">
                            <th scope="row"><label for="autoplay"><?php _e('Autoplay', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
                            <td>
                                <input type="checkbox" value="1" id="autoplay" name="autoplay"<?php Helper_Admin::check( (bool )$player_opt['autoplay'] );?> />
                                <span class="description"><?php _e( 'Autoplay may be blocked in some environments, such as IOS, Chrome 66+, and Safari 11+. In these cases, Vimeo player revert to standard playback requiring viewers to initiate playback.', 'codeflavors-vimeo-video-post-lite' );?></span>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row"><label for="loop"><?php _e('Loop video', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
                            <td><input type="checkbox" value="1" id="loop" name="loop"<?php Helper_Admin::check( (bool )$player_opt['loop'] );?> /></td>
                        </tr>

						<tr valign="top">
							<th scope="row"><label for="title"><?php _e('Show video title', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
							<td><input type="checkbox" value="1" id="title" name="title"<?php Helper_Admin::check( (bool )$player_opt['title'] );?> /></td>
						</tr>


                        <tr valign="top">
                            <th scope="row"><label for="cvm_color"><?php _e('Player color', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
                            <td>
                                <input type="text" name="color" id="cvm_color" value="<?php echo $player_opt['color'];?>" />
                            </td>
                        </tr>

						<tr valign="top">
							<th scope="row"><label for="byline"><?php _e('Show video author', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
							<td><input type="checkbox" value="1" id="byline" name="byline"<?php Helper_Admin::check( (bool )$player_opt['byline'] );?> /></td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><label for="portrait"><?php _e('Show author portrait', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
							<td><input type="checkbox" value="1" id="portrait" name="portrait"<?php Helper_Admin::check( (bool )$player_opt['portrait'] );?> /></td>
						</tr>

					</tbody>
				</table>
				<?php submit_button(__('Save settings', 'codeflavors-vimeo-video-post-lite'));?>
			</div>	
			<!-- Tab embed options -->	
			
			<!-- Tab auth options -->
			<div id="cvm-settings-auth-options">
				<table class="form-table">
					<tbody>
						<tr>
							<th colspan="2">
								<h4><i class="dashicons dashicons-admin-network"></i> <?php _e('Vimeo oAuth keys', 'codeflavors-vimeo-video-post-lite');?></h4>
                                <p class="description">
									<?php _e( 'In order to be able to make requests to Vimeo API, you must first have a Vimeo account and create the credentials.', 'codeflavors-vimeo-video-post-lite');?><br />
									<?php _e( 'To register your App, please visit <a target="_blank" href="https://developer.vimeo.com/apps/new">Vimeo App registration page</a> (requires login to Vimeo).', 'codeflavors-vimeo-video-post-lite')?><br />
									<?php printf( __( 'A step by step tutorial on how to create an app on Vimeo can be found %shere%s.', 'codeflavors-vimeo-video-post-lite' ), '<a href="' . Helper_Admin::docs_link( 'getting-started/vimeo-oauth-new-interface/' ) . '" target="_blank">', '</a>');?>
								</p>
								<p class="description">
									<?php printf( __( 'Make sure that you have set up the APP Callback URL to: <br /><span class="emphasize">%s</span><br /> in your APP settings on Vimeo.' , 'codeflavors-vimeo-video-post-lite' ), $this->vimeo_oauth->get_redirect_url() );?>
								</p>
							</th>
						</tr>
                        <?php if(empty( $options['vimeo_consumer_key'] ) || empty( $options['vimeo_secret_key'] )):?>
						<tr valign="top">
							<th scope="row"><label for="vimeo_consumer_key"><?php _e('Enter Vimeo Client Identifier', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
							<td>
								<input type="text" name="vimeo_consumer_key" id="vimeo_consumer_key" value="<?php echo $options['vimeo_consumer_key'];?>" size="60" />
								<p class="description"><?php _e('You first need to create a Vimeo Account.', 'codeflavors-vimeo-video-post-lite');?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="vimeo_secret_key"><?php _e('Enter Vimeo Client Secrets', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
							<td>
								<input type="text" name="vimeo_secret_key" id="vimeo_secret_key" value="<?php echo $options['vimeo_secret_key'];?>" size="60" />
								<p class="description"><?php _e('You first need to create a Vimeo Account.', 'codeflavors-vimeo-video-post-lite');?></p>
							</td>
						</tr>
						<?php else:?>
						<tr valign="top">
                            <th scope="row"><label><?php _e('Plugin access to Vimeo account', 'codeflavors-vimeo-video-post-lite');?>:</label></th>
							<td>
                                <p>
                                    <?php _e( 'Your Vimeo keys are successfully installed.', 'codeflavors-vimeo-video-post-lite' );?>
                                    <?php $this->clear_oauth_credentials_link( __( 'Reset credentials', 'codeflavors-vimeo-video-post-lite' ), 'button cvm-danger' );?>
                                </p>
                                <p class="description">
                                    <?php _e( 'You can now query public videos on Vimeo and import them as WordPress posts.', 'codeflavors-vimeo-video-post-lite' );?>
                                </p>
                                <hr />
								<?php
                                    /**
                                     * Action that allows display of additional OAuth settings
                                     */
                                    do_action( 'vimeotheque\admin\api_oauth_section' );
								?>
							</td>
						</tr>
                            <?php
                            /**
                             * Action that allows display of additional OAuth settings
                             */
                            do_action( 'vimeotheque\admin\api_oauth_settings_extra' );
                            ?>
                        <?php endif;?>
						<?php
                            /**
                             * Action that allows display of additional OAuth settings
                             */
                            do_action( 'vimeotheque\admin\credentials_section' );
						?>
					</tbody>
				</table>
				<?php submit_button(__('Save settings', 'codeflavors-vimeo-video-post-lite'));?>
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