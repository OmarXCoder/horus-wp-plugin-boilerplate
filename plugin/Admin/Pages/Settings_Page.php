<?php

namespace Horus\Admin\Pages;

use Horus\Admin\Contracts\Admin_Page;
use Horus\Admin\Admin_Settings;

/**
 * Responsible for displaying settings page
 * 
 * @since   1.0.0
 * @package Horus
 * @author  Omar Ali <business@OmarAli.dev>
 */
class Settings_Page extends Admin_Page
{

    public function __construct($page_slug, $page_title, $menu_title = null, $parent_page = null)
    {
        parent::__construct($page_slug, $page_title, $menu_title, $parent_page);
        add_action('wp_loaded', [$this, 'save_settings']);
    }

    public function add_to_menu()
    {
        add_menu_page(
            $this->get_page_title(),
            $this->get_menu_title(),
            'manage_options',
            $this->get_page_slug(),
            [$this, 'display'],
            'dashicons-admin-generic',
            3
        );
    }

    public function display()
    {
        Admin_Settings::render();
    }

    /**
     * Handle saving of the settings.
     */
    public function save_settings()
    {
        global $current_tab, $current_section;
        // We should only save on the settings page.
        if (!is_admin() || !isset($_GET['page']) || 'ox-settings' !== $_GET['page']) {
            return;
        }

        // Include settings pages.
        Admin_Settings::init_settings_tabs();

        // Get current tab/section.
        $current_tab     = !empty($_GET['tab']) ? sanitize_title(wp_unslash($_GET['tab'])) : '';
        $current_section = !isset($_REQUEST['section']) || empty($_REQUEST['section']) ? '' : sanitize_title(wp_unslash($_REQUEST['section']));


        if (!empty($_POST['save'])) {
            Admin_Settings::save();
        }
    }
}
