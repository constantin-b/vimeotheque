<?php
/**
 * @author  CodeFlavors
 * @project vimeotheque-series
 */

use Vimeotheque_Series\Series\Playlist;
use function Vimeotheque_Series\Theme_Default\css_classes;

/**
 * @var WP_Query $query
 * @var Playlist $playlist
 */
?>
<div class="<?php css_classes( $playlist, 'vimeotheque-series playlist default', true )?>">

    <?php while( $query->have_posts() ): ?>

        <?php
            /**
             * Set the current post
             */
            $query->the_post();
            $video = Vimeotheque\Helper::get_video_post( $query->post );
        ?>

            <?php if( 0 == $query->current_post ) :?>

                <?php
                    Vimeotheque\Helper::embed_video(
                            $query->post,
                            [
                                'video_align'=>'align-center',
                                'width'=> $playlist->width,
                                'playlist_loop' => 1,
                                'transparent'=> false,
                                'shuffle' => $playlist->shuffle
                            ]
                    )
                ?>

            <?php endif; // end check for current post ?>

            <?php if( 0 == $query->current_post ) :?>

            <div class="video-items">

            <?php endif ;?>

                <div
                        class="video-item <?php echo !has_post_thumbnail() ? 'no-thumbnail' : 'with-thumbnail' ?>"
                        data-video_id="<?php echo esc_attr($video->video_id ) ?>"
                        data-size_ratio="<?php echo esc_attr($video->size['ratio'] ) ?>"
                >

                    <?php the_title( '<div class="video-title">', '</div>' ) ?>

                    <div class="video-duration">

                        <?php echo esc_html( $video->_duration );?>

                    </div><!-- .video-duration -->

                    <?php the_post_thumbnail( 'large' ); ?>

                </div><!-- .video-item -->

    <?php endwhile; // End loop ?>

            </div><!--.video-items-->

</div><!-- .vimeotheque-series -->