<?php
/**
 * @author CodeFlavors
 * @project codeflavors-vimeo-video-post-lite
 */

use Vimeotheque_Series\Series\Playlist;

/**
 * @var WP_Query $query
 * @var Playlist $playlist
 */
?>
<div class="vimeotheque-series playlist carousel flexslider">
	<ul class="slides">
		<?php while ( $query->have_posts() ) : ?>
			<?php
				/**
				 * Set the current post
				 */
				$query->the_post();
				$video = Vimeotheque\Helper::get_video_post( $query->post );
			?>
			<li>
				<div class="video-item">
					<?php the_title( '<div class="video-title">', '</div>' ); ?>

					<div class="video-duration">

						<?php echo esc_html( $video->_duration ); ?>

					</div><!-- .video-duration -->

					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<?php the_post_thumbnail( 'medium' ); ?>
					</a>
				</div>
			</li>
		<?php endwhile; ?>
	</ul>
</div>

