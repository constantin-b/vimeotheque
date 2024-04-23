<?php
/**
 * @author CodeFlavors
 * @project vimeotheque-series
 */

namespace Vimeotheque_Series\Admin\Metabox;

use Vimeotheque_Series\Helper;

if (!defined('ABSPATH')) {
    die();
}

class Player_Metabox extends Abstract_Metabox implements Metabox_Interface {

    /**
     * @inheritDoc
     */
    public function content () {
        $this->load_assets();
        ?>
        <div id="vimeotheque-series-player"><!-- The React App for the series player --></div>
        <?php
    }

    private function load_assets () {

        $script_handle = Helper::enqueue_script(
            'player',
            ['wp-element', 'wp-editor', 'lodash'],
            true
        );
    }
}