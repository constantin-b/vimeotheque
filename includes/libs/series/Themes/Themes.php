<?php
/**
 * @author  CodeFlavors
 * @project vimeotheque-series
 */

namespace Vimeotheque_Series\Themes;

if (!defined('ABSPATH')) {
    die();
}

class Themes{
    /**
     * Registered themes.
     *
     * @var Theme[]
     */
    private $themes = [];

    /**
     * @param Theme $theme
     */
    public function __construct( Theme $theme ) {
        $this->register_theme( $theme );
    }

    /**
     * Register a theme.
     *
     * @param  Theme $theme
     * @return void
     */
    public function register_theme( Theme $theme ) {
        $this->themes[ $theme->get_folder_name() ] = $theme;
        $theme->load_functions();
    }

    /**
     * Returns all registered themes.
     *
     * @return Theme[]
     */
    public function get_themes (): array {
        return $this->themes;
    }

    /**
     * Returns a theme object if registered.
     *
     * @param  string $theme The theme folder name.
     * @return Theme|void
     */
    public function get_theme ( string $theme ) {
        if( isset( $this->themes[ $theme ] ) ){
            return $this->themes[ $theme ];
        }
    }
}