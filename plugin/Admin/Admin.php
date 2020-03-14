<?php

namespace Horus\Admin;

use Horus\Admin\Metaboxes\Metabox_Example;
use Horus\Admin\Pages\Settings_Page;
use Horus\Admin\Post_Types\Post_Type_Example;
use Horus\Admin\Taxonomies\Taxonomy_Example;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link    https://OmarAli.dev
 * @package Horus
 * @author  Omar Ali <business@OmarAli.dev>
 */
class Admin
{
    /**
     * Hooked to the wp init action.
     * 
     * @since 1.0.0
     */
    public function init()
    {
        // add admin pages
        $this->add_admin_pages();
        // create new post type
        $this->register_post_types();
        // add custom taxonomies
        $this->register_taxonomies();
        // add metaboxes
        $this->add_meta_boxes();
    }

    /**
     * Registers custom post types.
     * 
     * @since 1.0.0
     */
    public function register_post_types()
    {
        // Register your post types e.g
        // Post_Type_Example::register();
    }

    /**
     * Registers custom taxonomies.
     * 
     * @since 1.0.0
     */
    public function register_taxonomies()
    {
        // Register your custom Taxonomies  e.g
        // Taxonomy_Example::instance()->register(['example-post-type']);
    }

    /**
     * Add metaboxes.
     * 
     * @since 1.0.0
     */
    public function add_meta_boxes()
    {
        // Register your metaboxes e.g
        // new Metabox_Example('metabox_example', ['example-post-type']);
    }

    /**
     * Add menu pages.
     * Hooked to wp admin_menu action
     * 
     * @since 1.0.0
     */
    public function add_admin_pages()
    {
        // Register Admin Pages
        new Settings_Page(
            'ox-settings', // page uid
            __('Settings', 'horus'), // page title
            __('Horus', 'horus') // menu title
        );
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        // wp_enqueue_style('admin-main-style', ox_plugin_assets_url() . 'css/admin/main.css');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script('admin-main-script', ox_plugin_assets_url() . 'js/admin/admin-main.js', ['jquery'], OX_PLUGIN_VERSION, true);

        wp_localize_script('admin-main-script', 'ox_l10n', [
            'plugin_name' => OX_PLUGIN_NAME,
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce()
        ]);
    }
}
