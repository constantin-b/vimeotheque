<?php
/**
 * @author CodeFlavors
 * @project vimeotheque-series
 */

namespace Vimeotheque_Series\Admin\Metabox;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Shortcode_Metabox extends Abstract_Metabox implements Metabox_Interface {

	/**
	 * @inheritDoc
	 */
	public function content() {
			$post = get_post();

		switch ( $post->post_status ) {
			case 'publish':
				$message = esc_html_e( 'Copy the shortcode and paste it into other posts or pages to show this playlist.' );
				break;
			default:
				$message = esc_html_e( 'You must publish the playlist for the shortcode to display it.' );
				break;
		}
		?>

			<p>
				<?php echo $message; ?>
			</p>
			<input type="text" readonly="readonly" value="[vimeotheque_series id='<?php esc_attr_e( $post->ID ); ?>']" onclick="this.select()" style="width: 100%;" />

		<?php
	}
}
