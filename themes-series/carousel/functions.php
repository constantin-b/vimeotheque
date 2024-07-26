<?php
/**
 * @author CodeFlavors
 * @project codeflavors-vimeo-video-post-lite
 */

namespace Vimeotheque_Series\Theme_Carousel;

use Vimeotheque_Series\Helper;

add_action(
    'vimeotheque-series/before-enqueue-script',
    function(){

        wp_enqueue_script(
            'vimeotheque-series-theme-carousel-editor',
            Helper::get_url() . 'themes-series/carousel/assets/js/editor.js',
            ['wp-element', 'wp-editor', 'lodash'],
            Helper::get_version()
        );

    }

);