<?php

/**
 * The plugin bootstrap file
 * 
 * registers the activation and deactivation functions, and returns
 * an instance of the plugin class.
 * 
 * @since   1.0.0
 * @package Horus
 * @author  Omar Ali <business@OmarAli.dev>
 */
defined('ABSPATH') || exit('Forbidden!');

/**
 * Load the functions files
 */
require_once OX_PLUGIN_PATH . 'plugin/functions.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in Core/Activator.php
 */
register_activation_hook(OX_PLUGIN__FILE__, function () {
    Horus\Core\Activator::activate();
});

/**
 * The code that runs during plugin deactivation.
 * This action is documented in Core/Deactivator.php
 */
register_deactivation_hook(OX_PLUGIN__FILE__, function () {
    Horus\Core\Deactivator::deactivate();
});


/**
 * return a plugin instance
 */
return new Horus\Core\Plugin();
