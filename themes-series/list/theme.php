<?php
/**
 * @author  CodeFlavors
 * @project vimeotheque-series
 */

use Vimeotheque_Series\Series\Playlist;
use function Vimeotheque_Series\Theme_List\css_classes;
use function Vimeotheque_Series\Theme_List\has_modal;
use function Vimeotheque_Series\Theme_List\the_image;

/**
 * @var WP_Query $query
 * @var Playlist $playlist
 */
?>
<div class="<?php css_classes( $playlist, 'vimeotheque-series playlist list', true ); ?>" data-shuffle="<?php echo esc_attr( $playlist->shuffle ); ?>" data-modal="<?php echo (int) has_modal( $playlist ); ?>">

	<?php while ( $query->have_posts() ) : ?>

		<?php
		/**
		 * Set the current post
		 */
		$query->the_post();
		$video = Vimeotheque\Helper::get_video_post( $query->post );
		?>

		<?php if ( 0 == $query->current_post ) : ?>

	<div class="video-items">

		<?php endif; ?>

		<div
				class="video-item <?php echo ! has_post_thumbnail() ? 'no-thumbnail' : 'with-thumbnail'; ?>"
				data-video_id="<?php echo esc_attr( $video->video_id ); ?>"
				data-size_ratio="<?php echo esc_attr( $video->size['ratio'] ); ?>"
				data-embed_url="<?php echo esc_url( $video->get_embed_url() ); ?>"
				data-title="<?php echo esc_attr( get_the_title() ); ?>"
		>

			<?php the_title( '<div class="video-title">', '</div>' ); ?>

			<div class="video-duration">

				<?php echo esc_html( $video->_duration ); ?>

			</div><!-- .video-duration -->

			<?php the_image( $playlist, 'large' ); ?>

		</div><!-- .video-item -->

		<?php endwhile; // End loop ?>

	</div><!--.video-items-->

</div><!-- .vimeotheque-series -->
