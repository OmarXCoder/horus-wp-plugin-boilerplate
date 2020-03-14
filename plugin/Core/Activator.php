<?php

namespace Horus\Core;

use Horus\Admin\Admin_Settings;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since   1.0.0
 * @package Horus
 * @author  Omar Ali <business@OmarAli.dev>
 */
defined('ABSPATH') || exit('Forbidden!');

class Activator
{

    /**
     * Activates the plugin if its requirements are met.
     * 
     * Only activate the plugin if the underlying system has
     * the minimum PHP and WordPress versions and the active
     * theme is Pyramids theme
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        self::check_php_version_compatibility();
        self::check_wp_version_compatibility();

        self::init_db();
        self::add_default_options();
    }

    /**
     * Check PHP version compatibility with the Horus plugin
     *
     * @since 1.0.0
     *
     * @return void
     */
    private static function check_php_version_compatibility()
    {
        if (!version_compare(PHP_VERSION, MINIMUM_PHP_VERSION, '>=')) {
            $message = sprintf(
                __('Pyramids Toolbox requires PHP version %s+. Because you are using an earlier version, the plugin is currently <strong>NOT RUNNING</strong>.', 'horus'),
                MINIMUM_PHP_VERSION
            );
            wp_die($message, 'PHP Version Compatibility');
        }
    }

    /**
     *  Check WordPress version compatibility with the Horus plugin
     *
     * @since 1.0.0
     *
     * @return void
     */
    private static function check_wp_version_compatibility()
    {
        if (!version_compare(get_bloginfo('version'), MINIMUM_WP_VERSION, '>=')) {
            $message = sprintf(
                __('Pyramids Toolbox requires WordPress version %s+. Because you are using an earlier version, the plugin is currently <strong>NOT RUNNING</strong>.', 'horus'),
                MINIMUM_WP_VERSION
            );
            wp_die($message, "WordPress Version Compatibility");
        }
    }

    private static function init_db()
    {
        // uncomment the following line if you want to create your DB Schema
        // DB_Schema::instance()->create();
    }

    /**
     * Default options.
     *
     * Sets up the default options used on the settings page.
     */
    private static function add_default_options()
    {
        Admin_Settings::add_default_options();
    }
}
