<?php

/**
 * Plugin Name:       Horus wp plugin boilerplate
 * Plugin URI:        https://github.com/omarxcoder/horus-wp-plugin-boilerplate
 * Description:       Wordpress plugin boilerplate
 * Version:           1.0.0
 * Author:            Omar Ali
 * Author URI:        https://omarali.dev
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       horus
 * Domain Path:       /i18n/languages
 */

// If this file is called directly, abort.
defined('ABSPATH') || exit;

/**
 * Currently plugin version.
 */
define('OX_PLUGIN_NAME', 'horus');

/**
 * Currently plugin version.
 */
define('OX_PLUGIN_VERSION', '1.0.0');

/**
 * Currently DB version.
 */
define('OX_DB_VERSION', '1.0.0');

/**
 * Minimum required PHP version.
 */
define('MINIMUM_PHP_VERSION', '5.6');

/**
 * Minimum required WordPress version.
 */
define('MINIMUM_WP_VERSION', '4.7');

/**
 * Plugin __FILE__.
 */
define('OX_PLUGIN__FILE__', __FILE__);

/**
 * Plugin root path.
 */
define('OX_PLUGIN_PATH', plugin_dir_path(OX_PLUGIN__FILE__));

/**
 * Plugin root URL.
 */
define('OX_PLUGIN_URL', plugins_url('/', OX_PLUGIN__FILE__));


/**
 * Admin directory path.
 */
define('OX_ADMIN_PATH', OX_PLUGIN_PATH . 'plugin/Admin/');

/**
 * Frontend directory path.
 */
define('OX_FRONTEND_PATH', OX_PLUGIN_PATH . 'plugin/Frontend/');

/**
 * Global prefix used to in places
 * where naming conflects
 * are expected
 */
define('OX_PREFIX', 'ox_');


/*
 * Register The Auto Loader
*/
require __DIR__ . '/vendor/autoload.php';

/*
 * Bootstrap the plugin
*/
$plugin = require_once OX_PLUGIN_PATH . 'plugin/bootstrap.php';

/**
 * Begins execution of the plugin.
 */
$plugin->run();
