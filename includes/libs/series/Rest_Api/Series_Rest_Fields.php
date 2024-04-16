<?php
/**
 * @author  CodeFlavors
 * @project vimeotheque-series
 */

namespace Vimeotheque_Series\Rest_Api;

use Vimeotheque_Series\Plugin;

if (!defined('ABSPATH')) {
    die();
}

class Series_Rest_Fields{

    public function __construct(){

        add_action( 'rest_api_init', [$this, 'rest_api_init'] );
    }

    public function rest_api_init(){
        $fields = [
            'content_type' => '',
            'shuffle' => false,
            'items' => [],
            'theme' => 'default',
            'volume' => 30,
            'width' => 1200
        ];

        foreach ( $fields as $field => $default_value ) {
            register_rest_field(
                'series',
                $field,
                [
                    'get_callback' => function( $object ) use ($default_value, $field){
                        $meta = get_post_meta( $object['id'], $field, true );

                        // Cast numeric values to number to avoid issues in JavaScript.
                        if( is_numeric($default_value ) && $meta ){
                            $meta = (int) $meta;
                        }

                        /**
                         * If not already set, set the default value.
                         */
                        if( !$meta ){
                            update_post_meta($object['id'], $field, $default_value );
                        }

                        return $meta ?: $default_value;
                    },
                    /**
                     * @param array $value      The new value
                     * @param \WP_Post $object  The post object
                     * @param string $field_name The field name
                     */
                    'update_callback' => function( $value, \WP_Post $object, $field_name ) use ($field){
                        update_post_meta( $object->ID, $field_name, $value );
                    }
                ]
            );
        }

        register_rest_field(
            'series',
            'theme_details',
            [
                'get_callback' => function( $object ){
                    $_theme = get_post_meta( $object['id'], 'theme', true );
                    $theme = false;
                    if( $_theme ){
                        $theme = Plugin::instance()->get_themes_manager()->get_theme($_theme);
                    }

                    return $theme;
                }
            ]
        );

        register_rest_field(
            'series',
            'edit_link',
            [
                'get_callback' => function( $object ){
                    $link = false;
                    if( current_user_can( 'edit_post', $object['id'] ) ){
                        $link = get_edit_post_link( $object['id'], 'edit' );
                    }

                    return $link;
                }
            ]
        );

    }

}