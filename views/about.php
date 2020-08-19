<?php
namespace Vimeotheque\Admin;

use Vimeotheque\Helper;
use function Vimeotheque\Admin\Helper_Admin\publisher_link;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="wrap about-wrap">
	<h1><?php printf( __( 'Welcome to Vimeotheque %s', 'codeflavors-vimeo-video-post-lite' ), VIMEOTHEQUE_VERSION ); ?></h1>
	<p class="about-text"><?php printf( __( 'Thank you for installing Vimeotheque %s, the plugin that gives you the possibility to automatically create WordPress posts from Vimeo searches, channels, user uploads or albums.', 'codeflavors-vimeo-video-post-lite' ), VIMEOTHEQUE_VERSION ); ?></p>

	<div class="changelog point-releases">
		<h3><?php _e( 'Maintenance Release' ); ?></h3>
		<p>
			<?php
			/* translators: %s: Codex URL */
			printf( __( 'For more information, see <a href="%s" target="_blank">the changelog</a>.' ), Helper_Admin::publisher_link( 'changelog/' ) );
			?>
		</p>
	</div>

	<div class="feature-section one-col">
		<div class="col">
			<h2><?php _e( 'Before getting started' ); ?></h2>
			<p class="lead-description"><?php _e( 'See how to set up Vimeotheque and start importing your Vimeo videos!', 'codeflavors-vimeo-video-post-lite' ); ?></p>

			<div id="cvm-video-preview" class="vimeotheque-player" data-volume="90" style="height: auto; width: 100%; max-width: 100%; overflow:hidden; background:#000000;">
                <?php Helper::embed_by_video_id('223879840' );?>
            </div>
			<p style="text-align:center;"><a href="https://vimeo.com/223879840" target="_blank"><?php _e( 'Watch on Vimeo', 'codeflavors-vimeo-video-post-lite' );?></a></p>
		</div>
	</div>

	<div class="return-to-dashboard">
		<a href="<?php menu_page_url( 'cvm_settings' ); ?>#cvm-settings-auth-options"><?php _e( 'Go to plugin Settings', 'codeflavors-vimeo-video-post-lite' ); ?></a> |
		<a href="<?php echo Helper_Admin::publisher_link('documents/getting-started/')?>" target="_blank"><?php _e('Online documentation', 'codeflavors-vimeo-video-post-lite');?></a> |
		<a href="<?php echo Helper_Admin::publisher_link( 'tickets/open-new-ticket/' );?>" target="_blank"><?php _e( 'Priority support', 'codeflavors-vimeo-video-post-lite' );?></a>
	</div>
</div>