<?php

/**
 * Fired when the plugin is being uninstalled.
 *
 * @since   1.0.0
 * @package Horus
 * @author  Omar Ali <business@OmarAli.dev>
 */

// If uninstall not called from WordPress, then exit.
defined('WP_UNINSTALL_PLUGIN') || exit;

/*
 * Register The Auto Loader
*/
require __DIR__ . '/vendor/autoload.php';

/**
 * Removes all plugin settings from the 
 * wp_options table
 */
\Horus\Core\Settings::instance()->deleteAll();

/**
 * Drop all custom plugin tables from the DB
 */
\Horus\Core\DB_Schema::instance()->drop();
