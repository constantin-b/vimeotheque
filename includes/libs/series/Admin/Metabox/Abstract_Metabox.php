<?php
/**
 * @author  CodeFlavors
 * @project vimeotheque-series
 */

namespace Vimeotheque_Series\Admin\Metabox;

if (!defined('ABSPATH')) {
    die();
}

class Abstract_Metabox{
    /**
     * Meta box ID.
     *
     * @var string
     */
    private $id;
    /**
     * Meta box name.
     *
     * @var string
     */
    private $name;
    /**
     * Meta box screen.
     *
     * @var string
     */
    private $screen;
    /**
     * Meta box position.
     *
     * @var string
     */
    private $context;

    /**
     * Constructor
     *
     * Initiate meta box.
     *
     * @param string $id
     * @param string $name
     * @param string $screen
     * @param string $context
     */
    public function __construct( string $id, string $name, string $screen, string $context = 'normal' ){
        $this->id = $id;
        $this->name = $name;
        $this->screen = $screen;
        $this->context = $context;
    }

    /**
     * Initiate the meta box.
     *
     * @return void
     */
    public function initiate_metabox () {
        add_meta_box(
            $this->get_id(),
            $this->get_name(),
            [ $this, 'content' ],
            $this->get_screen(),
            $this->get_context()
        );
    }

    /**
     * Get the meta box ID.
     *
     * @return string
     */
    public function get_id (): string {
        return $this->id;
    }

    /**
     * Get the meta box name.
     *
     * @return string
     */
    public function get_name (): string {
        return $this->name;
    }

    /**
     * Get the meta box screen.
     *
     * @return string
     */
    public function get_screen (): string {
        return $this->screen;
    }

    /**
     * Get the meta box position.
     *
     * @return string
     */
    public function get_context (): string {
        return $this->context;
    }
}