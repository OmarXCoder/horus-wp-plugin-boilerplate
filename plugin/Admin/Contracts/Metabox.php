<?php

namespace Horus\Admin\Contracts;

/**
 * The base metabox abstract class
 * 
 * holdes the common functionality for metaboxes
 * 
 * @since   1.0.0
 * @package Horus
 * @author  Omar Ali <business@OmarAli.dev>
 */
abstract class Metabox
{
    /**
     * The meta key to be used to register
     * this metabox data with add_post_meta
     */
    protected $meta_key;

    /**
     * Security nonce used in the edit form
     */
    protected $nonce;

    /**
     * The screens where this meatabox can show
     * 
     * @var array $screens
     */
    protected $screens;

    /**
     * The title of the metabox
     * 
     * @var string $title
     */
    protected $title;

    /**
     * the context on the screen can be 'normal' 'side' and others
     * 
     * @var string $context
     */
    protected $context;

    /**
     * the priority of the metabox
     * 
     * @var string $priority
     */
    protected $priority;

    public function __construct($meta_key = null, $screens = array(), $title = '', $context = '', $priority = '')
    {
        $this->meta_key = $meta_key;
        $this->screens  = $screens;
        $this->title    = $title;
        $this->context  = $context;
        $this->priority = $priority;

        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));

        add_action('add_meta_boxes', array($this, 'register'));

        add_action('save_post', array($this, 'save_data'));
    }

    /**
     * meta_key getter
     * 
     * @return string $this->meta_key
     */
    public function get_meta_key()
    {
        return $this->meta_key;
    }

    /**
     * nonce getter
     * 
     * @return string $this->nonce
     */
    public function get_nonce($suffix = '_nonce')
    {
        return $this->meta_key . $suffix;
    }

    /**
     * Add a gallery metabox to specified post types
     * 
     * @package Horus Toolbox
     * @since 1.0.0
     */
    public function enqueue_scripts($hook)
    {
    }

    /**
     * Adds the gallery metabox to the specified post type
     * 
     * @var string $post_type the type of the post being edited
     */
    public function register($post_type)
    {
        if (in_array($post_type, $this->screens)) {
            add_meta_box(
                $this->get_meta_key(),
                __($this->title, 'horus'),
                [$this, 'render'],
                $post_type,
                $this->context,
                $this->priority
            );
        }
    }

    /**
     * Renders the metabox in the backend
     * 
     * @var WP_Post $post the post being created/updated
     */
    abstract public function render($post);

    /**
     * Associate metabox data with the post meta
     * 
     * @var int $post_id the post id being created/updated
     */
    abstract public function save_data($post_id);
}
