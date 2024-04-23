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

class Rest_Endpoint_Factory {
    /**
     * @var Rest_Controller_Interface[]
     */
    private $endpoints;

    /**
     * Rest_Endpoint_Factory constructor.
     */
    public function __construct() {
        add_action(
            'rest_api_init',
            [$this, 'init']
        );
    }

    /**
     * Initialize endpoints
     */
    public function init() {
        // Add support for the post title when making rest queries.
        add_post_type_support( Plugin::instance()->get_post_type()->get_post_name(), 'title' );

        $this->store_endpoint( new Rest_Themes_Controller() );
    }

    /**
     * Store the endpoint.
     *
     * @param Rest_Controller_Interface $controller
     */
    public function store_endpoint( Rest_Controller_Interface $controller ) {
        $this->endpoints[ $controller->get_rest_base() ] = $controller;
    }

    /**
     * Get all endpoints.
     *
     * @return Rest_Controller_Interface[]
     */
    public function get_endpoints(): array {
        return $this->endpoints;
    }

    /**
     * Get an endpoint.
     *
     * @param $rest_base
     *
     * @return Rest_Controller_Interface|null
     */
    public function get_endpoint( $rest_base ) {
        return $this->endpoints[ $rest_base ] ?? null;
    }
}