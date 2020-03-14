<?php

/**
 * Core functions necessary for the plugin functionality
 * 
 * @since   1.0.0
 * @package Horus
 * @author  Omar Ali <business@OmarAli.dev>
 */
defined('ABSPATH') || exit('Forbidden!');

use Horus\Core\Settings;


/**
 * Gets a setting group as an object or
 * a setting value if $key provided
 * 
 * @param string $group_name settings group name
 * @param string $key name of a single setting
 * @param string $default
 * @return mixed
 */
function ox_get_setting($group_name, $key = null, $default = null)
{
    if (!$group_name) {
        return null;
    }

    static $instance = null;

    if (is_null($instance)) {
        $instance = Settings::instance();
    }

    $group = $instance->get($group_name);

    if ($key && is_array($group) && array_key_exists($key, $group)) {
        return $group[$key];
    } elseif ($key) {
        return $default;
    } elseif ($group) {
        return $group;
    }

    return $default;
}


function ox_plugin_assets_url()
{
    return defined('WP_DEBUG') && WP_DEBUG === true ? OX_PLUGIN_URL . 'src/' : OX_PLUGIN_URL . 'dist/';
}
