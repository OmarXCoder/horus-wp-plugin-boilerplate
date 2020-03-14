<?php

namespace Horus\Admin\Contracts;

/**
 * Custom taxonomy abstract class
 * 
 * @since   1.0.0
 * @package Horus
 * @author  Omar Ali <business@OmarAli.dev>
 */
abstract class Taxonomy
{
    /**
     * Taxonomy instance
     * @var Taxonomy $instance
     */
    protected static $instance;

    protected function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public static function instance()
    {
        static::$instance = new static;

        return static::$instance;
    }

    public function enqueue_scripts($hook)
    {
    }

    /**
     * Registers the taxonomy
     * 
     * @param array $post_types this taxonomy is for
     */
    abstract public function register($post_types = []);
}
