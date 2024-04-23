<?php
/**
 * @author  CodeFlavors
 * @project vimeotheque-series
 */

namespace Vimeotheque_Series\Rest_Api;

interface Rest_Controller_Interface {
    /**
     * Returns the Rest API base of the resource.
     *
     * @return string
     */
    public function get_rest_base(): string;

    /**
     * Returns Rest namespace.
     *
     * @return string
     */
    public function get_namespace(): string;
    /**
     * Route registration
     *
     * @return void
     */
    public function register_routes();

    /**
     * Returns endpoint route.
     *
     * @return string
     */
    public function get_route(): string;
}