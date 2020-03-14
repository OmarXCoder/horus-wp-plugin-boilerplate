<?php

namespace Horus\Admin\Contracts;

use Horus\Admin\Admin_Settings;
use Horus\Admin\Contracts\Settings_Api;

/**
 * Admin settings Tab
 * 
 * @since   1.0.0
 * @package Horus
 * @author  Omar Ali <business@OmarAli.dev>
 */
abstract class Settings_Tab extends Settings_Api
{
    /**
     * default section id
     * @var string
     */
    protected $default_section_id;
    /**
     * default section label
     * @var string
     */
    protected $default_section_label;

    public function __construct()
    {
        add_filter('ox_settings_tabs_array', [$this, 'add_settings_tab'], 20);
        add_action("ox_sections_{$this->id}", [$this, 'render_sections_nav']);
        add_action("ox_settings_{$this->id}", [$this, 'render']);
        add_action("ox_settings_save_{$this->id}", [$this, 'save']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function enqueue_scripts()
    {
    }

    /**
     * Adds this tab to the tabs array
     * 
     * @hook ox_settings_tabs_array
     * @param array $tabs the tabs array passed by the fillter
     * @return array $tabs array
     */
    public function add_settings_tab($tabs)
    {
        $tabs[$this->id] = $this->label;
        return $tabs;
    }

    /**
     * Gets tab settins
     * 
     * @return array $settings
     */
    public function get_settings()
    {
        return apply_filters("ox_get_settings_{$this->id}", []);
    }

    /**
     * Gets tab sections if any
     * 
     * @return array $sections sections array
     */
    public function get_sections()
    {
        return apply_filters("ox_get_sections_{$this->id}", []);
    }

    /**
     * Checks if this tab has sections or not
     * 
     * @return bool true if has sections
     */
    public function has_sections()
    {
        return count($this->get_sections()) > 0;
    }

    /**
     * Returns the default section id in case
     * this tab has sections
     * 
     * @return string $default_section_id
     */
    public function get_default_section_id()
    {
        if (is_null($this->default_section_id)) {
            $this->default_section_id = $this->id;
        }

        return $this->default_section_id;
    }

    /**
     * Returns the default section label in case
     * this tab has sections
     * 
     * @return string $default_section_label
     */
    public function get_default_section_label()
    {
        if (is_null($this->default_section_label)) {
            $this->default_section_label = $this->label;
        }

        return $this->default_section_label;
    }

    /**
     * Displays tab sections nav if any
     */
    public function render_sections_nav()
    {
        global $current_section;

        $sections = $this->get_sections();

        if (empty($sections) || 1 === sizeof($sections)) {
            return;
        }

        echo '<ul class="subsubsub mt-0 mb-4">';

        $array_keys = wp_list_pluck($sections, 'id');

        foreach ($sections as $section) {
            if (!$section instanceof Settings_Api) continue;

            if ($section instanceof Settings_Tab) {
                $id    = $section->get_id() == $this->get_id() ? '' : $section->get_id();
                $label = $section->get_default_section_label();
            } else {
                $id    = $section->get_id();
                $label = $section->get_label();
            }

            $class       = ($current_section == $id ? 'current' : '');
            $section_var =  $section->get_id() == $this->get_id() ? '' : "&section=$id";

            echo '<li><a href="' . admin_url('admin.php?page=ox-settings&tab=' . $this->get_id() . $section_var) . '" class="' . $class . '">' . $label . '</a> ' . (end($array_keys) == $id ? '' : '|') . ' </li>';
        }

        echo '</ul><br class="clear" />';
    }

    /**
     * Display tab settings.
     */
    public function render()
    {
        global $current_section;

        if ($this->has_sections() && $current_section != '') {
            foreach ($this->get_sections() as $section) {
                if ($current_section === $section->id) {
                    Admin_Settings::render_fields($section->get_settings());
                    break;
                }
            }
        } else {
            Admin_Settings::render_fields($this->get_settings());
        }
    }

    /**
     * Saves tab and its sections if any
     */
    public function save()
    {
        global $current_section;

        if ($this->has_sections() && $current_section != '') {
            foreach ($this->get_sections() as $section) {
                if (is_array($section)) {
                    Admin_Settings::save_fields($section);
                } elseif ($section instanceof Settings_Api) {
                    if ($current_section === $section->id) {
                        Admin_Settings::save_fields($section->get_settings());
                        break;
                    }
                }
            }
            do_action('ox_update_options_' . $this->id . '_' . $current_section);
        } else {
            Admin_Settings::save_fields($this->get_settings());
        }
    }
}
