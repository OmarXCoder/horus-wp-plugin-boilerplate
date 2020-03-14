<?php

namespace Horus\Core\Contracts;

/**
 * Meta Object works as a meta accessor for data models
 * 
 * @since   1.0.0
 * @package Horus
 * @author  Omar Ali <business@OmarAli.dev>
 */
class Meta_Object
{
    private $meta;

    public function __construct($meta)
    {
        $this->meta = $meta ?: [];
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function get($name)
    {
        if (array_key_exists($name, $this->meta)) {
            return wp_unslash($this->meta[$name]);
        }

        return '';
    }
}
