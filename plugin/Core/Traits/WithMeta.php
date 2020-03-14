<?php

namespace Horus\Core\Traits;

use Horus\Core\Contracts\Meta_Object;

trait WithMeta
{
    /**
     * Meta array
     * @var array
     */
    public $meta = [];

    public function getMeta($name = null)
    {
        if (null === $name) {
            return $this->meta;
        }

        if (array_key_exists($name, $this->meta)) {
            return wp_unslash($this->meta[$name]);
        }

        return '';
    }

    public function setMeta($key, $value)
    {
        $this->meta[$key] = $value;
        return $this;
    }

    /**
     * Defines the customer meta data
     * 
     * @param array $meta
     * @return Customer $instance of this class
     */
    public function defineMeta($meta)
    {
        if (is_array($meta)) {
            $this->meta = $meta;
            $this->saveMeta();
        }
        return $this;
    }

    public function saveMeta()
    {
        $meta = json_encode($this->meta);
        $updated = static::db()->update(static::table(), ['meta' => $meta], ['id' => $this->id]);
        return !!$updated;
    }

    public function getMetaObj()
    {
        if (!is_array($this->meta)) {
            if (is_string($this->meta)) {
                $this->meta = json_decode($this->meta);
            } else if (is_null($this->meta)) {
                $this->meta = [];
            }
        }

        return new Meta_Object($this->meta);
    }
}
