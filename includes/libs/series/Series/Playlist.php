<?php
/**
 * @author CodeFlavors
 * @project vimeotheque-series
 */

namespace Vimeotheque_Series\Series;

use Vimeotheque\Video_Post;
use Vimeotheque_Series\Plugin;
use WP_Post;

if (!defined('ABSPATH')) {
    die();
}

class Playlist {
    /**
     * The type of content used by the playlist.
     *
     * @var string
     */
    public $content_type;
    /**
     * Series shuffling enabled or disabled.
     *
     * @var bool
     */
    public $shuffle;
    /**
     * The video posts IDs.
     *
     * @var Video_Post[]
     */
    public $items;
    /**
     * The theme folder.
     *
     * @var string
     */
    public $theme;
    /**
     * Player volume.
     *
     * @var int
     */
    public $volume;
    /**
     * Maximum player width.
     *
     * @var int
     */
    public $width;
    /**
     * @var WP_Post
     */
    public $post;

    /**
     * Constructor.
     *
     * @param int|WP_Post $post    The "series" post.
     */
    public function __construct( $post ) {
        $this->post = get_post( $post );

        $this->initialize();
    }

    /**
     * Initialize the fields.
     *
     * @return void
     */
    private function initialize() {

        $this->content_type = $this->get_meta( 'content_type' );
        $this->shuffle = $this->get_meta( 'shuffle', false );
        $this->items = $this->get_meta( 'items', [] );
        $this->theme = $this->get_meta( 'theme', 'default' );
        $this->volume = $this->get_meta( 'volume', 30 );
        $this->width = $this->get_meta( 'width', 1200 );

    }

    /**
     * Process the post content.
     *
     * @return \WP_Query
     */
    private function query_items() {

        $load = apply_filters(
            'vimeotheque-series\load_playlist',
            !empty( $this->content_type ) && $this->items,
            $this->post->ID,
            $this
        );

        if( !$load || is_wp_error( $load ) ){
            $error = is_wp_error($load) ? $load : new \WP_Error();

            if( empty( $this->content_type ) ){
                $error->add(
                    'vimeotheque-series-no-content-selected',
                    esc_html__( 'No content type selected for the playlist.', 'codeflavors-vimeo-video-post-lite' )
                );
            }else if( 'posts' == $this->content_type && !$this->items ){
                $error->add(
                    'vimeotheque-series-no-videos-selected',
                    esc_html__( 'No videos selected for the playlist.', 'codeflavors-vimeo-video-post-lite' )
                );
            }else{
                $error->add(
                    'vimeotheque-series-playlist-not-loaded',
                    esc_html__('Playlist not loaded.', 'codeflavors-vimeo-video-post-lite' )
                );
            }

            $error = apply_filters(
                'vimeotheque-series\playlist\display-errors',
                $error,
                $this->post->ID,
                $this
            );

            return $error;
        }

        $args = [
            'post_type' => 'vimeo-video',
            'post__in' => $this->items,
            'orderby' => 'post__in',
            'posts_per_page' => count( $this->items ),
            'ignore_sticky_posts' => true
        ];

        /**
         * Run a  filter of the args to allow third party plugins to change the query.
         *
         * @param array $args
         * @param WP_Post $post
         */
        $args = apply_filters(
            'vimeotheque_series\playlist_items_query_args',
            $args,
            $this->post->ID,
            $this
        );

        $query = new \WP_Query( $args );

        return $query;
    }

    /**
     * Get a post meta.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed|string
     */
    private function get_meta( string $key, $default = '' ){
        $meta = get_post_meta( $this->post->ID, $key, true );

        return $meta ?: $default;
    }

    /**
     * Returns the playlist content.
     *
     * @return false|string
     */
    public function get_content() {

        $result = '';
        $query = $this->query_items();

        if( is_wp_error( $query ) ){

            if( current_user_can( 'edit_post', $this->post->ID ) ){
                $result = sprintf(
                    '<hr /><h3>%s</h3>',
                    esc_html__(
                        'Playlist not loaded',
                        'codeflavors-vimeo-video-post-lite'
                    )
                );
                $result .= sprintf(
                    '<p>%s</p>',
                    $query->get_error_message()
                );

                $result .= sprintf(
                    '<p><em>%s</em></p><hr />',
                    esc_html__(
                        'This notice is displayed only for playlist editors!',
                        'codeflavors-vimeo-video-post-lite'
                    )
                );
            }

        }else{

            $playlist = $this;
            $theme = Plugin::instance()->get_themes_manager()->get_theme( $this->theme );

            // Enqueue assets
            \Vimeotheque\Helper::enqueue_player();
            $theme->enqueue_script();
            $theme->enqueue_style();

            // Output buffer
            ob_start();

            // Get the theme display file.
            include $theme->get_file();

            // Get the content
            $result = ob_get_contents();
            ob_end_clean();

            // Restore initial post data.
            wp_reset_postdata();
        }

        return $result;
    }

}