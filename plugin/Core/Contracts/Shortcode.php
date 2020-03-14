<?php

namespace Horus\Core\Contracts;

defined('ABSPATH') || exit('Forbidden!');


abstract class Shortcode
{
    /**
     * Shortcode attributes
     * @var array $atts
     */
    protected $atts = [];

    /**
     * Defaults attributes
     * @var array $defaults
     */
    protected $defaults = [];

    /**
     * Shortcode content
     * @var string $content
     */
    protected $content;

    public static function instance()
    {
        return new static;
    }

    /**
     * Handles shortcode registration and rendering
     */
    abstract protected function handler($atts, $content = null);

    function prepare_atts()
    {
        $atts     = is_array($this->atts) ? $this->atts : [];
        $defaults = $this->defaults;
        $class    = '';

        if (!empty($defaults['class'])) {
            $class .= " {$defaults['class']}";
        }

        foreach ($atts as $key => $value) {
            if (is_int($key)) {
                if (isset($defaults[$value])) {
                    $atts[$value] = true;
                } else {
                    $class .= " $value";
                }

                unset($atts[$key]);
            }

            if ($value === 'true') {
                $atts[$key] = true;
            }

            if ($value === 'false') {
                $atts[$key] = false;
            }
        }

        if (!empty($atts['class'])) {
            $class .= " {$atts['class']}";
        }

        $defaults['class'] = $class;

        return shortcode_atts($defaults, $atts);
    }
}
