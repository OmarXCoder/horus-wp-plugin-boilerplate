<?php

namespace Horus\Admin;

use Horus\Admin\Settings_Tabs\Example_Tab;
use Horus\Admin\Contracts\Settings_Api;
use Horus\Core\Settings;

/**
 * Admin settings class is responsible for displaying and saving plugin settings
 * 
 * @since   1.0.0
 * @package Horus
 * @author  Omar Ali <business@OmarAli.dev>
 */
class Admin_Settings {

    /**
     * Setting pages.
     *
     * @var array
     */
    private static $settings = [];

    /**
     * Error messages.
     *
     * @var array
     */
    private static $errors = [];

    /**
     * Update messages.
     *
     * @var array
     */
    private static $messages = [];

    /**
     * Initializes the admin settings
     */
    public static function init_settings_tabs() {
        if (empty(self::$settings)) {
            $settings = [];

            // Register setting tabs
            $settings[] = new Example_Tab;

            self::$settings = $settings;
        }
    }

    /**
     * Gets the admin settings
     * 
     * @return array $settings
     */
    public static function get_settings_tabs()
    {
        if (empty(self::$settings)) {
            self::init_settings_tabs();
        }

        return apply_filters('ox_get_settings_tabs', self::$settings);
    }

    /**
     * Save the settings.
     */
    public static function save() {
        global $current_tab;

        check_admin_referer('horus-settings');

        // Trigger actions.
        do_action('ox_settings_save_' . $current_tab);
        do_action('ox_update_options_' . $current_tab);
        do_action('ox_update_options');

        self::add_message(__('Your settings have been saved.', 'horus'));

        do_action('ox_settings_saved');
    }

    /**
     * Add a message.
     *
     * @param string $text Message.
     */
    public static function add_message($text) {
        self::$messages[] = $text;
    }

    /**
     * Add an error.
     *
     * @param string $text Message.
     */
    public static function add_error($text) {
        self::$errors[] = $text;
    }

    /**
     * Output messages + errors.
     */
    public static function show_messages() {
        if (count(self::$errors) > 0) {
            foreach (self::$errors as $error) {
                echo '<div id="message" class="error inline"><p><strong>' . esc_html($error) . '</strong></p></div>';
            }
        } elseif (count(self::$messages) > 0) {
            foreach (self::$messages as $message) {
                echo '<div id="message" class="updated inline"><p><strong>' . esc_html($message) . '</strong></p></div>';
            }
        }
    }

    /**
     * Settings page.
     *
     * Handles the display of the main settings page in admin.
     */
    public static function render() {
        global $current_section, $current_tab;

        // Get tabs for the settings page.
        $tabs = apply_filters('ox_settings_tabs_array', []);

        include OX_ADMIN_PATH . 'views/admin-settings.php';
    }


    public static function get_option($option_name, $default) {
        if (!$option_name) {
            return $default;
        }

        $settings = Settings::instance();

        /**
         * Group option
        */
        if (strstr($option_name, '[')) {
            parse_str($option_name, $option_array);
            // Option name is first key.
            $option_name = current(array_keys($option_array));

            // Get value.
            $option_values = $settings->get($option_name);

            $key = key($option_array[$option_name]);

            if (isset($option_values[$key])) {
                $option_value = $option_values[$key];
            } else {
                $option_value = null;
            }
        } else {
            // single option
            $option_value = $settings->get($option_name);
        }

        if (is_array($option_value)) {
            $option_value = array_map('stripslashes', $option_value);
        } elseif (!is_null($option_value)) {
            $option_value = stripslashes($option_value);
        }

        return (null === $option_value) ? $default : $option_value;
    }

    /**
     * Output admin fields.
     *
     * Loops though the horus options array and outputs each field.
     *
     * @param array[] $options Opens array to output.
     */
    public static function render_fields($options) {
        if (!is_array($options)) {
            return;
        }

        foreach ($options as $value) {
            if (! isset($value['type'])) {
                continue;
            }
            if (! isset($value['id'])) {
                $value['id'] = '';
            }
            if (! isset($value['title'])) {
                $value['title'] = isset($value['name']) ? $value['name'] : '';
            }
            if (! isset($value['class'])) {
                $value['class'] = '';
            }
            if (! isset($value['css'])) {
                $value['css'] = '';
            }
            if (! isset($value['default'])) {
                $value['default'] = '';
            }
            if (! isset($value['desc'])) {
                $value['desc'] = '';
            }
            if (! isset($value['desc_tip'])) {
                $value['desc_tip'] = false;
            }
            if (! isset($value['placeholder'])) {
                $value['placeholder'] = '';
            }
            if (! isset($value['suffix'])) {
                $value['suffix'] = '';
            }
            if (! isset($value['value'])) {
                $value['value'] = self::get_option($value['id'], $value['default']);
            }

            // Custom attribute handling.
            $custom_attributes = [];

            if (! empty($value['custom_attributes']) && is_array($value['custom_attributes'])) {
                foreach ($value['custom_attributes'] as $attribute => $attribute_value) {
                    $custom_attributes[] = esc_attr($attribute) . '="' . esc_attr($attribute_value) . '"';
                }
            }

            // Description handling.
            $field_description = self::get_field_description($value);
            $description       = $field_description['description'];
            $tooltip_html      = $field_description['tooltip_html'];

            // Switch based on type.
            switch ($value['type']) {

                // Section Titles.
                case 'title':
                    if (! empty($value['title'])) {
                        echo '<h2>' . esc_html($value['title']) . '</h2>';
                    }
                    if (! empty($value['desc'])) {
                        echo '<div id="' . esc_attr(sanitize_title($value['id'])) . '-description">';
                        echo wp_kses_post(wpautop(wptexturize($value['desc'])));
                        echo '</div>';
                    }
                    echo '<table class="form-table">' . "\n\n";
                    if (! empty($value['id'])) {
                        do_action('ox_settings_' . sanitize_title($value['id']));
                    }
                    break;

                // Section Ends.
                case 'sectionend':
                    if (! empty($value['id'])) {
                        do_action('ox_settings_' . sanitize_title($value['id']) . '_end');
                    }
                    echo '</table>';
                    if (! empty($value['id'])) {
                        do_action('ox_settings_' . sanitize_title($value['id']) . '_after');
                    }
                    break;

                // Standard text inputs and subtypes like 'number'.
                case 'text':
                case 'password':
                case 'datetime':
                case 'datetime-local':
                case 'date':
                case 'month':
                case 'time':
                case 'week':
                case 'number':
                case 'email':
                case 'url':
                case 'tel':
                    $option_value = $value['value'];

                    ?><tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
                        </th>
                        <td class="forminp forminp-<?php echo esc_attr(sanitize_title($value['type'])); ?>">
                            <input
                                name="<?php echo esc_attr($value['id']); ?>"
                                id="<?php echo esc_attr($value['id']); ?>"
                                type="<?php echo esc_attr($value['type']); ?>"
                                style="<?php echo esc_attr($value['css']); ?>"
                                value="<?php echo esc_attr($option_value); ?>"
                                class="<?php echo esc_attr($value['class']); ?>"
                                placeholder="<?php echo esc_attr($value['placeholder']); ?>"
                                <?php echo implode(' ', $custom_attributes); // WPCS: XSS ok. ?>
                                /><?php echo esc_html($value['suffix']); ?> <?php echo $description; // WPCS: XSS ok. ?>
                        </td>
                    </tr>
                    <?php
                    break;

                // Textarea.
                case 'textarea':
                    $option_value = $value['value'];

                    ?>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
                        </th>
                        <td class="forminp forminp-<?php echo esc_attr(sanitize_title($value['type'])); ?>">
                            <?php echo $description; // WPCS: XSS ok. ?>

                            <textarea
                                name="<?php echo esc_attr($value['id']); ?>"
                                id="<?php echo esc_attr($value['id']); ?>"
                                style="<?php echo esc_attr($value['css']); ?>"
                                class="<?php echo esc_attr($value['class']); ?>"
                                placeholder="<?php echo esc_attr($value['placeholder']); ?>"
                                <?php echo implode(' ', $custom_attributes); // WPCS: XSS ok. ?>
                                ><?php echo esc_textarea($option_value); // WPCS: XSS ok. ?></textarea>
                        </td>
                    </tr>
                    <?php
                    break;

                // Select boxes.
                case 'select':
                case 'multiselect':
                    $option_value = $value['value'];

                    ?>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
                        </th>
                        <td class="forminp forminp-<?php echo esc_attr(sanitize_title($value['type'])); ?>">
                            <select
                                name="<?php echo esc_attr($value['id']); ?><?php echo ('multiselect' === $value['type']) ? '[]' : ''; ?>"
                                id="<?php echo esc_attr($value['id']); ?>"
                                style="<?php echo esc_attr($value['css']); ?>"
                                class="<?php echo esc_attr($value['class']); ?>"
                                <?php echo implode(' ', $custom_attributes); // WPCS: XSS ok. ?>
                                <?php echo 'multiselect' === $value['type'] ? 'multiple="multiple"' : ''; ?>
                                >
                                <?php
                                foreach ($value['options'] as $key => $val) {
                                    ?>
                                    <option value="<?php echo esc_attr($key); ?>"
                                        <?php

                                        if (is_array($option_value)) {
                                            selected(in_array((string) $key, $option_value, true), true);
                                        } else {
                                            selected($option_value, (string) $key);
                                        }

                                        ?>
                                    ><?php echo esc_html($val); ?></option>
                                    <?php
                                }
                                ?>
                            </select> <?php echo $description; // WPCS: XSS ok. ?>
                        </td>
                    </tr>
                    <?php
                    break;

                // Radio inputs.
                case 'radio':
                    $option_value = $value['value'];

                    ?>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
                        </th>
                        <td class="forminp forminp-<?php echo esc_attr(sanitize_title($value['type'])); ?>">
                            <fieldset>
                                <?php echo $description; // WPCS: XSS ok. ?>
                                <ul>
                                <?php
                                foreach ($value['options'] as $key => $val) {
                                    ?>
                                    <li>
                                        <label><input
                                            name="<?php echo esc_attr($value['id']); ?>"
                                            value="<?php echo esc_attr($key); ?>"
                                            type="radio"
                                            style="<?php echo esc_attr($value['css']); ?>"
                                            class="<?php echo esc_attr($value['class']); ?>"
                                            <?php echo implode(' ', $custom_attributes); // WPCS: XSS ok. ?>
                                            <?php checked($key, $option_value); ?>
                                            /> <?php echo esc_html($val); ?></label>
                                    </li>
                                    <?php
                                }
                                ?>
                                </ul>
                            </fieldset>
                        </td>
                    </tr>
                    <?php
                    break;

                // Checkbox input.
                case 'checkbox':
                    $option_value     = $value['value'];
                    $visibility_class = [];

                    if (! isset($value['hide_if_checked'])) {
                        $value['hide_if_checked'] = false;
                    }
                    if (! isset($value['show_if_checked'])) {
                        $value['show_if_checked'] = false;
                    }
                    if ('yes' === $value['hide_if_checked'] || 'yes' === $value['show_if_checked']) {
                        $visibility_class[] = 'hidden_option';
                    }
                    if ('option' === $value['hide_if_checked']) {
                        $visibility_class[] = 'hide_options_if_checked';
                    }
                    if ('option' === $value['show_if_checked']) {
                        $visibility_class[] = 'show_options_if_checked';
                    }

                    if (! isset($value['checkboxgroup']) || 'start' === $value['checkboxgroup']) {
                        ?>
                            <tr valign="top" class="<?php echo esc_attr(implode(' ', $visibility_class)); ?>">
                                <th scope="row" class="titledesc"><?php echo esc_html($value['title']); ?></th>
                                <td class="forminp forminp-checkbox">
                                    <fieldset>
                        <?php
                    } else {
                        ?>
                            <fieldset class="<?php echo esc_attr(implode(' ', $visibility_class)); ?>">
                        <?php
                    }

                    if (! empty($value['title'])) {
                        ?>
                            <legend class="screen-reader-text"><span><?php echo esc_html($value['title']); ?></span></legend>
                        <?php
                    }

                    ?>
                        <label for="<?php echo esc_attr($value['id']); ?>">
                            <input
                                name="<?php echo esc_attr($value['id']); ?>"
                                id="<?php echo esc_attr($value['id']); ?>"
                                type="checkbox"
                                class="<?php echo esc_attr(isset($value['class']) ? $value['class'] : ''); ?>"
                                value="1"
                                <?php checked($option_value, 'yes'); ?>
                                <?php echo implode(' ', $custom_attributes); // WPCS: XSS ok. ?>
                            /> <?php echo $description; // WPCS: XSS ok. ?>
                        </label> <?php echo $tooltip_html; // WPCS: XSS ok. ?>
                    <?php

                    if (! isset($value['checkboxgroup']) || 'end' === $value['checkboxgroup']) {
                        ?>
                                    </fieldset>
                                </td>
                            </tr>
                        <?php
                    } else {
                        ?>
                            </fieldset>
                        <?php
                    }
                    break;

                // Single page selects.
                case 'single_select_page':
                    $args = array(
                        'name'             => $value['id'],
                        'id'               => $value['id'],
                        'sort_column'      => 'menu_order',
                        'sort_order'       => 'ASC',
                        'show_option_none' => ' ',
                        'class'            => $value['class'],
                        'echo'             => false,
                        'selected'         => absint($value['value']),
                        'post_status'      => 'publish,private,draft',
                   );

                    if (isset($value['args'])) {
                        $args = wp_parse_args($value['args'], $args);
                    }

                    ?>
                    <tr valign="top" class="single_select_page">
                        <th scope="row" class="titledesc">
                            <label><?php echo esc_html($value['title']); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
                        </th>
                        <td class="forminp">
                            <?php echo str_replace(' id=', " data-placeholder='" . esc_attr__('Select a page&hellip;', 'horus') . "' style='" . $value['css'] . "' class='" . $value['class'] . "' id=", wp_dropdown_pages($args)); // WPCS: XSS ok. ?> <?php echo $description; // WPCS: XSS ok. ?>
                        </td>
                    </tr>
                    <?php
                    break;

                // Default: run an action.
                default:
                    do_action('ox_admin_field_' . $value['type'], $value);
                    break;
            }
        }
    }

    /**
     * Helper function to get the formatted description and tip HTML for a
     * given form field. Plugins can call this when implementing their own custom
     * settings types.
     *
     * @param  array $value The form field value array.
     * @return array The description and tip as a 2 element array.
     */
    public static function get_field_description($value) {
        $description  = '';
        $tooltip_html = '';

        if (true === $value['desc_tip']) {
            $tooltip_html = $value['desc'];
        } elseif (! empty($value['desc_tip'])) {
            $description  = $value['desc'];
            $tooltip_html = $value['desc_tip'];
        } elseif (! empty($value['desc'])) {
            $description = $value['desc'];
        }

        if ($description && in_array($value['type'], array('textarea', 'radio'), true)) {
            $description = '<p style="margin-top:0">' . wp_kses_post($description) . '</p>';
        } elseif ($description && in_array($value['type'], array('checkbox'), true)) {
            $description = wp_kses_post($description);
        } elseif ($description) {
            $description = '<span class="description">' . wp_kses_post($description) . '</span>';
        }

        if ($tooltip_html && in_array($value['type'], array('checkbox'), true)) {
            $tooltip_html = '<p class="description">' . $tooltip_html . '</p>';
        } elseif ($tooltip_html) {
            // $tooltip_html = wc_help_tip($tooltip_html);
            $tooltip_html = '<span class="ox-help-tip" title="' . $tooltip_html . '"></span>';
        }

        return array(
            'description'  => $description,
            'tooltip_html' => $tooltip_html,
       );
    }

    /**
     * Save admin fields.
     *
     * Loops though the horus options array and outputs each field.
     *
     * @param array $options Options array to output.
     * @param array $data    Optional. Data to use for saving. Defaults to $_POST.
     * @return bool
     */
    public static function save_fields($options, $data = null) {
        if (is_null($data)) {
            $data = $_POST; // WPCS: input var okay, CSRF ok.
        }
        if (empty($data)) {
            return false;
        }

        // Options to update will be stored here and saved later.
        $update_options   = [];
        $autoload_options = [];

        // Loop options and get values to save.
        foreach ($options as $option) {
            if (in_array($option['type'], ['title', 'sectionend'])) {
                continue;
            }
            
            if (! isset($option['id']) || ! isset($option['type']) || (isset($option['is_option']) && false === $option['is_option'])) {
                continue;
            }

            // Get posted value.
            if (strstr($option['id'], '[')) { // option_group[option_name]
                parse_str($option['id'], $group);
                $group_name  = current(array_keys($group)); // option_group
                $option_name = key($group[ $group_name ]); // option_name
                $raw_value   = isset($data[ $group_name ][ $option_name ]) ? wp_unslash($data[ $group_name ][ $option_name ]) : null; // option_value
            } else {
                $group_name  = $option['id'];
                $option_name = '';
                $raw_value   = isset($data[ $option['id'] ]) ? wp_unslash($data[ $option['id'] ]) : null;
            }

            // Format the value based on option type.
            switch ($option['type']) {
                case 'checkbox':
                    $value = '1' === $raw_value || 'yes' === $raw_value ? 'yes' : 'no';
                    break;
                case 'textarea':
                    $value = wp_kses_post(trim($raw_value));
                    break;
                case 'multiselect':
                case 'multi_select_countries':
                    $value = array_filter(array_map('esc_attr', (array) $raw_value));
                    break;

                case 'select':
                    $allowed_values = empty($option['options']) ? [] : array_map('strval', array_keys($option['options']));
                    if (empty($option['default']) && empty($allowed_values)) {
                        $value = null;
                        break;
                    }
                    $default = (empty($option['default']) ? $allowed_values[0] : $option['default']);
                    $value   = in_array($raw_value, $allowed_values, true) ? $raw_value : $default;
                    break;
                default:
                    $value = esc_attr($raw_value);
                    break;
            }

            /**
             * Sanitize the value of an option.
             *
             * @since 1.0.0
             */
            $value = apply_filters('ox_admin_settings_sanitize_option', $value, $option, $raw_value);

            /**
             * Sanitize the value of an option by option name.
             *
             * @since 1.0.0
             */
            $value = apply_filters("ox_admin_settings_sanitize_option_$group_name", $value, $option, $raw_value);

            if (is_null($value)) {
                continue;
            }

            $settings = Settings::instance();

            // Check if option is an array and handle that differently to single values.
            if ($group_name && $option_name) {
                if (! isset($update_options[ $group_name ])) {
                    $update_options[$group_name] = $settings->get($group_name);
                }
                if (! is_array($update_options[ $group_name ])) {
                    $update_options[ $group_name ] = [];
                }
                $update_options[ $group_name ][ $option_name ] = $value;
            } else {
                $update_options[ $group_name ] = $value;
            }

            $autoload_options[ $group_name ] = isset($option['autoload']) ? (bool) $option['autoload'] : true;
        }

        // Save all options in our array.
        foreach ($update_options as $name => $value) {
            $settings->set($name, $value, $autoload_options[$name] ? 'yes' : 'no');
        }

        return true;
    }

    public static function add_default_options()
    {
        $tabs = self::get_settings_tabs();
        $settings = Settings::instance();

        if ($settings->get('plugin_default_settings_added', null) === 'yes') {
            return;
        }

        foreach ($tabs as $tab) {
            if (method_exists($tab, 'get_defaults')) {
                $settings->set($tab->get_id(), $tab->get_defaults(), true);
            } elseif (method_exists($tab, 'get_settings')) {
                if ($tab->has_sections()) {
                    foreach ($tab->get_sections() as $section) {
                        if ($section instanceof Settings_Api) {
                            self::_add_options($section->get_settings());
                        } elseif (is_array($section)) {
                            self::_add_options($section);
                        }
                    }
                } else {
                    self::_add_options($tab->get_settings());
                }
            } else {
                continue;
            }
        }

        $settings->set('plugin_default_settings_added', 'yes');
    }

    private static function _add_options($options)
    {
        if (!is_array($options) || count($options) == 0) {
            return;
        }

        $settings         = Settings::instance();
        $update_options   = [];
        $autoload_options = [];

        foreach ($options as $option) {

            if (!isset($option['default']) || !isset($option['id']) || in_array($option['type'], ['title', 'sectionend'])) {
                continue;
            }

            // is a group option
            if (strstr($option['id'], '[')) {
                parse_str($option['id'], $group);
                $group_name = current(array_keys($group));
                $option_name = key($group[$group_name]);
            } else {
                $group_name = $option['id'];
                $option_name = null;
            }

            if ($group_name && $option_name) {
                if (!isset($update_options[$group_name]) || !is_array($update_options[$group_name])) {
                    $update_options[$group_name] = [];
                }
                $update_options[$group_name][$option_name] = $option['default'];
            } else {
                $update_options[$group_name] = $option['default'];
            }

            $autoload_options[$group_name] = isset($option['autoload']) ? (bool) $option['autoload'] : true;
        }

        foreach ($update_options as $name => $value) {
            $settings->set($name, $value, $autoload_options[$name] ? 'yes' : 'no');
        }
    }
}
