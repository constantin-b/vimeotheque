<?php
namespace Vimeotheque;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<p>
	<?php _e('Depending on the WordPress theme used, the plugin can import videos directly as posts. Currently supported themes are DeTube and Avada.', 'cvm_video');?><br />
	<?php _e('To import videos as posts the first requirement is to have any of the supported themes installed and activated.', 'cvm_video');?><br />
	<?php _e('If the plugin detects the theme, on all import pages (single post, manual import and automatic import) it will display a new option asking if it should do the import as regular post.', 'cvm_video');?><br />
	<?php _e('Any videos imported for the theme will not be available under All videos, instead they will display under Posts.', 'cvm_video');?>
</p>