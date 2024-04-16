<?php
/**
 * @author  CodeFlavors
 * @project vimeotheque-series
 */

namespace Vimeotheque_Series\Admin\Metabox;

use Vimeotheque_Series\Admin\Metabox\Abstract_Metabox;
use Vimeotheque_Series\Admin\Metabox\Metabox_Interface;
use Vimeotheque_Series\Helper;

if (!defined('ABSPATH')) {
    die();
}

class Theme_Metabox extends Abstract_Metabox implements Metabox_Interface {

    /**
     * @inheritDoc
     */
    public function content () {
        $this->load_assets();
?>
<div id="vimeotheque-series-theme"><!-- The React App for the series theme --></div>
<?php
    }

    private function load_assets () {

        /**
         * Theme scripts embed action.
         *
         */
        do_action(
            'vimeotheque-series\before-theme-script-embed'
        );

        $script_handle = Helper::enqueue_script(
            'theme',
            ['wp-element', 'wp-editor', 'lodash'],
            true
        );

        /**
         * Theme scripts embed action.
         *
         * @param string $script_handle The theme script handle.
         */
        do_action(
                'vimeotheque-series\after-theme-script-embed',
            $script_handle
        );

    }


}