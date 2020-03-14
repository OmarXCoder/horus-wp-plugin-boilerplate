<?php

namespace Horus\Admin\Contracts;

/**
 * Settings Api contract
 * 
 * @since   1.0.0
 * @package Horus
 * @author  Omar Ali <business@OmarAli.dev>
 */
abstract class Settings_Api
{
    /**
     * the id of settings tab/section
     * @var string $id
     */
    public $id;

    /**
     * the label of settings tab/section
     * @var string $label
     */
    public $label;

    /**
     * Get settings tab ID.
     *
     * @since 1.0.0
     * @return string
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * Get settings tab Label
     *
     * @since 1.0.0
     * @return string
     */
    public function get_label()
    {
        return !is_null($this->label) ? $this->label : ucfirst($this->id);
    }

    abstract public function get_settings();
}
