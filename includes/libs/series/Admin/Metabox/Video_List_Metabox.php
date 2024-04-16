<?php
/**
 * @author  CodeFlavors
 * @project vimeotheque-series
 */

namespace Vimeotheque_Series\Admin\Metabox;

use Vimeotheque_Series\Helper;

if (!defined('ABSPATH')) {
    die();
}

class Video_List_Metabox extends Abstract_Metabox implements Metabox_Interface {

    /**
     * @return void
     */
    public function content(){
        $this->load_assets();
?>
<div id="vimeotheque-series-videos"><!-- The React App for selecting videos --></div>
<?php
    }

    private function load_assets(){

        do_action(
            'vimeotheque-series/before-enqueue-script'
        );

        $script_handle = Helper::enqueue_script(
            'video-list',
            ['wp-element', 'wp-editor', 'lodash'],
            true
        );

        do_action(
            'vimeotheque-series/after-enqueue-script',
            $script_handle
        );

        $style_handle = Helper::enqueue_style(
            'series-admin',
            ['wp-components']
        );

        do_action(
            'vimeotheque-series/enqueue-styles',
            $style_handle
        );
    }
}