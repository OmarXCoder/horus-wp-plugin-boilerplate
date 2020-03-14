<?php

namespace Horus\Core;

defined('ABSPATH') || exit('Forbidden!');

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since   1.0.0
 * @package Horus
 * @author  Omar Ali <business@OmarAli.dev>
 */
class I18n
{

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain()
    {
        load_plugin_textdomain(
            'horus',
            false,
            OX_PLUGIN_PATH . 'i18n/languages'
        );
    }
}
