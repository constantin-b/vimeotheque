<?php
/**
 * @author CodeFlavors
 * @project codeflavors-vimeo-video-post-lite
 */

namespace Vimeotheque\Admin\Page;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Extensions_Page extends Page_Abstract implements Page_Interface{

	/**
	 * Outputs the page
	 *
	 * @return string|void
	 */
	public function get_html() {
		$extensions = parent::get_admin()->get_extensions()->get_registered_extensions();
?>
<div class="wrap vimeotheque vimeotheque-addons-wrap">
	<h1><?php _e( 'Add-ons', 'codeflavors-vimeo-video-post-lite' );?></h1>
	<div class="container">
	<?php foreach( $extensions as $extension ):?>
	<?php
		$classes = ['extension'];
		$classes[] = $extension->is_installed() ? 'is-installed' : 'not-installed';
		$classes[] = $extension->is_activated() ? 'active' : 'inactive';
		$classes[] = $extension->is_pro_addon() ? 'pro-addon' : 'free-addon';
	?>
		<div class="<?php echo implode( ' ', $classes );?>">
            <div class="inside">
                <h2>
                    <?php echo $extension->get_name() ?>
	                <?php if( $extension->is_pro_addon() ):?>
                        <div class="pro-emblem">PRO</div>
	                <?php endif;?>
                </h2>
                <p><?php echo $extension->get_description();?></p>
                <?php
                    if( !$extension->is_installed() ){
                        printf(
                            '<a class="button" href="%s">%s</a>',
                            $extension->install_url(),
                            __( 'Install', 'codeflavors-vimeo-video-post-lite' )
                        );
                    }elseif( !$extension->is_activated() ){
                        printf(
                            '<a class="button" href="%s">%s</a>',
                            $extension->activation_url(),
                            __( 'Activate', 'codeflavors-vimeo-video-post-lite' )
                        );
                    }else{ // extensiton is active, show deactivation option
                        printf(
	                        '<a class="button" href="%s">%s</a>',
	                        $extension->deactivation_url(),
	                        __( 'Deactivate', 'codeflavors-vimeo-video-post-lite' )
                        );
                    }
                ?>
            </div>
		</div>
	<?php endforeach;?>
	</div>
</div>
<?php
	}

	/**
	 * Page on load event
	 *
	 * @return mixed|void
	 */
	public function on_load() {
		wp_enqueue_style( 'vimeotheque-extensions-css', VIMEOTHEQUE_URL . 'assets/back-end/css/extensions.css' );
	}
}