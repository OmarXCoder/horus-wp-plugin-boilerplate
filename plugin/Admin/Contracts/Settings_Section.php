<?php

namespace Horus\Admin\Contracts;

use Horus\Admin\Contracts\Settings_Api;

/**
 * Admin settings section
 * 
 * @since   1.0.0
 * @package Horus
 * @author  Omar Ali <business@OmarAli.dev>
 */
class Settings_Section extends Settings_Api
{
    private $settings = [];

    public function __construct($id, $label, $settings)
    {
        $this->id       = $id;
        $this->label    = $label;
        $this->settings = $settings;
    }

    public function get_settings()
    {
        return $this->settings;
    }
}
