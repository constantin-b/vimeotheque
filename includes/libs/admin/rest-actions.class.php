<?php

namespace Vimeotheque\Admin;

use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use WP_Error;
use Vimeotheque\Helper;
use Vimeotheque\Post\Post_Type;
use Vimeotheque\Video_Import;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * REST replacements for old admin-ajax actions.
 *
 * cvm_get_videos → GET /vimeotheque/v1/videos
 * cvm_import_video → POST /vimeotheque/v1/video/import
 * cvm_import_video_thumbnail → POST /vimeotheque/v1/video/(?P<id>\d+)/thumbnail
 * cvm_import_videos (bulk list view) → POST /vimeotheque/v1/videos/bulk-import
 *
 * @author CodeFlavors
 */
class Rest_Actions {

    /**
     * @var Post_Type
     */
    private Post_Type $cpt;

    public function __construct( Post_Type $object ) {
        $this->cpt = $object;

        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
    }

    /**
     * Register REST routes.
     */
    public function register_routes() {
        $namespace = 'vimeotheque/v1';

        // GET videos from Vimeo API (manual bulk import grid)
        register_rest_route(
            $namespace,
            '/videos',
            [
                'methods'             => [ WP_REST_Server::READABLE, WP_REST_Server::CREATABLE ],
                'callback'            => [ $this, 'vimeo_api_query' ],
                'permission_callback' => [ $this, 'can_edit_posts' ],
                'args'                => [
                    'page'               => [
                        'type'    => 'integer',
                        'default' => 1,
                    ],
                    'cvm_order'          => [
                        'type'     => 'string',
                        'required' => false,
                    ],
                    'cvm_search_results' => [
                        'type'     => 'string',
                        'required' => false,
                    ],
                    'cvm_feed'           => [
                        'type'     => 'string',
                        'required' => true,
                    ],
                    'cvm_query'          => [
                        'type'     => 'string',
                        'required' => true,
                    ],
                    'cvm_album_user'     => [
                        'type'     => 'string',
                        'required' => false,
                    ],
                ],
            ]
        );

        // Import a single video (grid view bulk import)
        register_rest_route(
            $namespace,
            '/video/import',
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => [ $this, 'import_video' ],
                'permission_callback' => [ $this, 'can_edit_posts' ],
                'args'                => [
                    'model' => [
                        'required' => true,
                    ],
                ],
            ]
        );

        // Import thumbnail as featured image
        register_rest_route(
            $namespace,
            '/video/(?P<id>\d+)/thumbnail',
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => [ $this, 'import_thumbnail' ],
                'permission_callback' => [ $this, 'can_upload_files' ],
                'args'                => [
                    'id'       => [
                        'type'     => 'integer',
                        'required' => true,
                    ],
                    'refresh'  => [
                        'type'    => 'boolean',
                        'default' => false,
                    ],
                    'gutenberg' => [
                        'type'     => 'boolean',
                        'required' => false,
                    ],
                ],
            ]
        );

        // Bulk import videos from List view
        register_rest_route(
            $namespace,
            '/videos/bulk-import',
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => [ $this, 'bulk_import_videos' ],
                'permission_callback' => [ $this, 'can_edit_posts' ],
                'args'                => [
                    'cvm_import' => [
                        'required' => true,
                        'type'     => 'array',
                    ],
                    // opțional, dacă vrei să păstrezi noțiunea de "action_top"/"action2"
                    'action_top' => [
                        'type'     => 'string',
                        'required' => false,
                    ],
                    'action2' => [
                        'type'     => 'string',
                        'required' => false,
                    ],
                ],
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    */

    public function can_edit_posts() : bool {
        return current_user_can( 'edit_posts' );
    }

    public function can_upload_files() : bool {
        return current_user_can( 'upload_files' );
    }

    /*
    |--------------------------------------------------------------------------
    | Callbacks (portate de pe admin-ajax)
    |--------------------------------------------------------------------------
    */

    /**
     * GET /vimeotheque/v1/videos
     */
    public function vimeo_api_query( WP_REST_Request $request ) {
        $args = [
            'page'     => (int) $request->get_param( 'page' ) ?: 1,
            'order'    => $request->get_param( 'cvm_order' ),
            'query'    => trim( (string) $request->get_param( 'cvm_search_results' ) ),
            'per_page' => 50,
        ];

        $feed        = $request->get_param( 'cvm_feed' );
        $query_str   = $request->get_param( 'cvm_query' );
        $album_user  = $request->get_param( 'cvm_album_user' );

        Helper::debug_message(
            sprintf(
                'Initiating manual bulk import query for resource type "%s" with query string "%s".',
                $feed,
                $query_str
            )
        );

        $query  = new Video_Import( $feed, $query_str, $album_user, $args );
        $videos = $query->get_feed();

        if ( is_wp_error( $query->get_errors() ) ) {
            $error = $query->get_errors();

            Helper::debug_message(
                sprintf(
                    'Manual bulk import Vimeo API query generated error: "%s".',
                    $error->get_error_message()
                )
            );

            return new WP_Error(
                'vimeo_api_error',
                $error->get_error_message(),
                [ 'status' => 503 ]
            );
        }

        $response = [
            'results' => $query->get_total_items(),
            'page'    => $query->get_page(),
            'end'     => $query->has_ended(),
            'videos'  => $videos,
        ];

        Helper::debug_message(
            sprintf(
                'Manual bulk import Vimeo API query success (entries per page: %d; current page: %d; total entries: %d)',
                $args['per_page'],
                $query->get_page(),
                $query->get_total_items()
            )
        );

        return new WP_REST_Response( $response, 200 );
    }

    /**
     * POST /vimeotheque/v1/video/(?P<id>\d+)/thumbnail
     */
    public function import_thumbnail( WP_REST_Request $request ) {
        $post_id   = (int) $request->get_param( 'id' );
        $refresh   = (bool) $request->get_param( 'refresh' );
        $gutenberg = (bool) $request->get_param( 'gutenberg' );

        if ( ! $post_id ) {
            return new WP_Error(
                'invalid_post_id',
                __( 'Invalid post ID.', 'codeflavors-vimeo-video-post-lite' ),
                [ 'status' => 400 ]
            );
        }

        /**
         * Funcțiile de admin pentru thumbnail HTML nu sunt încărcate în context REST,
         * așa că le includem manual.
         */
        if ( ! function_exists( '_wp_post_thumbnail_html' ) ) {
            require_once ABSPATH . 'wp-admin/includes/post.php';
        }

        if ( ! function_exists( 'get_upload_iframe_src' ) ) {
            require_once ABSPATH . 'wp-admin/includes/media.php';
        }

        $thumbnail = Helper::get_video_post( $post_id )->set_featured_image( $refresh );

        if ( ! $thumbnail ) {
            return new WP_Error(
                'image_not_retrieved',
                __( 'Image could not be retrieved.', 'codeflavors-vimeo-video-post-lite' ),
                [
                    'status'  => 500,
                    'details' => [
                        'error' => [ __( 'Image could not be retrieved.', 'codeflavors-vimeo-video-post-lite' ) ],
                    ],
                ]
            );
        }

        if ( $gutenberg ) {
            // Dacă ai un format special pentru Gutenberg, îl poți păstra aici
            $html = $thumbnail;
        } else {
            // HTML pentru meta box "Featured Image" din admin clasic
            $html = _wp_post_thumbnail_html( $thumbnail['attachment_id'], $thumbnail['post_id'] );
        }

        return new WP_REST_Response(
            [
                'success' => true,
                'data'    => $html,
            ],
            200
        );
    }

    /**
     * POST /vimeotheque/v1/videos/bulk-import
     */
    public function bulk_import_videos( WP_REST_Request $request ) {
        $videos = (array) $request->get_param( 'cvm_import' );

        $response = [
            'total'    => count( $videos ),
            'imported' => 0,
            'skipped'  => 0,
            'private'  => 0,
            'error'    => 0,
            'success'  => false,
        ];

        $action_top = $request->get_param( 'action_top' );
        $action2    = $request->get_param( 'action2' );

        // Menținem același comportament: rulează doar dacă acțiunea e "import"
        if ( 'import' === $action_top || 'import' === $action2 ) {
            foreach ( $videos as $video ) {
                $result = $this->cpt
                    ->get_plugin()
                    ->get_posts_importer()
                    ->run_import(
                        [ Helper::query_video( $video ) ],
                        $request->get_params()
                    );

                $response['imported'] += (int) $result['imported'];
                $response['skipped']  += (int) $result['skipped'];
                $response['private']  += (int) $result['private'];
                $response['error']    += (int) $result['error'] ?? 0;
            }
        }

        $response['success'] = sprintf(
            __(
                '%1$d videos: %2$d imported, %3$d skipped, %4$d private, %5$d error.',
                'codeflavors-vimeo-video-post-lite'
            ),
            $response['total'],
            $response['imported'],
            $response['skipped'],
            $response['private'],
            $response['error']
        );

        return new WP_REST_Response( $response, 200 );
    }

    /**
     * POST /vimeotheque/v1/video/import
     */
    public function import_video( WP_REST_Request $request ) {
        $model = $request->get_param( 'model' );

        if ( ! $model ) {
            return new WP_Error(
                'invalid_model',
                __( 'Missing model data.', 'codeflavors-vimeo-video-post-lite' ),
                [ 'status' => 400 ]
            );
        }

        // Dacă nu se trimit explicit opțiunile de import, folosește opțiunile globale
        if ( empty( $model['import'] ) ) {
            $import_options = \Vimeotheque\Plugin::instance()->get_options();
        } else {
            $import_options = $model['import'];
        }

        $results = $this->cpt
            ->get_plugin()
            ->get_posts_importer()
            ->run_import(
                [ $model ],
                $import_options
            );

        if ( ! empty( $results['imported'] ) ) {
            $results['links'] = [];

            if ( ! empty( $results['ids'] ) ) {
                foreach ( $results['ids'] as $post_id ) {
                    $results['links'][] = [
                        'edit_link' => get_edit_post_link( $post_id ),
                        'permalink' => get_permalink( $post_id ),
                    ];
                }
            }

            return new WP_REST_Response( $results, 200 );
        }

        // Dacă sunt erori, normalizează-le în stringuri
        if ( ! empty( $results['error'] ) ) {
            foreach ( $results['error'] as $k => $error ) {
                if ( $error instanceof WP_Error ) {
                    $results['error'][ $k ] = $error->get_error_message();
                }
            }
        }

        return new WP_Error(
            'import_failed',
            __( 'Video import failed.', 'codeflavors-vimeo-video-post-lite' ),
            [
                'status'  => 409,
                'details' => $results,
            ]
        );
    }
}
