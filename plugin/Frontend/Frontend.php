<?php

namespace Horus\Frontend;

use Horus\Core\AJAX_Router;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 * 
 * @link       https://OmarAli.dev
 * @package Horus
 * @author  Omar Ali <business@OmarAli.dev>
 */
class Frontend
{
    /**
     * Hooked to the wp init action.
     * 
     * @since 1.0.0
     */
    public function init()
    {
        // Register AJAX routes
        AJAX_Router::AddRoutes();
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        // wp_enqueue_style('frontend-main-style', ox_plugin_assets_url() . 'css/frontend/main.css');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script('main-script', ox_plugin_assets_url() . 'js/frontend/frontend-main.js', ['jquery'], OX_PLUGIN_VERSION, true);

        wp_localize_script('main-script', 'ox_l10n', [
            'plugin_name' => OX_PLUGIN_NAME,
            'ajax_url'    => admin_url('admin-ajax.php'),
            'nonce'       => wp_create_nonce(),
            'text'        => []
        ]);
    }
}
