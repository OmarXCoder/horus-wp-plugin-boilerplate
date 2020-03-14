<?php

namespace Horus\Admin\Settings_Tabs;

use Horus\Admin\Contracts\Settings_Tab;

/**
 * Example Settings Tab
 * 
 * @since   1.0.0
 * @package Horus
 * @author  Omar Ali <business@OmarAli.dev>
 */
class Example_Tab extends Settings_Tab
{
    public function __construct()
    {
        $this->id    = 'example';
        $this->label = __('Example', 'horus');
        parent::__construct();
    }

    public function get_settings()
    {
        $settings = apply_filters("ox_example_settings", [
            [
                'type'  => 'title',
                'title' => __('Example Settings Tab', 'horus'),
                'desc'  => __('Example settings tab you can use text fileds select elements checkboxes etc.', 'horus'),
                'id'    => 'example_settings',
            ],
            [
                'type'     => 'text',
                'title'    => __('Example Setting', 'horus'),
                'desc'     => __('This is an example setting', 'horus'),
                'desc_tip' => true,
                'class'    => 'settings-input-field sm',
                'id'       => 'example_setting',
                'default'  => 'default value for example setting'
            ],
            [
                'type' => 'sectionend',
                'id'   => 'example_settings',
            ],
        ]);

        return apply_filters("ox_get_settings_{$this->id}", $settings);
    }
}
