<?php
namespace Vimeotheque;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<p>
	<?php _e('Import frequency is basically how often Vimeo will be queried for new videos that might have been published into a playlist.', 'cvm_video');?><br />
	<?php _e('To change the import frequency just visit Settings page in plugin menu and modify the option <em>Automatic import</em>.', 'cvm_video');?>	
</p>
<p>
	<?php _e('Please note that only one playlist is update with each query made to Vimeo.', 'cvm_video');?><br />
	<?php _e('This means that for each period of time set under Automatic import 20 videos will be retrieved from the playlist that comes next in line.', 'cvm_video');?><br />
</p>
<p>
	<?php _e("Please note that if videos from a playlist are already imported the plugin won't create double posts. All duplicates will be skipped but the import count for that playlist will get updated.", 'cvm_video' );?>
</p>