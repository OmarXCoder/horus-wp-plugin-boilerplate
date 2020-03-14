<?php

namespace Horus\Core\Shortcodes;

use Horus\Core\Contracts\Shortcode;

class Shortcode_Example extends Shortcode
{
    public function __construct()
    {
        $this->defaults = [
            'message' => 'This is a shortcode example',
            'color' => 'orange'
        ];
    }

    public function handler($atts, $content = null)
    {
        $this->atts = $atts;
        $this->content = $content;

        ob_start();
        include __DIR__ . '/views/shortcode-example.php';
        return ob_get_clean();
    }
}
