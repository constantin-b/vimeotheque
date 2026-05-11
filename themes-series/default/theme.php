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

$show_title   = get_post_meta( $playlist->post->ID, 'show_title', true );
$show_content = get_post_meta( $playlist->post->ID, 'show_content', true );
if ( '' === $show_title )   { $show_title   = 'yes'; }
if ( '' === $show_content ) { $show_content = 'yes'; }
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

                <?php if ( 'yes' === $show_title || 'yes' === $show_content ) : ?>
                <div class="vimeotheque-featured-info">
                    <?php if ( 'yes' === $show_title ) : ?>
                    <div class="featured-video-title"><?php the_title(); ?></div>
                    <?php endif; ?>
                    <?php if ( 'yes' === $show_content ) : ?>
                    <div class="featured-video-content">
                        <div class="content-inner"><?php echo wp_strip_all_tags( get_the_content() ); ?></div>
                        <button class="see-more-toggle"
                            data-see-more="<?php echo esc_attr__( 'See more', 'codeflavors-vimeo-video-post-lite' ); ?>"
                            data-see-less="<?php echo esc_attr__( 'See less', 'codeflavors-vimeo-video-post-lite' ); ?>"
                        ><?php echo esc_html__( 'See more', 'codeflavors-vimeo-video-post-lite' ); ?></button>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

            <?php endif; // end check for current post ?>

            <?php if( 0 == $query->current_post ) :?>

            <div class="video-items">

            <?php endif ;?>

                <div
                        class="video-item <?php echo !has_post_thumbnail() ? 'no-thumbnail' : 'with-thumbnail' ?>"
                        data-video_id="<?php echo esc_attr($video->video_id ) ?>"
                        data-size_ratio="<?php echo esc_attr($video->size['ratio'] ) ?>"
                        <?php if ( 'yes' === $show_title ) : ?>data-featured_title="<?php echo esc_attr( get_the_title() ); ?>"<?php endif; ?>
                        <?php if ( 'yes' === $show_content ) : ?>data-featured_content="<?php echo esc_attr( wp_strip_all_tags( get_the_content() ) ); ?>"<?php endif; ?>
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