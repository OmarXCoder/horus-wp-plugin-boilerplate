<?php

use Horus\Core\Shortcodes\Shortcode_Example;

defined('ABSPATH') || exit('Forbidden!');

add_shortcode('shortcode_example', [Shortcode_Example::instance(), 'handler']);
